<?php

namespace Tests\Feature\Order;

use App\Enums\OrderStatus;
use App\Enums\ProductType;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Category;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\User;
use App\Services\OrderService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Mockery;
use RuntimeException;
use Tests\TestCase;

class CheckoutTest extends TestCase
{
    use RefreshDatabase;

    private Category $category;

    protected function setUp(): void
    {
        parent::setUp();

        $this->category = Category::create([
            'name' => 'Coffee',
            'slug' => 'coffee',
        ]);
    }

    public function test_guest_cannot_checkout(): void
    {
        $this->postJson('/api/checkout')
            ->assertUnauthorized();
    }

    public function test_user_cannot_checkout_empty_cart(): void
    {
        $user = User::factory()->create();

        Sanctum::actingAs($user);

        $this->postJson('/api/checkout')
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['cart']);

        $this->assertDatabaseCount('orders', 0);
        $this->assertDatabaseCount('order_items', 0);
    }

    public function test_user_can_checkout_successfully(): void
    {
        $user = User::factory()->create();
        $product = $this->createProduct(price: 30000, stockQuantity: 10, name: 'Black Coffee');
        $variant = $this->createVariant($product, 'Large', 5000);
        $cart = Cart::create(['user_id' => $user->id]);

        CartItem::create([
            'cart_id' => $cart->id,
            'product_id' => $product->id,
            'product_variant_id' => $variant->id,
            'quantity' => 2,
        ]);

        Sanctum::actingAs($user);

        $response = $this->postJson('/api/checkout');

        $response->assertCreated()
            ->assertJsonPath('data.user_id', $user->id)
            ->assertJsonPath('data.status', OrderStatus::PENDING->value)
            ->assertJsonPath('data.total_amount', '70000.00')
            ->assertJsonCount(1, 'data.items')
            ->assertJsonPath('data.items.0.product_id', $product->id)
            ->assertJsonPath('data.items.0.product_variant_id', $variant->id)
            ->assertJsonPath('data.items.0.product_name', 'Black Coffee')
            ->assertJsonPath('data.items.0.unit_price', '35000.00')
            ->assertJsonPath('data.items.0.quantity', 2)
            ->assertJsonPath('data.items.0.subtotal', '70000.00');

        $this->assertDatabaseHas('orders', [
            'user_id' => $user->id,
            'status' => OrderStatus::PENDING->value,
            'total_amount' => 70000,
        ]);

        $this->assertDatabaseHas('order_items', [
            'product_id' => $product->id,
            'product_variant_id' => $variant->id,
            'product_name' => 'Black Coffee',
            'unit_price' => 35000,
            'quantity' => 2,
            'subtotal' => 70000,
        ]);

        $product->refresh();
        $this->assertSame(8, $product->stock_quantity);
    }

    public function test_checkout_creates_order_items_for_multiple_cart_items(): void
    {
        $user = User::factory()->create();
        $cart = Cart::create(['user_id' => $user->id]);

        $firstProduct = $this->createProduct(price: 30000, stockQuantity: 10, name: 'Latte');
        $secondProduct = $this->createProduct(price: 25000, stockQuantity: 10, name: 'Tea');
        $secondVariant = $this->createVariant($secondProduct, 'Less Ice', 2000);

        CartItem::create([
            'cart_id' => $cart->id,
            'product_id' => $firstProduct->id,
            'quantity' => 1,
        ]);

        CartItem::create([
            'cart_id' => $cart->id,
            'product_id' => $secondProduct->id,
            'product_variant_id' => $secondVariant->id,
            'quantity' => 3,
        ]);

        Sanctum::actingAs($user);

        $response = $this->postJson('/api/checkout');

        $response->assertCreated();

        $order = Order::query()->where('user_id', $user->id)->firstOrFail();

        $this->assertSame('111000.00', $order->total_amount);
        $this->assertDatabaseCount('order_items', 2);

        $this->assertDatabaseHas('order_items', [
            'order_id' => $order->id,
            'product_id' => $firstProduct->id,
            'product_variant_id' => null,
            'product_name' => 'Latte',
            'unit_price' => 30000,
            'quantity' => 1,
            'subtotal' => 30000,
        ]);

        $this->assertDatabaseHas('order_items', [
            'order_id' => $order->id,
            'product_id' => $secondProduct->id,
            'product_variant_id' => $secondVariant->id,
            'product_name' => 'Tea',
            'unit_price' => 27000,
            'quantity' => 3,
            'subtotal' => 81000,
        ]);

        $firstProduct->refresh();
        $secondProduct->refresh();
        $this->assertSame(9, $firstProduct->stock_quantity);
        $this->assertSame(7, $secondProduct->stock_quantity);
    }

    public function test_checkout_snapshots_product_information_even_if_product_changes_later(): void
    {
        $user = User::factory()->create();
        $product = $this->createProduct(price: 40000, stockQuantity: 10, name: 'Mocha');
        $cart = Cart::create(['user_id' => $user->id]);

        CartItem::create([
            'cart_id' => $cart->id,
            'product_id' => $product->id,
            'quantity' => 2,
        ]);

        Sanctum::actingAs($user);

        $this->postJson('/api/checkout')->assertCreated();

        $orderItem = OrderItem::query()->firstOrFail();

        $product->update([
            'name' => 'Mocha Updated',
            'price' => 55000,
        ]);

        $orderItem->refresh();

        $this->assertSame('Mocha', $orderItem->product_name);
        $this->assertSame('40000.00', $orderItem->unit_price);
        $this->assertSame('80000.00', $orderItem->subtotal);
        $this->assertSame(2, $orderItem->quantity);
    }

    public function test_checkout_fails_when_product_is_not_active(): void
    {
        $user = User::factory()->create();
        $product = $this->createProduct(price: 30000, stockQuantity: 10, name: 'Inactive Drink');
        $product->update(['is_active' => false]);
        $cart = Cart::create(['user_id' => $user->id]);

        CartItem::create([
            'cart_id' => $cart->id,
            'product_id' => $product->id,
            'quantity' => 1,
        ]);

        Sanctum::actingAs($user);

        $this->postJson('/api/checkout')
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['product_id']);

        $this->assertDatabaseCount('orders', 0);
        $this->assertDatabaseCount('order_items', 0);

        $product->refresh();
        $this->assertSame(10, $product->stock_quantity);
    }

    public function test_checkout_fails_when_cart_quantity_exceeds_current_stock(): void
    {
        $user = User::factory()->create();
        $product = $this->createProduct(price: 30000, stockQuantity: 1, name: 'Espresso');
        $cart = Cart::create(['user_id' => $user->id]);

        CartItem::create([
            'cart_id' => $cart->id,
            'product_id' => $product->id,
            'quantity' => 2,
        ]);

        Sanctum::actingAs($user);

        $this->postJson('/api/checkout')
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['quantity']);

        $this->assertDatabaseCount('orders', 0);
        $this->assertDatabaseCount('order_items', 0);

        $product->refresh();
        $this->assertSame(1, $product->stock_quantity);
    }

    public function test_checkout_fails_when_aggregate_quantity_exceeds_product_stock(): void
    {
        $user = User::factory()->create();
        $product = $this->createProduct(price: 30000, stockQuantity: 5, name: 'Americano');
        $firstVariant = $this->createVariant($product, 'Hot', 0);
        $secondVariant = $this->createVariant($product, 'Iced', 2000);
        $cart = Cart::create(['user_id' => $user->id]);

        CartItem::create([
            'cart_id' => $cart->id,
            'product_id' => $product->id,
            'product_variant_id' => $firstVariant->id,
            'quantity' => 3,
        ]);

        CartItem::create([
            'cart_id' => $cart->id,
            'product_id' => $product->id,
            'product_variant_id' => $secondVariant->id,
            'quantity' => 3,
        ]);

        Sanctum::actingAs($user);

        $this->postJson('/api/checkout')
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['quantity']);

        $this->assertDatabaseCount('orders', 0);
        $this->assertDatabaseCount('order_items', 0);

        $product->refresh();
        $this->assertSame(5, $product->stock_quantity);
    }

    public function test_checkout_rolls_back_when_order_item_creation_fails(): void
    {
        $user = User::factory()->create();
        $product = $this->createProduct(price: 30000, stockQuantity: 10, name: 'Cappuccino');
        $cart = Cart::create(['user_id' => $user->id]);

        CartItem::create([
            'cart_id' => $cart->id,
            'product_id' => $product->id,
            'quantity' => 1,
        ]);

        $service = Mockery::mock(OrderService::class)->makePartial();
        $service->shouldReceive('checkout')->passthru();
        $service->shouldReceive('createOrderItems')
            ->once()
            ->andThrow(new RuntimeException('Simulated checkout failure.'));
        $this->app->instance(OrderService::class, $service);

        Sanctum::actingAs($user);

        $this->withoutExceptionHandling();

        try {
            $this->postJson('/api/checkout');
            $this->fail('Expected checkout to fail.');
        } catch (RuntimeException $exception) {
            $this->assertSame('Simulated checkout failure.', $exception->getMessage());
        }

        $this->assertDatabaseCount('orders', 0);
        $this->assertDatabaseCount('order_items', 0);

        $product->refresh();
        $this->assertSame(10, $product->stock_quantity);
    }

    private function createProduct(
        int $price,
        int $stockQuantity,
        string $name,
    ): Product {
        return Product::create([
            'category_id' => $this->category->id,
            'name' => $name,
            'slug' => str($name)->slug().'-'.fake()->unique()->numberBetween(1, 9999),
            'type' => ProductType::DRINK,
            'price' => $price,
            'stock_quantity' => $stockQuantity,
            'is_active' => true,
        ]);
    }

    private function createVariant(Product $product, string $name, int $extraPrice): ProductVariant
    {
        return ProductVariant::create([
            'product_id' => $product->id,
            'name' => $name,
            'variant_group' => 'size',
            'extra_price' => $extraPrice,
        ]);
    }
}

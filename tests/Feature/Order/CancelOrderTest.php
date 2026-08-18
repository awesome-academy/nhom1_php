<?php

namespace Tests\Feature\Order;

use App\Enums\OrderStatus;
use App\Enums\ProductType;
use App\Models\Category;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class CancelOrderTest extends TestCase
{
    use RefreshDatabase;

    private Category $category;

    private Product $product;

    protected function setUp(): void
    {
        parent::setUp();

        $this->category = Category::create([
            'name' => 'Coffee',
            'slug' => 'coffee',
        ]);

        $this->product = Product::create([
            'category_id' => $this->category->id,
            'name' => 'Black Coffee',
            'slug' => 'black-coffee',
            'type' => ProductType::DRINK,
            'price' => 20000,
            'stock_quantity' => 10,
            'is_active' => true,
        ]);
    }

    public function test_guest_cannot_cancel_order(): void
    {
        $this->patchJson('/api/orders/1/cancel')
            ->assertUnauthorized();
    }

    public function test_user_can_cancel_pending_order(): void
    {
        $user = User::factory()->create();
        $order = $this->createOrderForUser($user, OrderStatus::PENDING, quantity: 2);

        Sanctum::actingAs($user);

        $this->patchJson("/api/orders/{$order->id}/cancel")
            ->assertOk()
            ->assertJsonPath('data.id', $order->id)
            ->assertJsonPath('data.status', OrderStatus::CANCELLED->value);

        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'status' => OrderStatus::CANCELLED->value,
        ]);

        $this->product->refresh();
        $this->assertSame(12, $this->product->stock_quantity);
    }

    public function test_user_can_cancel_confirmed_order(): void
    {
        $user = User::factory()->create();
        $order = $this->createOrderForUser($user, OrderStatus::CONFIRMED, quantity: 1);

        Sanctum::actingAs($user);

        $this->patchJson("/api/orders/{$order->id}/cancel")
            ->assertOk()
            ->assertJsonPath('data.status', OrderStatus::CANCELLED->value);

        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'status' => OrderStatus::CANCELLED->value,
        ]);

        $this->product->refresh();
        $this->assertSame(11, $this->product->stock_quantity);
    }

    public function test_user_cannot_cancel_cancelled_order(): void
    {
        $user = User::factory()->create();
        $order = $this->createOrderForUser($user, OrderStatus::CANCELLED, quantity: 1);

        Sanctum::actingAs($user);

        $this->patchJson("/api/orders/{$order->id}/cancel")
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['status']);

        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'status' => OrderStatus::CANCELLED->value,
        ]);

        $this->product->refresh();
        $this->assertSame(10, $this->product->stock_quantity);
    }

    public function test_user_cannot_cancel_completed_order(): void
    {
        $user = User::factory()->create();
        $order = $this->createOrderForUser($user, OrderStatus::COMPLETED, quantity: 1);

        Sanctum::actingAs($user);

        $this->patchJson("/api/orders/{$order->id}/cancel")
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['status']);

        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'status' => OrderStatus::COMPLETED->value,
        ]);

        $this->product->refresh();
        $this->assertSame(10, $this->product->stock_quantity);
    }

    public function test_user_cannot_cancel_preparing_order(): void
    {
        $user = User::factory()->create();
        $order = $this->createOrderForUser($user, OrderStatus::PREPARING, quantity: 1);

        Sanctum::actingAs($user);

        $this->patchJson("/api/orders/{$order->id}/cancel")
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['status']);

        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'status' => OrderStatus::PREPARING->value,
        ]);

        $this->product->refresh();
        $this->assertSame(10, $this->product->stock_quantity);
    }

    public function test_cancel_returns_not_found_for_missing_order(): void
    {
        $user = User::factory()->create();

        Sanctum::actingAs($user);

        $this->patchJson('/api/orders/99999/cancel')
            ->assertNotFound()
            ->assertJson([
                'message' => 'Order not found.',
            ]);
    }

    public function test_user_cannot_cancel_another_users_order(): void
    {
        $owner = User::factory()->create();
        $otherUser = User::factory()->create();
        $order = $this->createOrderForUser($owner, OrderStatus::PENDING, quantity: 1);

        Sanctum::actingAs($otherUser);

        $this->patchJson("/api/orders/{$order->id}/cancel")
            ->assertNotFound()
            ->assertJson([
                'message' => 'Order not found.',
            ]);

        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'status' => OrderStatus::PENDING->value,
        ]);

        $this->product->refresh();
        $this->assertSame(10, $this->product->stock_quantity);
    }

    private function createOrderForUser(
        User $user,
        OrderStatus $status,
        int $quantity,
    ): Order {
        $order = Order::create([
            'user_id' => $user->id,
            'status' => $status,
            'total_amount' => $this->product->price * $quantity,
        ]);

        OrderItem::create([
            'order_id' => $order->id,
            'product_id' => $this->product->id,
            'product_name' => $this->product->name,
            'unit_price' => $this->product->price,
            'quantity' => $quantity,
            'subtotal' => $this->product->price * $quantity,
        ]);

        return $order;
    }
}

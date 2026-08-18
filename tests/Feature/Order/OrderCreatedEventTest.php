<?php

namespace Tests\Feature\Order;

use App\Enums\OrderStatus;
use App\Enums\ProductType;
use App\Events\OrderCreated;
use App\Listeners\SendAdminOrderEmail;
use App\Listeners\SendSlackOrderNotification;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use App\Services\OrderService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Laravel\Sanctum\Sanctum;
use Mockery;
use ReflectionClass;
use RuntimeException;
use Tests\TestCase;

class OrderCreatedEventTest extends TestCase
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

    public function test_checkout_successfully_dispatches_event_once_with_correct_order(): void
    {
        Event::fake();

        $user = User::factory()->create();
        $product = $this->createProduct(price: 30000, stockQuantity: 10, name: 'Black Coffee');
        $cart = Cart::create(['user_id' => $user->id]);

        CartItem::create([
            'cart_id' => $cart->id,
            'product_id' => $product->id,
            'quantity' => 2,
        ]);

        Sanctum::actingAs($user);

        $this->postJson('/api/checkout')->assertCreated();

        Event::assertDispatchedTimes(OrderCreated::class, 1);

        Event::assertDispatched(OrderCreated::class, function (OrderCreated $event) use ($user) {
            $order = $event->order;

            return $order->user_id === $user->id
                && $order->status === OrderStatus::PENDING
                && $order->items->count() === 1
                && $order->items->first()->product_name === 'Black Coffee';
        });
    }

    public function test_checkout_fails_on_empty_cart_does_not_dispatch_event(): void
    {
        Event::fake();

        $user = User::factory()->create();
        Cart::create(['user_id' => $user->id]);

        Sanctum::actingAs($user);

        $this->postJson('/api/checkout')->assertUnprocessable();

        Event::assertNotDispatched(OrderCreated::class);
    }

    public function test_checkout_fails_on_validation_does_not_dispatch_event(): void
    {
        Event::fake();

        $user = User::factory()->create();
        $product = $this->createProduct(price: 30000, stockQuantity: 1, name: 'Espresso');
        $cart = Cart::create(['user_id' => $user->id]);

        CartItem::create([
            'cart_id' => $cart->id,
            'product_id' => $product->id,
            'quantity' => 5, // Exceeds stock
        ]);

        Sanctum::actingAs($user);

        $this->postJson('/api/checkout')->assertUnprocessable();

        Event::assertNotDispatched(OrderCreated::class);
    }

    public function test_checkout_rolls_back_transaction_does_not_dispatch_event(): void
    {
        Event::fake();

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

        Event::assertNotDispatched(OrderCreated::class);
    }

    public function test_order_created_event_has_correct_queued_listeners(): void
    {
        Event::fake();

        Event::assertListening(
            OrderCreated::class,
            SendSlackOrderNotification::class
        );

        Event::assertListening(
            OrderCreated::class,
            SendAdminOrderEmail::class
        );

        $slackReflection = new ReflectionClass(SendSlackOrderNotification::class);
        $this->assertTrue($slackReflection->implementsInterface(ShouldQueue::class));

        $emailReflection = new ReflectionClass(SendAdminOrderEmail::class);
        $this->assertTrue($emailReflection->implementsInterface(ShouldQueue::class));
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
}

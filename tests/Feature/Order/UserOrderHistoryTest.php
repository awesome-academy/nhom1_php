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

class UserOrderHistoryTest extends TestCase
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

    public function test_guest_cannot_get_order_history(): void
    {
        $this->getJson('/api/orders')
            ->assertUnauthorized();
    }

    public function test_guest_cannot_get_order_detail(): void
    {
        $this->getJson('/api/orders/1')
            ->assertUnauthorized();
    }

    public function test_user_can_get_own_order_history(): void
    {
        $user = User::factory()->create();
        $order = $this->createOrderForUser($user, OrderStatus::PENDING, 20000);

        Sanctum::actingAs($user);

        $this->getJson('/api/orders')
            ->assertOk()
            ->assertJsonPath('data.0.id', $order->id)
            ->assertJsonPath('data.0.status', OrderStatus::PENDING->value)
            ->assertJsonPath('data.0.total_amount', '20000.00')
            ->assertJsonPath('data.0.item_count', 1)
            ->assertJsonMissingPath('data.0.items');
    }

    public function test_user_only_sees_their_own_orders_in_history(): void
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();

        $ownOrder = $this->createOrderForUser($user, OrderStatus::PENDING, 20000);
        $this->createOrderForUser($otherUser, OrderStatus::COMPLETED, 30000);

        Sanctum::actingAs($user);

        $response = $this->getJson('/api/orders');

        $response->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.id', $ownOrder->id);
    }

    public function test_order_history_is_sorted_newest_first(): void
    {
        $user = User::factory()->create();

        $olderOrder = Order::create([
            'user_id' => $user->id,
            'status' => OrderStatus::PENDING,
            'total_amount' => 10000,
            'created_at' => now()->subDay(),
            'updated_at' => now()->subDay(),
        ]);

        $this->createOrderItem($olderOrder);

        $newerOrder = Order::create([
            'user_id' => $user->id,
            'status' => OrderStatus::CONFIRMED,
            'total_amount' => 20000,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->createOrderItem($newerOrder);

        Sanctum::actingAs($user);

        $this->getJson('/api/orders')
            ->assertOk()
            ->assertJsonPath('data.0.id', $newerOrder->id)
            ->assertJsonPath('data.1.id', $olderOrder->id);
    }

    public function test_user_can_get_own_order_detail_with_items(): void
    {
        $user = User::factory()->create();
        $order = $this->createOrderForUser($user, OrderStatus::COMPLETED, 20000);

        Sanctum::actingAs($user);

        $this->getJson("/api/orders/{$order->id}")
            ->assertOk()
            ->assertJsonPath('data.id', $order->id)
            ->assertJsonPath('data.status', OrderStatus::COMPLETED->value)
            ->assertJsonPath('data.total_amount', '20000.00')
            ->assertJsonCount(1, 'data.items')
            ->assertJsonPath('data.items.0.product_id', $this->product->id)
            ->assertJsonPath('data.items.0.product_name', 'Black Coffee')
            ->assertJsonPath('data.items.0.unit_price', '20000.00')
            ->assertJsonPath('data.items.0.quantity', 1)
            ->assertJsonPath('data.items.0.subtotal', '20000.00');
    }

    public function test_user_cannot_get_another_users_order_detail(): void
    {
        $owner = User::factory()->create();
        $otherUser = User::factory()->create();
        $order = $this->createOrderForUser($owner, OrderStatus::PENDING, 20000);

        Sanctum::actingAs($otherUser);

        $this->getJson("/api/orders/{$order->id}")
            ->assertNotFound()
            ->assertJson([
                'message' => 'Order not found.',
            ]);
    }

    public function test_order_detail_returns_not_found_for_missing_order(): void
    {
        $user = User::factory()->create();

        Sanctum::actingAs($user);

        $this->getJson('/api/orders/99999')
            ->assertNotFound()
            ->assertJson([
                'message' => 'Order not found.',
            ]);
    }

    private function createOrderForUser(
        User $user,
        OrderStatus $status,
        float $totalAmount,
    ): Order {
        $order = Order::create([
            'user_id' => $user->id,
            'status' => $status,
            'total_amount' => $totalAmount,
        ]);

        $this->createOrderItem($order);

        return $order;
    }

    private function createOrderItem(Order $order): OrderItem
    {
        return OrderItem::create([
            'order_id' => $order->id,
            'product_id' => $this->product->id,
            'product_name' => $this->product->name,
            'unit_price' => $this->product->price,
            'quantity' => 1,
            'subtotal' => $this->product->price,
        ]);
    }
}

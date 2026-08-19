<?php

namespace Tests\Feature\Admin;

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

/**
 * 98919 - Feature tests for Admin Order List / Detail / Status Transition APIs.
 */
class AdminOrderTest extends TestCase
{
    use RefreshDatabase;

    private User $adminUser;

    private User $regularUser;

    private Category $category;

    private Product $product;

    protected function setUp(): void
    {
        parent::setUp();

        $this->adminUser = User::factory()->create(['role' => 'admin']);
        $this->regularUser = User::factory()->create(['role' => 'user']);

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
            'stock_quantity' => 20,
            'is_active' => true,
        ]);
    }

    // -------------------------------------------------------------------------
    // Admin Order List (GET /api/admin/orders)
    // -------------------------------------------------------------------------

    public function test_guest_cannot_access_admin_order_list(): void
    {
        $this->getJson('/api/admin/orders')
            ->assertUnauthorized();
    }

    public function test_regular_user_cannot_access_admin_order_list(): void
    {
        Sanctum::actingAs($this->regularUser);

        $this->getJson('/api/admin/orders')
            ->assertForbidden();
    }

    public function test_admin_can_get_all_orders(): void
    {
        $userA = User::factory()->create();
        $userB = User::factory()->create();

        $orderA = $this->createOrderForUser($userA, OrderStatus::PENDING);
        $orderB = $this->createOrderForUser($userB, OrderStatus::CONFIRMED);

        Sanctum::actingAs($this->adminUser);

        $response = $this->getJson('/api/admin/orders');

        $response->assertOk()
            ->assertJsonCount(2, 'data');
    }

    public function test_admin_order_list_sees_orders_from_all_users(): void
    {
        $userA = User::factory()->create();
        $userB = User::factory()->create();

        $this->createOrderForUser($userA, OrderStatus::PENDING);
        $this->createOrderForUser($userB, OrderStatus::CONFIRMED);
        $this->createOrderForUser($userB, OrderStatus::COMPLETED);

        Sanctum::actingAs($this->adminUser);

        $this->getJson('/api/admin/orders')
            ->assertOk()
            ->assertJsonCount(3, 'data');
    }

    public function test_admin_order_list_is_sorted_newest_first(): void
    {
        $user = User::factory()->create();

        $olderOrder = Order::create([
            'user_id' => $user->id,
            'status' => OrderStatus::PENDING,
            'total_amount' => 10000,
            'created_at' => now()->subHour(),
            'updated_at' => now()->subHour(),
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

        Sanctum::actingAs($this->adminUser);

        $this->getJson('/api/admin/orders')
            ->assertOk()
            ->assertJsonPath('data.0.id', $newerOrder->id)
            ->assertJsonPath('data.1.id', $olderOrder->id);
    }

    public function test_admin_order_list_returns_user_info(): void
    {
        $user = User::factory()->create();
        $this->createOrderForUser($user, OrderStatus::PENDING);

        Sanctum::actingAs($this->adminUser);

        $this->getJson('/api/admin/orders')
            ->assertOk()
            ->assertJsonPath('data.0.user.id', $user->id)
            ->assertJsonPath('data.0.user.email', $user->email);
    }

    public function test_admin_order_list_returns_item_count(): void
    {
        $user = User::factory()->create();
        $this->createOrderForUser($user, OrderStatus::PENDING);

        Sanctum::actingAs($this->adminUser);

        $this->getJson('/api/admin/orders')
            ->assertOk()
            ->assertJsonPath('data.0.item_count', 1);
    }

    public function test_admin_order_list_supports_pagination(): void
    {
        $user = User::factory()->create();

        for ($i = 0; $i < 5; $i++) {
            $this->createOrderForUser($user, OrderStatus::PENDING);
        }

        Sanctum::actingAs($this->adminUser);

        $this->getJson('/api/admin/orders?per_page=3')
            ->assertOk()
            ->assertJsonCount(3, 'data')
            ->assertJsonPath('meta.total', 5);
    }

    public function test_admin_can_filter_orders_by_status(): void
    {
        $user = User::factory()->create();

        $this->createOrderForUser($user, OrderStatus::PENDING);
        $this->createOrderForUser($user, OrderStatus::PENDING);
        $this->createOrderForUser($user, OrderStatus::CONFIRMED);

        Sanctum::actingAs($this->adminUser);

        $this->getJson('/api/admin/orders?status=pending')
            ->assertOk()
            ->assertJsonCount(2, 'data');
    }

    // -------------------------------------------------------------------------
    // Admin Order Detail (GET /api/admin/orders/{id})
    // -------------------------------------------------------------------------

    public function test_guest_cannot_access_admin_order_detail(): void
    {
        $this->getJson('/api/admin/orders/1')
            ->assertUnauthorized();
    }

    public function test_regular_user_cannot_access_admin_order_detail(): void
    {
        $order = $this->createOrderForUser($this->regularUser, OrderStatus::PENDING);

        Sanctum::actingAs($this->regularUser);

        $this->getJson("/api/admin/orders/{$order->id}")
            ->assertForbidden();
    }

    public function test_admin_can_get_order_detail_for_any_user(): void
    {
        $user = User::factory()->create();
        $order = $this->createOrderForUser($user, OrderStatus::CONFIRMED);

        Sanctum::actingAs($this->adminUser);

        $this->getJson("/api/admin/orders/{$order->id}")
            ->assertOk()
            ->assertJsonPath('data.id', $order->id)
            ->assertJsonPath('data.status', OrderStatus::CONFIRMED->value)
            ->assertJsonPath('data.user.id', $user->id)
            ->assertJsonCount(1, 'data.items');
    }

    public function test_admin_order_detail_includes_items(): void
    {
        $user = User::factory()->create();
        $order = $this->createOrderForUser($user, OrderStatus::PENDING);

        Sanctum::actingAs($this->adminUser);

        $this->getJson("/api/admin/orders/{$order->id}")
            ->assertOk()
            ->assertJsonPath('data.items.0.product_id', $this->product->id)
            ->assertJsonPath('data.items.0.product_name', $this->product->name)
            ->assertJsonPath('data.items.0.unit_price', '20000.00')
            ->assertJsonPath('data.items.0.quantity', 1)
            ->assertJsonPath('data.items.0.subtotal', '20000.00');
    }

    public function test_admin_order_detail_returns_404_for_missing_order(): void
    {
        Sanctum::actingAs($this->adminUser);

        $this->getJson('/api/admin/orders/99999')
            ->assertNotFound()
            ->assertJson(['message' => 'Order not found.']);
    }

    // -------------------------------------------------------------------------
    // Admin Status Transition (PATCH /api/admin/orders/{id}/status)
    // -------------------------------------------------------------------------

    public function test_guest_cannot_update_order_status(): void
    {
        $this->patchJson('/api/admin/orders/1/status', ['status' => 'confirmed'])
            ->assertUnauthorized();
    }

    public function test_regular_user_cannot_update_order_status(): void
    {
        $order = $this->createOrderForUser($this->regularUser, OrderStatus::PENDING);

        Sanctum::actingAs($this->regularUser);

        $this->patchJson("/api/admin/orders/{$order->id}/status", ['status' => 'confirmed'])
            ->assertForbidden();
    }

    public function test_admin_can_transition_pending_to_confirmed(): void
    {
        $user = User::factory()->create();
        $order = $this->createOrderForUser($user, OrderStatus::PENDING);

        Sanctum::actingAs($this->adminUser);

        $this->patchJson("/api/admin/orders/{$order->id}/status", ['status' => 'confirmed'])
            ->assertOk()
            ->assertJsonPath('data.id', $order->id)
            ->assertJsonPath('data.status', OrderStatus::CONFIRMED->value);

        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'status' => OrderStatus::CONFIRMED->value,
        ]);
    }

    public function test_admin_can_transition_confirmed_to_preparing(): void
    {
        $user = User::factory()->create();
        $order = $this->createOrderForUser($user, OrderStatus::CONFIRMED);

        Sanctum::actingAs($this->adminUser);

        $this->patchJson("/api/admin/orders/{$order->id}/status", ['status' => 'preparing'])
            ->assertOk()
            ->assertJsonPath('data.status', OrderStatus::PREPARING->value);
    }

    public function test_admin_can_transition_preparing_to_completed(): void
    {
        $user = User::factory()->create();
        $order = $this->createOrderForUser($user, OrderStatus::PREPARING);

        Sanctum::actingAs($this->adminUser);

        $this->patchJson("/api/admin/orders/{$order->id}/status", ['status' => 'completed'])
            ->assertOk()
            ->assertJsonPath('data.status', OrderStatus::COMPLETED->value);
    }

    public function test_admin_can_cancel_pending_order_and_stock_is_restored(): void
    {
        $user = User::factory()->create();
        $order = $this->createOrderForUser($user, OrderStatus::PENDING, quantity: 3);

        $stockBefore = $this->product->stock_quantity;

        Sanctum::actingAs($this->adminUser);

        $this->patchJson("/api/admin/orders/{$order->id}/status", ['status' => 'cancelled'])
            ->assertOk()
            ->assertJsonPath('data.status', OrderStatus::CANCELLED->value);

        $this->product->refresh();
        $this->assertSame($stockBefore + 3, $this->product->stock_quantity);
    }

    public function test_admin_can_cancel_confirmed_order_and_stock_is_restored(): void
    {
        $user = User::factory()->create();
        $order = $this->createOrderForUser($user, OrderStatus::CONFIRMED, quantity: 2);

        $stockBefore = $this->product->stock_quantity;

        Sanctum::actingAs($this->adminUser);

        $this->patchJson("/api/admin/orders/{$order->id}/status", ['status' => 'cancelled'])
            ->assertOk()
            ->assertJsonPath('data.status', OrderStatus::CANCELLED->value);

        $this->product->refresh();
        $this->assertSame($stockBefore + 2, $this->product->stock_quantity);
    }

    public function test_admin_cannot_transition_completed_order(): void
    {
        $user = User::factory()->create();
        $order = $this->createOrderForUser($user, OrderStatus::COMPLETED);

        Sanctum::actingAs($this->adminUser);

        $this->patchJson("/api/admin/orders/{$order->id}/status", ['status' => 'confirmed'])
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['status']);

        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'status' => OrderStatus::COMPLETED->value,
        ]);
    }

    public function test_admin_cannot_transition_cancelled_order(): void
    {
        $user = User::factory()->create();
        $order = $this->createOrderForUser($user, OrderStatus::CANCELLED);

        Sanctum::actingAs($this->adminUser);

        $this->patchJson("/api/admin/orders/{$order->id}/status", ['status' => 'pending'])
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['status']);
    }

    public function test_admin_cannot_skip_steps_in_transition(): void
    {
        $user = User::factory()->create();
        $order = $this->createOrderForUser($user, OrderStatus::PENDING);

        Sanctum::actingAs($this->adminUser);

        // PENDING → PREPARING trực tiếp không được phép
        $this->patchJson("/api/admin/orders/{$order->id}/status", ['status' => 'preparing'])
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['status']);
    }

    public function test_admin_status_update_returns_404_for_missing_order(): void
    {
        Sanctum::actingAs($this->adminUser);

        $this->patchJson('/api/admin/orders/99999/status', ['status' => 'confirmed'])
            ->assertNotFound()
            ->assertJson(['message' => 'Order not found.']);
    }

    public function test_admin_status_update_rejects_invalid_status_value(): void
    {
        $user = User::factory()->create();
        $order = $this->createOrderForUser($user, OrderStatus::PENDING);

        Sanctum::actingAs($this->adminUser);

        $this->patchJson("/api/admin/orders/{$order->id}/status", ['status' => 'flying'])
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['status']);
    }

    public function test_admin_status_update_rejects_missing_status_field(): void
    {
        $user = User::factory()->create();
        $order = $this->createOrderForUser($user, OrderStatus::PENDING);

        Sanctum::actingAs($this->adminUser);

        $this->patchJson("/api/admin/orders/{$order->id}/status", [])
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['status']);
    }

    // -------------------------------------------------------------------------
    // Helpers
    // -------------------------------------------------------------------------

    private function createOrderForUser(
        User $user,
        OrderStatus $status,
        int $quantity = 1,
    ): Order {
        $order = Order::create([
            'user_id' => $user->id,
            'status' => $status,
            'total_amount' => $this->product->price * $quantity,
        ]);

        $this->createOrderItem($order, $quantity);

        return $order;
    }

    private function createOrderItem(Order $order, int $quantity = 1): OrderItem
    {
        return OrderItem::create([
            'order_id' => $order->id,
            'product_id' => $this->product->id,
            'product_name' => $this->product->name,
            'unit_price' => $this->product->price,
            'quantity' => $quantity,
            'subtotal' => $this->product->price * $quantity,
        ]);
    }
}

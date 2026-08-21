<?php

namespace Tests\Feature\Integration;

use App\Enums\OrderStatus;
use App\Enums\ProductType;
use App\Listeners\SendAdminOrderEmail;
use App\Listeners\SendSlackOrderNotification;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Category;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Rating;
use App\Models\Suggestion;
use App\Models\User;
use Illuminate\Events\CallQueuedListener;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Queue;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class EngagementNotificationIntegrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_verified_buyer_can_create_update_and_delete_own_rating(): void
    {
        $user = User::factory()->create();
        $product = $this->createProduct('Rating Coffee');

        $order = Order::create([
            'user_id' => $user->id,
            'status' => OrderStatus::COMPLETED,
            'total_amount' => $product->price,
        ]);

        OrderItem::create([
            'order_id' => $order->id,
            'product_id' => $product->id,
            'product_name' => $product->name,
            'unit_price' => $product->price,
            'quantity' => 1,
            'subtotal' => $product->price,
        ]);

        Sanctum::actingAs($user);

        $this->postJson("/api/products/{$product->id}/ratings", [
            'rating' => 4,
            'comment' => 'Great first cup.',
        ])->assertCreated();

        $rating = Rating::query()->firstOrFail();

        $this->assertDatabaseHas('ratings', [
            'id' => $rating->id,
            'user_id' => $user->id,
            'product_id' => $product->id,
            'rating' => 4,
            'comment' => 'Great first cup.',
        ]);

        $this->putJson("/api/ratings/{$rating->id}", [
            'rating' => 5,
            'comment' => 'Excellent after another visit.',
        ])->assertOk()
            ->assertJsonPath('rating', 5)
            ->assertJsonPath('comment', 'Excellent after another visit.');

        $this->assertDatabaseHas('ratings', [
            'id' => $rating->id,
            'user_id' => $user->id,
            'product_id' => $product->id,
            'rating' => 5,
            'comment' => 'Excellent after another visit.',
        ]);

        $this->deleteJson("/api/ratings/{$rating->id}")->assertNoContent();

        $this->assertDatabaseMissing('ratings', ['id' => $rating->id]);
    }

    public function test_suggestion_flows_from_user_submission_through_admin_review_to_user_history(): void
    {
        $user = User::factory()->create(['role' => 'user']);
        $admin = User::factory()->create(['role' => 'admin']);

        Sanctum::actingAs($user);

        $this->postJson('/api/suggestions', [
            'content' => 'Please add a decaf oat-milk latte.',
        ])->assertCreated()
            ->assertJsonPath('content', 'Please add a decaf oat-milk latte.')
            ->assertJsonPath('status', 'pending');

        $suggestion = Suggestion::query()->firstOrFail();

        $this->assertDatabaseHas('suggestions', [
            'id' => $suggestion->id,
            'user_id' => $user->id,
            'content' => 'Please add a decaf oat-milk latte.',
            'status' => 'pending',
            'reviewed_by' => null,
        ]);

        Sanctum::actingAs($admin);

        $this->getJson('/api/admin/suggestions')->assertOk()
            ->assertJsonPath('data.0.id', $suggestion->id);

        $this->putJson("/api/admin/suggestions/{$suggestion->id}", [
            'status' => 'reviewed',
            'admin_note' => 'The menu team will consider it.',
        ])->assertOk()
            ->assertJsonPath('status', 'reviewed')
            ->assertJsonPath('reviewer.id', $admin->id);

        $this->assertDatabaseHas('suggestions', [
            'id' => $suggestion->id,
            'status' => 'reviewed',
            'admin_note' => 'The menu team will consider it.',
            'reviewed_by' => $admin->id,
        ]);

        Sanctum::actingAs($user);

        $this->getJson('/api/suggestions/me')->assertOk()
            ->assertJsonFragment([
                'id' => $suggestion->id,
                'status' => 'reviewed',
            ]);
    }

    public function test_successful_checkout_queues_slack_and_admin_email_listeners(): void
    {
        Queue::fake();

        $user = User::factory()->create();
        $product = $this->createProduct('Notification Coffee', price: 30000, stockQuantity: 10);
        $variant = ProductVariant::create([
            'product_id' => $product->id,
            'name' => 'Large',
            'variant_group' => 'size',
            'extra_price' => 5000,
        ]);
        $cart = Cart::create(['user_id' => $user->id]);

        CartItem::create([
            'cart_id' => $cart->id,
            'product_id' => $product->id,
            'product_variant_id' => $variant->id,
            'quantity' => 2,
        ]);

        Sanctum::actingAs($user);

        $this->postJson('/api/checkout')->assertCreated()
            ->assertJsonPath('data.user_id', $user->id)
            ->assertJsonPath('data.status', OrderStatus::PENDING->value);

        $this->assertDatabaseHas('orders', [
            'user_id' => $user->id,
            'status' => OrderStatus::PENDING->value,
            'total_amount' => 70000,
        ]);

        Queue::assertPushed(
            CallQueuedListener::class,
            fn (CallQueuedListener $job): bool => $job->class === SendSlackOrderNotification::class,
        );

        Queue::assertPushed(
            CallQueuedListener::class,
            fn (CallQueuedListener $job): bool => $job->class === SendAdminOrderEmail::class,
        );
    }

    private function createProduct(
        string $name,
        int $price = 20000,
        int $stockQuantity = 10,
    ): Product {
        $category = Category::factory()->create();

        return Product::create([
            'category_id' => $category->id,
            'name' => $name,
            'slug' => str($name)->slug().'-'.fake()->unique()->numberBetween(1, 9999),
            'type' => ProductType::DRINK,
            'price' => $price,
            'stock_quantity' => $stockQuantity,
            'is_active' => true,
        ]);
    }
}

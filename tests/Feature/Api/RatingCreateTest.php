<?php

namespace Tests\Feature\Api;

use App\Enums\OrderStatus;
use App\Enums\ProductType;
use App\Models\Category;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Rating;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class RatingCreateTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    private Product $product;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();

        $category = Category::create([
            'name' => 'Coffee',
            'slug' => 'coffee',
        ]);

        $this->product = Product::create([
            'category_id' => $category->id,
            'name' => 'Black Coffee',
            'slug' => 'black-coffee',
            'type' => ProductType::DRINK,
            'price' => 20000,
            'stock_quantity' => 10,
            'is_active' => true,
        ]);
    }

    private function createOrderForUser(User $user, Product $product, OrderStatus $status): Order
    {
        $order = Order::create([
            'user_id' => $user->id,
            'status' => $status,
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

        return $order;
    }

    public function test_unauthenticated_user_cannot_create_rating(): void
    {
        $response = $this->postJson("/api/products/{$this->product->id}/ratings", [
            'rating' => 5,
        ]);

        $response->assertUnauthorized();
    }

    public function test_user_can_create_rating_for_purchased_product(): void
    {
        $this->createOrderForUser($this->user, $this->product, OrderStatus::COMPLETED);
        Sanctum::actingAs($this->user);

        $response = $this->postJson("/api/products/{$this->product->id}/ratings", [
            'rating' => 4,
            'comment' => 'Great product!',
        ]);

        $response->assertCreated();
        $this->assertDatabaseHas('ratings', [
            'user_id' => $this->user->id,
            'product_id' => $this->product->id,
            'rating' => 4,
            'comment' => 'Great product!',
        ]);
    }

    public function test_comment_is_nullable(): void
    {
        $this->createOrderForUser($this->user, $this->product, OrderStatus::COMPLETED);
        Sanctum::actingAs($this->user);

        $response = $this->postJson("/api/products/{$this->product->id}/ratings", [
            'rating' => 5,
        ]);

        $response->assertCreated();
        $this->assertDatabaseHas('ratings', [
            'user_id' => $this->user->id,
            'product_id' => $this->product->id,
            'rating' => 5,
            'comment' => null,
        ]);
    }

    public function test_user_cannot_rate_without_purchase(): void
    {
        Sanctum::actingAs($this->user);

        $response = $this->postJson("/api/products/{$this->product->id}/ratings", [
            'rating' => 5,
        ]);

        $response->assertForbidden();
        $this->assertDatabaseMissing('ratings', [
            'user_id' => $this->user->id,
            'product_id' => $this->product->id,
        ]);
    }

    public function test_user_cannot_rate_if_order_belongs_to_another_user(): void
    {
        $anotherUser = User::factory()->create();
        $this->createOrderForUser($anotherUser, $this->product, OrderStatus::COMPLETED);

        Sanctum::actingAs($this->user);

        $response = $this->postJson("/api/products/{$this->product->id}/ratings", [
            'rating' => 5,
        ]);

        $response->assertForbidden();
    }

    public function test_user_cannot_rate_if_order_contains_another_product(): void
    {
        $anotherProduct = Product::create([
            'category_id' => $this->product->category_id,
            'name' => 'Tea',
            'slug' => 'tea',
            'type' => ProductType::DRINK,
            'price' => 15000,
            'stock_quantity' => 10,
            'is_active' => true,
        ]);

        $this->createOrderForUser($this->user, $anotherProduct, OrderStatus::COMPLETED);

        Sanctum::actingAs($this->user);

        $response = $this->postJson("/api/products/{$this->product->id}/ratings", [
            'rating' => 5,
        ]);

        $response->assertForbidden();
    }

    public function test_user_cannot_rate_if_order_is_not_completed(): void
    {
        $statuses = [
            OrderStatus::PENDING,
            OrderStatus::CONFIRMED,
            OrderStatus::PREPARING,
            OrderStatus::CANCELLED,
        ];

        foreach ($statuses as $status) {
            $user = User::factory()->create();
            $this->createOrderForUser($user, $this->product, $status);

            Sanctum::actingAs($user);

            $response = $this->postJson("/api/products/{$this->product->id}/ratings", [
                'rating' => 5,
            ]);

            $response->assertForbidden();
            $this->assertDatabaseMissing('ratings', [
                'user_id' => $user->id,
                'product_id' => $this->product->id,
            ]);
        }
    }

    public function test_rating_validation_errors(): void
    {
        $this->createOrderForUser($this->user, $this->product, OrderStatus::COMPLETED);
        Sanctum::actingAs($this->user);

        // Missing rating
        $this->postJson("/api/products/{$this->product->id}/ratings", [])
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['rating']);

        // Rating < 1
        $this->postJson("/api/products/{$this->product->id}/ratings", ['rating' => 0])
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['rating']);

        // Rating > 5
        $this->postJson("/api/products/{$this->product->id}/ratings", ['rating' => 6])
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['rating']);

        // Rating is not integer
        $this->postJson("/api/products/{$this->product->id}/ratings", ['rating' => 4.5])
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['rating']);
    }

    public function test_user_cannot_rate_product_twice(): void
    {
        $this->createOrderForUser($this->user, $this->product, OrderStatus::COMPLETED);
        Sanctum::actingAs($this->user);

        // First rating
        $this->postJson("/api/products/{$this->product->id}/ratings", [
            'rating' => 4,
        ])->assertCreated();

        // Second rating attempt
        $response = $this->postJson("/api/products/{$this->product->id}/ratings", [
            'rating' => 5,
        ]);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['rating']);

        $this->assertDatabaseCount('ratings', 1);
    }

    public function test_product_not_found(): void
    {
        Sanctum::actingAs($this->user);

        $response = $this->postJson('/api/products/99999/ratings', [
            'rating' => 5,
        ]);

        $response->assertNotFound();
    }

    public function test_payload_spoofing_does_not_work(): void
    {
        $anotherUser = User::factory()->create();
        $anotherProduct = Product::create([
            'category_id' => $this->product->category_id,
            'name' => 'Tea',
            'slug' => 'tea2',
            'type' => ProductType::DRINK,
            'price' => 15000,
            'stock_quantity' => 10,
            'is_active' => true,
        ]);

        $this->createOrderForUser($this->user, $this->product, OrderStatus::COMPLETED);

        Sanctum::actingAs($this->user);

        $response = $this->postJson("/api/products/{$this->product->id}/ratings", [
            'rating' => 5,
            'user_id' => $anotherUser->id,
            'product_id' => $anotherProduct->id,
        ]);

        $response->assertCreated();

        // Verify it used the authenticated user and URL product, ignoring payload spoofing
        $this->assertDatabaseHas('ratings', [
            'user_id' => $this->user->id,
            'product_id' => $this->product->id,
            'rating' => 5,
        ]);

        $this->assertDatabaseMissing('ratings', [
            'user_id' => $anotherUser->id,
        ]);

        $this->assertDatabaseMissing('ratings', [
            'product_id' => $anotherProduct->id,
        ]);
    }
}

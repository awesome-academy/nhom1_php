<?php

namespace Tests\Feature\Api;

use App\Enums\ProductType;
use App\Models\Category;
use App\Models\Product;
use App\Models\Rating;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class RatingUpdateDeleteTest extends TestCase
{
    use RefreshDatabase;

    private User $owner;

    private Product $product;

    private Rating $rating;

    protected function setUp(): void
    {
        parent::setUp();

        $this->owner = User::factory()->create();

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

        $this->rating = Rating::create([
            'user_id' => $this->owner->id,
            'product_id' => $this->product->id,
            'rating' => 4,
            'comment' => 'Original comment',
        ]);
    }

    // UPDATE TESTS

    public function test_guest_cannot_update_rating(): void
    {
        $response = $this->putJson("/api/ratings/{$this->rating->id}", [
            'rating' => 5,
        ]);

        $response->assertUnauthorized();
    }

    public function test_owner_can_update_rating_and_comment(): void
    {
        Sanctum::actingAs($this->owner);

        $response = $this->putJson("/api/ratings/{$this->rating->id}", [
            'rating' => 5,
            'comment' => 'Updated comment',
        ]);

        $response->assertOk()
            ->assertJson([
                'id' => $this->rating->id,
                'rating' => 5,
                'comment' => 'Updated comment',
            ]);

        $this->assertDatabaseHas('ratings', [
            'id' => $this->rating->id,
            'rating' => 5,
            'comment' => 'Updated comment',
        ]);
    }

    public function test_owner_can_update_comment_to_null(): void
    {
        Sanctum::actingAs($this->owner);

        $response = $this->putJson("/api/ratings/{$this->rating->id}", [
            'rating' => 3,
            'comment' => null,
        ]);

        $response->assertOk();

        $this->assertDatabaseHas('ratings', [
            'id' => $this->rating->id,
            'rating' => 3,
            'comment' => null,
        ]);
    }

    public function test_update_requires_rating(): void
    {
        Sanctum::actingAs($this->owner);

        $response = $this->putJson("/api/ratings/{$this->rating->id}", []);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['rating']);
    }

    public function test_update_rating_cannot_be_less_than_1(): void
    {
        Sanctum::actingAs($this->owner);

        $response = $this->putJson("/api/ratings/{$this->rating->id}", [
            'rating' => 0,
        ]);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['rating']);
    }

    public function test_update_rating_cannot_be_greater_than_5(): void
    {
        Sanctum::actingAs($this->owner);

        $response = $this->putJson("/api/ratings/{$this->rating->id}", [
            'rating' => 6,
        ]);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['rating']);
    }

    public function test_update_rating_must_be_integer(): void
    {
        Sanctum::actingAs($this->owner);

        $response = $this->putJson("/api/ratings/{$this->rating->id}", [
            'rating' => 4.5,
        ]);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['rating']);
    }

    public function test_update_comment_must_be_string_or_null(): void
    {
        Sanctum::actingAs($this->owner);

        $response = $this->putJson("/api/ratings/{$this->rating->id}", [
            'rating' => 4,
            'comment' => ['array_is_invalid'],
        ]);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['comment']);
    }

    public function test_other_user_cannot_update_rating(): void
    {
        $otherUser = User::factory()->create();
        Sanctum::actingAs($otherUser);

        $response = $this->putJson("/api/ratings/{$this->rating->id}", [
            'rating' => 5,
        ]);

        $response->assertForbidden();

        $this->assertDatabaseHas('ratings', [
            'id' => $this->rating->id,
            'rating' => 4,
            'comment' => 'Original comment',
        ]);
    }

    public function test_update_payload_spoofing_ignores_user_and_product_ids(): void
    {
        Sanctum::actingAs($this->owner);

        $otherUser = User::factory()->create();

        $otherProduct = Product::create([
            'category_id' => $this->product->category_id,
            'name' => 'Tea',
            'slug' => 'tea',
            'type' => ProductType::DRINK,
            'price' => 15000,
            'stock_quantity' => 10,
            'is_active' => true,
        ]);

        $response = $this->putJson("/api/ratings/{$this->rating->id}", [
            'rating' => 5,
            'user_id' => $otherUser->id,
            'product_id' => $otherProduct->id,
        ]);

        $response->assertOk();

        // Ensure it updated the rating, but did not change ownership or product
        $this->assertDatabaseHas('ratings', [
            'id' => $this->rating->id,
            'rating' => 5,
            'user_id' => $this->owner->id,
            'product_id' => $this->product->id,
        ]);

        $this->assertDatabaseMissing('ratings', [
            'id' => $this->rating->id,
            'user_id' => $otherUser->id,
        ]);
    }

    public function test_update_returns_404_if_rating_does_not_exist(): void
    {
        Sanctum::actingAs($this->owner);

        $response = $this->putJson('/api/ratings/999999', [
            'rating' => 5,
        ]);

        $response->assertNotFound();
    }

    // DELETE TESTS

    public function test_guest_cannot_delete_rating(): void
    {
        $response = $this->deleteJson("/api/ratings/{$this->rating->id}");

        $response->assertUnauthorized();
    }

    public function test_owner_can_delete_rating(): void
    {
        Sanctum::actingAs($this->owner);

        $response = $this->deleteJson("/api/ratings/{$this->rating->id}");

        $response->assertNoContent();

        // Assert response has no body
        $this->assertEmpty($response->getContent());

        $this->assertDatabaseMissing('ratings', [
            'id' => $this->rating->id,
        ]);
    }

    public function test_other_user_cannot_delete_rating(): void
    {
        $otherUser = User::factory()->create();
        Sanctum::actingAs($otherUser);

        $response = $this->deleteJson("/api/ratings/{$this->rating->id}");

        $response->assertForbidden();

        $this->assertDatabaseHas('ratings', [
            'id' => $this->rating->id,
        ]);
    }

    public function test_delete_returns_404_if_rating_does_not_exist(): void
    {
        Sanctum::actingAs($this->owner);

        $response = $this->deleteJson('/api/ratings/999999');

        $response->assertNotFound();
    }
}

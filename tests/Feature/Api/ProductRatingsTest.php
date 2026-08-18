<?php

namespace Tests\Feature\Api;

use App\Enums\ProductType;
use App\Models\Category;
use App\Models\Product;
use App\Models\Rating;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductRatingsTest extends TestCase
{
    use RefreshDatabase;

    private Product $product;

    protected function setUp(): void
    {
        parent::setUp();

        $category = Category::create(['name' => 'Coffee', 'slug' => 'coffee']);
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

    public function test_product_detail_includes_rounded_rating_summary(): void
    {
        Rating::create(['product_id' => $this->product->id, 'user_id' => User::factory()->create()->id, 'rating' => 4]);
        Rating::create(['product_id' => $this->product->id, 'user_id' => User::factory()->create()->id, 'rating' => 5]);
        Rating::create(['product_id' => $this->product->id, 'user_id' => User::factory()->create()->id, 'rating' => 5]);

        $this->getJson("/api/products/{$this->product->id}")
            ->assertOk()
            ->assertJsonPath('data.ratings_avg_rating', 4.7)
            ->assertJsonPath('data.ratings_count', 3);
    }

    public function test_public_ratings_endpoint_returns_newest_ratings_and_safe_reviewer_data(): void
    {
        $olderUser = User::factory()->create(['name' => 'Older', 'avatar' => 'older.jpg']);
        $newerUser = User::factory()->create(['name' => 'Newer', 'avatar' => 'newer.jpg']);

        $older = Rating::create(['product_id' => $this->product->id, 'user_id' => $olderUser->id, 'rating' => 4, 'comment' => 'Older review']);
        $newer = Rating::create(['product_id' => $this->product->id, 'user_id' => $newerUser->id, 'rating' => 5, 'comment' => 'Newer review']);
        $older->forceFill(['created_at' => now()->subMinute()])->save();
        $newer->forceFill(['created_at' => now()])->save();

        $this->getJson("/api/products/{$this->product->id}/ratings")
            ->assertOk()
            ->assertJsonPath('meta.total', 2)
            ->assertJsonPath('message', 'Ratings retrieved successfully.')
            ->assertJsonPath('data.0.id', $newer->id)
            ->assertJsonPath('data.0.user.name', 'Newer')
            ->assertJsonPath('data.0.user.avatar', 'newer.jpg')
            ->assertJsonMissingPath('data.0.user.email')
            ->assertJsonMissingPath('data.0.user.phone')
            ->assertJsonMissingPath('data.0.user.address');
    }
}

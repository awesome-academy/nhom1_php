<?php

namespace Tests\Feature\Cart;

use App\Enums\ProductType;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class RemoveCartItemTest extends TestCase
{
    use RefreshDatabase;

    private function createCartWithItems(User $user, int $itemCount = 2): Cart
    {
        $category = Category::create([
            'name' => 'Coffee',
            'slug' => 'coffee',
        ]);

        $cart = Cart::create(['user_id' => $user->id]);

        for ($i = 1; $i <= $itemCount; $i++) {
            $product = Product::create([
                'category_id' => $category->id,
                'name' => "Drink {$i}",
                'slug' => "drink-{$i}",
                'type' => ProductType::DRINK,
                'price' => 30000 * $i,
                'stock_quantity' => 10,
                'is_active' => true,
            ]);

            CartItem::create([
                'cart_id' => $cart->id,
                'product_id' => $product->id,
                'quantity' => 1,
            ]);
        }

        return $cart->load('items');
    }

    public function test_guest_cannot_remove_cart_item(): void
    {
        $this->deleteJson('/api/cart/items/1')
            ->assertUnauthorized();
    }

    public function test_guest_cannot_clear_cart(): void
    {
        $this->deleteJson('/api/cart')
            ->assertUnauthorized();
    }

    public function test_user_can_remove_one_cart_item(): void
    {
        $user = User::factory()->create();
        $cart = $this->createCartWithItems($user, 2);
        $itemToRemove = $cart->items->first();
        $remainingItem = $cart->items->last();

        Sanctum::actingAs($user);

        $this->deleteJson("/api/cart/items/{$itemToRemove->id}")
            ->assertOk()
            ->assertJson([
                'data' => [
                    'item_count' => 1,
                    'items' => [
                        [
                            'id' => $remainingItem->id,
                            'product_id' => $remainingItem->product_id,
                            'quantity' => 1,
                        ],
                    ],
                ],
            ]);

        $this->assertDatabaseMissing('cart_items', [
            'id' => $itemToRemove->id,
        ]);
    }

    public function test_user_cannot_remove_another_users_cart_item(): void
    {
        $owner = User::factory()->create();
        $otherUser = User::factory()->create();
        $cart = $this->createCartWithItems($owner, 1);
        $item = $cart->items->first();

        Sanctum::actingAs($otherUser);

        $this->deleteJson("/api/cart/items/{$item->id}")
            ->assertNotFound();

        $this->assertDatabaseHas('cart_items', [
            'id' => $item->id,
        ]);
    }

    public function test_user_can_clear_entire_cart(): void
    {
        $user = User::factory()->create();
        $cart = $this->createCartWithItems($user, 2);

        Sanctum::actingAs($user);

        $this->deleteJson('/api/cart')
            ->assertOk()
            ->assertJson([
                'data' => [
                    'id' => $cart->id,
                    'items' => [],
                    'item_count' => 0,
                    'total' => 0,
                ],
            ]);

        $this->assertDatabaseCount('cart_items', 0);
        $this->assertDatabaseHas('carts', [
            'id' => $cart->id,
            'user_id' => $user->id,
        ]);
    }
}

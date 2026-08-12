<?php

namespace App\Services;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;

class CartService
{
    public function getCart(User $user): Cart
    {
        $cart = $user->getOrCreateCart();
        $cart->load(['items.product', 'items.productVariant']);

        return $cart;
    }

    public function addItem(
        User $user,
        int $productId,
        ?int $productVariantId,
        int $quantity = 1
    ): Cart {
        $product = Product::query()->findOrFail($productId);

        if ($productVariantId !== null) {
            $variant = ProductVariant::query()
                ->where('product_id', $product->id)
                ->whereKey($productVariantId)
                ->first();

            if (! $variant) {
                throw ValidationException::withMessages([
                    'product_variant_id' => ['The selected product variant is invalid for this product.'],
                ]);
            }
        }

        $cart = $user->getOrCreateCart();

        $query = CartItem::query()
            ->where('cart_id', $cart->id)
            ->where('product_id', $product->id);

        if ($productVariantId === null) {
            $query->whereNull('product_variant_id');
        } else {
            $query->where('product_variant_id', $productVariantId);
        }

        $item = $query->first();

        if ($item) {
            $item->quantity += $quantity;
            $item->save();
        } else {
            $cart->items()->create([
                'product_id' => $product->id,
                'product_variant_id' => $productVariantId,
                'quantity' => $quantity,
            ]);
        }

        return $this->getCart($user);
    }

    public function updateQuantity(User $user, int $cartItemId, int $quantity): Cart
    {
        $item = $this->findOwnedCartItem($user, $cartItemId);
        $item->quantity = $quantity;
        $item->save();

        return $this->getCart($user);
    }

    public function removeItem(User $user, int $cartItemId): Cart
    {
        $item = $this->findOwnedCartItem($user, $cartItemId);
        $item->delete();

        return $this->getCart($user);
    }

    public function clear(User $user): Cart
    {
        $cart = $user->getOrCreateCart();
        $cart->items()->delete();

        return $this->getCart($user);
    }

    private function findOwnedCartItem(User $user, int $cartItemId): CartItem
    {
        $cart = $user->getOrCreateCart();

        $item = CartItem::query()
            ->where('cart_id', $cart->id)
            ->whereKey($cartItemId)
            ->first();

        if (! $item) {
            throw (new ModelNotFoundException)->setModel(CartItem::class, [$cartItemId]);
        }

        return $item;
    }
}

<?php

namespace App\Services;

use App\Models\Cart;
use App\Models\CartItem;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class CartService
{
    public static function getOrCreateForUser(int $userId): Cart
    {
        return Cart::firstOrCreate(['user_id' => $userId]);
    }

    public static function removeItem(int $userId, int $itemId): Cart
    {
        $cart = self::getOrCreateForUser($userId);

        $item = CartItem::query()
            ->whereKey($itemId)
            ->where('cart_id', $cart->id)
            ->first();

        if (! $item) {
            throw (new ModelNotFoundException())->setModel(CartItem::class, [$itemId]);
        }

        $item->delete();

        $cart->load(['items.product', 'items.productVariant']);

        return $cart;
    }

    public static function clearCart(int $userId): Cart
    {
        $cart = self::getOrCreateForUser($userId);

        $cart->items()->delete();

        $cart->load(['items.product', 'items.productVariant']);

        return $cart;
    }
}

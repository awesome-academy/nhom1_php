<?php

namespace App\Services;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;

class CartService
{
    public static function getOrCreateForUser(int $userId): Cart
    {
        return Cart::firstOrCreate(['user_id' => $userId]);
    }

    public static function addItem(
        int $userId,
        int $productId,
        ?int $variantId,
        int $quantity,
    ): Cart {
        $product = Product::query()->find($productId);

        if (! $product || ! $product->is_active) {
            throw ValidationException::withMessages([
                'product_id' => ['Product is not available.'],
            ]);
        }

        if ($variantId !== null) {
            $variantExists = ProductVariant::query()
                ->where('id', $variantId)
                ->where('product_id', $productId)
                ->exists();

            if (! $variantExists) {
                throw ValidationException::withMessages([
                    'product_variant_id' => ['Variant does not belong to this product.'],
                ]);
            }
        }

        $cart = self::getOrCreateForUser($userId);

        $item = CartItem::query()
            ->where('cart_id', $cart->id)
            ->where('product_id', $productId)
            ->when(
                $variantId,
                fn ($query) => $query->where('product_variant_id', $variantId),
                fn ($query) => $query->whereNull('product_variant_id'),
            )
            ->first();

        $newQuantity = ($item?->quantity ?? 0) + $quantity;

        if ($newQuantity > $product->stock_quantity) {
            throw ValidationException::withMessages([
                'quantity' => ['Not enough stock for this product.'],
            ]);
        }

        if ($item) {
            $item->update(['quantity' => $newQuantity]);
        } else {
            CartItem::create([
                'cart_id' => $cart->id,
                'product_id' => $productId,
                'product_variant_id' => $variantId,
                'quantity' => $quantity,
            ]);
        }

        $cart->load(['items.product', 'items.productVariant']);

        return $cart;
    }
}

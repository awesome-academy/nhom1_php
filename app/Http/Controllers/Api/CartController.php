<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\AddCartItemRequest;
use App\Http\Requests\UpdateCartItemRequest;
use App\Http\Resources\CartResource;
use App\Services\CartService;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function show(Request $request): CartResource
    {
        $cart = CartService::getOrCreateForUser($request->user()->id);

        $cart->load(['items.product', 'items.productVariant']);

        return new CartResource($cart);
    }

    // 98913 - Add product to cart
    public function storeItem(AddCartItemRequest $request): CartResource
    {
        $cart = CartService::addItem(
            userId: $request->user()->id,
            productId: $request->integer('product_id'),
            variantId: $request->filled('product_variant_id')
                ? $request->integer('product_variant_id')
                : null,
            quantity: $request->integer('quantity'),
        );

        return new CartResource($cart);
    }

    // 98914 - Update cart item quantity
    public function updateItem(UpdateCartItemRequest $request, int $id): CartResource
    {
        $cart = CartService::updateItemQuantity(
            userId: $request->user()->id,
            itemId: $id,
            quantity: $request->integer('quantity'),
        );

        return new CartResource($cart);
    }

    // 98915 - Remove cart item
    public function destroyItem(Request $request, int $id): CartResource
    {
        $cart = CartService::removeItem(
            userId: $request->user()->id,
            itemId: $id,
        );

        return new CartResource($cart);
    }

    // 98915 - Clear cart
    public function clear(Request $request): CartResource
    {
        $cart = CartService::clearCart($request->user()->id);

        return new CartResource($cart);
    }
}
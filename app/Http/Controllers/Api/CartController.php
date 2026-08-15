<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
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

    public function destroyItem(Request $request, int $id): CartResource
    {
        $cart = CartService::removeItem(
            userId: $request->user()->id,
            itemId: $id,
        );

        return new CartResource($cart);
    }

    public function clear(Request $request): CartResource
    {
        $cart = CartService::clearCart($request->user()->id);

        return new CartResource($cart);
    }
}

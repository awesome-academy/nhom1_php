<?php

namespace App\Http\Controllers\Api\V1;

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
}

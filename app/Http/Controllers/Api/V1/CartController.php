<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\AddCartItemRequest;
use App\Http\Requests\Api\V1\UpdateCartItemRequest;
use App\Http\Resources\Api\V1\CartResource;
use App\Services\CartService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function __construct(
        private readonly CartService $cartService
    ) {}

    public function show(Request $request): CartResource
    {
        $cart = $this->cartService->getCart($request->user());

        return new CartResource($cart);
    }

    public function storeItem(AddCartItemRequest $request): JsonResponse
    {
        $cart = $this->cartService->addItem(
            $request->user(),
            (int) $request->validated('product_id'),
            $request->validated('product_variant_id') !== null
                ? (int) $request->validated('product_variant_id')
                : null,
            (int) ($request->validated('quantity') ?? 1),
        );

        return (new CartResource($cart))
            ->response()
            ->setStatusCode(201);
    }

    public function updateItem(UpdateCartItemRequest $request, int $cartItem): CartResource
    {
        $cart = $this->cartService->updateQuantity(
            $request->user(),
            $cartItem,
            (int) $request->validated('quantity'),
        );

        return new CartResource($cart);
    }

    public function destroyItem(Request $request, int $cartItem): CartResource
    {
        $cart = $this->cartService->removeItem($request->user(), $cartItem);

        return new CartResource($cart);
    }

    public function clear(Request $request): CartResource
    {
        $cart = $this->cartService->clear($request->user());

        return new CartResource($cart);
    }
}

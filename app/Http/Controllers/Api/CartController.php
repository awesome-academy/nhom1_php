<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\AddCartItemRequest;
use App\Http\Requests\UpdateCartItemRequest;
use App\Http\Resources\CartResource;
use App\Services\CartService;
use Illuminate\Http\Request;
use App\Models\Cart;

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

    protected function loadCart(Request $request): Cart
    {
        return Cart::firstOrCreate(['user_id' => $request->user()->id])
            ->load([
                'items.product.images',       // Thêm eager-load images
                'items.productVariant',
            ]);
    }

    protected function formatCartResponse(Cart $cart): array
    {
        return [
            'id' => $cart->id,
            'user_id' => $cart->user_id,
            'total_price' => (float) $cart->total_price,
            'items_count' => $cart->items->sum('quantity'), // Đếm tổng số lượng items cho Badge
            'items' => $cart->items->map(function ($item) {
                // Lấy ảnh từ product->images hoặc image/thumbnail mặc định
                $primaryImage = $item->product?->images?->first()?->image_path 
                    ?? $item->product?->image 
                    ?? null;

                $imageUrl = $primaryImage 
                    ? (filter_var($primaryImage, FILTER_VALIDATE_URL) ? $primaryImage : asset('storage/' . $primaryImage))
                    : asset('images/default-food.png');

                return [
                    'id' => $item->id,
                    'product_id' => $item->product_id,
                    'product_variant_id' => $item->product_variant_id,
                    'quantity' => (int) $item->quantity,
                    'unit_price' => (float) $item->unit_price,
                    'total_price' => (float) ($item->quantity * $item->unit_price),
                    'product' => [
                        'id' => $item->product?->id,
                        'name' => $item->product?->name,
                        'slug' => $item->product?->slug,
                        'image_url' => $imageUrl, // Luôn trả về URL ảnh hợp lệ
                    ],
                    'variant' => $item->productVariant ? [
                        'id' => $item->productVariant->id,
                        'name' => $item->productVariant->name,
                        'price' => (float) $item->productVariant->price,
                    ] : null,
                ];
            }),
        ];
    }
}
<?php

namespace App\Services;

use App\Enums\OrderStatus;
use App\Events\OrderCreated;
use App\Models\CartItem;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class OrderService
{
    public function checkout(int $userId): Order
    {
        $order = DB::transaction(function () use ($userId): Order {
            $cart = CartService::getOrCreateForUser($userId);
            $cart->load(['items.product', 'items.productVariant']);

            if ($cart->items->isEmpty()) {
                throw ValidationException::withMessages([
                    'cart' => ['Cart is empty.'],
                ]);
            }

            $this->validateCartItems($cart->items);

            $order = Order::create([
                'user_id' => $userId,
                'status' => OrderStatus::PENDING,
                'total_amount' => round($cart->total(), 2),
            ]);

            $this->createOrderItems($order, $cart->items);

            $this->deductStock($cart->items);

            return $order->load('items');
        });

        OrderCreated::dispatch($order);

        return $order;
    }

    public function createOrderItems(Order $order, Collection $cartItems): void
    {
        foreach ($cartItems as $cartItem) {
            $unitPrice = $this->resolveUnitPrice($cartItem->product, $cartItem->productVariant);

            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $cartItem->product_id,
                'product_variant_id' => $cartItem->product_variant_id,
                'product_name' => $cartItem->product->name,
                'unit_price' => round($unitPrice, 2),
                'quantity' => $cartItem->quantity,
                'subtotal' => round($unitPrice * $cartItem->quantity, 2),
            ]);
        }
    }

    private function validateCartItems(Collection $cartItems): void
    {
        foreach ($cartItems as $cartItem) {
            $product = $cartItem->product;

            if (! $product instanceof Product || ! $product->is_active) {
                throw ValidationException::withMessages([
                    'product_id' => ['Product is not available.'],
                ]);
            }

            if ($cartItem->product_variant_id !== null) {
                $variant = $cartItem->productVariant;

                if (! $variant instanceof ProductVariant || $variant->product_id !== $product->id) {
                    throw ValidationException::withMessages([
                        'product_variant_id' => ['Variant does not belong to this product.'],
                    ]);
                }
            }
        }

        $this->validateStock($cartItems);
    }

    private function validateStock(Collection $cartItems): void
    {
        foreach ($this->quantitiesByProduct($cartItems) as $productId => $totalQuantity) {
            $product = Product::query()->lockForUpdate()->find($productId);

            if (! $product instanceof Product || ! $product->is_active) {
                throw ValidationException::withMessages([
                    'product_id' => ['Product is not available.'],
                ]);
            }

            if ($totalQuantity > $product->stock_quantity) {
                throw ValidationException::withMessages([
                    'quantity' => ['Not enough stock for this product.'],
                ]);
            }
        }
    }

    private function deductStock(Collection $cartItems): void
    {
        foreach ($this->quantitiesByProduct($cartItems) as $productId => $totalQuantity) {
            $product = Product::query()->lockForUpdate()->findOrFail($productId);

            $product->decrement('stock_quantity', $totalQuantity);
        }
    }

    /**
     * @return Collection<int, int>
     */
    private function quantitiesByProduct(Collection $cartItems): Collection
    {
        return $cartItems
            ->groupBy('product_id')
            ->map(fn (Collection $items) => $items->sum(
                fn (CartItem $item) => $item->quantity
            ));
    }

    private function resolveUnitPrice(Product $product, ?ProductVariant $variant): float
    {
        return (float) $product->price + (float) ($variant?->extra_price ?? 0);
    }
}

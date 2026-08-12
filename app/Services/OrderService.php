<?php

namespace App\Services;

use App\Enums\OrderStatus;
use App\Models\Order;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class OrderService
{
    public function checkout(User $user, ?string $note = null): Order
    {
        return DB::transaction(function () use ($user, $note) {
            $cart = $user->getOrCreateCart();
            $cart->load(['items.product', 'items.productVariant']);

            if ($cart->items->isEmpty()) {
                throw ValidationException::withMessages([
                    'cart' => ['Your cart is empty.'],
                ]);
            }

            $total = 0.0;
            $lines = [];

            foreach ($cart->items as $item) {
                $unitPrice = (float) $item->product->price;
                $subtotal = round($unitPrice * $item->quantity, 2);
                $total += $subtotal;

                $productName = $item->product->name;
                if ($item->productVariant) {
                    $productName = mb_substr($productName.' - '.$item->productVariant->name, 0, 150);
                }

                $lines[] = [
                    'product_id' => $item->product_id,
                    'product_variant_id' => $item->product_variant_id,
                    'product_name' => $productName,
                    'unit_price' => $unitPrice,
                    'quantity' => $item->quantity,
                    'subtotal' => $subtotal,
                ];
            }

            $order = $user->orders()->create([
                'status' => OrderStatus::PENDING,
                'total_amount' => round($total, 2),
                'note' => $note,
            ]);

            foreach ($lines as $line) {
                $order->items()->create($line);
            }

            $cart->items()->delete();

            return $order->load(['items.product', 'items.productVariant']);
        });
    }

    public function listForUser(User $user): LengthAwarePaginator
    {
        return $user->orders()
            ->with('items')
            ->latest()
            ->paginate(15);
    }

    public function findForUser(User $user, int $orderId): Order
    {
        $order = $user->orders()
            ->with(['items.product', 'items.productVariant'])
            ->whereKey($orderId)
            ->first();

        if (! $order) {
            throw (new ModelNotFoundException)->setModel(Order::class, [$orderId]);
        }

        return $order;
    }

    public function cancel(User $user, int $orderId): Order
    {
        $order = $this->findForUser($user, $orderId);

        if (! $order->canBeCancelled()) {
            throw ValidationException::withMessages([
                'status' => ['This order cannot be cancelled.'],
            ]);
        }

        $order->status = OrderStatus::CANCELLED;
        $order->save();

        return $order->load(['items.product', 'items.productVariant']);
    }
}

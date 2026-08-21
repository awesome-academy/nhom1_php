<?php

namespace Database\Seeders;

use App\Enums\OrderStatus;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;

class OrderSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::where('role', 'user')->first() ?? User::first();
        $products = Product::with('variants')->where('is_active', true)->get();

        if (!$user || $products->isEmpty()) {
            return;
        }

        $ordersData = [
            [
                'status' => OrderStatus::COMPLETED,
                'note' => 'Giao trước 10h sáng, ít đá giúp mình nhé.',
                'items' => [
                    ['product_index' => 0, 'quantity' => 2, 'use_variant' => true],
                    ['product_index' => 1, 'quantity' => 1, 'use_variant' => false],
                ],
                'created_at' => now()->subDays(3),
            ],
            [
                'status' => OrderStatus::CONFIRMED,
                'note' => 'Giao hàng tận nơi, gọi trước khi đến.',
                'items' => [
                    ['product_index' => 2, 'quantity' => 1, 'use_variant' => true],
                ],
                'created_at' => now()->subDays(1),
            ],
            [
                'status' => OrderStatus::PENDING,
                'note' => 'Giao nhanh giúp mình với ạ.',
                'items' => [
                    ['product_index' => 0, 'quantity' => 1, 'use_variant' => false],
                    ['product_index' => 3, 'quantity' => 2, 'use_variant' => false],
                ],
                'created_at' => now()->subHours(2),
            ],
            [
                'status' => OrderStatus::CANCELLED,
                'note' => 'Đặt nhầm địa chỉ.',
                'items' => [
                    ['product_index' => 1, 'quantity' => 1, 'use_variant' => false],
                ],
                'created_at' => now()->subDays(5),
            ],
        ];

        foreach ($ordersData as $data) {
            $order = Order::create([
                'user_id' => $user->id,
                'status' => $data['status'],
                'total_amount' => 0,
                'note' => $data['note'],
                'created_at' => $data['created_at'],
                'updated_at' => $data['created_at'],
            ]);

            $totalAmount = 0;

            foreach ($data['items'] as $itemData) {
                $product = $products->get($itemData['product_index'] % $products->count());
                $variant = $itemData['use_variant'] ? $product->variants->first() : null;

                $unitPrice = $product->price + ($variant?->extra_price ?? 0);
                $quantity = $itemData['quantity'];
                $subtotal = $unitPrice * $quantity;

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'product_variant_id' => $variant?->id,
                    'product_name' => $product->name . ($variant ? ' (' . $variant->name . ')' : ''),
                    'unit_price' => $unitPrice,
                    'quantity' => $quantity,
                    'subtotal' => $subtotal,
                    'created_at' => $data['created_at'],
                    'updated_at' => $data['created_at'],
                ]);

                $totalAmount += $subtotal;
            }

            $order->update(['total_amount' => $totalAmount]);
        }
    }
}
<?php

namespace Database\Seeders;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;

class CartSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::where('role', 'user')->first() ?? User::first();

        if (!$user) {
            return;
        }

        $cart = Cart::firstOrCreate([
            'user_id' => $user->id,
        ]);

        $cart->items()->delete();

        $drinkProduct = Product::where('type', 'drink')
            ->whereHas('variants')
            ->with(['variants' => fn ($q) => $q->where('variant_group', 'size')])
            ->first();

        if ($drinkProduct) {
            $variant = $drinkProduct->variants->first();

            CartItem::create([
                'cart_id' => $cart->id,
                'product_id' => $drinkProduct->id,
                'product_variant_id' => $variant?->id,
                'quantity' => 2,
            ]);
        }

        $foodProduct = Product::where('type', 'food')
            ->where('id', '!=', $drinkProduct?->id ?? 0)
            ->first();

        if ($foodProduct) {
            CartItem::create([
                'cart_id' => $cart->id,
                'product_id' => $foodProduct->id,
                'product_variant_id' => null,
                'quantity' => 1,
            ]);
        }
    }
}
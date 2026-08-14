<?php

namespace App\Services;

use App\Models\Cart;

class CartService
{
    public static function getOrCreateForUser(int $userId): Cart
    {
        return Cart::firstOrCreate(['user_id' => $userId]);
    }
}

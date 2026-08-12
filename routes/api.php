<?php

use App\Http\Controllers\Api\V1\CartController;
use App\Http\Controllers\Api\V1\OrderController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->middleware('auth:sanctum')->group(function () {
    Route::get('cart', [CartController::class, 'show']);
    Route::post('cart/items', [CartController::class, 'storeItem']);
    Route::put('cart/items/{cartItem}', [CartController::class, 'updateItem']);
    Route::delete('cart/items/{cartItem}', [CartController::class, 'destroyItem']);
    Route::delete('cart', [CartController::class, 'clear']);

    Route::post('orders', [OrderController::class, 'store']);
    Route::get('orders', [OrderController::class, 'index']);
    Route::get('orders/{order}', [OrderController::class, 'show']);
    Route::put('orders/{order}/cancel', [OrderController::class, 'cancel']);
});

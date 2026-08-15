<?php

use App\Http\Controllers\Api\CartController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;

Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function (): void {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/cart', [CartController::class, 'show']);
    Route::delete('/cart/items/{id}', [CartController::class, 'destroyItem']);
    Route::delete('/cart', [CartController::class, 'clear']);
});

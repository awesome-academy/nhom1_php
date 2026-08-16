<?php

use App\Http\Controllers\Api\CartController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\RatingController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\SocialAuthController;

Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function (): void {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('products/{product}/ratings', [RatingController::class, 'store']);
    Route::get('/cart', [CartController::class, 'show']);
});

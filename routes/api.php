<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CartController;
use App\Http\Controllers\Api\RatingController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\RatingController;
use App\Http\Controllers\Api\SuggestionController;
use App\Http\Controllers\Auth\SocialAuthController;
use Illuminate\Support\Facades\Route;

// Auth
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function (): void {
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::post('products/{product}/ratings', [RatingController::class, 'store']);
    Route::put('ratings/{rating}', [RatingController::class, 'update']);
    Route::delete('ratings/{rating}', [RatingController::class, 'destroy']);

    Route::get('/suggestions/me', [SuggestionController::class, 'index']);
    Route::post('/suggestions', [SuggestionController::class, 'store']);

    Route::get('/cart', [CartController::class, 'show']);
    Route::post('/cart/items', [CartController::class, 'storeItem']);
    Route::put('/cart/items/{id}', [CartController::class, 'updateItem']);

    // 98915
    Route::delete('/cart/items/{id}', [CartController::class, 'destroyItem']);
    Route::delete('/cart', [CartController::class, 'clear']);
});

// Categories (Public)
Route::get('/categories', [CategoryController::class, 'index']);
Route::get('/categories/{id}', [CategoryController::class, 'show']);
Route::get('/categories/{id}/products', [CategoryController::class, 'products']);

// Products (Public)
Route::get('/products', [ProductController::class, 'index']);
Route::get('/products/{id}', [ProductController::class, 'show']);

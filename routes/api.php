<?php

use App\Http\Controllers\Admin\ProductImageController;
use App\Http\Controllers\Admin\AdminCategoryController;
use App\Http\Controllers\Admin\SuggestionController as AdminSuggestionController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CartController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\RatingController;
use App\Http\Middleware\AdminMiddleware;
use App\Http\Controllers\Api\SuggestionController;
use Illuminate\Support\Facades\Route;

// Auth
Route::post('/login', [AuthController::class, 'login']);

// Authenticated Routes (User & Admin)
Route::middleware('auth:sanctum')->group(function (): void {
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::post('products/{product}/ratings', [RatingController::class, 'store']);
    Route::put('ratings/{rating}', [RatingController::class, 'update']);
    Route::delete('ratings/{rating}', [RatingController::class, 'destroy']);

    Route::get('/suggestions/me', [SuggestionController::class, 'index']);
    Route::post('/suggestions', [SuggestionController::class, 'store']);

    Route::prefix('admin')->middleware('admin')->group(function (): void {
        Route::get('/suggestions', [AdminSuggestionController::class, 'index']);
        Route::put('/suggestions/{suggestion}', [AdminSuggestionController::class, 'update']);
    });

    Route::get('/cart', [CartController::class, 'show']);
    Route::post('/cart/items', [CartController::class, 'storeItem']);
    Route::put('/cart/items/{id}', [CartController::class, 'updateItem']);

    // 98915
    Route::delete('/cart/items/{id}', [CartController::class, 'destroyItem']);
    Route::delete('/cart', [CartController::class, 'clear']);

    // 98916 - Checkout
    Route::post('/checkout', [OrderController::class, 'checkout']);

    // 98917 - Order history & detail
    Route::get('/orders', [OrderController::class, 'index']);
    Route::get('/orders/{id}', [OrderController::class, 'show']);

    // 98918 - Cancel order
    Route::patch('/orders/{id}/cancel', [OrderController::class, 'cancel']);

    // Admin
    Route::middleware('role')
        ->prefix('admin')
        ->name('api.admin.')
        ->group(function () {
            Route::apiResource('categories', AdminCategoryController::class);
        });
});

// Product gallery management (Admin only)
Route::prefix('admin')->middleware(['auth:sanctum', AdminMiddleware::class])->group(function (): void {
    Route::post('products/{product}/images', [ProductImageController::class, 'store']);
    Route::delete('products/{product}/images/{image}', [ProductImageController::class, 'destroy']);
});

// Categories (Public)
Route::get('/categories', [CategoryController::class, 'index']);
Route::get('/categories/{id}', [CategoryController::class, 'show']);
Route::get('/categories/{id}/products', [CategoryController::class, 'products']);

// Products (Public)
Route::get('/products', [ProductController::class, 'index']);
Route::get('/products/{id}', [ProductController::class, 'show']);
Route::get('/products/{id}/ratings', [ProductController::class, 'ratings']);

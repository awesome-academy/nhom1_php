<?php

use App\Http\Controllers\Admin\ProductImageController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CartController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\RatingController;
use App\Http\Middleware\AdminMiddleware;
use Illuminate\Support\Facades\Route;

// Auth
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function (): void {
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::post('products/{product}/ratings', [RatingController::class, 'store']);

    Route::get('/cart', [CartController::class, 'show']);
    Route::post('/cart/items', [CartController::class, 'storeItem']);
    Route::put('/cart/items/{id}', [CartController::class, 'updateItem']);

    // 98915
    Route::delete('/cart/items/{id}', [CartController::class, 'destroyItem']);
    Route::delete('/cart', [CartController::class, 'clear']);
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

<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CartController;
use App\Http\Controllers\Api\CategoryController;
<<<<<<< HEAD
use App\Http\Controllers\Api\ProductController;
=======
>>>>>>> feat/98902-category-list-detail-tree
use Illuminate\Support\Facades\Route;

// Auth
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function (): void {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/cart', [CartController::class, 'show']);
});

// Categories (Public)
Route::get('/categories',               [CategoryController::class, 'index']);
Route::get('/categories/{id}',          [CategoryController::class, 'show']);
Route::get('/categories/{id}/products', [CategoryController::class, 'products']);
<<<<<<< HEAD

// Products (Public) — Task #98903
// Note: /products/{id}/ratings thuộc Task #98905, không khai báo ở đây
Route::get('/products',      [ProductController::class, 'index']);
Route::get('/products/{id}', [ProductController::class, 'show']);
=======
>>>>>>> feat/98902-category-list-detail-tree

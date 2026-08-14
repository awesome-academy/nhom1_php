<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CartController;
use App\Http\Controllers\Api\CategoryController;
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

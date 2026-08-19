<?php

use App\Http\Controllers\Admin\AdminAuthenticatedSessionController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Admin\AdminCategoryController;
use App\Http\Controllers\Admin\AdminProductController;


use App\Http\Middleware\AdminMiddleware;
use Illuminate\Support\Facades\Route;


/*
| Admin Routes
|
| Admins authenticate through their own dedicated login page (separate
| from the user-facing login form) but share the same "web" guard.
| AdminLoginRequest enforces that only accounts with role = 'admin'
| may pass, and AdminMiddleware protects every route below.
|
*/

Route::prefix('admin')->name('admin.')->group(function () {
    Route::middleware('guest:admin')->group(function () {
        Route::get('login', [AdminAuthenticatedSessionController::class, 'create'])
            ->name('login');

        Route::post('login', [AdminAuthenticatedSessionController::class, 'store']);
    });

    Route::middleware(['auth:admin', AdminMiddleware::class])->group(function () {
        Route::get('dashboard', [DashboardController::class, 'index'])
            ->name('dashboard');

        // User management.
        Route::get('users', [AdminUserController::class, 'index'])
            ->name('users.index');
        Route::get('users/{user}/edit', [AdminUserController::class, 'edit'])
            ->name('users.edit');
        Route::put('users/{user}', [AdminUserController::class, 'update'])
            ->name('users.update');
        Route::delete('users/{user}', [AdminUserController::class, 'destroy'])
            ->name('users.destroy');

        //Category management
        Route::get('categories/manage', [AdminCategoryController::class, 'manage'])
            ->name('categories.manage');
        Route::resource('categories', AdminCategoryController::class)->except(['create', 'edit']);

        //Product management
        Route::resource('products', AdminProductController::class)->except(['show']);
        Route::delete('products/{product}/images/{image}', [AdminProductController::class, 'destroyImage'])
            ->name('products.images.destroy');
        Route::patch('products/{product}/images/{image}/primary', [AdminProductController::class, 'setPrimaryImage'])
            ->name('products.images.primary');

        Route::post('logout', [AdminAuthenticatedSessionController::class, 'destroy'])
            ->name('logout');
    });
});
 
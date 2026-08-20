<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\SocialAuthController;
use App\Http\Controllers\User\ProfileController;
use App\Http\Controllers\User\OrderController;
use App\Http\Controllers\User\MenuController;
use App\Http\Controllers\Api\RatingController;
use App\Http\Controllers\User\CartController;
use App\Http\Controllers\Api\CartController as ApiCartController;


Route::get('/', function () {
    return view('user/welcome');
});

Route::get('/dashboard', function () {
    return view('user.dashboard');
    
})->middleware(['auth', 'verified'])->name('dashboard');

// Menu - public discovery & shopping pages (User Discovery & Shopping)
Route::get('/menu', [MenuController::class, 'index'])->name('menu.index');
Route::get('/menu/{product:slug}', [MenuController::class, 'show'])->name('menu.show');

// Authenticated routes for profile management
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('/profile/avatar', [ProfileController::class, 'updateAvatar'])->name('profile.avatar.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::post('/products/{product}/ratings', [RatingController::class, 'store'])->name('ratings.store');
    Route::put('/ratings/{rating}', [RatingController::class, 'update'])->name('ratings.update');
    Route::delete('/ratings/{rating}', [RatingController::class, 'destroy'])->name('ratings.destroy');

    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::get('/cart/data', [ApiCartController::class, 'show'])->name('cart.data');
    Route::post('/cart/items', [ApiCartController::class, 'storeItem'])->name('cart.items.store');
    Route::put('/cart/items/{id}', [ApiCartController::class, 'updateItem'])->name('cart.items.update');
    Route::delete('/cart/items/{id}', [ApiCartController::class, 'destroyItem'])->name('cart.items.destroy');
    Route::delete('/cart/clear', [ApiCartController::class, 'clear'])->name('cart.clear');

    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
});

Route::prefix('auth')->group(function () {
    Route::get('/{provider}/redirect', [SocialAuthController::class, 'redirectToProvider'])
        ->name('social.redirect');
    
    Route::get('/{provider}/callback', [SocialAuthController::class, 'handleProviderCallback'])
        ->name('social.callback');
});

require __DIR__.'/auth.php';
require __DIR__.'/admin.php';

<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\SocialAuthController;
use App\Http\Controllers\User\ProfileController;
use App\Http\Controllers\User\OrderController;

Route::get('/', function () {
    return view('user/welcome');
});

Route::get('/dashboard', function () {
    return view('user.dashboard');
    
})->middleware(['auth', 'verified'])->name('dashboard');

// Authenticated routes for profile management
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('/profile/avatar', [ProfileController::class, 'updateAvatar'])->name('profile.avatar.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

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

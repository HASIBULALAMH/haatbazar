<?php

use App\Http\Controllers\Buyer\ProfileController;
use App\Http\Controllers\Buyer\Auth\RegisterController;
use App\Http\Controllers\Buyer\Auth\LoginController;
use App\Http\Controllers\Seller\Auth\RegisterController as SellerRegisterController;
use App\Http\Controllers\Seller\Auth\LoginController as SellerLoginController;
use App\Http\Controllers\Admin\Auth\LoginController as AdminLoginController;

use Illuminate\Support\Facades\Route;

// Homepage
Route::get('/', function () {
    return view('welcome');
});

// Buyer routes
Route::prefix('buyer')->name('buyer.')->group(function () {

    // Guest only
    Route::middleware('guest')->group(function () {
        Route::get('/register', [RegisterController::class, 'create'])->name('register');
        Route::post('/register', [RegisterController::class, 'store'])->name('register.store');
        Route::get('/login', [LoginController::class, 'create'])->name('login');
        Route::post('/login', [LoginController::class, 'store'])->name('login.store');
    });

    // Protected
    Route::middleware(['auth', 'buyer'])->group(function () {
        Route::get('/dashboard', fn() => view('buyer.dashboard'))->name('dashboard');
        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('password.update');
        Route::post('/logout', [LoginController::class, 'destroy'])->name('logout');
    });
});


//seller routes
Route::prefix('seller')->name('seller.')->group(function () {


//guest only
    Route::middleware('guest')->group(function () {
        Route::get('/register', [SellerRegisterController::class, 'create'])->name('register');
        Route::post('/register', [SellerRegisterController::class, 'store'])->name('register.store');
        Route::get('/login', [SellerLoginController::class, 'create'])->name('login');
        Route::post('/login', [SellerLoginController::class, 'store'])->name('login.store');
    });

     // Protected
    Route::middleware(['auth', 'seller'])->group(function () {
        Route::get('/dashboard', fn() => view('seller.dashboard'))->name('dashboard');
        Route::post('/logout', [SellerLoginController::class, 'destroy'])->name('logout');
    });
});




// Admin routes
Route::prefix('admin')->name('admin.')->group(function () {

    // Guest only
    Route::middleware('guest')->group(function () {
        Route::get('/login', [AdminLoginController::class, 'create'])->name('login');
        Route::post('/login', [AdminLoginController::class, 'store'])->name('login.store');
    });

    // Protected
    Route::middleware(['auth', 'admin'])->group(function () {
        Route::get('/dashboard', fn() => view('admin.dashboard'))->name('dashboard');
        Route::post('/logout', [AdminLoginController::class, 'destroy'])->name('logout');
    });
});

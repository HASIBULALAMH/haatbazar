<?php

use App\Http\Controllers\Buyer\ProfileController;
use App\Http\Controllers\Buyer\Auth\RegisterController;
use App\Http\Controllers\Buyer\Auth\LoginController;
use App\Http\Controllers\Seller\Auth\RegisterController as SellerRegisterController;
use App\Http\Controllers\Seller\Auth\LoginController as SellerLoginController;
use App\Http\Controllers\Admin\Auth\LoginController as AdminLoginController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Seller\ProductController;
use App\Http\Controllers\Seller\ShopController;
use App\Http\Controllers\Admin\ShopController as AdminShopController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController as PublicProductController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Buyer\AddressController;
use App\Http\Controllers\Seller\OrderController as SellerOrderController;
use App\Http\Controllers\Seller\DashboardController as SellerDashboardController;
use App\Http\Controllers\Seller\ProfileController as SellerProfileController;
use App\Http\Controllers\Admin\ProfileController as AdminProfileController;
use App\Http\Controllers\Buyer\CartController;
use App\Http\Controllers\Buyer\OrderController;

// Public routes
    Route::get('/', [HomeController::class, 'index'])->name('home');
    Route::get('/products', [PublicProductController::class, 'index'])->name('products.index');
    Route::get('/products/{product:slug}', [PublicProductController::class, 'show'])->name('products.show');


    // Buyer routes
Route::prefix('buyer')->name('buyer.')->group(function () {

    // Guest only
    Route::middleware('guest')->group(function () {
        // Authentication
        Route::get('/register', [RegisterController::class, 'create'])->name('register');
        Route::post('/register', [RegisterController::class, 'store'])->name('register.store');
        Route::get('/login', [LoginController::class, 'create'])->name('login');
        Route::post('/login', [LoginController::class, 'store'])->name('login.store');
    });

    // Protected
    Route::middleware(['auth:buyer', 'buyer'])->group(function () {
        // Dashboard
        Route::get('/dashboard', fn() => view('buyer.dashboard'))->name('dashboard');
        // Profile
        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('password.update');
        // Logout
        Route::post('/logout', [LoginController::class, 'destroy'])->name('logout');

        // Addresses
        Route::resource('addresses', AddressController::class)->except(['show']);
        Route::patch('/addresses/{address}/default', [AddressController::class, 'setDefault'])->name('addresses.default');


        // Cart
        Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
        Route::post('/cart/{product}/add', [CartController::class, 'add'])->name('cart.add');
        Route::patch('/cart/{cartItem}/update', [CartController::class, 'update'])->name('cart.update');
        Route::delete('/cart/clear', [CartController::class, 'clear'])->name('cart.clear');
        Route::delete('/cart/{cartItem}/remove', [CartController::class, 'remove'])->name('cart.remove');



        // Orders
        Route::get('/orders/checkout', [OrderController::class, 'checkout'])->name('orders.checkout');
        Route::post('/orders', [OrderController::class, 'store'])->name('orders.store');
        Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
        Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
        Route::patch('/orders/{order}/cancel', [OrderController::class, 'cancel'])->name('orders.cancel');
    });
});


//seller routes
Route::prefix('seller')->name('seller.')->group(function () {


//guest only
    Route::middleware('guest')->group(function () {
        // Authentication
        Route::get('/register', [SellerRegisterController::class, 'create'])->name('register');
        Route::post('/register', [SellerRegisterController::class, 'store'])->name('register.store');
        Route::get('/login', [SellerLoginController::class, 'create'])->name('login');
        Route::post('/login', [SellerLoginController::class, 'store'])->name('login.store');
    });

     // Protected
    Route::middleware(['auth:seller', 'seller'])->group(function () {
        // Dashboard
        Route::get('/dashboard', [SellerDashboardController::class, 'index'])->name('dashboard');
        // Logout
        Route::post('/logout', [SellerLoginController::class, 'destroy'])->name('logout');

        // Shop
        Route::get('/shop/create', [ShopController::class, 'create'])->name('shop.create');
        Route::post('/shop', [ShopController::class, 'store'])->name('shop.store');
        Route::get('/shop/edit', [ShopController::class, 'edit'])->name('shop.edit');
        Route::patch('/shop', [ShopController::class, 'update'])->name('shop.update');

         // Products
        Route::resource('products', ProductController::class);
        Route::delete('/products/images/{image}', [ProductController::class, 'destroyImage'])->name('products.images.destroy');

        // Profile
        Route::get('/profile', [SellerProfileController::class, 'index'])->name('profile.edit');
         Route::patch('/profile', [SellerProfileController::class, 'update'])->name('profile.update');
        Route::put('/profile/password', [SellerProfileController::class, 'updatePassword'])->name('password.update');


        // Seller Orders
        Route::get('/orders', [SellerOrderController::class, 'index'])->name('orders.index');
        Route::get('/orders/{order}', [SellerOrderController::class, 'show'])->name('orders.show');
        Route::patch('/orders/{order}/status', [SellerOrderController::class, 'updateStatus'])->name('orders.status');
    });
});




// Admin routes
Route::prefix('admin')->name('admin.')->group(function () {

        // Authentication
        Route::get('/login', [AdminLoginController::class, 'create'])->name('login');
        Route::post('/login', [AdminLoginController::class, 'store'])->name('login.store');

    // Protected
    Route::middleware(['auth:admin', 'admin'])->group(function () {
        // Dashboard
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
        // Logout
        Route::post('/logout', [AdminLoginController::class, 'destroy'])->name('logout');
        // Categories
        Route::resource('categories', CategoryController::class);

         // Shops
        Route::get('/shops', [AdminShopController::class, 'index'])->name('shops.index');
        Route::patch('/shops/{shop}/approve', [AdminShopController::class, 'approve'])->name('shops.approve');
        Route::patch('/shops/{shop}/reject', [AdminShopController::class, 'reject'])->name('shops.reject');

        // Products
        Route::get('/products', [AdminProductController::class, 'index'])->name('products.index');

        // Users
        Route::get('/users', [UserController::class, 'index'])->name('users.index');
        Route::patch('/users/{user}/toggle', [UserController::class, 'toggle'])->name('users.toggle');

        // Sellers
        Route::get('/sellers', [UserController::class, 'sellers'])->name('sellers.index');
        Route::patch('/sellers/{user}/toggle', [UserController::class, 'sellerToggle'])->name('sellers.toggle');

        // Profile
        Route::get('/profile', [AdminProfileController::class, 'index'])->name('profile');
        Route::patch('/profile', [AdminProfileController::class, 'update'])->name('profile.update');
        Route::put('/profile/password', [AdminProfileController::class, 'updatePassword'])->name('password.update');
    });
});

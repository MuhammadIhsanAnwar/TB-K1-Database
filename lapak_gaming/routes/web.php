<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\PasswordResetController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\Seller\DashboardController as SellerDashboardController;
use App\Http\Controllers\Seller\ProductController as SellerProductController;
use App\Models\Category;
use App\Models\Product;

// Public Routes
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/category/{slug}', [HomeController::class, 'category'])->name('category');
Route::get('/product/{slug}', [HomeController::class, 'showProduct'])->name('product.show');

// Auth Routes
Route::middleware('guest')->group(function () {
    // Register
    Route::get('/register', [RegisterController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);

    // Login
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);

    // Password Reset
    Route::get('/forgot-password', [PasswordResetController::class, 'showForgotForm'])->name('password.forgot');
    Route::post('/forgot-password', [PasswordResetController::class, 'sendReset']);
    Route::get('/reset-password/{token}', [PasswordResetController::class, 'showResetForm'])->name('password.reset');
    Route::post('/reset-password', [PasswordResetController::class, 'reset'])->name('password.update');
});

Route::post('/logout', [LoginController::class, 'logout'])->middleware('auth')->name('logout');

// Email Verification
Route::get('/email/verify/{token}', [RegisterController::class, 'verifyEmail'])->name('email.verify');
Route::get('/email/verify-pending', function() {
    return view('auth.verify');
})->name('email.verification.pending');

// Authenticated Routes
Route::middleware(['auth', 'verified'])->group(function () {
    // Buyer Dashboard
    Route::get('/buyer/dashboard', function() {
        return view('buyer.dashboard');
    })->name('buyer.dashboard');

    Route::get('/checkout/{slug}', [CheckoutController::class, 'checkout'])->name('checkout.confirm');
    Route::post('/checkout/{slug}', [CheckoutController::class, 'process'])->name('checkout.process');

    // Orders
    Route::prefix('orders')->group(function () {
        Route::get('/', function() {
            return view('buyer.orders.index');
        })->name('buyer.orders.index');
        Route::get('/{order}', [OrderController::class, 'show'])->name('order.show');
        Route::post('/{order}/upload-proof', [OrderController::class, 'uploadPaymentProof'])->name('order.upload-proof');
        Route::post('/{order}/confirm-delivery', [OrderController::class, 'confirmDelivery'])->name('order.confirm-delivery');
        Route::post('/{order}/confirm-receipt', [OrderController::class, 'confirmReceipt'])->name('order.confirm-receipt');
        Route::post('/{order}/dispute', [OrderController::class, 'dispute'])->name('order.dispute');
        Route::post('/{order}/review', [OrderController::class, 'submitReview'])->name('order.review');
    });

    // Seller Routes
    Route::prefix('seller')->middleware('verified')->group(function () {
        Route::get('/setup', [SellerDashboardController::class, 'setup'])->name('seller.setup');
        Route::post('/setup', [SellerDashboardController::class, 'completeSetup'])->name('seller.setup.complete');

        Route::middleware('seller')->group(function () {
            Route::get('/dashboard', [SellerDashboardController::class, 'index'])->name('seller.dashboard');

            // Products
            Route::get('/products', [SellerProductController::class, 'index'])->name('seller.products.index');
            Route::get('/products/create', [SellerProductController::class, 'create'])->name('seller.products.create');
            Route::post('/products', [SellerProductController::class, 'store'])->name('seller.products.store');
            Route::get('/products/{product}/edit', [SellerProductController::class, 'edit'])->name('seller.products.edit');
            Route::put('/products/{product}', [SellerProductController::class, 'update'])->name('seller.products.update');
            Route::delete('/products/{product}', [SellerProductController::class, 'delete'])->name('seller.products.delete');
        });
    });

    // Wallet
    Route::get('/wallet', function() {
        return view('buyer.wallet.index');
    })->name('wallet');

    // Profile
    Route::get('/profile', function() {
        return view('profile.edit');
    })->name('profile.edit');
    Route::post('/profile', function() {
        return redirect()->back()->with('success', 'Profile updated');
    })->name('profile.update');
});

// Error Pages
Route::fallback(function () {
    return view('errors.404');
});

// Temporary debug route for diagnosing production home 500
Route::get('/__debug-home', function () {
    try {
        $categories = Category::where('parent_id', null)
            ->where('is_active', true)
            ->with('children')
            ->get();

        $featured = Product::where('is_featured', true)
            ->where('is_active', true)
            ->with(['seller', 'category'])
            ->limit(8)
            ->get();

        $trending = Product::where('is_active', true)
            ->with(['seller', 'category'])
            ->orderByDesc('view_count')
            ->limit(12)
            ->get();

        $newest = Product::where('is_active', true)
            ->with(['seller', 'category'])
            ->latest()
            ->limit(12)
            ->get();

        return view('home', compact('categories', 'featured', 'trending', 'newest'));
    } catch (\Throwable $e) {
        return response()->json([
            'ok' => false,
            'error' => $e->getMessage(),
            'file' => $e->getFile(),
            'line' => $e->getLine(),
        ], 500);
    }
});

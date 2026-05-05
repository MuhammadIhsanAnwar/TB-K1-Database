<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MarketplaceController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\PasswordResetController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\VerificationController;
use App\Http\Controllers\WalletController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\SetupController;
use App\Http\Controllers\MigrationController;
use App\Http\Controllers\ArtisanTerminalController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\Seller\SellerProductController;
use App\Http\Controllers\Seller\SellerOrderController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\SeederController;


Route::prefix('marketplace')->name('marketplace.')->group(function () {
    
    Route::get('/', [MarketplaceController::class, 'home'])->name('home');

    Route::get('/browse', [MarketplaceController::class, 'browse'])->name('browse');

    Route::get('/trending', [MarketplaceController::class, 'trending'])->name('trending');

    Route::get('/deals', [MarketplaceController::class, 'deals'])->name('deals');

    Route::get('/category/{slug}', [MarketplaceController::class, 'category'])->name('category');

});

Route::get('/', [MarketplaceController::class, 'home'])->name('home');
Route::get('/products/type/{type}', [ProductController::class, 'byType'])->name('products.by-type');
Route::get('/products/{product:slug}', [ProductController::class, 'show'])->name('products.show');
Route::get('/browse/search', [ProductController::class, 'search'])->name('products.search');
Route::get('/categories/{category:slug}', [ProductController::class, 'byCategory'])->name('categories.show');

Route::get('/artisan-terminal', [ArtisanTerminalController::class, 'index'])->name('artisan.terminal.index');
Route::post('/artisan-terminal/execute', [ArtisanTerminalController::class, 'executeCommand'])->name('artisan.terminal.execute');
Route::post('/artisan-terminal/quick', [ArtisanTerminalController::class, 'runQuickCommand'])->name('artisan.terminal.quick');

Route::middleware('auth')->group(function (): void {
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart', [CartController::class, 'add'])->name('cart.add');
    Route::patch('/cart/{id}', [CartController::class, 'update'])->name('cart.update');
    Route::delete('/cart/{id}', [CartController::class, 'remove'])->name('cart.remove');
    Route::delete('/cart', [CartController::class, 'clear'])->name('cart.clear');

    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order:order_code}', [OrderController::class, 'show'])->name('orders.show');
    Route::post('/orders/{order:order_code}/pay', [OrderController::class, 'pay'])->name('orders.pay');
    Route::post('/orders/{order:order_code}/complete', [OrderController::class, 'complete'])->name('orders.complete');
    Route::post('/orders/{order:order_code}/cancel', [OrderController::class, 'cancel'])->name('orders.cancel');
    Route::post('/orders/{order:order_code}/proof', [OrderController::class, 'uploadProof'])->name('orders.proof');
});

// Setup page untuk membuat admin pertama kali
Route::get('/setup', [SetupController::class, 'index'])->name('setup.index');
Route::post('/setup/admin', [SetupController::class, 'storeAdmin'])->name('setup.storeAdmin');

// Migration page
Route::get('/setup/migrate', [MigrationController::class, 'index'])->name('setup.migrate');
Route::post('/setup/migrate/run', [MigrationController::class, 'run'])->name('setup.migrate.run');
Route::get('/setup/migrate/status', [MigrationController::class, 'status'])->name('setup.migrate.status');

Route::middleware('guest')->group(function (): void {
    Route::get('/login', [AuthController::class, 'createLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'storeLogin']);
    
    Route::get('/register', [AuthController::class, 'createRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'storeRegister']);
    
    Route::get('/register/seller', [AuthController::class, 'createRegisterSeller'])->name('register.seller');
    Route::post('/register/seller', [AuthController::class, 'storeRegisterSeller'])->name('register.seller.store');
    
    Route::get('/forgot-password', [PasswordResetController::class, 'createLinkRequest'])->name('password.request');
    Route::post('/forgot-password', [PasswordResetController::class, 'storeLinkRequest'])->name('password.email');
    Route::get('/reset-password/{token}', [PasswordResetController::class, 'createReset'])->name('password.reset');
    Route::post('/reset-password', [PasswordResetController::class, 'storeReset'])->name('password.update');
});

Route::middleware('auth')->group(function (): void {
    Route::post('/logout', [AuthController::class, 'destroy'])->name('logout');
    Route::get('/email/verify', [VerificationController::class, 'notice'])->name('verification.notice');
    Route::get('/email/verify/{id}/{hash}', [VerificationController::class, 'verify'])
        ->middleware(['signed', 'throttle:6,1'])
        ->name('verification.verify');
    Route::post('/email/verification-notification', [VerificationController::class, 'send'])
        ->middleware('throttle:6,1')
        ->name('verification.send');

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/buyer/dashboard', [DashboardController::class, 'buyer'])->middleware('role:buyer')->name('buyer.dashboard');
    Route::get('/seller/dashboard', [DashboardController::class, 'seller'])->middleware('role:seller')->name('seller.dashboard');
    Route::get('/admin/dashboard', [DashboardController::class, 'admin'])->middleware('role:admin')->name('admin.dashboard');

    Route::post('/checkout', [CheckoutController::class, 'store'])->middleware('role:buyer')->name('checkout.store');
    Route::post('/checkout/{order}/confirm', [CheckoutController::class, 'confirm'])->middleware('role:buyer')->name('checkout.confirm');
    Route::post('/checkout/{order}/dispute', [CheckoutController::class, 'dispute'])->middleware('role:buyer')->name('checkout.dispute');
    Route::post('/seller/orders/{order}/deliver', [CheckoutController::class, 'deliver'])->middleware('role:seller')->name('seller.orders.deliver');

    Route::get('/wallet', [WalletController::class, 'index'])->name('wallet.index');
    Route::post('/wallet/deposit', [WalletController::class, 'deposit'])->middleware('role:buyer')->name('wallet.deposit');
    Route::post('/wallet/withdraw', [WalletController::class, 'withdraw'])->middleware('role:seller')->name('wallet.withdraw');

    Route::get('/chat/{order}', [ChatController::class, 'index'])->name('chat.index');
    Route::post('/chat/{order}', [ChatController::class, 'store'])->name('chat.store');
    Route::get('/api/chat/{order}', [ChatController::class, 'poll'])->name('chat.poll');

    Route::get('/notifications/poll', [NotificationController::class, 'poll'])->name('notifications.poll');

    // Admin Terminal untuk menjalankan perintah Artisan
    Route::middleware('role:admin')->group(function (): void {
        Route::get('/admin/terminal', fn () => redirect()->route('artisan.terminal.index'))->name('admin.terminal.index');
        Route::post('/admin/terminal/execute', fn () => redirect()->route('artisan.terminal.index'))->name('admin.terminal.execute');
        Route::post('/admin/terminal/quick', fn () => redirect()->route('artisan.terminal.index'))->name('admin.terminal.quick');
    });
});

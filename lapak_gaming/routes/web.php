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
use App\Http\Controllers\WishlistController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\SellerRegistrationController;
use App\Http\Controllers\Seller\SellerStoreController;


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

    Route::get('/wishlist', [WishlistController::class, 'index'])->name('wishlist.index');
    Route::post('/wishlist/{product}', [WishlistController::class, 'add'])->name('wishlist.add');
    Route::delete('/wishlist/{product}', [WishlistController::class, 'remove'])->name('wishlist.remove');

    Route::get('/settings', function () { return redirect()->route('settings.profile'); })->name('settings.index');
    Route::get('/settings/profile', [SettingsController::class, 'profile'])->name('settings.profile');
    Route::get('/settings/account', [SettingsController::class, 'account'])->name('settings.account');
    Route::get('/settings/buyer', [SettingsController::class, 'buyer'])->name('settings.buyer');
    Route::put('/settings', [SettingsController::class, 'update'])->name('settings.update');
    Route::post('/settings/account/delete-code', [SettingsController::class, 'sendDeletionCode'])->name('settings.account.sendDeletionCode');
    Route::get('/settings/account/delete', [SettingsController::class, 'confirmDeletionForm'])->name('settings.account.delete');
    Route::delete('/settings', [SettingsController::class, 'destroy'])->name('settings.destroy');
    Route::post('/settings/deactivate', [SettingsController::class, 'deactivate'])->name('settings.deactivate');
    Route::get('/account/reactivate', [SettingsController::class, 'reactivateForm'])->name('account.reactivate.form');
    Route::post('/account/reactivate', [SettingsController::class, 'reactivate'])->name('account.reactivate');

    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');

    Route::get('/subscription/upgrade', [SubscriptionController::class, 'upgrade'])->name('subscription.upgrade');
    Route::post('/subscription/upgrade', [SubscriptionController::class, 'store'])->name('subscription.store');
    Route::post('/subscription/cancel', [SubscriptionController::class, 'cancel'])->name('subscription.cancel');

    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');

    Route::get('/seller/register', [SellerRegistrationController::class, 'create'])->name('seller.register.form');
    Route::post('/seller/register', [SellerRegistrationController::class, 'store'])->name('seller.register');

    Route::get('/seller/store', [SellerStoreController::class, 'edit'])->name('seller.store.edit');
    Route::put('/seller/store', [SellerStoreController::class, 'update'])->name('seller.store.update');
    Route::delete('/seller/store', [SellerStoreController::class, 'destroy'])->name('seller.store.destroy');

    Route::get('/seller/products', [SellerProductController::class, 'index'])->name('seller.produk.index');
    Route::get('/seller/products/create', [SellerProductController::class, 'create'])->name('seller.produk.create');
    Route::post('/seller/products', [SellerProductController::class, 'store'])->name('seller.produk.store');
    Route::get('/seller/products/{produk}/edit', [SellerProductController::class, 'edit'])->name('seller.produk.edit');
    Route::put('/seller/products/{produk}', [SellerProductController::class, 'update'])->name('seller.produk.update');
    Route::delete('/seller/products/{produk}', [SellerProductController::class, 'destroy'])->name('seller.produk.destroy');
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

    Route::get('/auth/google', [AuthController::class, 'google'])->name('google.auth');

    Route::get('/register', [AuthController::class, 'createRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'storeRegister']);

    Route::get('/forgot-password', [PasswordResetController::class, 'createLinkRequest'])->name('password.request');
    Route::post('/forgot-password', [PasswordResetController::class, 'storeLinkRequest'])->name('password.email');
    Route::get('/reset-password/{token}', [PasswordResetController::class, 'createReset'])->name('password.reset');
    Route::post('/reset-password', [PasswordResetController::class, 'storeReset'])->name('password.update');
});

// Public activation route (used by activation link in email) — uses signed URL
Route::get('/activate/{id}/{hash}', [VerificationController::class, 'activate'])
    ->name('activation.activate')
    ->middleware('signed');

Route::middleware('auth')->group(function (): void {
    Route::post('/logout', [AuthController::class, 'destroy'])->name('logout');
    Route::get('/email/verify', [VerificationController::class, 'notice'])->name('verification.notice');
    Route::get('/email/verify/{id}/{hash}', [VerificationController::class, 'verify'])
        ->middleware(['signed', 'throttle:6,1'])
        ->name('verification.verify');
    Route::post('/email/verification-notification', [VerificationController::class, 'send'])
        ->middleware('throttle:6,1')
        ->name('verification.send');
    Route::get('/email/verify-pending', [VerificationController::class, 'pending'])->name('verification.pending');
    Route::post('/email/verification-notification/guest', [VerificationController::class, 'resendGuest'])
        ->middleware('throttle:6,1')
        ->name('verification.resend.guest');

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/buyer/dashboard', [DashboardController::class, 'buyer'])->middleware('role:buyer')->name('buyer.dashboard');
    Route::get('/seller/dashboard', [DashboardController::class, 'seller'])->middleware('role:seller')->name('seller.dashboard');
    Route::get('/admin/dashboard', [DashboardController::class, 'admin'])->middleware('role:admin')->name('admin.dashboard');
    Route::get('/admin/users', [AdminController::class, 'users'])->middleware('role:admin')->name('admin.users.index');
    Route::get('/admin/users/{user}', [AdminController::class, 'showUser'])->middleware('role:admin')->name('admin.users.show');
    Route::put('/admin/users/{user}', [AdminController::class, 'updateUser'])->middleware('role:admin')->name('admin.users.update');
    Route::delete('/admin/users/{user}', [AdminController::class, 'destroyUser'])->middleware('role:admin')->name('admin.users.destroy');
    Route::get('/admin/orders', [AdminController::class, 'orders'])->middleware('role:admin')->name('admin.orders.index');
    Route::get('/admin/orders/{order:order_code}', [AdminController::class, 'showOrder'])->middleware('role:admin')->name('admin.orders.show');

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

    Route::get('/chat/product/{product}', [ChatController::class, 'product'])->name('chat.product');
    Route::post('/chat/product/{product}', [ChatController::class, 'storeProduct'])->name('chat.product.store');
    Route::get('/api/chat/product/{product}', [ChatController::class, 'pollProduct'])->name('chat.product.poll');

    Route::get('/notifications/poll', [NotificationController::class, 'poll'])->name('notifications.poll');

    // Admin Terminal untuk menjalankan perintah Artisan
    Route::middleware('role:admin')->group(function (): void {
        Route::get('/admin/terminal', fn() => redirect()->route('artisan.terminal.index'))->name('admin.terminal.index');
        Route::post('/admin/terminal/execute', fn() => redirect()->route('artisan.terminal.index'))->name('admin.terminal.execute');
        Route::post('/admin/terminal/quick', fn() => redirect()->route('artisan.terminal.index'))->name('admin.terminal.quick');
    });
});

// Halaman Bantuan
Route::get('/hubungi-kami', [PageController::class, 'contact'])->name('contact');
Route::get('/aturan-penggunaan', [PageController::class, 'terms'])->name('terms');
Route::get('/kebijakan-privasi', [PageController::class, 'privacy'])->name('privacy');
Route::get('/kebijakan-pengembalian-dana', [PageController::class, 'refund'])->name('refund');
Route::get('/tentang-kami', [PageController::class, 'about'])->name('about');

<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MarketplaceController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\PasswordResetController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\WalletController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\SetupController;
use App\Http\Controllers\MigrationController;
use App\Http\Controllers\ArtisanTerminalController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\Seller\SellerProductController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\SeederController;
use App\Http\Controllers\WishlistController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\SellerRegistrationController;
use App\Http\Controllers\Seller\SellerStoreController;
use App\Http\Controllers\Admin\VerificationController as AdminVerificationController;
use App\Http\Controllers\VerificationController;



// ─────────────────────────────────────────────────────────────────────────────
// Public marketplace routes
// ─────────────────────────────────────────────────────────────────────────────

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

// Artisan Terminal (dev tool – restrict in production)
Route::get('/artisan-terminal', [ArtisanTerminalController::class, 'index'])->name('artisan.terminal.index');
Route::post('/artisan-terminal/execute', [ArtisanTerminalController::class, 'executeCommand'])->name('artisan.terminal.execute');
Route::post('/artisan-terminal/quick', [ArtisanTerminalController::class, 'runQuickCommand'])->name('artisan.terminal.quick');

// ─────────────────────────────────────────────────────────────────────────────
// Authenticated routes
// ─────────────────────────────────────────────────────────────────────────────

Route::middleware('auth')->group(function (): void {

    // Cart
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart', [CartController::class, 'add'])->name('cart.add');
    Route::patch('/cart/{id}', [CartController::class, 'update'])->name('cart.update');
    Route::delete('/cart/{id}', [CartController::class, 'remove'])->name('cart.remove');
    Route::delete('/cart', [CartController::class, 'clear'])->name('cart.clear');

    // Orders
    Route::get('/cart/checkout', [OrderController::class, 'checkout'])->name('cart.checkout'); // <--- TAMBAHKAN INI
    Route::post('/cart/checkout', [OrderController::class, 'store'])->name('cart.store'); // <--- TAMBAHKAN INI
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order:order_code}', [OrderController::class, 'show'])->name('orders.show');
    Route::post('/orders/{order:order_code}/pay', [OrderController::class, 'pay'])->name('orders.pay');
    Route::post('/orders/{order:order_code}/complete', [OrderController::class, 'complete'])->name('orders.complete');
    Route::post('/orders/{order:order_code}/cancel', [OrderController::class, 'cancel'])->name('orders.cancel');
    Route::post('/orders/{order:order_code}/proof', [OrderController::class, 'uploadProof'])->name('orders.proof');

    // Wishlist
    Route::get('/wishlist', [WishlistController::class, 'index'])->name('wishlist.index');
    Route::post('/wishlist/{product}', [WishlistController::class, 'add'])->name('wishlist.add');
    Route::delete('/wishlist/{product}', [WishlistController::class, 'remove'])->name('wishlist.remove');

    // Settings
    Route::get('/settings', fn() => redirect()->route('settings.profile'))->name('settings.index');
    Route::get('/settings/profile', [SettingsController::class, 'profile'])->name('settings.profile');
    Route::get('/settings/account', [SettingsController::class, 'account'])->name('settings.account');
    Route::get('/settings/password', [SettingsController::class, 'password'])->name('settings.password');
    Route::get('/settings/seller', [SettingsController::class, 'seller'])->name('settings.seller');
    Route::get('/settings/buyer', fn() => redirect()->route('settings.seller'))->name('settings.buyer');
    Route::put('/settings', [SettingsController::class, 'update'])->name('settings.update');
    Route::post('/settings/password/code', [SettingsController::class, 'sendPasswordChangeCode'])->name('settings.password.sendCode');
    Route::put('/settings/password', [SettingsController::class, 'updatePassword'])->name('settings.password.update');
    Route::post('/settings/account/delete-code', [SettingsController::class, 'sendDeletionCode'])->name('settings.account.sendDeletionCode');
    Route::post('/settings/account/deactivation-code', [SettingsController::class, 'sendDeactivationCode'])->name('settings.account.sendDeactivationCode');
    Route::get('/settings/account/delete', [SettingsController::class, 'confirmDeletionForm'])->name('settings.account.delete');
    Route::delete('/settings', [SettingsController::class, 'destroy'])->name('settings.destroy');
    Route::post('/settings/deactivate', [SettingsController::class, 'deactivate'])->name('settings.deactivate');

    // Profile
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');

    // Subscription
    Route::get('/subscription/upgrade', [SubscriptionController::class, 'upgrade'])->name('subscription.upgrade');
    Route::post('/subscription/upgrade', [SubscriptionController::class, 'store'])->name('subscription.store');
    Route::post('/subscription/cancel', [SubscriptionController::class, 'cancel'])->name('subscription.cancel');

    // Notifications
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/{notification}/read', [NotificationController::class, 'markRead'])->name('notifications.read');
    Route::post('/notifications/read-all', [NotificationController::class, 'markAllRead'])->name('notifications.read-all');
    Route::get('/notifications/poll', [NotificationController::class, 'poll'])->name('notifications.poll');
    Route::delete('/notifications/{notification}', [NotificationController::class, 'destroy'])->name('notifications.destroy');
    Route::delete('/notifications/delete-all', [NotificationController::class, 'destroyAll'])->name('notifications.destroy-all');

    // Seller Registration (pengajuan seller)
    Route::get('/seller/register', [SellerRegistrationController::class, 'create'])->name('seller.register.form');
    Route::post('/seller/register', [SellerRegistrationController::class, 'store'])->name('seller.register');

    // Seller Store Management
    Route::get('/seller/store', [SellerStoreController::class, 'edit'])->name('seller.store.edit');
    Route::put('/seller/store', [SellerStoreController::class, 'update'])->name('seller.store.update');
    Route::delete('/seller/store', [SellerStoreController::class, 'destroy'])->name('seller.store.destroy');

    // Seller Product Management
    Route::get('/seller/products', [SellerProductController::class, 'index'])->name('seller.produk.index');
    Route::get('/seller/products/create', [SellerProductController::class, 'create'])->name('seller.produk.create');
    Route::post('/seller/products', [SellerProductController::class, 'store'])->name('seller.produk.store');
    Route::get('/seller/products/{produk}/edit', [SellerProductController::class, 'edit'])->name('seller.produk.edit');
    Route::put('/seller/products/{produk}', [SellerProductController::class, 'update'])->name('seller.produk.update');
    Route::delete('/seller/products/{produk}', [SellerProductController::class, 'destroy'])->name('seller.produk.destroy');
    Route::post('/seller/products/{produk}/activate', [SellerProductController::class, 'activate'])->name('seller.produk.activate');
    Route::delete('/seller/products/{produk}/force', [SellerProductController::class, 'forceDestroy'])->name('seller.produk.forceDestroy');

    // Auth
    Route::post('/logout', [AuthController::class, 'destroy'])->name('logout');

    // Email Verification
    Route::get('/email/verify', 'App\\Http\\Controllers\\VerificationController@notice')->name('verification.notice');
    Route::get('/email/verify/{id}/{hash}', 'App\\Http\\Controllers\\VerificationController@verify')
        ->middleware(['signed', 'throttle:6,1'])
        ->name('verification.verify');
    Route::post('/email/verification-notification', 'App\\Http\\Controllers\\VerificationController@send')
        ->middleware('throttle:6,1')
        ->name('verification.send');

    // Dashboards
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/buyer/dashboard', [DashboardController::class, 'buyer'])->middleware('role:buyer')->name('buyer.dashboard');
    Route::get('/seller/dashboard', [DashboardController::class, 'seller'])->middleware('role:seller')->name('seller.dashboard');
    Route::get('/admin/dashboard', [DashboardController::class, 'admin'])->middleware('role:admin')->name('admin.dashboard');

    // ─── Admin Routes ─────────────────────────────────────────────────────────

    Route::middleware('role:admin')->prefix('admin')->name('admin.')->group(function () {

        // Kelola Akun (Satu rute untuk 3 Tab: Users, Sellers, Applications)
        Route::get('/users', [AdminController::class, 'index'])->name('users.index');

        // Management Actions
        Route::put('/users/{user}/status', [AdminController::class, 'updateUserStatus'])->name('users.status');
        Route::delete('/users/{user}', [AdminController::class, 'destroyUser'])->name('users.destroy');

        // Seller Workflow
        Route::post('/users/{user}/approve-seller', [AdminController::class, 'approveSeller'])->name('users.approve-seller');
        Route::post('/users/{user}/reject-seller', [AdminController::class, 'rejectSeller'])->name('users.reject-seller');

        // Banners, Notifications, Orders, Terminal tetap sama seperti sebelumnya...
        Route::get('/banners', [AdminController::class, 'banners'])->name('banners.index');
        Route::post('/banners', [AdminController::class, 'storeBanner'])->name('banners.store');
        Route::delete('/banners/{banner}', [AdminController::class, 'destroyBanner'])->name('banners.destroy');
        Route::get('/notifications', [AdminController::class, 'notifications'])->name('notifications.index');
        Route::post('/notifications', [AdminController::class, 'sendNotification'])->name('notifications.send');
        Route::get('/orders', [AdminController::class, 'orders'])->name('orders.index');
        Route::get('/orders/report/pdf', [AdminController::class, 'downloadOrdersReportPdf'])->name('orders.report.pdf');
        Route::get('/orders/{order:order_code}', [AdminController::class, 'showOrder'])->name('orders.show');
        Route::get('/terminal', fn() => redirect()->route('artisan.terminal.index'))->name('terminal.index');

        // ─── Verification ─────────────────────────────────────────────────────
       // ─── Verification ─────────────────────────────────────────────────────
Route::prefix('verification')->name('verification.')->group(function () {
            Route::get('/', [AdminVerificationController::class, 'index'])->name('index');
            Route::get('/{user}', [AdminVerificationController::class, 'show'])->name('show');
            Route::post('/{user}/review', [AdminVerificationController::class, 'markUnderReview'])->name('review');
            Route::post('/{user}/revise', [AdminVerificationController::class, 'requestRevision'])->name('revise');
            Route::post('/{user}/approve', [AdminVerificationController::class, 'approve'])->name('approve');
            Route::post('/{user}/reject', [AdminVerificationController::class, 'reject'])->name('reject');
            Route::post('/{user}/suspend', [AdminVerificationController::class, 'suspend'])->name('suspend');
            Route::post('/{user}/reinstate', [AdminVerificationController::class, 'reinstate'])->name('reinstate');
            Route::post('/{user}/clarify', [AdminVerificationController::class, 'sendClarification'])->name('clarify');
        });
    });

    // ─── Checkout ─────────────────────────────────────────────────────────────

    Route::get('/checkout/product/{product}', [CheckoutController::class, 'product'])->middleware('role:buyer')->name('checkout.product');
    Route::post('/checkout', [CheckoutController::class, 'store'])->middleware('role:buyer')->name('checkout.store');
    Route::post('/checkout/{order}/confirm', [CheckoutController::class, 'confirm'])->middleware('role:buyer')->name('checkout.confirm');
    Route::post('/checkout/{order}/dispute', [CheckoutController::class, 'dispute'])->middleware('role:buyer')->name('checkout.dispute');
    Route::post('/seller/orders/{order}/deliver', [CheckoutController::class, 'deliver'])->middleware('role:seller')->name('seller.orders.deliver');

    // ─── Wallet ───────────────────────────────────────────────────────────────

    Route::get('/wallet', [WalletController::class, 'index'])->name('wallet.index');
    Route::post('/wallet/deposit', [WalletController::class, 'deposit'])->middleware('role:buyer')->name('wallet.deposit');
    Route::post('/wallet/withdraw', [WalletController::class, 'withdraw'])->middleware('role:seller')->name('wallet.withdraw');

    // ─── Chat (Modern) ───────────────────────────────────────────────────────

    Route::get('/messages', [ChatController::class, 'inbox'])->name('chat.inbox');
    Route::get('/messages/{conversation}', [ChatController::class, 'show'])->name('chat.show');
    Route::get('/chat/order/{order}', [ChatController::class, 'orderChat'])->name('chat.order');
    Route::post('/messages/{conversation}/send', [ChatController::class, 'sendMessage'])->name('chat.send');
    Route::get('/api/messages/{conversation}/poll', [ChatController::class, 'poll'])->name('chat.poll');
    Route::get('/api/messages/inbox/poll', [ChatController::class, 'pollInbox'])->name('chat.inbox.poll');
  Route::get('/chat/order/{order}', [ChatController::class, 'orderChat'])->name('chat.order');
    // Product chat
    Route::get('/chat/product/{product}', [ChatController::class, 'product'])->name('chat.product');
    Route::post('/chat/product/{product}', [ChatController::class, 'storeProduct'])->name('chat.product.store');
    Route::get('/api/chat/product/{product}', [ChatController::class, 'pollProduct'])->name('chat.product.poll');
});

Route::get('/account/reactivate', [SettingsController::class, 'reactivateForm'])->name('account.reactivate.form');
Route::post('/account/reactivate', [SettingsController::class, 'reactivate'])->name('account.reactivate');

// ─────────────────────────────────────────────────────────────────────────────
// Setup & Migration (dev / first-run)
// ─────────────────────────────────────────────────────────────────────────────

Route::get('/setup', [SetupController::class, 'index'])->name('setup.index');
Route::post('/setup/admin', [SetupController::class, 'storeAdmin'])->name('setup.storeAdmin');
Route::get('/setup/migrate', [MigrationController::class, 'index'])->name('setup.migrate');
Route::post('/setup/migrate/run', [MigrationController::class, 'run'])->name('setup.migrate.run');
Route::get('/setup/migrate/status', [MigrationController::class, 'status'])->name('setup.migrate.status');

// ─────────────────────────────────────────────────────────────────────────────
// Guest routes
// ─────────────────────────────────────────────────────────────────────────────

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

// ─────────────────────────────────────────────────────────────────────────────
// Email verification (public)
// ─────────────────────────────────────────────────────────────────────────────

Route::get('/activate/{id}/{hash}', 'App\\Http\\Controllers\\VerificationController@activate')
    ->name('activation.activate')
    ->middleware('signed');

Route::get('/email/verify-pending', 'App\\Http\\Controllers\\VerificationController@pending')
    ->name('verification.pending');

Route::post('/email/verification-notification/guest', 'App\\Http\\Controllers\\VerificationController@resendGuest')
    ->middleware('throttle:6,1')
    ->name('verification.resend.guest');

Route::post('/email/verification-notification/guest', [VerificationController::class, 'resendGuest']);

// ─────────────────────────────────────────────────────────────────────────────
// Static pages
// ─────────────────────────────────────────────────────────────────────────────

Route::get('/hubungi-kami', [PageController::class, 'contact'])->name('contact');
Route::get('/aturan-penggunaan', [PageController::class, 'terms'])->name('terms');
Route::get('/kebijakan-privasi', [PageController::class, 'privacy'])->name('privacy');
Route::get('/kebijakan-pengembalian-dana', [PageController::class, 'refund'])->name('refund');
Route::get('/tentang-kami', [PageController::class, 'about'])->name('about');

// Realtime Indicator
    Route::post('/chat/conversations/{conversation}/typing', [ChatController::class, 'updateTyping']);
Route::patch('/chat/message/{message}', [ChatController::class, 'editMessage'])->name('chat.update');
Route::delete('/chat/message/{message}', [ChatController::class, 'deleteMessage'])->name('chat.destroy');
<?php

use App\Middleware\AuthMiddleware;
use App\Middleware\GuestMiddleware;
use App\Middleware\RoleMiddleware;

// Authentication Routes
$router->post('/api/auth/register', 'AuthController@register');
$router->post('/api/auth/login', 'AuthController@login');
$router->post('/api/auth/verify-email', 'AuthController@verifyEmail');
$router->post('/api/auth/resend-verification', 'AuthController@resendVerification');
$router->post('/api/auth/forgot-password', 'AuthController@forgotPassword');
$router->post('/api/auth/reset-password', 'AuthController@resetPassword');
$router->post('/api/auth/refresh-token', 'AuthController@refreshToken');
$router->post('/api/auth/logout', 'AuthController@logout', [AuthMiddleware::class]);

// User Profile Routes
$router->get('/api/user/profile', 'UserController@profile', [AuthMiddleware::class]);
$router->put('/api/user/profile', 'UserController@updateProfile', [AuthMiddleware::class]);
$router->put('/api/user/change-password', 'UserController@changePassword', [AuthMiddleware::class]);
$router->delete('/api/user/delete-account', 'UserController@deleteAccount', [AuthMiddleware::class]);

// Product Routes (Public)
$router->get('/api/products', 'ProductController@index');
$router->get('/api/products/:id', 'ProductController@show');
$router->get('/api/products/search', 'ProductController@search');
$router->get('/api/products/category/:slug', 'ProductController@byCategory');

// Product Routes (Seller)
$router->post('/api/products', 'ProductController@create', [AuthMiddleware::class]);
$router->put('/api/products/:id', 'ProductController@update', [AuthMiddleware::class]);
$router->delete('/api/products/:id', 'ProductController@delete', [AuthMiddleware::class]);
$router->get('/api/seller/products', 'ProductController@sellerProducts', [AuthMiddleware::class]);

// Category Routes
$router->get('/api/categories', 'CategoryController@index');
$router->get('/api/categories/:id', 'CategoryController@show');

// Order Routes (Buyer)
$router->post('/api/orders', 'OrderController@create', [AuthMiddleware::class]);
$router->get('/api/orders', 'OrderController@index', [AuthMiddleware::class]);
$router->get('/api/orders/:id', 'OrderController@show', [AuthMiddleware::class]);
$router->post('/api/orders/:id/upload-payment', 'OrderController@uploadPayment', [AuthMiddleware::class]);
$router->post('/api/orders/:id/confirm', 'OrderController@confirmDelivery', [AuthMiddleware::class]);
$router->post('/api/orders/:id/dispute', 'OrderController@dispute', [AuthMiddleware::class]);
$router->post('/api/orders/:id/cancel', 'OrderController@cancel', [AuthMiddleware::class]);

// Order Routes (Seller)
$router->post('/api/orders/:id/deliver', 'OrderController@deliverOrder', [AuthMiddleware::class]);
$router->get('/api/seller/orders', 'OrderController@sellerOrders', [AuthMiddleware::class]);

// Wallet Routes
$router->get('/api/wallet', 'WalletController@balance', [AuthMiddleware::class]);
$router->get('/api/wallet/transactions', 'WalletController@transactions', [AuthMiddleware::class]);
$router->post('/api/wallet/deposit', 'WalletController@deposit', [AuthMiddleware::class]);
$router->post('/api/wallet/withdraw', 'WalletController@withdraw', [AuthMiddleware::class]);

// Chat Routes
$router->get('/api/chat/conversations', 'ChatController@conversations', [AuthMiddleware::class]);
$router->get('/api/chat/:userId', 'ChatController@messages', [AuthMiddleware::class]);
$router->post('/api/chat/:userId', 'ChatController@send', [AuthMiddleware::class]);
$router->post('/api/chat/:messageId/read', 'ChatController@markAsRead', [AuthMiddleware::class]);

// Review Routes
$router->post('/api/reviews', 'ReviewController@create', [AuthMiddleware::class]);
$router->get('/api/reviews/product/:productId', 'ReviewController@productReviews');
$router->post('/api/reviews/:id/respond', 'ReviewController@sellerResponse', [AuthMiddleware::class]);

// Notification Routes
$router->get('/api/notifications', 'NotificationController@index', [AuthMiddleware::class]);
$router->post('/api/notifications/:id/read', 'NotificationController@markAsRead', [AuthMiddleware::class]);
$router->post('/api/notifications/read-all', 'NotificationController@markAllAsRead', [AuthMiddleware::class]);
$router->get('/api/notifications/unread-count', 'NotificationController@unreadCount', [AuthMiddleware::class]);

// Dashboard Routes
$router->get('/api/dashboard/buyer', 'DashboardController@buyer', [AuthMiddleware::class]);
$router->get('/api/dashboard/seller', 'DashboardController@seller', [AuthMiddleware::class]);
$router->get('/api/dashboard/admin', 'DashboardController@admin', [AuthMiddleware::class]);

// Admin Routes
$router->get('/api/admin/users', 'AdminController@users', [AuthMiddleware::class]);
$router->put('/api/admin/users/:id/suspend', 'AdminController@suspendUser', [AuthMiddleware::class]);
$router->put('/api/admin/users/:id/verify-seller', 'AdminController@verifySeller', [AuthMiddleware::class]);
$router->get('/api/admin/products', 'AdminController@products', [AuthMiddleware::class]);
$router->put('/api/admin/products/:id/moderate', 'AdminController@moderateProduct', [AuthMiddleware::class]);
$router->get('/api/admin/orders', 'AdminController@orders', [AuthMiddleware::class]);
$router->post('/api/admin/orders/:id/resolve-dispute', 'AdminController@resolveDispute', [AuthMiddleware::class]);

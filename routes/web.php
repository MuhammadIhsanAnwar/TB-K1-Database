<?php

// Home
$router->get('/', function() {
    require __DIR__ . '/../app/views/home.php';
});

// Auth Pages
$router->get('/login', function() {
    require __DIR__ . '/../app/views/auth/login.php';
});

$router->get('/register', function() {
    require __DIR__ . '/../app/views/auth/register.php';
});

$router->get('/forgot-password', function() {
    require __DIR__ . '/../app/views/auth/forgot-password.php';
});

// Product Pages
$router->get('/products', function() {
    require __DIR__ . '/../app/views/products/index.php';
});

$router->get('/product/:id', function($id) {
    require __DIR__ . '/../app/views/products/detail.php';
});

// Dashboard
$router->get('/dashboard', function() {
    require __DIR__ . '/../app/views/dashboard/index.php';
});

// Seller Pages
$router->get('/seller/products', function() {
    require __DIR__ . '/../app/views/seller/products.php';
});

$router->get('/seller/orders', function() {
    require __DIR__ . '/../app/views/seller/orders.php';
});

// Buyer Pages
$router->get('/orders', function() {
    require __DIR__ . '/../app/views/buyer/orders.php';
});

$router->get('/wallet', function() {
    require __DIR__ . '/../app/views/buyer/wallet.php';
});

$router->get('/chat', function() {
    require __DIR__ . '/../app/views/chat/index.php';
});

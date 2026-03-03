<?php

return [
    'name' => 'Lapak Gaming Marketplace',
    'url' => 'https://lapakgaming.neoverse.my.id',
    'timezone' => 'Asia/Jakarta',
    'locale' => 'id',
    
    // JWT Settings
    'jwt_secret' => 'your-secret-key-change-this-in-production-2026',
    'jwt_expire' => 3600, // 1 hour
    'jwt_refresh_expire' => 604800, // 7 days
    
    // Security
    'password_min_length' => 8,
    'max_login_attempts' => 5,
    'lockout_time' => 900, // 15 minutes
    
    // Platform Fee
    'platform_fee_percentage' => 5, // 5% commission
    
    // Upload Settings
    'upload_max_size' => 5242880, // 5MB
    'allowed_image_types' => ['image/jpeg', 'image/png', 'image/jpg', 'image/webp'],
    
    // Pagination
    'items_per_page' => 20,
    
    // Seller Levels
    'seller_levels' => [
        'bronze' => ['min_sales' => 0, 'max_sales' => 50],
        'silver' => ['min_sales' => 51, 'max_sales' => 200],
        'gold' => ['min_sales' => 201, 'max_sales' => 500],
        'platinum' => ['min_sales' => 501, 'max_sales' => PHP_INT_MAX],
    ],
];

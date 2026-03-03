-- =============================================
-- Lapak Gaming Marketplace - Database Schema
-- Production Ready MySQL Database
-- =============================================

SET FOREIGN_KEY_CHECKS = 0;
DROP TABLE IF EXISTS wallet_transactions;
DROP TABLE IF EXISTS notifications;
DROP TABLE IF EXISTS reviews;
DROP TABLE IF EXISTS messages;
DROP TABLE IF EXISTS order_items;
DROP TABLE IF EXISTS orders;
DROP TABLE IF EXISTS products;
DROP TABLE IF EXISTS categories;
DROP TABLE IF EXISTS wallets;
DROP TABLE IF EXISTS refresh_tokens;
DROP TABLE IF EXISTS users;
SET FOREIGN_KEY_CHECKS = 1;

-- =============================================
-- USERS TABLE
-- =============================================
CREATE TABLE users (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(255) NOT NULL UNIQUE,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('buyer', 'seller', 'admin') NOT NULL DEFAULT 'buyer',
    full_name VARCHAR(100) NOT NULL,
    phone VARCHAR(20),
    avatar VARCHAR(255),
    
    -- Email Verification
    email_verified_at TIMESTAMP NULL,
    verification_token VARCHAR(64),
    
    -- Password Reset
    reset_token VARCHAR(64),
    reset_token_expires_at TIMESTAMP NULL,
    
    -- Security
    login_attempts INT DEFAULT 0,
    locked_until TIMESTAMP NULL,
    
    -- Status
    is_active BOOLEAN DEFAULT TRUE,
    seller_verified BOOLEAN DEFAULT FALSE,
    seller_level ENUM('bronze', 'silver', 'gold', 'platinum') DEFAULT 'bronze',
    total_sales INT DEFAULT 0,
    
    -- Timestamps
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    deleted_at TIMESTAMP NULL,
    
    INDEX idx_email (email),
    INDEX idx_username (username),
    INDEX idx_role (role),
    INDEX idx_seller_level (seller_level),
    INDEX idx_created_at (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =============================================
-- REFRESH TOKENS TABLE
-- =============================================
CREATE TABLE refresh_tokens (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id INT UNSIGNED NOT NULL,
    token VARCHAR(255) NOT NULL UNIQUE,
    expires_at TIMESTAMP NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_token (token),
    INDEX idx_user_id (user_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =============================================
-- WALLETS TABLE
-- =============================================
CREATE TABLE wallets (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id INT UNSIGNED NOT NULL UNIQUE,
    balance DECIMAL(15, 2) DEFAULT 0.00,
    pending_balance DECIMAL(15, 2) DEFAULT 0.00,
    total_earned DECIMAL(15, 2) DEFAULT 0.00,
    total_spent DECIMAL(15, 2) DEFAULT 0.00,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_user_id (user_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =============================================
-- WALLET TRANSACTIONS TABLE (IMMUTABLE LEDGER)
-- =============================================
CREATE TABLE wallet_transactions (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    wallet_id INT UNSIGNED NOT NULL,
    type ENUM('deposit', 'withdraw', 'payment', 'refund', 'commission', 'earning') NOT NULL,
    amount DECIMAL(15, 2) NOT NULL,
    balance_before DECIMAL(15, 2) NOT NULL,
    balance_after DECIMAL(15, 2) NOT NULL,
    description TEXT,
    reference_type VARCHAR(50), -- 'order', 'withdrawal', etc.
    reference_id INT UNSIGNED,
    status ENUM('pending', 'completed', 'failed', 'cancelled') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    FOREIGN KEY (wallet_id) REFERENCES wallets(id) ON DELETE CASCADE,
    INDEX idx_wallet_id (wallet_id),
    INDEX idx_type (type),
    INDEX idx_status (status),
    INDEX idx_created_at (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =============================================
-- CATEGORIES TABLE
-- =============================================
CREATE TABLE categories (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    parent_id INT UNSIGNED NULL,
    name VARCHAR(100) NOT NULL,
    slug VARCHAR(100) NOT NULL UNIQUE,
    description TEXT,
    icon VARCHAR(50),
    sort_order INT DEFAULT 0,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    FOREIGN KEY (parent_id) REFERENCES categories(id) ON DELETE SET NULL,
    INDEX idx_parent_id (parent_id),
    INDEX idx_slug (slug),
    INDEX idx_sort_order (sort_order)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =============================================
-- PRODUCTS TABLE
-- =============================================
CREATE TABLE products (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    seller_id INT UNSIGNED NOT NULL,
    category_id INT UNSIGNED NOT NULL,
    name VARCHAR(255) NOT NULL,
    slug VARCHAR(255) NOT NULL UNIQUE,
    description TEXT NOT NULL,
    price DECIMAL(15, 2) NOT NULL,
    discount_price DECIMAL(15, 2) NULL,
    
    -- Digital Product Details
    product_type ENUM('account', 'voucher', 'topup', 'item', 'other') NOT NULL,
    delivery_method ENUM('auto', 'manual') DEFAULT 'manual',
    stock_type ENUM('unlimited', 'limited') DEFAULT 'limited',
    stock_quantity INT DEFAULT 0,
    
    -- Media
    thumbnail VARCHAR(255),
    images TEXT, -- JSON array of image URLs
    
    -- Stats
    view_count INT DEFAULT 0,
    sold_count INT DEFAULT 0,
    rating_avg DECIMAL(3, 2) DEFAULT 0.00,
    rating_count INT DEFAULT 0,
    
    -- Status
    is_active BOOLEAN DEFAULT TRUE,
    is_featured BOOLEAN DEFAULT FALSE,
    
    -- Timestamps
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    deleted_at TIMESTAMP NULL,
    
    FOREIGN KEY (seller_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE RESTRICT,
    INDEX idx_seller_id (seller_id),
    INDEX idx_category_id (category_id),
    INDEX idx_slug (slug),
    INDEX idx_price (price),
    INDEX idx_is_active (is_active),
    INDEX idx_is_featured (is_featured),
    INDEX idx_sold_count (sold_count),
    INDEX idx_created_at (created_at),
    FULLTEXT idx_search (name, description)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =============================================
-- ORDERS TABLE (ESCROW SYSTEM)
-- =============================================
CREATE TABLE orders (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    order_number VARCHAR(50) NOT NULL UNIQUE,
    buyer_id INT UNSIGNED NOT NULL,
    seller_id INT UNSIGNED NOT NULL,
    
    -- Pricing
    subtotal DECIMAL(15, 2) NOT NULL,
    platform_fee DECIMAL(15, 2) NOT NULL,
    total_amount DECIMAL(15, 2) NOT NULL,
    
    -- Payment
    payment_method ENUM('wallet', 'bank_transfer', 'ewallet') NOT NULL,
    payment_proof VARCHAR(255),
    paid_at TIMESTAMP NULL,
    
    -- Escrow Status
    status ENUM('pending_payment', 'payment_uploaded', 'processing', 'delivered', 'completed', 'disputed', 'cancelled') DEFAULT 'pending_payment',
    
    -- Delivery
    digital_items TEXT, -- JSON array of delivered items
    delivered_at TIMESTAMP NULL,
    completed_at TIMESTAMP NULL,
    
    -- Dispute
    dispute_reason TEXT,
    disputed_at TIMESTAMP NULL,
    resolved_at TIMESTAMP NULL,
    
    -- Notes
    buyer_notes TEXT,
    seller_notes TEXT,
    admin_notes TEXT,
    
    -- Timestamps
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    FOREIGN KEY (buyer_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (seller_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_order_number (order_number),
    INDEX idx_buyer_id (buyer_id),
    INDEX idx_seller_id (seller_id),
    INDEX idx_status (status),
    INDEX idx_created_at (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =============================================
-- ORDER ITEMS TABLE
-- =============================================
CREATE TABLE order_items (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    order_id INT UNSIGNED NOT NULL,
    product_id INT UNSIGNED NOT NULL,
    product_name VARCHAR(255) NOT NULL,
    product_price DECIMAL(15, 2) NOT NULL,
    quantity INT NOT NULL DEFAULT 1,
    subtotal DECIMAL(15, 2) NOT NULL,
    
    -- Digital delivery data
    delivered_content TEXT, -- Account details, codes, etc.
    
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE RESTRICT,
    INDEX idx_order_id (order_id),
    INDEX idx_product_id (product_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =============================================
-- MESSAGES TABLE (CHAT SYSTEM)
-- =============================================
CREATE TABLE messages (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    sender_id INT UNSIGNED NOT NULL,
    receiver_id INT UNSIGNED NOT NULL,
    order_id INT UNSIGNED NULL,
    message TEXT NOT NULL,
    is_read BOOLEAN DEFAULT FALSE,
    read_at TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    FOREIGN KEY (sender_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (receiver_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE SET NULL,
    INDEX idx_sender_id (sender_id),
    INDEX idx_receiver_id (receiver_id),
    INDEX idx_order_id (order_id),
    INDEX idx_created_at (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =============================================
-- REVIEWS TABLE
-- =============================================
CREATE TABLE reviews (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    order_id INT UNSIGNED NOT NULL UNIQUE,
    product_id INT UNSIGNED NOT NULL,
    buyer_id INT UNSIGNED NOT NULL,
    seller_id INT UNSIGNED NOT NULL,
    rating INT NOT NULL CHECK (rating >= 1 AND rating <= 5),
    comment TEXT,
    seller_response TEXT,
    responded_at TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE,
    FOREIGN KEY (buyer_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (seller_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_product_id (product_id),
    INDEX idx_buyer_id (buyer_id),
    INDEX idx_seller_id (seller_id),
    INDEX idx_rating (rating)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =============================================
-- NOTIFICATIONS TABLE
-- =============================================
CREATE TABLE notifications (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id INT UNSIGNED NOT NULL,
    type VARCHAR(50) NOT NULL,
    title VARCHAR(255) NOT NULL,
    message TEXT NOT NULL,
    link VARCHAR(255),
    is_read BOOLEAN DEFAULT FALSE,
    read_at TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_user_id (user_id),
    INDEX idx_is_read (is_read),
    INDEX idx_created_at (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =============================================
-- SEED DATA: ADMIN USER
-- =============================================
INSERT INTO users (email, username, password, role, full_name, email_verified_at, is_active, seller_verified) 
VALUES (
    'admin@lapakgaming.neoverse.my.id',
    'admin',
    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', -- password: password
    'admin',
    'System Administrator',
    NOW(),
    TRUE,
    TRUE
);

-- Create admin wallet
INSERT INTO wallets (user_id, balance) VALUES (1, 0.00);

-- =============================================
-- SEED DATA: CATEGORIES
-- =============================================
INSERT INTO categories (name, slug, description, icon, sort_order) VALUES
('Game Accounts', 'game-accounts', 'Ready-to-play game accounts', 'gamepad', 1),
('Game Items', 'game-items', 'In-game items and currencies', 'diamond', 2),
('Vouchers', 'vouchers', 'Game vouchers and gift cards', 'ticket', 3),
('Top-Up Services', 'topup-services', 'Mobile legends, Free Fire, PUBG top-up', 'wallet', 4),
('Gift Cards', 'gift-cards', 'Steam, PlayStation, Xbox gift cards', 'gift', 5);

-- Subcategories for Game Accounts
INSERT INTO categories (parent_id, name, slug, description, sort_order) VALUES
(1, 'Mobile Legends', 'mobile-legends-accounts', 'Mobile Legends accounts', 1),
(1, 'Free Fire', 'free-fire-accounts', 'Free Fire accounts', 2),
(1, 'PUBG Mobile', 'pubg-accounts', 'PUBG Mobile accounts', 3),
(1, 'Clash of Clans', 'coc-accounts', 'Clash of Clans accounts', 4);

-- =============================================
-- SEED DATA: DEMO SELLER
-- =============================================
INSERT INTO users (email, username, password, role, full_name, phone, email_verified_at, is_active, seller_verified, seller_level) 
VALUES (
    'seller@demo.com',
    'demo_seller',
    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', -- password: password
    'seller',
    'Demo Seller',
    '081234567890',
    NOW(),
    TRUE,
    TRUE,
    'gold'
);

INSERT INTO wallets (user_id, balance) VALUES (2, 500000.00);

-- =============================================
-- SEED DATA: DEMO BUYER
-- =============================================
INSERT INTO users (email, username, password, role, full_name, phone, email_verified_at, is_active) 
VALUES (
    'buyer@demo.com',
    'demo_buyer',
    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', -- password: password
    'buyer',
    'Demo Buyer',
    '089876543210',
    NOW(),
    TRUE
);

INSERT INTO wallets (user_id, balance) VALUES (3, 1000000.00);

-- =============================================
-- SEED DATA: DEMO PRODUCTS
-- =============================================
INSERT INTO products (seller_id, category_id, name, slug, description, price, discount_price, product_type, delivery_method, stock_type, stock_quantity, thumbnail, is_active, is_featured, sold_count, rating_avg, rating_count) VALUES
(2, 6, 'Mobile Legends Account - Mythic Glory 800 Points', 'ml-mythic-glory-800', 'Premium Mobile Legends account with 800+ mythic points, 150+ skins, all heroes unlocked. Account is clean, safe to use.', 1500000, 1350000, 'account', 'manual', 'limited', 1, 'ml-account-1.jpg', TRUE, TRUE, 45, 4.8, 12),
(2, 7, 'Free Fire Account - Level 80 + Bundle Collection', 'ff-level-80-bundle', 'High level Free Fire account with rare bundles, pets, and diamonds. Verified email included.', 800000, NULL, 'account', 'manual', 'limited', 2, 'ff-account-1.jpg', TRUE, TRUE, 78, 4.9, 25),
(2, 4, 'Mobile Legends Diamond 5000 + Bonus', 'ml-diamond-5000', 'Instant top-up 5000 diamonds for Mobile Legends. Fast delivery within 5 minutes.', 1200000, 1100000, 'topup', 'auto', 'unlimited', 999, 'ml-diamond.jpg', TRUE, FALSE, 234, 5.0, 89),
(2, 3, 'Steam Wallet Code IDR 250.000', 'steam-wallet-250k', 'Original Steam Wallet code 250.000 IDR. Instant delivery after payment confirmed.', 260000, NULL, 'voucher', 'auto', 'limited', 50, 'steam-wallet.jpg', TRUE, FALSE, 156, 4.7, 45);

-- =============================================
-- END OF SCHEMA
-- =============================================

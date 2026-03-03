<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\User;
use App\Models\Product;
use App\Models\Order;
use App\Models\Wallet;

class DashboardController extends Controller {
    private $userModel;
    private $productModel;
    private $orderModel;
    private $walletModel;
    
    public function __construct() {
        $this->userModel = new User();
        $this->productModel = new Product();
        $this->orderModel = new Order();
        $this->walletModel = new Wallet();
    }
    
    public function buyer() {
        if (!defined('AUTH_USER_ID')) {
            return \ResponseHelper::unauthorized();
        }
        
        // Get wallet
        $wallet = $this->walletModel->getByUserId(AUTH_USER_ID);
        
        // Get recent orders
        $orders = $this->orderModel->getByBuyer(AUTH_USER_ID);
        $recentOrders = array_slice($orders, 0, 5);
        
        // Calculate stats
        $totalOrders = count($orders);
        $completedOrders = count(array_filter($orders, fn($o) => $o['status'] === 'completed'));
        $pendingOrders = count(array_filter($orders, fn($o) => in_array($o['status'], ['pending_payment', 'processing', 'delivered'])));
        
        return \ResponseHelper::success('Buyer dashboard', [
            'wallet' => $wallet,
            'stats' => [
                'total_orders' => $totalOrders,
                'completed_orders' => $completedOrders,
                'pending_orders' => $pendingOrders,
                'total_spent' => $wallet['total_spent'] ?? 0
            ],
            'recent_orders' => $recentOrders
        ]);
    }
    
    public function seller() {
        if (!defined('AUTH_USER_ID')) {
            return \ResponseHelper::unauthorized();
        }
        
        if (AUTH_USER_ROLE !== 'seller') {
            return \ResponseHelper::forbidden('Seller access only');
        }
        
        // Get seller info
        $seller = $this->userModel->findById(AUTH_USER_ID);
        
        // Get wallet
        $wallet = $this->walletModel->getByUserId(AUTH_USER_ID);
        
        // Get products
        $products = $this->productModel->getBySeller(AUTH_USER_ID);
        $activeProducts = array_filter($products, fn($p) => $p['is_active']);
        
        // Get orders
        $orders = $this->orderModel->getBySeller(AUTH_USER_ID);
        $recentOrders = array_slice($orders, 0, 5);
        
        // Get stats
        $stats = $this->orderModel->getStatsBySeller(AUTH_USER_ID);
        
        return \ResponseHelper::success('Seller dashboard', [
            'seller' => [
                'username' => $seller['username'],
                'seller_level' => $seller['seller_level'],
                'total_sales' => $seller['total_sales'],
                'seller_verified' => $seller['seller_verified']
            ],
            'wallet' => $wallet,
            'stats' => [
                'total_products' => count($products),
                'active_products' => count($activeProducts),
                'total_orders' => $stats['total_orders'] ?? 0,
                'completed_orders' => $stats['completed_orders'] ?? 0,
                'total_revenue' => $stats['total_revenue'] ?? 0,
                'pending_balance' => $wallet['pending_balance'] ?? 0,
                'available_balance' => $wallet['balance'] ?? 0
            ],
            'recent_orders' => $recentOrders
        ]);
    }
    
    public function admin() {
        if (!defined('AUTH_USER_ID')) {
            return \ResponseHelper::unauthorized();
        }
        
        if (AUTH_USER_ROLE !== 'admin') {
            return \ResponseHelper::forbidden('Admin access only');
        }
        
        // Total users
        $totalUsers = $this->userModel->query("SELECT COUNT(*) as count FROM users WHERE deleted_at IS NULL")->fetch();
        $totalBuyers = $this->userModel->query("SELECT COUNT(*) as count FROM users WHERE role='buyer' AND deleted_at IS NULL")->fetch();
        $totalSellers = $this->userModel->query("SELECT COUNT(*) as count FROM users WHERE role='seller' AND deleted_at IS NULL")->fetch();
        
        // Total products
        $totalProducts = $this->productModel->query("SELECT COUNT(*) as count FROM products WHERE deleted_at IS NULL")->fetch();
        $activeProducts = $this->productModel->query("SELECT COUNT(*) as count FROM products WHERE is_active=1 AND deleted_at IS NULL")->fetch();
        
        // Total orders & revenue
        $orderStats = $this->orderModel->query(
            "SELECT 
                COUNT(*) as total_orders,
                SUM(CASE WHEN status='completed' THEN 1 ELSE 0 END) as completed_orders,
                SUM(CASE WHEN status='disputed' THEN 1 ELSE 0 END) as disputed_orders,
                SUM(CASE WHEN status='completed' THEN total_amount ELSE 0 END) as total_revenue,
                SUM(CASE WHEN status='completed' THEN platform_fee ELSE 0 END) as total_fees
             FROM orders"
        )->fetch();
        
        // Recent orders
        $recentOrders = $this->orderModel->query(
            "SELECT o.*, 
                    buyer.username as buyer_username,
                    seller.username as seller_username
             FROM orders o
             JOIN users buyer ON o.buyer_id = buyer.id
             JOIN users seller ON o.seller_id = seller.id
             ORDER BY o.created_at DESC
             LIMIT 10"
        )->fetchAll();
        
        return \ResponseHelper::success('Admin dashboard', [
            'stats' => [
                'total_users' => $totalUsers['count'],
                'total_buyers' => $totalBuyers['count'],
                'total_sellers' => $totalSellers['count'],
                'total_products' => $totalProducts['count'],
                'active_products' => $activeProducts['count'],
                'total_orders' => $orderStats['total_orders'],
                'completed_orders' => $orderStats['completed_orders'],
                'disputed_orders' => $orderStats['disputed_orders'],
                'total_revenue' => $orderStats['total_revenue'],
                'platform_revenue' => $orderStats['total_fees']
            ],
            'recent_orders' => $recentOrders
        ]);
    }
}

<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\User;
use App\Models\Product;
use App\Models\Order;

class AdminController extends Controller {
    private $userModel;
    private $productModel;
    private $orderModel;
    
    public function __construct() {
        $this->userModel = new User();
        $this->productModel = new Product();
        $this->orderModel = new Order();
    }
    
    private function checkAdmin() {
        if (!defined('AUTH_USER_ID') || AUTH_USER_ROLE !== 'admin') {
            \ResponseHelper::forbidden('Admin access only');
            return false;
        }
        return true;
    }
    
    public function users() {
        if (!$this->checkAdmin()) return;
        
        $page = $_GET['page'] ?? 1;
        $limit = $_GET['limit'] ?? 20;
        $offset = ($page - 1) * $limit;
        
        $users = $this->userModel->query(
            "SELECT id, email, username, role, full_name, email_verified_at, is_active, seller_verified, seller_level, total_sales, created_at
             FROM users 
             WHERE deleted_at IS NULL
             ORDER BY created_at DESC
             LIMIT :limit OFFSET :offset",
            ['limit' => $limit, 'offset' => $offset]
        )->fetchAll();
        
        return \ResponseHelper::success('Users list', [
            'users' => $users,
            'page' => (int)$page,
            'limit' => (int)$limit
        ]);
    }
    
    public function suspendUser($id) {
        if (!$this->checkAdmin()) return;
        
        $user = $this->userModel->findById($id);
        
        if (!$user) {
            return \ResponseHelper::notFound('User not found');
        }
        
        $this->userModel->update($id, [
            'is_active' => !$user['is_active']
        ]);
        
        $action = $user['is_active'] ? 'suspended' : 'activated';
        
        return \ResponseHelper::success("User {$action} successfully");
    }
    
    public function verifySeller($id) {
        if (!$this->checkAdmin()) return;
        
        $user = $this->userModel->findById($id);
        
        if (!$user || $user['role'] !== 'seller') {
            return \ResponseHelper::error('Invalid seller');
        }
        
        $this->userModel->update($id, [
            'seller_verified' => !$user['seller_verified']
        ]);
        
        $action = $user['seller_verified'] ? 'unverified' : 'verified';
        
        return \ResponseHelper::success("Seller {$action} successfully");
    }
    
    public function products() {
        if (!$this->checkAdmin()) return;
        
        $page = $_GET['page'] ?? 1;
        $limit = $_GET['limit'] ?? 20;
        $offset = ($page - 1) * $limit;
        
        $products = $this->productModel->query(
            "SELECT p.*, u.username as seller_username, c.name as category_name
             FROM products p
             JOIN users u ON p.seller_id = u.id
             JOIN categories c ON p.category_id = c.id
             WHERE p.deleted_at IS NULL
             ORDER BY p.created_at DESC
             LIMIT :limit OFFSET :offset",
            ['limit' => $limit, 'offset' => $offset]
        )->fetchAll();
        
        return \ResponseHelper::success('Products list', [
            'products' => $products,
            'page' => (int)$page,
            'limit' => (int)$limit
        ]);
    }
    
    public function moderateProduct($id) {
        if (!$this->checkAdmin()) return;
        
        $product = $this->productModel->findById($id);
        
        if (!$product) {
            return \ResponseHelper::notFound('Product not found');
        }
        
        $this->productModel->update($id, [
            'is_active' => !$product['is_active']
        ]);
        
        $action = $product['is_active'] ? 'deactivated' : 'activated';
        
        return \ResponseHelper::success("Product {$action} successfully");
    }
    
    public function orders() {
        if (!$this->checkAdmin()) return;
        
        $page = $_GET['page'] ?? 1;
        $limit = $_GET['limit'] ?? 20;
        $offset = ($page - 1) * $limit;
        
        $orders = $this->orderModel->query(
            "SELECT o.*, 
                    buyer.username as buyer_username,
                    seller.username as seller_username
             FROM orders o
             JOIN users buyer ON o.buyer_id = buyer.id
             JOIN users seller ON o.seller_id = seller.id
             ORDER BY o.created_at DESC
             LIMIT :limit OFFSET :offset",
            ['limit' => $limit, 'offset' => $offset]
        )->fetchAll();
        
        return \ResponseHelper::success('Orders list', [
            'orders' => $orders,
            'page' => (int)$page,
            'limit' => (int)$limit
        ]);
    }
    
    public function resolveDispute($id) {
        if (!$this->checkAdmin()) return;
        
        $input = $this->getInput();
        
        if (empty($input['resolution']) || !in_array($input['resolution'], ['completed', 'cancelled'])) {
            return \ResponseHelper::error('Invalid resolution. Use "completed" or "cancelled"');
        }
        
        $order = $this->orderModel->findById($id);
        
        if (!$order || $order['status'] !== 'disputed') {
            return \ResponseHelper::error('Order is not in disputed status');
        }
        
        $adminNotes = $input['admin_notes'] ?? 'Resolved by admin';
        
        $this->orderModel->resolveDispute($id, $input['resolution'], $adminNotes);
        
        // Handle refund if cancelled
        if ($input['resolution'] === 'cancelled' && $order['payment_method'] === 'wallet') {
            $walletModel = new \App\Models\Wallet();
            $transactionModel = new \App\Models\WalletTransaction();
            
            // Refund buyer
            $buyerWallet = $walletModel->getByUserId($order['buyer_id']);
            $walletModel->addBalance($buyerWallet['id'], $order['total_amount']);
            
            $transactionModel->record(
                $buyerWallet['id'],
                'refund',
                $order['total_amount'],
                $buyerWallet['balance'],
                $buyerWallet['balance'] + $order['total_amount'],
                "Dispute refund for order {$order['order_number']}",
                'order',
                $id
            );
            
            // Remove from seller pending
            $sellerWallet = $walletModel->getByUserId($order['seller_id']);
            $walletModel->refundPendingBalance($sellerWallet['id'], $order['subtotal']);
        }
        
        return \ResponseHelper::success('Dispute resolved successfully');
    }
}

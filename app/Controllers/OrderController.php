<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\Wallet;
use App\Models\WalletTransaction;
use App\Models\Notification;
use App\Services\EmailService;

class OrderController extends Controller {
    private $orderModel;
    private $productModel;
    private $walletModel;
    private $walletTransactionModel;
    private $notificationModel;
    private $emailService;
    
    public function __construct() {
        $this->orderModel = new Order();
        $this->productModel = new Product();
        $this->walletModel = new Wallet();
        $this->walletTransactionModel = new WalletTransaction();
        $this->notificationModel = new Notification();
        $this->emailService = new EmailService();
    }
    
    public function create() {
        if (!defined('AUTH_USER_ID')) {
            return \ResponseHelper::unauthorized();
        }
        
        $input = $this->getInput();
        
        $errors = $this->validate($input, [
            'product_id' => 'required',
            'quantity' => 'required',
            'payment_method' => 'required'
        ]);
        
        if ($errors !== true) {
            return \ResponseHelper::validationError($errors);
        }
        
        // Get product
        $product = $this->productModel->findById($input['product_id']);
        
        if (!$product) {
            return \ResponseHelper::notFound('Product not found');
        }
        
        // Check stock
        if ($product['stock_type'] === 'limited' && $product['stock_quantity'] < $input['quantity']) {
            return \ResponseHelper::error('Insufficient stock');
        }
        
        // Calculate amounts
        $subtotal = $product['discount_price'] ?? $product['price'];
        $subtotal *= $input['quantity'];
        
        $config = require __DIR__ . '/../../config/app.php';
        $platformFeePercentage = $config['platform_fee_percentage'];
        $platformFee = $subtotal * ($platformFeePercentage / 100);
        $totalAmount = $subtotal + $platformFee;
        
        // Generate order number
        $orderNumber = \StringHelper::generateOrderNumber();
        
        // Create order
        $orderId = $this->orderModel->create([
            'order_number' => $orderNumber,
            'buyer_id' => AUTH_USER_ID,
            'seller_id' => $product['seller_id'],
            'subtotal' => $subtotal,
            'platform_fee' => $platformFee,
            'total_amount' => $totalAmount,
            'payment_method' => $input['payment_method'],
            'status' => 'pending_payment',
            'buyer_notes' => $input['notes'] ?? null
        ]);
        
        // Create order item
        $this->orderModel->query(
            "INSERT INTO order_items (order_id, product_id, product_name, product_price, quantity, subtotal)
             VALUES (:order_id, :product_id, :product_name, :product_price, :quantity, :subtotal)",
            [
                'order_id' => $orderId,
                'product_id' => $product['id'],
                'product_name' => $product['name'],
                'product_price' => $product['discount_price'] ?? $product['price'],
                'quantity' => $input['quantity'],
                'subtotal' => $subtotal
            ]
        );
        
        // If payment method is wallet, process immediately
        if ($input['payment_method'] === 'wallet') {
            $buyerWallet = $this->walletModel->getByUserId(AUTH_USER_ID);
            
            if ($buyerWallet['balance'] < $totalAmount) {
                // Delete order - insufficient balance
                $this->orderModel->delete($orderId);
                return \ResponseHelper::error('Insufficient wallet balance');
            }
            
            // Deduct from buyer wallet
            $this->walletModel->deductBalance($buyerWallet['id'], $totalAmount);
            
            // Record transaction
            $balanceAfter = $buyerWallet['balance'] - $totalAmount;
            $this->walletTransactionModel->record(
                $buyerWallet['id'],
                'payment',
                $totalAmount,
                $buyerWallet['balance'],
                $balanceAfter,
                "Payment for order {$orderNumber}",
                'order',
                $orderId
            );
            
            // Add to seller pending balance (escrow)
            $sellerWallet = $this->walletModel->getByUserId($product['seller_id']);
            if (!$sellerWallet) {
                $sellerWalletId = $this->walletModel->createForUser($product['seller_id']);
                $sellerWallet = $this->walletModel->findById($sellerWalletId);
            }
            
            $sellerEarnings = $subtotal; // Seller gets subtotal (minus platform fee)
            $this->walletModel->addPendingBalance($sellerWallet['id'], $sellerEarnings);
            
            // Update order status
            $this->orderModel->update($orderId, [
                'status' => 'processing',
                'paid_at' => date('Y-m-d H:i:s')
            ]);
            
            // Notify seller
            $this->notificationModel->createNotification(
                $product['seller_id'],
                'new_order',
                'New Order Received',
                "You have a new order #{$orderNumber}",
                "/seller/orders/{$orderId}"
            );
        }
        
        // Send confirmation email
        $this->emailService->sendOrderConfirmation(
            $_SESSION['auth_user']->email ?? '',
            $orderNumber,
            $totalAmount
        );
        
        return \ResponseHelper::success('Order created successfully', [
            'order_id' => $orderId,
            'order_number' => $orderNumber,
            'total_amount' => $totalAmount
        ], 201);
    }
    
    public function index() {
        if (!defined('AUTH_USER_ID')) {
            return \ResponseHelper::unauthorized();
        }
        
        $orders = $this->orderModel->getByBuyer(AUTH_USER_ID);
        
        // Get order items for each order
        foreach ($orders as &$order) {
            $order['items'] = $this->orderModel->getItems($order['id']);
        }
        
        return \ResponseHelper::success('Your orders', ['orders' => $orders]);
    }
    
    public function show($id) {
        if (!defined('AUTH_USER_ID')) {
            return \ResponseHelper::unauthorized();
        }
        
        $order = $this->orderModel->getDetailById($id);
        
        if (!$order) {
            return \ResponseHelper::notFound('Order not found');
        }
        
        // Check authorization
        if ($order['buyer_id'] != AUTH_USER_ID && $order['seller_id'] != AUTH_USER_ID && AUTH_USER_ROLE !== 'admin') {
            return \ResponseHelper::forbidden('Access denied');
        }
        
        $order['items'] = $this->orderModel->getItems($id);
        
        return \ResponseHelper::success('Order details', ['order' => $order]);
    }
    
    public function uploadPayment($id) {
        if (!defined('AUTH_USER_ID')) {
            return \ResponseHelper::unauthorized();
        }
        
        $order = $this->orderModel->findById($id);
        
        if (!$order || $order['buyer_id'] != AUTH_USER_ID) {
            return \ResponseHelper::forbidden('Access denied');
        }
        
        if ($order['status'] !== 'pending_payment') {
            return \ResponseHelper::error('Cannot upload payment for this order status');
        }
        
        $input = $this->getInput();
        
        // Handle file upload (simplified for demo)
        $paymentProof = $input['payment_proof'] ?? 'payment_proof_' . time() . '.jpg';
        
        $this->orderModel->markAsPaid($id, $paymentProof);
        
        // Notify seller
        $this->notificationModel->createNotification(
            $order['seller_id'],
            'payment_uploaded',
            'Payment Proof Uploaded',
            "Buyer uploaded payment proof for order #{$order['order_number']}",
            "/seller/orders/{$id}"
        );
        
        return \ResponseHelper::success('Payment proof uploaded successfully');
    }
    
    public function deliverOrder($id) {
        if (!defined('AUTH_USER_ID')) {
            return \ResponseHelper::unauthorized();
        }
        
        $order = $this->orderModel->getDetailById($id);
        
        if (!$order || $order['seller_id'] != AUTH_USER_ID) {
            return \ResponseHelper::forbidden('Access denied');
        }
        
        if (!in_array($order['status'], ['processing', 'payment_uploaded'])) {
            return \ResponseHelper::error('Cannot deliver order in current status');
        }
        
        $input = $this->getInput();
        
        if (empty($input['digital_items'])) {
            return \ResponseHelper::error('Digital items content is required');
        }
        
        // Mark as delivered
        $this->orderModel->markAsDelivered($id, $input['digital_items']);
        
        // Update product sold count
        $items = $this->orderModel->getItems($id);
        foreach ($items as $item) {
            $this->productModel->incrementSold($item['product_id'], $item['quantity']);
        }
        
        // Notify buyer
        $this->notificationModel->createNotification(
            $order['buyer_id'],
            'order_delivered',
            'Order Delivered',
            "Your order #{$order['order_number']} has been delivered",
            "/orders/{$id}"
        );
        
        // Send email
        $this->emailService->sendOrderDelivered(
            $order['buyer_email'],
            $order['order_number']
        );
        
        return \ResponseHelper::success('Order delivered successfully');
    }
    
    public function confirmDelivery($id) {
        if (!defined('AUTH_USER_ID')) {
            return \ResponseHelper::unauthorized();
        }
        
        $order = $this->orderModel->getDetailById($id);
        
        if (!$order || $order['buyer_id'] != AUTH_USER_ID) {
            return \ResponseHelper::forbidden('Access denied');
        }
        
        if ($order['status'] !== 'delivered') {
            return \ResponseHelper::error('Order must be in delivered status');
        }
        
        // Mark as completed
        $this->orderModel->markAsCompleted($id);
        
        // Release escrow - transfer from pending to seller balance
        $sellerWallet = $this->walletModel->getByUserId($order['seller_id']);
        $sellerEarnings = $order['subtotal']; // Seller earnings (without platform fee)
        
        $this->walletModel->releasePendingBalance($sellerWallet['id'], $sellerEarnings);
        
        // Record seller transaction
        $balanceBefore = $sellerWallet['balance'];
        $balanceAfter = $balanceBefore + $sellerEarnings;
        
        $this->walletTransactionModel->record(
            $sellerWallet['id'],
            'earning',
            $sellerEarnings,
            $balanceBefore,
            $balanceAfter,
            "Earnings from order {$order['order_number']}",
            'order',
            $id
        );
        
        // Update seller stats
        $userModel = new \App\Models\User();
        $userModel->incrementSales($order['seller_id']);
        $userModel->updateSellerLevel($order['seller_id']);
        
        // Notify seller
        $this->notificationModel->createNotification(
            $order['seller_id'],
            'order_completed',
            'Order Completed',
            "Order #{$order['order_number']} completed. Payment released!",
            "/seller/orders/{$id}"
        );
        
        return \ResponseHelper::success('Order completed successfully. Payment released to seller.');
    }
    
    public function dispute($id) {
        if (!defined('AUTH_USER_ID')) {
            return \ResponseHelper::unauthorized();
        }
        
        $order = $this->orderModel->findById($id);
        
        if (!$order || $order['buyer_id'] != AUTH_USER_ID) {
            return \ResponseHelper::forbidden('Access denied');
        }
        
        $input = $this->getInput();
        
        if (empty($input['reason'])) {
            return \ResponseHelper::error('Dispute reason is required');
        }
        
        $this->orderModel->dispute($id, $input['reason']);
        
        // Notify admin
        $adminUsers = $this->orderModel->query("SELECT id FROM users WHERE role = 'admin'");
        foreach ($adminUsers->fetchAll() as $admin) {
            $this->notificationModel->createNotification(
                $admin['id'],
                'dispute',
                'Order Dispute',
                "Dispute raised for order #{$order['order_number']}",
                "/admin/orders/{$id}"
            );
        }
        
        return \ResponseHelper::success('Dispute submitted successfully. Admin will review your case.');
    }
    
    public function cancel($id) {
        if (!defined('AUTH_USER_ID')) {
            return \ResponseHelper::unauthorized();
        }
        
        $order = $this->orderModel->getDetailById($id);
        
        if (!$order || $order['buyer_id'] != AUTH_USER_ID) {
            return \ResponseHelper::forbidden('Access denied');
        }
        
        if (!in_array($order['status'], ['pending_payment', 'payment_uploaded'])) {
            return \ResponseHelper::error('Cannot cancel order in current status');
        }
        
        // Refund if already paid
        if ($order['status'] === 'payment_uploaded' && $order['payment_method'] === 'wallet') {
            $buyerWallet = $this->walletModel->getByUserId(AUTH_USER_ID);
            $this->walletModel->addBalance($buyerWallet['id'], $order['total_amount']);
            
            // Record refund transaction
            $balanceBefore = $buyerWallet['balance'];
            $balanceAfter = $balanceBefore + $order['total_amount'];
            
            $this->walletTransactionModel->record(
                $buyerWallet['id'],
                'refund',
                $order['total_amount'],
                $balanceBefore,
                $balanceAfter,
                "Refund for cancelled order {$order['order_number']}",
                'order',
                $id
            );
            
            // Refund seller pending balance
            $sellerWallet = $this->walletModel->getByUserId($order['seller_id']);
            $this->walletModel->refundPendingBalance($sellerWallet['id'], $order['subtotal']);
        }
        
        $this->orderModel->updateStatus($id, 'cancelled');
        
        return \ResponseHelper::success('Order cancelled successfully');
    }
    
    public function sellerOrders() {
        if (!defined('AUTH_USER_ID')) {
            return \ResponseHelper::unauthorized();
        }
        
        if (AUTH_USER_ROLE !== 'seller') {
            return \ResponseHelper::forbidden('Only sellers can access this endpoint');
        }
        
        $orders = $this->orderModel->getBySeller(AUTH_USER_ID);
        
        // Get order items
        foreach ($orders as &$order) {
            $order['items'] = $this->orderModel->getItems($order['id']);
        }
        
        return \ResponseHelper::success('Your seller orders', ['orders' => $orders]);
    }
}

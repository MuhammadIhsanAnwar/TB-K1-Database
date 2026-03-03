<?php

namespace App\Models;

use App\Core\Model;

class Order extends Model {
    protected $table = 'orders';
    
    public function getByBuyer($buyerId) {
        $stmt = $this->query(
            "SELECT o.*, u.username as seller_username
             FROM {$this->table} o
             JOIN users u ON o.seller_id = u.id
             WHERE o.buyer_id = :buyer_id
             ORDER BY o.created_at DESC",
            ['buyer_id' => $buyerId]
        );
        return $stmt->fetchAll();
    }
    
    public function getBySeller($sellerId) {
        $stmt = $this->query(
            "SELECT o.*, u.username as buyer_username
             FROM {$this->table} o
             JOIN users u ON o.buyer_id = u.id
             WHERE o.seller_id = :seller_id
             ORDER BY o.created_at DESC",
            ['sellerId' => $sellerId]
        );
        return $stmt->fetchAll();
    }
    
    public function getDetailById($id) {
        $stmt = $this->query(
            "SELECT o.*, 
                    buyer.username as buyer_username, buyer.email as buyer_email,
                    seller.username as seller_username, seller.email as seller_email
             FROM {$this->table} o
             JOIN users buyer ON o.buyer_id = buyer.id
             JOIN users seller ON o.seller_id = seller.id
             WHERE o.id = :id
             LIMIT 1",
            ['id' => $id]
        );
        return $stmt->fetch();
    }
    
    public function getItems($orderId) {
        $stmt = $this->query(
            "SELECT oi.*, p.thumbnail, p.slug
             FROM order_items oi
             JOIN products p ON oi.product_id = p.id
             WHERE oi.order_id = :order_id",
            ['order_id' => $orderId]
        );
        return $stmt->fetchAll();
    }
    
    public function updateStatus($id, $status) {
        return $this->update($id, ['status' => $status]);
    }
    
    public function markAsPaid($id, $paymentProof = null) {
        $data = [
            'status' => 'payment_uploaded',
            'paid_at' => date('Y-m-d H:i:s')
        ];
        
        if ($paymentProof) {
            $data['payment_proof'] = $paymentProof;
        }
        
        return $this->update($id, $data);
    }
    
    public function markAsDelivered($id, $digitalItems) {
        return $this->update($id, [
            'status' => 'delivered',
            'digital_items' => json_encode($digitalItems),
            'delivered_at' => date('Y-m-d H:i:s')
        ]);
    }
    
    public function markAsCompleted($id) {
        return $this->update($id, [
            'status' => 'completed',
            'completed_at' => date('Y-m-d H:i:s')
        ]);
    }
    
    public function dispute($id, $reason) {
        return $this->update($id, [
            'status' => 'disputed',
            'dispute_reason' => $reason,
            'disputed_at' => date('Y-m-d H:i:s')
        ]);
    }
    
    public function resolveDispute($id, $resolution, $adminNotes) {
        return $this->update($id, [
            'status' => $resolution,
            'admin_notes' => $adminNotes,
            'resolved_at' => date('Y-m-d H:i:s')
        ]);
    }
    
    public function getStatsBySeller($sellerId) {
        $stmt = $this->query(
            "SELECT 
                COUNT(*) as total_orders,
                SUM(CASE WHEN status = 'completed' THEN 1 ELSE 0 END) as completed_orders,
                SUM(CASE WHEN status = 'completed' THEN total_amount ELSE 0 END) as total_revenue,
                SUM(CASE WHEN status = 'completed' THEN platform_fee ELSE 0 END) as total_fees
             FROM {$this->table}
             WHERE seller_id = :seller_id",
            ['seller_id' => $sellerId]
        );
        return $stmt->fetch();
    }
}

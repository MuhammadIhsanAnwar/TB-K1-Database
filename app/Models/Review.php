<?php

namespace App\Models;

use App\Core\Model;

class Review extends Model {
    protected $table = 'reviews';
    
    public function getByProduct($productId, $limit = 20) {
        $stmt = $this->query(
            "SELECT r.*, u.username as buyer_username, u.avatar as buyer_avatar
             FROM {$this->table} r
             JOIN users u ON r.buyer_id = u.id
             WHERE r.product_id = :product_id
             ORDER BY r.created_at DESC
             LIMIT :limit",
            ['product_id' => $productId, 'limit' => $limit]
        );
        return $stmt->fetchAll();
    }
    
    public function createReview($orderId, $productId, $buyerId, $sellerId, $rating, $comment) {
        return $this->create([
            'order_id' => $orderId,
            'product_id' => $productId,
            'buyer_id' => $buyerId,
            'seller_id' => $sellerId,
            'rating' => $rating,
            'comment' => $comment
        ]);
    }
    
    public function addSellerResponse($reviewId, $response) {
        return $this->update($reviewId, [
            'seller_response' => $response,
            'responded_at' => date('Y-m-d H:i:s')
        ]);
    }
    
    public function canReview($orderId) {
        $review = $this->findOne(['order_id' => $orderId]);
        return $review === false;
    }
}

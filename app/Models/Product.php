<?php

namespace App\Models;

use App\Core\Model;

class Product extends Model {
    protected $table = 'products';
    
    public function getAllActive($limit = 20, $offset = 0) {
        $stmt = $this->query(
            "SELECT p.*, u.username as seller_username, u.seller_level, c.name as category_name
             FROM {$this->table} p
             JOIN users u ON p.seller_id = u.id
             JOIN categories c ON p.category_id = c.id
             WHERE p.is_active = 1 AND p.deleted_at IS NULL
             ORDER BY p.created_at DESC
             LIMIT :limit OFFSET :offset",
            ['limit' => $limit, 'offset' => $offset]
        );
        return $stmt->fetchAll();
    }
    
    public function getByCategory($categoryId, $limit = 20, $offset = 0) {
        $stmt = $this->query(
            "SELECT p.*, u.username as seller_username, u.seller_level, c.name as category_name
             FROM {$this->table} p
             JOIN users u ON p.seller_id = u.id
             JOIN categories c ON p.category_id = c.id
             WHERE p.category_id = :category_id 
             AND p.is_active = 1 AND p.deleted_at IS NULL
             ORDER BY p.created_at DESC
             LIMIT :limit OFFSET :offset",
            ['category_id' => $categoryId, 'limit' => $limit, 'offset' => $offset]
        );
        return $stmt->fetchAll();
    }
    
    public function search($keyword, $limit = 20) {
        $stmt = $this->query(
            "SELECT p.*, u.username as seller_username, u.seller_level, c.name as category_name
             FROM {$this->table} p
             JOIN users u ON p.seller_id = u.id
             JOIN categories c ON p.category_id = c.id
             WHERE (p.name LIKE :keyword OR p.description LIKE :keyword)
             AND p.is_active = 1 AND p.deleted_at IS NULL
             ORDER BY p.sold_count DESC
             LIMIT :limit",
            ['keyword' => "%{$keyword}%", 'limit' => $limit]
        );
        return $stmt->fetchAll();
    }
    
    public function getDetailById($id) {
        $stmt = $this->query(
            "SELECT p.*, u.username as seller_username, u.seller_level, u.total_sales as seller_total_sales,
                    c.name as category_name, c.slug as category_slug
             FROM {$this->table} p
             JOIN users u ON p.seller_id = u.id
             JOIN categories c ON p.category_id = c.id
             WHERE p.id = :id AND p.deleted_at IS NULL
             LIMIT 1",
            ['id' => $id]
        );
        return $stmt->fetch();
    }
    
    public function getBySeller($sellerId) {
        $stmt = $this->query(
            "SELECT p.*, c.name as category_name
             FROM {$this->table} p
             JOIN categories c ON p.category_id = c.id
             WHERE p.seller_id = :seller_id AND p.deleted_at IS NULL
             ORDER BY p.created_at DESC",
            ['seller_id' => $sellerId]
        );
        return $stmt->fetchAll();
    }
    
    public function incrementView($id) {
        $stmt = $this->query(
            "UPDATE {$this->table} SET view_count = view_count + 1 WHERE id = :id",
            ['id' => $id]
        );
        return $stmt->rowCount() > 0;
    }
    
    public function incrementSold($id, $quantity = 1) {
        $stmt = $this->query(
            "UPDATE {$this->table} 
             SET sold_count = sold_count + :quantity,
                 stock_quantity = stock_quantity - :quantity
             WHERE id = :id",
            ['id' => $id, 'quantity' => $quantity]
        );
        return $stmt->rowCount() > 0;
    }
    
    public function updateRating($productId) {
        $stmt = $this->query(
            "SELECT AVG(rating) as avg_rating, COUNT(*) as count
             FROM reviews
             WHERE product_id = :product_id",
            ['product_id' => $productId]
        );
        $result = $stmt->fetch();
        
        if ($result) {
            $this->update($productId, [
                'rating_avg' => round($result['avg_rating'], 2),
                'rating_count' => $result['count']
            ]);
        }
    }
    
    public function getFeatured($limit = 10) {
        $stmt = $this->query(
            "SELECT p.*, u.username as seller_username, u.seller_level, c.name as category_name
             FROM {$this->table} p
             JOIN users u ON p.seller_id = u.id
             JOIN categories c ON p.category_id = c.id
             WHERE p.is_featured = 1 AND p.is_active = 1 AND p.deleted_at IS NULL
             ORDER BY p.sold_count DESC
             LIMIT :limit",
            ['limit' => $limit]
        );
        return $stmt->fetchAll();
    }
    
    public function getTrending($limit = 10) {
        $stmt = $this->query(
            "SELECT p.*, u.username as seller_username, u.seller_level, c.name as category_name
             FROM {$this->table} p
             JOIN users u ON p.seller_id = u.id
             JOIN categories c ON p.category_id = c.id
             WHERE p.is_active = 1 AND p.deleted_at IS NULL
             AND p.created_at >= DATE_SUB(NOW(), INTERVAL 30 DAY)
             ORDER BY (p.sold_count * 0.7 + p.view_count * 0.3) DESC
             LIMIT :limit",
            ['limit' => $limit]
        );
        return $stmt->fetchAll();
    }
    
    public function softDelete($id) {
        return $this->update($id, ['deleted_at' => date('Y-m-d H:i:s')]);
    }
}

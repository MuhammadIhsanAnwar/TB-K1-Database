<?php

namespace App\Models;

use App\Core\Model;

class Category extends Model {
    protected $table = 'categories';
    
    public function getAllActive() {
        $stmt = $this->query(
            "SELECT * FROM {$this->table}
             WHERE is_active = 1
             ORDER BY sort_order ASC, name ASC"
        );
        return $stmt->fetchAll();
    }
    
    public function getParentCategories() {
        $stmt = $this->query(
            "SELECT * FROM {$this->table}
             WHERE parent_id IS NULL AND is_active = 1
             ORDER BY sort_order ASC"
        );
        return $stmt->fetchAll();
    }
    
    public function getSubcategories($parentId) {
        $stmt = $this->query(
            "SELECT * FROM {$this->table}
             WHERE parent_id = :parent_id AND is_active = 1
             ORDER BY sort_order ASC",
            ['parent_id' => $parentId]
        );
        return $stmt->fetchAll();
    }
    
    public function findBySlug($slug) {
        return $this->findOne(['slug' => $slug]);
    }
}

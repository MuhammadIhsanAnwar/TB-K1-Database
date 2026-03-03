<?php

namespace App\Models;

use App\Core\Model;

class Notification extends Model {
    protected $table = 'notifications';
    
    public function getByUser($userId, $limit = 20) {
        $stmt = $this->query(
            "SELECT * FROM {$this->table}
             WHERE user_id = :user_id
             ORDER BY created_at DESC
             LIMIT :limit",
            ['user_id' => $userId, 'limit' => $limit]
        );
        return $stmt->fetchAll();
    }
    
    public function createNotification($userId, $type, $title, $message, $link = null) {
        return $this->create([
            'user_id' => $userId,
            'type' => $type,
            'title' => $title,
            'message' => $message,
            'link' => $link,
            'is_read' => false
        ]);
    }
    
    public function markAsRead($id) {
        return $this->update($id, [
            'is_read' => true,
            'read_at' => date('Y-m-d H:i:s')
        ]);
    }
    
    public function markAllAsRead($userId) {
        $stmt = $this->query(
            "UPDATE {$this->table} 
             SET is_read = 1, read_at = NOW()
             WHERE user_id = :user_id AND is_read = 0",
            ['user_id' => $userId]
        );
        return $stmt->rowCount();
    }
    
    public function getUnreadCount($userId) {
        $stmt = $this->query(
            "SELECT COUNT(*) as count FROM {$this->table}
             WHERE user_id = :user_id AND is_read = 0",
            ['user_id' => $userId]
        );
        $result = $stmt->fetch();
        return $result['count'] ?? 0;
    }
}

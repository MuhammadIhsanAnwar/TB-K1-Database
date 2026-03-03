<?php

namespace App\Models;

use App\Core\Model;

class Message extends Model {
    protected $table = 'messages';
    
    public function getConversations($userId) {
        $stmt = $this->query(
            "SELECT 
                CASE 
                    WHEN sender_id = :user_id THEN receiver_id 
                    ELSE sender_id 
                END as contact_id,
                u.username as contact_username,
                u.avatar as contact_avatar,
                MAX(m.created_at) as last_message_time,
                (SELECT message FROM messages 
                 WHERE (sender_id = contact_id AND receiver_id = :user_id) 
                    OR (sender_id = :user_id AND receiver_id = contact_id)
                 ORDER BY created_at DESC LIMIT 1) as last_message,
                SUM(CASE WHEN receiver_id = :user_id AND is_read = 0 THEN 1 ELSE 0 END) as unread_count
             FROM {$this->table} m
             JOIN users u ON (CASE WHEN m.sender_id = :user_id THEN m.receiver_id ELSE m.sender_id END) = u.id
             WHERE sender_id = :user_id OR receiver_id = :user_id
             GROUP BY contact_id, u.username, u.avatar
             ORDER BY last_message_time DESC",
            ['user_id' => $userId]
        );
        return $stmt->fetchAll();
    }
    
    public function getMessages($user1Id, $user2Id, $limit = 50) {
        $stmt = $this->query(
            "SELECT m.*, 
                    sender.username as sender_username,
                    receiver.username as receiver_username
             FROM {$this->table} m
             JOIN users sender ON m.sender_id = sender.id
             JOIN users receiver ON m.receiver_id = receiver.id
             WHERE (sender_id = :user1 AND receiver_id = :user2)
                OR (sender_id = :user2 AND receiver_id = :user1)
             ORDER BY created_at DESC
             LIMIT :limit",
            ['user1' => $user1Id, 'user2' => $user2Id, 'limit' => $limit]
        );
        return array_reverse($stmt->fetchAll());
    }
    
    public function send($senderId, $receiverId, $message, $orderId = null) {
        return $this->create([
            'sender_id' => $senderId,
            'receiver_id' => $receiverId,
            'message' => $message,
            'order_id' => $orderId,
            'is_read' => false
        ]);
    }
    
    public function markAsRead($messageId) {
        return $this->update($messageId, [
            'is_read' => true,
            'read_at' => date('Y-m-d H:i:s')
        ]);
    }
    
    public function markConversationAsRead($userId, $contactId) {
        $stmt = $this->query(
            "UPDATE {$this->table} 
             SET is_read = 1, read_at = NOW()
             WHERE receiver_id = :user_id AND sender_id = :contact_id AND is_read = 0",
            ['user_id' => $userId, 'contact_id' => $contactId]
        );
        return $stmt->rowCount();
    }
    
    public function getUnreadCount($userId) {
        $stmt = $this->query(
            "SELECT COUNT(*) as count FROM {$this->table}
             WHERE receiver_id = :user_id AND is_read = 0",
            ['user_id' => $userId]
        );
        $result = $stmt->fetch();
        return $result['count'] ?? 0;
    }
}

<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Message;

class ChatController extends Controller {
    private $messageModel;
    
    public function __construct() {
        $this->messageModel = new Message();
    }
    
    public function conversations() {
        if (!defined('AUTH_USER_ID')) {
            return \ResponseHelper::unauthorized();
        }
        
        $conversations = $this->messageModel->getConversations(AUTH_USER_ID);
        
        return \ResponseHelper::success('Conversations', [
            'conversations' => $conversations
        ]);
    }
    
    public function messages($userId) {
        if (!defined('AUTH_USER_ID')) {
            return \ResponseHelper::unauthorized();
        }
        
        $limit = $_GET['limit'] ?? 50;
        $messages = $this->messageModel->getMessages(AUTH_USER_ID, $userId, $limit);
        
        // Mark as read
        $this->messageModel->markConversationAsRead(AUTH_USER_ID, $userId);
        
        return \ResponseHelper::success('Messages', [
            'messages' => $messages
        ]);
    }
    
    public function send($userId) {
        if (!defined('AUTH_USER_ID')) {
            return \ResponseHelper::unauthorized();
        }
        
        $input = $this->getInput();
        
        if (empty($input['message'])) {
            return \ResponseHelper::error('Message cannot be empty');
        }
        
        $messageId = $this->messageModel->send(
            AUTH_USER_ID,
            $userId,
            \SecurityHelper::sanitize($input['message']),
            $input['order_id'] ?? null
        );
        
        return \ResponseHelper::success('Message sent', [
            'message_id' => $messageId
        ], 201);
    }
    
    public function markAsRead($messageId) {
        if (!defined('AUTH_USER_ID')) {
            return \ResponseHelper::unauthorized();
        }
        
        $message = $this->messageModel->findById($messageId);
        
        if (!$message || $message['receiver_id'] != AUTH_USER_ID) {
            return \ResponseHelper::forbidden('Access denied');
        }
        
        $this->messageModel->markAsRead($messageId);
        
        return \ResponseHelper::success('Message marked as read');
    }
}

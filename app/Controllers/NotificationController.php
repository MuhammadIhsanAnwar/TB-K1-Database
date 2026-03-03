<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Notification;

class NotificationController extends Controller {
    private $notificationModel;
    
    public function __construct() {
        $this->notificationModel = new Notification();
    }
    
    public function index() {
        if (!defined('AUTH_USER_ID')) {
            return \ResponseHelper::unauthorized();
        }
        
        $limit = $_GET['limit'] ?? 20;
        $notifications = $this->notificationModel->getByUser(AUTH_USER_ID, $limit);
        
        return \ResponseHelper::success('Notifications', [
            'notifications' => $notifications
        ]);
    }
    
    public function markAsRead($id) {
        if (!defined('AUTH_USER_ID')) {
            return \ResponseHelper::unauthorized();
        }
        
        $notification = $this->notificationModel->findById($id);
        
        if (!$notification || $notification['user_id'] != AUTH_USER_ID) {
            return \ResponseHelper::forbidden('Access denied');
        }
        
        $this->notificationModel->markAsRead($id);
        
        return \ResponseHelper::success('Notification marked as read');
    }
    
    public function markAllAsRead() {
        if (!defined('AUTH_USER_ID')) {
            return \ResponseHelper::unauthorized();
        }
        
        $count = $this->notificationModel->markAllAsRead(AUTH_USER_ID);
        
        return \ResponseHelper::success("Marked {$count} notifications as read");
    }
    
    public function unreadCount() {
        if (!defined('AUTH_USER_ID')) {
            return \ResponseHelper::unauthorized();
        }
        
        $count = $this->notificationModel->getUnreadCount(AUTH_USER_ID);
        
        return \ResponseHelper::success('Unread count', [
            'unread_count' => $count
        ]);
    }
}

<?php

namespace App\Models;

use App\Core\Model;

class User extends Model {
    protected $table = 'users';
    
    public function findByEmail($email) {
        return $this->findOne(['email' => $email]);
    }
    
    public function findByUsername($username) {
        return $this->findOne(['username' => $username]);
    }
    
    public function findByVerificationToken($token) {
        return $this->findOne(['verification_token' => $token]);
    }
    
    public function findByResetToken($token) {
        $stmt = $this->query(
            "SELECT * FROM {$this->table} 
             WHERE reset_token = :token 
             AND reset_token_expires_at > NOW() 
             LIMIT 1",
            ['token' => $token]
        );
        return $stmt->fetch();
    }
    
    public function verifyEmail($id) {
        return $this->update($id, [
            'email_verified_at' => date('Y-m-d H:i:s'),
            'verification_token' => null
        ]);
    }
    
    public function updateLoginAttempts($id, $attempts) {
        $lockout = null;
        if ($attempts >= 5) {
            $lockout = date('Y-m-d H:i:s', strtotime('+15 minutes'));
        }
        
        return $this->update($id, [
            'login_attempts' => $attempts,
            'locked_until' => $lockout
        ]);
    }
    
    public function resetLoginAttempts($id) {
        return $this->update($id, [
            'login_attempts' => 0,
            'locked_until' => null
        ]);
    }
    
    public function isLocked($user) {
        if (!$user['locked_until']) {
            return false;
        }
        
        return strtotime($user['locked_until']) > time();
    }
    
    public function updateSellerLevel($id) {
        $user = $this->findById($id);
        $config = require __DIR__ . '/../../config/app.php';
        $levels = $config['seller_levels'];
        
        $totalSales = $user['total_sales'];
        $newLevel = 'bronze';
        
        foreach ($levels as $level => $range) {
            if ($totalSales >= $range['min_sales'] && $totalSales <= $range['max_sales']) {
                $newLevel = $level;
                break;
            }
        }
        
        if ($user['seller_level'] !== $newLevel) {
            $this->update($id, ['seller_level' => $newLevel]);
        }
        
        return $newLevel;
    }
    
    public function incrementSales($id) {
        $stmt = $this->query(
            "UPDATE {$this->table} SET total_sales = total_sales + 1 WHERE id = :id",
            ['id' => $id]
        );
        return $stmt->rowCount() > 0;
    }
    
    public function softDelete($id) {
        return $this->update($id, ['deleted_at' => date('Y-m-d H:i:s')]);
    }
}

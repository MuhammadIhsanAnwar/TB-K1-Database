<?php

namespace App\Models;

use App\Core\Model;

class Wallet extends Model {
    protected $table = 'wallets';
    
    public function getByUserId($userId) {
        return $this->findOne(['user_id' => $userId]);
    }
    
    public function createForUser($userId) {
        return $this->create([
            'user_id' => $userId,
            'balance' => 0.00,
            'pending_balance' => 0.00,
            'total_earned' => 0.00,
            'total_spent' => 0.00
        ]);
    }
    
    public function addBalance($walletId, $amount) {
        $stmt = $this->query(
            "UPDATE {$this->table} 
             SET balance = balance + :amount,
                 total_earned = total_earned + :amount
             WHERE id = :id",
            ['id' => $walletId, 'amount' => $amount]
        );
        return $stmt->rowCount() > 0;
    }
    
    public function deductBalance($walletId, $amount) {
        // Check if sufficient balance
        $wallet = $this->findById($walletId);
        if ($wallet['balance'] < $amount) {
            return false;
        }
        
        $stmt = $this->query(
            "UPDATE {$this->table} 
             SET balance = balance - :amount,
                 total_spent = total_spent + :amount
             WHERE id = :id",
            ['id' => $walletId, 'amount' => $amount]
        );
        return $stmt->rowCount() > 0;
    }
    
    public function addPendingBalance($walletId, $amount) {
        $stmt = $this->query(
            "UPDATE {$this->table} 
             SET pending_balance = pending_balance + :amount
             WHERE id = :id",
            ['id' => $walletId, 'amount' => $amount]
        );
        return $stmt->rowCount() > 0;
    }
    
    public function releasePendingBalance($walletId, $amount) {
        $stmt = $this->query(
            "UPDATE {$this->table} 
             SET pending_balance = pending_balance - :amount,
                 balance = balance + :amount,
                 total_earned = total_earned + :amount
             WHERE id = :id",
            ['id' => $walletId, 'amount' => $amount]
        );
        return $stmt->rowCount() > 0;
    }
    
    public function refundPendingBalance($walletId, $amount) {
        $stmt = $this->query(
            "UPDATE {$this->table} 
             SET pending_balance = pending_balance - :amount
             WHERE id = :id",
            ['id' => $walletId, 'amount' => $amount]
        );
        return $stmt->rowCount() > 0;
    }
}

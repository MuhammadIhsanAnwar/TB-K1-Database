<?php

namespace App\Models;

use App\Core\Model;

class WalletTransaction extends Model {
    protected $table = 'wallet_transactions';
    
    public function getByWallet($walletId, $limit = 50) {
        $stmt = $this->query(
            "SELECT * FROM {$this->table}
             WHERE wallet_id = :wallet_id
             ORDER BY created_at DESC
             LIMIT :limit",
            ['wallet_id' => $walletId, 'limit' => $limit]
        );
        return $stmt->fetchAll();
    }
    
    public function record($walletId, $type, $amount, $balanceBefore, $balanceAfter, $description, $referenceType = null, $referenceId = null) {
        return $this->create([
            'wallet_id' => $walletId,
            'type' => $type,
            'amount' => $amount,
            'balance_before' => $balanceBefore,
            'balance_after' => $balanceAfter,
            'description' => $description,
            'reference_type' => $referenceType,
            'reference_id' => $referenceId,
            'status' => 'completed'
        ]);
    }
}

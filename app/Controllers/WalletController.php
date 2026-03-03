<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Wallet;
use App\Models\WalletTransaction;

class WalletController extends Controller {
    private $walletModel;
    private $walletTransactionModel;
    
    public function __construct() {
        $this->walletModel = new Wallet();
        $this->walletTransactionModel = new WalletTransaction();
    }
    
    public function balance() {
        if (!defined('AUTH_USER_ID')) {
            return \ResponseHelper::unauthorized();
        }
        
        $wallet = $this->walletModel->getByUserId(AUTH_USER_ID);
        
        if (!$wallet) {
            // Create wallet if doesn't exist
            $walletId = $this->walletModel->createForUser(AUTH_USER_ID);
            $wallet = $this->walletModel->findById($walletId);
        }
        
        return \ResponseHelper::success('Wallet balance', [
            'wallet' => $wallet
        ]);
    }
    
    public function transactions() {
        if (!defined('AUTH_USER_ID')) {
            return \ResponseHelper::unauthorized();
        }
        
        $wallet = $this->walletModel->getByUserId(AUTH_USER_ID);
        
        if (!$wallet) {
            return \ResponseHelper::success('Transactions', ['transactions' => []]);
        }
        
        $limit = $_GET['limit'] ?? 50;
        $transactions = $this->walletTransactionModel->getByWallet($wallet['id'], $limit);
        
        return \ResponseHelper::success('Wallet transactions', [
            'transactions' => $transactions
        ]);
    }
    
    public function deposit() {
        if (!defined('AUTH_USER_ID')) {
            return \ResponseHelper::unauthorized();
        }
        
        $input = $this->getInput();
        
        if (empty($input['amount']) || $input['amount'] <= 0) {
            return \ResponseHelper::error('Invalid deposit amount');
        }
        
        // In production, this would integrate with payment gateway
        // For demo, we simulate deposit
        
        $wallet = $this->walletModel->getByUserId(AUTH_USER_ID);
        
        if (!$wallet) {
            $walletId = $this->walletModel->createForUser(AUTH_USER_ID);
            $wallet = $this->walletModel->findById($walletId);
        }
        
        $amount = $input['amount'];
        $balanceBefore = $wallet['balance'];
        
        // Add balance
        $this->walletModel->addBalance($wallet['id'], $amount);
        
        $balanceAfter = $balanceBefore + $amount;
        
        // Record transaction
        $this->walletTransactionModel->record(
            $wallet['id'],
            'deposit',
            $amount,
            $balanceBefore,
            $balanceAfter,
            "Deposit via {$input['payment_method']}",
            null,
            null
        );
        
        return \ResponseHelper::success('Deposit successful', [
            'new_balance' => $balanceAfter
        ]);
    }
    
    public function withdraw() {
        if (!defined('AUTH_USER_ID')) {
            return \ResponseHelper::unauthorized();
        }
        
        $input = $this->getInput();
        
        $errors = $this->validate($input, [
            'amount' => 'required',
            'bank_name' => 'required',
            'account_number' => 'required',
            'account_name' => 'required'
        ]);
        
        if ($errors !== true) {
            return \ResponseHelper::validationError($errors);
        }
        
        $amount = $input['amount'];
        
        if ($amount <= 0) {
            return \ResponseHelper::error('Invalid withdrawal amount');
        }
        
        $wallet = $this->walletModel->getByUserId(AUTH_USER_ID);
        
        if (!$wallet) {
            return \ResponseHelper::error('Wallet not found');
        }
        
        if ($wallet['balance'] < $amount) {
            return \ResponseHelper::error('Insufficient balance');
        }
        
        $balanceBefore = $wallet['balance'];
        
        // Deduct balance
        $success = $this->walletModel->deductBalance($wallet['id'], $amount);
        
        if (!$success) {
            return \ResponseHelper::error('Withdrawal failed');
        }
        
        $balanceAfter = $balanceBefore - $amount;
        
        // Record transaction
        $description = "Withdrawal to {$input['bank_name']} - {$input['account_number']}";
        
        $this->walletTransactionModel->record(
            $wallet['id'],
            'withdraw',
            $amount,
            $balanceBefore,
            $balanceAfter,
            $description,
            'withdrawal',
            null
        );
        
        // In production, this would create a withdrawal request for admin approval
        
        return \ResponseHelper::success('Withdrawal request submitted', [
            'new_balance' => $balanceAfter
        ]);
    }
}

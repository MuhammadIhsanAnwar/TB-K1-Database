<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\User;
use App\Models\Wallet;

class UserController extends Controller {
    private $userModel;
    private $walletModel;
    
    public function __construct() {
        $this->userModel = new User();
        $this->walletModel = new Wallet();
    }
    
    public function profile() {
        if (!defined('AUTH_USER_ID')) {
            return \ResponseHelper::unauthorized();
        }
        
        $user = $this->userModel->findById(AUTH_USER_ID);
        
        if (!$user) {
            return \ResponseHelper::notFound('User not found');
        }
        
        // Get wallet
        $wallet = $this->walletModel->getByUserId(AUTH_USER_ID);
        
        // Remove sensitive data
        unset($user['password']);
        unset($user['verification_token']);
        unset($user['reset_token']);
        
        return \ResponseHelper::success('User profile', [
            'user' => $user,
            'wallet' => $wallet
        ]);
    }
    
    public function updateProfile() {
        if (!defined('AUTH_USER_ID')) {
            return \ResponseHelper::unauthorized();
        }
        
        $input = $this->getInput();
        $updateData = [];
        
        // Allowed fields for update
        $allowedFields = ['full_name', 'phone', 'avatar'];
        
        foreach ($allowedFields as $field) {
            if (isset($input[$field])) {
                $updateData[$field] = \SecurityHelper::sanitize($input[$field]);
            }
        }
        
        if (empty($updateData)) {
            return \ResponseHelper::error('No data to update');
        }
        
        $this->userModel->update(AUTH_USER_ID, $updateData);
        
        return \ResponseHelper::success('Profile updated successfully');
    }
    
    public function changePassword() {
        if (!defined('AUTH_USER_ID')) {
            return \ResponseHelper::unauthorized();
        }
        
        $input = $this->getInput();
        
        $errors = $this->validate($input, [
            'current_password' => 'required',
            'new_password' => 'required|min:8'
        ]);
        
        if ($errors !== true) {
            return \ResponseHelper::validationError($errors);
        }
        
        $user = $this->userModel->findById(AUTH_USER_ID);
        
        // Verify current password
        if (!\SecurityHelper::verifyPassword($input['current_password'], $user['password'])) {
            return \ResponseHelper::error('Current password is incorrect');
        }
        
        // Update password
        $this->userModel->update(AUTH_USER_ID, [
            'password' => \SecurityHelper::hashPassword($input['new_password'])
        ]);
        
        return \ResponseHelper::success('Password changed successfully');
    }
    
    public function deleteAccount() {
        if (!defined('AUTH_USER_ID')) {
            return \ResponseHelper::unauthorized();
        }
        
        $input = $this->getInput();
        
        if (empty($input['password'])) {
            return \ResponseHelper::error('Password confirmation required');
        }
        
        $user = $this->userModel->findById(AUTH_USER_ID);
        
        // Verify password
        if (!\SecurityHelper::verifyPassword($input['password'], $user['password'])) {
            return \ResponseHelper::error('Incorrect password');
        }
        
        // Soft delete
        $this->userModel->softDelete(AUTH_USER_ID);
        
        // Delete tokens
        $this->userModel->query(
            "DELETE FROM refresh_tokens WHERE user_id = :user_id",
            ['user_id' => AUTH_USER_ID]
        );
        
        return \ResponseHelper::success('Account deleted successfully');
    }
}

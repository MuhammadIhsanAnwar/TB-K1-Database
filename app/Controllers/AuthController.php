<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\User;
use App\Models\Wallet;
use App\Services\EmailService;

class AuthController extends Controller {
    private $userModel;
    private $walletModel;
    private $emailService;
    
    public function __construct() {
        $this->userModel = new User();
        $this->walletModel = new Wallet();
        $this->emailService = new EmailService();
    }
    
    public function register() {
        $input = $this->getInput();
        
        // Validation
        $errors = $this->validate($input, [
            'email' => 'required|email',
            'username' => 'required|min:3',
            'password' => 'required|min:8',
            'full_name' => 'required',
            'role' => 'required'
        ]);
        
        if ($errors !== true) {
            return \ResponseHelper::validationError($errors);
        }
        
        // Check if email exists
        if ($this->userModel->findByEmail($input['email'])) {
            return \ResponseHelper::error('Email already registered');
        }
        
        // Check if username exists
        if ($this->userModel->findByUsername($input['username'])) {
            return \ResponseHelper::error('Username already taken');
        }
        
        // Validate role
        if (!in_array($input['role'], ['buyer', 'seller'])) {
            return \ResponseHelper::error('Invalid role');
        }
        
        // Create user
        $verificationToken = \SecurityHelper::generateToken(32);
        
        $userId = $this->userModel->create([
            'email' => $input['email'],
            'username' => $input['username'],
            'password' => \SecurityHelper::hashPassword($input['password']),
            'role' => $input['role'],
            'full_name' => $input['full_name'],
            'phone' => $input['phone'] ?? null,
            'verification_token' => $verificationToken,
            'is_active' => true
        ]);
        
        // Create wallet
        $this->walletModel->createForUser($userId);
        
        // Send verification email
        $this->emailService->sendVerificationEmail(
            $input['email'],
            $input['username'],
            $verificationToken
        );
        
        return \ResponseHelper::success(
            'Registration successful! Please check your email to verify your account.',
            ['user_id' => $userId],
            201
        );
    }
    
    public function login() {
        $input = $this->getInput();
        
        $errors = $this->validate($input, [
            'email' => 'required|email',
            'password' => 'required'
        ]);
        
        if ($errors !== true) {
            return \ResponseHelper::validationError($errors);
        }
        
        // Find user
        $user = $this->userModel->findByEmail($input['email']);
        
        if (!$user) {
            return \ResponseHelper::error('Invalid credentials', [], 401);
        }
        
        // Check if account is locked
        if ($this->userModel->isLocked($user)) {
            return \ResponseHelper::error('Account is temporarily locked due to multiple failed login attempts', [], 403);
        }
        
        // Verify password
        if (!\SecurityHelper::verifyPassword($input['password'], $user['password'])) {
            // Increment login attempts
            $this->userModel->updateLoginAttempts($user['id'], $user['login_attempts'] + 1);
            return \ResponseHelper::error('Invalid credentials', [], 401);
        }
        
        // Check email verification
        if (!$user['email_verified_at']) {
            return \ResponseHelper::error('Please verify your email before logging in', [], 403);
        }
        
        // Check if active
        if (!$user['is_active']) {
            return \ResponseHelper::error('Account is suspended', [], 403);
        }
        
        // Reset login attempts
        $this->userModel->resetLoginAttempts($user['id']);
        
        // Generate JWT token
        $payload = (object)[
            'id' => $user['id'],
            'email' => $user['email'],
            'username' => $user['username'],
            'role' => $user['role']
        ];
        
        $accessToken = \JWTHelper::encode($payload);
        $refreshToken = \JWTHelper::createRefreshToken();
        
        // Store refresh token
        $this->userModel->query(
            "INSERT INTO refresh_tokens (user_id, token, expires_at) VALUES (:user_id, :token, :expires_at)",
            [
                'user_id' => $user['id'],
                'token' => $refreshToken,
                'expires_at' => date('Y-m-d H:i:s', strtotime('+7 days'))
            ]
        );
        
        return \ResponseHelper::success('Login successful', [
            'access_token' => $accessToken,
            'refresh_token' => $refreshToken,
            'user' => [
                'id' => $user['id'],
                'email' => $user['email'],
                'username' => $user['username'],
                'full_name' => $user['full_name'],
                'role' => $user['role'],
                'seller_level' => $user['seller_level'],
                'avatar' => $user['avatar']
            ]
        ]);
    }
    
    public function verifyEmail() {
        $token = $_GET['token'] ?? null;
        
        if (!$token) {
            return \ResponseHelper::error('Verification token is required');
        }
        
        $user = $this->userModel->findByVerificationToken($token);
        
        if (!$user) {
            return \ResponseHelper::error('Invalid or expired verification token');
        }
        
        if ($user['email_verified_at']) {
            return \ResponseHelper::error('Email already verified');
        }
        
        // Verify email
        $this->userModel->verifyEmail($user['id']);
        
        return \ResponseHelper::success('Email verified successfully! You can now login.');
    }
    
    public function forgotPassword() {
        $input = $this->getInput();
        
        if (empty($input['email'])) {
            return \ResponseHelper::error('Email is required');
        }
        
        $user = $this->userModel->findByEmail($input['email']);
        
        // Always return success to prevent email enumeration
        if (!$user) {
            return \ResponseHelper::success('If the email exists, you will receive a password reset link');
        }
        
        // Generate reset token
        $resetToken = \SecurityHelper::generateToken(32);
        $expiresAt = date('Y-m-d H:i:s', strtotime('+1 hour'));
        
        $this->userModel->update($user['id'], [
            'reset_token' => $resetToken,
            'reset_token_expires_at' => $expiresAt
        ]);
        
        // Send reset email
        $this->emailService->sendPasswordResetEmail(
            $user['email'],
            $user['username'],
            $resetToken
        );
        
        return \ResponseHelper::success('If the email exists, you will receive a password reset link');
    }
    
    public function resetPassword() {
        $input = $this->getInput();
        
        $errors = $this->validate($input, [
            'token' => 'required',
            'password' => 'required|min:8'
        ]);
        
        if ($errors !== true) {
            return \ResponseHelper::validationError($errors);
        }
        
        $user = $this->userModel->findByResetToken($input['token']);
        
        if (!$user) {
            return \ResponseHelper::error('Invalid or expired reset token');
        }
        
        // Update password
        $this->userModel->update($user['id'], [
            'password' => \SecurityHelper::hashPassword($input['password']),
            'reset_token' => null,
            'reset_token_expires_at' => null
        ]);
        
        return \ResponseHelper::success('Password reset successful! You can now login with your new password.');
    }
    
    public function logout() {
        $input = $this->getInput();
        
        if (isset($input['refresh_token'])) {
            // Delete refresh token
            $this->userModel->query(
                "DELETE FROM refresh_tokens WHERE token = :token",
                ['token' => $input['refresh_token']]
            );
        }
        
        return \ResponseHelper::success('Logout successful');
    }
    
    public function refreshToken() {
        $input = $this->getInput();
        
        if (empty($input['refresh_token'])) {
            return \ResponseHelper::error('Refresh token is required');
        }
        
        // Verify refresh token
        $stmt = $this->userModel->query(
            "SELECT rt.*, u.* FROM refresh_tokens rt
             JOIN users u ON rt.user_id = u.id
             WHERE rt.token = :token AND rt.expires_at > NOW()
             LIMIT 1",
            ['token' => $input['refresh_token']]
        );
        
        $result = $stmt->fetch();
        
        if (!$result) {
            return \ResponseHelper::error('Invalid or expired refresh token', [], 401);
        }
        
        // Generate new access token
        $payload = (object)[
            'id' => $result['user_id'],
            'email' => $result['email'],
            'username' => $result['username'],
            'role' => $result['role']
        ];
        
        $accessToken = \JWTHelper::encode($payload);
        
        return \ResponseHelper::success('Token refreshed', [
            'access_token' => $accessToken
        ]);
    }
}

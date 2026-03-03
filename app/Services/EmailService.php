<?php

namespace App\Services;

// Simple PHPMailer implementation for cPanel
class EmailService {
    private $config;
    
    public function __construct() {
        $this->config = require __DIR__ . '/../../config/mail.php';
    }
    
    public function send($to, $subject, $body, $isHtml = true) {
        $headers = [
            'From: ' . $this->config['from_name'] . ' <' . $this->config['from_email'] . '>',
            'Reply-To: ' . $this->config['from_email'],
            'X-Mailer: PHP/' . phpversion(),
            'MIME-Version: 1.0'
        ];
        
        if ($isHtml) {
            $headers[] = 'Content-type: text/html; charset=UTF-8';
        }
        
        $headersString = implode("\r\n", $headers);
        
        return mail($to, $subject, $body, $headersString);
    }
    
    public function sendVerificationEmail($email, $username, $token) {
        $verificationUrl = $this->config['app_url'] ?? 'https://lapakgaming.neoverse.my.id';
        $verificationUrl .= "/api/auth/verify-email?token={$token}";
        
        $subject = 'Verify Your Email - ' . ($this->config['from_name'] ?? 'Lapak Gaming');
        $body = $this->getEmailTemplate('verification', [
            'username' => $username,
            'verification_url' => $verificationUrl
        ]);
        
        return $this->send($email, $subject, $body);
    }
    
    public function sendPasswordResetEmail($email, $username, $token) {
        $resetUrl = $this->config['app_url'] ?? 'https://lapakgaming.neoverse.my.id';
        $resetUrl .= "/reset-password?token={$token}";
        
        $subject = 'Reset Your Password - ' . ($this->config['from_name'] ?? 'Lapak Gaming');
        $body = $this->getEmailTemplate('reset-password', [
            'username' => $username,
            'reset_url' => $resetUrl
        ]);
        
        return $this->send($email, $subject, $body);
    }
    
    public function sendOrderConfirmation($email, $orderNumber, $totalAmount) {
        $subject = 'Order Confirmation #' . $orderNumber;
        $body = $this->getEmailTemplate('order-confirmation', [
            'order_number' => $orderNumber,
            'total_amount' => number_format($totalAmount, 0, ',', '.')
        ]);
        
        return $this->send($email, $subject, $body);
    }
    
    public function sendOrderDelivered($email, $orderNumber) {
        $subject = 'Order Delivered #' . $orderNumber;
        $body = $this->getEmailTemplate('order-delivered', [
            'order_number' => $orderNumber
        ]);
        
        return $this->send($email, $subject, $body);
    }
    
    private function getEmailTemplate($template, $data) {
        $templates = [
            'verification' => "
                <html>
                <body style='font-family: Arial, sans-serif;'>
                    <div style='max-width: 600px; margin: 0 auto; padding: 20px;'>
                        <h2>Welcome {$data['username']}!</h2>
                        <p>Thank you for registering. Please verify your email address by clicking the button below:</p>
                        <p style='margin: 30px 0;'>
                            <a href='{$data['verification_url']}' 
                               style='background: #4F46E5; color: white; padding: 12px 24px; 
                                      text-decoration: none; border-radius: 6px; display: inline-block;'>
                                Verify Email
                            </a>
                        </p>
                        <p>Or copy this link: {$data['verification_url']}</p>
                        <p style='color: #666; font-size: 12px; margin-top: 40px;'>
                            If you didn't create this account, please ignore this email.
                        </p>
                    </div>
                </body>
                </html>
            ",
            'reset-password' => "
                <html>
                <body style='font-family: Arial, sans-serif;'>
                    <div style='max-width: 600px; margin: 0 auto; padding: 20px;'>
                        <h2>Password Reset Request</h2>
                        <p>Hello {$data['username']},</p>
                        <p>We received a request to reset your password. Click the button below to create a new password:</p>
                        <p style='margin: 30px 0;'>
                            <a href='{$data['reset_url']}' 
                               style='background: #4F46E5; color: white; padding: 12px 24px; 
                                      text-decoration: none; border-radius: 6px; display: inline-block;'>
                                Reset Password
                            </a>
                        </p>
                        <p>This link will expire in 1 hour.</p>
                        <p style='color: #666; font-size: 12px; margin-top: 40px;'>
                            If you didn't request this, please ignore this email.
                        </p>
                    </div>
                </body>
                </html>
            ",
            'order-confirmation' => "
                <html>
                <body style='font-family: Arial, sans-serif;'>
                    <div style='max-width: 600px; margin: 0 auto; padding: 20px;'>
                        <h2>Order Confirmed!</h2>
                        <p>Your order has been received and is being processed.</p>
                        <p><strong>Order Number:</strong> {$data['order_number']}</p>
                        <p><strong>Total Amount:</strong> Rp {$data['total_amount']}</p>
                        <p style='margin-top: 30px;'>
                            Thank you for your purchase!
                        </p>
                    </div>
                </body>
                </html>
            ",
            'order-delivered' => "
                <html>
                <body style='font-family: Arial, sans-serif;'>
                    <div style='max-width: 600px; margin: 0 auto; padding: 20px;'>
                        <h2>Order Delivered!</h2>
                        <p>Your order #{$data['order_number']} has been delivered.</p>
                        <p>Please check your order details and confirm delivery to release payment to the seller.</p>
                        <p style='margin-top: 30px;'>
                            Thank you for shopping with us!
                        </p>
                    </div>
                </body>
                </html>
            "
        ];
        
        return $templates[$template] ?? '';
    }
}

<?php

namespace App\Middleware;

class AuthMiddleware {
    public function handle() {
        $headers = getallheaders();
        $token = null;
        
        if (isset($headers['Authorization'])) {
            $authHeader = $headers['Authorization'];
            if (preg_match('/Bearer\s+(.*)$/i', $authHeader, $matches)) {
                $token = $matches[1];
            }
        }
        
        if (!$token) {
            http_response_code(401);
            echo json_encode(['error' => 'Unauthorized - No token provided']);
            exit;
        }
        
        $userData = \JWTHelper::decode($token);
        
        if (!$userData) {
            http_response_code(401);
            echo json_encode(['error' => 'Unauthorized - Invalid token']);
            exit;
        }
        
        // Store user data globally
        $_SESSION['auth_user'] = $userData;
        define('AUTH_USER_ID', $userData->id);
        define('AUTH_USER_ROLE', $userData->role);
    }
}

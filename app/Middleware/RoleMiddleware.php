<?php

namespace App\Middleware;

class RoleMiddleware {
    private $allowedRoles;
    
    public function __construct($roles = []) {
        $this->allowedRoles = is_array($roles) ? $roles : [$roles];
    }
    
    public function handle() {
        if (!defined('AUTH_USER_ROLE')) {
            http_response_code(401);
            echo json_encode(['error' => 'Unauthorized']);
            exit;
        }
        
        if (!in_array(AUTH_USER_ROLE, $this->allowedRoles)) {
            http_response_code(403);
            echo json_encode(['error' => 'Forbidden - Insufficient permissions']);
            exit;
        }
    }
}

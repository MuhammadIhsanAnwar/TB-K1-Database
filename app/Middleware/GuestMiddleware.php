<?php

namespace App\Middleware;

class GuestMiddleware {
    public function handle() {
        if (isset($_SESSION['auth_user'])) {
            header('Location: /dashboard');
            exit;
        }
    }
}

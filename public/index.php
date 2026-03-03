<?php

// Autoloader
spl_autoload_register(function ($class) {
    $prefix = 'App\\';
    $base_dir = __DIR__ . '/../app/';
    
    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        return;
    }
    
    $relative_class = substr($class, $len);
    $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';
    
    if (file_exists($file)) {
        require $file;
    }
});

// Load helpers
require_once __DIR__ . '/../app/Helpers/JWTHelper.php';
require_once __DIR__ . '/../app/Helpers/SecurityHelper.php';
require_once __DIR__ . '/../app/Helpers/ResponseHelper.php';
require_once __DIR__ . '/../app/Helpers/StringHelper.php';

// Load JWT library (simplified inline version for cPanel compatibility)
if (!class_exists('Firebase\JWT\JWT')) {
    require_once __DIR__ . '/../vendor/firebase-jwt.php';
}

// Start session
session_start();

// Error handling
error_reporting(E_ALL);
ini_set('display_errors', 0);

// CORS headers for API
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

// Initialize router
$router = new App\Core\Router();

// Load routes
require_once __DIR__ . '/../routes/api.php';
require_once __DIR__ . '/../routes/web.php';

// Resolve route
$router->resolve();

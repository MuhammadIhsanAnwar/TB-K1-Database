<?php

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

if (PHP_VERSION_ID < 80300) {
    http_response_code(500);
    echo 'Server misconfiguration: This application requires PHP 8.3 or newer.';
    exit;
}

$autoloadFile = __DIR__.'/../vendor/autoload.php';
$bootstrapFile = __DIR__.'/../bootstrap/app.php';

if (! file_exists($autoloadFile) || ! file_exists($bootstrapFile)) {
    http_response_code(500);
    echo 'Server misconfiguration: Application files are incomplete. Please run composer install and verify deployment paths.';
    exit;
}

// Determine if the application is in maintenance mode...
if (file_exists($maintenance = __DIR__.'/../storage/framework/maintenance.php')) {
    require $maintenance;
}

// Register the Composer autoloader...
require $autoloadFile;

// Bootstrap Laravel and handle the request...
/** @var Application $app */
$app = require_once $bootstrapFile;

$app->handleRequest(Request::capture());

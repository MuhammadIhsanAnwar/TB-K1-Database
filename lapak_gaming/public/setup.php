<?php
/**
 * Laravel Setup Script for cPanel without Terminal
 * Akses via: https://lapakgaming.neoverse.my.id/setup.php
 * HAPUS FILE INI SETELAH SETUP SELESAI!
 */

// Cek apakah sudah setup
if (file_exists(__DIR__ . '/../.setup_completed')) {
    die('Setup sudah selesai. Hapus file .setup_completed untuk run ulang.');
}

// Set timeout
set_time_limit(300);
ini_set('max_execution_time', 300);

echo "<!DOCTYPE html>
<html>
<head>
    <title>Laravel Setup - Lapak Gaming</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 800px; margin: 50px auto; padding: 20px; }
        .success { color: green; }
        .error { color: red; }
        .warning { color: orange; }
        pre { background: #f4f4f4; padding: 10px; border-radius: 5px; }
        h1 { color: #333; }
        .step { margin: 20px 0; padding: 15px; border-left: 4px solid #4CAF50; background: #f9f9f9; }
    </style>
</head>
<body>
    <h1>🚀 Laravel Setup Script</h1>
";

// Helper function
function runCommand($description, $callback) {
    echo "<div class='step'>";
    echo "<strong>$description</strong><br>";
    try {
        $result = $callback();
        echo "<span class='success'>✓ Berhasil</span>";
        if ($result) echo "<pre>$result</pre>";
    } catch (Exception $e) {
        echo "<span class='error'>✗ Error: " . $e->getMessage() . "</span>";
    }
    echo "</div>";
}

// 1. Check PHP Version
runCommand("1. Checking PHP Version", function() {
    $version = phpversion();
    if (version_compare($version, '8.2.0', '<')) {
        throw new Exception("PHP 8.2+ required, current: $version");
    }
    return "PHP Version: $version";
});

// 2. Check Required Extensions
runCommand("2. Checking PHP Extensions", function() {
    $required = ['openssl', 'pdo', 'mbstring', 'tokenizer', 'xml', 'ctype', 'json', 'bcmath', 'curl'];
    $missing = [];
    foreach ($required as $ext) {
        if (!extension_loaded($ext)) {
            $missing[] = $ext;
        }
    }
    if (!empty($missing)) {
        throw new Exception("Missing extensions: " . implode(', ', $missing));
    }
    return "All required extensions loaded";
});

// 3. Setup Storage & Bootstrap Cache Permissions
runCommand("3. Setting Permissions for Storage & Cache", function() {
    $dirs = [
        __DIR__ . '/../storage/framework/cache',
        __DIR__ . '/../storage/framework/sessions',
        __DIR__ . '/../storage/framework/views',
        __DIR__ . '/../storage/logs',
        __DIR__ . '/../bootstrap/cache',
    ];
    
    $results = [];
    foreach ($dirs as $dir) {
        if (!is_dir($dir)) {
            mkdir($dir, 0775, true);
        }
        chmod($dir, 0775);
        $results[] = str_replace(__DIR__ . '/../', '', $dir) . ' → 775';
    }
    return implode("\n", $results);
});

// 4. Clear Old Cache Files
runCommand("4. Clearing Old Cache Files", function() {
    $cacheFiles = [
        __DIR__ . '/../bootstrap/cache/config.php',
        __DIR__ . '/../bootstrap/cache/routes.php',
        __DIR__ . '/../bootstrap/cache/packages.php',
        __DIR__ . '/../bootstrap/cache/services.php',
    ];
    
    $cleared = [];
    foreach ($cacheFiles as $file) {
        if (file_exists($file)) {
            unlink($file);
            $cleared[] = basename($file);
        }
    }
    return $cleared ? "Deleted: " . implode(', ', $cleared) : "No cache files to clear";
});

// 5. Check Composer Vendor
runCommand("5. Checking Composer Dependencies", function() {
    $autoload = __DIR__ . '/../vendor/autoload.php';
    if (!file_exists($autoload)) {
        throw new Exception("vendor/ folder not found! Upload vendor/ folder from local to server.");
    }
    require $autoload;
    return "Vendor folder exists, autoload ready";
});

// 6. Load Laravel App
runCommand("6. Bootstrapping Laravel Application", function() {
    $app = require_once __DIR__ . '/../bootstrap/app.php';
    return "Laravel bootstrapped successfully";
});

// 7. Check .env file
runCommand("7. Checking .env Configuration", function() {
    $env = __DIR__ . '/../.env';
    if (!file_exists($env)) {
        throw new Exception(".env file not found!");
    }
    
    // Load and validate critical values
    $contents = file_get_contents($env);
    $checks = [
        'APP_KEY=' => false,
        'DB_DATABASE=' => false,
        'DB_USERNAME=' => false,
    ];
    
    foreach ($checks as $key => $found) {
        if (strpos($contents, $key) !== false) {
            $checks[$key] = true;
        }
    }
    
    $missing = array_keys(array_filter($checks, fn($v) => !$v));
    if (!empty($missing)) {
        throw new Exception("Missing .env values: " . implode(', ', $missing));
    }
    
    return ".env file valid";
});

// 8. Generate APP_KEY if needed
runCommand("8. Checking APP_KEY", function() {
    $env = __DIR__ . '/../.env';
    $contents = file_get_contents($env);
    
    if (preg_match('/APP_KEY=base64:/', $contents)) {
        return "APP_KEY already set";
    }
    
    // Generate new key
    $key = 'base64:' . base64_encode(random_bytes(32));
    $contents = preg_replace('/APP_KEY=.*/', 'APP_KEY=' . $key, $contents);
    file_put_contents($env, $contents);
    
    return "APP_KEY generated: $key";
});

// 9. Test Database Connection
runCommand("9. Testing Database Connection", function() {
    // Load .env manually (parse_ini_file doesn't work well with Laravel .env format)
    $envFile = __DIR__ . '/../.env';
    $envVars = [];
    
    if (file_exists($envFile)) {
        $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach ($lines as $line) {
            // Skip comments
            if (strpos(trim($line), '#') === 0) continue;
            
            // Parse KEY=VALUE
            if (strpos($line, '=') !== false) {
                list($key, $value) = explode('=', $line, 2);
                $key = trim($key);
                $value = trim($value);
                
                // Remove quotes if present
                if (preg_match('/^"(.+)"$/', $value, $matches)) {
                    $value = $matches[1];
                } elseif (preg_match("/^'(.+)'$/", $value, $matches)) {
                    $value = $matches[1];
                }
                
                $envVars[$key] = $value;
            }
        }
    }
    
    $host = $envVars['DB_HOST'] ?? 'localhost';
    $db = $envVars['DB_DATABASE'] ?? '';
    $user = $envVars['DB_USERNAME'] ?? '';
    $pass = $envVars['DB_PASSWORD'] ?? '';
    
    if (empty($db) || empty($user)) {
        throw new Exception("DB_DATABASE or DB_USERNAME is empty in .env file");
    }
    
    try {
        $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        // Check if tables exist
        $stmt = $pdo->query("SHOW TABLES");
        $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
        
        if (empty($tables)) {
            return "Connected to database, but no tables found. Run migrations next.";
        } else {
            return "Connected! Found " . count($tables) . " tables: " . implode(', ', array_slice($tables, 0, 5)) . (count($tables) > 5 ? '...' : '');
        }
    } catch (PDOException $e) {
        throw new Exception("DB Connection failed: " . $e->getMessage());
    }
});

// 10. Create symbolic link for storage
runCommand("10. Creating Storage Symbolic Link", function() {
    $target = __DIR__ . '/../storage/app/public';
    $link = __DIR__ . '/storage';
    
    if (file_exists($link)) {
        return "Storage link already exists";
    }
    
    // Untuk Windows/cPanel, buat folder biasa jika symlink gagal
    if (!@symlink($target, $link)) {
        // Fallback: copy .htaccess redirect
        @mkdir($link, 0755, true);
        file_put_contents($link . '/.htaccess', 
            "<IfModule mod_rewrite.c>\n" .
            "    RewriteEngine On\n" .
            "    RewriteRule ^(.*)$ ../storage/app/public/$1 [L]\n" .
            "</IfModule>"
        );
        return "Symbolic link failed, created redirect folder instead";
    }
    
    return "Storage symbolic link created";
});

// Success
echo "<div class='step' style='border-left-color: #2196F3; background: #E3F2FD;'>";
echo "<h2>✅ Setup Completed!</h2>";
echo "<p><strong>Next Steps:</strong></p>";
echo "<ol>";
echo "<li>Run database migrations via <a href='migrate.php'>migrate.php</a></li>";
echo "<li>Test your site: <a href='/' target='_blank'>Home Page</a></li>";
echo "<li><span class='error'>DELETE setup.php dan migrate.php files!</span></li>";
echo "</ol>";
echo "</div>";

// Mark as completed
file_put_contents(__DIR__ . '/../.setup_completed', date('Y-m-d H:i:s'));

echo "</body></html>";

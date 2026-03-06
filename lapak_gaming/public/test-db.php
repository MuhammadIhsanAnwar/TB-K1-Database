<?php
/**
 * Simple Database Connection Test
 * Akses via: https://lapakgaming.neoverse.my.id/test-db.php
 * HAPUS FILE INI SETELAH TESTING!
 */

echo "<!DOCTYPE html>
<html>
<head>
    <title>Database Connection Test</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 700px; margin: 50px auto; padding: 20px; }
        .success { color: green; font-weight: bold; }
        .error { color: red; font-weight: bold; }
        pre { background: #f4f4f4; padding: 15px; border-radius: 5px; }
        .box { border: 2px solid #ccc; padding: 20px; margin: 20px 0; border-radius: 8px; }
    </style>
</head>
<body>
    <h1>🔌 Database Connection Test</h1>
";

// Load .env manually
$envFile = __DIR__ . '/../.env';
$envVars = [];

echo "<div class='box'>";
echo "<h3>1. Reading .env file</h3>";

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
    echo "<p class='success'>✓ .env file found and parsed</p>";
} else {
    echo "<p class='error'>✗ .env file not found at: $envFile</p>";
    die("</div></body></html>");
}
echo "</div>";

// Show DB config (hide password)
echo "<div class='box'>";
echo "<h3>2. Database Configuration</h3>";
echo "<pre>";
echo "DB_HOST     : " . ($envVars['DB_HOST'] ?? 'NOT SET') . "\n";
echo "DB_PORT     : " . ($envVars['DB_PORT'] ?? 'NOT SET') . "\n";
echo "DB_DATABASE : " . ($envVars['DB_DATABASE'] ?? 'NOT SET') . "\n";
echo "DB_USERNAME : " . ($envVars['DB_USERNAME'] ?? 'NOT SET') . "\n";
echo "DB_PASSWORD : " . (isset($envVars['DB_PASSWORD']) ? str_repeat('*', strlen($envVars['DB_PASSWORD'])) . ' (' . strlen($envVars['DB_PASSWORD']) . ' chars)' : 'NOT SET') . "\n";
echo "</pre>";
echo "</div>";

// Test connection
echo "<div class='box'>";
echo "<h3>3. Testing Database Connection</h3>";

$host = $envVars['DB_HOST'] ?? '';
$port = $envVars['DB_PORT'] ?? '3306';
$db = $envVars['DB_DATABASE'] ?? '';
$user = $envVars['DB_USERNAME'] ?? '';
$pass = $envVars['DB_PASSWORD'] ?? '';

if (empty($host) || empty($db) || empty($user)) {
    echo "<p class='error'>✗ Missing required database credentials</p>";
} else {
    try {
        // Try connection
        $dsn = "mysql:host=$host;port=$port;dbname=$db;charset=utf8mb4";
        $pdo = new PDO($dsn, $user, $pass, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]);
        
        echo "<p class='success'>✓ Database connection successful!</p>";
        
        // Get MySQL version
        $version = $pdo->query('SELECT VERSION()')->fetchColumn();
        echo "<p>MySQL Version: <strong>$version</strong></p>";
        
        // Get tables
        $stmt = $pdo->query("SHOW TABLES");
        $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
        
        echo "<h4>Tables Found: " . count($tables) . "</h4>";
        if (!empty($tables)) {
            echo "<pre>";
            foreach ($tables as $table) {
                echo "- $table\n";
            }
            echo "</pre>";
        } else {
            echo "<p>No tables found. Database is empty. Run migrations next.</p>";
        }
        
    } catch (PDOException $e) {
        echo "<p class='error'>✗ Connection Failed!</p>";
        echo "<p><strong>Error Code:</strong> " . $e->getCode() . "</p>";
        echo "<p><strong>Error Message:</strong> " . htmlspecialchars($e->getMessage()) . "</p>";
        
        echo "<h4>Troubleshooting:</h4>";
        echo "<ul>";
        
        $errorMsg = $e->getMessage();
        
        if (strpos($errorMsg, 'Access denied') !== false) {
            echo "<li>❌ <strong>Wrong username or password</strong></li>";
            echo "<li>Check credentials in cPanel → MySQL Databases</li>";
            echo "<li>Verify user has ALL PRIVILEGES on database</li>";
            echo "<li>Try login via phpMyAdmin to verify</li>";
        } elseif (strpos($errorMsg, 'Unknown database') !== false) {
            echo "<li>❌ <strong>Database does not exist</strong></li>";
            echo "<li>Create database in cPanel → MySQL Databases</li>";
            echo "<li>Database name: <code>" . htmlspecialchars($db) . "</code></li>";
        } elseif (strpos($errorMsg, "Can't connect") !== false || strpos($errorMsg, 'Connection refused') !== false) {
            echo "<li>❌ <strong>Cannot connect to MySQL server</strong></li>";
            echo "<li>Try DB_HOST=localhost instead of 127.0.0.1</li>";
            echo "<li>Check if MySQL is running</li>";
        } else {
            echo "<li>Unknown error. Check error message above.</li>";
        }
        
        echo "</ul>";
    }
}
echo "</div>";

echo "<div class='box' style='background: #FFF3E0;'>";
echo "<h3>⚠️ Security Warning</h3>";
echo "<p><strong>DELETE this file after testing!</strong></p>";
echo "<p>File: <code>public/test-db.php</code></p>";
echo "</div>";

echo "</body></html>";

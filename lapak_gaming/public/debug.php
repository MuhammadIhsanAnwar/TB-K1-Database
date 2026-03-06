<?php
/**
 * Laravel Error Debugger
 * Akses via: https://lapakgaming.neoverse.my.id/debug.php
 * HAPUS FILE INI SETELAH TROUBLESHOOTING!
 */

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<!DOCTYPE html>
<html>
<head>
    <title>Laravel Debug</title>
    <style>
        body { font-family: monospace; padding: 20px; background: #1e1e1e; color: #d4d4d4; }
        .ok { color: #4ec9b0; }
        .error { color: #f48771; }
        .warning { color: #dcdcaa; }
        h2 { color: #569cd6; border-bottom: 2px solid #569cd6; padding-bottom: 5px; }
        pre { background: #252526; padding: 15px; border-left: 3px solid #569cd6; overflow-x: auto; }
        .box { margin: 20px 0; padding: 15px; background: #252526; border-radius: 5px; }
    </style>
</head>
<body>
    <h1>🔍 Laravel Error Debugger</h1>
";

$errors = [];
$warnings = [];

// Check 1: PHP Version
echo "<div class='box'>";
echo "<h2>1. PHP Version</h2>";
$phpVersion = phpversion();
if (version_compare($phpVersion, '8.2.0', '>=')) {
    echo "<p class='ok'>✓ PHP $phpVersion (OK)</p>";
} else {
    echo "<p class='error'>✗ PHP $phpVersion (Need 8.2+)</p>";
    $errors[] = "PHP version too old";
}
echo "</div>";

// Check 2: Required Files
echo "<div class='box'>";
echo "<h2>2. Required Files</h2>";

$files = [
    'vendor/autoload.php' => 'Composer autoloader',
    'bootstrap/app.php' => 'Laravel bootstrap',
    '.env' => 'Environment config',
    'public/index.php' => 'Front controller',
];

foreach ($files as $file => $desc) {
    $path = __DIR__ . '/../' . $file;
    if (file_exists($path)) {
        echo "<p class='ok'>✓ $desc: $file</p>";
    } else {
        echo "<p class='error'>✗ $desc: $file NOT FOUND</p>";
        $errors[] = "$file missing";
    }
}
echo "</div>";

// Check 3: Directories & Permissions
echo "<div class='box'>";
echo "<h2>3. Directory Permissions</h2>";

$dirs = [
    'storage/framework/cache' => 'Cache directory',
    'storage/framework/sessions' => 'Sessions directory',
    'storage/framework/views' => 'Views cache',
    'storage/logs' => 'Log files',
    'bootstrap/cache' => 'Bootstrap cache',
];

foreach ($dirs as $dir => $desc) {
    $path = __DIR__ . '/../' . $dir;
    if (is_dir($path)) {
        $perms = substr(sprintf('%o', fileperms($path)), -4);
        $writable = is_writable($path);
        
        if ($writable) {
            echo "<p class='ok'>✓ $desc: $dir ($perms, writable)</p>";
        } else {
            echo "<p class='error'>✗ $desc: $dir ($perms, NOT writable)</p>";
            $errors[] = "$dir not writable";
        }
    } else {
        echo "<p class='error'>✗ $desc: $dir (NOT EXISTS)</p>";
        $errors[] = "$dir not found";
    }
}
echo "</div>";

// Check 4: .env File Content
echo "<div class='box'>";
echo "<h2>4. Environment Configuration</h2>";

$envPath = __DIR__ . '/../.env';
if (file_exists($envPath)) {
    $envContent = file_get_contents($envPath);
    
    // Parse .env
    $envVars = [];
    $lines = explode("\n", $envContent);
    foreach ($lines as $line) {
        if (strpos(trim($line), '#') === 0 || empty(trim($line))) continue;
        if (strpos($line, '=') !== false) {
            list($key, $value) = explode('=', $line, 2);
            $envVars[trim($key)] = trim($value);
        }
    }
    
    $requiredKeys = ['APP_KEY', 'APP_URL', 'DB_DATABASE', 'DB_USERNAME', 'DB_PASSWORD'];
    
    foreach ($requiredKeys as $key) {
        if (isset($envVars[$key]) && !empty($envVars[$key])) {
            $displayValue = in_array($key, ['DB_PASSWORD', 'APP_KEY']) 
                ? str_repeat('*', 10) 
                : $envVars[$key];
            echo "<p class='ok'>✓ $key = $displayValue</p>";
        } else {
            echo "<p class='error'>✗ $key = NOT SET</p>";
            $errors[] = "$key not set in .env";
        }
    }
} else {
    echo "<p class='error'>✗ .env file not found</p>";
    $errors[] = ".env file missing";
}
echo "</div>";

// Check 5: Try to load Laravel
echo "<div class='box'>";
echo "<h2>5. Laravel Bootstrap Test</h2>";

if (file_exists(__DIR__ . '/../vendor/autoload.php')) {
    try {
        require __DIR__ . '/../vendor/autoload.php';
        echo "<p class='ok'>✓ Composer autoloader loaded</p>";
        
        if (file_exists(__DIR__ . '/../bootstrap/app.php')) {
            try {
                $app = require_once __DIR__ . '/../bootstrap/app.php';
                echo "<p class='ok'>✓ Laravel application created</p>";
                
                try {
                    $kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
                    echo "<p class='ok'>✓ Console kernel loaded</p>";
                    
                    try {
                        $kernel->bootstrap();
                        echo "<p class='ok'>✓ Laravel bootstrapped successfully</p>";
                        echo "<p class='ok'>✓✓✓ LARAVEL IS WORKING! ✓✓✓</p>";
                        
                        // Show Laravel info
                        echo "<pre>";
                        echo "Laravel Version: " . app()->version() . "\n";
                        echo "Environment: " . app()->environment() . "\n";
                        echo "Debug Mode: " . (config('app.debug') ? 'ON' : 'OFF') . "\n";
                        echo "</pre>";
                        
                    } catch (Exception $e) {
                        echo "<p class='error'>✗ Bootstrap failed: " . $e->getMessage() . "</p>";
                        echo "<pre>" . $e->getTraceAsString() . "</pre>";
                        $errors[] = "Laravel bootstrap error";
                    }
                } catch (Exception $e) {
                    echo "<p class='error'>✗ Kernel error: " . $e->getMessage() . "</p>";
                    echo "<pre>" . $e->getTraceAsString() . "</pre>";
                    $errors[] = "Kernel creation error";
                }
            } catch (Exception $e) {
                echo "<p class='error'>✗ App bootstrap error: " . $e->getMessage() . "</p>";
                echo "<pre>" . $e->getTraceAsString() . "</pre>";
                $errors[] = "App creation error";
            }
        }
    } catch (Exception $e) {
        echo "<p class='error'>✗ Autoloader error: " . $e->getMessage() . "</p>";
        echo "<pre>" . $e->getTraceAsString() . "</pre>";
        $errors[] = "Autoloader error";
    }
} else {
    echo "<p class='error'>✗ vendor/autoload.php not found</p>";
    $errors[] = "Composer vendor folder missing";
}
echo "</div>";

// Check 6: Log file
echo "<div class='box'>";
echo "<h2>6. Laravel Error Log</h2>";

$logPath = __DIR__ . '/../storage/logs/laravel.log';
if (file_exists($logPath)) {
    $logContent = file_get_contents($logPath);
    $logLines = explode("\n", $logContent);
    $recentLines = array_slice($logLines, -50); // Last 50 lines
    
    echo "<p class='warning'>Last 50 lines from laravel.log:</p>";
    echo "<pre style='max-height: 400px; overflow-y: auto;'>";
    echo htmlspecialchars(implode("\n", $recentLines));
    echo "</pre>";
} else {
    echo "<p class='warning'>⚠ No log file yet (first run or no errors)</p>";
}
echo "</div>";

// Summary
echo "<div class='box' style='border: 3px solid " . (empty($errors) ? '#4ec9b0' : '#f48771') . ";'>";
echo "<h2>Summary</h2>";

if (empty($errors)) {
    echo "<p class='ok' style='font-size: 20px;'>✓✓✓ ALL CHECKS PASSED ✓✓✓</p>";
    echo "<p>If the main site still shows Error 500, check:</p>";
    echo "<ul>";
    echo "<li>Document Root points to: <code>lapak_gaming</code> (not <code>lapak_gaming/public</code>)</li>";
    echo "<li>.htaccess file exists in root and redirects to <code>public/</code></li>";
    echo "<li>Apache mod_rewrite is enabled</li>";
    echo "</ul>";
} else {
    echo "<p class='error' style='font-size: 20px;'>✗ FOUND " . count($errors) . " ERROR(S)</p>";
    echo "<ul>";
    foreach ($errors as $error) {
        echo "<li class='error'>$error</li>";
    }
    echo "</ul>";
    
    echo "<h3>Quick Fixes:</h3>";
    echo "<ul>";
    if (in_array('Composer vendor folder missing', $errors)) {
        echo "<li>Upload <code>vendor/</code> folder from local to server</li>";
    }
    if (in_array('.env file missing', $errors)) {
        echo "<li>Upload <code>.env</code> file to server root</li>";
    }
    if (strpos(implode(' ', $errors), 'not writable') !== false) {
        echo "<li>Run setup.php to fix permissions</li>";
        echo "<li>Or chmod 775 storage/ and bootstrap/cache/</li>";
    }
    echo "</ul>";
}
echo "</div>";

echo "<div class='box' style='background: #3c1f1f;'>";
echo "<h3 style='color: #f48771;'>⚠️ SECURITY WARNING</h3>";
echo "<p style='color: #f48771;'><strong>DELETE debug.php AFTER TROUBLESHOOTING!</strong></p>";
echo "</div>";

echo "</body></html>";

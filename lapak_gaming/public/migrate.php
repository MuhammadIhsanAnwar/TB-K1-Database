<?php
/**
 * Database Migration Script for cPanel without Terminal
 * Akses via: https://lapakgaming.neoverse.my.id/migrate.php
 * HAPUS FILE INI SETELAH MIGRASI SELESAI!
 */

// Set timeout
set_time_limit(300);

echo "<!DOCTYPE html>
<html>
<head>
    <title>Database Migration - Lapak Gaming</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 900px; margin: 50px auto; padding: 20px; }
        .success { color: green; }
        .error { color: red; }
        pre { background: #f4f4f4; padding: 10px; border-radius: 5px; overflow-x: auto; }
        h1 { color: #333; }
        .step { margin: 20px 0; padding: 15px; border-left: 4px solid #4CAF50; background: #f9f9f9; }
        .btn { display: inline-block; padding: 10px 20px; background: #4CAF50; color: white; text-decoration: none; border-radius: 5px; margin: 10px 5px; }
        .btn-danger { background: #f44336; }
    </style>
</head>
<body>
    <h1>📦 Database Migration Script</h1>
";

// Load Laravel
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);

// Import Laravel facades
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Artisan;

// Check action
$action = $_GET['action'] ?? 'check';

if ($action === 'check') {
    // Just check database status
    echo "<div class='step'>";
    echo "<h2>Database Status</h2>";
    
    try {
        $pdo = DB::connection()->getPdo();
        echo "<p class='success'>✓ Database connected</p>";
        
        // Get tables
        $tables = DB::select('SHOW TABLES');
        
        if (empty($tables)) {
            echo "<p class='error'>⚠ No tables found. Database is empty.</p>";
            echo "<a href='?action=migrate' class='btn'>Run Migration</a>";
        } else {
            echo "<p class='success'>✓ Found " . count($tables) . " tables</p>";
            echo "<pre>";
            foreach ($tables as $table) {
                $tableName = array_values((array)$table)[0];
                echo "- $tableName\n";
            }
            echo "</pre>";
            
            echo "<a href='?action=fresh' class='btn btn-danger'>Reset & Migrate Fresh</a>";
            echo "<a href='?action=seed' class='btn'>Run Seeders</a>";
        }
    } catch (Exception $e) {
        echo "<p class='error'>✗ Database Error: " . $e->getMessage() . "</p>";
    }
    echo "</div>";
    
} elseif ($action === 'migrate') {
    // Run migrations
    echo "<div class='step'>";
    echo "<h2>Running Migrations...</h2>";
    
    try {
        // Artisan call
        Artisan::call('migrate', ['--force' => true]);
        $output = Artisan::output();
        
        echo "<pre>$output</pre>";
        echo "<p class='success'>✓ Migrations completed!</p>";
        echo "<a href='?action=seed' class='btn'>Run Seeders</a>";
        echo "<a href='?action=check' class='btn'>Check Status</a>";
    } catch (Exception $e) {
        echo "<p class='error'>✗ Migration Error: " . $e->getMessage() . "</p>";
    }
    echo "</div>";
    
} elseif ($action === 'fresh') {
    // Fresh migration (drop all tables and migrate)
    echo "<div class='step'>";
    echo "<h2>⚠️ Fresh Migration (DROP ALL TABLES)</h2>";
    
    if (!isset($_GET['confirm'])) {
        echo "<p class='error'>This will DELETE all data!</p>";
        echo "<a href='?action=fresh&confirm=yes' class='btn btn-danger'>Confirm & Proceed</a>";
        echo "<a href='?action=check' class='btn'>Cancel</a>";
    } else {
        try {
            Artisan::call('migrate:fresh', ['--force' => true]);
            $output = Artisan::output();
            
            echo "<pre>$output</pre>";
            echo "<p class='success'>✓ Fresh migration completed!</p>";
            echo "<a href='?action=seed' class='btn'>Run Seeders</a>";
        } catch (Exception $e) {
            echo "<p class='error'>✗ Error: " . $e->getMessage() . "</p>";
        }
    }
    echo "</div>";
    
} elseif ($action === 'seed') {
    // Run seeders
    echo "<div class='step'>";
    echo "<h2>Running Database Seeders...</h2>";
    
    try {
        Artisan::call('db:seed', ['--force' => true]);
        $output = Artisan::output();
        
        echo "<pre>$output</pre>";
        echo "<p class='success'>✓ Seeding completed!</p>";
        
        // Show sample data
        echo "<h3>Sample Admin Account:</h3>";
        echo "<pre>";
        echo "Email: admin@lapakgaming.com\n";
        echo "Password: password\n";
        echo "</pre>";
        
        echo "<a href='/' class='btn'>Go to Home</a>";
        echo "<a href='?action=check' class='btn'>Check Status</a>";
    } catch (Exception $e) {
        echo "<p class='error'>✗ Seeding Error: " . $e->getMessage() . "</p>";
    }
    echo "</div>";
}

echo "<div class='step' style='background: #FFF3E0; border-left-color: #FF9800;'>";
echo "<h3>⚠️ Security Warning</h3>";
echo "<p><strong>DELETE these files after setup:</strong></p>";
echo "<ul>";
echo "<li>public/setup.php</li>";
echo "<li>public/migrate.php</li>";
echo "</ul>";
echo "</div>";

echo "</body></html>";

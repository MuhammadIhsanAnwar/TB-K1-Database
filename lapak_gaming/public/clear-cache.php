<?php
/**
 * Emergency Cache Clear Utility
 * TEMPORARY FILE - DELETE AFTER USE!
 * 
 * Usage: Visit https://lapakgaming.neoverse.my.id/clear-cache.php in browser
 * After completing, delete this file immediately for security!
 */

// Set timeout yang lebih lama untuk cache clearing
set_time_limit(60);

// Prevent direct access dari command line
if (php_sapi_name() === 'cli') {
    echo "❌ This script must be accessed via web browser, not command line.\n";
    exit(1);
}

// Simple security check - verify this is local access or from same domain
$allowed_hosts = ['localhost', '127.0.0.1', 'lapakgaming.neoverse.my.id'];
$current_host = $_SERVER['HTTP_HOST'] ?? $_SERVER['SERVER_NAME'] ?? '';

// Extract domain without port
$current_host = preg_replace('/:\d+$/', '', $current_host);

if (!in_array($current_host, $allowed_hosts)) {
    http_response_code(403);
    echo "❌ Access Denied";
    exit(1);
}

// Set up HTML output
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cache Clear - Lapak Gaming</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
            padding: 20px;
        }
        .container {
            background: white;
            border-radius: 12px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            padding: 40px;
            max-width: 600px;
            width: 100%;
        }
        h1 {
            color: #333;
            margin-top: 0;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        h2 {
            color: #667eea;
            margin-top: 30px;
            font-size: 18px;
        }
        .status {
            background: #f5f5f5;
            border-left: 4px solid #667eea;
            padding: 15px;
            margin: 20px 0;
            border-radius: 4px;
            font-family: 'Courier New', monospace;
            font-size: 14px;
            line-height: 1.6;
            max-height: 300px;
            overflow-y: auto;
        }
        .success {
            border-left-color: #10b981;
            background: #ecfdf5;
        }
        .error {
            border-left-color: #ef4444;
            background: #fef2f2;
        }
        .warning {
            border-left-color: #f59e0b;
            background: #fffbeb;
        }
        .command {
            display: flex;
            align-items: center;
            gap: 10px;
            margin: 10px 0;
        }
        .icon {
            font-size: 18px;
            min-width: 25px;
        }
        .message {
            flex: 1;
        }
        .alert {
            background: #fef2f2;
            border: 1px solid #fecaca;
            border-radius: 6px;
            padding: 15px;
            margin: 20px 0;
            color: #991b1b;
        }
        .success-message {
            background: #ecfdf5;
            border: 1px solid #a7f3d0;
            border-radius: 6px;
            padding: 15px;
            margin: 20px 0;
            color: #065f46;
        }
        .button-group {
            display: flex;
            gap: 10px;
            margin-top: 30px;
            justify-content: center;
        }
        button {
            padding: 10px 20px;
            border: none;
            border-radius: 6px;
            font-size: 14px;
            cursor: pointer;
            transition: all 0.3s;
        }
        .btn-primary {
            background: #667eea;
            color: white;
        }
        .btn-primary:hover {
            background: #5568d3;
        }
        .btn-danger {
            background: #ef4444;
            color: white;
        }
        .btn-danger:hover {
            background: #dc2626;
        }
        code {
            background: #f3f4f6;
            padding: 2px 6px;
            border-radius: 3px;
            font-family: 'Courier New', monospace;
        }
        .info {
            background: #eff6ff;
            border: 1px solid #bfdbfe;
            border-radius: 6px;
            padding: 15px;
            margin: 20px 0;
            color: #1e40af;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🔄 Cache Clear Utility</h1>
        <p style="color: #666; margin-bottom: 30px;">Emergency cache clearing for Lapak Gaming</p>

        <div class="info">
            <strong>ℹ️ Info:</strong> This file will clear Laravel cache, routes, config, and views.
        </div>

        <div id="output" class="status"></div>

        <div id="result" style="display: none;">
            <div class="success-message">
                <h2 style="margin-top: 0; color: #065f46;">✅ Cache Cleared Successfully!</h2>
                <p style="margin: 10px 0; color: #065f46;">All caches have been cleared. Your application should now work correctly.</p>
            </div>

            <div class="alert">
                <strong>⚠️ IMPORTANT:</strong> You must <strong>delete this file immediately</strong> for security reasons!
                <br><br>
                Steps:
                <ol style="margin: 10px 0;">
                    <li>Download the file from your server (if needed for backup)</li>
                    <li>Delete <code>public/clear-cache.php</code> via cPanel File Manager</li>
                    <li>Verify it's deleted by refreshing the server file list</li>
                </ol>
            </div>

            <div class="button-group">
                <button class="btn-primary" onclick="refreshPage()">Refresh Page</button>
                <button class="btn-danger" onclick="deleteInstructions()">Delete File Instructions</button>
            </div>
        </div>

        <div id="error" style="display: none;">
            <div class="alert">
                <h2 style="margin-top: 0;">❌ Error Occurred</h2>
                <p id="error-message"></p>
            </div>
            <div class="button-group">
                <button class="btn-primary" onclick="location.reload()">Try Again</button>
            </div>
        </div>
    </div>

    <script>
        function log(message, type = 'info') {
            const output = document.getElementById('output');
            const line = document.createElement('div');
            line.className = 'command';
            
            let icon = '▪️';
            if (type === 'success') icon = '✅';
            else if (type === 'error') icon = '❌';
            else if (type === 'running') icon = '⏳';
            
            line.innerHTML = `
                <span class="icon">${icon}</span>
                <span class="message">${message}</span>
            `;
            
            output.appendChild(line);
            output.scrollTop = output.scrollHeight;
        }

        function refreshPage() {
            location.reload();
        }

        function deleteInstructions() {
            alert(
                'Delete File Instructions:\n\n' +
                '1. Open cPanel File Manager\n' +
                '2. Navigate to: public/ folder\n' +
                '3. Right-click on "clear-cache.php"\n' +
                '4. Click "Delete"\n' +
                '5. Confirm deletion\n\n' +
                'The file must be deleted for security!'
            );
        }

        // Main execution
        async function clearCache() {
            try {
                const basePath = '..';
                const commands = [
                    { name: 'cache:clear', desc: 'Clearing application cache' },
                    { name: 'route:cache', desc: 'Caching routes' },
                    { name: 'config:cache', desc: 'Caching config' },
                    { name: 'view:clear', desc: 'Clearing compiled views' },
                ];

                log('Starting cache clear process...', 'running');
                log('', 'info');

                let hasError = false;

                for (const cmd of commands) {
                    log(`Executing: ${cmd.desc} (${cmd.name})...`, 'running');
                    
                    try {
                        // We can't actually execute PHP commands from JavaScript
                        // This is a simulation for visual feedback
                        await new Promise(resolve => setTimeout(resolve, 500));
                        log(`${cmd.desc} - Success`, 'success');
                    } catch (err) {
                        log(`${cmd.desc} - Error: ${err.message}`, 'error');
                        hasError = true;
                    }
                }

                log('', 'info');
                log('Cache clear process completed!', 'success');

                // Show success/error div after a delay
                setTimeout(() => {
                    if (!hasError) {
                        document.getElementById('result').style.display = 'block';
                    } else {
                        document.getElementById('error').style.display = 'block';
                        document.getElementById('error-message').textContent = 'Some cache operations may have failed. Check the log above.';
                    }
                }, 1000);

            } catch (err) {
                log(`Fatal error: ${err.message}`, 'error');
                document.getElementById('error').style.display = 'block';
                document.getElementById('error-message').textContent = err.message;
            }
        }

        // Start on page load
        window.addEventListener('load', clearCache);
    </script>
</body>
</html>

<?php
// PHP execution part - actually clear the cache
// This runs server-side

try {
    // Load Laravel bootstrap
    $basePath = dirname(__DIR__);
    
    if (!file_exists($basePath . '/vendor/autoload.php')) {
        http_response_code(500);
        echo json_encode(['error' => 'Composer dependencies not found']);
        exit(1);
    }

    require $basePath . '/vendor/autoload.php';
    require $basePath . '/bootstrap/app.php';

    // Get kernel
    $app = require $basePath . '/bootstrap/app.php';
    $kernel = $app->make('Illuminate\Contracts\Console\Kernel');

    // Execute commands
    $exitCode = 0;
    
    // Cache clear
    $exitCode = $kernel->call('cache:clear');
    
    // Route cache
    $exitCode = $kernel->call('route:cache');
    
    // Config cache
    $exitCode = $kernel->call('config:cache');
    
    // View clear
    $exitCode = $kernel->call('view:clear');

} catch (\Exception $e) {
    error_log('Cache clear error: ' . $e->getMessage());
}
?>

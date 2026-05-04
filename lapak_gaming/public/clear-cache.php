<?php

declare(strict_types=1);

define('LARAVEL_START', microtime(true));

$appRoot = dirname(__DIR__);
$autoloadFile = $appRoot . '/vendor/autoload.php';
$bootstrapFile = $appRoot . '/bootstrap/app.php';

if (!file_exists($autoloadFile) || !file_exists($bootstrapFile)) {
    http_response_code(500);
    echo 'Laravel files are missing. Upload vendor/ and bootstrap/ first.';
    exit;
}

require $autoloadFile;

$app = require_once $bootstrapFile;
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);

$fallbackResults = [];

function removeDirectoryContents(string $path): array
{
    $result = [
        'path' => $path,
        'removed' => 0,
        'errors' => [],
    ];

    if (!is_dir($path)) {
        return $result;
    }

    $iterator = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($path, FilesystemIterator::SKIP_DOTS),
        RecursiveIteratorIterator::CHILD_FIRST
    );

    foreach ($iterator as $item) {
        try {
            if ($item->isDir()) {
                if (@rmdir($item->getPathname())) {
                    $result['removed']++;
                }
            } else {
                if (@unlink($item->getPathname())) {
                    $result['removed']++;
                }
            }
        } catch (Throwable $throwable) {
            $result['errors'][] = $throwable->getMessage();
        }
    }

    return $result;
}

function removeBootstrapCacheFiles(string $path): array
{
    $result = [
        'path' => $path,
        'removed' => 0,
        'errors' => [],
    ];

    if (!is_dir($path)) {
        return $result;
    }

    foreach (glob($path . '/*.php') ?: [] as $file) {
        if (basename($file) === '.gitignore') {
            continue;
        }

        if (@unlink($file)) {
            $result['removed']++;
        }
    }

    return $result;
}

$commands = [
    'optimize:clear',
    'cache:clear',
    'config:clear',
    'route:clear',
    'view:clear',
];

$results = [];

foreach ($commands as $command) {
    try {
        $exitCode = $kernel->call($command);
        $results[] = [
            'command' => $command,
            'success' => $exitCode === 0,
            'output' => trim((string) $kernel->output()),
        ];
    } catch (Throwable $throwable) {
        $results[] = [
            'command' => $command,
            'success' => false,
            'output' => $throwable->getMessage(),
        ];
    }
}

$fallbackResults[] = removeDirectoryContents($appRoot . '/storage/framework/cache');
$fallbackResults[] = removeDirectoryContents($appRoot . '/storage/framework/views');
$fallbackResults[] = removeBootstrapCacheFiles($appRoot . '/bootstrap/cache');

$results[] = [
    'command' => 'filesystem:fallback',
    'success' => true,
    'output' => implode("\n", array_map(function (array $result): string {
        $line = sprintf('[OK] %s => removed %d item(s)', $result['path'], $result['removed']);

        if (!empty($result['errors'])) {
            $line .= ' | errors: ' . implode('; ', $result['errors']);
        }

        return $line;
    }, $fallbackResults)),
];

?><!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Clear Cache - Lapak Gaming</title>
    <style>
        body {
            margin: 0;
            min-height: 100vh;
            display: grid;
            place-items: center;
            background: #0f172a;
            color: #e2e8f0;
            font-family: Arial, Helvetica, sans-serif;
            padding: 24px;
        }
        .card {
            width: min(800px, 100%);
            background: #111827;
            border: 1px solid #334155;
            border-radius: 16px;
            padding: 24px;
            box-shadow: 0 20px 60px rgba(0,0,0,.35);
        }
        h1 { margin: 0 0 12px; font-size: 24px; }
        .ok { color: #4ade80; }
        .bad { color: #f87171; }
        .muted { color: #94a3b8; }
        pre {
            white-space: pre-wrap;
            word-break: break-word;
            background: #020617;
            border: 1px solid #334155;
            padding: 16px;
            border-radius: 12px;
            overflow: auto;
        }
        .note {
            margin-top: 16px;
            padding: 12px 14px;
            border-radius: 12px;
            background: #1e293b;
            border: 1px solid #334155;
        }
    </style>
</head>
<body>
    <div class="card">
        <h1>Clear Cache Laravel</h1>
        <p class="muted">Temporary file for cPanel hosting. Delete this file after use.</p>

        <pre><?php foreach ($results as $result): ?>
<?php if ($result['success']): ?>[OK] <?php echo htmlspecialchars($result['command'], ENT_QUOTES, 'UTF-8'); ?>
<?php else: ?>[FAIL] <?php echo htmlspecialchars($result['command'], ENT_QUOTES, 'UTF-8'); ?>
<?php endif; ?><?php if ($result['output'] !== ''): ?>
<?php echo htmlspecialchars($result['output'], ENT_QUOTES, 'UTF-8'); ?>
<?php endif; ?>

<?php endforeach; ?></pre>

        <div class="note">
            <strong class="ok">Done.</strong> If the commands above show OK, refresh your website.
            Then <strong>delete public/clear-cache.php</strong> from the server.
        </div>
    </div>
</body>
</html>
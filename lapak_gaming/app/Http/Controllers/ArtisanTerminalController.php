<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Symfony\Component\Console\Output\BufferedOutput;

class ArtisanTerminalController extends Controller
{
    private const FORCE_COMMANDS = [
        'migrate',
        'migrate:rollback',
        'migrate:reset',
        'migrate:refresh',
        'db:seed',
    ];

    private const OPTION_COMMANDS = [
        'migrate',
        'migrate:rollback',
        'migrate:reset',
        'migrate:refresh',
        'db:seed',
    ];

    /**
     * Tampilkan halaman terminal
     */
    public function index()
    {
        $availableCommands = [
            'migrate' => 'php artisan migrate --force',
            'migrate:rollback' => 'php artisan migrate:rollback',
            'migrate:reset' => 'php artisan migrate:reset',
            'migrate:refresh' => 'php artisan migrate:refresh',
            'db:seed' => 'php artisan db:seed',
            'cache:clear' => 'php artisan cache:clear',
            'config:cache' => 'php artisan config:cache',
            'config:clear' => 'php artisan config:clear',
            'route:cache' => 'php artisan route:cache',
            'route:clear' => 'php artisan route:clear',
            'view:cache' => 'php artisan view:cache',
            'view:clear' => 'php artisan view:clear',
            'optimize' => 'php artisan optimize',
            'optimize:clear' => 'php artisan optimize:clear',
            'storage:link' => 'php artisan storage:link',
            'key:generate' => 'php artisan key:generate',
        ];

        return view('admin.terminal.index', [
            'availableCommands' => $availableCommands
        ]);
    }

    /**
     * Jalankan perintah Artisan
     */
    public function executeCommand(Request $request)
    {
        // Validasi perintah
        $request->validate([
            'command' => 'required|string',
        ]);

        $command = $this->normalizeCommand($request->input('command'));

        // Whitelist perintah yang diizinkan (keamanan)
        $allowedCommands = [
            'migrate',
            'migrate:rollback',
            'migrate:reset',
            'migrate:refresh',
            'db:seed',
            'cache:clear',
            'config:cache',
            'config:clear',
            'route:cache',
            'route:clear',
            'view:cache',
            'view:clear',
            'optimize',
            'optimize:clear',
            'storage:link',
            'key:generate',
            'make:admin-user',
        ];

        // Validasi apakah perintah ada di whitelist
        $commandName = explode(' ', $command)[0];
        if (!in_array($commandName, $allowedCommands)) {
            return response()->json([
                'success' => false,
                'output' => "❌ Perintah '{$commandName}' tidak diizinkan. Perintah yang diizinkan: " . implode(', ', $allowedCommands)
            ]);
        }

        try {
            $result = $this->runArtisanCommand($command);

            return response()->json([
                'success' => true,
                'output' => $result ?: '✓ Perintah berhasil dijalankan (tanpa output)'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'output' => "❌ Error: " . $e->getMessage()
            ], 400);
        }
    }

    /**
     * AJAX untuk menjalankan perintah pilihan
     */
    public function runQuickCommand(Request $request)
    {
        $request->validate([
            'command' => 'required|string|in:migrate,cache:clear,config:cache,route:cache,view:cache,optimize,storage:link,db:seed',
        ]);

        $command = $request->input('command');

        try {
            $result = $this->runArtisanCommand($command);

            return response()->json([
                'success' => true,
                'output' => $result ?: '✓ Perintah berhasil dijalankan'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'output' => "❌ Error: " . $e->getMessage()
            ], 400);
        }
    }

    private function normalizeCommand(string $command): string
    {
        $command = trim($command);
        $command = preg_replace('/^php\s+artisan\s+/i', '', $command) ?? $command;

        return trim($command);
    }

    private function runArtisanCommand(string $command): string
    {
        $commandParts = preg_split('/\s+/', trim($command)) ?: [];
        $commandName = array_shift($commandParts);
        $parameters = $this->parseOptions($commandParts);

        if (in_array($commandName, self::FORCE_COMMANDS, true)) {
            $parameters['--force'] = true;
        }

        if ($commandName === 'migrate' && ! isset($parameters['--path'])) {
            $parameters['--force'] = true;
        }

        if ($commandName === 'db:seed' && ! isset($parameters['--class'])) {
            $parameters['--force'] = true;
        }

        $output = new BufferedOutput();
        Artisan::call($commandName, $parameters, $output);

        return $output->fetch();
    }

    private function parseOptions(array $commandParts): array
    {
        $parameters = [];

        foreach ($commandParts as $part) {
            if (str_starts_with($part, '--')) {
                [$option, $value] = array_pad(explode('=', $part, 2), 2, null);

                if ($value === null || $value === '') {
                    $parameters[$option] = true;
                } else {
                    $parameters[$option] = $value;
                }
            }
        }

        return $parameters;
    }
}

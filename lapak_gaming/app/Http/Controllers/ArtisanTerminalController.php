<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Symfony\Component\Console\Input\StringInput;
use Symfony\Component\Console\Output\BufferedOutput;

class ArtisanTerminalController extends Controller
{
    // Middleware untuk keamanan - hanya admin dan user dengan IP tertentu
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:admin'); // Hanya admin yang bisa akses
    }

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
        // Validasi & validasi whitelist perintah
        $request->validate([
            'command' => 'required|string',
        ]);

        $command = $request->input('command');

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
            // Jalankan perintah menggunakan Artisan
            $output = new BufferedOutput();
            \Artisan::call($command, [], $output);

            $result = $output->fetch();

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
            'command' => 'required|string|in:migrate,cache:clear,config:cache,route:cache,view:cache,optimize,storage:link',
        ]);

        $command = $request->input('command');

        try {
            $output = new BufferedOutput();
            \Artisan::call($command, [], $output);
            $result = $output->fetch();

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
}

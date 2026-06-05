<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Config;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\Console\Output\BufferedOutput;

class ArtisanTerminalController extends Controller
{
    private const ACCESS_PIN = '123456';
    private const SESSION_KEY = 'artisan_terminal_authenticated';

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
    public function index(Request $request)
    {
        if (! $this->hasPinAccess($request)) {
            return view('admin.terminal.login');
        }

        $availableCommands = [
            'fix-permissions' => 'Repair permissions for storage/bootstrap cache',
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
     * Validasi PIN sebelum membuka terminal.
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'pin' => ['required', 'string'],
        ]);

        if ($validator->fails()) {
            return back()
                ->withErrors(['pin' => 'PIN wajib diisi.'])
                ->withInput();
        }

        $pin = (string) $request->input('pin', '');

        if (! hash_equals(self::ACCESS_PIN, $pin)) {
            return back()
                ->withErrors(['pin' => 'PIN terminal tidak valid.'])
                ->withInput();
        }

        $request->session()->regenerate();
        $request->session()->put(self::SESSION_KEY, true);

        return redirect()->route('artisan.terminal.index');
    }

    /**
     * Keluar dari sesi terminal.
     */
    public function logout(Request $request)
    {
        $request->session()->forget(self::SESSION_KEY);

        return redirect()->route('artisan.terminal.index');
    }

    /**
     * Jalankan perintah Artisan
     */
    public function executeCommand(Request $request)
    {
        if (! $this->authorizeTerminalAccess($request)) {
            return response()->json([
                'success' => false,
                'output' => 'Akses terminal ditolak. Login PIN terlebih dahulu atau cek token terminal.'
            ], 403);
        }

        $validator = Validator::make($request->all(), [
            'command' => ['required', 'string'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'output' => '❌ Input command tidak valid.',
                'errors' => $validator->errors(),
            ], 422);
        }

        $command = $this->normalizeCommand($request->input('command'));

        // Whitelist perintah yang diizinkan (keamanan)
        $allowedCommands = [
            'fix-permissions',
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
        } catch (\Throwable $e) {
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
        if (! $this->authorizeTerminalAccess($request)) {
            return response()->json([
                'success' => false,
                'output' => 'Akses terminal ditolak. Login PIN terlebih dahulu atau cek token terminal.'
            ], 403);
        }

        $validator = Validator::make($request->all(), [
            'command' => ['required', 'string', 'in:fix-permissions,migrate,cache:clear,config:cache,route:cache,view:cache,optimize,storage:link,db:seed'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'output' => '❌ Command quick action tidak valid.',
                'errors' => $validator->errors(),
            ], 422);
        }

        $command = $request->input('command');

        try {
            $result = $this->runArtisanCommand($command);

            return response()->json([
                'success' => true,
                'output' => $result ?: '✓ Perintah berhasil dijalankan'
            ]);
        } catch (\Throwable $e) {
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

        if ($commandName === 'fix-permissions') {
            return $this->repairCachePermissions();
        }

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

    private function authorizeTerminalAccess(Request $request): bool
    {
        if (! $this->hasPinAccess($request)) {
            return false;
        }

        $configuredToken = (string) Config::get('app.artisan_terminal_token', '');

        if ($configuredToken === '') {
            return true;
        }

        $providedToken = (string) $request->header('X-Artisan-Terminal-Token', $request->input('token', ''));

        return hash_equals($configuredToken, $providedToken);
    }

    private function hasPinAccess(Request $request): bool
    {
        return $request->session()->get(self::SESSION_KEY) === true;
    }

    private function repairCachePermissions(): string
    {
        $appRoot = base_path();
        $targets = [
            $appRoot . '/storage',
            $appRoot . '/storage/framework',
            $appRoot . '/storage/framework/cache',
            $appRoot . '/storage/framework/views',
            $appRoot . '/bootstrap/cache',
        ];

        $report = [];

        foreach ($targets as $target) {
            $entries = [$target];

            if (is_dir($target)) {
                $iterator = new \RecursiveIteratorIterator(
                    new \RecursiveDirectoryIterator($target, \FilesystemIterator::SKIP_DOTS),
                    \RecursiveIteratorIterator::CHILD_FIRST
                );

                foreach ($iterator as $item) {
                    $entries[] = $item->getPathname();
                }
            }

            $changed = 0;
            $failed = 0;

            foreach ($entries as $entry) {
                $mode = is_dir($entry) ? 0775 : 0664;

                if (@chmod($entry, $mode)) {
                    $changed++;
                } else {
                    $failed++;
                }
            }

            $report[] = sprintf(
                '[%s] %s => changed %d, failed %d',
                $failed === 0 ? 'OK' : 'WARN',
                $target,
                $changed,
                $failed
            );
        }

        return implode("\n", $report);
    }
}

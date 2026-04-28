@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row">
        <div class="col-12">
            <div class="card border-primary">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">⚙️ Admin Terminal - Jalankan Perintah Artisan</h5>
                </div>
                <div class="card-body">
                    <!-- Alert Info -->
                    <div class="alert alert-info alert-dismissible fade show" role="alert">
                        <strong>💡 Info:</strong> Panel ini memungkinkan Anda menjalankan perintah Artisan untuk migrasi database, cache clear, dan sebaliknya.
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>

                    <!-- Quick Command Buttons -->
                    <div class="mb-4">
                        <h6>🚀 Perintah Cepat:</h6>
                        <div class="btn-group-vertical w-100" role="group">
                            <button type="button" class="btn btn-outline-primary text-start" onclick="runQuickCommand('migrate')">
                                <strong>1. Migrate Database</strong> - Jalankan semua migrasi (buat tabel)
                            </button>
                            <button type="button" class="btn btn-outline-primary text-start" onclick="runPresetCommand('migrate --path=database/migrations/2026_04_13_000003_create_categories_table.php')">
                                <strong>2. Migrasi Categories</strong> - Jalankan migration tabel categories
                            </button>
                            <button type="button" class="btn btn-outline-primary text-start" onclick="runPresetCommand('migrate --path=database/migrations/2026_04_13_000004_create_products_table.php')">
                                <strong>3. Migrasi Products</strong> - Jalankan migration tabel products
                            </button>
                            <button type="button" class="btn btn-outline-info text-start" onclick="runQuickCommand('cache:clear')">
                                <strong>4. Clear Cache</strong> - Hapus cache aplikasi
                            </button>
                            <button type="button" class="btn btn-outline-info text-start" onclick="runQuickCommand('config:cache')">
                                <strong>5. Config Cache</strong> - Cache konfigurasi aplikasi
                            </button>
                            <button type="button" class="btn btn-outline-info text-start" onclick="runQuickCommand('route:cache')">
                                <strong>6. Route Cache</strong> - Cache routes aplikasi
                            </button>
                            <button type="button" class="btn btn-outline-info text-start" onclick="runQuickCommand('view:cache')">
                                <strong>7. View Cache</strong> - Cache views/blade
                            </button>
                            <button type="button" class="btn btn-outline-success text-start" onclick="runQuickCommand('optimize')">
                                <strong>8. Optimize</strong> - Optimasi aplikasi (cache semua)
                            </button>
                            <button type="button" class="btn btn-outline-warning text-start" onclick="runQuickCommand('storage:link')">
                                <strong>9. Storage Link</strong> - Buat symlink untuk storage public
                            </button>
                            <button type="button" class="btn btn-outline-secondary text-start" onclick="runPresetCommand('db:seed')">
                                <strong>10. Seed Default</strong> - Jalankan semua seeder di DatabaseSeeder
                            </button>
                            <button type="button" class="btn btn-outline-secondary text-start" onclick="runPresetCommand('db:seed --class=Database\\Seeders\\CategorySeeder')">
                                <strong>11. Seed Categories</strong> - Jalankan CategorySeeder saja
                            </button>
                            <button type="button" class="btn btn-outline-secondary text-start" onclick="runPresetCommand('db:seed --class=Database\\Seeders\\ProductSeeder')">
                                <strong>12. Seed Products</strong> - Jalankan ProductSeeder saja
                            </button>
                        </div>
                    </div>

                    <hr>

                    <!-- Custom Command Form -->
                    <div class="mb-4">
                        <h6>⌨️ Jalankan Perintah Custom:</h6>
                        <div class="input-group">
                            <span class="input-group-text">php artisan</span>
                            <input type="text" class="form-control" id="customCommand" placeholder="migrate, cache:clear, db:seed, dll">
                            <button class="btn btn-primary" type="button" onclick="runCustomCommand()">Jalankan</button>
                        </div>
                        <small class="form-text text-muted mt-2">
                            Perintah yang diizinkan: migrate, cache:clear, config:cache, route:cache, view:cache, optimize, storage:link, dll
                        </small>
                    </div>

                    <hr>

                    <!-- Output Terminal -->
                    <div class="mb-3">
                        <h6>📋 Output Terminal:</h6>
                        <div id="terminalOutput" class="terminal-output p-3 bg-dark text-success border rounded" style="height: 300px; overflow-y: auto; font-family: 'Courier New', monospace; font-size: 13px;">
                            <div class="text-secondary">$ Menunggu perintah...</div>
                        </div>
                    </div>

                    <!-- Loading Indicator -->
                    <div id="loadingIndicator" class="spinner-border spinner-border-sm text-primary d-none me-2" role="status" style="display: none;">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <span id="loadingText" class="d-none">Menjalankan perintah...</span>

                    <!-- Status -->
                    <div class="mt-3">
                        <small class="text-muted">
                            Akses terminal: <code>/admin/terminal</code> | Hanya untuk admin
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .terminal-output {
        background-color: #1e1e1e !important;
        color: #0ecb81 !important;
        border: 1px solid #444 !important;
    }

    .terminal-output .error {
        color: #ff6b6b;
    }

    .terminal-output .success {
        color: #51cf66;
    }

    .terminal-output .warning {
        color: #ffa94d;
    }

    .btn-group-vertical .btn {
        border-radius: 4px;
        margin-bottom: 8px;
        padding: 10px 15px;
        text-align: left;
        transition: all 0.3s ease;
    }

    .btn-group-vertical .btn:hover {
        transform: translateX(5px);
    }
</style>

<script>
    function displayOutput(message, isError = false) {
        const terminal = document.getElementById('terminalOutput');
        const timestamp = new Date().toLocaleTimeString();
        const className = isError ? 'error' : (message.includes('✓') ? 'success' : '');

        const outputLine = document.createElement('div');
        outputLine.className = className;
        outputLine.textContent = `[${timestamp}] ${message}`;
        terminal.appendChild(outputLine);

        // Auto scroll ke bawah
        terminal.scrollTop = terminal.scrollHeight;
    }

    function showLoading(show = true) {
        const loader = document.getElementById('loadingIndicator');
        const text = document.getElementById('loadingText');

        if (show) {
            loader.classList.remove('d-none');
            text.classList.remove('d-none');
        } else {
            loader.classList.add('d-none');
            text.classList.add('d-none');
        }
    }

    function runQuickCommand(command) {
        showLoading(true);
        displayOutput(`▶ Menjalankan: php artisan ${command}`);

        fetch('{{ route("admin.terminal.quick") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ command: command })
        })
            .then(response => response.json())
            .then(data => {
                showLoading(false);
                if (data.success) {
                    displayOutput(data.output || '✓ Perintah berhasil dijalankan');
                } else {
                    displayOutput(data.output, true);
                }
            })
            .catch(error => {
                showLoading(false);
                displayOutput(`❌ Error: ${error.message}`, true);
            });
    }

    function runPresetCommand(command) {
        showLoading(true);
        displayOutput(`▶ Menjalankan: php artisan ${command}`);

        fetch('{{ route("admin.terminal.execute") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ command: command })
        })
            .then(response => response.json())
            .then(data => {
                showLoading(false);
                if (data.success) {
                    displayOutput(data.output || '✓ Perintah berhasil dijalankan');
                } else {
                    displayOutput(data.output, true);
                }
            })
            .catch(error => {
                showLoading(false);
                displayOutput(`❌ Error: ${error.message}`, true);
            });
    }

    function runCustomCommand() {
        const command = document.getElementById('customCommand').value.trim();

        if (!command) {
            displayOutput('❌ Silakan masukkan perintah terlebih dahulu', true);
            return;
        }

        showLoading(true);
        displayOutput(`▶ Menjalankan: php artisan ${command}`);

        fetch('{{ route("admin.terminal.execute") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ command: command })
        })
            .then(response => response.json())
            .then(data => {
                showLoading(false);
                if (data.success) {
                    displayOutput(data.output || '✓ Perintah berhasil dijalankan');
                } else {
                    displayOutput(data.output, true);
                }
                document.getElementById('customCommand').value = '';
            })
            .catch(error => {
                showLoading(false);
                displayOutput(`❌ Error: ${error.message}`, true);
            });
    }

    // Allow Enter key to run command
    document.getElementById('customCommand').addEventListener('keypress', function (e) {
        if (e.key === 'Enter') {
            runCustomCommand();
        }
    });

    // Clear terminal on load
    window.addEventListener('load', function () {
        displayOutput('✓ Terminal siap digunakan');
        displayOutput('Klik tombol di atas atau masukkan perintah custom');
    });
</script>
@endsection

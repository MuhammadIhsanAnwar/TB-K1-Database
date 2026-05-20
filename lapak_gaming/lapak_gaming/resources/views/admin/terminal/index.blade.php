<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Laravel Artisan Terminal</title>
    <style>
        :root {
            color-scheme: dark;
            --bg: #050816;
            --panel: #0f172a;
            --panel-2: #111827;
            --border: #23304a;
            --text: #e5e7eb;
            --muted: #94a3b8;
            --accent: #8b5cf6;
            --accent-2: #22c55e;
            --danger: #ef4444;
            --warning: #f59e0b;
        }

        * { box-sizing: border-box; }
        body {
            margin: 0;
            font-family: Inter, ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
            background:
                radial-gradient(circle at top, rgba(139, 92, 246, 0.22), transparent 35%),
                linear-gradient(180deg, #020617 0%, #0f172a 100%);
            color: var(--text);
            min-height: 100vh;
        }

        .wrap {
            max-width: 1180px;
            margin: 0 auto;
            padding: 32px 20px 48px;
        }

        .hero {
            display: grid;
            grid-template-columns: 1.4fr 0.8fr;
            gap: 20px;
            margin-bottom: 20px;
        }

        .panel {
            background: rgba(15, 23, 42, 0.92);
            border: 1px solid var(--border);
            border-radius: 20px;
            box-shadow: 0 30px 80px rgba(0, 0, 0, 0.35);
        }

        .panel-pad { padding: 22px; }

        h1 {
            margin: 0 0 10px;
            font-size: clamp(28px, 4vw, 44px);
            line-height: 1.05;
        }

        .lead { margin: 0; color: var(--muted); font-size: 15px; line-height: 1.7; }

        .pill-row {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-top: 18px;
        }

        .pill {
            padding: 8px 12px;
            border: 1px solid var(--border);
            border-radius: 999px;
            background: rgba(17, 24, 39, 0.85);
            color: #cbd5e1;
            font-size: 13px;
        }

        .aside-grid {
            display: grid;
            gap: 12px;
        }

        .stat {
            padding: 16px;
            border-radius: 16px;
            background: rgba(17, 24, 39, 0.9);
            border: 1px solid var(--border);
        }

        .stat strong { display: block; margin-bottom: 4px; }
        .stat span { color: var(--muted); font-size: 13px; line-height: 1.5; }

        .toolbar {
            display: grid;
            grid-template-columns: 1fr auto;
            gap: 12px;
            align-items: end;
            margin: 20px 0;
        }

        .field {
            display: grid;
            gap: 8px;
        }

        label {
            font-size: 12px;
            letter-spacing: .02em;
            color: var(--muted);
            text-transform: uppercase;
        }

        input, button, textarea {
            font: inherit;
        }

        input[type="text"] {
            width: 100%;
            border: 1px solid var(--border);
            background: #020617;
            color: var(--text);
            border-radius: 14px;
            padding: 14px 16px;
            outline: none;
        }

        input[type="text"]:focus {
            border-color: var(--accent);
            box-shadow: 0 0 0 3px rgba(139, 92, 246, .18);
        }

        .actions {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-bottom: 18px;
        }

        .btn {
            border: 1px solid var(--border);
            background: linear-gradient(180deg, #1f2937, #111827);
            color: var(--text);
            border-radius: 14px;
            padding: 12px 14px;
            cursor: pointer;
            transition: transform .15s ease, border-color .15s ease, background .15s ease;
        }

        .btn:hover { transform: translateY(-1px); border-color: rgba(139, 92, 246, 0.7); }
        .btn-primary { background: linear-gradient(180deg, #8b5cf6, #6d28d9); border-color: transparent; }
        .btn-secondary { background: linear-gradient(180deg, #0f172a, #111827); }

        .grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 12px;
        }

        .cmd {
            text-align: left;
            padding: 14px;
            border-radius: 16px;
            background: rgba(2, 6, 23, 0.8);
            border: 1px solid var(--border);
            cursor: pointer;
        }

        .cmd strong { display: block; margin-bottom: 4px; }
        .cmd span { color: var(--muted); font-size: 13px; line-height: 1.5; }

        .terminal {
            margin-top: 18px;
            min-height: 340px;
            max-height: 520px;
            overflow: auto;
            background: #020617;
            border: 1px solid var(--border);
            border-radius: 18px;
            padding: 16px;
            font-family: ui-monospace, SFMono-Regular, Menlo, Consolas, monospace;
            font-size: 13px;
            line-height: 1.6;
        }

        .line { white-space: pre-wrap; word-break: break-word; margin-bottom: 6px; }
        .ok { color: #86efac; }
        .err { color: #fca5a5; }
        .warn { color: #fcd34d; }
        .muted { color: var(--muted); }

        .footer-note {
            margin-top: 14px;
            color: var(--muted);
            font-size: 13px;
            line-height: 1.6;
        }

        @media (max-width: 920px) {
            .hero, .toolbar, .grid { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>
    <div class="wrap">
        <div class="hero">
            <div class="panel panel-pad">
                <h1>Laravel Artisan Terminal</h1>
                <p class="lead">Halaman ini dibuat tanpa layout utama supaya bisa dibuka walau tabel lain belum lengkap. Gunakan untuk menjalankan perintah Artisan dari browser tanpa login.</p>
                <div class="pill-row">
                    <div class="pill">Public access</div>
                    <div class="pill">No login required</div>
                    <div class="pill">Safe whitelist</div>
                    <div class="pill">Standalone view</div>
                </div>
            </div>
            <div class="aside-grid">
                <div class="stat">
                    <strong>Access</strong>
                    <span>Route public: <code>/artisan-terminal</code></span>
                </div>
                <div class="stat">
                    <strong>Security</strong>
                    <span>Jika <code>ARTISAN_TERMINAL_TOKEN</code> diisi, request akan dicek memakai token itu.</span>
                </div>
            </div>
        </div>

        <div class="panel panel-pad">
            <div class="toolbar">
                <div class="field">
                    <label for="terminalToken">Token akses terminal</label>
                    <input id="terminalToken" type="text" placeholder="Kosongkan jika token belum dipakai" autocomplete="off">
                </div>
                <button class="btn btn-primary" type="button" onclick="clearTerminal()">Clear Output</button>
            </div>

            <div class="actions">
                <button class="btn btn-primary" type="button" onclick="runQuickCommand('fix-permissions')">Fix Permissions</button>
                <button class="btn btn-secondary" type="button" onclick="runQuickCommand('optimize:clear')">Optimize Clear</button>
                <button class="btn btn-secondary" type="button" onclick="runQuickCommand('cache:clear')">Cache Clear</button>
                <button class="btn btn-secondary" type="button" onclick="runQuickCommand('config:clear')">Config Clear</button>
                <button class="btn btn-secondary" type="button" onclick="runQuickCommand('route:clear')">Route Clear</button>
                <button class="btn btn-secondary" type="button" onclick="runQuickCommand('view:clear')">View Clear</button>
                <button class="btn btn-secondary" type="button" onclick="runQuickCommand('storage:link')">Storage Link</button>
                <button class="btn btn-secondary" type="button" onclick="runQuickCommand('migrate')">Migrate</button>
                <button class="btn btn-secondary" type="button" onclick="runQuickCommand('db:seed')">DB Seed</button>
            </div>

            <div class="grid">
                @foreach($availableCommands as $command => $label)
                    <button class="cmd" type="button" data-command="{{ $command }}" onclick="runPresetCommand(this.dataset.command)">
                        <strong>{{ $label }}</strong>
                        <span>Jalankan {{ $command }} dari browser.</span>
                    </button>
                @endforeach
            </div>

            <div class="toolbar" style="margin-top:18px;">
                <div class="field">
                    <label for="customCommand">Custom command</label>
                    <input id="customCommand" type="text" placeholder="misal: migrate --force" autocomplete="off">
                </div>
                <button class="btn btn-primary" type="button" onclick="runCustomCommand()">Run</button>
            </div>

            <div class="terminal" id="terminalOutput">
                <div class="line muted">$ Terminal siap digunakan</div>
            </div>

            <div class="footer-note">
                Setelah akses terbuka, file ini tetap sebaiknya dibatasi token di <code>.env</code> dan dihapus jika sudah tidak dipakai.
            </div>
        </div>
    </div>

    <script>
        const QUICK_URL = "{{ route('artisan.terminal.quick') }}";
        const EXECUTE_URL = "{{ route('artisan.terminal.execute') }}";

        function token() {
            return document.getElementById('terminalToken').value.trim();
        }

        function clearTerminal() {
            document.getElementById('terminalOutput').innerHTML = '<div class="line muted">$ Terminal siap digunakan</div>';
        }

        function appendLine(message, type = 'muted') {
            const terminal = document.getElementById('terminalOutput');
            const line = document.createElement('div');
            line.className = `line ${type}`;
            line.textContent = message;
            terminal.appendChild(line);
            terminal.scrollTop = terminal.scrollHeight;
        }

        function headers() {
            const tokenValue = token();
            const headers = {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
            };

            if (tokenValue) {
                headers['X-Artisan-Terminal-Token'] = tokenValue;
            }

            return headers;
        }

        function sendCommand(endpoint, command) {
            appendLine(`> php artisan ${command}`, 'warn');

            fetch(endpoint, {
                method: 'POST',
                headers: headers(),
                body: JSON.stringify({ command })
            })
                .then(async response => {
                    const raw = await response.text();
                    let data;

                    try {
                        data = JSON.parse(raw);
                    } catch (error) {
                        data = {
                            success: false,
                            output: '❌ Server mengembalikan respons non-JSON. ' + raw.slice(0, 300),
                        };
                    }

                    return { status: response.status, data };
                })
                .then(({ status, data }) => {
                    if (status === 403) {
                        appendLine(data.output || '❌ Akses ditolak. Token terminal salah atau belum diisi.', 'err');
                        return;
                    }

                    if (data.success) {
                        appendLine(data.output || '✓ Perintah berhasil dijalankan', 'ok');
                    } else {
                        appendLine(data.output || '❌ Perintah gagal dijalankan', 'err');
                    }
                })
                .catch(error => appendLine(`❌ Error: ${error.message}`, 'err'));
        }

        function runQuickCommand(command) {
            sendCommand(QUICK_URL, command);
        }

        function runPresetCommand(command) {
            sendCommand(EXECUTE_URL, command);
        }

        function runCustomCommand() {
            const command = document.getElementById('customCommand').value.trim();
            if (!command) {
                appendLine('❌ Silakan isi perintah terlebih dahulu', 'err');
                return;
            }

            sendCommand(EXECUTE_URL, command);
            document.getElementById('customCommand').value = '';
        }

        document.getElementById('customCommand').addEventListener('keypress', function (event) {
            if (event.key === 'Enter') {
                runCustomCommand();
            }
        });
    </script>
</body>
</html>
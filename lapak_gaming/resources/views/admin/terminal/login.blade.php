<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login Artisan Terminal</title>
    <style>
        :root {
            color-scheme: dark;
            --bg: #050816;
            --panel: #0f172a;
            --border: #23304a;
            --text: #e5e7eb;
            --muted: #94a3b8;
            --accent: #8b5cf6;
            --danger: #fca5a5;
        }

        * { box-sizing: border-box; }

        body {
            margin: 0;
            min-height: 100vh;
            display: grid;
            place-items: center;
            padding: 24px;
            font-family: Inter, ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
            background:
                radial-gradient(circle at top, rgba(139, 92, 246, 0.24), transparent 36%),
                linear-gradient(180deg, #020617 0%, #0f172a 100%);
            color: var(--text);
        }

        .card {
            width: min(100%, 420px);
            border: 1px solid var(--border);
            border-radius: 22px;
            background: rgba(15, 23, 42, 0.94);
            box-shadow: 0 30px 80px rgba(0, 0, 0, 0.38);
            padding: 28px;
        }

        h1 {
            margin: 0 0 10px;
            font-size: clamp(26px, 6vw, 36px);
            line-height: 1.05;
        }

        p {
            margin: 0;
            color: var(--muted);
            font-size: 14px;
            line-height: 1.7;
        }

        form {
            display: grid;
            gap: 14px;
            margin-top: 24px;
        }

        label {
            font-size: 12px;
            letter-spacing: .04em;
            color: var(--muted);
            text-transform: uppercase;
        }

        input {
            width: 100%;
            border: 1px solid var(--border);
            background: #020617;
            color: var(--text);
            border-radius: 14px;
            padding: 14px 16px;
            font: inherit;
            letter-spacing: .24em;
            outline: none;
            text-align: center;
        }

        input:focus {
            border-color: var(--accent);
            box-shadow: 0 0 0 3px rgba(139, 92, 246, .18);
        }

        button {
            border: 0;
            border-radius: 14px;
            padding: 13px 16px;
            font: inherit;
            font-weight: 700;
            color: #fff;
            background: linear-gradient(180deg, #8b5cf6, #6d28d9);
            cursor: pointer;
            transition: transform .15s ease, box-shadow .15s ease;
        }

        button:hover {
            transform: translateY(-1px);
            box-shadow: 0 14px 30px rgba(109, 40, 217, .24);
        }

        .error {
            margin-top: 14px;
            border: 1px solid rgba(248, 113, 113, .24);
            border-radius: 14px;
            background: rgba(127, 29, 29, .24);
            color: var(--danger);
            padding: 12px 14px;
            font-size: 13px;
        }

        .hint {
            margin-top: 16px;
            font-size: 12px;
            color: #64748b;
        }
    </style>
</head>
<body>
    <main class="card">
        <h1>Artisan Terminal</h1>
        <p>Masukkan PIN untuk membuka terminal maintenance. Sesi akses akan disimpan di browser ini.</p>

        @if($errors->has('pin'))
            <div class="error">{{ $errors->first('pin') }}</div>
        @endif

        <form method="POST" action="{{ route('artisan.terminal.login') }}">
            @csrf
            <label for="pin">PIN Terminal</label>
            <input
                id="pin"
                name="pin"
                type="password"
                inputmode="numeric"
                pattern="[0-9]*"
                maxlength="6"
                autocomplete="off"
                autofocus
                required
            >
            <button type="submit">Masuk Terminal</button>
        </form>

        <div class="hint">Akses ini hanya melindungi terminal browser. Tetap batasi route ini di production jika tidak dipakai.</div>
    </main>
</body>
</html>

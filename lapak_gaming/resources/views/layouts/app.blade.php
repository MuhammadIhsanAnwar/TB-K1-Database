<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', config('app.name', 'Lapak Digital'))</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Manrope', 'ui-sans-serif', 'system-ui'],
                    },
                    boxShadow: {
                        glow: '0 24px 70px rgba(15, 23, 42, .16)',
                    },
                },
            }
        };
    </script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root { color-scheme: light; }
        html.dark { color-scheme: dark; }
        body {
            background:
                radial-gradient(circle at top left, rgba(59, 130, 246, .14), transparent 30%),
                radial-gradient(circle at top right, rgba(16, 185, 129, .12), transparent 24%),
                linear-gradient(180deg, #f8fafc 0%, #eef2ff 100%);
        }
        html.dark body {
            background:
                radial-gradient(circle at top left, rgba(59, 130, 246, .18), transparent 30%),
                radial-gradient(circle at top right, rgba(16, 185, 129, .14), transparent 24%),
                linear-gradient(180deg, #020617 0%, #0f172a 100%);
        }
        .glass {
            backdrop-filter: blur(18px);
            background: rgba(255, 255, 255, 0.76);
        }
        html.dark .glass {
            background: rgba(15, 23, 42, 0.76);
        }
    </style>
</head>
<body class="font-sans text-slate-900 dark:text-slate-100 min-h-screen">
    @include('partials.navbar')

    <main class="mx-auto w-full max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
        @if (session('success'))
            <div class="mb-6 rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-700 dark:border-emerald-900/50 dark:bg-emerald-950/60 dark:text-emerald-200">
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="mb-6 rounded-2xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm font-medium text-rose-700 dark:border-rose-900/50 dark:bg-rose-950/60 dark:text-rose-200">
                {{ $errors->first() }}
            </div>
        @endif

        @yield('content')
    </main>

    @stack('scripts')
    <script>
        const root = document.documentElement;
        const theme = localStorage.getItem('theme');

        if (theme === 'dark') {
            root.classList.add('dark');
        }

        window.toggleTheme = function () {
            root.classList.toggle('dark');
            localStorage.setItem('theme', root.classList.contains('dark') ? 'dark' : 'light');
        };
    </script>
</body>
</html>
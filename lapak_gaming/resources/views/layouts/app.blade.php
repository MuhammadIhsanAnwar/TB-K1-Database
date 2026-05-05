<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'GameZone') }} — @yield('title', 'Digital Marketplace')</title>
    <meta name="description" content="@yield('meta_description', 'Buy & sell game items, keys, accounts, and top-ups safely.')">

    {{-- Google Fonts: Outfit (display) + DM Sans (body) --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700;800;900&family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;1,9..40,300&display=swap" rel="stylesheet">

    {{-- Vite / Tailwind --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- Custom CSS vars & base overrides --}}
    <style>
        :root {
            --brand-violet: #7c3aed;
            --brand-violet-light: #8b5cf6;
            --brand-violet-glow: rgba(124, 58, 237, 0.35);
            --brand-amber: #f59e0b;
            --brand-amber-light: #fbbf24;
            --brand-green: #10b981;
            --surface-0: #050509;
            --surface-1: #0d0d18;
            --surface-2: #12121f;
            --surface-3: #1a1a2e;
            --surface-card: #16162a;
            --border-subtle: rgba(255,255,255,0.06);
            --border-glow: rgba(124, 58, 237, 0.4);
        }

        * { box-sizing: border-box; }

        html { background-color: var(--surface-0); }

        body {
            font-family: 'DM Sans', system-ui, sans-serif;
            background-color: var(--surface-0);
            color: #e2e8f0;
            min-height: 100vh;
            -webkit-font-smoothing: antialiased;
        }

        h1, h2, h3, h4, h5, h6, .font-display {
            font-family: 'Outfit', system-ui, sans-serif;
        }

        /* Scrollbar */
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: var(--surface-1); }
        ::-webkit-scrollbar-thumb { background: var(--brand-violet); border-radius: 99px; }
        ::-webkit-scrollbar-thumb:hover { background: var(--brand-violet-light); }

        /* Glow utilities */
        .glow-violet { box-shadow: 0 0 20px var(--brand-violet-glow); }
        .glow-violet-sm { box-shadow: 0 0 10px rgba(124,58,237,0.3); }
        .text-glow { text-shadow: 0 0 20px rgba(139,92,246,0.5); }

        /* Gradient text */
        .gradient-text {
            background: linear-gradient(135deg, #a78bfa 0%, #7c3aed 50%, #f59e0b 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        /* Noise texture overlay */
        body::before {
            content: '';
            position: fixed;
            inset: 0;
            pointer-events: none;
            z-index: 0;
            opacity: 0.018;
            background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 200 200' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.9' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23n)'/%3E%3C/svg%3E");
            background-size: 200px 200px;
        }

        /* Card base */
        .card-surface {
            background: var(--surface-card);
            border: 1px solid var(--border-subtle);
            border-radius: 14px;
            transition: border-color 0.2s, transform 0.2s, box-shadow 0.2s;
        }
        .card-surface:hover {
            border-color: var(--border-glow);
            transform: translateY(-2px);
            box-shadow: 0 8px 32px rgba(0,0,0,0.5), 0 0 0 1px rgba(124,58,237,0.15);
        }

        /* Primary button */
        .btn-primary {
            background: linear-gradient(135deg, #7c3aed, #5b21b6);
            color: #fff;
            border: none;
            border-radius: 10px;
            font-family: 'Outfit', sans-serif;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
            position: relative;
            overflow: hidden;
        }
        .btn-primary::after {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, rgba(255,255,255,0.1), transparent);
            opacity: 0;
            transition: opacity 0.2s;
        }
        .btn-primary:hover::after { opacity: 1; }
        .btn-primary:hover { transform: translateY(-1px); box-shadow: 0 6px 20px rgba(124,58,237,0.45); }
        .btn-primary:active { transform: translateY(0); }

        /* Amber button */
        .btn-amber {
            background: linear-gradient(135deg, #f59e0b, #d97706);
            color: #0d0d18;
            border: none;
            border-radius: 10px;
            font-family: 'Outfit', sans-serif;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.2s;
        }
        .btn-amber:hover { transform: translateY(-1px); box-shadow: 0 6px 20px rgba(245,158,11,0.4); }

        /* Input focus ring */
        input:focus, select:focus, textarea:focus {
            outline: none;
            box-shadow: 0 0 0 2px rgba(124,58,237,0.5);
        }

        /* Page content above noise */
        #app > * { position: relative; z-index: 1; }

        /* Section heading */
        .section-title {
            font-family: 'Outfit', sans-serif;
            font-weight: 700;
            font-size: 1.5rem;
            color: #f1f5f9;
        }

        /* Badge */
        .badge-hot {
            background: linear-gradient(135deg, #ef4444, #dc2626);
            color: #fff;
            font-family: 'Outfit', sans-serif;
            font-size: 0.625rem;
            font-weight: 700;
            letter-spacing: 0.05em;
            padding: 2px 6px;
            border-radius: 4px;
            text-transform: uppercase;
        }
        .badge-top {
            background: linear-gradient(135deg, #f59e0b, #d97706);
            color: #0d0d18;
            font-family: 'Outfit', sans-serif;
            font-size: 0.625rem;
            font-weight: 700;
            letter-spacing: 0.05em;
            padding: 2px 6px;
            border-radius: 4px;
            text-transform: uppercase;
        }
        .badge-new {
            background: linear-gradient(135deg, #10b981, #059669);
            color: #fff;
            font-family: 'Outfit', sans-serif;
            font-size: 0.625rem;
            font-weight: 700;
            letter-spacing: 0.05em;
            padding: 2px 6px;
            border-radius: 4px;
            text-transform: uppercase;
        }

        /* Price color */
        .price-tag {
            font-family: 'Outfit', sans-serif;
            font-weight: 700;
            color: var(--brand-amber-light);
        }

        /* Star rating */
        .stars { color: #f59e0b; }

        /* Divider */
        .divider {
            height: 1px;
            background: var(--border-subtle);
        }

        /* Trust badge strip */
        .trust-strip {
            background: linear-gradient(90deg, rgba(124,58,237,0.08), rgba(16,185,129,0.08));
            border-top: 1px solid var(--border-subtle);
            border-bottom: 1px solid var(--border-subtle);
        }

        /* Animations */
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(16px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        @keyframes shimmer {
            0%   { background-position: -200% center; }
            100% { background-position: 200% center; }
        }
        @keyframes pulse-glow {
            0%, 100% { box-shadow: 0 0 10px rgba(124,58,237,0.3); }
            50%       { box-shadow: 0 0 25px rgba(124,58,237,0.6); }
        }
        .animate-fade-up { animation: fadeInUp 0.5s ease forwards; }
        .animate-shimmer {
            background: linear-gradient(90deg, transparent 0%, rgba(255,255,255,0.08) 50%, transparent 100%);
            background-size: 200% 100%;
            animation: shimmer 2s infinite;
        }

        /* Mobile nav active */
        .mobile-nav-open { transform: translateX(0) !important; }
    </style>

    @stack('styles')
</head>

<body class="antialiased">

{{-- Noise overlay is via CSS --}}
<div id="app" class="flex flex-col min-h-screen">

    {{-- ===== NAVBAR ===== --}}
    @include('components.navbar')

    {{-- ===== MAIN CONTENT ===== --}}
    <main class="flex-1 relative z-10">
        @if (session('success'))
            <div class="max-w-7xl mx-auto px-4 pt-4">
                <div class="flex items-center gap-3 bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 rounded-xl px-4 py-3 text-sm font-medium">
                    <svg class="w-4 h-4 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    {{ session('success') }}
                </div>
            </div>
        @endif

        @if (session('error'))
            <div class="max-w-7xl mx-auto px-4 pt-4">
                <div class="flex items-center gap-3 bg-red-500/10 border border-red-500/30 text-red-400 rounded-xl px-4 py-3 text-sm font-medium">
                    <svg class="w-4 h-4 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                    </svg>
                    {{ session('error') }}
                </div>
            </div>
        @endif

        @yield('content')
    </main>

    {{-- ===== FOOTER ===== --}}
    @include('components.footer')

</div>

{{-- Mobile nav overlay --}}
<div id="mobile-overlay" class="fixed inset-0 bg-black/70 backdrop-blur-sm z-40 hidden" onclick="closeMobileMenu()"></div>

<script>
    // Mobile menu toggle
    function openMobileMenu() {
        document.getElementById('mobile-menu').classList.add('mobile-nav-open');
        document.getElementById('mobile-overlay').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }
    function closeMobileMenu() {
        document.getElementById('mobile-menu').classList.remove('mobile-nav-open');
        document.getElementById('mobile-overlay').classList.add('hidden');
        document.body.style.overflow = '';
    }

    // User dropdown toggle
    function toggleUserDropdown() {
        const dd = document.getElementById('user-dropdown');
        dd.classList.toggle('hidden');
    }
    document.addEventListener('click', function(e) {
        const btn = document.getElementById('user-menu-btn');
        const dd  = document.getElementById('user-dropdown');
        if (btn && dd && !btn.contains(e.target) && !dd.contains(e.target)) {
            dd.classList.add('hidden');
        }
    });

    // Cart dropdown toggle
    function toggleCartDropdown() {
        const dd = document.getElementById('cart-dropdown');
        if (dd) dd.classList.toggle('hidden');
    }

    // Category dropdown in navbar
    function toggleCategoryDropdown() {
        const dd = document.getElementById('cat-dropdown');
        if (dd) dd.classList.toggle('hidden');
    }
    document.addEventListener('click', function(e) {
        const btn = document.getElementById('cat-dropdown-btn');
        const dd  = document.getElementById('cat-dropdown');
        if (btn && dd && !btn.contains(e.target) && !dd.contains(e.target)) {
            dd.classList.add('hidden');
        }
    });
</script>

@stack('scripts')
</body>
</html>
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', config('app.name', 'Lapak Gaming')) | Marketplace Gaming Terpercaya</title>
    <meta name="description" content="@yield('meta_description', 'Marketplace gaming & topup terpercaya. Beli item game, topup diamond, jual beli akun game dengan harga terbaik.')">

    {{-- Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Oxanium:wght@400;500;600;700;800&family=Nunito:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    {{-- Favicon --}}
    <link rel="icon" type="image/x-icon" href="{{ asset('images/favicon.ico') }}">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @stack('styles')
</head>
<body class="antialiased bg-[var(--bg-base)] text-[var(--text-primary)] font-['Nunito']" x-data="{ mobileMenuOpen: false }">

    {{-- Background ambient glow --}}
    <div class="fixed inset-0 pointer-events-none overflow-hidden" aria-hidden="true">
        <div class="absolute -top-40 -left-40 w-[600px] h-[600px] bg-violet-900/10 rounded-full blur-[120px]"></div>
        <div class="absolute top-1/2 -right-40 w-[400px] h-[400px] bg-cyan-900/8 rounded-full blur-[100px]"></div>
        <div class="absolute bottom-0 left-1/2 w-[500px] h-[300px] bg-violet-900/8 rounded-full blur-[120px] -translate-x-1/2"></div>
    </div>

    {{-- Navbar --}}
    @include('components.navbar')

    {{-- Mobile Drawer --}}
    <div class="drawer-overlay lg:hidden" id="drawerOverlay" @click="mobileMenuOpen = false" :class="{ 'active': mobileMenuOpen }"></div>
    @include('components.mobile-drawer')

    {{-- Flash Messages --}}
    @if(session('success') || session('error') || session('info'))
    <div
        x-data="{ show: true }"
        x-show="show"
        x-init="setTimeout(() => show = false, 4000)"
        x-transition:leave="transition ease-in duration-300"
        x-transition:leave-start="opacity-100 translate-y-0"
        x-transition:leave-end="opacity-0 -translate-y-2"
        class="fixed top-20 right-4 z-80 max-w-sm"
    >
        @if(session('success'))
        <div class="flex items-start gap-3 rounded-xl border border-green-500/30 bg-green-950/90 px-4 py-3 text-sm text-white backdrop-blur-xl shadow-xl">
            <svg class="w-5 h-5 text-green-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            <span>{{ session('success') }}</span>
            <button @click="show = false" class="ml-auto text-green-400 hover:text-white flex-shrink-0">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        @endif
        @if(session('error'))
        <div class="flex items-start gap-3 rounded-xl border border-red-500/30 bg-red-950/90 px-4 py-3 text-sm text-white backdrop-blur-xl shadow-xl">
            <svg class="w-5 h-5 text-red-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            <span>{{ session('error') }}</span>
            <button @click="show = false" class="ml-auto text-red-400 hover:text-white flex-shrink-0">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        @endif
    </div>
    @endif

    {{-- Toast Container --}}
    <div id="toast-container" class="fixed top-20 right-4 z-80 flex flex-col gap-2 w-80 pointer-events-none" aria-live="polite"></div>

    {{-- Main Content --}}
    <main class="relative z-10 min-h-screen pb-28 lg:pb-0">
        @yield('content')
    </main>

    {{-- Footer --}}
    @include('components.footer')

    {{-- Bottom Navigation (Mobile) --}}
    @include('components.bottom-nav')

    @stack('scripts')
</body>
</html>
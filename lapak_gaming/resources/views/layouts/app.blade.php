<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Lapak Geming') — Marketplace Game Terpercaya</title>
    <meta name="description" content="@yield('meta_description', 'Beli item game, top up, akun, voucher dengan aman dan murah di Lapak Geming')">

    @if (file_exists(public_path('build/manifest.json')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        <link rel="stylesheet" href="{{ asset('css/fallback.css') }}">
    @endif
</head>
<body class="min-h-full bg-gray-950 text-gray-100 font-sans">

    {{-- NAVBAR --}}
    @include('components.navbar')

    {{-- FLASH MESSAGES --}}
    @if(session('success'))
        <div class="fixed top-20 right-4 z-50 bg-green-600 text-white px-5 py-3 rounded-lg shadow-lg text-sm" x-data="{show:true}" x-show="show" x-init="setTimeout(()=>show=false,4000)">
            ✓ {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="fixed top-20 right-4 z-50 bg-red-600 text-white px-5 py-3 rounded-lg shadow-lg text-sm" x-data="{show:true}" x-show="show" x-init="setTimeout(()=>show=false,4000)">
            ✗ {{ session('error') }}
        </div>
    @endif

    {{-- MAIN CONTENT --}}
    <main>
        @yield('content')
    </main>

    {{-- FOOTER --}}
    @include('components.footer')

    @stack('scripts')
</body>
</html>
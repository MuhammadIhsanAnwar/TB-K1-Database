<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Auth') | {{ config('app.name', 'Lapak Gaming') }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Oxanium:wght@400;500;600;700;800&family=Nunito:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="antialiased bg-[var(--bg-base)] text-[var(--text-primary)] font-['Nunito'] min-h-screen">

    {{-- Ambient BG --}}
    <div class="fixed inset-0 pointer-events-none" aria-hidden="true">
        <div class="absolute -top-20 left-1/2 -translate-x-1/2 w-[800px] h-[400px] bg-violet-900/15 rounded-full blur-[120px]"></div>
        <div class="absolute bottom-0 left-0 w-[400px] h-[300px] bg-cyan-900/10 rounded-full blur-[100px]"></div>
        {{-- Grid pattern --}}
        <div class="absolute inset-0 opacity-[0.025]" style="background-image: linear-gradient(var(--border-default) 1px, transparent 1px), linear-gradient(90deg, var(--border-default) 1px, transparent 1px); background-size: 40px 40px;"></div>
    </div>

    {{-- Logo bar --}}
    <div class="relative z-10 flex items-center justify-center py-8">
        <a href="{{ route('marketplace.home') }}" class="flex items-center gap-2 group" aria-label="Lapak Gaming">
            <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-violet-600 to-violet-800 flex items-center justify-center shadow-lg shadow-violet-900/50 group-hover:shadow-violet-700/50 transition-shadow">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <span class="text-xl font-bold font-['Oxanium'] text-white tracking-tight">Lapak<span class="text-violet-400">Gaming</span></span>
        </a>
    </div>

    <main class="relative z-10">
        @yield('content')
    </main>

    <div class="relative z-10 py-8 text-center text-xs text-[var(--text-muted)]">
        &copy; {{ date('Y') }} LapakGaming. All rights reserved.
    </div>
</body>
</html>
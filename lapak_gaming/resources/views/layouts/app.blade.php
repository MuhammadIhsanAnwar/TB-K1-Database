<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Lapak Gaming') }} - @yield('title')</title>

    {{-- Tailwind CDN for shared hosting without Node/Vite runtime --}}
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        :root {
            --brand-600: #4f46e5;
            --brand-700: #4338ca;
            --surface: #f8fafc;
            --text: #0f172a;
            --muted: #475569;
        }

        body {
            background-color: var(--surface);
            color: var(--text);
        }

        .site-card {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            color: var(--text);
        }

        .site-muted {
            color: var(--muted);
        }

        .site-link {
            color: var(--brand-600);
        }

        .site-link:hover {
            color: var(--brand-700);
        }

        .game-loader {
            position: fixed;
            inset: 0;
            z-index: 9999;
            display: grid;
            place-items: center;
            background:
                radial-gradient(circle at 20% 20%, rgba(79, 70, 229, 0.35), transparent 35%),
                radial-gradient(circle at 80% 70%, rgba(6, 182, 212, 0.28), transparent 30%),
                #020617;
            transition: opacity 0.45s ease, visibility 0.45s ease;
        }

        .game-loader.hidden {
            opacity: 0;
            visibility: hidden;
            pointer-events: none;
        }

        .game-loader-card {
            width: min(90vw, 430px);
            border: 1px solid rgba(99, 102, 241, 0.55);
            border-radius: 16px;
            padding: 22px;
            background: rgba(15, 23, 42, 0.88);
            box-shadow: 0 0 28px rgba(99, 102, 241, 0.35);
            color: #e2e8f0;
            backdrop-filter: blur(4px);
        }

        .game-loader-title {
            display: flex;
            align-items: center;
            justify-content: space-between;
            font-weight: 700;
            letter-spacing: 0.06em;
            margin-bottom: 14px;
            color: #c7d2fe;
        }

        .game-loader-line {
            width: 56px;
            height: 2px;
            background: linear-gradient(90deg, transparent, #818cf8, transparent);
            animation: pulseLine 1.3s infinite;
        }

        .game-loader-bar {
            position: relative;
            height: 10px;
            border-radius: 999px;
            background: rgba(148, 163, 184, 0.22);
            overflow: hidden;
            margin-top: 8px;
        }

        .game-loader-progress {
            width: 35%;
            height: 100%;
            border-radius: inherit;
            background: linear-gradient(90deg, #22d3ee, #6366f1, #a78bfa);
            animation: runBar 1.2s ease-in-out infinite;
        }

        .game-loader-scan {
            height: 1px;
            width: 100%;
            margin-top: 16px;
            background: linear-gradient(90deg, transparent, #67e8f9, transparent);
            animation: scan 1.1s linear infinite;
        }

        .game-loader-text {
            margin-top: 10px;
            font-size: 13px;
            color: #94a3b8;
        }

        @keyframes runBar {
            0% { transform: translateX(-110%); }
            100% { transform: translateX(320%); }
        }

        @keyframes scan {
            0% { transform: translateX(-100%); opacity: 0.3; }
            50% { opacity: 1; }
            100% { transform: translateX(100%); opacity: 0.3; }
        }

        @keyframes pulseLine {
            0%, 100% { opacity: 0.4; }
            50% { opacity: 1; }
        }

        @media (prefers-reduced-motion: reduce) {
            .game-loader-line,
            .game-loader-progress,
            .game-loader-scan {
                animation: none;
            }
        }
    </style>
    
</head>
<body class="bg-slate-50 text-slate-900">
    <div id="game-loader" class="game-loader" aria-live="polite" aria-label="Loading">
        <div class="game-loader-card">
            <div class="game-loader-title">
                <span>LOADING ARENA</span>
                <div class="game-loader-line"></div>
            </div>
            <div class="game-loader-bar">
                <div class="game-loader-progress"></div>
            </div>
            <div class="game-loader-scan"></div>
            <p class="game-loader-text">Menyiapkan lobby, item, dan leaderboard...</p>
        </div>
    </div>

    {{-- Navigation --}}
    <nav class="sticky top-0 z-50 bg-white border-b border-slate-200 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                {{-- Logo --}}
                <div class="flex items-center gap-2">
                    <a href="{{ route('home') }}" class="text-2xl font-bold text-indigo-600">🎮 Lapak Gaming</a>
                </div>

                {{-- Search Bar --}}
                <div class="hidden md:flex flex-1 mx-8">
                    <form id="search-form" class="w-full max-w-md">
                        <div class="relative">
                            <input type="text" id="search-input" placeholder="Cari game item, voucher, akun..."
                                class="w-full px-4 py-2 rounded-lg border border-slate-300 bg-white text-slate-900 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                            <button type="submit" class="absolute right-3 top-2.5">
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                            </button>
                        </div>
                        <ul id="suggestions" class="absolute top-12 w-full bg-white border border-slate-200 rounded-lg shadow-lg hidden max-h-96 overflow-y-auto z-10"></ul>
                    </form>
                </div>

                {{-- Right Menu --}}
                <div class="flex items-center gap-4">
                    @auth
                        {{-- Notifications --}}
                        <a href="{{ route('notifications.index') }}" id="notification-btn" class="relative p-2 hover:bg-slate-100 rounded-lg inline-flex">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                            </svg>
                            <span id="notif-badge" class="absolute top-1 right-1 bg-red-500 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center hidden">0</span>
                        </a>

                        {{-- Chat --}}
                        <a href="{{ route('chat.index') }}" class="relative p-2 hover:bg-slate-100 rounded-lg">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                            </svg>
                            <span id="chat-badge" class="absolute top-1 right-1 bg-blue-500 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center hidden">0</span>
                        </a>

                        {{-- User Menu --}}
                        <div class="relative" id="user-menu-wrapper">
                            <button id="user-menu-button" class="flex items-center gap-2 p-2 hover:bg-slate-100 rounded-lg">
                                <img src="{{ auth()->user()->avatar }}" alt="Avatar" class="w-8 h-8 rounded-full">
                                <span class="hidden sm:inline text-sm font-medium">{{ auth()->user()->name }}</span>
                            </button>
                            <div id="user-menu-dropdown" class="absolute right-0 mt-2 w-48 bg-white border border-slate-200 rounded-lg shadow-lg py-2 z-20 hidden">
                                <a href="{{ route('profile.edit') }}" class="block px-4 py-2 hover:bg-slate-100">Profile</a>
                                <a href="{{ route('settings') }}" class="block px-4 py-2 hover:bg-slate-100">Settings</a>
                                @if(auth()->user()->isSeller())
                                    <a href="{{ route('seller.dashboard') }}" class="block px-4 py-2 hover:bg-slate-100">Seller Dashboard</a>
                                @endif
                                @if(auth()->user()->isAdmin())
                                    <a href="{{ route('admin.dashboard') }}" class="block px-4 py-2 hover:bg-slate-100">Admin Panel</a>
                                @endif
                                <hr class="my-1 border-slate-200">
                                <form method="POST" action="{{ route('logout') }}" class="block">
                                    @csrf
                                    <button type="submit" class="w-full text-left px-4 py-2 hover:bg-slate-100 text-rose-600">Logout</button>
                                </form>
                            </div>
                        </div>
                    @else
                        <a href="{{ route('login') }}" class="px-4 py-2 text-indigo-600 hover:text-indigo-700 font-medium">Login</a>
                        <a href="{{ route('register') }}" class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">Register</a>
                    @endauth

                </div>
            </div>
        </div>
    </nav>

    {{-- Flash Messages --}}
    @if($message = session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
            {{ $message }}
        </div>
    @endif

    @if($message = session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
            {{ $message }}
        </div>
    @endif

    {{-- Main Content --}}
    <main class="min-h-screen">
        @yield('content')
    </main>

    {{-- Footer --}}
    <footer class="bg-gray-900 text-gray-300 mt-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div>
                    <h3 class="text-white font-bold mb-4">Lapak Gaming</h3>
                    <p class="text-sm">Platform marketplace digital terpercaya untuk game items, vouchers, dan akun digital.</p>
                </div>
                <div>
                    <h4 class="text-white font-semibold mb-4">Marketplace</h4>
                    <ul class="text-sm space-y-2">
                        <li><a href="#" class="hover:text-white">Game Items</a></li>
                        <li><a href="#" class="hover:text-white">Vouchers</a></li>
                        <li><a href="#" class="hover:text-white">Accounts</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-white font-semibold mb-4">Seller</h4>
                    <ul class="text-sm space-y-2">
                        <li><a href="{{ route('seller.setup') }}" class="hover:text-white">Mulai Berjualan</a></li>
                        <li><a href="#" class="hover:text-white">Seller Hub</a></li>
                        <li><a href="#" class="hover:text-white">Panduan</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-white font-semibold mb-4">Kontak</h4>
                    <ul class="text-sm space-y-2">
                        <li>Email: support@lapakgaming.local</li>
                        <li>WhatsApp: +62 82x xxxx xxxx</li>
                    </ul>
                </div>
            </div>
            <div class="border-t border-gray-800 mt-8 pt-8 text-center text-sm">
                <p>&copy; 2024 Lapak Gaming. All rights reserved. | <a href="#" class="hover:text-white">Privacy Policy</a> | <a href="#" class="hover:text-white">Terms of Service</a></p>
            </div>
        </div>
    </footer>

    {{-- JavaScript --}}
    <script>
        const gameLoader = document.getElementById('game-loader');
        window.addEventListener('load', () => {
            if (!gameLoader) {
                return;
            }

            gameLoader.classList.add('hidden');
            setTimeout(() => {
                gameLoader.remove();
            }, 500);
        });

        // Theme Toggle
        // User dropdown menu without Alpine.js
        const userMenuButton = document.getElementById('user-menu-button');
        const userMenuDropdown = document.getElementById('user-menu-dropdown');
        const userMenuWrapper = document.getElementById('user-menu-wrapper');

        if (userMenuButton && userMenuDropdown && userMenuWrapper) {
            userMenuButton.addEventListener('click', (event) => {
                event.stopPropagation();
                userMenuDropdown.classList.toggle('hidden');
            });

            document.addEventListener('click', (event) => {
                if (!userMenuWrapper.contains(event.target)) {
                    userMenuDropdown.classList.add('hidden');
                }
            });
        }

        // Search functionality
        const searchInput = document.getElementById('search-input');
        const suggestionsList = document.getElementById('suggestions');

        if(searchInput) {
            searchInput.addEventListener('input', async (e) => {
                const query = e.target.value.trim();
                if(query.length < 2) {
                    suggestionsList.classList.add('hidden');
                    return;
                }

                try {
                    const response = await fetch(`/api/search/suggestions?q=${encodeURIComponent(query)}`);
                    const data = await response.json();
                    
                    suggestionsList.innerHTML = data.map(item => 
                        `<li><a href="/product/${item.slug}" class="block px-4 py-2 hover:bg-gray-100">${item.name}</a></li>`
                    ).join('');
                    
                    suggestionsList.classList.remove('hidden');
                } catch(error) {
                    console.error('Search error:', error);
                }
            });
        }
        
        // Load unread notifications - Only runs if user is authenticated
        const chatBadge = document.getElementById('chat-badge');
        if (chatBadge) {
            setInterval(async () => {
                try {
                    const response = await fetch('/api/chat/unread/count');
                    const data = await response.json();
                    if(data.unread_count > 0) {
                        chatBadge.textContent = data.unread_count;
                        chatBadge.classList.remove('hidden');
                    } else {
                        chatBadge.classList.add('hidden');
                    }
                } catch(error) {
                    console.error('Notification error:', error);
                }
            }, 30000);
        }
    </script>

    @yield('scripts')
</body>
</html>

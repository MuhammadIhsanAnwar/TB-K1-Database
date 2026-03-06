<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Lapak Gaming') }} - @yield('title')</title>

    {{-- Tailwind CDN for shared hosting without Node/Vite runtime --}}
    <script src="https://cdn.tailwindcss.com"></script>
    
</head>
<body class="bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-gray-100">
    {{-- Navigation --}}
    <nav class="sticky top-0 z-50 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 shadow-sm">
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
                                class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                            <button type="submit" class="absolute right-3 top-2.5">
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                            </button>
                        </div>
                        <ul id="suggestions" class="absolute top-12 w-full bg-white dark:bg-gray-700 border rounded-lg shadow-lg hidden max-h-96 overflow-y-auto z-10"></ul>
                    </form>
                </div>

                {{-- Right Menu --}}
                <div class="flex items-center gap-4">
                    @auth
                        {{-- Notifications --}}
                        <a href="{{ route('notifications.index') }}" id="notification-btn" class="relative p-2 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg inline-flex">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                            </svg>
                            <span id="notif-badge" class="absolute top-1 right-1 bg-red-500 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center hidden">0</span>
                        </a>

                        {{-- Chat --}}
                        <a href="{{ route('chat.index') }}" class="relative p-2 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                            </svg>
                            <span id="chat-badge" class="absolute top-1 right-1 bg-blue-500 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center hidden">0</span>
                        </a>

                        {{-- User Menu --}}
                        <div class="relative" id="user-menu-wrapper">
                            <button id="user-menu-button" class="flex items-center gap-2 p-2 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg">
                                <img src="{{ auth()->user()->avatar }}" alt="Avatar" class="w-8 h-8 rounded-full">
                                <span class="hidden sm:inline text-sm font-medium">{{ auth()->user()->name }}</span>
                            </button>
                            <div id="user-menu-dropdown" class="absolute right-0 mt-2 w-48 bg-white dark:bg-gray-700 rounded-lg shadow-lg py-2 z-20 hidden">
                                <a href="{{ route('profile.edit') }}" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600">Profile</a>
                                <a href="{{ route('settings') }}" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600">Settings</a>
                                @if(auth()->user()->isSeller())
                                    <a href="{{ route('seller.dashboard') }}" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600">Seller Dashboard</a>
                                @endif
                                @if(auth()->user()->isAdmin())
                                    <a href="{{ route('admin.dashboard') }}" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600">Admin Panel</a>
                                @endif
                                <hr class="my-1 dark:border-gray-600">
                                <form method="POST" action="{{ route('logout') }}" class="block">
                                    @csrf
                                    <button type="submit" class="w-full text-left px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 text-red-600">Logout</button>
                                </form>
                            </div>
                        </div>
                    @else
                        <a href="{{ route('login') }}" class="px-4 py-2 text-indigo-600 hover:text-indigo-700 font-medium">Login</a>
                        <a href="{{ route('register') }}" class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">Register</a>
                    @endauth

                    {{-- Dark Mode Toggle --}}
                    <button id="theme-toggle" class="p-2 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 118.646 3.646 9 9 0 0120.354 15.354z"></path>
                        </svg>
                    </button>
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
        // Theme Toggle
        const themeToggleBtn = document.getElementById('theme-toggle');
        if (themeToggleBtn) {
            themeToggleBtn.addEventListener('click', () => {
                document.documentElement.classList.toggle('dark');
                localStorage.theme = document.documentElement.classList.contains('dark') ? 'dark' : 'light';
            });
        }

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

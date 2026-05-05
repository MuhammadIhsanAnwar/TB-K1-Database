{{-- ============================================================
     resources/views/components/navbar.blade.php
     Dark gaming marketplace navbar — Logo | Search | Cart + User
     ============================================================ --}}

<header class="sticky top-0 z-50" style="background: rgba(13,13,24,0.92); backdrop-filter: blur(18px); -webkit-backdrop-filter: blur(18px); border-bottom: 1px solid rgba(255,255,255,0.06);">

    {{-- ── TOP UTILITY BAR (trust strip) ─────────────────────────────── --}}
    <div class="hidden md:block trust-strip">
        <div class="max-w-7xl mx-auto px-4 flex items-center justify-between h-8 text-xs text-gray-400">
            <div class="flex items-center gap-6">
                <span class="flex items-center gap-1.5">
                    <svg class="w-3 h-3 text-emerald-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 1l2.39 4.845L18 6.635l-4 3.896.944 5.507L10 13.5l-4.944 2.538L6 10.53 2 6.635l5.61-.79L10 1z" clip-rule="evenodd"/></svg>
                    Safe Transactions
                </span>
                <span class="flex items-center gap-1.5">
                    <svg class="w-3 h-3 text-emerald-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M2.166 4.999A11.954 11.954 0 0010 1.944 11.954 11.954 0 0017.834 5c.11.65.166 1.32.166 2.001 0 5.225-3.34 9.67-8 11.317C5.34 16.67 2 12.225 2 7c0-.682.057-1.35.166-2.001zm11.541 3.708a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                    Buyer Protection
                </span>
                <span class="flex items-center gap-1.5">
                    <svg class="w-3 h-3 text-emerald-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/></svg>
                    24/7 Support
                </span>
            </div>
            <div class="flex items-center gap-4">
                <a href="{{ route('home') ?? '#' }}" class="hover:text-violet-400 transition-colors">Sell on {{ config('app.name') }}</a>
                <span class="w-px h-3 bg-gray-700"></span>
                <a href="#" class="hover:text-violet-400 transition-colors">Help Center</a>
            </div>
        </div>
    </div>

    {{-- ── MAIN NAV ROW ────────────────────────────────────────────────── --}}
    <div class="max-w-7xl mx-auto px-4">
        <div class="flex items-center gap-4 h-16">

            {{-- ── HAMBURGER (mobile only) ──────────────────────────────── --}}
            <button
                onclick="openMobileMenu()"
                class="md:hidden flex-shrink-0 p-2 rounded-lg text-gray-400 hover:text-white hover:bg-white/5 transition-colors"
                aria-label="Open menu"
            >
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>

            {{-- ── LOGO ─────────────────────────────────────────────────── --}}
            <a href="{{ route('home') ?? '/' }}" class="flex-shrink-0 flex items-center gap-2 group">
                <div class="w-8 h-8 rounded-lg flex items-center justify-center" style="background: linear-gradient(135deg, #7c3aed, #5b21b6);">
                    <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M21 6H3a1 1 0 00-1 1v10a1 1 0 001 1h18a1 1 0 001-1V7a1 1 0 00-1-1zm-1 10H4V8h16v8zM8 11H6v2h2v-2zm4-2H10v6h2V9zm4 3h-2v3h2v-3z"/>
                    </svg>
                </div>
                <span class="font-display text-xl font-800 tracking-tight" style="font-family:'Outfit',sans-serif;font-weight:800;">
                    <span class="text-white">Game</span><span style="color:#7c3aed;">Zone</span>
                </span>
            </a>

            {{-- ── SEARCH BAR (desktop) ─────────────────────────────────── --}}
            <div class="hidden md:flex flex-1 items-center max-w-2xl relative" style="margin: 0 16px;">
                <form action="{{ route('products.search') ?? '#' }}" method="GET" class="w-full flex">

                    {{-- Category dropdown --}}
                    <div class="relative flex-shrink-0">
                        <button
                            id="cat-dropdown-btn"
                            type="button"
                            onclick="toggleCategoryDropdown()"
                            class="flex items-center gap-1.5 h-11 px-3 text-sm font-medium text-gray-300 hover:text-white transition-colors rounded-l-xl border-r"
                            style="background: rgba(255,255,255,0.06); border-color: rgba(255,255,255,0.08);"
                        >
                            <svg class="w-4 h-4 text-violet-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 10h16M4 14h16M4 18h16"/>
                            </svg>
                            <span class="hidden lg:inline text-xs">All</span>
                            <svg class="w-3 h-3 opacity-50" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>

                        {{-- Category dropdown panel --}}
                        <div
                            id="cat-dropdown"
                            class="hidden absolute top-full left-0 mt-1 w-52 rounded-xl shadow-2xl z-50 overflow-hidden"
                            style="background: #1a1a2e; border: 1px solid rgba(255,255,255,0.08);"
                        >
                            @php $navCategories = $categories ?? []; @endphp
                            @if($navCategories->count() ?? count($navCategories ?? []))
                                @foreach($navCategories as $cat)
                                    <a
                                        href="{{ route('categories.show', $cat->slug) }}"
                                        class="flex items-center gap-2.5 px-4 py-2.5 text-sm text-gray-300 hover:bg-violet-600/20 hover:text-white transition-colors"
                                    >
                                        <span class="w-1.5 h-1.5 rounded-full bg-violet-500 flex-shrink-0"></span>
                                        {{ $cat->name }}
                                    </a>
                                @endforeach
                            @else
                                <a href="{{ route('home') ?? '#' }}" class="flex items-center gap-2.5 px-4 py-2.5 text-sm text-gray-300 hover:bg-violet-600/20 hover:text-white transition-colors">
                                    <span class="w-1.5 h-1.5 rounded-full bg-violet-500"></span> Game Keys
                                </a>
                                <a href="{{ route('home') ?? '#' }}" class="flex items-center gap-2.5 px-4 py-2.5 text-sm text-gray-300 hover:bg-violet-600/20 hover:text-white transition-colors">
                                    <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span> Game Items
                                </a>
                                <a href="{{ route('home') ?? '#' }}" class="flex items-center gap-2.5 px-4 py-2.5 text-sm text-gray-300 hover:bg-violet-600/20 hover:text-white transition-colors">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Accounts
                                </a>
                                <a href="{{ route('home') ?? '#' }}" class="flex items-center gap-2.5 px-4 py-2.5 text-sm text-gray-300 hover:bg-violet-600/20 hover:text-white transition-colors">
                                    <span class="w-1.5 h-1.5 rounded-full bg-pink-500"></span> Gift Cards
                                </a>
                            @endif
                        </div>
                    </div>

                    {{-- Search input --}}
                    <input
                        type="text"
                        name="q"
                        value="{{ request('q') }}"
                        placeholder="Search games, items, accounts..."
                        class="flex-1 h-11 px-4 text-sm text-gray-100 placeholder-gray-500 outline-none transition-all"
                        style="background: rgba(255,255,255,0.06); border: 1px solid rgba(255,255,255,0.08); border-left: none; border-right: none;"
                        autocomplete="off"
                    >

                    {{-- Search button --}}
                    <button
                        type="submit"
                        class="flex items-center justify-center w-12 h-11 rounded-r-xl transition-all hover:opacity-90"
                        style="background: linear-gradient(135deg, #7c3aed, #5b21b6);"
                    >
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35M17 11A6 6 0 105 11a6 6 0 0012 0z"/>
                        </svg>
                    </button>
                </form>
            </div>

            {{-- ── RIGHT ACTIONS ─────────────────────────────────────────── --}}
            <div class="flex items-center gap-1 ml-auto md:ml-0">

                {{-- Mobile search icon --}}
                <a href="{{ route('products.search') ?? '#' }}" class="md:hidden p-2 rounded-lg text-gray-400 hover:text-white hover:bg-white/5 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35M17 11A6 6 0 105 11a6 6 0 0012 0z"/>
                    </svg>
                </a>

                {{-- Wishlist --}}
                <a href="{{ Route::has('wishlist.index') ? route('wishlist.index') : '#' }}" class="hidden sm:flex p-2 rounded-lg text-gray-400 hover:text-white hover:bg-white/5 transition-colors relative" title="Wishlist">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                    </svg>
                </a>

                {{-- Cart --}}
                <div class="relative">
                    <button onclick="toggleCartDropdown()" class="flex p-2 rounded-lg text-gray-400 hover:text-white hover:bg-white/5 transition-colors relative" title="Cart">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                        @php $cartCount = auth()->check() ? auth()->user()->cart()->count() : 0; @endphp
                        @if($cartCount > 0)
                            <span class="absolute -top-0.5 -right-0.5 w-4 h-4 rounded-full text-white text-xs flex items-center justify-center font-bold" style="background: #ef4444; font-family:'Outfit',sans-serif;">
                                {{ $cartCount > 9 ? '9+' : $cartCount }}
                            </span>
                        @endif
                    </button>

                    {{-- Cart mini-dropdown --}}
                    <div id="cart-dropdown" class="hidden absolute right-0 top-full mt-2 w-72 rounded-xl shadow-2xl z-50 overflow-hidden"
                         style="background: #1a1a2e; border: 1px solid rgba(255,255,255,0.08);">
                        <div class="px-4 py-3 flex items-center justify-between border-b" style="border-color: rgba(255,255,255,0.06);">
                            <span class="text-sm font-semibold text-white" style="font-family:'Outfit',sans-serif;">My Cart</span>
                            <span class="text-xs text-gray-500">{{ $cartCount }} item(s)</span>
                        </div>
                        @if($cartCount === 0)
                            <div class="px-4 py-8 text-center">
                                <div class="w-12 h-12 rounded-full mx-auto mb-3 flex items-center justify-center" style="background: rgba(124,58,237,0.1);">
                                    <svg class="w-6 h-6 text-violet-500" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                                    </svg>
                                </div>
                                <p class="text-sm text-gray-400">Your cart is empty</p>
                                <a href="{{ route('home') ?? '#' }}" class="mt-3 inline-block text-xs text-violet-400 hover:text-violet-300 transition-colors">Browse Products →</a>
                            </div>
                        @else
                            <div class="p-3">
                                <a href="{{ route('cart.index') ?? '#' }}" class="btn-primary block text-center text-sm py-2.5 rounded-lg">View Cart</a>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Separator --}}
                <span class="hidden md:block w-px h-6 mx-1" style="background: rgba(255,255,255,0.08);"></span>

                {{-- Auth: Guest --}}
                @guest
                    <a href="{{ route('login') }}" class="hidden sm:flex items-center gap-1.5 px-3 py-1.5 text-sm text-gray-300 hover:text-white transition-colors rounded-lg hover:bg-white/5">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
                        </svg>
                        Login
                    </a>
                    <a href="{{ route('register') }}" class="btn-primary hidden sm:flex items-center gap-1.5 px-4 py-1.5 text-sm">
                        Register
                    </a>
                @else
                    {{-- Auth: Logged in --}}
                    <div class="relative">
                        <button
                            id="user-menu-btn"
                            onclick="toggleUserDropdown()"
                            class="flex items-center gap-2 p-1.5 rounded-xl hover:bg-white/5 transition-colors"
                        >
                            {{-- Avatar --}}
                            <div class="w-7 h-7 rounded-lg flex items-center justify-center text-white text-xs font-bold flex-shrink-0"
                                 style="background: linear-gradient(135deg, #7c3aed, #5b21b6); font-family:'Outfit',sans-serif;">
                                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                            </div>
                            <span class="hidden lg:block text-sm text-gray-200 font-medium max-w-[80px] truncate">{{ Auth::user()->name }}</span>
                            <svg class="hidden lg:block w-3 h-3 text-gray-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>

                        {{-- User dropdown --}}
                        <div
                            id="user-dropdown"
                            class="hidden absolute right-0 top-full mt-2 w-52 rounded-xl shadow-2xl z-50 overflow-hidden"
                            style="background: #1a1a2e; border: 1px solid rgba(255,255,255,0.08);"
                        >
                            {{-- User info header --}}
                            <div class="px-4 py-3 border-b" style="border-color: rgba(255,255,255,0.06);">
                                <p class="text-sm font-semibold text-white truncate" style="font-family:'Outfit',sans-serif;">{{ Auth::user()->name }}</p>
                                <p class="text-xs text-gray-500 truncate mt-0.5">{{ Auth::user()->email }}</p>
                            </div>

                            <div class="py-1">
                                @if(Auth::user()->role === 'admin' || Auth::user()->is_admin)
                                    <a href="{{ route('admin.dashboard') ?? '#' }}" class="flex items-center gap-2.5 px-4 py-2 text-sm text-violet-400 hover:bg-violet-600/20 hover:text-violet-300 transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                                        Admin Dashboard
                                    </a>
                                @endif
                                <a href="{{ route('profile.show') ?? '#' }}" class="flex items-center gap-2.5 px-4 py-2 text-sm text-gray-300 hover:bg-white/5 hover:text-white transition-colors">
                                    <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                    My Profile
                                </a>
                                <a href="{{ route('orders.index') ?? '#' }}" class="flex items-center gap-2.5 px-4 py-2 text-sm text-gray-300 hover:bg-white/5 hover:text-white transition-colors">
                                    <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                                    My Orders
                                </a>
                                <a href="{{ Route::has('wishlist.index') ? route('wishlist.index') : '#' }}"
   class="flex items-center gap-2.5 px-4 py-2 text-sm text-gray-300 hover:bg-white/5 hover:text-white transition-colors">
    <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
    </svg>
    Wishlist
</a>
                            </div>

                            <div class="border-t py-1" style="border-color: rgba(255,255,255,0.06);">
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="w-full flex items-center gap-2.5 px-4 py-2 text-sm text-red-400 hover:bg-red-500/10 transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                                        Sign Out
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endguest
            </div>
        </div>
    </div>

    {{-- ── CATEGORY NAV BAR (desktop) ─────────────────────────────────── --}}
    <div class="hidden md:block border-t" style="border-color: rgba(255,255,255,0.05); background: rgba(10,10,20,0.6);">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex items-center gap-0 overflow-x-auto no-scrollbar">
                @php $navbarCategories = $categories ?? []; @endphp
                @if(($navbarCategories->count() ?? count($navbarCategories ?? [])) > 0)
                    @foreach($navbarCategories->take(8) as $cat)
                        <a
                            href="{{ route('categories.show', $cat->slug) }}"
                            class="flex-shrink-0 flex items-center gap-1.5 px-3 py-2.5 text-xs font-medium text-gray-400 hover:text-violet-400 hover:bg-violet-500/5 transition-all border-b-2 border-transparent hover:border-violet-500"
                        >
                            {{ $cat->name }}
                        </a>
                    @endforeach
                @else
                    @php
                        $defaultCats = [
                            ['name' => '🎮 Game Keys', 'href' => '#'],
                            ['name' => '⚔️ Game Items', 'href' => '#'],
                            ['name' => '💎 Top Up', 'href' => '#'],
                            ['name' => '🎴 Gift Cards', 'href' => '#'],
                            ['name' => '👤 Accounts', 'href' => '#'],
                            ['name' => '💰 Currency', 'href' => '#'],
                            ['name' => '🃏 Roblox', 'href' => '#'],
                            ['name' => '🌿 Growtopia', 'href' => '#'],
                        ];
                    @endphp
                    @foreach($defaultCats as $dc)
                        <a href="{{ $dc['href'] }}" class="flex-shrink-0 flex items-center gap-1.5 px-3 py-2.5 text-xs font-medium text-gray-400 hover:text-violet-400 hover:bg-violet-500/5 transition-all border-b-2 border-transparent hover:border-violet-500">
                            {{ $dc['name'] }}
                        </a>
                    @endforeach
                @endif
            </div>
        </div>
    </div>

</header>

{{-- ── MOBILE SIDE DRAWER ──────────────────────────────────────────────── --}}
<div
    id="mobile-menu"
    class="fixed top-0 left-0 h-full w-72 z-50 flex flex-col overflow-y-auto transition-transform duration-300"
    style="background: #0d0d18; border-right: 1px solid rgba(255,255,255,0.06); transform: translateX(-100%);"
>
    {{-- Drawer header --}}
    <div class="flex items-center justify-between px-4 py-4 border-b" style="border-color: rgba(255,255,255,0.06);">
        <span class="font-display text-lg font-800 text-white" style="font-family:'Outfit',sans-serif;">
            <span>Game</span><span style="color:#7c3aed;">Zone</span>
        </span>
        <button onclick="closeMobileMenu()" class="p-1.5 rounded-lg text-gray-400 hover:text-white hover:bg-white/5 transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>
    </div>

    {{-- Mobile search --}}
    <div class="px-4 py-3 border-b" style="border-color: rgba(255,255,255,0.06);">
        <form action="{{ route('products.search') ?? '#' }}" method="GET" class="flex gap-2">
            <input
                type="text"
                name="q"
                placeholder="Search..."
                class="flex-1 h-9 px-3 text-sm text-gray-100 placeholder-gray-500 rounded-lg outline-none"
                style="background: rgba(255,255,255,0.06); border: 1px solid rgba(255,255,255,0.08);"
            >
            <button type="submit" class="px-3 h-9 rounded-lg text-white text-sm" style="background: linear-gradient(135deg, #7c3aed, #5b21b6);">Go</button>
        </form>
    </div>

    {{-- Mobile auth --}}
    @guest
        <div class="px-4 py-3 flex gap-2">
            <a href="{{ route('login') }}" class="flex-1 text-center py-2 text-sm text-gray-200 rounded-lg border transition-colors hover:border-violet-500 hover:text-violet-400" style="border-color: rgba(255,255,255,0.1);">Login</a>
            <a href="{{ route('register') }}" class="flex-1 text-center py-2 text-sm text-white rounded-lg" style="background: linear-gradient(135deg, #7c3aed, #5b21b6);">Register</a>
        </div>
    @else
        <div class="px-4 py-3 flex items-center gap-3 border-b" style="border-color: rgba(255,255,255,0.06);">
            <div class="w-9 h-9 rounded-xl flex items-center justify-center text-white text-sm font-bold" style="background: linear-gradient(135deg, #7c3aed, #5b21b6); font-family:'Outfit',sans-serif;">
                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
            </div>
            <div>
                <p class="text-sm font-semibold text-white" style="font-family:'Outfit',sans-serif;">{{ Auth::user()->name }}</p>
                <p class="text-xs text-gray-500">{{ Auth::user()->email }}</p>
            </div>
        </div>
    @endguest

    {{-- Mobile nav links --}}
    <nav class="flex-1 px-2 py-3">
        <p class="px-2 text-xs font-semibold text-gray-600 uppercase tracking-widest mb-2" style="font-family:'Outfit',sans-serif;">Categories</p>
        @php $mobileCategories = $categories ?? []; @endphp
        @if(($mobileCategories->count() ?? count($mobileCategories ?? [])) > 0)
            @foreach($mobileCategories as $cat)
                <a href="{{ route('categories.show', $cat->slug) }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm text-gray-300 hover:bg-violet-600/10 hover:text-white transition-colors">
                    <span class="w-2 h-2 rounded-full bg-violet-500"></span>
                    {{ $cat->name }}
                </a>
            @endforeach
        @else
            <a href="#" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm text-gray-300 hover:bg-violet-600/10 hover:text-white transition-colors"><span class="w-2 h-2 rounded-full bg-violet-500"></span>Game Keys</a>
            <a href="#" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm text-gray-300 hover:bg-violet-600/10 hover:text-white transition-colors"><span class="w-2 h-2 rounded-full bg-amber-500"></span>Game Items</a>
            <a href="#" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm text-gray-300 hover:bg-violet-600/10 hover:text-white transition-colors"><span class="w-2 h-2 rounded-full bg-emerald-500"></span>Top Up</a>
            <a href="#" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm text-gray-300 hover:bg-violet-600/10 hover:text-white transition-colors"><span class="w-2 h-2 rounded-full bg-pink-500"></span>Gift Cards</a>
            <a href="#" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm text-gray-300 hover:bg-violet-600/10 hover:text-white transition-colors"><span class="w-2 h-2 rounded-full bg-blue-500"></span>Accounts</a>
        @endif

        @auth
            <div class="mt-4 border-t pt-3" style="border-color: rgba(255,255,255,0.06);">
                <p class="px-2 text-xs font-semibold text-gray-600 uppercase tracking-widest mb-2" style="font-family:'Outfit',sans-serif;">Account</p>
                <a href="{{ route('profile.show') ?? '#' }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm text-gray-300 hover:bg-white/5 hover:text-white transition-colors">
                    <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    My Profile
                </a>
                <a href="{{ route('orders.index') ?? '#' }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm text-gray-300 hover:bg-white/5 hover:text-white transition-colors">
                    <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                    My Orders
                </a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm text-red-400 hover:bg-red-500/10 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                        Sign Out
                    </button>
                </form>
            </div>
        @endauth
    </nav>
</div>
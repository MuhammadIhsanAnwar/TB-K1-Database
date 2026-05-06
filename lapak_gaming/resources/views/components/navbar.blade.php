{{--
  Component: components/navbar.blade.php
  Includes:
    - Announcement bar (dismissible)
    - Mobile sidebar drawer
    - Sticky top navbar (logo, nav links, search, dropdowns)
  Variables expected (from Auth facade & session, not passed in):
    - Auth::check(), Auth::user()
--}}

{{-- ═══ MOBILE SIDEBAR DRAWER ═══ --}}
<aside id="mobile-drawer" class="fixed top-0 left-0 h-full w-72 bg-gray-900 border-r border-gray-800 z-50 flex flex-col overflow-y-auto">

  {{-- Drawer Header --}}
  <div class="flex items-center justify-between p-4 border-b border-gray-800">
    <div class="flex items-center gap-2">
      <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-blue-600 to-orange-500 flex items-center justify-center shadow-glow-sm">
        <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
      </div>
      <span class="font-display font-bold text-lg text-white tracking-wide">{{ config('app.name', 'Lapak Gaming') }}</span>
    </div>
    <button onclick="closeDrawer()" class="w-8 h-8 rounded-lg bg-gray-800 flex items-center justify-center text-gray-400 hover:text-white hover:bg-gray-750 transition-colors">
      <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
    </button>
  </div>

  {{-- User info block --}}
  @auth
  <div class="p-4 border-b border-gray-800">
    <div class="flex items-center gap-3 p-3 rounded-xl bg-gray-850 border border-gray-750">
      <div class="w-10 h-10 rounded-full bg-gradient-to-br from-blue-600 to-orange-500 flex items-center justify-center font-display font-bold text-sm shadow-glow-sm">
        {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
      </div>
      <div class="flex-1 min-w-0">
        <div class="text-sm font-semibold text-white truncate">{{ Auth::user()->name }}</div>
        <div class="text-xs text-gray-400 truncate">{{ Auth::user()->email }}</div>
      </div>
      @if(Auth::user()->is_pro ?? false)
        <span class="text-xs bg-gradient-to-r from-blue-500 to-orange-500 text-white border border-orange-600/30 px-2 py-0.5 rounded-full font-display font-semibold">PRO</span>
      @endif
    </div>
  </div>
  @endauth

  {{-- Drawer Search --}}
  <div class="p-4 border-b border-gray-800">
    <div class="relative">
      <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
      <input type="text" placeholder="Search products..." class="w-full pl-9 pr-3 py-2.5 bg-gray-800 border border-gray-700 rounded-xl text-sm text-white placeholder-gray-500 focus:outline-none focus:border-blue-500 transition-colors" />
    </div>
  </div>

  {{-- Navigation --}}
  <nav class="p-4 flex-1">
    <p class="text-xs font-display font-semibold text-gray-600 uppercase tracking-widest mb-3 px-1">Navigation</p>
    <ul class="space-y-1">
      <li>
        <a href="{{ route('marketplace.home') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl {{ request()->routeIs('marketplace.home') ? 'bg-blue-600/10 text-blue-400 border border-blue-700/30' : 'text-gray-400 hover:bg-gray-800 hover:text-white' }} font-medium text-sm transition-all">
          <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
          Home
        </a>
      </li>
      <li>
        <a href="{{ route('marketplace.browse') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-gray-400 hover:bg-gray-800 hover:text-white font-medium text-sm transition-all">
          <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"/></svg>
          Browse All
        </a>
      </li>
      <li>
        <a href="{{ route('marketplace.trending') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-gray-400 hover:bg-gray-800 hover:text-white font-medium text-sm transition-all">
          <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
          Trending
          <span class="ml-auto text-xs bg-orange-500/20 text-orange-400 border border-orange-600/30 px-1.5 py-0.5 rounded-full font-display">HOT</span>
        </a>
      </li>
      <li>
        <a href="{{ route('marketplace.deals') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-gray-400 hover:bg-gray-800 hover:text-white font-medium text-sm transition-all">
          <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
          Deals
          <span class="ml-auto text-xs bg-green-500/20 text-green-400 border border-green-600/30 px-1.5 py-0.5 rounded-full font-display">SALE</span>
        </a>
      </li>
      @auth
      <li>
        <a href="{{ route('wishlist.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-gray-400 hover:bg-gray-800 hover:text-white font-medium text-sm transition-all">
          <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
          Wishlist
        </a>
      </li>
      @endauth
    </ul>

    {{-- Dynamic categories from controller --}}
    @isset($categories)
    <p class="text-xs font-display font-semibold text-gray-600 uppercase tracking-widest mb-3 mt-6 px-1">Categories</p>
    <ul class="space-y-1">
      @foreach($categories as $category)
      <li>
        <a href="{{ route('marketplace.category', $category->slug) }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-gray-400 hover:bg-gray-800 hover:text-white text-sm transition-all">
          {{ $category->icon ?? '' }} {{ $category->name }}
        </a>
      </li>
      @endforeach
    </ul>
    @endisset

    {{-- Account links --}}
    @auth
    <p class="text-xs font-display font-semibold text-gray-600 uppercase tracking-widest mb-3 mt-6 px-1">Account</p>
    <ul class="space-y-1">
      <li>
        <a href="{{ route('profile.show') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-gray-400 hover:bg-gray-800 hover:text-white text-sm transition-all">
          <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
          Profile
        </a>
      </li>
      <li>
        <a href="{{ route('orders.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-gray-400 hover:bg-gray-800 hover:text-white text-sm transition-all">
          <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
          Order History
        </a>
      </li>
      <li>
        <form method="POST" action="{{ route('logout') }}">
          @csrf
          <button type="submit" class="w-full flex items-center gap-3 px-3 py-2.5 rounded-xl text-gray-400 hover:bg-gray-800 hover:text-white text-sm transition-all">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
            Sign Out
          </button>
        </form>
      </li>
    </ul>
    @endauth
  </nav>

  {{-- Drawer Footer — Upgrade CTA --}}
  @auth
  @if(!(Auth::user()->is_pro ?? false))
  <div class="p-4 border-t border-gray-800">
    <div class="flex items-center justify-between px-3 py-3 rounded-xl bg-gray-850 border border-gray-750">
      <div>
        <p class="text-xs font-display font-semibold text-white">{{ config('app.name') }} Pro</p>
        <p class="text-xs text-gray-500 mt-0.5">Unlock all features</p>
      </div>
      <a href="{{ route('subscription.upgrade') }}" class="text-xs bg-gradient-to-r from-blue-500 to-orange-500 hover:from-blue-400 hover:to-orange-400 text-white px-3 py-1.5 rounded-lg font-display font-semibold transition-colors shadow-glow-sm">Upgrade</a>
    </div>
  </div>
  @endif
  @endauth
</aside>

{{-- ═══ ANNOUNCEMENT BAR ═══ --}}
<div id="announcement-bar" class="bg-gradient-to-r from-blue-900/60 via-cyan-800/40 to-orange-900/60 border-b border-orange-800/30 text-center py-2 px-4 text-xs text-orange-200 font-body relative overflow-hidden">
  <div class="absolute inset-0 bg-grid opacity-30"></div>
  <span class="relative">
    🎉 Ramadan Special: Up to <strong class="text-orange-300">50% OFF</strong> on all top-up products — Use code
    <strong class="text-yellow-300 bg-yellow-400/10 px-1.5 py-0.5 rounded border border-yellow-500/30">LAPAK50</strong>
  </span>
  <button class="absolute right-3 top-1/2 -translate-y-1/2 text-orange-400 hover:text-white transition-colors" onclick="document.getElementById('announcement-bar').remove()">
    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
  </button>
</div>

{{-- ═══ STICKY NAVBAR ═══ --}}
<header class="sticky top-0 z-30 navbar-blur bg-gray-950/80 border-b border-gray-800/60">
  <div class="max-w-7xl mx-auto px-4 lg:px-6">
    <div class="flex items-center h-16 gap-3 lg:gap-6">

      {{-- Hamburger (mobile only) --}}
      <button onclick="openDrawer()" class="flex-none lg:hidden w-9 h-9 rounded-xl bg-gray-800 border border-gray-700 flex items-center justify-center text-gray-400 hover:text-white hover:border-blue-500 transition-all">
        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
      </button>

      {{-- Logo --}}
      <a href="{{ route('marketplace.home') }}" class="flex-none flex items-center gap-2.5 group">
        <img src="{{ asset('images/LOGO LAPAK1.png') }}" alt="Logo" class="w-9 h-9 object-contain">
       <img src="{{ asset('images/LOGO LAPAK.png') }}" 
          alt="Logo" 
        class="h-8 hidden sm:block">
      </a>

      {{-- Desktop Nav Links --}}
      <nav class="hidden lg:flex items-center gap-1 flex-none">
        <a href="{{ route('marketplace.home') }}" class="px-3 py-1.5 rounded-lg text-sm font-medium {{ request()->routeIs('marketplace.home') ? 'text-white bg-blue-600/10 border border-blue-700/30' : 'text-gray-400 hover:text-white hover:bg-gray-800' }} transition-all">
          Home
        </a>

        {{-- Categories Mega Dropdown --}}
        <div class="relative" id="cat-dropdown-wrapper">
          <button onclick="toggleDropdown('cat-dropdown')" class="flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-sm font-medium text-gray-400 hover:text-white hover:bg-gray-800 transition-all">
            Categories
            <svg class="w-3.5 h-3.5 transition-transform duration-200" id="cat-dropdown-arrow" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
          </button>
          <div id="cat-dropdown" class="dropdown-panel absolute top-full left-0 mt-2 w-64 bg-gray-900 border border-gray-800 rounded-2xl shadow-2xl overflow-hidden z-50">
            <div class="p-2">
              <p class="text-xs font-display font-semibold text-gray-600 uppercase tracking-widest px-3 py-2">Popular Games</p>
              @isset($categories)
                @foreach($categories->take(5) as $category)
                <a href="{{ route('marketplace.category', $category->slug) }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl hover:bg-gray-800 transition-colors group">
                  <span class="text-lg">{{ $category->icon ?? '🎮' }}</span>
                  <div>
                    <div class="text-sm font-medium text-white group-hover:text-blue-400 transition-colors">{{ $category->name }}</div>
                    <div class="text-xs text-gray-500">{{ $category->subtitle ?? '' }}</div>
                  </div>
                  <span class="ml-auto text-xs text-gray-600">{{ number_format($category->product_count ?? 0) }} items</span>
                </a>
                @endforeach
              @endisset
              <div class="border-t border-gray-800 mt-2 pt-2">
                <a href="{{ route('marketplace.browse') }}" class="flex items-center justify-center gap-1.5 px-3 py-2 rounded-xl text-xs text-blue-400 hover:bg-blue-600/10 transition-colors font-medium">
                  View all categories
                  <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </a>
              </div>
            </div>
          </div>
        </div>

        <a href="{{ route('marketplace.trending') }}" class="flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-sm font-medium text-gray-400 hover:text-white hover:bg-gray-800 transition-all">
          Trending
          <span class="text-xs bg-fuchsia-500/20 text-fuchsia-400 px-1.5 py-0.5 rounded-full font-display font-bold leading-none">HOT</span>
        </a>
        <a href="{{ route('marketplace.deals') }}" class="flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-sm font-medium text-gray-400 hover:text-white hover:bg-gray-800 transition-all">
          Deals
          <span class="text-xs bg-green-500/20 text-green-400 px-1.5 py-0.5 rounded-full font-display font-bold leading-none">50%</span>
        </a>
      </nav>

      {{-- Desktop Search Bar --}}
      <div class="hidden md:flex flex-1 max-w-xl relative">
        <div class="relative w-full flex items-center">
          <svg class="absolute left-3.5 w-4 h-4 text-gray-500 pointer-events-none" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
          <input id="search-input" type="text" placeholder="Search games, top-up, vouchers..."
            class="w-full pl-10 pr-28 py-2.5 bg-gray-900 border border-gray-700 rounded-xl text-sm text-white placeholder-gray-500 focus:outline-none focus:border-blue-500 transition-all" />
          <div class="absolute right-2 flex items-center gap-1">
            <kbd class="hidden xl:flex text-xs text-gray-600 bg-gray-800 border border-gray-700 px-1.5 py-0.5 rounded font-mono">⌘K</kbd>
            <button class="bg-gradient-to-r from-blue-500 to-orange-500 hover:from-blue-400 hover:to-orange-400 text-white text-xs font-display font-semibold px-3 py-1.5 rounded-lg transition-colors shadow-glow-sm hover:shadow-glow">Search</button>
          </div>
        </div>
      </div>

      {{-- Right Action Icons --}}
      <div class="flex-none flex items-center gap-1 sm:gap-2 ml-auto lg:ml-0">

        {{-- Mobile search icon --}}
        <button class="md:hidden w-9 h-9 rounded-xl bg-gray-800 border border-gray-700 flex items-center justify-center text-gray-400 hover:text-white hover:border-blue-500 transition-all">
          <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
        </button>

        @auth
        {{-- Notifications --}}
        <div class="relative">
          <button onclick="toggleDropdown('notif-dropdown')" class="relative w-9 h-9 rounded-xl bg-gray-800 border border-gray-700 flex items-center justify-center text-gray-400 hover:text-white hover:border-blue-500 transition-all">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
            @php
              try {
                $hasUnreadNotifications = Auth::user()->notifications()->whereNull('read_at')->count() > 0;
              } catch (\Exception $e) {
                $hasUnreadNotifications = false;
              }
            @endphp
            @if($hasUnreadNotifications)
              <span class="notif-dot"></span>
            @endif
          </button>
          <div id="notif-dropdown" class="dropdown-panel absolute top-full right-0 mt-2 w-80 bg-gray-900 border border-gray-800 rounded-2xl shadow-2xl overflow-hidden z-50">
            <div class="flex items-center justify-between px-4 py-3 border-b border-gray-800">
              <span class="font-display font-semibold text-sm text-white">Notifications</span>
              <span class="text-xs text-cyan-400 hover:text-cyan-300 cursor-pointer">Mark all read</span>
            </div>
            <div class="divide-y divide-gray-800">
              {{-- Notifications would be injected via a view composer or passed from a shared middleware --}}
              @php
                try {
                  $notifications = Auth::user()->notifications()->take(3)->get();
                } catch (\Exception $e) {
                  $notifications = collect();
                }
              @endphp
              @forelse($notifications as $notification)
              <div class="flex items-start gap-3 px-4 py-3 hover:bg-gray-800/50 transition-colors cursor-pointer {{ $notification->read_at ? 'opacity-60' : '' }}">
                <div class="w-8 h-8 rounded-full bg-green-500/20 flex items-center justify-center flex-none mt-0.5">
                  <svg class="w-4 h-4 text-green-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                </div>
                <div>
                  <div class="text-sm font-medium text-white">{{ $notification->data['title'] ?? '' }}</div>
                  <div class="text-xs text-gray-400 mt-0.5">{{ $notification->data['body'] ?? '' }}</div>
                </div>
                @if(!$notification->read_at)
                  <div class="w-2 h-2 rounded-full bg-purple-500 flex-none mt-1.5 ml-auto"></div>
                @endif
              </div>
              @empty
              <div class="px-4 py-6 text-center text-xs text-gray-500">No notifications yet.</div>
              @endforelse
            </div>
            <div class="px-4 py-3 border-t border-gray-800">
              <a href="{{ route('notifications.index') }}" class="text-xs text-center text-cyan-400 hover:text-cyan-300 block transition-colors">View all notifications</a>
            </div>
          </div>
        </div>

        {{-- Cart --}}
        <div class="relative">
          <button onclick="toggleDropdown('cart-dropdown')" class="relative w-9 h-9 rounded-xl bg-gray-800 border border-gray-700 flex items-center justify-center text-gray-400 hover:text-white hover:border-cyan-500 transition-all">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
            @php $cartCount = session('cart_count', 0); @endphp
            @if($cartCount > 0)
            <span class="absolute -top-1 -right-1 bg-cyan-600 rounded-full text-white font-display font-bold flex items-center justify-center leading-none" style="width:18px;height:18px;font-size:10px">{{ $cartCount }}</span>
            @endif
          </button>
          <div id="cart-dropdown" class="dropdown-panel absolute top-full right-0 mt-2 w-80 bg-gray-900 border border-gray-800 rounded-2xl shadow-2xl overflow-hidden z-50">
            <div class="flex items-center justify-between px-4 py-3 border-b border-gray-800">
              <span class="font-display font-semibold text-sm text-white">Cart <span class="text-gray-500 font-normal">({{ $cartCount }} items)</span></span>
              <span class="text-xs text-cyan-400 hover:text-cyan-300 cursor-pointer">Clear all</span>
            </div>
            {{-- Cart items rendered via view composer / cart service --}}
            @php $cartItems = session('cart_items', []); @endphp
            <div class="divide-y divide-gray-800">
              @forelse($cartItems as $item)
              <div class="flex items-center gap-3 px-4 py-3 hover:bg-gray-800/40 transition-colors">
                <div class="w-10 h-10 rounded-xl overflow-hidden flex-none">
                  <img src="{{ $item['image_url'] }}" alt="{{ $item['name'] }}" class="w-full h-full object-cover" />
                </div>
                <div class="flex-1 min-w-0">
                  <div class="text-sm font-medium text-white truncate">{{ $item['name'] }}</div>
                  <div class="price-text text-sm font-display font-semibold mt-0.5">Rp {{ number_format($item['price'], 0, ',', '.') }}</div>
                </div>
                <div class="flex items-center gap-1.5 flex-none">
                  <button class="w-6 h-6 rounded-lg bg-gray-800 border border-gray-700 flex items-center justify-center text-gray-400 hover:text-white hover:border-cyan-500 transition-all text-xs">−</button>
                  <span class="text-sm font-semibold text-white w-4 text-center">{{ $item['qty'] }}</span>
                  <button class="w-6 h-6 rounded-lg bg-gray-800 border border-gray-700 flex items-center justify-center text-gray-400 hover:text-white hover:border-cyan-500 transition-all text-xs">+</button>
                </div>
              </div>
              @empty
              <div class="px-4 py-6 text-center text-xs text-gray-500">Your cart is empty.</div>
              @endforelse
            </div>
            @if(count($cartItems) > 0)
            <div class="px-4 py-3 border-t border-gray-800">
              <div class="flex items-center justify-between mb-3">
                <span class="text-sm text-gray-400">Total</span>
                <span class="font-display font-bold text-lg price-text">Rp {{ number_format(collect($cartItems)->sum(fn($i) => $i['price'] * $i['qty']), 0, ',', '.') }}</span>
              </div>
              <a href="{{ route('checkout.index') }}" class="w-full bg-gradient-to-r from-cyan-600 to-blue-600 hover:from-cyan-500 hover:to-blue-500 text-white text-sm font-display font-semibold py-3 rounded-xl transition-all shadow-glow hover:shadow-glow-lg flex items-center justify-center gap-2">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                Checkout Now
              </a>
            </div>
            @endif
          </div>
        </div>

        {{-- User Account Dropdown --}}
        <div class="relative">
          <button onclick="toggleDropdown('user-dropdown')" class="flex items-center gap-2 pl-1 pr-2 sm:pr-3 py-1 rounded-xl bg-gray-800 border border-gray-700 hover:border-cyan-500 transition-all group">
            <div class="w-7 h-7 rounded-lg bg-gradient-to-br from-cyan-600 to-blue-500 flex items-center justify-center font-display font-bold text-xs text-white shadow-glow-sm">
              {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
            </div>
            <span class="hidden sm:block text-sm font-medium text-gray-300 group-hover:text-white transition-colors max-w-20 truncate">{{ explode(' ', Auth::user()->name)[0] }}</span>
            <svg class="w-3 h-3 text-gray-500 hidden sm:block" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
          </button>
          <div id="user-dropdown" class="dropdown-panel absolute top-full right-0 mt-2 w-64 bg-gray-900 border border-gray-800 rounded-2xl shadow-2xl overflow-hidden z-50">
            <div class="p-4 border-b border-gray-800 bg-gradient-to-br from-cyan-900/20 to-gray-900">
              <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-gradient-to-br from-cyan-600 to-blue-500 flex items-center justify-center font-display font-bold text-sm shadow-glow">
                  {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
                </div>
                <div>
                  <div class="font-semibold text-sm text-white">{{ Auth::user()->name }}</div>
                  <div class="text-xs text-gray-500">{{ Auth::user()->email }}</div>
                </div>
                @if(Auth::user()->is_pro ?? false)
                  <span class="ml-auto text-xs bg-cyan-600/20 text-cyan-400 border border-cyan-700/40 px-2 py-0.5 rounded-full font-display font-semibold">PRO</span>
                @endif
              </div>
              <div class="mt-3 grid grid-cols-2 gap-2">
                <div class="bg-gray-800 rounded-xl px-3 py-2 text-center">
                  <div class="text-sm font-display font-bold text-white">{{ Auth::user()->orders_count ?? 0 }}</div>
                  <div class="text-xs text-gray-500">Orders</div>
                </div>
                <div class="bg-gray-800 rounded-xl px-3 py-2 text-center">
                  <div class="text-sm font-display font-bold price-text">Rp {{ number_format((Auth::user()->balance ?? 0) / 1000, 0) }}k</div>
                  <div class="text-xs text-gray-500">Balance</div>
                </div>
              </div>
            </div>
            <div class="p-2">
              <a href="{{ route('profile.show') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl hover:bg-gray-800 transition-colors text-sm text-gray-400 hover:text-white group">
                <svg class="w-4 h-4 group-hover:text-cyan-400 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                My Profile
              </a>
              <a href="{{ route('orders.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl hover:bg-gray-800 transition-colors text-sm text-gray-400 hover:text-white group">
                <svg class="w-4 h-4 group-hover:text-cyan-400 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 <PASSWORD>"/></svg>
                Order History
              </a>
              <a href="{{ route('wishlist.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl hover:bg-gray-800 transition-colors text-sm text-gray-400 hover:text-white group">
                <svg class="w-4 h-4 group-hover:text-purple-400 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                Wishlist
                <span class="ml-auto text-xs text-gray-500">{{ Auth::user()->wishlist_count ?? 0 }}</span>
              </a>
              <a href="{{ route('settings.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl hover:bg-gray-800 transition-colors text-sm text-gray-400 hover:text-white group">
                <svg class="w-4 h-4 group-hover:text-purple-400 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                Settings
              </a>
              <div class="border-t border-gray-800 mt-1 pt-1">
                <form method="POST" action="{{ route('logout') }}">
                  @csrf
                  <button type="submit" class="w-full flex items-center gap-3 px-3 py-2.5 rounded-xl hover:bg-red-500/10 transition-colors text-sm text-red-400 hover:text-red-300">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                    Sign Out
                  </button>
                </form>
              </div>
            </div>
          </div>
        </div>

        @else
        {{-- Guest: Login / Register --}}
        <a href="{{ route('login') }}" class="text-sm font-medium text-gray-400 hover:text-white transition-colors px-3 py-1.5">Login</a>
        <a href="{{ route('register') }}" class="text-sm font-display font-semibold bg-gradient-to-r from-blue-500 to-orange-500 hover:from-blue-400 hover:to-orange-400 text-white px-4 py-2 rounded-xl transition-colors shadow-glow-sm">Sign Up</a>
        @endauth

      </div>
    </div>
  </div>
</header>
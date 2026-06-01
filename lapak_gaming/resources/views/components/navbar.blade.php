{{--
Component: components/navbar.blade.php
Premium Itemku/Codashop style marketplace navbar.
--}}

@php
  /** @var \App\Models\User|null $authUser */
  $authUser = Auth::user();
  $navCategories = isset($categories) ? $categories : collect();
  // Consider settings.* as admin-style routes when the user is an admin
  $isAdminRoute = request()->routeIs('admin.*') || ($authUser?->isAdmin() && request()->routeIs('settings.*'));
  $isAdminSettingsRoute = $authUser?->isAdmin() && request()->routeIs('settings.*');
  $cartItems = collect();
  $cartCount = 0;
  $avatarFallback = 'https://ui-avatars.com/api/?name=' . urlencode($authUser?->name ?? 'User') . '&background=08399b&color=fff';

  if ($authUser) {
    $cartCount = (int) \App\Models\Cart::query()
      ->where('user_id', $authUser->id)
      ->sum('quantity');

    $cartItems = \App\Models\Cart::query()
      ->where('user_id', $authUser->id)
      ->with('product.seller')
      ->latest()
      ->take(3)
      ->get();
  }
@endphp

@push('styles')
  <style>
    /* Use theme variables defined in layouts/app.blade.php for consistency */
    .navbar-container {
      background: linear-gradient(180deg, rgba(2, 6, 23, .92), rgba(2, 6, 23, .78));
      backdrop-filter: blur(22px);
      border-bottom: 1px solid rgba(255, 255, 255, .06);
      box-shadow: 0 10px 40px rgba(0, 0, 0, .25);
    }

    /* NAV LINK */
    .nav-modern-link {
      position: relative;
      transition: all .25s ease;
    }

    .nav-modern-link:hover {
      color: white;
      transform: translateY(-1px);
    }

    .nav-modern-link::after {
      content: '';
      position: absolute;
      left: 0;
      bottom: -6px;
      width: 0;
      height: 2px;
      border-radius: 999px;
      background: #06b6d4;
      transition: width .25s ease;
    }

    .nav-modern-link:hover::after {
      width: 100%;
    }

    /* ACTIVE state for nav links */
    .nav-modern-link.is-active {
      color: #22d3ee !important;
    }

    .nav-modern-link.is-active::after {
      width: 100%;
      background: linear-gradient(90deg, #22d3ee, #818cf8);
      box-shadow: 0 0 8px rgba(34, 211, 238, 0.6);
    }

    /* Beranda active (standalone pill) */
    .nav-home-link.is-active {
      color: #22d3ee !important;
      background: rgba(6, 182, 212, 0.12) !important;
      box-shadow: 0 0 16px rgba(6, 182, 212, 0.15);
    }

    /* Mobile drawer active */
    .mobile-nav-link.is-active {
      color: #22d3ee !important;
      font-weight: 700;
    }

    /* ICON BUTTON */
    .nav-icon-btn {
      position: relative;
      width: 44px;
      height: 44px;
      border-radius: 14px;
      display: flex;
      align-items: center;
      justify-content: center;
      background: rgba(255, 255, 255, .03);
      border: 1px solid rgba(255, 255, 255, .05);
      transition: all .25s ease;
    }

    .nav-icon-btn:hover {
      background: rgba(6, 182, 212, .12);
      border-color: rgba(6, 182, 212, .28);
      transform: translateY(-2px);
      box-shadow: 0 12px 30px rgba(6, 182, 212, .18);
    }

    /* DROPDOWN BARU */
    .dropdown-panel {
      backdrop-filter: blur(20px);
      background: rgba(15, 23, 42, .96);
      border: 1px solid rgba(255, 255, 255, .06);
      box-shadow: 0 30px 60px rgba(0, 0, 0, .45);
      border-radius: 22px;
    }

    .navbar-top {
      background: rgba(15, 23, 42, 0.98);
      color: var(--text);
    }

    .navbar-categories {
      background: rgba(2, 6, 23, 0.94);
      color: var(--text);
    }

    .admin-navbar {
      background: rgba(2, 6, 23, 0.98);
    }

    .admin-navbar .navbar-container {
      background: rgba(2, 6, 23, 0.96);
    }

    #main-navbar a,
    #main-navbar button,
    #main-navbar .nav-link {
      color: inherit;
    }

    #mobile-drawer {
      background: rgba(2, 6, 23, 0.98);
      color: var(--text);
    }

    .navbar-container .surface-weak {
      background: rgba(255, 255, 255, 0.02);
    }

    .text-itemku-blue {
      color: var(--primary) !important;
    }

    .border-itemku-blue {
      border-color: var(--primary) !important;
    }

    .bg-itemku-yellow {
      background: var(--accent) !important;
    }

    .focus-within-border-accent:focus-within {
      border-color: var(--accent) !important;
    }

    @media (max-width: 640px) {
      #main-navbar .navbar-container {
        height: 56px;
      }

      #main-navbar .h-20 {
        height: 56px;
      }

      #main-navbar .font-display {
        font-size: 1rem;
      }
    }

    .mask-gradient-right {
      scroll-behavior: smooth;
      padding-bottom: 2px;
    }

    .no-scrollbar::-webkit-scrollbar {
      display: none;
    }

    .no-scrollbar {
      -ms-overflow-style: none;
      scrollbar-width: none;
    }
  </style>
@endpush

{{-- ═══ MOBILE SIDEBAR DRAWER ═══ --}}
<aside id="mobile-drawer"
  class="fixed top-0 left-0 h-full w-72 z-50 flex flex-col overflow-y-auto text-slate-200 transition-transform -translate-x-full backdrop-blur-xl {{ ($isAdminRoute || $isAdminSettingsRoute) ? 'admin-navbar' : '' }}">
  <div class="flex items-center justify-between p-4 border-b border-white/6 navbar-top">
    <a href="{{ route('marketplace.home') }}" class="flex items-center gap-2.5">
      <img src="{{ url('storage/app/public/logo/logo.png') }}" alt="Logo"
        class="w-8 h-8 rounded-lg object-contain surface-weak">
      <span class="font-display font-bold text-base text-white tracking-wide">{{ config('app.name', 'Itemku') }}</span>
    </a>
    <button onclick="closeDrawer()" class="text-white hover:text-gray-200">
      <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
      </svg>
    </button>
  </div>

  @auth
    <div class="p-4 border-b border-white/6 bg-transparent">
      <div class="flex items-center gap-3">
        <img src="{{ $authUser?->avatar_url ?? $avatarFallback }}" alt="Avatar"
          class="w-10 h-10 rounded-full object-cover shrink-0">
        <div class="flex-1 min-w-0">
          <div class="text-sm font-semibold text-slate-100 truncate">{{ $authUser?->name ?? 'User' }}</div>
          <div class="text-xs text-slate-400 truncate">{{ $authUser?->email ?? '' }}</div>
        </div>
      </div>
    </div>
  @else
    <div class="p-4 border-b border-white/6 flex gap-2">
      <a href="{{ route('login') }}" class="flex-1 btn-ghost text-sm font-semibold text-center">Masuk</a>
      <a href="{{ route('register') }}" class="flex-1 btn-accent text-sm font-semibold text-center">Daftar</a>
    </div>
  @endauth

  <div class="p-4 border-b border-white/6">
    <form action="{{ route('products.search') }}" method="GET">
      <div class="relative">
        <svg class="absolute left-4 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-500" fill="none" viewBox="0 0 24 24"
          stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
        </svg>
        <input type="text" name="q" placeholder="Cari Game, Item..."
          class="w-full rounded-full border border-slate-700/80 bg-slate-950/90 pl-11 pr-4 py-2 text-sm text-slate-100 placeholder:text-slate-500 outline-none focus:border-cyan-400 focus:ring-2 focus:ring-cyan-500/20" />
      </div>
    </form>
  </div>

  <nav class="p-4 flex-1 space-y-4">
    @if(($isAdminRoute || $isAdminSettingsRoute) && $authUser?->isAdmin())
      @include('components.navbar-admin-links')
    @else
      <div>
        <ul class="space-y-1">
          <li><a href="{{ route('marketplace.home') }}"
              class="flex items-center py-2 text-sm font-medium transition-colors
                        {{ request()->routeIs('marketplace.home') ? 'text-cyan-400 font-bold' : 'text-slate-300 hover:text-cyan-400' }}">Beranda</a></li>
          <li><a href="{{ route('marketplace.browse') }}"
              class="flex items-center py-2 text-sm font-medium transition-colors
                        {{ request()->routeIs('marketplace.browse') ? 'text-cyan-400 font-bold' : 'text-slate-300 hover:text-cyan-400' }}">Semua Produk</a></li>
          <li><a href="{{ route('marketplace.trending') }}"
              class="flex items-center py-2 text-sm font-medium transition-colors
                        {{ request()->routeIs('marketplace.trending') ? 'text-cyan-400 font-bold' : 'text-slate-300 hover:text-cyan-400' }}">Trending</a></li>
        </ul>
      </div>

      @auth
        <div>
          <p class="text-xs font-semibold surface-muted uppercase mb-2">Akun Saya</p>
          <ul class="space-y-1">
            @if($authUser?->isAdmin())
              <li><a href="{{ route('admin.dashboard') }}"
                  class="flex items-center py-2 surface-text hover:text-itemku-blue text-sm">Panel Admin</a></li>
              <li><a href="{{ route('admin.orders.index') }}"
                  class="flex items-center py-2 surface-text hover:text-itemku-blue text-sm">Transaksi</a></li>
            @else
              <li><a href="{{ route('dashboard') }}"
                  class="flex items-center py-2 surface-text hover:text-itemku-blue text-sm">Dashboard</a></li>
              <li><a href="{{ route('orders.index') }}"
                  class="flex items-center py-2 surface-text hover:text-itemku-blue text-sm">Pesanan Saya</a></li>
              <li><a href="{{ route('wallet.index') }}"
                  class="flex items-center py-2 surface-text hover:text-itemku-blue text-sm">Wallet</a></li>
            @endif
            <li>
              <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                  class="w-full flex items-center py-2 text-red-400 hover:text-red-200 text-sm text-left">Keluar</button>
              </form>
            </li>
          </ul>
        </div>
      @endauth
    @endif
  </nav>
</aside>

{{-- ═══ DESKTOP NAVBAR ═══ --}}
<header id="main-navbar"
  class="sticky top-0 z-40 shadow-sm w-full font-sans backdrop-blur-xl border-b border-white/10 {{ ($isAdminRoute || $isAdminSettingsRoute) ? 'admin-navbar' : '' }}">

  {{-- 2. Main Bar (Blue) --}}
  <div class="navbar-container h-20 flex flex-col justify-center">
    <div class="max-w-7xl mx-auto px-4 w-full flex items-center gap-6">

      {{-- Mobile Menu & Logo --}}
      <div class="flex items-center gap-3">
        <button onclick="openDrawer()" class="lg:hidden text-white hover:opacity-80">
          <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
          </svg>
        </button>
        <a href="{{ route('marketplace.home') }}" class="flex items-center gap-2 shrink-0">
          <img src="{{ url('storage/app/public/logo/logo.png') }}" alt="Logo"
            class="h-8 w-auto surface-weak rounded p-1">
          <span
            class="font-display font-bold text-white text-lg ml-1 tracking-tight">{{ config('app.name', 'Lapak Gaming') }}</span>
        </a>
      </div>

      {{-- Search Bar --}}
      @if(!$isAdminRoute && !$isAdminSettingsRoute)
        <div class="hidden md:flex flex-1 flex-col relative">
          <form action="{{ route('products.search') }}" method="GET" class="w-full relative">
            <div
              class="flex items-center gap-2 rounded-2xl border border-white/5 bg-[#020817]/90 px-4 py-2.5 shadow-[0_10px_35px_rgba(0,0,0,.25)] transition-all duration-300 focus-within:border-cyan-400/40 focus-within:shadow-[0_0_30px_rgba(6,182,212,.15)]">
              <div class="surface-muted shrink-0">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
              </div>
              <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari Game, Top Up, Akun..."
                class="w-full bg-transparent border-none outline-none py-2 px-2 text-sm text-slate-100 placeholder:text-slate-500" />
              <button type="submit"
                class="inline-flex h-10 min-w-[44px] items-center justify-center rounded-full bg-cyan-500/15 text-slate-100 hover:bg-cyan-500/30 transition">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
              </button>
            </div>
          </form>
        </div>
      @endif

      {{-- Right Icons (Auth, Chat, Cart) --}}
      <div class="flex items-center gap-3 shrink-0 ml-auto">

        @if($authUser)
          @if(!$isAdminRoute && !$isAdminSettingsRoute)
            {{-- Search Mobile --}}
            <a href="{{ route('products.search') }}" class="md:hidden text-white opacity-90 hover:opacity-100">
              <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
              </svg>
            </a>

            {{-- Notifications --}}
            <div class="relative">
              <button onclick="toggleDropdown('notif-dropdown'); loadNotificationPreview();"
                class="nav-icon-btn text-white/90">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                </svg>
                <span id="notif-badge" class="absolute top-1 right-1 w-2 h-2 bg-red-500 rounded-full hidden"></span>
              </button>
              <div id="notif-dropdown" data-notifications-url="{{ route('notifications.poll') }}"
                data-notifications-read-base-url="{{ route('notifications.index') }}"
                data-notifications-read-all-url="{{ route('notifications.read-all') }}"
                class="dropdown-panel absolute right-0 top-full mt-2 w-80 bg-surface-850 rounded-lg shadow-xl border border-white/6 overflow-hidden text-left text-slate-200 z-50">
                <div class="px-4 py-3 border-b flex justify-between items-center bg-transparent">
                  <span class="font-bold text-sm text-slate-100">Notifikasi</span>
                  <a href="{{ route('notifications.index') }}" class="text-xs text-itemku-blue">Lihat semua</a>
                </div>
                <div id="notif-dropdown-body" class="max-h-72 overflow-y-auto">
                  <div class="px-4 py-6 text-sm text-slate-400 text-center">Klik ikon notifikasi untuk memuat pesan terbaru.
                  </div>
                </div>
              </div>
            </div>

            {{-- Chat --}}
            @if(!$authUser?->isAdmin())
              <div class="relative">
                <a href="{{ route('chat.inbox') }}" class="block text-white opacity-90 hover:opacity-100 p-1">
                  <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a.863.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                  </svg>
                  <span id="chat-badge"
                    class="absolute -top-2 -right-2 min-w-[18px] h-[18px] px-1 flex items-center justify-center rounded-full bg-red-500 text-white text-[10px] font-bold hidden">0</span>
                </a>
              </div>

              {{-- Cart --}}
              <div class="relative">
                <button onclick="toggleDropdown('cart-dropdown')"
                  class="text-white opacity-90 hover:opacity-100 p-1 relative">
                  <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                  </svg>
                  @if($cartCount > 0)
                    <span
                      class="absolute -top-1 -right-1 bg-red-500 text-white text-[10px] font-bold px-1.5 py-0.5 rounded-full">{{ $cartCount > 99 ? '99+' : $cartCount }}</span>
                  @endif
                </button>
                <div id="cart-dropdown"
                  class="dropdown-panel absolute right-0 top-full mt-2 w-72 bg-surface-850 rounded-lg shadow-xl border border-white/6 overflow-hidden text-left z-50">
                  <div class="px-4 py-3 border-b flex justify-between items-center bg-transparent">
                    <span class="font-bold text-sm text-slate-100">Keranjang</span>
                    <a href="{{ route('cart.index') }}" class="text-xs text-itemku-blue">Lihat semua</a>
                  </div>
                  @if($cartItems->isNotEmpty())
                    <div class="max-h-72 overflow-y-auto divide-y" style="border-color:var(--card-border)">
                      @foreach($cartItems as $item)
                        <a href="{{ route('cart.index') }}" class="flex gap-3 px-4 py-3 hover:surface-panel"
                          style="text-decoration:none">
                          <img src="{{ $item->product?->image_url }}" alt="" class="w-10 h-10 object-cover rounded"
                            style="background:var(--card-border)">
                          <div class="flex-1 min-w-0">
                            <div class="text-sm surface-text font-medium truncate">{{ $item->product?->name ?? 'Produk' }}</div>
                            <div class="text-xs surface-muted mt-1">{{ $item->quantity }} x Rp
                              {{ number_format((float) ($item->product?->price ?? 0), 0, ',', '.') }}
                            </div>
                          </div>
                        </a>
                      @endforeach
                    </div>
                  @else
                    <div class="p-6 text-center text-sm text-slate-400">Keranjang kosong</div>
                  @endif
                </div>
              </div>
            @endif
          @endif

          {{-- If on admin routes, show admin quick links instead of chat/cart --}}
          @if($isAdminRoute && $authUser?->isAdmin())
            <div class="hidden xl:flex items-center gap-2 flex-wrap max-w-[58vw] justify-end">
              <a href="{{ route('admin.dashboard') }}"
                class="rounded-full border border-white/10 bg-white/5 px-3 py-2 text-sm font-semibold text-white transition hover:bg-white/10">Dashboard</a>
              <a href="{{ route('admin.contact-messages.index') }}"
                class="rounded-full border border-white/10 bg-white/5 px-3 py-2 text-sm font-semibold text-white transition hover:bg-white/10">Pesan
                Masuk</a>
              <a href="{{ route('admin.users.index') }}"
                class="rounded-full border border-white/10 bg-white/5 px-3 py-2 text-sm font-semibold text-white transition hover:bg-white/10">Kelola
                Akun</a>
              <a href="{{ route('admin.verification.index') }}"
                class="rounded-full border border-white/10 bg-white/5 px-3 py-2 text-sm font-semibold text-white transition hover:bg-white/10">Verifikasi</a>
              <a href="{{ route('admin.orders.index') }}"
                class="rounded-full border border-white/10 bg-white/5 px-3 py-2 text-sm font-semibold text-white transition hover:bg-white/10">Transaksi</a>
              <a href="{{ route('admin.banners.index') }}"
                class="rounded-full border border-white/10 bg-white/5 px-3 py-2 text-sm font-semibold text-white transition hover:bg-white/10">Banner</a>
              <a href="{{ route('admin.notifications.index') }}"
                class="rounded-full border border-white/10 bg-white/5 px-3 py-2 text-sm font-semibold text-white transition hover:bg-white/10">Notifikasi</a>
              <a href="{{ route('admin.terminal.index') }}"
                class="rounded-full border border-white/10 bg-white/5 px-3 py-2 text-sm font-semibold text-white transition hover:bg-white/10">Terminal</a>
            </div>
          @endif
          <div class="h-6 w-px surface-weak mx-1 hidden sm:block"></div>

          {{-- User Avatar Dropdown --}}
          <div class="relative hidden sm:block">
            <button onclick="toggleDropdown('user-dropdown')"
              class="flex items-center gap-2.5 rounded-full border border-white/10 bg-black/20 pl-2 pr-3 py-1.5 hover:bg-white/5 transition-colors">
              <img src="{{ $authUser?->avatar_url ?? $avatarFallback }}"
                class="w-7 h-7 rounded-full object-cover border border-cyan-500/30" alt="Avatar">
              <span class="text-sm font-bold text-white truncate max-w-[120px]">{{ $authUser?->name ?? 'User' }}</span>
              <svg class="w-3.5 h-3.5 text-cyan-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
              </svg>
            </button>

            <div id="user-dropdown"
              class="absolute right-0 top-full mt-4 w-64 hidden z-[999] bg-[#0B1220] rounded-2xl shadow-[0_30px_80px_rgba(0,0,0,0.9)] border border-white/10 overflow-hidden">

              {{-- Header Profil --}}
              <div
                class="px-4 py-4 border-b border-white/10 bg-[#060A14] bg-gradient-to-r from-cyan-500/10 to-transparent">
                <p class="text-sm font-black text-white truncate">{{ $authUser->name }}</p>
                <p class="text-xs text-cyan-100/70 truncate mt-0.5">{{ $authUser->email }}</p>
              </div>

              {{-- Menu Links --}}
              <div class="p-2 space-y-1">
                @if(($isAdminRoute || $isAdminSettingsRoute) && $authUser->isAdmin())
                  <a href="{{ route('admin.dashboard') }}"
                    class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium text-slate-300 hover:bg-white/10 hover:text-white transition-all">
                    <svg class="w-4 h-4 text-cyan-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    </svg>
                    Panel Admin
                  </a>
                  <a href="{{ route('admin.contact-messages.index') }}"
                    class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium text-slate-300 hover:bg-white/10 hover:text-white transition-all">
                    <svg class="w-4 h-4 text-cyan-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                    Pesan Masuk
                  </a>
                  <a href="{{ route('admin.users.index') }}"
                    class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium text-slate-300 hover:bg-white/10 hover:text-white transition-all">
                    <svg class="w-4 h-4 text-cyan-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                    Kelola Akun
                  </a>
                @else
                  <a href="{{ route('dashboard') }}"
                    class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium text-slate-300 hover:bg-cyan-500/10 hover:text-cyan-400 transition-all">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zm10 0a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zm10 0a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                    </svg>
                    Dashboard
                  </a>
                  <a href="{{ route('orders.index') }}"
                    class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium text-slate-300 hover:bg-cyan-500/10 hover:text-cyan-400 transition-all">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                    </svg>
                    Pesanan Saya
                  </a>
                  <a href="{{ route('wallet.index') }}"
                    class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium text-slate-300 hover:bg-cyan-500/10 hover:text-cyan-400 transition-all">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                    </svg>
                    Wallet Digital
                  </a>
                  @if($authUser->isSellerAccount())
                    <a href="{{ route('seller.dashboard') }}"
                      class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium text-slate-300 hover:bg-emerald-500/10 hover:text-emerald-400 transition-all mt-1">
                      <svg class="w-4 h-4 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                      </svg>
                      Dashboard Penjual
                    </a>
                  @else
                    <a href="{{ route('seller.register.form') }}"
                      class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-bold text-amber-400 bg-amber-500/10 hover:bg-amber-500/20 border border-amber-500/20 transition-all mt-1">
                      <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                      </svg>
                      Daftar Jadi Penjual
                    </a>
                  @endif
                @endif

                <div class="h-px bg-white/10 my-1 mx-2"></div>

                {{-- Pengaturan Akun --}}
                @if(Route::has('settings.account'))
                  <a href="{{ route('settings.account') }}"
                    class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium text-slate-300 hover:bg-white/10 hover:text-white transition-all">
                    <svg class="w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    Pengaturan Akun
                  </a>
                @else
                  <a href="{{ url('/settings/account') }}"
                    class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium text-slate-300 hover:bg-white/10 hover:text-white transition-all">
                    <svg class="w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    Pengaturan Akun
                  </a>
                @endif
              </div>

              {{-- Logout Button --}}
              <div class="p-2 border-t border-white/10 bg-black/20">
                <form method="POST" action="{{ route('logout') }}">
                  @csrf
                  <button type="submit"
                    class="w-full flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-bold text-rose-400 hover:bg-rose-500/10 hover:text-rose-300 transition-all">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                    </svg>
                    Keluar
                  </button>
                </form>
              </div>
            </div>
          </div>

        @else
          {{-- Guest --}}
          <a href="{{ route('products.search') }}" class="md:hidden text-white opacity-90 hover:opacity-100 mr-2">
            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
          </a>
          <a href="{{ route('login') }}"
            class="hidden sm:block text-white text-sm font-semibold hover:opacity-80 transition-opacity">Masuk</a>
          <a href="{{ route('register') }}" class="btn-accent">Daftar</a>
        @endif

      </div>
    </div>
  </div>

  {{-- 3. Category Bar (Dark Blue) --}}
  @if(!$isAdminRoute)
    <div class="navbar-categories h-12 hidden lg:block border-t border-white/5 shadow-sm">
      <div class="max-w-7xl mx-auto px-4 h-full flex items-center gap-1">

        {{-- Kategori Dropdown (MEGA MENU UPGRADED) --}}
        <div class="relative h-full flex items-center group">
          <button
            class="flex items-center gap-2 text-white font-bold text-sm px-4 py-1.5 rounded-xl hover:bg-white/5 transition h-full">
            <svg class="w-4 h-4 text-cyan-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7" />
            </svg>
            Kategori
          </button>

          {{-- Mega Menu Panel --}}
          <div
            class="absolute left-0 top-full w-[850px] bg-[#0B1220] rounded-2xl shadow-[0_30px_80px_rgba(0,0,0,0.9)] border border-white/10 hidden group-hover:flex z-[999] min-h-[380px] max-h-[75vh] overflow-hidden transition-all duration-300">

            {{-- Kiri: List Kategori dari DB --}}
            <div class="w-64 bg-[#060A14] border-r border-white/5 p-3 overflow-y-auto max-h-[75vh] no-scrollbar">
              <p class="text-[10px] font-bold text-slate-500 uppercase tracking-widest px-3 mb-2">Semua Game</p>
              @if($navCategories->isNotEmpty())
                @foreach($navCategories as $cat)
                  <a href="{{ route('categories.show', $cat->slug) }}"
                    class="flex items-center gap-3 px-3 py-2.5 text-sm text-slate-300 hover:bg-gradient-to-r hover:from-cyan-500/10 hover:to-transparent hover:text-cyan-400 rounded-xl transition-all duration-200">
                    <img src="{{ $cat->image_url }}" alt=""
                      class="w-6 h-6 object-cover rounded-lg bg-slate-800 border border-white/10 shadow-sm shrink-0">
                    <span class="truncate font-medium">{{ $cat->name }}</span>
                  </a>
                @endforeach
              @endif
              <div class="mt-4 pt-2 border-t border-white/5">
                <a href="{{ route('categories.index') }}"
                  class="flex items-center justify-center gap-2 py-2 px-4 rounded-xl bg-white/5 text-xs font-bold text-cyan-400 hover:bg-cyan-500/10 transition">
                  Lihat Semua Game
                  <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                  </svg>
                </a>
              </div>
            </div>

            {{-- Kanan: Grid Pilihan Favorit --}}
            <div class="flex-1 p-6 overflow-y-auto max-h-[75vh] bg-gradient-to-b from-transparent to-slate-950/20">
              <h3 class="font-black text-white text-base tracking-wide mb-4 flex items-center gap-2">
                <span class="h-2 w-2 rounded-full bg-cyan-400 shadow-[0_0_8px_#22d3ee]"></span>
                Paling Populer & Instan
              </h3>

              <div class="grid grid-cols-2 gap-4">
                <a href="{{ route('products.search', ['category' => 'game-top-up']) }}"
                  class="group/card block p-4 rounded-xl bg-white/[0.02] border border-white/5 hover:border-cyan-500/30 hover:bg-cyan-500/[0.03] transition-all duration-300 shadow-sm">
                  <div class="flex items-center justify-between">
                    <span class="block font-bold text-slate-100 group-hover/card:text-cyan-400 transition-colors">Game Top
                      Up</span>
                    <span
                      class="text-[10px] font-black tracking-wider text-cyan-400 bg-cyan-500/10 px-2 py-0.5 rounded-md">INSTAN</span>
                  </div>
                  <span class="block text-xs text-slate-400 mt-2 leading-relaxed">Mobile Legends, Free Fire, PUBG Mobile,
                    Valorant, & Game Favoritmu.</span>
                </a>

                <a href="{{ route('products.search', ['category' => 'game-key']) }}"
                  class="group/card block p-4 rounded-xl bg-white/[0.02] border border-white/5 hover:border-purple-500/30 hover:bg-purple-500/[0.03] transition-all duration-300 shadow-sm">
                  <div class="flex items-center justify-between">
                    <span class="block font-bold text-slate-100 group-hover/card:text-purple-400 transition-colors">Game
                      Key & CD-Key</span>
                    <span
                      class="text-[10px] font-black tracking-wider text-purple-400 bg-purple-500/10 px-2 py-0.5 rounded-md">PC
                      STEAM</span>
                  </div>
                  <span class="block text-xs text-slate-400 mt-2 leading-relaxed">Akses game orisinal Steam, EA App, Epic
                    Games, & Ubisoft Connect.</span>
                </a>

                <a href="{{ route('products.search', ['category' => 'roblox']) }}"
                  class="group/card block p-4 rounded-xl bg-white/[0.02] border border-white/5 hover:border-amber-500/30 hover:bg-amber-500/[0.03] transition-all duration-300 shadow-sm">
                  <div class="flex items-center justify-between">
                    <span class="block font-bold text-slate-100 group-hover/card:text-amber-400 transition-colors">Roblox
                      Center</span>
                    <span
                      class="text-[10px] font-black tracking-wider text-amber-400 bg-amber-500/10 px-2 py-0.5 rounded-md">POPULER</span>
                  </div>
                  <span class="block text-xs text-slate-400 mt-2 leading-relaxed">Beli Robux murah, item khusus, pet
                    premium, dan kelola akun roblox.</span>
                </a>

                <a href="{{ route('products.search', ['category' => 'voucher']) }}"
                  class="group/card block p-4 rounded-xl bg-white/[0.02] border border-white/5 hover:border-emerald-500/30 hover:bg-emerald-500/[0.03] transition-all duration-300 shadow-sm">
                  <div class="flex items-center justify-between">
                    <span
                      class="block font-bold text-slate-100 group-hover/card:text-emerald-400 transition-colors">Voucher &
                      Gift Card</span>
                    <span
                      class="text-[10px] font-black tracking-wider text-emerald-400 bg-emerald-500/10 px-2 py-0.5 rounded-md">PREMIUM</span>
                  </div>
                  <span class="block text-xs text-slate-400 mt-2 leading-relaxed">Voucher Steam Wallet, Google Play,
                    iTunes, PlayStation, & Nintendo.</span>
                </a>
              </div>

              <div
                class="mt-6 p-4 rounded-xl border border-white/5 bg-gradient-to-r from-cyan-500/5 to-transparent flex items-center justify-between">
                <div>
                  <h4 class="text-xs font-bold text-white">Butuh Bantuan Transaksi?</h4>
                  <p class="text-[11px] text-slate-400 mt-0.5">Layanan Customer Service kami stand-by 24 jam penuh
                    untukmu.</p>
                </div>
                <a href="/hubungi-kami" class="text-xs font-bold text-cyan-400 hover:underline">Hubungi Kami</a>
              </div>
            </div>

          </div>
        </div>

        <div class="h-5 w-px bg-slate-200/20 mx-2"></div>

        {{-- Horizontal Links --}}
        <a href="{{ route('marketplace.home') }}" class="hidden lg:flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-semibold transition-all duration-300
                    {{ request()->routeIs('marketplace.home')
      ? 'text-cyan-400 bg-cyan-500/10 border border-cyan-400/20 shadow-[0_0_14px_rgba(6,182,212,0.15)]'
      : 'text-white/80 hover:text-white hover:bg-cyan-500/10' }}">
          <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M3 10l9-7 9 7v9a2 2 0 01-2 2h-4a2 2 0 01-2-2V13H9v6a2 2 0 01-2 2H3z" />
          </svg>
          Beranda
        </a>

        <div class="flex-1 overflow-x-auto no-scrollbar mask-gradient-right">
          <div class="flex items-center gap-2 min-w-max">
            @php
              $currentQ = request('q', '');
              $currentCat = request('category', '');
              $navLinkBase = 'nav-modern-link px-4 py-2 text-sm font-medium rounded-xl transition-all duration-300 whitespace-nowrap';
              $navActive = 'text-cyan-400 bg-cyan-500/10 border border-cyan-400/20';
              $navInactive = 'text-white/75 hover:text-white hover:bg-white/[0.04]';
            @endphp
            <a href="{{ route('products.search', ['category' => 'top-up-game']) }}"
              class="{{ $navLinkBase }} flex items-center gap-2 {{ ($currentCat === 'top-up-game') ? $navActive : $navInactive }}">
              <span class="px-2 py-0.5 rounded-full bg-cyan-400/20 text-cyan-200 text-[10px] font-bold">HOT</span>
              Top Up Game
            </a>
            <a href="{{ route('products.search', ['category' => 'akun']) }}"
              class="{{ $navLinkBase }} {{ ($currentCat === 'akun') ? $navActive : $navInactive }}">Akun Game</a>
            <a href="{{ route('products.search', ['category' => 'voucher']) }}"
              class="{{ $navLinkBase }} {{ ($currentCat === 'voucher') ? $navActive : $navInactive }}">Voucher Game</a>
            <a href="{{ route('products.search', ['category' => 'roblox']) }}"
              class="{{ $navLinkBase }} {{ ($currentCat === 'roblox') ? $navActive : $navInactive }}">Roblox Games</a>
            <a href="{{ route('products.search', ['category' => 'growtopia']) }}"
              class="{{ $navLinkBase }} {{ ($currentCat === 'growtopia') ? $navActive : $navInactive }}">Growtopia</a>
            <a href="{{ route('products.search', ['category' => 'genshin-impact']) }}"
              class="{{ $navLinkBase }} {{ ($currentCat === 'genshin-impact') ? $navActive : $navInactive }}">Genshin
              Impact</a>
            <a href="{{ route('products.search', ['category' => 'dota-2']) }}"
              class="{{ $navLinkBase }} {{ ($currentCat === 'dota-2') ? $navActive : $navInactive }}">Dota 2 Item</a>
            <a href="{{ route('products.search', ['category' => 'game-key']) }}"
              class="{{ $navLinkBase }} {{ ($currentCat === 'game-key') ? $navActive : $navInactive }}">Game Key</a>
            <a href="{{ route('products.search', ['category' => 'mobile-legends']) }}"
              class="{{ $navLinkBase }} {{ ($currentCat === 'mobile-legends') ? $navActive : $navInactive }}">Mobile
              Legend Account</a>
          </div>
        </div>
      </div>
    </div>
  @endif
</header>

@push('scripts')
  <script>
    function getCurrentTheme() {
      return document.documentElement.getAttribute('data-theme') || (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light');
    }

    function applyTheme(t) {
      document.documentElement.setAttribute('data-theme', t);
      try { localStorage.setItem('site-theme', t); } catch (e) { }
      updateThemeIcon(t);
    }

    function initTheme() {
      try {
        const saved = localStorage.getItem('site-theme');
        const theme = saved || (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light');
        applyTheme(theme);
      } catch (e) { applyTheme(getCurrentTheme()); }
    }

    function toggleTheme() {
      const cur = getCurrentTheme();
      applyTheme(cur === 'dark' ? 'light' : 'dark');
    }

    function updateThemeIcon(t) {
      const icon = document.getElementById('themeIcon');
      if (!icon) return;
      if (t === 'dark') {
        icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12.79A9 9 0 1111.21 3 7 7 0 0021 12.79z" />';
      } else {
        icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v2m0 14v2m9-9h-2M5 12H3m15.364-6.364l-1.414 1.414M7.05 16.95l-1.414 1.414M16.95 16.95l1.414 1.414M7.05 7.05L5.636 5.636M12 8a4 4 0 100 8 4 4 0 000-8z" />';
      }
    }

    document.addEventListener('DOMContentLoaded', initTheme);

    const navbar = document.getElementById('main-navbar');
    window.addEventListener('scroll', () => {
      if (window.scrollY > 12) {
        navbar.style.backdropFilter = 'blur(28px)';
        navbar.style.boxShadow = '0 14px 45px rgba(0,0,0,.38)';
      } else {
        navbar.style.boxShadow = '';
      }
    });
    // ═══ DROPDOWN SYSTEM ═══
    function toggleDropdown(id) {
      const el = document.getElementById(id);
      if (!el) return;

      const isHidden = el.classList.contains('hidden');

      // Tutup semua dropdown dulu
      closeAllDropdowns();

      // Kalau tadinya hidden, buka. Kalau sudah terbuka, biarkan tertutup.
      if (isHidden) {
        el.classList.remove('hidden');
      }
    }

    function closeAllDropdowns() {
      ['user-dropdown', 'notif-dropdown', 'cart-dropdown'].forEach(id => {
        const el = document.getElementById(id);
        if (el) el.classList.add('hidden');
      });
    }

    // Klik di luar = tutup semua dropdown
    document.addEventListener('click', function (e) {
      const clickedTrigger = e.target.closest('button[onclick], a[onclick]');
      const clickedInsideDropdown = e.target.closest('#user-dropdown, #notif-dropdown, #cart-dropdown');

      if (!clickedTrigger && !clickedInsideDropdown) {
        closeAllDropdowns();
      }
    });

    // Tutup dengan tombol ESC
    document.addEventListener('keydown', function (e) {
      if (e.key === 'Escape') closeAllDropdowns();
    });

    // ═══ MOBILE DRAWER ═══
    function openDrawer() {
      const drawer = document.getElementById('mobile-drawer');
      if (drawer) {
        drawer.classList.remove('-translate-x-full');
        drawer.classList.add('translate-x-0');
        document.body.style.overflow = 'hidden';
      }
    }

    function closeDrawer() {
      const drawer = document.getElementById('mobile-drawer');
      if (drawer) {
        drawer.classList.add('-translate-x-full');
        drawer.classList.remove('translate-x-0');
        document.body.style.overflow = '';
      }
    }

    // Klik backdrop untuk tutup drawer
    document.addEventListener('click', function (e) {
      const drawer = document.getElementById('mobile-drawer');
      if (!drawer) return;
      const isOpen = drawer.classList.contains('translate-x-0');
      if (isOpen && !drawer.contains(e.target) && !e.target.closest('button[onclick="openDrawer()"]')) {
        closeDrawer();
      }
    });

    // ═══ NOTIFICATION PREVIEW (stub — ganti dengan implementasi kamu) ═══
    function loadNotificationPreview() {
      const dropdown = document.getElementById('notif-dropdown');
      const body = document.getElementById('notif-dropdown-body');
      if (!dropdown || !body) return;

      const url = dropdown.dataset.notificationsUrl;
      if (!url) return;

      body.innerHTML = '<div class="px-4 py-6 text-sm text-slate-400 text-center">Memuat...</div>';

      fetch(url, {
        headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
      })
        .then(r => r.json())
        .then(data => {
          const notifs = data.notifications ?? data.data ?? [];
          const badge = document.getElementById('notif-badge');

          if (notifs.length === 0) {
            body.innerHTML = '<div class="px-4 py-6 text-sm text-slate-400 text-center">Tidak ada notifikasi baru.</div>';
            if (badge) badge.classList.add('hidden');
            return;
          }

          if (badge) badge.classList.remove('hidden');

          body.innerHTML = notifs.slice(0, 5).map(n => `
          <a href="${n.url ?? '#'}" class="flex gap-3 px-4 py-3 hover:bg-white/5 border-b border-white/5 transition-colors" style="text-decoration:none">
            <div class="flex-1 min-w-0">
              <div class="text-sm text-slate-200 font-medium truncate">${n.title ?? 'Notifikasi'}</div>
              <div class="text-xs text-slate-400 mt-0.5 line-clamp-2">${n.message ?? n.body ?? ''}</div>
              <div class="text-[10px] text-slate-500 mt-1">${n.created_at ?? ''}</div>
            </div>
            ${!n.read_at ? '<span class="w-2 h-2 rounded-full bg-cyan-400 shrink-0 mt-1"></span>' : ''}
          </a>
        `).join('');
        })
        .catch(() => {
          body.innerHTML = '<div class="px-4 py-6 text-sm text-red-400 text-center">Gagal memuat notifikasi.</div>';
        });
    }

    // ═══ SCROLL NAVBAR EFFECT ═══
    document.addEventListener('DOMContentLoaded', function () {
      const navbar = document.getElementById('main-navbar');
      if (!navbar) return;

      window.addEventListener('scroll', () => {
        if (window.scrollY > 12) {
          navbar.style.backdropFilter = 'blur(28px)';
          navbar.style.boxShadow = '0 14px 45px rgba(0,0,0,.38)';
        } else {
          navbar.style.boxShadow = '';
        }
      });
    });
  </script>
@endpush
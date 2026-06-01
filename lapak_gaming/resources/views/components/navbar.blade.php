{{--
Component: components/navbar.blade.php
Premium Itemku/Codashop style marketplace navbar.
--}}

@php
  /** @var \App\Models\User|null $authUser */
  $authUser = Auth::user();
  $navCategories = isset($categories) ? $categories : collect();
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
    /* ── BASE NAVBAR ── */
    .navbar-container {
      background: linear-gradient(180deg, rgba(2, 6, 23, .92), rgba(2, 6, 23, .78));
      backdrop-filter: blur(22px);
      border-bottom: 1px solid rgba(255, 255, 255, .06);
      box-shadow: 0 10px 40px rgba(0, 0, 0, .25);
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

    #mobile-drawer {
      background: rgba(2, 6, 23, 0.98);
      color: var(--text);
    }

    /* ── NAV LINKS ── */
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

    .nav-modern-link.is-active {
      color: #22d3ee !important;
    }

    .nav-modern-link.is-active::after {
      width: 100%;
      background: linear-gradient(90deg, #22d3ee, #818cf8);
      box-shadow: 0 0 8px rgba(34, 211, 238, 0.6);
    }

    .nav-home-link.is-active {
      color: #22d3ee !important;
      background: rgba(6, 182, 212, 0.12) !important;
      box-shadow: 0 0 16px rgba(6, 182, 212, 0.15);
    }

    .mobile-nav-link.is-active {
      color: #22d3ee !important;
      font-weight: 700;
    }

    /* ── ICON BUTTON ── */
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

    /* ── DROPDOWN PANEL ── */
    .dropdown-panel {
      backdrop-filter: blur(20px);
      background: rgba(15, 23, 42, .96);
      border: 1px solid rgba(255, 255, 255, .06);
      box-shadow: 0 30px 60px rgba(0, 0, 0, .45);
      border-radius: 22px;
    }

    /* ── PROFILE DROPDOWN ── */
    .profile-dropdown {
      background: linear-gradient(145deg, #0d1528 0%, #0a1020 100%);
      border: 1px solid rgba(255, 255, 255, .08);
      box-shadow: 0 40px 80px rgba(0, 0, 0, .7), 0 0 0 1px rgba(6, 182, 212, .05);
      border-radius: 20px;
      overflow: hidden;
      animation: dropdownIn .18s cubic-bezier(.16, 1, .3, 1);
    }

    .profile-dropdown-header {
      background: linear-gradient(135deg, rgba(6, 182, 212, .12) 0%, rgba(129, 140, 248, .06) 100%);
      border-bottom: 1px solid rgba(255, 255, 255, .06);
      padding: 16px;
      position: relative;
      overflow: hidden;
    }

    .profile-dropdown-header::before {
      content: '';
      position: absolute;
      inset: 0;
      background: radial-gradient(ellipse at top right, rgba(6, 182, 212, .08) 0%, transparent 70%);
    }

    .profile-menu-item {
      display: flex;
      align-items: center;
      gap: 10px;
      padding: 9px 12px;
      border-radius: 12px;
      margin: 1px 6px;
      font-size: 13px;
      font-weight: 500;
      color: #cbd5e1;
      transition: all .2s ease;
      text-decoration: none;
      cursor: pointer;
      border: 1px solid transparent;
      width: calc(100% - 12px);
      box-sizing: border-box;
    }

    .profile-menu-item:hover {
      background: rgba(255, 255, 255, .05);
      border-color: rgba(255, 255, 255, .06);
      color: #f1f5f9;
    }

    .profile-menu-item .menu-icon {
      width: 28px;
      height: 28px;
      border-radius: 8px;
      display: flex;
      align-items: center;
      justify-content: center;
      flex-shrink: 0;
    }

    .profile-menu-item.danger {
      color: #f87171;
    }

    .profile-menu-item.danger:hover {
      background: rgba(239, 68, 68, .08);
      border-color: rgba(239, 68, 68, .1);
      color: #fca5a5;
    }

    .profile-menu-item.seller-cta {
      background: rgba(245, 158, 11, .06);
      border-color: rgba(245, 158, 11, .15);
      color: #fbbf24;
      margin: 4px 6px;
      width: calc(100% - 12px);
    }

    .profile-menu-item.seller-cta:hover {
      background: rgba(245, 158, 11, .12);
      border-color: rgba(245, 158, 11, .25);
      color: #fcd34d;
    }

    .profile-divider {
      height: 1px;
      background: rgba(255, 255, 255, .05);
      margin: 6px 0;
    }

    .profile-stat-badge {
      font-size: 10px;
      font-weight: 700;
      padding: 2px 7px;
      border-radius: 999px;
      line-height: 1.4;
    }

    /* ── NOTIF DROPDOWN ── */
    .notif-dropdown-panel {
      background: linear-gradient(145deg, #0d1528 0%, #0a1020 100%);
      border: 1px solid rgba(255, 255, 255, .08);
      box-shadow: 0 40px 80px rgba(0, 0, 0, .7);
      border-radius: 20px;
      overflow: hidden;
      animation: dropdownIn .18s cubic-bezier(.16, 1, .3, 1);
    }

    .notif-item {
      display: flex;
      gap: 12px;
      padding: 12px 16px;
      border-bottom: 1px solid rgba(255, 255, 255, .04);
      transition: background .15s ease;
      text-decoration: none;
      cursor: pointer;
    }

    .notif-item:last-child {
      border-bottom: none;
    }

    .notif-item:hover {
      background: rgba(255, 255, 255, .03);
    }

    .notif-item.unread {
      background: rgba(6, 182, 212, .03);
    }

    .notif-item.unread:hover {
      background: rgba(6, 182, 212, .06);
    }

    .notif-icon-wrap {
      width: 38px;
      height: 38px;
      border-radius: 11px;
      display: flex;
      align-items: center;
      justify-content: center;
      flex-shrink: 0;
    }

    .notif-skeleton {
      background: linear-gradient(90deg, rgba(255, 255, 255, .04) 25%, rgba(255, 255, 255, .08) 50%, rgba(255, 255, 255, .04) 75%);
      background-size: 200% 100%;
      animation: shimmer 1.4s infinite;
      border-radius: 6px;
    }

    /* ── ANIMATIONS ── */
    @keyframes dropdownIn {
      from {
        opacity: 0;
        transform: translateY(-8px) scale(.97);
      }

      to {
        opacity: 1;
        transform: translateY(0) scale(1);
      }
    }

    @keyframes shimmer {
      0% {
        background-position: 200% 0;
      }

      100% {
        background-position: -200% 0;
      }
    }

    /* ── MISC ── */
    #main-navbar a,
    #main-navbar button,
    #main-navbar .nav-link {
      color: inherit;
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

    @media (max-width:640px) {
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

    /* Hidden utility used by JS */
    .dd-hidden {
      display: none !important;
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
              class="flex items-center py-2 text-sm font-medium transition-colors {{ request()->routeIs('marketplace.home') ? 'text-cyan-400 font-bold' : 'text-slate-300 hover:text-cyan-400' }}">Beranda</a>
          </li>
          <li><a href="{{ route('marketplace.browse') }}"
              class="flex items-center py-2 text-sm font-medium transition-colors {{ request()->routeIs('marketplace.browse') ? 'text-cyan-400 font-bold' : 'text-slate-300 hover:text-cyan-400' }}">Semua
              Produk</a></li>
          <li><a href="{{ route('marketplace.trending') }}"
              class="flex items-center py-2 text-sm font-medium transition-colors {{ request()->routeIs('marketplace.trending') ? 'text-cyan-400 font-bold' : 'text-slate-300 hover:text-cyan-400' }}">Trending</a>
          </li>
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

      {{-- Right Icons --}}
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

            {{-- ── NOTIFICATIONS ── --}}
            <div class="relative" id="notif-wrapper">
              <button id="notif-btn" onclick="toggleDropdown('notif-dropdown'); loadNotificationPreview();"
                class="nav-icon-btn text-white/90">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                </svg>
                <span id="notif-badge"
                  class="absolute -top-1 -right-1 min-w-[18px] h-[18px] px-1 flex items-center justify-center rounded-full bg-red-500 text-white text-[10px] font-bold dd-hidden">0</span>
              </button>

              {{-- Notif Panel --}}
              <div id="notif-dropdown" data-notifications-url="{{ route('notifications.poll') }}"
                data-notifications-read-all-url="{{ route('notifications.read-all') }}"
                class="notif-dropdown-panel dd-hidden absolute right-0 top-full mt-3 w-[360px] z-[999] text-slate-200">

                {{-- Header --}}
                <div class="flex items-center justify-between px-5 py-4 border-b border-white/[0.06]"
                  style="background: linear-gradient(135deg,rgba(6,182,212,.1) 0%,rgba(129,140,248,.05) 100%)">
                  <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-xl bg-cyan-500/15 flex items-center justify-center">
                      <svg class="w-4 h-4 text-cyan-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                      </svg>
                    </div>
                    <div>
                      <p class="text-sm font-bold text-white">Notifikasi</p>
                      <p id="notif-unread-count" class="text-[11px] text-slate-400">Memuat...</p>
                    </div>
                  </div>
                  <button onclick="markAllNotifsRead()"
                    class="text-[11px] font-semibold text-cyan-400 hover:text-cyan-300 transition px-2 py-1 rounded-lg hover:bg-cyan-500/10">
                    Tandai dibaca
                  </button>
                </div>

                {{-- Body --}}
                <div id="notif-dropdown-body" class="max-h-[340px] overflow-y-auto">
                  {{-- Skeleton loader --}}
                  <div id="notif-skeleton" class="p-3 space-y-2">
                    @for($i = 0; $i < 3; $i++)
                      <div class="flex gap-3 p-3 rounded-xl">
                        <div class="notif-skeleton w-9 h-9 rounded-xl shrink-0"></div>
                        <div class="flex-1 space-y-2 pt-0.5">
                          <div class="notif-skeleton h-3 w-3/4 rounded"></div>
                          <div class="notif-skeleton h-2.5 w-full rounded"></div>
                          <div class="notif-skeleton h-2 w-1/3 rounded"></div>
                        </div>
                      </div>
                    @endfor
                  </div>
                  <div id="notif-list" class="dd-hidden"></div>
                  <div id="notif-empty" class="dd-hidden flex flex-col items-center justify-center py-10 px-4">
                    <div
                      class="w-14 h-14 rounded-2xl bg-white/[0.03] border border-white/[0.06] flex items-center justify-center mb-3">
                      <svg class="w-7 h-7 text-slate-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                          d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                      </svg>
                    </div>
                    <p class="text-sm font-semibold text-slate-400">Belum ada notifikasi</p>
                    <p class="text-xs text-slate-600 mt-1">Kamu sudah up-to-date!</p>
                  </div>
                </div>

                {{-- Footer --}}
                <div class="border-t border-white/[0.05] px-4 py-3">
                  <a href="{{ route('notifications.index') }}"
                    class="flex items-center justify-center gap-2 w-full py-2 rounded-xl text-sm font-semibold text-cyan-400 hover:bg-cyan-500/10 transition">
                    Lihat semua notifikasi
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                  </a>
                </div>
              </div>
            </div>

            {{-- Chat & Cart (non-admin only) --}}
            @if(!$authUser?->isAdmin())
              {{-- Chat --}}
              <div class="relative">
                <a href="{{ route('chat.inbox') }}" class="nav-icon-btn text-white/90">
                  <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a.863.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                  </svg>
                  <span id="chat-badge"
                    class="absolute -top-1 -right-1 min-w-[18px] h-[18px] px-1 flex items-center justify-center rounded-full bg-red-500 text-white text-[10px] font-bold dd-hidden">0</span>
                </a>
              </div>

              {{-- Cart --}}
              <div class="relative" id="cart-wrapper">
                <button onclick="toggleDropdown('cart-dropdown')" class="nav-icon-btn text-white/90 relative">
                  <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                  </svg>
                  @if($cartCount > 0)
                    <span
                      class="absolute -top-1 -right-1 bg-red-500 text-white text-[10px] font-bold min-w-[18px] h-[18px] px-1 flex items-center justify-center rounded-full">
                      {{ $cartCount > 99 ? '99+' : $cartCount }}
                    </span>
                  @endif
                </button>
                <div id="cart-dropdown"
                  class="dropdown-panel dd-hidden absolute right-0 top-full mt-3 w-72 z-[999] text-slate-200 overflow-hidden">
                  <div class="flex items-center justify-between px-4 py-3.5 border-b border-white/[0.06]"
                    style="background:linear-gradient(135deg,rgba(6,182,212,.08) 0%,transparent 100%)">
                    <span class="font-bold text-sm text-white">Keranjang</span>
                    <a href="{{ route('cart.index') }}" class="text-xs text-cyan-400 hover:text-cyan-300 font-semibold">Lihat
                      semua</a>
                  </div>
                  @if($cartItems->isNotEmpty())
                    <div class="max-h-64 overflow-y-auto divide-y divide-white/[0.04]">
                      @foreach($cartItems as $item)
                        <a href="{{ route('cart.index') }}" class="flex gap-3 px-4 py-3 hover:bg-white/[0.03] transition"
                          style="text-decoration:none">
                          <img src="{{ $item->product?->image_url }}" alt="" class="w-10 h-10 object-cover rounded-lg bg-white/5">
                          <div class="flex-1 min-w-0">
                            <div class="text-sm text-slate-200 font-medium truncate">{{ $item->product?->name ?? 'Produk' }}</div>
                            <div class="text-xs text-slate-400 mt-0.5">{{ $item->quantity }} × Rp
                              {{ number_format((float) ($item->product?->price ?? 0), 0, ',', '.') }}</div>
                          </div>
                        </a>
                      @endforeach
                    </div>
                    <div class="p-3">
                      <a href="{{ route('cart.index') }}"
                        class="flex items-center justify-center w-full py-2.5 rounded-xl bg-cyan-500/15 text-cyan-400 text-sm font-bold hover:bg-cyan-500/25 transition">
                        Checkout Sekarang
                      </a>
                    </div>
                  @else
                    <div class="flex flex-col items-center py-10 px-4">
                      <div
                        class="w-12 h-12 rounded-2xl bg-white/[0.03] border border-white/[0.06] flex items-center justify-center mb-3">
                        <svg class="w-6 h-6 text-slate-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                      </div>
                      <p class="text-sm font-semibold text-slate-400">Keranjang kosong</p>
                      <p class="text-xs text-slate-600 mt-1">Yuk mulai belanja!</p>
                    </div>
                  @endif
                </div>
              </div>
            @endif
          @endif

          {{-- Admin quick links --}}
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

          <div class="h-6 w-px bg-white/[0.06] mx-1 hidden sm:block"></div>

          {{-- ── USER PROFILE DROPDOWN ── --}}
          <div class="relative hidden sm:block" id="user-dd-wrapper">
            <button id="user-dd-btn" onclick="toggleDropdown('user-dropdown')"
              class="flex items-center gap-2.5 rounded-2xl border border-white/[0.08] bg-white/[0.03] px-3 py-2 hover:bg-white/[0.07] hover:border-cyan-500/20 transition-all duration-200 group">
              <div class="relative">
                <img src="{{ $authUser?->avatar_url ?? $avatarFallback }}"
                  class="w-8 h-8 rounded-xl object-cover ring-2 ring-cyan-500/20 group-hover:ring-cyan-500/40 transition-all"
                  alt="Avatar">
                <span
                  class="absolute -bottom-0.5 -right-0.5 w-2.5 h-2.5 bg-emerald-400 rounded-full border-2 border-[#0d1528]"></span>
              </div>
              <div class="text-left hidden lg:block">
                <p class="text-[13px] font-bold text-white leading-tight truncate max-w-[100px]">
                  {{ $authUser?->name ?? 'User' }}</p>
                <p class="text-[10px] text-slate-400 leading-tight">
                  {{ $authUser?->isAdmin() ? 'Administrator' : ($authUser?->isSellerAccount() ? 'Seller' : 'Member') }}
                </p>
              </div>
              <svg class="w-3.5 h-3.5 text-slate-400 group-hover:text-cyan-400 transition-colors ml-0.5" fill="none"
                viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7" />
              </svg>
            </button>

            {{-- Dropdown Panel --}}
            <div id="user-dropdown" class="profile-dropdown dd-hidden absolute right-0 top-full mt-3 w-[260px] z-[999]">

              {{-- Profile Header --}}
              <div class="profile-dropdown-header">
                <div class="flex items-center gap-3 relative z-10">
                  <div class="relative">
                    <img src="{{ $authUser?->avatar_url ?? $avatarFallback }}"
                      class="w-11 h-11 rounded-2xl object-cover ring-2 ring-cyan-500/30" alt="Avatar">
                    <span
                      class="absolute -bottom-0.5 -right-0.5 w-3 h-3 bg-emerald-400 rounded-full border-2 border-[#0d1528]"></span>
                  </div>
                  <div class="flex-1 min-w-0">
                    <p class="text-sm font-black text-white truncate">{{ $authUser->name }}</p>
                    <p class="text-[11px] text-slate-400 truncate mt-0.5">{{ $authUser->email }}</p>
                    <span
                      class="inline-flex items-center mt-1.5 px-2 py-0.5 rounded-full text-[10px] font-bold
                        {{ $authUser->isAdmin() ? 'bg-purple-500/15 text-purple-400' : ($authUser->isSellerAccount() ? 'bg-emerald-500/15 text-emerald-400' : 'bg-cyan-500/15 text-cyan-400') }}">
                      {{ $authUser->isAdmin() ? '⚡ Admin' : ($authUser->isSellerAccount() ? '🏪 Seller' : '👤 Member') }}
                    </span>
                  </div>
                </div>
              </div>

              {{-- Menu Items --}}
              <div class="py-2">
                @if(($isAdminRoute || $isAdminSettingsRoute) && $authUser->isAdmin())
                  <a href="{{ route('admin.dashboard') }}" class="profile-menu-item">
                    <span class="menu-icon bg-purple-500/10">
                      <svg class="w-4 h-4 text-purple-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                      </svg>
                    </span>
                    Panel Admin
                  </a>
                  <a href="{{ route('admin.contact-messages.index') }}" class="profile-menu-item">
                    <span class="menu-icon bg-blue-500/10">
                      <svg class="w-4 h-4 text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                      </svg>
                    </span>
                    Pesan Masuk
                  </a>
                  <a href="{{ route('admin.users.index') }}" class="profile-menu-item">
                    <span class="menu-icon bg-cyan-500/10">
                      <svg class="w-4 h-4 text-cyan-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                      </svg>
                    </span>
                    Kelola Akun
                  </a>
                @else
                  <a href="{{ route('dashboard') }}" class="profile-menu-item">
                    <span class="menu-icon bg-cyan-500/10">
                      <svg class="w-4 h-4 text-cyan-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zm10 0a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zm10 0a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                      </svg>
                    </span>
                    Dashboard
                  </a>
                  <a href="{{ route('orders.index') }}" class="profile-menu-item">
                    <span class="menu-icon bg-indigo-500/10">
                      <svg class="w-4 h-4 text-indigo-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                      </svg>
                    </span>
                    Pesanan Saya
                  </a>
                  <a href="{{ route('wallet.index') }}" class="profile-menu-item">
                    <span class="menu-icon bg-emerald-500/10">
                      <svg class="w-4 h-4 text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                      </svg>
                    </span>
                    Wallet Digital
                  </a>

                  @if($authUser->isSellerAccount())
                    <a href="{{ route('seller.dashboard') }}" class="profile-menu-item">
                      <span class="menu-icon bg-amber-500/10">
                        <svg class="w-4 h-4 text-amber-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                        </svg>
                      </span>
                      Dashboard Penjual
                    </a>
                  @else
                    <a href="{{ route('seller.register.form') }}" class="profile-menu-item seller-cta">
                      <span class="menu-icon bg-amber-500/10">
                        <svg class="w-4 h-4 text-amber-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                      </span>
                      Daftar Jadi Penjual
                      <span
                        class="ml-auto text-[9px] font-black bg-amber-500/20 text-amber-300 px-1.5 py-0.5 rounded-full">GRATIS</span>
                    </a>
                  @endif
                @endif

                <div class="profile-divider mx-2 my-1"></div>

                {{-- Settings --}}
                @if(Route::has('settings.account'))
                  <a href="{{ route('settings.account') }}" class="profile-menu-item">
                @else
                    <a href="{{ url('/settings/account') }}" class="profile-menu-item">
                  @endif
                    <span class="menu-icon bg-slate-500/10">
                      <svg class="w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                      </svg>
                    </span>
                    Pengaturan Akun
                  </a>

                  <div class="profile-divider mx-2 my-1"></div>

                  {{-- Logout --}}
                  <form method="POST" action="{{ route('logout') }}" class="px-1">
                    @csrf
                    <button type="submit" class="profile-menu-item danger w-full text-left">
                      <span class="menu-icon bg-red-500/10">
                        <svg class="w-4 h-4 text-red-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                        </svg>
                      </span>
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

  {{-- Category Bar --}}
  @if(!$isAdminRoute)
    <div class="navbar-categories h-12 hidden lg:block border-t border-white/5 shadow-sm">
      <div class="max-w-7xl mx-auto px-4 h-full flex items-center gap-1">

        {{-- Kategori Mega Menu --}}
        <div class="relative h-full flex items-center group">
          <button
            class="flex items-center gap-2 text-white font-bold text-sm px-4 py-1.5 rounded-xl hover:bg-white/5 transition h-full">
            <svg class="w-4 h-4 text-cyan-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7" />
            </svg>
            Kategori
          </button>
          <div
            class="absolute left-0 top-full w-[850px] bg-[#0B1220] rounded-2xl shadow-[0_30px_80px_rgba(0,0,0,0.9)] border border-white/10 hidden group-hover:flex z-[999] min-h-[380px] max-h-[75vh] overflow-hidden transition-all duration-300">
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

        <a href="{{ route('marketplace.home') }}"
          class="hidden lg:flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-semibold transition-all duration-300
              {{ request()->routeIs('marketplace.home') ? 'text-cyan-400 bg-cyan-500/10 border border-cyan-400/20 shadow-[0_0_14px_rgba(6,182,212,0.15)]' : 'text-white/80 hover:text-white hover:bg-cyan-500/10' }}">
          <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M3 10l9-7 9 7v9a2 2 0 01-2 2h-4a2 2 0 01-2-2V13H9v6a2 2 0 01-2 2H3z" />
          </svg>
          Beranda
        </a>

        <div class="flex-1 overflow-x-auto no-scrollbar mask-gradient-right">
          <div class="flex items-center gap-2 min-w-max">
            @php
              $currentCat = request('category', '');
              $navLinkBase = 'nav-modern-link px-4 py-2 text-sm font-medium rounded-xl transition-all duration-300 whitespace-nowrap';
              $navActive = 'text-cyan-400 bg-cyan-500/10 border border-cyan-400/20';
              $navInactive = 'text-white/75 hover:text-white hover:bg-white/[0.04]';
            @endphp
            <a href="{{ route('products.search', ['category' => 'top-up-game']) }}"
              class="{{ $navLinkBase }} flex items-center gap-2 {{ $currentCat === 'top-up-game' ? $navActive : $navInactive }}">
              <span class="px-2 py-0.5 rounded-full bg-cyan-400/20 text-cyan-200 text-[10px] font-bold">HOT</span>
              Top Up Game
            </a>
            <a href="{{ route('products.search', ['category' => 'akun']) }}"
              class="{{ $navLinkBase }} {{ $currentCat === 'akun' ? $navActive : $navInactive }}">Akun Game</a>
            <a href="{{ route('products.search', ['category' => 'voucher']) }}"
              class="{{ $navLinkBase }} {{ $currentCat === 'voucher' ? $navActive : $navInactive }}">Voucher
              Game</a>
            <a href="{{ route('products.search', ['category' => 'roblox']) }}"
              class="{{ $navLinkBase }} {{ $currentCat === 'roblox' ? $navActive : $navInactive }}">Roblox
              Games</a>
            <a href="{{ route('products.search', ['category' => 'growtopia']) }}"
              class="{{ $navLinkBase }} {{ $currentCat === 'growtopia' ? $navActive : $navInactive }}">Growtopia</a>
            <a href="{{ route('products.search', ['category' => 'genshin-impact']) }}"
              class="{{ $navLinkBase }} {{ $currentCat === 'genshin-impact' ? $navActive : $navInactive }}">Genshin
              Impact</a>
            <a href="{{ route('products.search', ['category' => 'dota-2']) }}"
              class="{{ $navLinkBase }} {{ $currentCat === 'dota-2' ? $navActive : $navInactive }}">Dota 2
              Item</a>
            <a href="{{ route('products.search', ['category' => 'game-key']) }}"
              class="{{ $navLinkBase }} {{ $currentCat === 'game-key' ? $navActive : $navInactive }}">Game Key</a>
            <a href="{{ route('products.search', ['category' => 'mobile-legends']) }}"
              class="{{ $navLinkBase }} {{ $currentCat === 'mobile-legends' ? $navActive : $navInactive }}">Mobile Legend
              Account</a>
          </div>
        </div>
      </div>
    </div>
  @endif
</header>

@push('scripts')
  <script>
    // ════════════════════════════════════════════
    //  DROPDOWN SYSTEM
    // ════════════════════════════════════════════
    const DD_IDS = ['user-dropdown', 'notif-dropdown', 'cart-dropdown'];

    function toggleDropdown(id) {
      const el = document.getElementById(id);
      if (!el) return;
      const isHidden = el.classList.contains('dd-hidden');
      closeAllDropdowns();
      if (isHidden) el.classList.remove('dd-hidden');
    }

    function closeAllDropdowns() {
      DD_IDS.forEach(id => {
        const el = document.getElementById(id);
        if (el) el.classList.add('dd-hidden');
      });
    }

    // Close on outside click
    document.addEventListener('click', function (e) {
      const insideWrapper = e.target.closest('#user-dd-wrapper, #notif-wrapper, #cart-wrapper');
      if (!insideWrapper) closeAllDropdowns();
    });

    // Close on ESC
    document.addEventListener('keydown', e => {
      if (e.key === 'Escape') closeAllDropdowns();
    });

    // ════════════════════════════════════════════
    //  MOBILE DRAWER
    // ════════════════════════════════════════════
    function openDrawer() {
      const d = document.getElementById('mobile-drawer');
      if (!d) return;
      d.classList.remove('-translate-x-full');
      d.classList.add('translate-x-0');
      document.body.style.overflow = 'hidden';
    }
    function closeDrawer() {
      const d = document.getElementById('mobile-drawer');
      if (!d) return;
      d.classList.add('-translate-x-full');
      d.classList.remove('translate-x-0');
      document.body.style.overflow = '';
    }
    document.addEventListener('click', function (e) {
      const drawer = document.getElementById('mobile-drawer');
      if (!drawer) return;
      if (drawer.classList.contains('translate-x-0')
        && !drawer.contains(e.target)
        && !e.target.closest('button[onclick="openDrawer()"]')) {
        closeDrawer();
      }
    });

    // ════════════════════════════════════════════
    //  NOTIFICATIONS
    // ════════════════════════════════════════════
    let notifLoaded = false;

    function loadNotificationPreview() {
      if (notifLoaded) return;

      const panel = document.getElementById('notif-dropdown');
      const skeleton = document.getElementById('notif-skeleton');
      const list = document.getElementById('notif-list');
      const empty = document.getElementById('notif-empty');
      const badge = document.getElementById('notif-badge');
      const counter = document.getElementById('notif-unread-count');
      const url = panel?.dataset.notificationsUrl;

      if (!url) {
        if (skeleton) skeleton.classList.add('dd-hidden');
        if (empty) empty.classList.remove('dd-hidden');
        return;
      }

      fetch(url, {
        headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
      })
        .then(r => r.json())
        .then(data => {
          notifLoaded = true;
          const notifs = data.notifications ?? data.data ?? [];
          const unread = notifs.filter(n => !n.read_at).length;

          // Badge
          if (badge) {
            if (unread > 0) {
              badge.textContent = unread > 9 ? '9+' : unread;
              badge.classList.remove('dd-hidden');
            } else {
              badge.classList.add('dd-hidden');
            }
          }

          // Counter text
          if (counter) {
            counter.textContent = unread > 0 ? `${unread} belum dibaca` : 'Semua sudah dibaca';
          }

          if (skeleton) skeleton.classList.add('dd-hidden');

          if (notifs.length === 0) {
            if (empty) empty.classList.remove('dd-hidden');
            return;
          }

          // Icon map by type
          const iconMap = {
            order: { bg: 'bg-indigo-500/15', color: 'text-indigo-400', path: 'M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z' },
            payment: { bg: 'bg-emerald-500/15', color: 'text-emerald-400', path: 'M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z' },
            promo: { bg: 'bg-amber-500/15', color: 'text-amber-400', path: 'M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z' },
            default: { bg: 'bg-cyan-500/15', color: 'text-cyan-400', path: 'M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9' },
          };

          list.innerHTML = notifs.slice(0, 6).map(n => {
            const type = n.type ?? 'default';
            const icon = iconMap[type] ?? iconMap.default;
            const isUnread = !n.read_at;
            const time = n.created_at ? new Date(n.created_at).toLocaleDateString('id-ID', { day: 'numeric', month: 'short', hour: '2-digit', minute: '2-digit' }) : '';

            return `
          <a href="${n.url ?? '#'}" class="notif-item ${isUnread ? 'unread' : ''}" style="text-decoration:none">
            <div class="notif-icon-wrap ${icon.bg} shrink-0">
              <svg class="w-4 h-4 ${icon.color}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="${icon.path}"/>
              </svg>
            </div>
            <div class="flex-1 min-w-0">
              <div class="flex items-start justify-between gap-2">
                <p class="text-[13px] font-semibold text-slate-200 leading-snug line-clamp-1">${n.title ?? 'Notifikasi'}</p>
                ${isUnread ? '<span class="w-2 h-2 rounded-full bg-cyan-400 shrink-0 mt-1.5"></span>' : ''}
              </div>
              <p class="text-xs text-slate-400 mt-0.5 line-clamp-2 leading-relaxed">${n.message ?? n.body ?? ''}</p>
              <p class="text-[10px] text-slate-600 mt-1">${time}</p>
            </div>
          </a>
        `;
          }).join('');

          if (list) list.classList.remove('dd-hidden');
        })
        .catch(() => {
          notifLoaded = false;
          if (skeleton) skeleton.classList.add('dd-hidden');
          if (list) {
            list.innerHTML = `
          <div class="flex flex-col items-center py-8 px-4">
            <p class="text-sm font-semibold text-red-400">Gagal memuat notifikasi</p>
            <button onclick="notifLoaded=false; loadNotificationPreview()" class="mt-2 text-xs text-cyan-400 hover:underline">Coba lagi</button>
          </div>`;
            list.classList.remove('dd-hidden');
          }
        });
    }

    function markAllNotifsRead() {
      const panel = document.getElementById('notif-dropdown');
      const url = panel?.dataset.notificationsReadAllUrl;
      if (!url) return;

      fetch(url, {
        method: 'POST',
        headers: {
          'X-Requested-With': 'XMLHttpRequest',
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content ?? '',
          'Accept': 'application/json',
        }
      }).then(() => {
        // Hapus dot unread di semua item
        document.querySelectorAll('#notif-list .notif-item.unread').forEach(el => {
          el.classList.remove('unread');
          el.querySelector('.rounded-full.bg-cyan-400')?.remove();
        });
        const badge = document.getElementById('notif-badge');
        const counter = document.getElementById('notif-unread-count');
        if (badge) badge.classList.add('dd-hidden');
        if (counter) counter.textContent = 'Semua sudah dibaca';
      });
    }

    // ════════════════════════════════════════════
    //  THEME
    // ════════════════════════════════════════════
    function getCurrentTheme() {
      return document.documentElement.getAttribute('data-theme')
        || (window.matchMedia?.('(prefers-color-scheme: dark)').matches ? 'dark' : 'light');
    }
    function applyTheme(t) {
      document.documentElement.setAttribute('data-theme', t);
      try { localStorage.setItem('site-theme', t); } catch (e) { }
      updateThemeIcon(t);
    }
    function initTheme() {
      try {
        const saved = localStorage.getItem('site-theme');
        const theme = saved || (window.matchMedia?.('(prefers-color-scheme: dark)').matches ? 'dark' : 'light');
        applyTheme(theme);
      } catch (e) { applyTheme(getCurrentTheme()); }
    }
    function toggleTheme() {
      applyTheme(getCurrentTheme() === 'dark' ? 'light' : 'dark');
    }
    function updateThemeIcon(t) {
      const icon = document.getElementById('themeIcon');
      if (!icon) return;
      icon.innerHTML = t === 'dark'
        ? '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12.79A9 9 0 1111.21 3 7 7 0 0021 12.79z"/>'
        : '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v2m0 14v2m9-9h-2M5 12H3m15.364-6.364l-1.414 1.414M7.05 16.95l-1.414 1.414M16.95 16.95l1.414 1.414M7.05 7.05L5.636 5.636M12 8a4 4 0 100 8 4 4 0 000-8z"/>';
    }

    // ════════════════════════════════════════════
    //  SCROLL EFFECT
    // ════════════════════════════════════════════
    document.addEventListener('DOMContentLoaded', function () {
      initTheme();
      const navbar = document.getElementById('main-navbar');
      if (!navbar) return;
      window.addEventListener('scroll', () => {
        navbar.style.boxShadow = window.scrollY > 12
          ? '0 14px 45px rgba(0,0,0,.38)'
          : '';
      });
    });
  </script>
@endpush
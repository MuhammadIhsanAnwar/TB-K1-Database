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
    /* MAIN NAVBAR GLASS EFFECT */
    .navbar-container {
      background: linear-gradient(180deg, rgba(6, 10, 25, 0.95), rgba(6, 10, 25, 0.85));
      backdrop-filter: blur(24px);
      border-bottom: 1px solid rgba(255, 255, 255, 0.05);
      transition: box-shadow 0.3s ease;
    }

    /* NAV PILL LINKS (Clean, no messy underlines) */
    .nav-modern-link {
      transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .nav-modern-link:hover {
      transform: translateY(-1px);
    }

    /* ACTIVE state for Beranda */
    .nav-home-link.is-active {
      color: #22d3ee !important;
      background: rgba(6, 182, 212, 0.12) !important;
      box-shadow: 0 0 16px rgba(6, 182, 212, 0.15);
    }

    /* ICON BUTTON */
    .nav-icon-btn {
      position: relative;
      width: 42px;
      height: 42px;
      border-radius: 12px;
      display: flex;
      align-items: center;
      justify-content: center;
      background: rgba(255, 255, 255, 0.03);
      border: 1px solid rgba(255, 255, 255, 0.05);
      transition: all .25s ease;
      color: #e2e8f0;
    }

    .nav-icon-btn:hover {
      background: rgba(6, 182, 212, 0.12);
      border-color: rgba(6, 182, 212, 0.3);
      color: #22d3ee;
      transform: translateY(-2px);
      box-shadow: 0 10px 25px rgba(6, 182, 212, 0.2);
    }

    /* DROPDOWN PANELS */
    .dropdown-panel {
      backdrop-filter: blur(24px);
      background: rgba(15, 23, 42, 0.96);
      border: 1px solid rgba(255, 255, 255, 0.08);
      box-shadow: 0 30px 60px rgba(0, 0, 0, 0.5);
      border-radius: 20px;
    }

    .navbar-top {
      background: rgba(15, 23, 42, 0.98);
      color: var(--text);
    }

    .navbar-categories {
      background: rgba(11, 18, 32, 0.85);
      backdrop-filter: blur(24px);
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
      background: rgba(11, 18, 32, 0.98);
      color: var(--text);
    }

    .navbar-container .surface-weak {
      background: rgba(255, 255, 255, 0.03);
    }

    /* RESPONSIVE TWEAKS */
    @media (max-width: 640px) {
      #main-navbar .navbar-container {
        height: 60px;
      }

      #main-navbar .h-20 {
        height: 60px;
      }
    }

    /* PREMIUM SCROLLBAR & MASKING EFFECT */
    .mask-gradient-right {
      scroll-behavior: smooth;
      -webkit-mask-image: linear-gradient(to right, black 85%, transparent 100%);
      mask-image: linear-gradient(to right, black 85%, transparent 100%);
      padding-bottom: 6px;
      /* Memberi ruang agar scrollbar tidak menempel ke teks */
    }

    .premium-scrollbar::-webkit-scrollbar {
      height: 3px;
      /* Sangat tipis ala MacOS */
    }

    .premium-scrollbar::-webkit-scrollbar-track {
      background: transparent;
    }

    .premium-scrollbar::-webkit-scrollbar-thumb {
      background: rgba(6, 182, 212, 0.2);
      border-radius: 10px;
    }

    .premium-scrollbar::-webkit-scrollbar-thumb:hover {
      background: rgba(6, 182, 212, 0.6);
    }
  </style>
@endpush

{{-- ═══ MOBILE SIDEBAR DRAWER ═══ --}}
<aside id="mobile-drawer"
  class="fixed top-0 left-0 h-full w-72 z-50 flex flex-col overflow-y-auto text-slate-200 transition-transform -translate-x-full backdrop-blur-2xl border-r border-white/5 {{ ($isAdminRoute || $isAdminSettingsRoute) ? 'admin-navbar' : '' }}">
  <div class="flex items-center justify-between p-4 border-b border-white/5 navbar-top">
    <a href="{{ route('marketplace.home') }}" class="flex items-center gap-2.5 group">
      <div class="p-1.5 rounded-xl bg-white/5 border border-white/10 group-hover:border-cyan-500/30 transition">
        <img src="{{ url('storage/app/public/logo/logo.png') }}" alt="Logo" class="w-7 h-7 object-contain">
      </div>
      <span class="font-black text-white text-lg tracking-wide">{{ config('app.name', 'Lapak Gaming') }}</span>
    </a>
    <button onclick="closeDrawer()" class="text-slate-400 hover:text-white transition">
      <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
      </svg>
    </button>
  </div>

  @auth
    <div class="p-4 border-b border-white/5 bg-transparent">
      <div class="flex items-center gap-3">
        <img src="{{ $authUser?->avatar_url ?? $avatarFallback }}" alt="Avatar"
          class="w-10 h-10 rounded-full object-cover border border-blue-500/30 shrink-0">
        <div class="flex-1 min-w-0">
          <div class="text-sm font-bold text-white truncate">{{ $authUser?->name ?? 'User' }}</div>
          <div class="text-xs text-slate-400 truncate">{{ $authUser?->email ?? '' }}</div>
        </div>
      </div>
    </div>
  @else
    <div class="p-4 border-b border-white/5 flex gap-2">
      <a href="{{ route('login') }}"
        class="flex-1 py-2.5 rounded-xl bg-white/5 border border-white/10 text-sm font-bold text-white text-center hover:bg-white/10 transition">Masuk</a>
      <a href="{{ route('register') }}"
        class="flex-1 py-2.5 rounded-xl bg-blue-600 border border-blue-500/50 text-white text-sm font-bold text-center hover:bg-blue-500 transition shadow-[0_0_15px_rgba(37,99,235,0.2)]">Daftar</a>
    </div>
  @endauth

  <div class="p-4 border-b border-white/5">
    <form action="{{ route('products.search') }}" method="GET">
      <div class="relative">
        <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-500" fill="none" viewBox="0 0 24 24"
          stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
        </svg>
        <input type="text" name="q" placeholder="Cari Game, Item..."
          class="w-full rounded-xl border border-white/10 bg-black/40 pl-10 pr-4 py-2.5 text-sm text-white placeholder:text-slate-500 outline-none focus:border-cyan-500/50" />
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
              class="flex items-center py-2.5 px-3 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('marketplace.home') ? 'text-cyan-400 bg-white/5 font-bold' : 'text-slate-300 hover:text-cyan-400 hover:bg-white/5' }}">Beranda</a>
          </li>
          <li><a href="{{ route('marketplace.browse') }}"
              class="flex items-center py-2.5 px-3 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('marketplace.browse') ? 'text-cyan-400 bg-white/5 font-bold' : 'text-slate-300 hover:text-cyan-400 hover:bg-white/5' }}">Semua
              Produk</a></li>
          <li><a href="{{ route('marketplace.trending') }}"
              class="flex items-center py-2.5 px-3 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('marketplace.trending') ? 'text-cyan-400 bg-white/5 font-bold' : 'text-slate-300 hover:text-cyan-400 hover:bg-white/5' }}">Trending</a>
          </li>
        </ul>
      </div>

      @auth
        <div>
          <p class="text-[10px] font-bold text-slate-500 uppercase tracking-widest px-3 mb-2">Akun Saya</p>
          <ul class="space-y-1">
            @if($authUser?->isAdmin())
              <li><a href="{{ route('admin.dashboard') }}"
                  class="flex items-center py-2.5 px-3 rounded-lg text-sm text-slate-300 hover:bg-white/5 hover:text-white transition">Panel
                  Admin</a></li>
              <li><a href="{{ route('admin.orders.index') }}"
                  class="flex items-center py-2.5 px-3 rounded-lg text-sm text-slate-300 hover:bg-white/5 hover:text-white transition">Transaksi</a>
              </li>
            @else
              <li><a href="{{ route('dashboard') }}"
                  class="flex items-center py-2.5 px-3 rounded-lg text-sm text-slate-300 hover:bg-white/5 hover:text-white transition">Dashboard</a>
              </li>
              <li><a href="{{ route('orders.index') }}"
                  class="flex items-center py-2.5 px-3 rounded-lg text-sm text-slate-300 hover:bg-white/5 hover:text-white transition">Pesanan
                  Saya</a></li>
              <li><a href="{{ route('wallet.index') }}"
                  class="flex items-center py-2.5 px-3 rounded-lg text-sm text-slate-300 hover:bg-white/5 hover:text-white transition">Wallet
                  Digital</a></li>
            @endif
            <li>
              <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                  class="w-full text-left py-2.5 px-3 rounded-lg text-sm font-bold text-rose-400 hover:bg-rose-500/10 transition">Keluar</button>
              </form>
            </li>
          </ul>
        </div>
      @endauth
    @endif
  </nav>
</aside>

{{-- ═══ DESKTOP NAVBAR (FIXED -> STICKY) ═══ --}}
<header id="main-navbar"
  class="sticky top-0 z-40 w-full font-sans transition-all duration-300 {{ ($isAdminRoute || $isAdminSettingsRoute) ? 'admin-navbar' : '' }}">

  {{-- TOP MAIN BAR --}}
  <div class="navbar-container h-20 flex flex-col justify-center" id="nav-container">
    <div class="max-w-7xl mx-auto px-4 w-full flex items-center gap-6">

      {{-- Mobile Menu & Logo --}}
      <div class="flex items-center gap-3">
        <button onclick="openDrawer()" class="lg:hidden text-white hover:text-cyan-400 transition">
          <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
          </svg>
        </button>
        <a href="{{ route('marketplace.home') }}" class="flex items-center gap-2.5 shrink-0 group">
          <div class="p-1.5 rounded-xl bg-white/5 border border-white/10 group-hover:border-cyan-500/30 transition">
            <img src="{{ url('storage/app/public/logo/logo.png') }}" alt="Logo" class="h-7 w-auto object-contain">
          </div>
          <span
            class="font-black text-white text-xl tracking-tight hidden sm:block">{{ config('app.name', 'Lapak Gaming') }}</span>
        </a>
      </div>

      {{-- Search Bar --}}
      @if(!$isAdminRoute && !$isAdminSettingsRoute)
        <div class="hidden md:flex flex-1 max-w-2xl mx-auto relative">
          <form action="{{ route('products.search') }}" method="GET" class="w-full">
            <div
              class="flex items-center gap-2 rounded-2xl border border-white/10 bg-black/30 px-3 py-2 shadow-inner transition-all duration-300 focus-within:border-cyan-500/40 focus-within:bg-black/50 focus-within:shadow-[0_0_25px_rgba(6,182,212,0.15)]">
              <svg class="w-5 h-5 text-slate-400 ml-2 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
              </svg>
              <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari Game, Top Up, Voucher, Akun..."
                class="w-full bg-transparent border-none outline-none px-2 py-1.5 text-sm text-white placeholder:text-slate-500" />
              <button type="submit"
                class="h-9 px-4 rounded-xl bg-blue-600 hover:bg-blue-500 text-white text-sm font-bold transition-colors">Cari</button>
            </div>
          </form>
        </div>
      @endif

      {{-- Right Icons (Auth, Chat, Cart) --}}
      <div class="flex items-center gap-3 shrink-0 ml-auto">

        @if($authUser)
          @if(!$isAdminRoute && !$isAdminSettingsRoute)
            {{-- Search Mobile --}}
            <a href="{{ route('products.search') }}" class="md:hidden nav-icon-btn">
              <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
              </svg>
            </a>

            {{-- Notifications --}}
            <div class="relative">
              <button onclick="toggleDropdown('notif-dropdown'); loadNotificationPreview();" class="nav-icon-btn">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                </svg>
                <span id="notif-badge"
                  class="absolute top-0 right-0 w-2.5 h-2.5 bg-rose-500 rounded-full border-2 border-[#0B1220] hidden"></span>
              </button>
              <div id="notif-dropdown" data-notifications-url="{{ route('notifications.poll') }}"
                data-notifications-read-base-url="{{ route('notifications.index') }}"
                data-notifications-read-all-url="{{ route('notifications.read-all') }}"
                class="dropdown-panel absolute right-0 top-full mt-4 w-80 hidden z-50">
                <div class="px-4 py-3 border-b border-white/10 flex justify-between items-center">
                  <span class="font-bold text-white">Notifikasi</span>
                  <a href="{{ route('notifications.index') }}" class="text-xs text-cyan-400 hover:text-cyan-300">Lihat
                    semua</a>
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
                <a href="{{ route('chat.inbox') }}" class="nav-icon-btn">
                  <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a.863.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                  </svg>
                  <span id="chat-badge"
                    class="absolute -top-1.5 -right-1.5 bg-rose-500 text-white text-[9px] font-black px-1.5 py-0.5 rounded-full border-2 border-[#0B1220] hidden">0</span>
                </a>
              </div>

              {{-- Cart --}}
              <div class="relative">
                <button onclick="toggleDropdown('cart-dropdown')" class="nav-icon-btn">
                  <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                  </svg>
                  @if($cartCount > 0)
                    <span
                      class="absolute -top-1.5 -right-1.5 bg-rose-500 text-white text-[9px] font-black px-1.5 py-0.5 rounded-full border-2 border-[#0B1220]">{{ $cartCount > 99 ? '99+' : $cartCount }}</span>
                  @endif
                </button>
                <div id="cart-dropdown" class="dropdown-panel absolute right-0 top-full mt-4 w-80 hidden z-50">
                  <div class="px-4 py-3 border-b border-white/10 flex justify-between items-center">
                    <span class="font-bold text-white">Keranjang Belanja</span>
                    <a href="{{ route('cart.index') }}" class="text-xs text-cyan-400 hover:text-cyan-300">Lihat semua</a>
                  </div>
                  @if($cartItems->isNotEmpty())
                    <div class="max-h-72 overflow-y-auto divide-y divide-white/5">
                      @foreach($cartItems as $item)
                        <a href="{{ route('cart.index') }}" class="flex gap-3 px-4 py-3 hover:bg-white/5 transition">
                          <img src="{{ $item->product?->image_url }}" alt=""
                            class="w-12 h-12 object-cover rounded-lg bg-black/50">
                          <div class="flex-1 min-w-0">
                            <div class="text-sm text-white font-semibold truncate">{{ $item->product?->name ?? 'Produk' }}</div>
                            <div class="text-xs text-cyan-400 mt-1 font-bold">{{ $item->quantity }} x Rp
                              {{ number_format((float) ($item->product?->price ?? 0), 0, ',', '.') }}</div>
                          </div>
                        </a>
                      @endforeach
                    </div>
                  @else
                    <div class="p-8 text-center text-sm text-slate-500">Keranjang masih kosong.</div>
                  @endif
                </div>
              </div>
            @endif
          @endif

          {{-- If on admin routes, show admin quick links --}}
          @if($isAdminRoute && $authUser?->isAdmin())
            <div class="hidden xl:flex items-center gap-2 flex-wrap justify-end">
              <a href="{{ route('admin.dashboard') }}"
                class="rounded-full border border-white/10 bg-white/5 px-3 py-1.5 text-xs font-semibold text-white transition hover:bg-white/10">Dashboard</a>
              <a href="{{ route('admin.orders.index') }}"
                class="rounded-full border border-white/10 bg-white/5 px-3 py-1.5 text-xs font-semibold text-white transition hover:bg-white/10">Transaksi</a>
            </div>
          @endif
          <div class="h-6 w-px bg-white/10 mx-1 hidden sm:block"></div>

          {{-- User Avatar Dropdown --}}
          <div class="relative hidden sm:block">
            <button onclick="toggleDropdown('user-dropdown')"
              class="flex items-center gap-2.5 rounded-full border border-white/10 bg-black/20 pl-2 pr-3 py-1.5 hover:bg-white/5 transition-colors">
              <img src="{{ $authUser?->avatar_url ?? $avatarFallback }}"
                class="w-7 h-7 rounded-full object-cover border border-blue-500/30" alt="Avatar">
              <span class="text-sm font-bold text-white truncate max-w-[120px]">{{ $authUser?->name ?? 'User' }}</span>
              <svg class="w-3.5 h-3.5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
              </svg>
            </button>
            <div id="user-dropdown" class="dropdown-panel absolute right-0 top-full mt-4 w-56 hidden z-50 py-2">
              <div class="px-4 py-3 border-b border-white/10 mb-1">
                <p class="text-sm font-black text-white truncate">{{ $authUser->name }}</p>
                <p class="text-xs text-slate-400 truncate">{{ $authUser->email }}</p>
              </div>

              <div class="px-2 space-y-0.5">
                @if(($isAdminRoute || $isAdminSettingsRoute) && $authUser->isAdmin())
                  <a href="{{ route('admin.dashboard') }}"
                    class="block px-3 py-2 rounded-lg text-sm text-slate-200 hover:bg-white/10 hover:text-white">Panel
                    Admin</a>
                  <a href="{{ route('admin.users.index') }}"
                    class="block px-3 py-2 rounded-lg text-sm text-slate-200 hover:bg-white/10 hover:text-white">Kelola
                    Akun</a>
                @else
                  <a href="{{ route('dashboard') }}"
                    class="block px-3 py-2 rounded-lg text-sm text-slate-200 hover:bg-white/10 hover:text-white">Dashboard</a>
                  <a href="{{ route('orders.index') }}"
                    class="block px-3 py-2 rounded-lg text-sm text-slate-200 hover:bg-white/10 hover:text-white">Pesanan
                    Saya</a>
                  <a href="{{ route('wallet.index') }}"
                    class="block px-3 py-2 rounded-lg text-sm text-slate-200 hover:bg-white/10 hover:text-white">Wallet
                    Digital</a>
                  @if($authUser->isSellerAccount())
                    <a href="{{ route('seller.dashboard') }}"
                      class="block px-3 py-2 rounded-lg text-sm text-slate-200 hover:bg-white/10 hover:text-white">Dashboard
                      Penjual</a>
                  @else
                    <a href="{{ route('seller.register.form') }}"
                      class="block px-3 py-2 rounded-lg text-sm text-amber-400 font-bold hover:bg-amber-500/10">Daftar Jadi
                      Penjual</a>
                  @endif
                @endif

                @if(Route::has('settings.account'))
                  <a href="{{ route('settings.account') }}"
                    class="block px-3 py-2 rounded-lg text-sm text-slate-200 hover:bg-white/10 hover:text-white">Pengaturan
                    Akun</a>
                @else
                  <a href="{{ url('/settings/account') }}"
                    class="block px-3 py-2 rounded-lg text-sm text-slate-200 hover:bg-white/10 hover:text-white">Pengaturan
                    Akun</a>
                @endif
              </div>

              <div class="border-t border-white/10 mt-2 pt-2 px-2">
                <form method="POST" action="{{ route('logout') }}">
                  @csrf
                  <button type="submit"
                    class="w-full text-left px-3 py-2 rounded-lg text-sm font-bold text-rose-400 hover:bg-rose-500/10">Keluar
                    Akun</button>
                </form>
              </div>
            </div>
          </div>

        @else
          {{-- Guest --}}
          <a href="{{ route('products.search') }}" class="md:hidden nav-icon-btn">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
          </a>
          <a href="{{ route('login') }}"
            class="hidden sm:block px-5 py-2 rounded-xl text-sm font-bold text-white hover:bg-white/5 border border-transparent hover:border-white/10 transition-all">Masuk</a>
          <a href="{{ route('register') }}"
            class="px-5 py-2 rounded-xl bg-blue-600 hover:bg-blue-500 text-white text-sm font-bold shadow-[0_0_20px_rgba(37,99,235,0.3)] transition-all">Daftar</a>
        @endif

      </div>
    </div>
  </div>

  {{-- SUB-BAR: Category & Pill Links --}}
  @if(!$isAdminRoute)
    <div class="navbar-categories h-14 hidden lg:block border-t border-white/5 shadow-sm">
      <div class="max-w-7xl mx-auto px-4 h-full flex items-center gap-4">

        {{-- MEGA MENU KATEGORI (Fixed Solid Background & No Dead Zone) --}}
        <div class="relative h-full flex items-center group">
          <button
            class="flex items-center gap-2 text-white font-bold text-sm px-4 py-2 rounded-xl bg-white/5 border border-white/10 hover:bg-white/10 transition-colors">
            <svg class="w-4 h-4 text-cyan-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7" />
            </svg>
            Kategori
          </button>

          {{-- Panel (Solid BG, removed mt-2) --}}
          <div
            class="absolute left-0 top-full w-[850px] bg-[#0B1220] rounded-b-2xl shadow-[0_30px_80px_rgba(0,0,0,0.9)] border border-white/10 hidden group-hover:flex z-[999] min-h-[380px] max-h-[75vh] overflow-hidden transition-all duration-300">

            {{-- Kiri --}}
            <div class="w-64 bg-[#060A14] border-r border-white/5 p-3 overflow-y-auto max-h-[75vh] no-scrollbar">
              <p class="text-[10px] font-bold text-slate-500 uppercase tracking-widest px-3 mb-2 mt-2">Semua Game</p>
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

            {{-- Kanan --}}
            <div
              class="flex-1 p-6 overflow-y-auto max-h-[75vh] bg-gradient-to-b from-transparent to-slate-950/20 premium-scrollbar">
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
            </div>
          </div>
        </div>

        <div class="h-6 w-px bg-white/10 shrink-0"></div>

        {{-- Beranda Button (Static) --}}
        <a href="{{ route('marketplace.home') }}"
          class="hidden lg:flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-semibold transition-all duration-300 shrink-0 {{ request()->routeIs('marketplace.home') ? 'text-cyan-400 bg-cyan-500/10 border border-cyan-400/20 shadow-[0_0_14px_rgba(6,182,212,0.15)]' : 'text-slate-300 hover:text-white hover:bg-white/5 border border-transparent' }}">
          <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M3 10l9-7 9 7v9a2 2 0 01-2 2h-4a2 2 0 01-2-2V13H9v6a2 2 0 01-2 2H3z" />
          </svg>
          Beranda
        </a>

        {{-- PILL LINKS HORIZONTAL SCROLL (Fade effect + Premium Scrollbar) --}}
        <div class="flex-1 h-full overflow-x-auto premium-scrollbar mask-gradient-right pr-8 flex items-center">
          <div class="flex items-center gap-2.5 min-w-max pt-1 pb-1">
            @php
              $currentCat = request('category', '');
              $navLinkBase = 'nav-modern-link px-4 py-2 text-sm font-bold rounded-xl border transition-all duration-300 whitespace-nowrap flex items-center gap-2';
              $navActive = 'text-cyan-400 bg-cyan-500/10 border-cyan-400/30 shadow-[0_0_15px_rgba(6,182,212,0.1)]';
              $navInactive = 'text-slate-300 bg-white/[0.02] border-white/5 hover:text-white hover:bg-white/10 hover:border-white/10';
            @endphp

            <a href="{{ route('products.search', ['category' => 'top-up-game']) }}"
              class="{{ $navLinkBase }} {{ ($currentCat === 'top-up-game') ? $navActive : $navInactive }}">
              <span class="w-1.5 h-1.5 rounded-full bg-rose-500 animate-pulse"></span>
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
          </div>
        </div>

      </div>
    </div>
  @endif
</header>

@push('scripts')
  <script>
    function toggleDropdown(id) {
      const dropdown = document.getElementById(id);
      if (!dropdown) return;
      document.querySelectorAll('.dropdown-panel').forEach(el => {
        if (el.id !== id && !el.classList.contains('hidden')) {
          el.classList.add('hidden');
        }
      });
      dropdown.classList.toggle('hidden');
    }

    document.addEventListener('click', function (event) {
      const isClickInsideMenu = event.target.closest('.dropdown-panel');
      const isClickOnButton = event.target.closest('button[onclick^="toggleDropdown"]');
      if (!isClickInsideMenu && !isClickOnButton) {
        document.querySelectorAll('.dropdown-panel').forEach(el => {
          el.classList.add('hidden');
        });
      }
    });

    const navbar = document.getElementById('main-navbar');
    const navContainer = document.getElementById('nav-container');
    window.addEventListener('scroll', () => {
      if (window.scrollY > 20) {
        navContainer.style.boxShadow = '0 20px 40px rgba(0,0,0,0.4)';
        navContainer.style.background = 'rgba(6, 10, 25, 0.95)';
      } else {
        navContainer.style.boxShadow = '';
        navContainer.style.background = 'linear-gradient(180deg, rgba(6, 10, 25, 0.95), rgba(6, 10, 25, 0.85))';
      }
    });
  </script>
@endpush
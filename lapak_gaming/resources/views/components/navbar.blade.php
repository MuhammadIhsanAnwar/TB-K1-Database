{{--
  Component: components/navbar.blade.php
  Premium dark gaming marketplace navbar.
  Variables: Auth facade, $categories (injected via view composer or controller)
--}}

@php
  /** @var \App\Models\User|null $authUser */
  $authUser = Auth::user();
  $navCategories = isset($categories) ? $categories : collect();
  $isAdminRoute = request()->routeIs('admin.*');
  $isAdminSettingsRoute = $authUser?->isAdmin() && request()->routeIs('settings.*');
  $cartItems = collect();
  $cartCount = 0;
  $avatarFallback = 'https://ui-avatars.com/api/?name=' . urlencode($authUser?->name ?? 'User') . '&background=2563eb&color=fff';

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
  /* ═══════════════════════════════════════
      DUNIAGAMES STYLE BUTTON (for navbar)
   ════════════════════════════════════════ */

  .dg-btn {
    background:
      linear-gradient(135deg,#2563eb 0%,#1d4ed8 40%,#0f172a 100%);
    border: 1px solid rgba(96,165,250,0.35);

    box-shadow:
      0 10px 30px rgba(37,99,235,0.25),
      inset 0 1px 0 rgba(255,255,255,0.08);

    letter-spacing: .02em;

    transform-style: preserve-3d;
  }

  .dg-btn:hover {
    transform:
      translateY(-3px)
      scale(1.02);

    box-shadow:
      0 18px 40px rgba(37,99,235,0.38),
      0 0 30px rgba(96,165,250,0.22);
  }

  .dg-btn:active {
    transform: scale(.98);
  }

  .dg-btn-glow {
    position: absolute;
    inset: 0;

    background:
      linear-gradient(
        120deg,
        transparent 20%,
        rgba(255,255,255,0.20) 50%,
        transparent 80%
      );

    transform: translateX(-120%);
    transition: transform .8s ease;
  }

  .dg-btn:hover .dg-btn-glow {
    transform: translateX(120%);
  }

  .cat-menu-scroll {
    max-height: min(72vh, 760px);
    overflow-y: auto;
    scrollbar-width: thin;
    scrollbar-color: rgba(59,130,246,.55) rgba(15,23,42,.4);
  }

  .cat-menu-scroll::-webkit-scrollbar {
    width: 8px;
  }

  .cat-menu-scroll::-webkit-scrollbar-track {
    background: rgba(15,23,42,.35);
    border-radius: 999px;
  }

  .cat-menu-scroll::-webkit-scrollbar-thumb {
    background: linear-gradient(180deg, rgba(59,130,246,.7), rgba(37,99,235,.95));
    border-radius: 999px;
  }
</style>
@endpush

{{-- ═══ MOBILE SIDEBAR DRAWER ═══ --}}
<aside id="mobile-drawer" class="fixed top-0 left-0 h-full w-72 z-50 flex flex-col overflow-y-auto"
       style="background:#0D1421;border-right:1px solid #1E2D45;">

  {{-- Drawer Header --}}
  <div class="flex items-center justify-between p-4" style="border-bottom:1px solid #1E2D45;">
    <a href="{{ route('marketplace.home') }}" class="flex items-center gap-2.5">
      <img src="{{ url('storage/app/public/logo/logo.png') }}" alt="Lapak Gaming" class="w-8 h-8 rounded-lg object-contain bg-white/5 p-1 shadow-glow-sm">
      <span class="font-display font-bold text-base text-white tracking-wide">{{ config('app.name', 'Lapak Gaming') }}</span>
    </a>
    <button onclick="closeDrawer()" aria-label="Close menu"
            class="w-8 h-8 rounded-lg flex items-center justify-center text-slate-400 hover:text-white transition-colors"
            style="background:#162032;">
      <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
    </button>
  </div>

  {{-- User Block --}}
  @auth
  <div class="p-4" style="border-bottom:1px solid #1E2D45;">
    <div class="flex items-center gap-3 p-3 rounded-xl" style="background:#162032;border:1px solid #1E2D45;">
       <img src="{{ $authUser?->avatar_url ?? $avatarFallback }}"
         alt="Foto profil {{ $authUser?->name ?? 'User' }}"
         class="w-10 h-10 rounded-full object-cover shrink-0"
         style="background:#162032;border:1px solid #1E2D45;"
         onerror="this.onerror=null;this.src='{{ $avatarFallback }}';">
      <div class="flex-1 min-w-0">
        <div class="text-sm font-semibold text-white truncate">{{ $authUser?->name ?? 'User' }}</div>
        <div class="text-xs text-slate-400 truncate">{{ $authUser?->email ?? '' }}</div>
      </div>
      @if(Auth::user()->is_pro ?? false)
        <span class="badge badge-gold shrink-0">PRO</span>
      @endif
    </div>
  </div>
  @else
  <div class="p-4" style="border-bottom:1px solid #1E2D45;">
    <div class="grid grid-cols-2 gap-2">
      <a href="{{ route('login') }}" class="btn-ghost text-center text-sm py-2.5 px-3 rounded-xl">Login</a>
      <a href="{{ route('register') }}" class="dg-btn group relative inline-flex items-center justify-center overflow-hidden rounded-xl py-2.5 px-3 font-display font-bold text-white transition-all duration-300 text-sm">
        <span class="relative z-10">Daftar</span>
        <span class="dg-btn-glow"></span>
      </a>
    </div>
  </div>
  @endauth

  {{-- Search --}}
  <div class="p-4" style="border-bottom:1px solid #1E2D45;">
    <form action="{{ route('products.search') }}" method="GET">
      <div class="relative">
        <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
        <input type="text" name="q" placeholder="Cari produk..." class="input pl-9 text-sm py-2.5" />
      </div>
    </form>
  </div>

  {{-- Navigation --}}
  <nav class="p-4 flex-1 space-y-6">
    <div>
      <p class="text-[10px] font-display font-semibold text-slate-600 uppercase tracking-widest mb-2 px-1">Menu</p>
      <ul class="space-y-0.5">
        @php
          $navItems = [
            ['route' => 'marketplace.home',    'icon' => 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6', 'label' => 'Beranda'],
            ['route' => 'marketplace.browse',  'icon' => 'M4 6h16M4 12h16M4 18h7', 'label' => 'Semua Produk'],
            ['route' => 'marketplace.trending','icon' => 'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z', 'label' => 'Trending', 'badge' => ['text'=>'HOT','class'=>'badge-orange']],
          ];
        @endphp
        @foreach($navItems as $item)
        <li>
          <a href="{{ route($item['route']) }}"
             class="flex items-center gap-3 px-3 py-2.5 rounded-xl font-medium text-sm transition-all
                    {{ request()->routeIs($item['route']) ? 'text-brand-400' : 'text-slate-400 hover:text-white hover:bg-surface-750' }}"
             @if(request()->routeIs($item['route'])) style="background:rgba(37,99,235,0.1);border:1px solid rgba(37,99,235,0.2);" @endif>
            <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $item['icon'] }}"/></svg>
            {{ $item['label'] }}
            @if(isset($item['badge']))
              <span class="ml-auto badge {{ $item['badge']['class'] }}">{{ $item['badge']['text'] }}</span>
            @endif
          </a>
        </li>
        @endforeach
        @auth
        <li>
          <a href="{{ route('wishlist.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-slate-400 hover:text-white hover:bg-surface-750 font-medium text-sm transition-all">
            <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
            Wishlist
          </a>
        </li>
        @endauth
      </ul>
    </div>

    @isset($categories)
    @if($categories->count())
    <div>
      <p class="text-[10px] font-display font-semibold text-slate-600 uppercase tracking-widest mb-2 px-1">Kategori</p>
      <ul class="space-y-0.5">
        @foreach($categories->take(13) as $category)
        <li>
          <a href="{{ route('categories.show', $category->slug) }}"
             class="flex items-center gap-3 px-3 py-2 rounded-xl text-slate-400 hover:text-white hover:bg-surface-750 text-sm transition-all">
            @if(!empty($category->image))
              <img src="{{ $category->image_url }}" alt="{{ $category->name }}" class="w-5 h-5 rounded object-cover shrink-0" loading="lazy">
            @else
              <span class="text-base leading-none">{{ $category->icon ?? '🎮' }}</span>
            @endif
            {{ $category->name }}
          </a>
        </li>
        @endforeach
      </ul>
    </div>
    @endif
    @endisset

    @auth
    <div>
      <p class="text-[10px] font-display font-semibold text-slate-600 uppercase tracking-widest mb-2 px-1">Akun</p>
      <ul class="space-y-0.5">
        @if($authUser?->isAdmin())
          <li>
            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-slate-400 hover:text-white hover:bg-surface-750 text-sm transition-all">
              <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5a1 1 0 011-1h4a1 1 0 011 1v5a1 1 0 01-1 1H5a1 1 0 01-1-1V5zm10 0a1 1 0 011-1h4a1 1 0 011 1v2a1 1 0 01-1 1h-4a1 1 0 01-1-1V5zM4 15a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1H5a1 1 0 01-1-1v-4zm10-2a1 1 0 011-1h4a1 1 0 011 1v6a1 1 0 01-1 1h-4a1 1 0 01-1-1v-6z"/></svg>
              Panel Admin
            </a>
          </li>
          <li>
            <a href="{{ route('admin.users.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-slate-400 hover:text-white hover:bg-surface-750 text-sm transition-all">
              <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a4 4 0 00-5-3.874M9 20H4v-2a4 4 0 015-3.874m7-4.126a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
              Kelola Akun
            </a>
          </li>
          <li>
            <a href="{{ route('admin.orders.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-slate-400 hover:text-white hover:bg-surface-750 text-sm transition-all">
              <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
              Transaksi
            </a>
          </li>
          <li>
            <a href="{{ route('admin.banners.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-slate-400 hover:text-white hover:bg-surface-750 text-sm transition-all">
              <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h10"/></svg>
              Banner
            </a>
          </li>
          <li>
            <a href="{{ route('admin.notifications.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-slate-400 hover:text-white hover:bg-surface-750 text-sm transition-all">
              <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
              Notifikasi
            </a>
          </li>
          <li>
            <a href="{{ route('profile.show') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-slate-400 hover:text-white hover:bg-surface-750 text-sm transition-all">
              <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
              Profil Saya
            </a>
          </li>
          <li>
            <a href="{{ route('settings.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-slate-400 hover:text-white hover:bg-surface-750 text-sm transition-all">
              <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
              Pengaturan
            </a>
          </li>
          <li>
            <a href="{{ route('settings.password') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-slate-400 hover:text-white hover:bg-surface-750 text-sm transition-all">
              <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 11c1.657 0 3 1.343 3 3v2H9v-2c0-1.657 1.343-3 3-3zm0 0V8m0 3h3m-3 0H9m3-5a5 5 0 00-5 5v2h10v-2a5 5 0 00-5-5z"/></svg>
              Ubah Password
            </a>
          </li>
        @else
          {{-- KELUARKAN DARI CONDITIONAL: Seller sekarang wajib punya akses ke Dashboard Buyer biasa --}}
          <li>
            <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-slate-400 hover:text-white hover:bg-surface-750 text-sm transition-all">
              <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5a1 1 0 011-1h4a1 1 0 011 1v5a1 1 0 01-1 1H5a1 1 0 01-1-1V5zm10 0a1 1 0 011-1h4a1 1 0 011 1v2a1 1 0 01-1 1h-4a1 1 0 01-1-1V5zM4 15a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1H5a1 1 0 01-1-1v-4zm10-2a1 1 0 011-1h4a1 1 0 011 1v6a1 1 0 01-1 1h-4a1 1 0 01-1-1v-6z"/></svg>
              Dashboard
            </a>
          </li>
          <li>
            <a href="{{ route('profile.show') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-slate-400 hover:text-white hover:bg-surface-750 text-sm transition-all">
              <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
              Profil Saya
            </a>
          </li>
          <li>
            <a href="{{ route('settings.password') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-slate-400 hover:text-white hover:bg-surface-750 text-sm transition-all">
              <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 11c1.657 0 3 1.343 3 3v2H9v-2c0-1.657 1.343-3 3-3zm0 0V8m0 3h3m-3 0H9m3-5a5 5 0 00-5 5v2h10v-2a5 5 0 00-5-5z"/></svg>
              Ubah Password
            </a>
          </li>
          <li>
            <a href="{{ route('wallet.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-slate-400 hover:text-white hover:bg-surface-750 text-sm transition-all">
              <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
              Wallet
            </a>
          </li>
          <li>
            <a href="{{ route('orders.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-slate-400 hover:text-white hover:bg-surface-750 text-sm transition-all">
              <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
              Pesanan
            </a>
          </li>
          @if($authUser?->isSellerAccount())
          <li>
            <a href="{{ route('seller.dashboard') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-slate-400 hover:text-white hover:bg-surface-750 text-sm transition-all">
              <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7h18M5 7l1 12h12l1-12M10 11v4M14 11v4M9 7V5a1 1 0 011-1h4a1 1 0 011 1v2"/></svg>
              Dashboard Seller
            </a>
          </li>
          @else
          <li>
            <form method="POST" action="{{ route('seller.register') }}">
              @csrf
              <button type="submit" class="w-full flex items-center gap-3 px-3 py-2.5 rounded-xl text-amber-300 hover:text-amber-200 hover:bg-amber-900/20 text-sm transition-all">
                <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 1.343-3 3v1H8a2 2 0 00-2 2v4h12v-4a2 2 0 00-2-2h-1v-1c0-1.657-1.343-3-3-3zm0 0V6m0 0l-2 2m2-2l2 2"/></svg>
                Daftar Jadi Seller
              </button>
            </form>
          </li>
          @endif
        @endif
        <li>
          <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="w-full flex items-center gap-3 px-3 py-2.5 rounded-xl text-red-400 hover:text-red-300 hover:bg-red-900/20 text-sm transition-all">
              <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
              Keluar
            </button>
          </form>
        </li>
      </ul>
    </div>
    @endauth
  </nav>

  {{-- Upgrade CTA --}}
  @auth
  @if(!(Auth::user()->is_pro ?? false))
  <div class="p-4" style="border-top:1px solid #1E2D45;">
    <div class="rounded-xl p-4 relative overflow-hidden" style="background:linear-gradient(135deg,rgba(37,99,235,0.2),rgba(249,115,22,0.1));border:1px solid rgba(37,99,235,0.3);">
      <div class="flex items-center justify-between">
        <div>
          <p class="text-xs font-display font-bold text-white">Upgrade ke PRO</p>
          <p class="text-xs text-slate-400 mt-0.5">Fitur premium tak terbatas</p>
        </div>
        <a href="{{ route('subscription.upgrade') }}" class="text-xs btn-primary py-2 px-3 rounded-lg">
          Upgrade ↗
        </a>
      </div>
    </div>
  </div>
  @endif
  @endauth
</aside>

{{-- ═══ STICKY TOP NAVBAR ═══ --}}
<header id="main-navbar"
  class="sticky top-0 z-30 navbar-blur reveal-navbar"
  style="background:rgba(6,10,18,0.85);border-bottom:1px solid rgba(30,45,69,0.6);">

 
  <div class="max-w-7xl mx-auto px-4 h-14 flex items-center gap-4">

    {{-- Hamburger (mobile) --}}
    <button onclick="openDrawer()" aria-label="Open menu"
            class="lg:hidden w-9 h-9 rounded-lg flex items-center justify-center text-slate-400 hover:text-white transition-colors shrink-0"
            style="background:#162032;border:1px solid #1E2D45;">
      <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
    </button>

    {{-- Logo --}}
    <a href="{{ route('marketplace.home') }}" class="flex items-center gap-2 shrink-0 group">
      <img src="{{ url('storage/app/public/logo/logo.png') }}" alt="Lapak Gaming" class="w-8 h-8 rounded-lg object-contain bg-white/5 p-1 transition-transform group-hover:scale-105" style="box-shadow:0 0 12px rgba(37,99,235,0.35);">
      <span class="font-display font-bold text-white text-base hidden sm:block">{{ config('app.name', 'Lapak Gaming') }}</span>
    </a>

    {{-- Desktop nav links --}}
    <nav class="hidden lg:flex items-center gap-1 ml-2">
        @if($authUser?->isAdmin())
          <a href="{{ route('admin.dashboard') }}" class="nav-link px-3 py-1.5 rounded-lg text-sm font-medium {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">Panel Admin</a>
          <a href="{{ route('admin.users.index') }}" class="nav-link px-3 py-1.5 rounded-lg text-sm font-medium {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">Kelola Akun</a>
          <a href="{{ route('admin.orders.index') }}" class="nav-link px-3 py-1.5 rounded-lg text-sm font-medium {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}">Transaksi</a>
          <a href="{{ route('admin.banners.index') }}" class="nav-link px-3 py-1.5 rounded-lg text-sm font-medium {{ request()->routeIs('admin.banners.*') ? 'active' : '' }}">Banner</a>
          <a href="{{ route('admin.notifications.index') }}" class="nav-link px-3 py-1.5 rounded-lg text-sm font-medium {{ request()->routeIs('admin.notifications.*') ? 'active' : '' }}">Notifikasi</a>
        @else
          <a href="{{ route('marketplace.home') }}"
              class="nav-link px-3 py-1.5 rounded-lg text-sm font-medium
              {{ request()->routeIs('marketplace.home') ? 'active' : '' }}">
              Beranda
            </a>

          {{-- Categories Dropdown --}}
          <div class="relative">
            <button onclick="toggleDropdown('cat-dropdown')" class="flex items-center gap-1 px-3 py-1.5 rounded-lg text-sm font-medium text-slate-400 hover:text-white transition-colors">
              Kategori
              <svg id="cat-dropdown-arrow" class="w-3.5 h-3.5 transition-transform duration-200" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
            </button>
            <div id="cat-dropdown" class="dropdown-panel absolute top-full left-0 mt-2 w-[880px] max-w-[calc(100vw-2rem)] rounded-2xl shadow-card-hover overflow-visible"
                 style="background:#0D1421;border:1px solid #1E2D45;">
              <div class="grid grid-cols-[300px_minmax(0,1fr)] gap-0">
                <div class="cat-menu-scroll p-2" style="border-right:1px solid #1E2D45;">
                  <div class="px-3 py-2 mb-2 rounded-xl bg-white/5 border border-white/5">
                    <div class="text-sm font-semibold text-white">Kategori</div>
                    <div class="text-xs text-slate-400 mt-1">Arahkan kursor ke kategori untuk melihat subkategori.</div>
                  </div>

                  @if($navCategories->isNotEmpty())
                    <div class="space-y-1.5">
                      @foreach($navCategories as $cat)
                        <div class="group/category relative">
                          <a href="{{ route('categories.show', $cat->slug) }}"
                             class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm text-slate-300 hover:text-white hover:bg-white/5 transition-colors">
                            <img src="{{ $cat->image_url }}" alt="{{ $cat->name }}" class="w-8 h-8 rounded-lg object-cover shrink-0 bg-slate-900/40" loading="lazy">
                            <span class="flex-1 min-w-0 truncate font-medium">{{ $cat->name }}</span>
                            <span class="text-[11px] uppercase tracking-[0.18em] text-slate-500">{{ $cat->children->count() }}</span>
                          </a>

                          @if($cat->children->isNotEmpty())
                            <div class="absolute left-full top-0 z-20 ml-3 hidden w-[540px] group-hover/category:block">
                              <div class="rounded-2xl border border-white/10 bg-[#0B1220] p-4 shadow-2xl">
                                <div class="flex items-start justify-between gap-4 border-b border-white/5 pb-4">
                                  <div class="flex items-center gap-3 min-w-0">
                                    <img src="{{ $cat->image_url }}" alt="{{ $cat->name }}" class="w-11 h-11 rounded-xl object-cover shrink-0 bg-slate-900/40" loading="lazy">
                                    <div class="min-w-0">
                                      <div class="text-base font-semibold text-white truncate">{{ $cat->name }}</div>
                                      <div class="text-xs text-slate-400 mt-1">{{ $cat->children->count() }} subkategori tersedia</div>
                                    </div>
                                  </div>
                                  <a href="{{ route('categories.index') }}" class="shrink-0 rounded-full border border-cyan-400/20 bg-cyan-400/10 px-3 py-1.5 text-[11px] font-semibold uppercase tracking-[0.16em] text-cyan-300 hover:bg-cyan-400 hover:text-black transition-colors">
                                    Tampilkan semua kategori
                                  </a>
                                </div>

                                <div class="mt-4 grid grid-cols-2 gap-2">
                                  @foreach($cat->children as $subCategory)
                                    <a href="{{ route('categories.show', $subCategory->slug) }}"
                                       class="group/sub flex items-center gap-3 rounded-xl border border-white/5 bg-white/[0.02] px-3 py-2.5 text-sm text-slate-300 hover:border-cyan-400/25 hover:bg-cyan-400/10 hover:text-white transition-colors">
                                      <img src="{{ $subCategory->image_url }}" alt="{{ $subCategory->name }}" class="h-10 w-10 rounded-lg object-cover shrink-0 bg-slate-900/40" loading="lazy">
                                      <span class="min-w-0 flex-1 truncate">{{ $subCategory->name }}</span>
                                    </a>
                                  @endforeach
                                </div>
                              </div>
                            </div>
                          @endif
                        </div>
                      @endforeach
                    </div>
                  @endif

                  <div class="mt-3 pt-3" style="border-top:1px solid #1E2D45;">
                    <a href="{{ route('categories.index') }}" class="flex items-center justify-center gap-2 rounded-xl border border-cyan-400/20 bg-cyan-400/10 px-4 py-2.5 text-sm font-semibold text-cyan-300 hover:bg-cyan-400 hover:text-black transition-colors">
                      Tampilkan semua kategori
                    </a>
                  </div>
                </div>

                <div class="hidden xl:flex flex-col justify-between p-5">
                  <div>
                    <div class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-500">Kategori Pilihan</div>
                    <h3 class="mt-2 text-xl font-semibold text-white">Temukan subkategori dengan cepat</h3>
                    <p class="mt-2 text-sm leading-6 text-slate-400">Arahkan kursor ke kategori di sisi kiri untuk membuka panel subkategori di samping. Panel ini hanya menampilkan daftar kategori, bukan produk.</p>
                  </div>

                  <div class="grid grid-cols-2 gap-3 mt-6">
                    <a href="{{ route('categories.index') }}" class="rounded-2xl border border-white/10 bg-white/[0.03] p-4 text-left hover:border-cyan-400/25 hover:bg-cyan-400/10 transition-colors">
                      <div class="text-sm font-semibold text-white">Semua Kategori</div>
                      <div class="mt-1 text-xs text-slate-400">Lihat 13 kategori dan subkategori</div>
                    </a>
                    <a href="{{ route('products.search') }}" class="rounded-2xl border border-white/10 bg-white/[0.03] p-4 text-left hover:border-cyan-400/25 hover:bg-cyan-400/10 transition-colors">
                      <div class="text-sm font-semibold text-white">Cari Produk</div>
                      <div class="mt-1 text-xs text-slate-400">Khusus untuk pencarian produk</div>
                    </a>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <a href="{{ route('marketplace.trending') }}"
            class="nav-link flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-sm font-medium
            {{ request()->routeIs('marketplace.trending') ? 'active' : '' }}">
            Trending
            <span class="badge badge-orange">HOT</span>
          </a>
        @endif
  </nav>

    {{-- Search Bar (center, desktop) --}}
    @if(! $isAdminRoute && ! $isAdminSettingsRoute)
    <form action="{{ route('products.search') }}" method="GET" class="hidden md:flex flex-1 max-w-md mx-auto">
      <div class="relative w-full">
        <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
        <input id="search-input" type="text" name="q"
               value="{{ request('q') }}"
               placeholder="Cari top-up, item, akun game..."
               class="input pl-9 pr-4 py-2 text-sm rounded-xl" />
      </div>
    </form>
    @endif

    {{-- Right Action Icons --}}
    <div class="flex items-center gap-1.5 ml-auto">

      @if($authUser)
      @if(! $authUser?->isAdmin())
      {{-- Mobile search (only for non-admin) --}}
      <a href="{{ route('products.search') }}" class="md:hidden w-9 h-9 rounded-lg flex items-center justify-center text-slate-400 hover:text-white transition-colors" style="background:#162032;border:1px solid #1E2D45;">
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
      </a>
      @endif

      {{-- Notifications --}}
      @if(! $isAdminRoute && ! $isAdminSettingsRoute)
      <div class="relative">
        <button onclick="toggleDropdown('notif-dropdown'); loadNotificationPreview();" class="relative w-9 h-9 rounded-lg flex items-center justify-center text-slate-400 hover:text-white transition-colors" style="background:#162032;border:1px solid #1E2D45;" aria-label="Notifications">
          <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
          <span id="notif-badge" class="notif-dot hidden"></span>
        </button>
        <div id="notif-dropdown" data-notifications-url="{{ route('notifications.poll') }}" class="dropdown-panel absolute right-0 top-full mt-2 w-80 rounded-xl shadow-card-hover"
             data-notifications-read-all-url="{{ route('notifications.read-all') }}"
             style="background:#0D1421;border:1px solid #1E2D45;">
          <div class="flex items-center justify-between px-4 py-3" style="border-bottom:1px solid #1E2D45;">
            <span class="font-display font-semibold text-sm text-white">Notifikasi</span>
            <div class="flex items-center gap-3">
              <button type="button" onclick="markAllNotificationsRead()" class="text-xs text-slate-400 hover:text-white">Tandai semua</button>
              <a href="{{ route('notifications.index') }}" class="text-xs text-brand-400 hover:text-brand-300">Lihat semua</a>
            </div>
          </div>
          <div id="notif-dropdown-body" class="py-2 max-h-72 overflow-y-auto">
            <div class="px-4 py-3 text-sm text-slate-400 text-center">Klik ikon notifikasi untuk memuat pesan terbaru.</div>
          </div>
        </div>
      </div>
      @endif

      @if(! $authUser?->isAdmin())
      {{-- Chat (only for non-admin) --}}
      <div class="relative">
        <a href="{{ route('chat.inbox') }}" class="relative w-9 h-9 rounded-lg flex items-center justify-center text-slate-400 hover:text-white transition-colors" style="background:#162032;border:1px solid #1E2D45;" aria-label="Chat">
          <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a.863.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
          <span id="chat-badge" class="notif-dot hidden"></span>
        </a>
      </div>

      {{-- Cart (only for non-admin) --}}
      <div class="relative">
        <button onclick="toggleDropdown('cart-dropdown')" class="relative w-9 h-9 rounded-lg flex items-center justify-center text-slate-400 hover:text-white transition-colors" style="background:#162032;border:1px solid #1E2D45;" aria-label="Cart">
          <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
          @if($authUser && $cartCount > 0)
            <span class="absolute -right-2 -top-2 flex min-w-[1.15rem] h-[1.15rem] items-center justify-center rounded-full border-2 border-surface-950 bg-accent-500 px-1 text-[10px] font-bold leading-none text-white">
              {{ $cartCount > 99 ? '99+' : $cartCount }}
            </span>
          @endif
        </button>
      </div>
      @endif

      <div id="cart-dropdown" class="dropdown-panel absolute right-0 top-full mt-2 w-72 rounded-xl shadow-card-hover"
             style="background:#0D1421;border:1px solid #1E2D45;">
          <div class="px-4 py-3" style="border-bottom:1px solid #1E2D45;">
            <div class="flex items-center justify-between gap-3">
              <span class="font-display font-semibold text-sm text-white">Keranjang</span>
              <a href="{{ route('cart.index') }}" class="text-xs text-brand-400 hover:text-brand-300">Lihat semua</a>
            </div>
          </div>
          @if($authUser && $cartItems->isNotEmpty())
            <div class="max-h-80 overflow-y-auto divide-y divide-slate-800/80">
              @foreach($cartItems as $item)
                <a href="{{ route('cart.index') }}" class="flex items-start gap-3 px-4 py-3 hover:bg-surface-850 transition-colors">
                  <div class="w-10 h-10 rounded-lg overflow-hidden shrink-0" style="background:#162032;border:1px solid #1E2D45;">
                    <img src="{{ $item->product?->image_url }}" alt="{{ $item->product?->name }}" class="w-full h-full object-cover">
                  </div>
                  <div class="min-w-0 flex-1">
                    <div class="text-sm text-white font-medium truncate">{{ $item->product?->name ?? 'Produk' }}</div>
                    <div class="mt-0.5 text-xs text-slate-500">
                      {{ $item->quantity }} x Rp {{ number_format((float) ($item->product?->price ?? 0), 0, ',', '.') }}
                    </div>
                  </div>
                </a>
              @endforeach
            </div>
          @else
            <div class="px-4 py-8 text-center">
              <div class="w-10 h-10 rounded-xl mx-auto mb-3 flex items-center justify-center" style="background:#162032;">
                <svg class="w-5 h-5 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
              </div>
              <p class="text-sm text-slate-400">Keranjang kosong.</p>
              <a href="{{ route('products.search') }}" class="mt-3 inline-block text-xs text-brand-400 hover:text-brand-300">Mulai belanja →</a>
            </div>
          @endif
        </div>

      {{-- User Dropdown --}}
      <div class="relative">
        <button onclick="toggleDropdown('user-dropdown')" class="flex items-center gap-2 pl-2 pr-3 py-1.5 rounded-xl transition-colors" style="background:#162032;border:1px solid #1E2D45;" aria-label="User menu">
          <img src="{{ $authUser?->avatar_url ?? $avatarFallback }}"
            alt="Foto profil {{ $authUser?->name ?? 'User' }}"
            class="w-7 h-7 rounded-full object-cover"
            style="background:#162032;border:1px solid #1E2D45;"
            onerror="this.onerror=null;this.src='{{ $avatarFallback }}';">
          <span class="hidden sm:block text-sm font-medium text-slate-200 max-w-[80px] truncate">{{ $authUser?->name ?? 'User' }}</span>
          <svg class="w-3.5 h-3.5 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
        </button>
        <div id="user-dropdown" class="dropdown-panel absolute right-0 top-full mt-2 w-52 rounded-xl shadow-card-hover overflow-hidden"
             style="background:#0D1421;border:1px solid #1E2D45;">
          <div class="px-4 py-3" style="border-bottom:1px solid #1E2D45;">
            <div class="text-sm font-semibold text-white truncate">{{ $authUser?->name ?? 'User' }}</div>
            <div class="text-xs text-slate-400 truncate mt-0.5">{{ $authUser?->email ?? '' }}</div>
          </div>
          <div class="p-1.5">
            @if($authUser?->isAdmin())
              @foreach([
                ['route'=>'admin.dashboard', 'icon'=>'M4 5a1 1 0 011-1h4a1 1 0 011 1v5a1 1 0 01-1 1H5a1 1 0 01-1-1V5zm10 0a1 1 0 011-1h4a1 1 0 011 1v2a1 1 0 01-1 1h-4a1 1 0 01-1-1V5zM4 15a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1H5a1 1 0 01-1-1v-4zm10-2a1 1 0 011-1h4a1 1 0 011 1v6a1 1 0 01-1 1h-4a1 1 0 01-1-1v-6z', 'label'=>'Panel Admin'],
                ['route'=>'admin.users.index', 'icon'=>'M17 20h5v-2a4 4 0 00-5-3.874M9 20H4v-2a4 4 0 015-3.874m7-4.126a4 4 0 11-8 0 4 4 0 018 0z', 'label'=>'Kelola Akun'],
                ['route'=>'admin.orders.index', 'icon'=>'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2', 'label'=>'Transaksi'],
                ['route'=>'admin.banners.index', 'icon'=>'M4 6h16M4 12h16M4 18h10', 'label'=>'Banner'],
                ['route'=>'admin.notifications.index', 'icon'=>'M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9', 'label'=>'Notifikasi'],
                ['route'=>'profile.show', 'icon'=>'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z', 'label'=>'Profil Saya'],
                ['route'=>'settings.index','icon'=>'M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z M15 12a3 3 0 11-6 0 3 3 0 016 0z', 'label'=>'Pengaturan'],
              ] as $link)
              <a href="{{ route($link['route']) }}" class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-sm text-slate-400 hover:text-white transition-all">
                <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="{{ $link['icon'] }}"/></svg>
                {{ $link['label'] }}
              </a>
              @endforeach
            @else
              {{-- PERBAIKAN SEKARANG: Di-loop secara normal tanpa @continue, agar menu Dashboard pembeli tetap muncul bagi Seller --}}
              @foreach([
                ['route'=>'dashboard',    'icon'=>'M4 5a1 1 0 011-1h4a1 1 0 011 1v5a1 1 0 01-1 1H5a1 1 0 01-1-1V5zm10 0a1 1 0 011-1h4a1 1 0 011 1v2a1 1 0 01-1 1h-4a1 1 0 01-1-1V5zM4 15a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1H5a1 1 0 01-1-1v-4zm10-2a1 1 0 011-1h4a1 1 0 011 1v6a1 1 0 01-1 1h-4a1 1 0 01-1-1v-6z', 'label'=>'Dashboard'],
                ['route'=>'profile.show', 'icon'=>'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z', 'label'=>'Profil Saya'],
                ['route'=>'wallet.index', 'icon'=>'M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z', 'label'=>'Wallet'],
                ['route'=>'orders.index', 'icon'=>'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2', 'label'=>'Pesanan Saya'],
                ['route'=>'settings.index','icon'=>'M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z M15 12a3 3 0 11-6 0 3 3 0 016 0z', 'label'=>'Pengaturan'],
              ] as $link)
                <a href="{{ route($link['route']) }}" class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-sm text-slate-400 hover:text-white transition-all">
                  <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="{{ $link['icon'] }}"/></svg>
                  {{ $link['label'] }}
                </a>
              @endforeach
              
              @if($authUser?->isSellerAccount())
              <a href="{{ route('seller.dashboard') }}" class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-sm text-slate-400 hover:text-white transition-all">
                <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 7h18M5 7l1 12h12l1-12M10 11v4M14 11v4M9 7V5a1 1 0 011-1h4a1 1 0 011 1v2"/></svg>
                Dashboard Seller
              </a>
              @else
              <a href="{{ route('seller.register.form') }}" class="w-full flex items-center gap-2.5 px-3 py-2 rounded-lg text-sm text-amber-300 hover:text-amber-200 hover:bg-amber-900/20 transition-all">
                  <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8c-1.657 0-3 1.343-3 3v1H8a2 2 0 00-2 2v4h12v-4a2 2 0 00-2-2h-1v-1c0-1.657-1.343-3-3-3zm0 0V6m0 0l-2 2m2-2l2 2"/>
                  </svg>
                  Daftar Jadi Seller
              </a>
              @endif
            @endif
          </div>
          <div class="p-1.5" style="border-top:1px solid #1E2D45;">
            <form method="POST" action="{{ route('logout') }}">
              @csrf
              <button type="submit" class="w-full flex items-center gap-2.5 px-3 py-2 rounded-lg text-sm text-red-400 hover:text-red-300 hover:bg-red-900/20 transition-all">
                <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                Keluar
              </button>
            </form>
          </div>
        </div>
      </div>

      @else
      {{-- Guest --}}
      <a href="{{ route('login') }}" class="btn-ghost py-2 px-4 text-sm rounded-xl">Masuk</a>
      <a href="{{ route('register') }}" class="inline-flex dg-btn group relative items-center justify-center overflow-hidden rounded-xl px-4 py-2 font-display font-bold text-white transition-all duration-300 text-sm">
        <span class="relative z-10 flex items-center gap-2">
          <span>Daftar</span>
        </span>
        <span class="dg-btn-glow"></span>
      </a>
      @endif
    </div>
  </div>
</header>
{{--
  Component: components/navbar.blade.php
  Itemku style marketplace navbar.
--}}

@php
  /** @var \App\Models\User|null $authUser */
  $authUser = Auth::user();
  $navCategories = isset($categories) ? $categories : collect();
  $isAdminRoute = request()->routeIs('admin.*');
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
    background: var(--nav-main-bg);
    color: var(--text);
  }
  .navbar-top {
    background: var(--nav-top-bg);
    color: var(--text);
  }
  .navbar-categories {
    background: var(--nav-cat-bg);
    color: var(--text);
  }
  /* Ensure links and icons inside the navbar inherit readable colors */
  #main-navbar a, #main-navbar button, #main-navbar .nav-link { color: inherit; }
  /* Mobile drawer should follow surface/background tokens */
  #mobile-drawer { background: var(--surface); color: var(--text); }
  .navbar-container .surface-weak { background: rgba(255,255,255,0.02); }
  /* Small utility tokens to keep legacy classnames working */
  .text-itemku-blue { color: var(--primary) !important; }
  .border-itemku-blue { border-color: var(--primary) !important; }
  .bg-itemku-yellow { background: var(--accent) !important; }
  .focus-within-border-accent:focus-within { border-color: var(--accent) !important; }

  /* Responsive navbar tweaks */
  @media (max-width: 640px) {
    #main-navbar .navbar-container { height: 56px; }
    #main-navbar .h-20 { height: 56px; }
    #main-navbar .font-display { font-size: 1rem; }
  }
</style>
@endpush

{{-- ═══ MOBILE SIDEBAR DRAWER ═══ --}}
<aside id="mobile-drawer" class="fixed top-0 left-0 h-full w-72 z-50 flex flex-col overflow-y-auto bg-[#0D1421] text-slate-200 transition-transform -translate-x-full">
  <div class="flex items-center justify-between p-4 border-b border-white/6 navbar-top">
    <a href="{{ route('marketplace.home') }}" class="flex items-center gap-2.5">
      <img src="{{ url('storage/app/public/logo/logo.png') }}" alt="Logo" class="w-8 h-8 rounded-lg object-contain surface-weak">
      <span class="font-display font-bold text-base text-white tracking-wide">{{ config('app.name', 'Itemku') }}</span>
    </a>
    <button onclick="closeDrawer()" class="text-white hover:text-gray-200">
      <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
    </button>
  </div>

  @auth
  <div class="p-4 border-b border-white/6 bg-transparent">
    <div class="flex items-center gap-3">
       <img src="{{ $authUser?->avatar_url ?? $avatarFallback }}" alt="Avatar" class="w-10 h-10 rounded-full object-cover shrink-0">
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
        <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 surface-muted" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
        <input type="text" name="q" placeholder="Cari Game, Item..." class="w-full pl-9 pr-3 py-2 border-none rounded-lg text-sm text-slate-300" style="background:var(--input-bg); color:var(--text)" />
      </div>
    </form>
  </div>

  <nav class="p-4 flex-1 space-y-4">
    <div>
      <ul class="space-y-1">
        <li><a href="{{ route('marketplace.home') }}" class="flex items-center py-2 surface-text hover:text-itemku-blue text-sm font-medium">Beranda</a></li>
        <li><a href="{{ route('marketplace.browse') }}" class="flex items-center py-2 surface-text hover:text-itemku-blue text-sm font-medium">Semua Produk</a></li>
        <li><a href="{{ route('marketplace.trending') }}" class="flex items-center py-2 surface-text hover:text-itemku-blue text-sm font-medium">Trending</a></li>
      </ul>
    </div>
    
    @auth
    <div>
      <p class="text-xs font-semibold surface-muted uppercase mb-2">Akun Saya</p>
      <ul class="space-y-1">
        @if($authUser?->isAdmin())
          <li><a href="{{ route('admin.dashboard') }}" class="flex items-center py-2 surface-text hover:text-itemku-blue text-sm">Panel Admin</a></li>
          <li><a href="{{ route('admin.orders.index') }}" class="flex items-center py-2 surface-text hover:text-itemku-blue text-sm">Transaksi</a></li>
        @else
          <li><a href="{{ route('dashboard') }}" class="flex items-center py-2 surface-text hover:text-itemku-blue text-sm">Dashboard</a></li>
          <li><a href="{{ route('orders.index') }}" class="flex items-center py-2 surface-text hover:text-itemku-blue text-sm">Pesanan Saya</a></li>
          <li><a href="{{ route('wallet.index') }}" class="flex items-center py-2 surface-text hover:text-itemku-blue text-sm">Wallet</a></li>
        @endif
        <li>
          <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="w-full flex items-center py-2 text-red-400 hover:text-red-200 text-sm text-left">Keluar</button>
          </form>
        </li>
      </ul>
    </div>
    @endauth
  </nav>
</aside>

{{-- ═══ DESKTOP NAVBAR ═══ --}}
<header id="main-navbar" class="sticky top-0 z-40 shadow-sm w-full font-sans">
  
  {{-- 2. Main Bar (Blue) --}}
  <div class="navbar-container h-20 flex flex-col justify-center">
    <div class="max-w-7xl mx-auto px-4 w-full flex items-center gap-6">
      
      {{-- Mobile Menu & Logo --}}
      <div class="flex items-center gap-3">
        <button onclick="openDrawer()" class="lg:hidden text-white hover:opacity-80">
          <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
        </button>
        <a href="{{ route('marketplace.home') }}" class="flex shrink-0">
          <img src="{{ url('storage/app/public/logo/logo.png') }}" alt="Logo" class="h-8 w-auto surface-weak rounded p-1 hidden sm:block">
          <span class="font-display font-bold text-white text-xl ml-2 sm:hidden tracking-tight">{{ config('app.name', 'Itemku') }}</span>
        </a>
      </div>

      {{-- Search Bar --}}
      @if(! $isAdminRoute && ! $isAdminSettingsRoute)
      <div class="hidden md:flex flex-1 flex-col relative">
        <form action="{{ route('products.search') }}" method="GET" class="w-full relative">
          <div class="flex items-center surface-bg rounded w-full overflow-hidden p-0.5 border-2 border-transparent focus-within-border-accent transition-colors">
            <div class="pl-3 surface-muted">
              <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            </div>
            <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari Game, Top Up, Akun..." class="w-full border-none outline-none py-2 px-2 text-sm" style="color:var(--text)" />
            <button type="submit" class="surface-panel hover:brightness-110 px-4 py-1.5 rounded text-sm font-semibold mr-0.5 whitespace-nowrap" style="color:var(--text)">ENTER</button>
          </div>
        </form>
        {{-- Trending Chips (Absolute positioned below search to not expand nav height) --}}
        <div class="absolute top-full left-0 mt-1.5 flex items-center gap-1.5 whitespace-nowrap overflow-hidden text-xs w-full">
          <a href="{{ route('products.search', ['q'=>'cheap robux']) }}" class="px-2 py-0.5 rounded surface-weak text-white/90 hover:brightness-105 transition-colors">cheap robux</a>
          <a href="{{ route('products.search', ['q'=>'growtopia']) }}" class="px-2 py-0.5 rounded surface-weak text-white/90 hover:brightness-105 transition-colors">growtopia</a>
          <a href="{{ route('products.search', ['q'=>'genshin impact']) }}" class="px-2 py-0.5 rounded surface-weak text-white/90 hover:brightness-105 transition-colors">genshin impact</a>
          <a href="{{ route('products.search', ['q'=>'steam']) }}" class="px-2 py-0.5 rounded surface-weak text-white/90 hover:brightness-105 transition-colors">steam</a>
          <a href="{{ route('products.search', ['q'=>'mobile legends']) }}" class="px-2 py-0.5 rounded surface-weak text-white/90 hover:brightness-105 transition-colors">mobile legends</a>
        </div>
      </div>
      @endif

      {{-- Right Icons (Auth, Chat, Cart) --}}
      <div class="flex items-center gap-3 shrink-0 ml-auto md:ml-0">
        
        @if($authUser)
          @if(! $isAdminRoute && ! $isAdminSettingsRoute)
          {{-- Search Mobile --}}
          <a href="{{ route('products.search') }}" class="md:hidden text-white opacity-90 hover:opacity-100">
            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
          </a>
          
          {{-- Notifications --}}
          <div class="relative">
            <button onclick="toggleDropdown('notif-dropdown'); loadNotificationPreview();" class="text-white opacity-90 hover:opacity-100 p-1">
              <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
              <span id="notif-badge" class="absolute top-1 right-1 w-2 h-2 bg-red-500 rounded-full hidden"></span>
            </button>
            <div id="notif-dropdown" class="hidden absolute right-0 top-full mt-2 w-80 bg-[#0D1421] rounded-lg shadow-xl border border-white/6 overflow-hidden text-left text-slate-200 z-50">
              <div class="px-4 py-3 border-b flex justify-between items-center bg-transparent">
                <span class="font-bold text-sm text-slate-100">Notifikasi</span>
                <a href="{{ route('notifications.index') }}" class="text-xs text-itemku-blue">Lihat semua</a>
              </div>
              <div id="notif-dropdown-body" class="max-h-72 overflow-y-auto">
                <div class="px-4 py-6 text-sm text-slate-400 text-center">Klik ikon notifikasi untuk memuat pesan terbaru.</div>
              </div>
            </div>
          </div>

          {{-- Chat --}}
          @if(! $authUser?->isAdmin())
          <div class="relative">
            <a href="{{ route('chat.inbox') }}" class="block text-white opacity-90 hover:opacity-100 p-1">
              <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a.863.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
              <span id="chat-badge" class="absolute top-1 right-0 w-2 h-2 bg-red-500 rounded-full hidden"></span>
            </a>
          </div>

          {{-- Cart --}}
          <div class="relative">
            <button onclick="toggleDropdown('cart-dropdown')" class="text-white opacity-90 hover:opacity-100 p-1 relative">
              <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
              @if($cartCount > 0)
                <span class="absolute -top-1 -right-1 bg-red-500 text-white text-[10px] font-bold px-1.5 py-0.5 rounded-full">{{ $cartCount > 99 ? '99+' : $cartCount }}</span>
              @endif
            </button>
            <div id="cart-dropdown" class="hidden absolute right-0 top-full mt-2 w-72 bg-[#0D1421] rounded-lg shadow-xl border border-white/6 overflow-hidden text-left z-50">
              <div class="px-4 py-3 border-b flex justify-between items-center bg-transparent">
                <span class="font-bold text-sm text-slate-100">Keranjang</span>
                <a href="{{ route('cart.index') }}" class="text-xs text-itemku-blue">Lihat semua</a>
              </div>
              @if($cartItems->isNotEmpty())
                <div class="max-h-72 overflow-y-auto divide-y" style="border-color:var(--card-border)">
                  @foreach($cartItems as $item)
                    <a href="{{ route('cart.index') }}" class="flex gap-3 px-4 py-3 hover:surface-panel" style="text-decoration:none">
                      <img src="{{ $item->product?->image_url }}" alt="" class="w-10 h-10 object-cover rounded" style="background:var(--card-border)">
                      <div class="flex-1 min-w-0">
                        <div class="text-sm surface-text font-medium truncate">{{ $item->product?->name ?? 'Produk' }}</div>
                        <div class="text-xs surface-muted mt-1">{{ $item->quantity }} x Rp {{ number_format((float) ($item->product?->price ?? 0), 0, ',', '.') }}</div>
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
          
          <div class="h-6 w-px surface-weak mx-1 hidden sm:block"></div>

          {{-- User Avatar Dropdown --}}
          <div class="relative hidden sm:block">
            <button onclick="toggleDropdown('user-dropdown')" class="flex items-center gap-2 text-white">
              <img src="{{ $authUser?->avatar_url ?? $avatarFallback }}" class="w-7 h-7 rounded-full object-cover border border-white/20" alt="Avatar">
              <span class="text-sm font-medium truncate max-w-[100px]">{{ $authUser?->name ?? 'User' }}</span>
              <svg class="w-3 h-3 opacity-80" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
            </button>
            <div id="user-dropdown" class="hidden absolute right-0 top-full mt-3 w-56 bg-[#0D1421] rounded-lg shadow-xl border border-white/6 overflow-hidden text-left z-50 py-2">
              <div class="px-4 py-2 border-b border-white/6 mb-1">
                <p class="text-sm font-bold text-slate-100 truncate">{{ $authUser->name }}</p>
                <p class="text-xs text-slate-400 truncate">{{ $authUser->email }}</p>
              </div>
              
              @if($authUser->isAdmin())
                <a href="{{ route('admin.dashboard') }}" class="block px-4 py-2 text-sm surface-text hover:surface-weak">Panel Admin</a>
                <a href="{{ route('admin.users.index') }}" class="block px-4 py-2 text-sm surface-text hover:surface-weak">Kelola Akun</a>
                <a href="{{ route('admin.orders.index') }}" class="block px-4 py-2 text-sm surface-text hover:surface-weak">Transaksi</a>
                <a href="{{ route('admin.banners.index') }}" class="block px-4 py-2 text-sm surface-text hover:surface-weak">Banner</a>
              @else
                <a href="{{ route('dashboard') }}" class="block px-4 py-2 text-sm surface-text hover:surface-weak">Dashboard</a>
                <a href="{{ route('orders.index') }}" class="block px-4 py-2 text-sm surface-text hover:surface-weak">Pesanan Saya</a>
                <a href="{{ route('wallet.index') }}" class="block px-4 py-2 text-sm surface-text hover:surface-weak">Wallet</a>
                @if($authUser->isSellerAccount())
                  <a href="{{ route('seller.dashboard') }}" class="block px-4 py-2 text-sm surface-text hover:surface-weak">Dashboard Penjual</a>
                @else
                  <a href="{{ route('seller.register.form') }}" class="block px-4 py-2 text-sm text-amber-400 font-medium hover:bg-amber-800/10">Daftar Jadi Penjual</a>
                @endif
              @endif
              {{-- Pengaturan Akun (visible to all authenticated users) --}}
              @if(Route::has('settings.account'))
                <a href="{{ route('settings.account') }}" class="block px-4 py-2 text-sm surface-text hover:surface-weak">Pengaturan Akun</a>
              @else
                <a href="{{ url('/settings/account') }}" class="block px-4 py-2 text-sm surface-text hover:surface-weak">Pengaturan Akun</a>
              @endif

              <div class="border-t border-gray-100 mt-1 pt-1">
                <form method="POST" action="{{ route('logout') }}">
                  @csrf
                  <button type="submit" class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50">Keluar</button>
                </form>
              </div>
            </div>
          </div>

        @else
          {{-- Guest --}}
          <a href="{{ route('products.search') }}" class="md:hidden text-white opacity-90 hover:opacity-100 mr-2">
            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
          </a>
          <a href="{{ route('login') }}" class="hidden sm:block text-white text-sm font-semibold hover:opacity-80 transition-opacity">Masuk</a>
          <a href="{{ route('register') }}" class="btn-accent">Daftar</a>
        @endif

      </div>
    </div>
  </div>

  {{-- 3. Category Bar (Dark Blue) --}}
  <div class="navbar-categories h-12 hidden lg:block border-t border-white/5 shadow-sm">
    <div class="max-w-7xl mx-auto px-4 h-full flex items-center gap-1">
      
      {{-- Kategori Dropdown --}}
      <div class="relative h-full flex items-center group">
        <button class="flex items-center gap-2 text-white font-medium text-sm px-3 py-1.5 rounded hover:surface-weak transition-colors h-full">
          <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"/></svg>
          Kategori
        </button>
        {{-- Mega Menu (Hover to open) --}}
            <div class="absolute left-0 top-full w-[800px] bg-[#0D1421] rounded-b-lg shadow-xl border border-white/6 hidden group-hover:flex z-50 min-h-[400px] max-h-[70vh] overflow-y-auto">
          <div class="w-64 bg-transparent border-r border-white/6 p-2 overflow-y-auto max-h-[70vh]">
            @if($navCategories->isNotEmpty())
              @foreach($navCategories as $cat)
                <a href="{{ route('categories.show', $cat->slug) }}" class="flex items-center gap-3 px-3 py-2 text-sm text-slate-200 hover:surface-weak hover:text-itemku-blue hover:font-medium rounded-lg transition-colors">
                  <img src="{{ $cat->image_url }}" alt="" class="w-6 h-6 object-cover rounded bg-slate-800">
                  <span class="whitespace-normal">{{ $cat->name }}</span>
                </a>
              @endforeach
              <a href="{{ route('categories.index') }}" class="block text-center mt-2 py-2 text-sm font-semibold text-itemku-blue hover:underline">Semua Kategori</a>
            @endif
          </div>
          <div class="flex-1 p-6 overflow-y-auto max-h-[70vh]">
            <h3 class="font-bold text-slate-100 text-lg mb-4">Temukan Produk Digital Terbaik</h3>
            <div class="grid grid-cols-2 gap-4">
              <a href="{{ route('products.search', ['category'=>'game-top-up']) }}" class="block p-4 border border-white/6 rounded-lg hover:border-itemku-blue hover:shadow-md transition-all">
                <span class="block font-bold text-slate-100 mb-1">Game Top Up</span>
                <span class="text-xs text-slate-400">Mobile Legends, Free Fire, PUBG...</span>
              </a>
              <a href="{{ route('products.search', ['category'=>'game-key']) }}" class="block p-4 border border-white/6 rounded-lg hover:border-itemku-blue hover:shadow-md transition-all">
                <span class="block font-bold text-slate-100 mb-1">Game Key & Sharing</span>
                <span class="text-xs text-slate-400">Steam, EA, Epic Games...</span>
              </a>
              <a href="{{ route('products.search', ['category'=>'roblox']) }}" class="block p-4 border border-white/6 rounded-lg hover:border-itemku-blue hover:shadow-md transition-all">
                <span class="block font-bold text-slate-100 mb-1">Roblox</span>
                <span class="text-xs text-slate-400">Robux, Item, Pet, Akun...</span>
              </a>
              <a href="{{ route('products.search', ['category'=>'voucher']) }}" class="block p-4 border border-white/6 rounded-lg hover:border-itemku-blue hover:shadow-md transition-all">
                <span class="block font-bold text-slate-100 mb-1">Voucher & Gift Card</span>
                <span class="text-xs text-slate-400">Steam Wallet, Google Play...</span>
              </a>
            </div>
          </div>
        </div>
      </div>

      <div class="h-5 w-px bg-slate-200/20 mx-2"></div>

      {{-- Horizontal Links --}}
      <div class="flex-1 overflow-x-auto no-scrollbar mask-gradient-right">
        <div class="flex items-center gap-1 min-w-max">
          <a href="{{ route('products.search', ['category'=>'roblox']) }}" class="px-3 py-1.5 text-white text-sm hover:surface-weak rounded transition-colors whitespace-nowrap">Roblox Games</a>
          <a href="{{ route('products.search', ['category'=>'growtopia']) }}" class="px-3 py-1.5 text-white text-sm hover:surface-weak rounded transition-colors whitespace-nowrap">Growtopia</a>
          <a href="{{ route('products.search', ['category'=>'genshin-impact']) }}" class="px-3 py-1.5 text-white text-sm hover:surface-weak rounded transition-colors whitespace-nowrap">Genshin Impact</a>
          <a href="{{ route('products.search', ['category'=>'dota-2']) }}" class="px-3 py-1.5 text-white text-sm hover:surface-weak rounded transition-colors whitespace-nowrap">Dota 2 Item</a>
          <a href="{{ route('products.search', ['category'=>'game-key']) }}" class="px-3 py-1.5 text-white text-sm hover:surface-weak rounded transition-colors whitespace-nowrap">Game Key</a>
          <a href="{{ route('products.search', ['category'=>'mobile-legends']) }}" class="px-3 py-1.5 text-white text-sm hover:surface-weak rounded transition-colors whitespace-nowrap">Mobile Legend Account</a>
        </div>
      </div>

    </div>
  </div>
</header>

@push('scripts')
<script>
  function openDrawer() {
    const drawer = document.getElementById('mobile-drawer');
    drawer.classList.remove('-translate-x-full');
  }
  
  function closeDrawer() {
    const drawer = document.getElementById('mobile-drawer');
    drawer.classList.add('-translate-x-full');
  }

  function toggleDropdown(id) {
    const dropdown = document.getElementById(id);
    const allDropdowns = document.querySelectorAll('.dropdown-panel, [id$="-dropdown"]');
    
    const isHidden = dropdown.classList.contains('hidden');
    
    allDropdowns.forEach(d => {
      if(d.id !== id && !d.classList.contains('hidden')) {
        d.classList.add('hidden');
      }
    });

    if (isHidden) {
      dropdown.classList.remove('hidden');
    } else {
      dropdown.classList.add('hidden');
    }
  }

  document.addEventListener('click', function(event) {
    const isDropdownButton = event.target.closest('button[onclick^="toggleDropdown"]');
    const isDropdownPanel = event.target.closest('[id$="-dropdown"]');
    
    if (!isDropdownButton && !isDropdownPanel) {
      const allDropdowns = document.querySelectorAll('[id$="-dropdown"]');
      allDropdowns.forEach(d => {
        if (!d.classList.contains('hidden') && d.id !== 'mobile-drawer') {
          d.classList.add('hidden');
        }
      });
    }
  });

  // Keep existing notification preview logic if needed
  function loadNotificationPreview() {
    // Basic implementation since we removed complex data attributes for simplicity
    const body = document.getElementById('notif-dropdown-body');
    if(body && body.innerHTML.includes('Klik ikon')) {
      body.innerHTML = '<div class="p-4 text-center text-sm surface-muted">Memuat...</div>';
      fetch('/notifications/poll')
        .then(r => r.json())
        .then(data => {
            if(data.count > 0 && data.notifications) {
                let html = '<div class="divide-y divide-gray-100 max-h-72 overflow-y-auto">';
                data.notifications.slice(0,5).forEach(n => {
                    html += `<a href="${n.action_url||'#'}" class="block px-4 py-3 hover:bg-white/5 ${n.read_at ? 'opacity-70' : 'bg-blue-500/10'}">
                        <div class="text-sm font-semibold surface-text">${n.title}</div>
                        <div class="text-xs surface-muted mt-1 line-clamp-2">${n.message}</div>
                    </a>`;
                });
                html += '</div>';
                body.innerHTML = html;
            } else {
                body.innerHTML = '<div class="p-6 text-center text-sm text-gray-500">Belum ada notifikasi baru</div>';
            }
        })
        .catch(() => {
            body.innerHTML = '<div class="p-4 text-center text-sm text-red-500">Gagal memuat notifikasi</div>';
        });
    }
  }

  /* Theme toggle helpers */
  function getCurrentTheme(){
    return document.documentElement.getAttribute('data-theme') || (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light');
  }

  function applyTheme(t){
    document.documentElement.setAttribute('data-theme', t);
    try{ localStorage.setItem('site-theme', t); }catch(e){}
    updateThemeIcon(t);
  }

  function initTheme(){
    try{
      const saved = localStorage.getItem('site-theme');
      const theme = saved || (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light');
      applyTheme(theme);
    }catch(e){ applyTheme(getCurrentTheme()); }
  }

  function toggleTheme(){
    const cur = getCurrentTheme();
    applyTheme(cur === 'dark' ? 'light' : 'dark');
  }

  function updateThemeIcon(t){
    const icon = document.getElementById('themeIcon');
    if(!icon) return;
    if(t === 'dark'){
      icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12.79A9 9 0 1111.21 3 7 7 0 0021 12.79z" />';
    } else {
      icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v2m0 14v2m9-9h-2M5 12H3m15.364-6.364l-1.414 1.414M7.05 16.95l-1.414 1.414M16.95 16.95l1.414 1.414M7.05 7.05L5.636 5.636M12 8a4 4 0 100 8 4 4 0 000-8z" />';
    }
  }

  document.addEventListener('DOMContentLoaded', initTheme);
</script>
@endpush

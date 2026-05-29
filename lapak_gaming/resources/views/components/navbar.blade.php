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
  $avatarFallback = 'https://ui-avatars.com/api/?name=' . urlencode($authUser?->name ?? 'User') . '&background=0ea5e9&color=fff';

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
      background: rgba(6, 10, 25, 0.85);
      backdrop-filter: blur(24px);
      -webkit-backdrop-filter: blur(24px);
      border-bottom: 1px solid rgba(255, 255, 255, 0.05);
      transition: box-shadow 0.3s ease;
  }
  
  .navbar-container.scrolled {
      box-shadow: 0 20px 40px rgba(0, 0, 0, 0.4);
      background: rgba(6, 10, 25, 0.95);
  }

  /* CATEGORY BAR GLASS EFFECT */
  .navbar-categories {
      background: rgba(11, 18, 32, 0.75);
      backdrop-filter: blur(24px);
      -webkit-backdrop-filter: blur(24px);
      border-bottom: 1px solid rgba(255, 255, 255, 0.05);
  }

  /* ICON BUTTONS */
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
      transition: all 0.3s ease;
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
      background: rgba(15, 23, 42, 0.95);
      backdrop-filter: blur(24px);
      -webkit-backdrop-filter: blur(24px);
      border: 1px solid rgba(255, 255, 255, 0.08);
      box-shadow: 0 30px 60px rgba(0, 0, 0, 0.5);
      border-radius: 20px;
  }

  /* CATEGORY PILL TAGS */
  .cat-pill {
      display: inline-flex;
      align-items: center;
      gap: 0.4rem;
      padding: 0.45rem 1rem;
      border-radius: 999px;
      font-size: 0.85rem;
      font-weight: 600;
      color: #94a3b8;
      background: rgba(255, 255, 255, 0.03);
      border: 1px solid rgba(255, 255, 255, 0.05);
      transition: all 0.25s ease;
      white-space: nowrap;
  }

  .cat-pill:hover {
      color: #fff;
      background: rgba(255, 255, 255, 0.08);
      border-color: rgba(255, 255, 255, 0.15);
      transform: translateY(-1px);
  }

  .cat-pill.active {
      color: #67e8f9;
      background: rgba(6, 182, 212, 0.1);
      border-color: rgba(6, 182, 212, 0.25);
  }

  .no-scrollbar::-webkit-scrollbar { display: none; }
  .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
</style>
@endpush

{{-- ═══ MOBILE SIDEBAR DRAWER ═══ --}}
<aside id="mobile-drawer" class="fixed top-0 left-0 h-full w-72 z-50 flex flex-col overflow-y-auto bg-[#0B1220]/95 backdrop-blur-2xl border-r border-white/5 text-slate-200 transition-transform -translate-x-full">
  <div class="flex items-center justify-between p-4 border-b border-white/5">
    <a href="{{ route('marketplace.home') }}" class="flex items-center gap-2.5">
      <img src="{{ url('storage/app/public/logo/logo.png') }}" alt="Logo" class="w-8 h-8 rounded-lg object-contain bg-white/5 p-1">
      <span class="font-black text-lg text-white tracking-wide">{{ config('app.name', 'Lapak Gaming') }}</span>
    </a>
    <button onclick="closeDrawer()" class="text-slate-400 hover:text-white">
      <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
    </button>
  </div>

  @auth
  <div class="p-4 border-b border-white/5">
    <div class="flex items-center gap-3">
       <img src="{{ $authUser?->avatar_url ?? $avatarFallback }}" alt="Avatar" class="w-10 h-10 rounded-full object-cover border border-blue-500/30">
      <div class="flex-1 min-w-0">
        <div class="text-sm font-bold text-white truncate">{{ $authUser?->name ?? 'User' }}</div>
        <div class="text-xs text-slate-400 truncate">{{ $authUser?->email ?? '' }}</div>
      </div>
    </div>
  </div>
  @else
  <div class="p-4 border-b border-white/5 flex gap-2">
    <a href="{{ route('login') }}" class="flex-1 py-2 rounded-xl bg-white/5 border border-white/10 text-sm font-bold text-center hover:bg-white/10 transition">Masuk</a>
    <a href="{{ route('register') }}" class="flex-1 py-2 rounded-xl bg-blue-600 border border-blue-500/50 text-white text-sm font-bold text-center hover:bg-blue-500 transition">Daftar</a>
  </div>
  @endauth

  <div class="p-4 border-b border-white/5">
    <form action="{{ route('products.search') }}" method="GET">
      <div class="relative">
        <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
        <input type="text" name="q" placeholder="Cari Game, Item..." class="w-full rounded-xl border border-white/10 bg-black/40 pl-10 pr-4 py-2.5 text-sm text-white placeholder:text-slate-500 outline-none focus:border-cyan-500/50" />
      </div>
    </form>
  </div>

  <nav class="p-4 flex-1 space-y-4">
    <div>
      <ul class="space-y-1">
        <li><a href="{{ route('marketplace.home') }}" class="flex items-center py-2.5 px-3 rounded-lg hover:bg-white/5 text-sm font-medium">Beranda</a></li>
        <li><a href="{{ route('marketplace.browse') }}" class="flex items-center py-2.5 px-3 rounded-lg hover:bg-white/5 text-sm font-medium">Semua Produk</a></li>
        <li><a href="{{ route('marketplace.trending') }}" class="flex items-center py-2.5 px-3 rounded-lg hover:bg-white/5 text-sm font-medium">Trending</a></li>
      </ul>
    </div>
    
    @auth
    <div>
      <p class="text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-2 ml-3">Akun Saya</p>
      <ul class="space-y-1">
        @if($authUser?->isAdmin())
          <li><a href="{{ route('admin.dashboard') }}" class="flex items-center py-2.5 px-3 rounded-lg hover:bg-white/5 text-sm">Panel Admin</a></li>
          <li><a href="{{ route('admin.orders.index') }}" class="flex items-center py-2.5 px-3 rounded-lg hover:bg-white/5 text-sm">Transaksi</a></li>
        @else
          <li><a href="{{ route('dashboard') }}" class="flex items-center py-2.5 px-3 rounded-lg hover:bg-white/5 text-sm">Dashboard</a></li>
          <li><a href="{{ route('orders.index') }}" class="flex items-center py-2.5 px-3 rounded-lg hover:bg-white/5 text-sm">Pesanan Saya</a></li>
          <li><a href="{{ route('wallet.index') }}" class="flex items-center py-2.5 px-3 rounded-lg hover:bg-white/5 text-sm">Wallet</a></li>
        @endif
        <li>
          <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="w-full text-left py-2.5 px-3 rounded-lg hover:bg-red-500/10 text-red-400 text-sm text-left">Keluar</button>
          </form>
        </li>
      </ul>
    </div>
    @endauth
  </nav>
</aside>

{{-- ═══ DESKTOP NAVBAR (FIXED -> STICKY UNTUK CEGAH LAYOUT TABRAKAN) ═══ --}}
<header id="main-navbar" class="sticky top-0 w-full z-40 transition-all duration-300 {{ ($isAdminRoute || $isAdminSettingsRoute) ? 'admin-navbar' : '' }}">
  
  {{-- TOP MAIN BAR --}}
  <div class="navbar-container h-20 w-full flex flex-col justify-center" id="nav-container">
    <div class="max-w-7xl mx-auto px-4 w-full flex items-center gap-6">
      
      {{-- Mobile Menu Toggle & Logo --}}
      <div class="flex items-center gap-3">
        <button onclick="openDrawer()" class="lg:hidden text-white hover:text-cyan-400 transition">
          <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
        </button>
        <a href="{{ route('marketplace.home') }}" class="flex items-center gap-2 shrink-0 group">
          <div class="p-1.5 rounded-xl bg-white/5 border border-white/10 group-hover:border-cyan-500/30 transition">
              <img src="{{ url('storage/app/public/logo/logo.png') }}" alt="Logo" class="h-7 w-auto object-contain drop-shadow-md">
          </div>
          <span class="font-black text-white text-xl tracking-tight hidden sm:block">{{ config('app.name', 'Lapak Gaming') }}</span>
        </a>
      </div>

      {{-- SEARCH BAR (Desktop) --}}
      @if(! $isAdminRoute && ! $isAdminSettingsRoute)
      <div class="hidden md:flex flex-1 max-w-2xl mx-auto relative">
        <form action="{{ route('products.search') }}" method="GET" class="w-full">
          <div class="flex items-center gap-2 rounded-2xl border border-white/10 bg-black/30 px-3 py-2 shadow-inner transition-all duration-300 focus-within:border-cyan-500/40 focus-within:bg-black/50 focus-within:shadow-[0_0_25px_rgba(6,182,212,0.15)]">
            <svg class="w-5 h-5 text-slate-400 ml-2 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari Game, Top Up, Voucher, Akun..." class="w-full bg-transparent border-none outline-none px-2 py-1.5 text-sm text-white placeholder:text-slate-500" />
            <button type="submit" class="h-9 px-4 rounded-xl bg-blue-600 hover:bg-blue-500 text-white text-sm font-bold transition-colors">
              Cari
            </button>
          </div>
        </form>
      </div>
      @endif

      {{-- RIGHT ICONS (Auth, Notif, Cart) --}}
      <div class="flex items-center gap-3 shrink-0 ml-auto">
        
        @if($authUser)
          @if(! $isAdminRoute && ! $isAdminSettingsRoute)
          
          {{-- Search Mobile Icon --}}
          <a href="{{ route('products.search') }}" class="md:hidden nav-icon-btn">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
          </a>
          
          {{-- Notification Icon --}}
          <div class="relative">
            <button onclick="toggleDropdown('notif-dropdown')" class="nav-icon-btn">
              <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
              <span id="notif-badge" class="absolute top-0 right-0 w-2.5 h-2.5 bg-rose-500 rounded-full border-2 border-[#0B1220] hidden"></span>
            </button>
          </div>

          {{-- Cart Icon --}}
          <div class="relative">
            <button onclick="toggleDropdown('cart-dropdown')" class="nav-icon-btn">
              <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
              @if($cartCount > 0)
                <span class="absolute -top-1.5 -right-1.5 bg-rose-500 text-white text-[9px] font-black px-1.5 py-0.5 rounded-full border-2 border-[#0B1220]">{{ $cartCount > 99 ? '99+' : $cartCount }}</span>
              @endif
            </button>
            
            {{-- Cart Dropdown --}}
            <div id="cart-dropdown" class="dropdown-panel absolute right-0 top-full mt-4 w-80 hidden z-50">
              <div class="px-4 py-3 border-b border-white/10 flex justify-between items-center">
                <span class="font-bold text-white">Keranjang Belanja</span>
                <a href="{{ route('cart.index') }}" class="text-xs text-cyan-400 hover:text-cyan-300">Lihat Semua</a>
              </div>
              @if($cartItems->isNotEmpty())
                <div class="max-h-72 overflow-y-auto divide-y divide-white/5">
                  @foreach($cartItems as $item)
                    <a href="{{ route('cart.index') }}" class="flex gap-3 px-4 py-3 hover:bg-white/5 transition">
                      <img src="{{ $item->product?->image_url }}" alt="" class="w-12 h-12 object-cover rounded-lg bg-black/50">
                      <div class="flex-1 min-w-0">
                        <div class="text-sm text-white font-semibold truncate">{{ $item->product?->name ?? 'Produk' }}</div>
                        <div class="text-xs text-cyan-400 mt-1 font-bold">{{ $item->quantity }} x Rp {{ number_format((float) ($item->product?->price ?? 0), 0, ',', '.') }}</div>
                      </div>
                    </a>
                  @endforeach
                </div>
              @else
                <div class="p-8 text-center text-sm text-slate-500">Keranjang kamu masih kosong.</div>
              @endif
            </div>
          </div>
          @endif

          <div class="h-6 w-px bg-white/10 mx-1 hidden sm:block"></div>

          {{-- User Avatar Dropdown --}}
          <div class="relative hidden sm:block">
            <button onclick="toggleDropdown('user-dropdown')" class="flex items-center gap-2.5 rounded-full border border-white/10 bg-black/20 pl-2 pr-3 py-1.5 hover:bg-white/5 transition-colors">
              <img src="{{ $authUser?->avatar_url ?? $avatarFallback }}" class="w-7 h-7 rounded-full object-cover border border-blue-500/30" alt="Avatar">
              <span class="text-sm font-bold text-white truncate max-w-[120px]">{{ $authUser?->name ?? 'User' }}</span>
              <svg class="w-3.5 h-3.5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
            </button>
            
            <div id="user-dropdown" class="dropdown-panel absolute right-0 top-full mt-4 w-56 hidden z-50 py-2">
              <div class="px-4 py-3 border-b border-white/10 mb-1">
                <p class="text-sm font-black text-white truncate">{{ $authUser->name }}</p>
                <p class="text-xs text-slate-400 truncate">{{ $authUser->email }}</p>
              </div>
              
              <div class="px-2 space-y-0.5">
                @if($authUser->isAdmin())
                  <a href="{{ route('admin.dashboard') }}" class="block px-3 py-2 rounded-lg text-sm text-slate-200 hover:bg-white/10 hover:text-white">Panel Admin</a>
                @else
                  <a href="{{ route('dashboard') }}" class="block px-3 py-2 rounded-lg text-sm text-slate-200 hover:bg-white/10 hover:text-white">Dashboard</a>
                  <a href="{{ route('orders.index') }}" class="block px-3 py-2 rounded-lg text-sm text-slate-200 hover:bg-white/10 hover:text-white">Pesanan Saya</a>
                  <a href="{{ route('wallet.index') }}" class="block px-3 py-2 rounded-lg text-sm text-slate-200 hover:bg-white/10 hover:text-white">Wallet Digital</a>
                @endif
                
                <a href="{{ url('/settings/profile') }}" class="block px-3 py-2 rounded-lg text-sm text-slate-200 hover:bg-white/10 hover:text-white">Edit Profil</a>
              </div>

              <div class="border-t border-white/10 mt-2 pt-2 px-2">
                <form method="POST" action="{{ route('logout') }}">
                  @csrf
                  <button type="submit" class="w-full text-left px-3 py-2 rounded-lg text-sm font-bold text-rose-400 hover:bg-rose-500/10">Keluar Akun</button>
                </form>
              </div>
            </div>
          </div>

        @else
          {{-- Guest Buttons --}}
          <a href="{{ route('products.search') }}" class="md:hidden nav-icon-btn">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
          </a>
          <a href="{{ route('login') }}" class="hidden sm:block px-5 py-2 rounded-xl text-sm font-bold text-white hover:bg-white/5 border border-transparent hover:border-white/10 transition-all">Masuk</a>
          <a href="{{ route('register') }}" class="px-5 py-2 rounded-xl bg-blue-600 hover:bg-blue-500 text-white text-sm font-bold shadow-[0_0_20px_rgba(37,99,235,0.3)] transition-all">Daftar</a>
        @endif

      </div>
    </div>
  </div>
  
  {{-- SUB-BAR: CATEGORY PILL TAGS --}}
  @if(! $isAdminRoute)
  <div class="navbar-categories h-14 hidden lg:block">
    <div class="max-w-7xl mx-auto px-4 h-full flex items-center gap-4">
      
      {{-- Categories Dropdown Menu (Left Side) --}}
      <div class="relative h-full flex items-center group">
        <button class="flex items-center gap-2 text-white font-bold text-sm px-4 py-2 rounded-xl bg-white/5 border border-white/10 hover:bg-white/10 transition-colors">
          <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"/></svg>
          Kategori
        </button>
        
        {{-- Hover Dropdown Content --}}
        <div class="absolute left-0 top-full w-[800px] bg-[#0B1220]/95 backdrop-blur-2xl border border-white/10 rounded-2xl shadow-2xl hidden group-hover:flex z-50 overflow-hidden mt-2">
          <div class="w-64 bg-black/20 border-r border-white/5 p-3 overflow-y-auto max-h-[60vh]">
            @if($navCategories->isNotEmpty())
              @foreach($navCategories as $cat)
                <a href="{{ route('categories.show', $cat->slug) }}" class="flex items-center gap-3 px-3 py-2.5 text-sm font-medium text-slate-300 hover:bg-white/5 hover:text-cyan-400 rounded-xl transition-colors">
                  <img src="{{ $cat->image_url }}" alt="" class="w-6 h-6 object-cover rounded shadow-sm bg-black/50">
                  <span class="truncate">{{ $cat->name }}</span>
                </a>
              @endforeach
            @endif
          </div>
          <div class="flex-1 p-6">
            <h3 class="font-black text-white text-lg mb-4">Paling Populer</h3>
            <div class="grid grid-cols-2 gap-3">
              <a href="{{ route('products.search', ['category'=>'game-top-up']) }}" class="block p-4 border border-white/5 bg-white/5 rounded-xl hover:border-cyan-500/40 hover:bg-cyan-500/10 transition-all">
                <span class="block font-bold text-white mb-1">Game Top Up</span>
                <span class="text-xs text-slate-400">Mobile Legends, Free Fire, PUBG...</span>
              </a>
              <a href="{{ route('products.search', ['category'=>'game-key']) }}" class="block p-4 border border-white/5 bg-white/5 rounded-xl hover:border-cyan-500/40 hover:bg-cyan-500/10 transition-all">
                <span class="block font-bold text-white mb-1">Game Key</span>
                <span class="text-xs text-slate-400">Steam, EA, Epic Games...</span>
              </a>
              <a href="{{ route('products.search', ['category'=>'voucher']) }}" class="block p-4 border border-white/5 bg-white/5 rounded-xl hover:border-cyan-500/40 hover:bg-cyan-500/10 transition-all">
                <span class="block font-bold text-white mb-1">Gift Cards</span>
                <span class="text-xs text-slate-400">Steam Wallet, Google Play...</span>
              </a>
            </div>
          </div>
        </div>
      </div>

      <div class="h-6 w-px bg-white/10 shrink-0"></div>

      {{-- Horizontal Pill Links (Scrollable & Uniform Style) --}}
      <div class="flex-1 overflow-x-auto no-scrollbar flex items-center h-full">
        <div class="flex items-center gap-2.5 pb-1 pt-1">

            <a href="{{ route('marketplace.home') }}" class="cat-pill">
                Beranda
            </a>

            <a href="{{ route('products.search', ['q'=>'top up game']) }}" class="cat-pill active">
                <span class="w-1.5 h-1.5 rounded-full bg-cyan-400 animate-pulse"></span>
                Top Up
            </a>

            <a href="{{ route('products.search', ['q'=>'akun game']) }}" class="cat-pill">Akun Game</a>
            <a href="{{ route('products.search', ['q'=>'voucher game']) }}" class="cat-pill">Voucher</a>
            <a href="{{ route('products.search', ['category'=>'roblox']) }}" class="cat-pill">Roblox</a>
            <a href="{{ route('products.search', ['category'=>'growtopia']) }}" class="cat-pill">Growtopia</a>
            <a href="{{ route('products.search', ['category'=>'genshin-impact']) }}" class="cat-pill">Genshin</a>
            <a href="{{ route('products.search', ['category'=>'dota-2']) }}" class="cat-pill">Dota 2</a>
            <a href="{{ route('products.search', ['category'=>'game-key']) }}" class="cat-pill">PC Games</a>

        </div>
      </div>

    </div>
  </div>
  @endif
</header>

@push('scripts')
<script>
  // Simple dropdown toggler
  function toggleDropdown(id) {
    const dropdown = document.getElementById(id);
    if (!dropdown) return;
    
    document.querySelectorAll('.dropdown-panel').forEach(el => {
      if(el.id !== id && !el.classList.contains('hidden')) {
        el.classList.add('hidden');
      }
    });
    
    dropdown.classList.toggle('hidden');
  }

  // Close dropdowns on outside click
  document.addEventListener('click', function(event) {
    const isClickInsideMenu = event.target.closest('.dropdown-panel');
    const isClickOnButton = event.target.closest('button[onclick^="toggleDropdown"]');

    if (!isClickInsideMenu && !isClickOnButton) {
      document.querySelectorAll('.dropdown-panel').forEach(el => {
        el.classList.add('hidden');
      });
    }
  });

  // Smooth scroll glaze background transition
  const navContainer = document.getElementById('nav-container');
  window.addEventListener('scroll', () => {
    if (window.scrollY > 20) {
      navContainer.classList.add('scrolled');
    } else {
      navContainer.classList.remove('scrolled');
    }
  });
</script>
@endpush
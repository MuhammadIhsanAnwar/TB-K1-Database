{{--
  Component: components/bottom-nav.blade.php
  Mobile bottom navigation matching itemku style.
--}}

<div class="fixed bottom-0 left-0 right-0 z-[60] md:hidden">
  <div class="backdrop-blur-sm bg-black/40 border-t border-white/6 shadow-sm" style="padding-bottom: env(safe-area-inset-bottom, 0);">
    <div class="flex justify-around items-center h-16 px-2">
    
    {{-- Beranda --}}
    <a href="{{ route('marketplace.home') }}" class="flex flex-col items-center justify-center w-full h-full text-center {{ request()->routeIs('marketplace.home') ? 'text-primary' : 'surface-muted hover:surface-text' }}">
      <svg class="w-6 h-6 mb-1" fill="{{ request()->routeIs('marketplace.home') ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24" stroke-width="{{ request()->routeIs('marketplace.home') ? '0' : '2' }}">
        <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
      </svg>
      <span class="text-[10px] font-semibold">Beranda</span>
    </a>

    {{-- Kategori / Pencarian --}}
    <a href="{{ route('products.search') }}" class="flex flex-col items-center justify-center w-full h-full text-center {{ request()->routeIs('products.*', 'categories.*') ? 'text-primary' : 'surface-muted hover:surface-text' }}">
      <svg class="w-6 h-6 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
      </svg>
      <span class="text-[10px] font-semibold">Cari</span>
    </a>

    {{-- Pesanan (Transaksi) --}}
    <a href="{{ route('orders.index') }}" class="flex flex-col items-center justify-center w-full h-full text-center relative {{ request()->routeIs('orders.*') ? 'text-primary' : 'surface-muted hover:surface-text' }}">
      <svg class="w-6 h-6 mb-1" fill="{{ request()->routeIs('orders.*') ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24" stroke-width="{{ request()->routeIs('orders.*') ? '0' : '2' }}">
        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
      </svg>
      <span class="text-[10px] font-semibold">Transaksi</span>
    </a>

    {{-- Chat / Inbox --}}
    <a href="{{ route('chat.inbox') }}" class="flex flex-col items-center justify-center w-full h-full text-center relative {{ request()->routeIs('chat.*') ? 'text-primary' : 'surface-muted hover:surface-text' }}">
      <svg class="w-6 h-6 mb-1" fill="{{ request()->routeIs('chat.*') ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24" stroke-width="{{ request()->routeIs('chat.*') ? '0' : '2' }}">
        <path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
      </svg>
      <span id="mobile-chat-badge" class="absolute top-1.5 right-4 w-2.5 h-2.5 bg-red-500 rounded-full border border-white hidden"></span>
      <span class="text-[10px] font-semibold">Pesan</span>
    </a>

    {{-- Akun --}}
    <a href="{{ route('dashboard') }}" class="flex flex-col items-center justify-center w-full h-full text-center {{ request()->routeIs('dashboard', 'settings.*', 'profile.*') ? 'text-primary' : 'surface-muted hover:surface-text' }}">
      <svg class="w-6 h-6 mb-1" fill="{{ request()->routeIs('dashboard', 'settings.*', 'profile.*') ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24" stroke-width="{{ request()->routeIs('dashboard', 'settings.*', 'profile.*') ? '0' : '2' }}">
        <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
      </svg>
      <span class="text-[10px] font-semibold">Akun</span>
    </a>

    </div>
  </div>
</div>

{{-- Add bottom padding to body for mobile so content doesn't hide under nav (includes safe-area) --}}
@push('styles')
<style>
  @media (max-width: 768px) {
    body {
      padding-bottom: calc(4rem + env(safe-area-inset-bottom, 0)); /* 64px + safe area */
    }
  }
</style>
@endpush

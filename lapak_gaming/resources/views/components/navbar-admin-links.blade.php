@php
  /**
   * Compact admin links partial used in mobile drawer and avatar dropdown
   * Expects $authUser and route helpers to be available.
   */
@endphp

<div class="p-4">
  <p class="text-sm font-semibold text-slate-200 mb-2">Admin Menu</p>
  <ul class="space-y-2">
    <li><a href="{{ route('admin.dashboard') }}" class="block px-3 py-2 rounded text-sm text-slate-100 hover:bg-white/3">Dashboard</a></li>
    <li><a href="{{ route('admin.users.index') }}" class="block px-3 py-2 rounded text-sm text-slate-100 hover:bg-white/3">Kelola Akun</a></li>
    <li><a href="{{ route('admin.orders.index') }}" class="block px-3 py-2 rounded text-sm text-slate-100 hover:bg-white/3">Transaksi</a></li>
    <li><a href="{{ route('admin.banners.index') }}" class="block px-3 py-2 rounded text-sm text-slate-100 hover:bg-white/3">Banner</a></li>
    <li><a href="{{ route('admin.notifications.index') }}" class="block px-3 py-2 rounded text-sm text-slate-100 hover:bg-white/3">Notifikasi</a></li>
    <li><a href="{{ route('admin.terminal.index') ?? url('/artisan-terminal') }}" class="block px-3 py-2 rounded text-sm text-slate-100 hover:bg-white/3">Terminal</a></li>
  </ul>
</div>

@php
  /**
   * Compact admin links partial used in mobile drawer and avatar dropdown
   * Expects $authUser and route helpers to be available.
   */
@endphp

<div class="p-4">
  <p class="text-sm font-semibold text-slate-200 mb-2">Admin Menu</p>
  <div class="grid grid-cols-2 gap-2">
    <a href="{{ route('admin.dashboard') }}" class="rounded-xl border border-white/6 bg-white/3 px-3 py-2 text-center text-sm text-slate-100 transition hover:border-white/15 hover:bg-white/6">Dashboard</a>
    <a href="{{ route('admin.users.index') }}" class="rounded-xl border border-white/6 bg-white/3 px-3 py-2 text-center text-sm text-slate-100 transition hover:border-white/15 hover:bg-white/6">Kelola Akun</a>
    <a href="{{ route('admin.verification.index') }}" class="rounded-xl border border-white/6 bg-white/3 px-3 py-2 text-center text-sm text-slate-100 transition hover:border-white/15 hover:bg-white/6">Verifikasi</a>
    <a href="{{ route('admin.orders.index') }}" class="rounded-xl border border-white/6 bg-white/3 px-3 py-2 text-center text-sm text-slate-100 transition hover:border-white/15 hover:bg-white/6">Transaksi</a>
    <a href="{{ route('admin.banners.index') }}" class="rounded-xl border border-white/6 bg-white/3 px-3 py-2 text-center text-sm text-slate-100 transition hover:border-white/15 hover:bg-white/6">Banner</a>
    <a href="{{ route('admin.notifications.index') }}" class="rounded-xl border border-white/6 bg-white/3 px-3 py-2 text-center text-sm text-slate-100 transition hover:border-white/15 hover:bg-white/6">Notifikasi</a>
    <a href="{{ route('admin.terminal.index') }}" class="rounded-xl border border-white/6 bg-white/3 px-3 py-2 text-center text-sm text-slate-100 transition hover:border-white/15 hover:bg-white/6">Terminal</a>
    <a href="{{ route('admin.users.index', ['tab' => 'applications']) }}" class="rounded-xl border border-white/6 bg-white/3 px-3 py-2 text-center text-sm text-slate-100 transition hover:border-white/15 hover:bg-white/6">Pengajuan</a>
  </div>
</div>

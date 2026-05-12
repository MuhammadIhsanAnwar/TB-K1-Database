@extends('layouts.app')

@section('title', 'Manajemen Akun — Admin')

@push('styles')
<style>
  /* ── CSS Hasil "Copy" dari halaman Users ── */
  .tab-btn {
    position: relative;
    padding: .6rem 1.25rem;
    border-radius: 999px;
    font-size: .8125rem;
    font-weight: 600;
    color: #64748b;
    transition: color .2s, background .2s;
    white-space: nowrap;
    cursor: pointer;
    border: none; background: none;
    text-decoration: none !important;
  }
  .tab-btn:hover { color: #cbd5e1; background: rgba(255,255,255,.04); }
  .tab-btn.active { color: #fff; background: rgba(245,158,11,.15); }
  .tab-btn.active::after {
    content: '';
    position: absolute; bottom: -1px; left: 50%; transform: translateX(-50%);
    width: 32px; height: 2px; border-radius: 2px;
    background: #f97316;
  }

  .tab-badge {
    display: inline-flex; align-items: center; justify-content: center;
    min-width: 18px; height: 18px; padding: 0 5px; border-radius: 999px;
    font-size: .6875rem; font-weight: 700;
    background: rgba(245,158,11,.2); color: #fbbf24;
    margin-left: 6px;
  }
  .tab-badge.badge-pending { background: rgba(239,68,68,.2); color: #f87171; }

  .pill {
    display: inline-flex; align-items: center; gap: 4px;
    padding: 2px 8px; border-radius: 999px;
    font-size: .6875rem; font-weight: 600;
  }
  .pill-active   { background: rgba(16,185,129,.12); color: #34d399; }
  .pill-pending  { background: rgba(245,158,11,.12); color: #fbbf24; }
  .pill-approved { background: rgba(16,185,129,.12); color: #34d399; }
</style>
@endpush

@section('content')
<div class="min-h-screen bg-slate-950 py-10 px-4">
  <div class="mx-auto max-w-7xl space-y-6">

    {{-- Header Gaya "Users" --}}
    <div class="flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
      <div>
        <p class="text-xs uppercase tracking-widest text-amber-400">Admin Panel</p>
        <h1 class="mt-1 text-3xl font-bold text-white">Manajemen Akun</h1>
        <p class="mt-1 text-slate-400 text-sm">Kelola verifikasi seller dan data buyer platform.</p>
      </div>
      <div class="flex gap-2">
          <a href="{{ route('admin.dashboard') }}"
             class="inline-flex items-center gap-1.5 rounded-2xl border border-slate-700 px-4 py-2.5 text-sm text-slate-300 hover:border-slate-500 hover:text-white transition">
            Dashboard
          </a>
      </div>
    </div>

    {{-- Statistik Ringkas (Glow Style) --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="rounded-2xl border border-slate-800 bg-slate-900 p-5">
            <p class="text-slate-500 text-xs font-bold uppercase tracking-wider">Total Buyers</p>
            <p class="text-2xl font-bold text-white mt-1">{{ $buyers->total() }}</p>
        </div>
        <div class="rounded-2xl border border-slate-800 bg-slate-900 p-5">
            <p class="text-slate-500 text-xs font-bold uppercase tracking-wider">Verified Sellers</p>
            <p class="text-2xl font-bold text-emerald-400 mt-1">{{ $sellers->total() }}</p>
        </div>
        <div class="rounded-2xl border border-amber-500/20 bg-amber-500/5 p-5">
            <p class="text-amber-500/50 text-xs font-bold uppercase tracking-wider">Pending Apps</p>
            <p class="text-2xl font-bold text-amber-500 mt-1">{{ $applications->total() }}</p>
        </div>
    </div>

    {{-- Tab Navigation Gaya "Users" --}}
    <div class="overflow-x-auto">
      <div class="inline-flex gap-1 rounded-2xl border border-slate-800 bg-slate-900 p-1.5 min-w-max">
        <a href="?tab=buyers" class="tab-btn {{ ($tab ?? 'buyers') === 'buyers' ? 'active' : '' }}">
          Daftar Buyers
          <span class="tab-badge">{{ $buyers->total() }}</span>
        </a>

        <a href="?tab=sellers" class="tab-btn {{ ($tab ?? '') === 'sellers' ? 'active' : '' }}">
          Daftar Sellers
          <span class="tab-badge">{{ $sellers->total() }}</span>
        </a>

        <a href="?tab=applications" class="tab-btn {{ ($tab ?? '') === 'applications' ? 'active' : '' }}">
          Pengajuan Seller
          @if($applications->total() > 0)
            <span class="tab-badge badge-pending">{{ $applications->total() }}</span>
          @endif
        </a>
      </div>
    </div>

    {{-- Tabel Konten --}}
    <div class="overflow-hidden rounded-3xl border border-slate-800 bg-slate-900">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-800 text-sm text-left text-slate-300">
                <thead class="bg-slate-950 text-xs uppercase tracking-widest text-slate-500">
                    <tr>
                        <th class="px-6 py-4">User</th>
                        <th class="px-6 py-4">Kontak</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800">
                    @php 
                        if(($tab ?? 'buyers') == 'sellers') $currentData = $sellers;
                        elseif(($tab ?? '') == 'applications') $currentData = $applications;
                        else $currentData = $buyers;
                    @endphp

                    @forelse($currentData as $user)
                    <tr class="hover:bg-slate-800/40 transition">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-slate-800 border border-slate-700 flex items-center justify-center font-bold text-blue-400">
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                </div>
                                <div>
                                    <p class="font-medium text-white">{{ $user->name }}</p>
                                    <p class="text-xs text-slate-500 font-mono">ID #{{ $user->id }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <p class="text-slate-300">{{ $user->email }}</p>
                            <p class="text-xs text-slate-500">{{ $user->phone ?? '-' }}</p>
                        </td>
                        <td class="px-6 py-4">
                            @if($user->role == 'seller' || $user->seller_status == 'approved')
                                <span class="pill pill-approved">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span> Verified Seller
                                </span>
                            @elseif($user->seller_status == 'pending')
                                <span class="pill pill-pending">
                                    <span class="w-1.5 h-1.5 rounded-full bg-amber-400"></span> Pending
                                </span>
                            @else
                                <span class="pill bg-slate-800 text-slate-400">
                                    <span class="w-1.5 h-1.5 rounded-full bg-slate-500"></span> Buyer
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-right">
                            <button class="rounded-xl bg-slate-800 border border-slate-700 px-4 py-1.5 text-xs font-bold text-slate-300 hover:text-white hover:border-slate-500 transition">
                                Detail
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="py-20 text-center">
                            <p class="text-slate-500 italic">Tidak ada data ditemukan di tab ini.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        {{-- Pagination Gaya "Users" --}}
        <div class="px-6 py-4 border-t border-slate-800">
            {{ $currentData->links() }}
        </div>
    </div>

  </div>
</div>
@endsection
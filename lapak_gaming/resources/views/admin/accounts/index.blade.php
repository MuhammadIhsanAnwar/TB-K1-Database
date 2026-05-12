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
        <a href="?tab=users" class="tab-btn {{ ($tab ?? 'users') === 'users' ? 'active' : '' }}">
          Menu User
          <span class="tab-badge">{{ $buyers->total() }}</span>
        </a>

        <a href="?tab=sellers" class="tab-btn {{ ($tab ?? '') === 'sellers' ? 'active' : '' }}">
          Menu Seller
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
                        if(($tab ?? 'users') == 'sellers') {
                            $currentData = $sellers;
                        } elseif(($tab ?? '') == 'applications') {
                            $currentData = $applications;
                        } else {
                            $currentData = $buyers;
                        }
                    @endphp

                    @forelse($currentData as $user)
                    <tr class="hover:bg-slate-800/40 transition">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="h-12 w-12 overflow-hidden rounded-2xl border border-slate-700 bg-slate-800">
                                    <img src="{{ $user->shop_photo_url ?? $user->avatar_url }}" alt="Avatar {{ $user->name }}" class="h-full w-full object-cover" />
                                </div>
                                <div class="min-w-0">
                                    <p class="font-medium text-white">{{ $user->name }}</p>
                                    <p class="text-xs text-slate-500 font-mono">ID #{{ $user->id }}</p>
                                    @if(($tab ?? '') === 'sellers' || ($tab ?? '') === 'applications')
                                        <p class="mt-1 text-sm text-slate-400 truncate">{{ $user->shop_name ?? 'Belum ada nama toko' }}</p>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <p class="text-slate-300">{{ $user->email }}</p>
                            <p class="text-xs text-slate-500">{{ $user->phone ?? '-' }}</p>
                            @if(($tab ?? '') !== 'users' && $user->shop_description)
                                <p class="mt-2 text-xs text-slate-400">{{ \Illuminate\Support\Str::limit($user->shop_description, 80) }}</p>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            @if($user->status === 'suspended')
                                <span class="pill bg-rose-500/10 text-rose-300">Suspended</span>
                                @if($user->suspend_reason)
                                    <p class="mt-2 text-xs text-rose-300">Alasan: {{ \Illuminate\Support\Str::limit($user->suspend_reason, 90) }}</p>
                                @endif
                            @elseif($user->role === 'seller' || $user->seller_status === 'approved')
                                <span class="pill pill-approved">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span> Verified Seller
                                </span>
                            @elseif($user->seller_status === 'pending')
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
                            @if($user->role !== 'admin')
                                <form action="{{ route('admin.users.status', $user) }}" method="POST" class="space-y-2 text-right">
                                    @csrf
                                    @method('PUT')

                                    <div class="flex items-center justify-end gap-2">
                                        <select name="status" class="rounded-2xl border border-slate-800 bg-slate-950 px-3 py-2 text-sm text-slate-200 outline-none transition focus:border-amber-500">
                                            <option value="active" @selected($user->status === 'active')>Active</option>
                                            <option value="suspended" @selected($user->status === 'suspended')>Suspended</option>
                                        </select>
                                        <button type="submit" class="rounded-2xl bg-amber-500 px-4 py-2 text-xs font-bold text-slate-950 hover:bg-amber-400">Simpan</button>
                                    </div>

                                    <textarea name="suspend_reason" rows="2" placeholder="Alasan suspend (opsional)" class="mt-2 w-full rounded-2xl border border-slate-800 bg-slate-950 px-3 py-2 text-sm text-slate-200 outline-none transition focus:border-amber-500">{{ old('suspend_reason') }}</textarea>
                                </form>
                            @else
                                <span class="text-xs text-slate-500">Tidak dapat mengubah admin.</span>
                            @endif
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
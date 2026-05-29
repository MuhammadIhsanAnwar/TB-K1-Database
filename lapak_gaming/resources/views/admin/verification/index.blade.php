@extends('layouts.app')

@section('title', 'Verifikasi Seller — Admin')

@push('styles')
<style>
/* ===================================================================
   ADMIN DASHBOARD · PRO VERIFICATION MODULE
   Aesthetic: Clean Dark Mode, Flat-Glass, High Readability
   =================================================================== */

.admin-panel-bg { background-color: #020617; } /* slate-950 */

.stat-card {
    background: #0f172a; /* slate-900 */
    border: 1px solid #1e293b; /* slate-800 */
    border-radius: 16px;
    transition: all 0.2s ease;
}
.stat-card:hover {
    border-color: #334155; /* slate-700 */
    transform: translateY(-2px);
}

.status-badge {
    display: inline-flex; 
    align-items: center; 
    gap: 4px;
    padding: 4px 10px; 
    border-radius: 6px; 
    font-size: 0.68rem; 
    font-weight: 700; 
    letter-spacing: 0.03em; 
    text-transform: uppercase;
}
.s-pending       { background: rgba(245,158,11,.1); color: #fbbf24; border: 1px solid rgba(245,158,11,.2); }
.s-under_review  { background: rgba(99,102,241,.1); color: #a5b4fc; border: 1px solid rgba(99,102,241,.2); }
.s-need_revision { background: rgba(249,115,22,.1); color: #fb923c; border: 1px solid rgba(249,115,22,.2); }
.s-approved      { background: rgba(16,185,129,.1); color: #34d399; border: 1px solid rgba(16,185,129,.2); }
.s-rejected      { background: rgba(239,68,68,.1);  color: #f87171; border: 1px solid rgba(239,68,68,.2); }
.s-suspended     { background: rgba(100,116,139,.1); color: #94a3b8; border: 1px solid rgba(100,116,139,.2); }

/* Modern Tabs */
.tab-nav {
    display: flex;
    gap: 0.5rem;
    overflow-x: auto;
    border-bottom: 1px solid #1e293b;
    padding-bottom: 2px;
}
.tab-item { 
    padding: 0.75rem 1rem; 
    font-size: 0.85rem; 
    font-weight: 600; 
    color: #64748b; 
    display: inline-flex;
    align-items: center;
    gap: 8px;
    white-space: nowrap;
    border-bottom: 2px solid transparent;
    transition: all 0.2s;
    margin-bottom: -1px;
}
.tab-item:hover { color: #cbd5e1; }
.tab-item.active { 
    color: #38bdf8; 
    border-bottom-color: #38bdf8;
}
.count-pill { 
    padding: 2px 8px; 
    border-radius: 999px; 
    font-size: 0.65rem; 
    font-weight: 800; 
}

/* User List Item */
.data-row {
    background: #0f172a;
    border: 1px solid #1e293b;
    border-radius: 12px;
    transition: all 0.2s ease;
}
.data-row:hover {
    border-color: #38bdf840; /* subtle blue */
    background: #111a2e;
}

/* Hide Scrollbar for tabs */
.hide-scrollbar::-webkit-scrollbar { display: none; }
.hide-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
</style>
@endpush

@section('content')
<div class="admin-panel-bg min-h-screen py-8 px-4">
    <div class="mx-auto max-w-7xl space-y-6">

        {{-- ── HEADER ─────────────────────────────────────────────────── --}}
        <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between mb-8">
            <div>
                <div class="inline-flex items-center gap-2 text-sky-400 text-xs font-bold uppercase tracking-widest mb-2">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                    </svg>
                    Pusat Verifikasi
                </div>
                <h1 class="text-3xl font-bold text-white tracking-tight">Pengajuan Penjual</h1>
                <p class="text-slate-400 text-sm mt-1">Tinjau kelengkapan dokumen dan atur izin buka toko seller.</p>
            </div>
            <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-slate-800 border border-slate-700 text-sm font-semibold text-slate-300 hover:bg-slate-700 hover:text-white transition-all">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Kembali
            </a>
        </div>

        {{-- ── STATS CARDS ────────────────────────────────────────────── --}}
        <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
            @php
                $statConfig = [
                    'pending' => ['Menunggu', 'text-amber-400', '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />'],
                    'under_review' => ['Direview', 'text-indigo-400', '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />'],
                    'need_revision' => ['Revisi', 'text-orange-400', '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />'],
                    'approved' => ['Disetujui', 'text-emerald-400', '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />'],
                    'rejected' => ['Ditolak', 'text-red-400', '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />'],
                ];
            @endphp

            @foreach($statConfig as $key => [$label, $textCol, $svgPath])
            <a href="?tab={{ $key }}" class="stat-card p-4 flex flex-col justify-between {{ $tab === $key ? 'ring-1 ring-sky-500/50 bg-slate-800' : '' }}">
                <div class="flex items-start justify-between mb-2">
                    <div class="text-slate-400">
                        <svg class="w-5 h-5 {{ $tab === $key ? $textCol : '' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">{!! $svgPath !!}</svg>
                    </div>
                </div>
                <div>
                    <div class="font-['Oxanium'] text-3xl font-black {{ $textCol }}">{{ number_format($counts[$key] ?? 0) }}</div>
                    <div class="text-[11px] font-bold text-slate-500 uppercase tracking-wider mt-1">{{ $label }}</div>
                </div>
            </a>
            @endforeach
        </div>

        {{-- ── TABS ───────────────────────────────────────────────────── --}}
        <div class="tab-nav hide-scrollbar mt-8 mb-4">
            @foreach($statConfig as $key => [$label, $textCol, $svgPath])
            <a href="?tab={{ $key }}" class="tab-item {{ $tab === $key ? 'active' : '' }}">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">{!! $svgPath !!}</svg>
                {{ $label }}
                @if(($counts[$key] ?? 0) > 0)
                <span class="count-pill {{ $tab === $key ? 'bg-sky-500/20 text-sky-400' : 'bg-slate-800 text-slate-400' }}">
                    {{ $counts[$key] }}
                </span>
                @endif
            </a>
            @endforeach
        </div>

        {{-- ── USER LIST ──────────────────────────────────────────────── --}}
        @if($users->isEmpty())
        <div class="flex flex-col items-center justify-center p-16 text-center border border-dashed border-slate-800 rounded-2xl bg-slate-900/50">
            <svg class="w-16 h-16 text-slate-700 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
            </svg>
            <h3 class="text-slate-300 font-semibold text-lg mb-1">Tidak ada data</h3>
            <p class="text-slate-500 text-sm max-w-sm">Daftar pengajuan dengan status <strong class="text-slate-400">{{ str_replace('_', ' ', $tab) }}</strong> sedang kosong.</p>
        </div>
        @else
        <div class="space-y-3">
            @foreach($users as $user)
            <div class="data-row p-4 cursor-pointer flex flex-col sm:flex-row sm:items-center gap-4" onclick="window.location='{{ route('admin.verification.show', $user) }}'">
                
                {{-- Avatar & Info --}}
                <div class="flex items-center gap-4 flex-1 min-w-0">
                    <img src="{{ $user->shop_photo ? asset('storage/' . $user->shop_photo) : $user->avatar_url }}"
                         alt="{{ $user->name }}"
                         class="w-12 h-12 rounded-lg object-cover border border-slate-700 shrink-0">
                    
                    <div class="min-w-0">
                        <div class="flex items-center gap-2 mb-1">
                            <h3 class="font-bold text-slate-200 text-base truncate">{{ $user->shop_name ?? $user->name }}</h3>
                            @if($user->seller_status === 'approved')
                                <svg class="w-4 h-4 text-emerald-400 shrink-0" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                            @endif
                        </div>
                        <div class="text-xs text-slate-500 flex items-center gap-2 truncate">
                            <span>{{ $user->name }}</span>
                            <span class="w-1 h-1 rounded-full bg-slate-700"></span>
                            <span>{{ $user->email }}</span>
                        </div>
                    </div>
                </div>

                {{-- Status & Date --}}
                <div class="flex flex-row sm:flex-col items-center sm:items-end justify-between sm:justify-center gap-1 sm:w-32 shrink-0">
                    <span class="status-badge s-{{ $user->seller_status }}">
                        {{ match($user->seller_status) {
                            'pending'       => 'Pending',
                            'under_review'  => 'Direview',
                            'need_revision' => 'Revisi',
                            'approved'      => 'Disetujui',
                            'rejected'      => 'Ditolak',
                            'suspended'     => 'Suspend',
                            default         => ucfirst($user->seller_status),
                        } }}
                    </span>
                    <span class="text-[11px] text-slate-500">{{ $user->created_at->format('d M Y, H:i') }}</span>
                </div>

                {{-- Action Button --}}
                <div class="shrink-0 flex sm:block border-t sm:border-t-0 border-slate-800 pt-3 sm:pt-0 mt-1 sm:mt-0">
                    <a href="{{ route('admin.verification.show', $user) }}" class="w-full sm:w-auto inline-flex justify-center items-center gap-2 px-4 py-2 rounded-lg bg-slate-800 hover:bg-sky-500/10 text-slate-300 hover:text-sky-400 text-sm font-semibold border border-slate-700 hover:border-sky-500/30 transition-all">
                        Tinjau
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </a>
                </div>

            </div>

            {{-- Rejection Context (Muncul di bawah baris jika ada) --}}
            @if($user->seller_rejection_reason && in_array($user->seller_status, ['rejected', 'need_revision']))
            <div class="mt-[-8px] mb-4 p-3 px-4 rounded-b-xl bg-red-950/30 border border-t-0 border-red-900/50 flex items-start gap-2 ml-4 mr-4">
                <svg class="w-4 h-4 text-red-400 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                <p class="text-xs text-slate-300"><span class="font-semibold text-red-400">Catatan Admin:</span> {{ $user->seller_rejection_reason }}</p>
            </div>
            @endif
            @endforeach
        </div>

        {{-- Pagination --}}
        <div class="flex justify-center pt-6">
            {{ $users->links() }}
        </div>
        @endif

    </div>
</div>
@endsection
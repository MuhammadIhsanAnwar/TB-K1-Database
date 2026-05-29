@extends('layouts.app')

@section('title', 'Verifikasi Seller — Admin')

@push('styles')
<style>
/* ===================================================================
   ADMIN DASHBOARD · VERIFICATION MODULE
   Aesthetic: Dark Mode Glassmorphism & Cyber/Gaming Accents
   =================================================================== */

.vcard {
    background: linear-gradient(145deg, rgba(15, 23, 42, 0.85), rgba(9, 14, 23, 0.95));
    border: 1px solid rgba(255, 255, 255, 0.06);
    border-radius: 20px;
    backdrop-filter: blur(20px);
    transition: all 0.3s cubic-bezier(0.2, 0.8, 0.2, 1);
}

/* Stat Card Specifics */
a.vcard-stat {
    position: relative;
    overflow: hidden;
}
a.vcard-stat::before {
    content: '';
    position: absolute;
    inset: 0;
    background: radial-gradient(circle at top right, var(--tw-gradient-from) 0%, transparent 70%);
    opacity: 0;
    transition: opacity 0.3s ease;
}
a.vcard-stat:hover {
    transform: translateY(-4px);
    border-color: var(--tw-border-opacity);
    box-shadow: 0 12px 32px -10px var(--tw-shadow-color);
}
a.vcard-stat:hover::before {
    opacity: 0.15;
}

/* Status Badges */
.status-badge {
    display: inline-flex; 
    align-items: center; 
    gap: 6px;
    padding: 4px 12px; 
    border-radius: 999px; 
    font-size: 0.68rem; 
    font-weight: 800; 
    letter-spacing: 0.05em; 
    text-transform: uppercase;
    box-shadow: inset 0 0 0 1px currentColor;
}
.s-pending       { background: rgba(245,158,11,.1); color: #fbbf24; }
.s-under_review  { background: rgba(99,102,241,.1); color: #a5b4fc; }
.s-need_revision { background: rgba(249,115,22,.1); color: #fb923c; }
.s-approved      { background: rgba(16,185,129,.1); color: #34d399; }
.s-rejected      { background: rgba(239,68,68,.1);  color: #f87171; }
.s-suspended     { background: rgba(100,116,139,.1); color: #94a3b8; }

/* Tabs */
.tab-item { 
    padding: 0.6rem 1.25rem; 
    border-radius: 999px; 
    font-size: 0.85rem; 
    font-weight: 600; 
    cursor: pointer; 
    transition: all 0.2s ease; 
    color: #64748b; 
    white-space: nowrap;
    display: inline-flex;
    align-items: center;
    gap: 8px;
}
.tab-item:hover { 
    color: #e2e8f0; 
    background: rgba(255,255,255,.05); 
}
.tab-item.active { 
    color: #fff; 
    background: rgba(56, 189, 248, 0.15);
    box-shadow: inset 0 0 0 1px rgba(56, 189, 248, 0.3);
}
.count-dot { 
    display: inline-flex; 
    align-items: center; 
    justify-content: center; 
    min-width: 20px; 
    height: 20px; 
    border-radius: 999px; 
    font-size: 0.65rem; 
    font-weight: 800; 
}

/* User List Item Hover */
.user-row:hover {
    border-color: rgba(56, 189, 248, 0.25);
    background: linear-gradient(145deg, rgba(15, 23, 42, 0.95), rgba(15, 23, 42, 1));
}

/* Hide Scrollbar for tabs */
.hide-scrollbar::-webkit-scrollbar { display: none; }
.hide-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
</style>
@endpush

@section('content')
<div class="min-h-screen py-8 px-4 relative overflow-hidden">
    {{-- Ambient Background Glow --}}
    <div class="absolute top-0 left-1/2 -translate-x-1/2 w-[800px] h-[400px] bg-sky-500/10 rounded-full blur-[120px] pointer-events-none"></div>

    <div class="mx-auto max-w-7xl space-y-8 relative z-10">

        {{-- ── HEADER ─────────────────────────────────────────────────── --}}
        <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-sky-500/10 border border-sky-500/20 text-sky-400 text-xs font-bold uppercase tracking-widest mb-3">
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                    Pusat Verifikasi
                </div>
                <h1 class="text-3xl sm:text-4xl font-black text-white tracking-tight">Verifikasi Seller</h1>
                <p class="text-slate-400 text-sm mt-2">Tinjau pengajuan toko, klarifikasi dokumen, dan atur status penjual.</p>
            </div>
            <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-white/5 border border-white/10 text-sm font-semibold text-slate-300 hover:bg-white/10 hover:text-white transition-all">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Dashboard
            </a>
        </div>

        {{-- ── STATS CARDS ────────────────────────────────────────────── --}}
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-4">
            @foreach([
                ['pending', 'Menunggu', 'from-amber-400/20', 'border-amber-500/30', 'shadow-amber-500/20', 'text-amber-400', '⏳'],
                ['under_review', 'Direview', 'from-indigo-400/20', 'border-indigo-500/30', 'shadow-indigo-500/20', 'text-indigo-400', '🔍'],
                ['need_revision', 'Revisi', 'from-orange-400/20', 'border-orange-500/30', 'shadow-orange-500/20', 'text-orange-400', '✏️'],
                ['approved', 'Disetujui', 'from-emerald-400/20', 'border-emerald-500/30', 'shadow-emerald-500/20', 'text-emerald-400', '✅'],
                ['rejected', 'Ditolak', 'from-red-400/20', 'border-red-500/30', 'shadow-red-500/20', 'text-red-400', '❌'],
            ] as [$key, $label, $grad, $border, $shadow, $textCol, $icon])
            
            <a href="?tab={{ $key }}" class="vcard vcard-stat p-5 flex flex-col justify-between group {{ $tab === $key ? 'ring-1 ring-white/20 bg-white/5' : '' }}" style="--tw-gradient-from: {{ $grad }}; --tw-border-opacity: {{ $border }}; --tw-shadow-color: {{ $shadow }};">
                <div class="flex items-start justify-between mb-3">
                    <div class="h-10 w-10 rounded-xl bg-white/5 border border-white/10 flex items-center justify-center text-xl shadow-inner group-hover:scale-110 transition-transform duration-300">
                        {{ $icon }}
                    </div>
                </div>
                <div>
                    <div class="font-['Oxanium'] text-3xl font-black {{ $textCol }}">{{ number_format($counts[$key]) }}</div>
                    <div class="text-xs font-semibold text-slate-400 uppercase tracking-wide mt-1">{{ $label }}</div>
                </div>
            </a>
            @endforeach
        </div>

        {{-- ── TABS ───────────────────────────────────────────────────── --}}
        <div class="vcard p-1.5 flex gap-1 overflow-x-auto hide-scrollbar w-max max-w-full">
            @foreach([
                ['pending', '⏳ Pending', 'bg-amber-500/20 text-amber-300'],
                ['under_review', '🔍 Direview', 'bg-indigo-500/20 text-indigo-300'],
                ['need_revision', '✏️ Perlu Revisi', 'bg-orange-500/20 text-orange-300'],
                ['approved', '✅ Disetujui', 'bg-emerald-500/20 text-emerald-300'],
                ['rejected', '❌ Ditolak/Suspend', 'bg-red-500/20 text-red-300'],
            ] as [$key, $label, $cls])
            <a href="?tab={{ $key }}" class="tab-item {{ $tab === $key ? 'active' : '' }}">
                {{ $label }}
                @if($counts[$key] > 0)
                <span class="count-dot {{ $tab === $key ? 'bg-sky-500 text-white' : 'bg-slate-700/50 text-slate-400' }}">
                    {{ $counts[$key] }}
                </span>
                @endif
            </a>
            @endforeach
        </div>

        {{-- ── USER LIST ──────────────────────────────────────────────── --}}
        @if($users->isEmpty())
        <div class="vcard flex flex-col items-center justify-center p-16 text-center border-dashed border-2 border-white/10 bg-transparent">
            <div class="w-20 h-20 bg-slate-800/50 rounded-full flex items-center justify-center text-4xl mb-5 shadow-inner">
                🎉
            </div>
            <h3 class="text-white font-bold text-xl mb-2">Semua Beres!</h3>
            <p class="text-slate-400 text-sm max-w-sm">Tidak ada penjual yang berada pada status <strong class="text-white">{{ str_replace('_', ' ', $tab) }}</strong> saat ini.</p>
        </div>
        @else
        <div class="space-y-4">
            @foreach($users as $user)
            <div class="vcard user-row p-5 sm:p-6 cursor-pointer" onclick="window.location='{{ route('admin.verification.show', $user) }}'">
                <div class="flex flex-col sm:flex-row sm:items-center gap-5">
                    
                    {{-- Avatar & Shop Identity --}}
                    <div class="flex items-center gap-4 flex-1 min-w-0">
                        <div class="relative shrink-0">
                            <img src="{{ $user->shop_photo ? asset('storage/' . $user->shop_photo) : $user->avatar_url }}"
                                 alt="{{ $user->name }}"
                                 class="w-16 h-16 rounded-2xl object-cover border border-slate-700 shadow-md">
                            <div class="absolute -bottom-2 -right-2 bg-slate-900 rounded-full p-0.5">
                                <span class="status-badge s-{{ $user->seller_status }} scale-[0.8] origin-bottom-right">
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
                            </div>
                        </div>

                        <div class="min-w-0">
                            <h3 class="font-bold text-white text-lg truncate flex items-center gap-2">
                                {{ $user->shop_name ?? $user->name }}
                                @if($user->seller_status === 'approved')
                                    <svg class="w-4 h-4 text-emerald-400 shrink-0" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                                @endif
                            </h3>
                            <div class="flex flex-wrap items-center gap-2 text-sm text-slate-400 mt-1">
                                <span class="truncate">👤 {{ $user->name }}</span>
                                <span class="w-1 h-1 rounded-full bg-slate-600"></span>
                                <span class="truncate">✉️ {{ $user->email }}</span>
                            </div>
                        </div>
                    </div>

                    {{-- Action & Time --}}
                    <div class="flex sm:flex-col items-center sm:items-end justify-between shrink-0 gap-3 border-t sm:border-t-0 border-white/5 pt-4 sm:pt-0">
                        <div class="text-xs font-medium text-slate-500 bg-slate-800/50 px-3 py-1.5 rounded-lg border border-slate-700/50">
                            🕒 {{ $user->created_at->diffForHumans() }}
                        </div>
                        
                        <a href="{{ route('admin.verification.show', $user) }}" class="inline-flex items-center gap-1.5 px-5 py-2.5 rounded-xl bg-sky-500/10 text-sky-400 text-sm font-bold border border-sky-500/20 hover:bg-sky-500 hover:text-white hover:border-sky-400 transition-all shadow-[0_0_15px_rgba(14,165,233,0.1)] hover:shadow-[0_0_20px_rgba(14,165,233,0.4)]">
                            Tinjau Data
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                        </a>
                    </div>
                </div>

                {{-- Rejection Reason Context (If Any) --}}
                @if($user->seller_rejection_reason && in_array($user->seller_status, ['rejected', 'need_revision']))
                <div class="mt-4 p-4 rounded-xl bg-red-500/5 border border-red-500/10 flex items-start gap-3">
                    <svg class="w-5 h-5 text-red-400 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <div>
                        <p class="text-xs font-bold text-red-400 mb-0.5">Catatan Penolakan / Revisi:</p>
                        <p class="text-sm text-slate-300 leading-relaxed">{{ $user->seller_rejection_reason }}</p>
                    </div>
                </div>
                @endif
            </div>
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
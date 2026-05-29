@extends('layouts.app')

@section('title', 'Verifikasi Seller — Admin')

@push('styles')
<style>
/* ==========================================================
   MODERN MARKETPLACE ADMIN
   Premium Colorful Dashboard
   ========================================================== */

:root{
    --grad-primary: linear-gradient(135deg,#7c3aed 0%,#2563eb 50%,#06b6d4 100%);
    --grad-success: linear-gradient(135deg,#10b981,#22c55e);
    --grad-warning: linear-gradient(135deg,#f59e0b,#f97316);
    --grad-danger: linear-gradient(135deg,#ef4444,#dc2626);
    --grad-info: linear-gradient(135deg,#3b82f6,#06b6d4);
}

body{
    background:
        radial-gradient(circle at top left,
            rgba(124,58,237,.15),
            transparent 30%),
        radial-gradient(circle at top right,
            rgba(6,182,212,.15),
            transparent 30%),
        var(--color-background-primary);
}

/* HEADER */

.admin-header-title{
    font-size:2.2rem;
    font-weight:900;
    letter-spacing:-0.04em;
    background:var(--grad-primary);
    -webkit-background-clip:text;
    -webkit-text-fill-color:transparent;
}

.admin-header-sub{
    color:var(--color-text-secondary);
    font-size:.95rem;
}

/* CARD */

.vcard{
    backdrop-filter:blur(20px);
    background:rgba(255,255,255,.03);
    border:1px solid rgba(255,255,255,.08);
    border-radius:20px;
    box-shadow:
        0 20px 50px rgba(0,0,0,.12);
}

/* STAT CARD */

.stat-card{
    position:relative;
    overflow:hidden;
    border-radius:20px;
    padding:1.25rem;
    transition:.3s ease;
    border:none;
    color:white;
}

.stat-card::before{
    content:'';
    position:absolute;
    inset:0;
    opacity:.95;
}

.stat-card:nth-child(1)::before{
    background:var(--grad-warning);
}

.stat-card:nth-child(2)::before{
    background:var(--grad-info);
}

.stat-card:nth-child(3)::before{
    background:linear-gradient(135deg,#ec4899,#f59e0b);
}

.stat-card:nth-child(4)::before{
    background:var(--grad-success);
}

.stat-card:nth-child(5)::before{
    background:var(--grad-danger);
}

.stat-card > *{
    position:relative;
    z-index:2;
}

.stat-card:hover{
    transform:translateY(-6px) scale(1.02);
    box-shadow:
        0 20px 40px rgba(0,0,0,.25);
}

.stat-card.active{
    outline:3px solid rgba(255,255,255,.4);
}

.stat-card .text-3xl{
    color:white !important;
}

/* TABS */

.tab-container{
    display:flex;
    gap:6px;
    padding:6px;
    border-radius:16px;
    background:rgba(255,255,255,.03);
    border:1px solid rgba(255,255,255,.08);
    backdrop-filter:blur(12px);
}

.tab-item{
    padding:.75rem 1rem;
    border-radius:12px;
    font-weight:700;
    transition:.25s;
}

.tab-item:hover{
    background:rgba(255,255,255,.05);
}

.tab-item.active{
    background:var(--grad-primary);
    color:white;
    box-shadow:
        0 8px 20px rgba(99,102,241,.35);
}

.tab-count{
    background:white;
    color:#111827;
    font-weight:800;
}

/* DATA ROW */

.data-row{
    position:relative;
    overflow:hidden;
    border:none;
    border-radius:20px;
    backdrop-filter:blur(18px);

    background:
        linear-gradient(
            135deg,
            rgba(255,255,255,.04),
            rgba(255,255,255,.02)
        );

    transition:.3s ease;
}

.data-row::before{
    content:'';
    position:absolute;
    left:0;
    top:0;
    bottom:0;
    width:4px;
    background:var(--grad-primary);
    opacity:0;
    transition:.3s;
}

.data-row:hover{
    transform:translateY(-3px);
    box-shadow:
        0 20px 35px rgba(0,0,0,.15);
}

.data-row:hover::before{
    opacity:1;
}

/* AVATAR */

.avatar-box{
    width:60px;
    height:60px;
    border-radius:18px;
    border:none;
    box-shadow:
        0 10px 25px rgba(0,0,0,.15);
}

.avatar-initials{
    background:var(--grad-primary);
    color:white;
    font-weight:900;
    font-size:1rem;
}

/* STATUS */

.status-badge{
    font-size:.68rem;
    border:none;
    font-weight:800;
    letter-spacing:.08em;
    padding:6px 14px;
    border-radius:999px;
}

.s-pending{
    background:rgba(245,158,11,.15);
    color:#fbbf24;
    box-shadow:0 0 15px rgba(245,158,11,.25);
}

.s-under_review{
    background:rgba(59,130,246,.15);
    color:#60a5fa;
    box-shadow:0 0 15px rgba(59,130,246,.25);
}

.s-need_revision{
    background:rgba(236,72,153,.15);
    color:#f472b6;
    box-shadow:0 0 15px rgba(236,72,153,.25);
}

.s-approved{
    background:rgba(16,185,129,.15);
    color:#34d399;
    box-shadow:0 0 20px rgba(16,185,129,.35);
}

.s-rejected,
.s-suspended{
    background:rgba(239,68,68,.15);
    color:#f87171;
    box-shadow:0 0 15px rgba(239,68,68,.25);
}

/* BUTTON */

.btn-outline{
    border:none;
    border-radius:12px;
    padding:.7rem 1rem;
    font-weight:700;

    background:
        linear-gradient(
            135deg,
            rgba(124,58,237,.15),
            rgba(37,99,235,.15)
        );

    transition:.25s ease;
}

.btn-outline:hover{
    transform:translateY(-2px);
    background:var(--grad-primary);
    color:white;
}

/* REJECTION NOTE */

.rejection-box{
    margin-top:.75rem;
    border:none;

    background:
        linear-gradient(
            135deg,
            rgba(239,68,68,.08),
            rgba(239,68,68,.02)
        );

    border-left:4px solid #ef4444;
    border-radius:14px;
}

/* EMPTY STATE */

.vcard.border-dashed{
    border:2px dashed rgba(255,255,255,.08);
    border-radius:24px;
}

/* PAGINATION */

.pagination{
    gap:.5rem;
}

.pagination .page-link{
    border:none;
    border-radius:12px;
}

.pagination .active .page-link{
    background:var(--grad-primary);
}
</style>
@endpush

@section('content')
<div class="min-h-screen py-8 px-4">
<div class="mx-auto max-w-7xl space-y-6">

    {{-- Header --}}
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <p class="text-xs uppercase tracking-widest text-amber-400 font-bold mb-1">Admin Panel</p>
            <h1 class="text-3xl font-black text-white">Verifikasi Seller</h1>
            <p class="text-slate-400 text-sm mt-1">Kelola pengajuan, klarifikasi, dan status verifikasi penjual.</p>
        </div>
        <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center gap-2 text-sm text-slate-400 hover:text-white transition-colors">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Kembali ke Dashboard
        </a>
    </div>

    {{-- Stats Cards --}}
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-3">
        @foreach([
            ['pending', 'Pending', '#fbbf24', 'rgba(245,158,11,.1)', '⏳'],
            ['under_review', 'Direview', '#a5b4fc', 'rgba(99,102,241,.1)', '🔍'],
            ['need_revision', 'Perlu Revisi', '#fb923c', 'rgba(249,115,22,.1)', '✏️'],
            ['approved', 'Disetujui', '#34d399', 'rgba(16,185,129,.1)', '✅'],
            ['rejected', 'Ditolak/Suspend', '#f87171', 'rgba(239,68,68,.1)', '❌'],
        ] as [$key, $label, $color, $bg, $icon])
        <a href="?tab={{ $key }}" class="vcard p-4 {{ $tab === $key ? 'border-amber-500/40' : '' }}">
            <div class="text-xl mb-2">{{ $icon }}</div>
            <div class="text-2xl font-black text-white">{{ number_format($counts[$key]) }}</div>
            <div class="text-xs text-slate-400 mt-1">{{ $label }}</div>
        </a>
        @endforeach
    </div>

    {{-- Tabs --}}
    <div class="vcard p-1 flex gap-1 overflow-x-auto">
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
            <span class="count-dot {{ $tab === $key ? 'bg-amber-500/30 text-amber-200' : 'bg-slate-700 text-slate-300' }}">
                {{ $counts[$key] }}
            </span>
            @endif
        </a>
        @endforeach
    </div>

    {{-- User List --}}
    @if($users->isEmpty())
    <div class="vcard p-16 text-center">
        <div class="text-5xl mb-4">🎉</div>
        <h3 class="text-white font-bold text-lg">Tidak ada data</h3>
        <p class="text-slate-400 text-sm mt-1">Belum ada pengajuan dengan status ini.</p>
    </div>
    @else
    <div class="space-y-3">
        @foreach($users as $user)
        <div class="vcard p-5 hover:cursor-pointer" onclick="window.location='{{ route('admin.verification.show', $user) }}'">
            <div class="flex items-start gap-4">
                {{-- Avatar --}}
                <img src="{{ $user->shop_photo ? asset('storage/' . $user->shop_photo) : $user->avatar_url }}"
                     alt="{{ $user->name }}"
                     class="w-14 h-14 rounded-2xl object-cover shrink-0 border border-slate-700">

                {{-- Info --}}
                <div class="flex-1 min-w-0">
                    <div class="flex flex-wrap items-center gap-2 mb-1">
                        <h3 class="font-bold text-white text-base truncate">{{ $user->name }}</h3>
                        <span class="status-badge s-{{ $user->seller_status }}">
                            {{ match($user->seller_status) {
                                'pending'       => 'Pending',
                                'under_review'  => 'Direview',
                                'need_revision' => 'Perlu Revisi',
                                'approved'      => 'Disetujui',
                                'rejected'      => 'Ditolak',
                                'suspended'     => 'Suspend',
                                default         => ucfirst($user->seller_status),
                            } }}
                        </span>
                    </div>
                    <p class="text-sm text-slate-400 truncate">{{ $user->email }}</p>
                    @if($user->shop_name)
                    <p class="text-sm font-medium text-slate-300 mt-1">🏪 {{ $user->shop_name }}</p>
                    @endif
                    @if($user->shop_description)
                    <p class="text-xs text-slate-500 mt-1 line-clamp-2">{{ $user->shop_description }}</p>
                    @endif
                </div>

                {{-- Date + Action --}}
                <div class="shrink-0 text-right">
                    <p class="text-xs text-slate-500">
                        {{ $user->created_at->diffForHumans() }}
                    </p>
                    <a href="{{ route('admin.verification.show', $user) }}"
                       class="mt-3 inline-flex items-center gap-1 px-4 py-2 rounded-xl bg-amber-500/15 text-amber-300 text-xs font-bold hover:bg-amber-500/25 transition-colors">
                        Review
                        <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </a>
                </div>
            </div>

            {{-- Rejection reason if any --}}
            @if($user->seller_rejection_reason)
            <div class="mt-3 p-3 rounded-xl bg-red-900/20 border border-red-800/30">
                <p class="text-xs text-red-300"><span class="font-bold">Alasan:</span> {{ $user->seller_rejection_reason }}</p>
            </div>
            @endif
        </div>
        @endforeach
    </div>

    {{-- Pagination --}}
    <div class="flex justify-center pt-2">
        {{ $users->links() }}
    </div>
    @endif

</div>
</div>
@endsection
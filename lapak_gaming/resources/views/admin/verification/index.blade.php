@extends('layouts.app')

@section('title', 'Verifikasi Seller — Admin')

@push('styles')
<style>
    .admin-bg {
        background: radial-gradient(ellipse at 20% 50%, #0f172a 0%, #020617 100%);
        min-height: 100vh;
    }

    .glass-card {
        background: rgba(15, 23, 42, 0.7);
        backdrop-filter: blur(16px);
        -webkit-backdrop-filter: blur(16px);
        border: 1px solid rgba(71, 85, 105, 0.25);
        border-radius: 20px;
        box-shadow: 0 4px 24px rgba(0, 0, 0, 0.3);
        transition: all 0.25s ease;
    }

    .glass-card:hover {
        border-color: rgba(245, 158, 11, 0.4);
        box-shadow: 0 8px 32px rgba(245, 158, 11, 0.08);
        transform: translateY(-2px);
    }

    .glass-card-interactive {
        cursor: pointer;
    }
    .glass-card-interactive:hover {
        transform: translateY(-3px) scale(1.01);
        border-color: rgba(245, 158, 11, 0.45);
        box-shadow: 0 12px 40px rgba(245, 158, 11, 0.1);
    }

    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 3px 10px;
        border-radius: 999px;
        font-size: 0.7rem;
        font-weight: 700;
        letter-spacing: 0.04em;
        text-transform: uppercase;
        backdrop-filter: blur(4px);
        -webkit-backdrop-filter: blur(4px);
    }

    .s-pending      { background: rgba(245,158,11,0.12); color: #fbbf24; border:1px solid rgba(245,158,11,0.25); }
    .s-under_review { background: rgba(99,102,241,0.12); color: #a5b4fc; border:1px solid rgba(99,102,241,0.25); }
    .s-need_revision{ background: rgba(249,115,22,0.12); color: #fb923c; border:1px solid rgba(249,115,22,0.25); }
    .s-approved     { background: rgba(16,185,129,0.12); color: #34d399; border:1px solid rgba(16,185,129,0.25); }
    .s-rejected     { background: rgba(239,68,68,0.12);  color: #f87171; border:1px solid rgba(239,68,68,0.25); }
    .s-suspended    { background: rgba(100,116,139,0.12); color: #94a3b8; border:1px solid rgba(100,116,139,0.25); }

    .tab-pill {
        display: inline-flex;
        align-items: center;
        padding: 0.55rem 1.25rem;
        border-radius: 999px;
        font-size: 0.82rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s;
        color: #94a3b8;
        white-space: nowrap;
        background: transparent;
        border: 1px solid transparent;
    }
    .tab-pill:hover {
        color: #e2e8f0;
        background: rgba(255,255,255,0.06);
        border-color: rgba(255,255,255,0.08);
    }
    .tab-pill.active {
        color: #fff;
        background: rgba(245,158,11,0.18);
        border-color: rgba(245,158,11,0.4);
        box-shadow: 0 0 18px rgba(245,158,11,0.15);
    }

    .count-badge {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 20px;
        height: 20px;
        border-radius: 999px;
        font-size: 0.65rem;
        font-weight: 700;
        margin-left: 6px;
        padding: 0 5px;
    }

    .empty-illustration {
        opacity: 0.7;
    }
</style>
@endpush

@section('content')
<div class="admin-bg py-8 px-4">
<div class="mx-auto max-w-7xl space-y-8">

    {{-- Header --}}
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <p class="text-xs uppercase tracking-widest text-amber-400/80 font-bold mb-1">Admin Panel</p>
            <h1 class="text-3xl sm:text-4xl font-black text-white tracking-tight">Verifikasi Seller</h1>
            <p class="text-slate-400 text-sm mt-1 max-w-xl">Kelola pengajuan, klarifikasi, dan pantau status verifikasi setiap penjual.</p>
        </div>
        <a href="{{ route('admin.dashboard') }}" 
           class="inline-flex items-center gap-2 text-sm font-medium text-slate-300 hover:text-white bg-white/5 hover:bg-white/10 px-4 py-2 rounded-xl transition-all">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Kembali ke Dashboard
        </a>
    </div>

    {{-- Stats Cards --}}
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-4">
        @php
            $stats = [
                ['pending', 'Pending', '#fbbf24', 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z'],
                ['under_review', 'Direview', '#a5b4fc', 'M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z'],
                ['need_revision', 'Perlu Revisi', '#fb923c', 'M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z'],
                ['approved', 'Disetujui', '#34d399', 'M5 13l4 4L19 7'],
                ['rejected', 'Ditolak/Suspend', '#f87171', 'M6 18L18 6M6 6l12 12'],
            ];
        @endphp
        @foreach($stats as [$key, $label, $color, $path])
        <a href="?tab={{ $key }}" class="glass-card p-4 flex items-start gap-3 {{ $tab === $key ? 'ring-1 ring-amber-500/50' : '' }}">
            <div class="p-2 rounded-xl" style="background: {{ $color }}15;">
                <svg class="w-6 h-6" style="color: {{ $color }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="{{ $path }}"/>
                </svg>
            </div>
            <div>
                <div class="text-2xl font-black text-white">{{ number_format($counts[$key]) }}</div>
                <div class="text-xs text-slate-400 mt-0.5">{{ $label }}</div>
            </div>
        </a>
        @endforeach
    </div>

    {{-- Tabs --}}
    <div class="glass-card p-1.5 flex gap-1 overflow-x-auto scrollbar-hide">
        @php
            $tabs = [
                ['pending', 'Pending', 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z', '#fbbf24'],
                ['under_review', 'Direview', 'M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z', '#a5b4fc'],
                ['need_revision', 'Perlu Revisi', 'M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z', '#fb923c'],
                ['approved', 'Disetujui', 'M5 13l4 4L19 7', '#34d399'],
                ['rejected', 'Ditolak/Suspend', 'M6 18L18 6M6 6l12 12', '#f87171'],
            ];
        @endphp
        @foreach($tabs as [$key, $label, $path, $color])
        <a href="?tab={{ $key }}" class="tab-pill {{ $tab === $key ? 'active' : '' }}">
            <svg class="w-4 h-4 mr-1.5" style="color: {{ $tab === $key ? '#fff' : $color }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="{{ $path }}"/>
            </svg>
            {{ $label }}
            @if($counts[$key] > 0)
            <span class="count-badge {{ $tab === $key ? 'bg-white/20 text-white' : 'bg-slate-800 text-slate-400' }}">
                {{ $counts[$key] }}
            </span>
            @endif
        </a>
        @endforeach
    </div>

    {{-- User List --}}
    @if($users->isEmpty())
    <div class="glass-card p-16 text-center">
        <div class="empty-illustration mb-6">
            <svg class="w-32 h-32 mx-auto" viewBox="0 0 200 200" fill="none">
                <circle cx="100" cy="100" r="80" stroke="#1e293b" stroke-width="2" stroke-dasharray="8 8"/>
                <rect x="70" y="70" width="60" height="60" rx="12" stroke="#334155" stroke-width="2"/>
                <path d="M85 90h30M85 100h20M85 110h15" stroke="#475569" stroke-width="2" stroke-linecap="round"/>
                <circle cx="130" cy="70" r="12" fill="#1e293b" stroke="#475569" stroke-width="2"/>
                <path d="M125 70l5-5 5 5" stroke="#64748b" stroke-width="2" stroke-linecap="round"/>
            </svg>
        </div>
        <h3 class="text-white font-bold text-xl">Belum ada pengajuan</h3>
        <p class="text-slate-400 text-sm mt-1">Data dengan status <strong class="text-amber-300">{{ $tab }}</strong> belum tersedia.</p>
    </div>
    @else
    <div class="space-y-4">
        @foreach($users as $user)
        <div class="glass-card glass-card-interactive p-5" onclick="window.location='{{ route('admin.verification.show', $user) }}'">
            <div class="flex items-start gap-4">
                {{-- Avatar --}}
                <div class="relative shrink-0">
                    <img src="{{ $user->shop_photo ? asset('storage/' . $user->shop_photo) : $user->avatar_url }}"
                         alt="{{ $user->name }}"
                         class="w-14 h-14 rounded-2xl object-cover border-2 border-slate-700/60 shadow-lg">
                    @if($user->seller_status === 'approved')
                    <span class="absolute -bottom-1 -right-1 w-5 h-5 bg-emerald-500 rounded-full flex items-center justify-center border-2 border-slate-900">
                        <svg class="w-3 h-3 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                    </span>
                    @endif
                </div>

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
                    <p class="text-sm font-medium text-slate-300 mt-1 flex items-center gap-1.5">
                        <svg class="w-4 h-4 text-slate-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 21V7a2 2 0 012-2h4a2 2 0 012 2v14m-8 0h8m-8 0H6m10 0h2"/>
                        </svg>
                        {{ $user->shop_name }}
                    </p>
                    @endif
                    @if($user->shop_description)
                    <p class="text-xs text-slate-500 mt-1 line-clamp-2 leading-relaxed">{{ $user->shop_description }}</p>
                    @endif
                </div>

                {{-- Date + Action --}}
                <div class="shrink-0 text-right flex flex-col items-end">
                    <p class="text-xs text-slate-500 mb-2">{{ $user->created_at->diffForHumans() }}</p>
                    <a href="{{ route('admin.verification.show', $user) }}"
                       class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-amber-500/15 text-amber-300 text-xs font-bold hover:bg-amber-500/25 transition-all border border-amber-500/20 hover:border-amber-500/40">
                        Review
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </a>
                </div>
            </div>

            {{-- Rejection reason --}}
            @if($user->seller_rejection_reason)
            <div class="mt-3 p-3 rounded-xl bg-red-900/20 border border-red-800/30 flex gap-2">
                <svg class="w-4 h-4 text-red-400 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
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
@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const cards = document.querySelectorAll('.spotlight-card');
        cards.forEach(card => {
            card.addEventListener('mousemove', e => {
                const rect = card.getBoundingClientRect();
                const x = e.clientX - rect.left;
                const y = e.clientY - rect.top;
                card.style.background = `radial-gradient(circle at ${x}px ${y}px, rgba(245,158,11,0.10), rgba(15,23,42,0.7) 45%)`;
            });
            card.addEventListener('mouseleave', () => {
                card.style.background = ''; // kembali ke default CSS glass-card
            });
        });
    });
</script>
@endpush
@endsection
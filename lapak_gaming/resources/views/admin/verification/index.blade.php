@extends('layouts.app')

@section('title', 'Verifikasi Seller — Admin')

@push('styles')
<style>
/* ===================================================================
   ADMIN DASHBOARD · ENTERPRISE VERIFICATION
   Aesthetic: Clean SaaS, Data-Dense, High Readability
   =================================================================== */

/* ── WRAPPERS & CARDS ── */
.admin-header-title { color: var(--color-text-primary); font-weight: 900; letter-spacing: -0.02em; }
.admin-header-sub { color: var(--color-text-secondary); }

.vcard {
    background: var(--color-background-primary, rgba(13,20,33,.9));
    border: 1px solid var(--color-border-tertiary);
    border-radius: 16px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.05);
}

/* ── STAT CARDS ── */
.stat-card {
    display: flex; flex-direction: column; justify-content: space-between;
    background: var(--color-background-secondary);
    border: 1px solid var(--color-border-tertiary);
    border-radius: 14px; 
    padding: 1.1rem 1.25rem;
    text-decoration: none;
    transition: all .2s ease;
}
.stat-card:hover { 
    border-color: var(--color-border-secondary); 
    transform: translateY(-2px); 
}
.stat-card.active { 
    background: var(--color-background-primary); 
    border-color: var(--color-border-warning); 
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
}

/* ── TABS (SEGMENTED CONTROL) ── */
.tab-container {
    display: inline-flex; gap: 4px; padding: 4px;
    background: var(--color-background-secondary);
    border: 1px solid var(--color-border-tertiary);
    border-radius: 12px;
    overflow-x: auto;
}
.tab-item {
    display: inline-flex; align-items: center; gap: 6px;
    padding: 0.5rem 1rem; border-radius: 8px;
    font-size: 0.8rem; font-weight: 600; cursor: pointer;
    color: var(--color-text-secondary); white-space: nowrap;
    transition: all .2s ease;
}
.tab-item:hover { color: var(--color-text-primary); }
.tab-item.active {
    background: var(--color-background-primary);
    color: var(--color-text-warning);
    box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    border: 1px solid var(--color-border-tertiary);
}
.tab-count {
    display: inline-flex; align-items: center; justify-content: center;
    min-width: 20px; height: 20px; border-radius: 999px;
    font-size: 0.65rem; font-weight: 800; padding: 0 6px;
    background: var(--color-background-primary);
    color: var(--color-text-secondary);
    border: 1px solid var(--color-border-tertiary);
}
.tab-item.active .tab-count {
    background: var(--color-background-warning);
    color: var(--color-text-primary);
    border-color: transparent;
}

/* ── DATA ROWS (LIST VIEW) ── */
.data-row {
    display: flex; align-items: center; gap: 1.5rem;
    padding: 1.25rem 1.5rem;
    background: var(--color-background-primary);
    border: 1px solid var(--color-border-tertiary);
    border-radius: 14px;
    transition: all .2s ease;
    cursor: pointer;
}
.data-row:hover {
    border-color: var(--color-border-secondary);
    background: var(--color-background-secondary);
}

/* ── BADGES & BUTTONS ── */
.status-badge {
    display: inline-flex; align-items: center; justify-content: center;
    padding: 4px 12px; border-radius: 6px;
    font-size: 0.7rem; font-weight: 700; letter-spacing: 0.05em; text-transform: uppercase;
    border: 1px solid transparent;
}
.s-pending       { background: var(--color-background-warning); color: var(--color-text-warning); border-color: var(--color-border-warning); }
.s-under_review  { background: var(--color-background-info);    color: var(--color-text-info);    border-color: var(--color-border-info); }
.s-need_revision { background: var(--color-background-warning); color: var(--color-text-warning); border-color: var(--color-border-warning); }
.s-approved      { background: var(--color-background-success); color: var(--color-text-success); border-color: var(--color-border-success); }
.s-rejected      { background: var(--color-background-danger);  color: var(--color-text-danger);  border-color: var(--color-border-danger); }
.s-suspended     { background: var(--color-background-secondary); color: var(--color-text-secondary); border-color: var(--color-border-secondary); }

.btn-outline {
    display: inline-flex; align-items: center; gap: 6px;
    padding: 8px 16px; border-radius: 8px;
    font-size: 0.75rem; font-weight: 700;
    background: var(--color-background-primary);
    color: var(--color-text-primary);
    border: 1px solid var(--color-border-tertiary);
    transition: all .2s ease;
}
.btn-outline:hover { background: var(--color-background-secondary); border-color: var(--color-border-secondary); }
.data-row:hover .btn-outline { background: var(--color-background-primary); border-color: var(--color-text-primary); }

/* ── MISC ── */
.avatar-box {
    width: 52px; height: 52px; border-radius: 12px;
    object-fit: cover; flex-shrink: 0;
    border: 1px solid var(--color-border-tertiary);
    background: var(--color-background-secondary);
}
.avatar-initials {
    display: flex; align-items: center; justify-content: center;
    font-size: 1rem; font-weight: 700;
    color: var(--color-text-info);
}
.rejection-box {
    display: flex; align-items: flex-start; gap: 10px;
    margin-top: 1rem; padding: 0.85rem 1rem;
    background: var(--color-background-danger);
    border-left: 3px solid var(--color-border-danger);
    border-radius: 0 8px 8px 0;
}
/* Hide scrollbar for tab container */
.hide-scroll::-webkit-scrollbar { display: none; }
.hide-scroll { -ms-overflow-style: none; scrollbar-width: none; }
</style>
@endpush

@section('content')
<div class="min-h-screen py-8 px-4">
<div class="mx-auto max-w-7xl space-y-8">

    {{-- ── HEADER ── --}}
    <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <div class="inline-flex items-center gap-2 px-3 py-1 mb-3 rounded-full border" style="background: var(--color-background-warning); border-color: var(--color-border-warning); color: var(--color-text-warning);">
                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                </svg>
                <span class="text-[10px] font-bold uppercase tracking-widest">Pusat Verifikasi</span>
            </div>
            <h1 class="text-3xl admin-header-title">Pengajuan Penjual</h1>
            <p class="text-sm mt-1 admin-header-sub">Tinjau kelengkapan dokumen dan atur izin buka toko seller.</p>
        </div>
        <a href="{{ route('admin.dashboard') }}" class="btn-outline">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Kembali ke Dashboard
        </a>
    </div>

    {{-- ── STATS CARDS ── --}}
    <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
        @php
        $statItems = [
            ['pending',       'Menunggu',        'var(--color-text-warning)', 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z'],
            ['under_review',  'Direview',        'var(--color-text-info)',    'M15 12a3 3 0 11-6 0 3 3 0 016 0zM2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z'],
            ['need_revision', 'Revisi',          'var(--color-text-warning)', 'M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z'],
            ['approved',      'Disetujui',       'var(--color-text-success)', 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'],
            ['rejected',      'Ditolak/Suspend', 'var(--color-text-danger)',  'M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z'],
        ];
        @endphp

        @foreach($statItems as [$key, $label, $colorVar, $iconPath])
        <a href="?tab={{ $key }}" class="stat-card {{ $tab === $key ? 'active' : '' }}">
            <div class="flex items-center justify-between mb-4">
                <span class="text-xs font-bold uppercase tracking-wider" style="color: var(--color-text-secondary);">{{ $label }}</span>
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="color: {{ $colorVar }};">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $iconPath }}"/>
                </svg>
            </div>
            <div class="text-3xl font-black" style="color: var(--color-text-primary);">
                {{ number_format($counts[$key] ?? 0) }}
            </div>
        </a>
        @endforeach
    </div>

    {{-- ── TABS ── --}}
    <div class="w-full hide-scroll overflow-x-auto pb-2">
        <div class="tab-container">
            @foreach([
                ['pending',       'Pending'],
                ['under_review',  'Direview'],
                ['need_revision', 'Perlu Revisi'],
                ['approved',      'Disetujui'],
                ['rejected',      'Ditolak/Suspend'],
            ] as [$key, $label])
            <a href="?tab={{ $key }}" class="tab-item {{ $tab === $key ? 'active' : '' }}">
                {{ $label }}
                @if(($counts[$key] ?? 0) > 0)
                <span class="tab-count">{{ $counts[$key] }}</span>
                @endif
            </a>
            @endforeach
        </div>
    </div>

    {{-- ── USER LIST ── --}}
    @if($users->isEmpty())
    <div class="vcard flex flex-col items-center justify-center p-16 text-center border-dashed">
        <div class="w-16 h-16 rounded-full flex items-center justify-center mb-4" style="background: var(--color-background-secondary);">
            <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="color: var(--color-text-secondary);">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
            </svg>
        </div>
        <h3 class="font-bold text-lg" style="color: var(--color-text-primary);">Tidak ada data</h3>
        <p class="text-sm mt-1" style="color: var(--color-text-secondary);">
            Daftar pengajuan dengan status <strong style="color: var(--color-text-primary);">{{ str_replace('_', ' ', $tab) }}</strong> sedang kosong.
        </p>
    </div>

    @else
    <div class="space-y-3">
        @foreach($users as $user)
        @php
            $initials = collect(explode(' ', $user->name))->take(2)->map(fn($w) => strtoupper($w[0] ?? ''))->implode('');
        @endphp

        <div class="flex flex-col">
            <div class="data-row flex-col sm:flex-row items-start sm:items-center" onclick="window.location='{{ route('admin.verification.show', $user) }}'">
                
                {{-- Avatar --}}
                @if($user->shop_photo)
                    <img src="{{ asset('storage/' . $user->shop_photo) }}" alt="{{ $user->name }}" class="avatar-box">
                @else
                    <div class="avatar-box avatar-initials">{{ $initials }}</div>
                @endif

                {{-- User & Shop Info --}}
                <div class="flex-1 min-w-0 flex flex-col justify-center">
                    <div class="flex items-center gap-2 mb-1">
                        <h3 class="font-bold text-base truncate" style="color: var(--color-text-primary);">
                            {{ $user->shop_name ?? $user->name }}
                        </h3>
                        @if($user->seller_status === 'approved')
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20" style="color: var(--color-text-success);"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                        @endif
                    </div>
                    
                    <div class="flex items-center gap-2 text-xs truncate" style="color: var(--color-text-secondary);">
                        <span class="truncate">{{ $user->name }}</span>
                        <span class="w-1 h-1 rounded-full" style="background: var(--color-border-tertiary);"></span>
                        <span class="truncate">{{ $user->email }}</span>
                    </div>
                </div>

                {{-- Status, Date & Action --}}
                <div class="flex items-center gap-6 mt-4 sm:mt-0 w-full sm:w-auto justify-between sm:justify-end shrink-0">
                    <div class="flex flex-col items-start sm:items-end gap-1">
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
                        <span class="text-[11px] font-medium" style="color: var(--color-text-secondary);">
                            {{ $user->created_at->format('d M Y') }}
                        </span>
                    </div>
                    
                    <button class="btn-outline" onclick="event.stopPropagation(); window.location='{{ route('admin.verification.show', $user) }}'">
                        Tinjau
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </button>
                </div>
            </div>

            {{-- Rejection Note (Placed right below the row seamlessly) --}}
            @if($user->seller_rejection_reason && in_array($user->seller_status, ['rejected', 'need_revision']))
            <div class="rejection-box mx-4 mb-2">
                <svg class="w-4 h-4 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="color: var(--color-text-danger);">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <div class="text-xs" style="color: var(--color-text-danger);">
                    <strong class="block mb-0.5 font-bold">Catatan Admin:</strong>
                    <span style="opacity: 0.9;">{{ $user->seller_rejection_reason }}</span>
                </div>
            </div>
            @endif
        </div>
        @endforeach
    </div>

    {{-- Pagination --}}
    <div class="flex justify-center pt-4">
        {{ $users->appends(['tab' => $tab])->links() }}
    </div>
    @endif

</div>
</div>
@endsection
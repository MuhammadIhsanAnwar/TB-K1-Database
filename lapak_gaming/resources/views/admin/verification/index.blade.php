@extends('layouts.app')

@section('title', 'Verifikasi Seller — Admin')

@push('styles')
<style>
.vcard {
    background: var(--color-background-primary, rgba(13,20,33,.9));
    border: 0.5px solid var(--color-border-tertiary);
    border-radius: 16px;
    transition: border-color .2s;
}
.vcard:hover { border-color: var(--color-border-secondary); }

.status-badge {
    display: inline-flex; align-items: center; gap: 5px;
    padding: 2px 10px; border-radius: 999px;
    font-size: .7rem; font-weight: 600; letter-spacing: .03em; text-transform: uppercase;
    border: 0.5px solid transparent;
}
.s-pending       { background: var(--color-background-warning); color: var(--color-text-warning); border-color: var(--color-border-warning); }
.s-under_review  { background: var(--color-background-info);    color: var(--color-text-info);    border-color: var(--color-border-info); }
.s-need_revision { background: var(--color-background-warning); color: var(--color-text-warning); border-color: var(--color-border-warning); }
.s-approved      { background: var(--color-background-success); color: var(--color-text-success); border-color: var(--color-border-success); }
.s-rejected      { background: var(--color-background-danger);  color: var(--color-text-danger);  border-color: var(--color-border-danger); }
.s-suspended     { background: var(--color-background-secondary); color: var(--color-text-secondary); border-color: var(--color-border-secondary); }

.tab-item {
    display: inline-flex; align-items: center; gap: 6px;
    padding: .45rem 1rem; border-radius: 999px;
    font-size: .8rem; font-weight: 600; cursor: pointer;
    transition: background .15s, color .15s;
    color: var(--color-text-secondary); white-space: nowrap;
    border: 0.5px solid transparent;
}
.tab-item:hover { background: var(--color-background-secondary); color: var(--color-text-primary); }
.tab-item.active {
    background: var(--color-background-warning);
    border-color: var(--color-border-warning);
    color: var(--color-text-warning);
}
.tab-count {
    display: inline-flex; align-items: center; justify-content: center;
    min-width: 20px; height: 18px; border-radius: 999px;
    font-size: .65rem; font-weight: 700; padding: 0 5px;
    background: var(--color-background-secondary);
    color: var(--color-text-secondary);
    border: 0.5px solid var(--color-border-tertiary);
}
.tab-item.active .tab-count {
    background: var(--color-background-primary);
    color: var(--color-text-warning);
}

.stat-card {
    display: block; text-decoration: none;
    background: var(--color-background-secondary);
    border: 0.5px solid var(--color-border-tertiary);
    border-radius: 12px; padding: .9rem 1rem;
    transition: border-color .15s, background .15s;
}
.stat-card:hover  { border-color: var(--color-border-secondary); }
.stat-card.active { background: var(--color-background-warning); border-color: var(--color-border-warning); }

.review-btn {
    display: inline-flex; align-items: center; gap: 4px;
    padding: 6px 12px; border-radius: 8px;
    font-size: .72rem; font-weight: 600;
    background: var(--color-background-warning);
    color: var(--color-text-warning);
    border: 0.5px solid var(--color-border-warning);
    text-decoration: none;
    transition: opacity .15s;
}
.review-btn:hover { opacity: .75; }

.rejection-box {
    display: flex; align-items: flex-start; gap: 8px;
    margin-top: .75rem; padding: .6rem .85rem;
    background: var(--color-background-danger);
    border: 0.5px solid var(--color-border-danger);
    border-radius: 8px;
}

.avatar-initials {
    display: flex; align-items: center; justify-content: center;
    width: 48px; height: 48px; border-radius: 12px;
    font-size: .85rem; font-weight: 600; flex-shrink: 0;
    background: var(--color-background-info);
    color: var(--color-text-info);
    border: 0.5px solid var(--color-border-tertiary);
}
</style>
@endpush

@section('content')
<div class="min-h-screen py-8 px-4">
<div class="mx-auto max-w-7xl space-y-5">

    {{-- ── Header ── --}}
    <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
        <div>
            <p class="text-xs uppercase tracking-widest font-bold mb-1"
               style="color: var(--color-text-warning);">Admin Panel</p>
            <h1 class="text-3xl font-black" style="color: var(--color-text-primary);">
                Verifikasi Seller
            </h1>
            <p class="text-sm mt-1" style="color: var(--color-text-secondary);">
                Kelola pengajuan, klarifikasi, dan status verifikasi penjual.
            </p>
        </div>
        <a href="{{ route('admin.dashboard') }}"
           class="inline-flex items-center gap-2 text-sm px-4 py-2 rounded-xl border"
           style="color: var(--color-text-secondary);
                  border-color: var(--color-border-tertiary);
                  background: var(--color-background-primary);
                  text-decoration: none; white-space: nowrap; transition: color .15s;">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Kembali ke Dashboard
        </a>
    </div>

    {{-- ── Stats Cards ── --}}
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-3">
        @php
        $statItems = [
            ['pending',       'Pending',         'clock',          'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z'],
            ['under_review',  'Direview',         'eye',            'M15 12a3 3 0 11-6 0 3 3 0 016 0zM2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z'],
            ['need_revision', 'Perlu Revisi',     'pencil',         'M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z'],
            ['approved',      'Disetujui',        'check-circle',   'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'],
            ['rejected',      'Ditolak/Suspend',  'x-circle',       'M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z'],
        ];
        @endphp

        @foreach($statItems as [$key, $label, $iconName, $iconPath])
        <a href="?tab={{ $key }}"
           class="stat-card {{ $tab === $key ? 'active' : '' }}">
            <svg class="w-5 h-5 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                 style="color: var(--color-text-{{ $key === 'approved' ? 'success' : ($key === 'rejected' ? 'danger' : ($key === 'under_review' ? 'info' : 'warning')) }})">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="{{ $iconPath }}"/>
            </svg>
            <div class="text-2xl font-black" style="color: var(--color-text-primary);">
                {{ number_format($counts[$key]) }}
            </div>
            <div class="text-xs mt-1" style="color: var(--color-text-secondary);">
                {{ $label }}
            </div>
        </a>
        @endforeach
    </div>

    {{-- ── Tabs ── --}}
    <div class="vcard px-2 py-2 flex gap-1 overflow-x-auto">
        @php
        $tabItems = [
            ['pending',       'Pending'],
            ['under_review',  'Direview'],
            ['need_revision', 'Perlu Revisi'],
            ['approved',      'Disetujui'],
            ['rejected',      'Ditolak/Suspend'],
        ];
        @endphp

        @foreach($tabItems as [$key, $label])
        <a href="?tab={{ $key }}" class="tab-item {{ $tab === $key ? 'active' : '' }}">
            {{ $label }}
            @if($counts[$key] > 0)
            <span class="tab-count">{{ $counts[$key] }}</span>
            @endif
        </a>
        @endforeach
    </div>

    {{-- ── User List ── --}}
    @if($users->isEmpty())
    <div class="vcard py-16 text-center">
        <div class="mx-auto mb-4 w-14 h-14 rounded-full flex items-center justify-content-center"
             style="background: var(--color-background-success);">
            <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                 style="color: var(--color-text-success);">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                      d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </div>
        <h3 class="font-bold text-lg" style="color: var(--color-text-primary);">Tidak ada pengajuan</h3>
        <p class="text-sm mt-1" style="color: var(--color-text-secondary);">
            Belum ada seller dengan status ini.
        </p>
    </div>

    @else
    <div class="space-y-2">
        @foreach($users as $user)
        @php
            $initials = collect(explode(' ', $user->name))
                ->take(2)
                ->map(fn($w) => strtoupper($w[0] ?? ''))
                ->implode('');
        @endphp

        <div class="vcard p-5 cursor-pointer"
             onclick="window.location='{{ route('admin.verification.show', $user) }}'">

            <div class="flex items-start gap-4">

                {{-- Avatar --}}
                @if($user->shop_photo)
                <img src="{{ asset('storage/' . $user->shop_photo) }}"
                     alt="{{ $user->name }}"
                     class="w-12 h-12 rounded-xl object-cover shrink-0"
                     style="border: 0.5px solid var(--color-border-tertiary);">
                @else
                <div class="avatar-initials">{{ $initials }}</div>
                @endif

                {{-- Info --}}
                <div class="flex-1 min-w-0">
                    <div class="flex flex-wrap items-center gap-2 mb-1">
                        <h3 class="font-bold text-base truncate"
                            style="color: var(--color-text-primary);">
                            {{ $user->name }}
                        </h3>
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

                    <p class="text-sm truncate" style="color: var(--color-text-secondary);">
                        {{ $user->email }}
                    </p>

                    @if($user->shop_name)
                    <p class="text-sm mt-1 flex items-center gap-1"
                       style="color: var(--color-text-primary);">
                        <svg class="w-3.5 h-3.5 shrink-0" fill="none" viewBox="0 0 24 24"
                             stroke="currentColor" style="color: var(--color-text-secondary);">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                  d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                        {{ $user->shop_name }}
                    </p>
                    @endif

                    @if($user->shop_description)
                    <p class="text-xs mt-1 line-clamp-2"
                       style="color: var(--color-text-secondary);">
                        {{ $user->shop_description }}
                    </p>
                    @endif
                </div>

                {{-- Date + Action --}}
                <div class="shrink-0 text-right flex flex-col items-end gap-2">
                    <p class="text-xs" style="color: var(--color-text-secondary);">
                        {{ $user->created_at->diffForHumans() }}
                    </p>
                    <a href="{{ route('admin.verification.show', $user) }}"
                       class="review-btn"
                       onclick="event.stopPropagation()">
                        Review
                        <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M9 5l7 7-7 7"/>
                        </svg>
                    </a>
                </div>

            </div>

            {{-- Rejection / revision note --}}
            @if($user->seller_rejection_reason)
            <div class="rejection-box">
                <svg class="w-4 h-4 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24"
                     stroke="currentColor" style="color: var(--color-text-danger);">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <p class="text-xs" style="color: var(--color-text-danger);">
                    <span class="font-semibold">Catatan:</span>
                    {{ $user->seller_rejection_reason }}
                </p>
            </div>
            @endif

        </div>
        @endforeach
    </div>

    {{-- Pagination --}}
    <div class="flex justify-center pt-2">
        {{ $users->appends(['tab' => $tab])->links() }}
    </div>
    @endif

</div>
</div>
@endsection
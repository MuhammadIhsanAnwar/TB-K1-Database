@extends('layouts.app')

@section('content')
@php
    $tabs = [
        'all' => 'Semua',
        'transaction' => 'Transaksi',
        'event_reward' => 'Event & Hadiah',
        'general' => 'Umum',
    ];
@endphp

<div class="min-h-screen relative px-4 pt-28 pb-16 overflow-hidden">
    {{-- BACKGROUND EFFECT --}}
    <div class="pointer-events-none absolute inset-0 -z-10 overflow-hidden">
        <div class="absolute top-[-120px] left-[-120px] h-[320px] w-[320px] rounded-full bg-cyan-500/10 blur-3xl"></div>
        <div class="absolute bottom-[-140px] right-[-120px] h-[320px] w-[320px] rounded-full bg-blue-500/10 blur-3xl"></div>
    </div>

    <div class="relative z-10 mx-auto max-w-4xl">
        <!-- Header Section -->
        <div class="reveal-up relative overflow-hidden rounded-[30px] border border-cyan-500/20 bg-gradient-to-br from-[#091225] via-[#0B1730] to-[#0A1120] px-7 py-8 mb-10 shadow-[0_0_40px_rgba(6,182,212,0.12)]">
            
            <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_right,rgba(6,182,212,0.18),transparent_35%)]"></div>

            <div class="relative z-10 flex flex-col gap-6 sm:flex-row sm:items-center sm:justify-between">
                <div class="flex items-center gap-5">
                    <div class="w-14 h-14 rounded-2xl bg-cyan-500/10 border border-cyan-500/30 flex items-center justify-center shadow-[0_0_20px_rgba(6,182,212,0.2)] shrink-0">
                        <svg class="w-7 h-7 text-cyan-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                        </svg>
                    </div>
                    <div>
                        <div class="inline-flex items-center gap-2 rounded-full border border-cyan-500/30 bg-cyan-500/10 px-4 py-1.5 text-[10px] font-bold tracking-[0.2em] text-cyan-300 mb-2">
                            <span class="h-2 w-2 rounded-full bg-cyan-400 animate-pulse"></span>
                            UPDATES
                        </div>
                        <h1 class="text-3xl font-black text-white tracking-tight leading-tight">Notifikasi</h1>
                        <p class="mt-2 text-sm text-slate-300">Pusat pemberitahuan dan informasi penting untukmu.</p>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <form action="{{ route('notifications.read-all') }}" method="POST">
                        @csrf
                        <button type="submit" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-white/5 hover:bg-white/10 border border-white/10 text-sm font-semibold text-slate-300 hover:text-white transition-all">
                            <svg class="w-4 h-4 text-cyan-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            Tandai Dibaca
                        </button>
                    </form>
                    <form action="{{ route('notifications.destroy-all') }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus semua notifikasi?')">
                        @csrf
                        <button type="submit" class="inline-flex items-center justify-center w-10 h-10 rounded-xl bg-rose-500/10 hover:bg-rose-500/20 border border-rose-500/20 text-rose-400 hover:text-rose-300 transition-all" title="Hapus Semua">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Filter Tabs -->
        <div class="reveal-up mb-8 flex flex-wrap gap-3 rounded-[26px] border border-white/5 bg-[#0B1220]/90 p-4 backdrop-blur-xl overflow-x-auto no-scrollbar">
            @foreach($tabs as $key => $label)
                <a href="{{ route('notifications.index', ['filter' => $key]) }}"
                   class="inline-flex items-center gap-2 rounded-2xl border px-4 py-3 text-sm font-bold transition whitespace-nowrap {{ $filter === $key ? 'border-cyan-500/30 bg-cyan-500/15 text-cyan-200 shadow-md' : 'border-white/10 text-slate-300 hover:border-cyan-500/20 hover:bg-cyan-500/[0.08] hover:text-white' }}">
                    <span>{{ $label }}</span>
                    <span class="rounded-full flex h-5 min-w-[20px] items-center justify-center px-1.5 text-[10px] {{ $filter === $key ? 'bg-black/30' : 'bg-black/20' }}">
                        {{ $counts[$key] ?? 0 }}
                    </span>
                </a>
            @endforeach
        </div>

        <!-- Notifications List -->
        @if($notifications->count() > 0)
            <div class="space-y-4">
                @foreach($notifications as $notification)
                <div class="reveal-up group relative block overflow-hidden bg-[#0B1220]/90 backdrop-blur-xl rounded-[28px] p-5 sm:p-6 border transition-all duration-300 hover:-translate-y-1.5 hover:shadow-[0_0_35px_rgba(6,182,212,0.14)] {{ !$notification->is_read ? 'border-cyan-500/30 bg-cyan-500/[0.02]' : 'border-white/5 hover:border-cyan-500/20' }}">
                    
                    @if(!$notification->is_read)
                        <!-- Unread Glowing Indicator on the left edge -->
                        <div class="absolute left-0 top-1/2 -translate-y-1/2 w-1 h-16 bg-gradient-to-b from-cyan-400 to-blue-500 rounded-r-full shadow-[0_0_15px_#22d3ee]"></div>
                    @endif

                    <div class="flex flex-col sm:flex-row gap-5">
                        <!-- Icon -->
                        <div class="shrink-0">
                            @if(!$notification->is_read)
                                <div class="w-12 h-12 rounded-2xl bg-cyan-500/10 border border-cyan-500/30 flex items-center justify-center text-cyan-400 shadow-[0_0_15px_rgba(6,182,212,0.2)]">
                                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                </div>
                            @else
                                <div class="w-12 h-12 rounded-2xl bg-slate-800 border border-white/5 flex items-center justify-center text-slate-500 group-hover:text-slate-400 transition-colors">
                                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                </div>
                            @endif
                        </div>

                        <!-- Content -->
                        <div class="flex-1 min-w-0">
                            <div class="flex flex-wrap items-center gap-3 mb-1.5">
                                <h3 class="text-lg font-black truncate {{ !$notification->is_read ? 'text-white' : 'text-slate-300 group-hover:text-white' }} transition-colors">{{ $notification->title }}</h3>
                                <span class="rounded-full border border-white/10 bg-white/5 px-2.5 py-1 text-[10px] uppercase tracking-wider font-bold text-slate-400">
                                    {{ $notification->category_label }}
                                </span>
                                <span class="text-[11px] font-semibold text-slate-500 ml-auto flex items-center gap-1">
                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    {{ $notification->created_at->diffForHumans() }}
                                </span>
                            </div>
                            <p class="text-sm leading-relaxed {{ !$notification->is_read ? 'text-slate-300' : 'text-slate-400' }}">{{ $notification->body }}</p>
                            
                            <!-- Actions -->
                            <div class="mt-5 flex flex-wrap items-center gap-3 border-t border-white/5 pt-5">
                                @if($notification->link)
                                    <a href="{{ $notification->link }}" class="inline-flex items-center justify-center rounded-2xl border border-cyan-500/20 bg-cyan-500/10 px-4 py-2.5 text-sm font-bold text-cyan-300 transition hover:bg-cyan-500/20">
                                        Lihat Detail
                                    </a>
                                @endif
                                
                                @if(!$notification->is_read)
                                    <form action="{{ route('notifications.read', $notification) }}" method="POST" class="inline">
                                        @csrf
                                        <button class="inline-flex items-center justify-center rounded-2xl border border-white/10 bg-white/5 px-4 py-2.5 text-sm font-bold text-slate-300 transition hover:bg-white/10 hover:text-white">
                                            Tandai Dibaca
                                        </button>
                                    </form>
                                @endif

                                <form action="{{ route('notifications.destroy', $notification) }}" method="POST" class="inline sm:ml-auto" onsubmit="return confirm('Hapus notifikasi ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="inline-flex items-center justify-center rounded-2xl border border-rose-500/20 bg-rose-500/10 px-4 py-2.5 text-sm font-bold text-rose-400 transition hover:bg-rose-500/20">
                                        Hapus
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            {{-- Pagination --}}
            <div class="reveal-up mt-10 overflow-x-auto">
                {{ $notifications->links() }}
            </div>
        @else
            <!-- Empty State -->
            <div class="reveal-up rounded-[32px] border border-dashed border-white/10 bg-[#0B1220]/75 py-20 text-center backdrop-blur-xl">
                <div class="mx-auto flex h-20 w-20 items-center justify-center rounded-full border border-cyan-500/20 bg-cyan-500/10 relative">
                    <div class="absolute inset-0 border-2 border-cyan-500/30 rounded-full animate-ping opacity-20"></div>
                    <svg class="w-10 h-10 text-cyan-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                    </svg>
                </div>
                <div class="mt-6 text-2xl font-bold text-white">Belum Ada Notifikasi</div>
                <p class="mt-3 text-sm text-slate-500 max-w-sm mx-auto">Anda telah membaca semua notifikasi terbaru. Segala pembaruan penting akan muncul di sini.</p>
            </div>
        @endif
    </div>
</div>

{{-- REVEAL ANIMATION --}}
<style>
.reveal-up{
    opacity:0;
    transform:translateY(50px);
    animation:revealUp 1s cubic-bezier(.22,1,.36,1) forwards;
    will-change:transform, opacity;
}

.reveal-up:nth-child(2){animation-delay:.08s;}
.reveal-up:nth-child(3){animation-delay:.14s;}
.reveal-up:nth-child(4){animation-delay:.20s;}
.reveal-up:nth-child(5){animation-delay:.26s;}
.reveal-up:nth-child(6){animation-delay:.32s;}

@keyframes revealUp{
    to{
        opacity:1;
        transform:translateY(0);
    }
}
</style>
@endsection

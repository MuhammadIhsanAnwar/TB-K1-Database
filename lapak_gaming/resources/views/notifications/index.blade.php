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

<div class="min-h-screen bg-[#060a14] py-12 px-4 relative overflow-hidden">
    <!-- Decorative background elements -->
    <div class="absolute top-0 left-1/2 -translate-x-1/2 w-full max-w-4xl h-[400px] bg-cyan-500/10 blur-[120px] rounded-full pointer-events-none"></div>

    <div class="max-w-4xl mx-auto relative z-10">
        <!-- Header Section -->
        <div class="flex flex-col gap-6 mb-10 sm:flex-row sm:items-end sm:justify-between bg-[#0b1121]/80 backdrop-blur-xl border border-white/5 rounded-3xl p-6 sm:p-8 shadow-[0_20px_40px_rgba(0,0,0,0.5)]">
            <div class="flex items-center gap-5">
                <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-cyan-500 to-blue-600 flex items-center justify-center shadow-[0_0_20px_rgba(6,182,212,0.4)] shrink-0">
                    <svg class="w-7 h-7 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                    </svg>
                </div>
                <div>
                    <h1 class="text-3xl font-bold text-white tracking-tight">Notifikasi</h1>
                    <p class="mt-1 text-sm text-slate-400">Pusat pemberitahuan dan informasi penting untukmu.</p>
                </div>
            </div>
            <div class="flex items-center gap-3">
                <form action="{{ route('notifications.read-all') }}" method="POST">
                    @csrf
                    <button type="submit" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-white/5 hover:bg-white/10 border border-white/10 text-sm font-semibold text-slate-300 hover:text-white transition-all">
                        <svg class="w-4 h-4 text-cyan-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        Tandai Semua Dibaca
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

        <!-- Filter Tabs -->
        <div class="mb-8 overflow-x-auto no-scrollbar">
            <div class="inline-flex min-w-max gap-2 p-1.5 rounded-2xl bg-[#0b1121]/80 backdrop-blur-xl border border-white/5 shadow-lg">
                @foreach($tabs as $key => $label)
                    <a href="{{ route('notifications.index', ['filter' => $key]) }}"
                       class="relative flex items-center gap-2 rounded-xl px-5 py-2.5 text-sm font-bold transition-all {{ $filter === $key ? 'text-white shadow-md' : 'text-slate-400 hover:text-white hover:bg-white/5' }}">
                        @if($filter === $key)
                            <div class="absolute inset-0 bg-gradient-to-r from-cyan-600 to-blue-600 rounded-xl opacity-90"></div>
                        @endif
                        <span class="relative z-10">{{ $label }}</span>
                        <span class="relative z-10 flex h-5 min-w-[20px] items-center justify-center rounded-full px-1.5 text-[10px] {{ $filter === $key ? 'bg-white/20 text-white' : 'bg-slate-800 text-slate-400' }}">
                            {{ $counts[$key] ?? 0 }}
                        </span>
                    </a>
                @endforeach
            </div>
        </div>

        <!-- Notifications List -->
        @if($notifications->count() > 0)
            <div class="space-y-4">
                @foreach($notifications as $notification)
                <div class="group relative bg-[#0b1121]/80 backdrop-blur-xl rounded-2xl p-5 sm:p-6 border transition-all duration-300 hover:-translate-y-1 hover:shadow-[0_15px_30px_rgba(0,0,0,0.4)] {{ !$notification->is_read ? 'border-cyan-500/30 bg-cyan-500/[0.02]' : 'border-white/5 hover:border-white/10' }}">
                    
                    @if(!$notification->is_read)
                        <!-- Unread Glowing Indicator on the left edge -->
                        <div class="absolute left-0 top-1/2 -translate-y-1/2 w-1 h-12 bg-gradient-to-b from-cyan-400 to-blue-500 rounded-r-full shadow-[0_0_10px_#22d3ee]"></div>
                    @endif

                    <div class="flex flex-col sm:flex-row gap-5">
                        <!-- Icon -->
                        <div class="shrink-0">
                            @if(!$notification->is_read)
                                <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-cyan-500/20 to-blue-600/20 border border-cyan-500/30 flex items-center justify-center text-cyan-400 shadow-[0_0_15px_rgba(6,182,212,0.2)]">
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
                                <h3 class="text-base font-bold truncate {{ !$notification->is_read ? 'text-white' : 'text-slate-300 group-hover:text-white' }} transition-colors">{{ $notification->title }}</h3>
                                <span class="rounded-full border border-white/10 bg-white/5 px-2.5 py-0.5 text-[10px] uppercase tracking-wider font-bold text-slate-400">
                                    {{ $notification->category_label }}
                                </span>
                                <span class="text-[11px] font-semibold text-slate-500 ml-auto flex items-center gap-1">
                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    {{ $notification->created_at->diffForHumans() }}
                                </span>
                            </div>
                            <p class="text-sm leading-relaxed {{ !$notification->is_read ? 'text-slate-300' : 'text-slate-400' }}">{{ $notification->body }}</p>
                            
                            <!-- Actions -->
                            <div class="mt-4 flex flex-wrap items-center gap-3">
                                @if($notification->link)
                                    <a href="{{ $notification->link }}" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-cyan-500/10 hover:bg-cyan-500/20 border border-cyan-500/20 text-xs font-bold text-cyan-400 transition-colors">
                                        Lihat Detail
                                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                                    </a>
                                @endif
                                
                                @if(!$notification->is_read)
                                    <form action="{{ route('notifications.read', $notification) }}" method="POST" class="inline">
                                        @csrf
                                        <button class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-white/5 hover:bg-white/10 border border-transparent text-xs font-bold text-slate-300 hover:text-white transition-colors">
                                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                            Tandai Dibaca
                                        </button>
                                    </form>
                                @endif

                                <form action="{{ route('notifications.destroy', $notification) }}" method="POST" class="inline sm:ml-auto" onsubmit="return confirm('Hapus notifikasi ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="inline-flex items-center gap-1.5 px-3 py-2 rounded-xl text-xs font-bold text-slate-500 hover:text-rose-400 hover:bg-rose-500/10 transition-colors">
                                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
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
            <div class="mt-10">
                {{ $notifications->links() }}
            </div>
        @else
            <!-- Empty State -->
            <div class="flex flex-col items-center justify-center py-20 px-4 bg-[#0b1121]/80 backdrop-blur-xl border border-white/5 rounded-3xl shadow-xl">
                <div class="w-24 h-24 mb-6 rounded-full bg-slate-800/50 border border-white/5 flex items-center justify-center relative">
                    <div class="absolute inset-0 border-2 border-slate-700 rounded-full animate-ping opacity-20"></div>
                    <svg class="w-10 h-10 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-white mb-2">Belum Ada Notifikasi</h3>
                <p class="text-slate-400 text-center max-w-sm">Anda telah membaca semua notifikasi terbaru. Segala pembaruan penting akan muncul di sini.</p>
            </div>
        @endif
    </div>
</div>
@endsection

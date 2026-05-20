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

<div class="min-h-screen bg-gray-950 py-12 px-4">
    <div class="max-w-4xl mx-auto">
        <div class="flex flex-col gap-4 mb-8 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <h1 class="text-3xl font-bold text-white">Notifikasi</h1>
                <p class="mt-2 text-sm text-gray-500">Pilih kategori untuk melihat pesan yang paling relevan.</p>
            </div>
            <div class="flex items-center gap-4">
                <form action="{{ route('notifications.read-all') }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="text-sm font-semibold text-brand-300 hover:text-brand-200 transition-colors">
                        Tandai semua dibaca
                    </button>
                </form>
                <span class="text-gray-700">|</span>
                <form action="{{ route('notifications.destroy-all') }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus semua notifikasi?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="text-sm font-semibold text-red-400 hover:text-red-300 transition-colors">
                        Hapus Semua
                    </button>
                </form>
            </div>
        </div>

        <div class="mb-6 overflow-x-auto">
            <div class="inline-flex min-w-max gap-2 rounded-2xl border border-gray-800 bg-gray-900 p-1.5">
                @foreach($tabs as $key => $label)
                    <a href="{{ route('notifications.index', ['filter' => $key]) }}"
                       class="rounded-xl px-4 py-2 text-sm font-semibold transition {{ $filter === $key ? 'bg-brand-600 text-white' : 'text-gray-400 hover:bg-gray-800 hover:text-white' }}">
                        {{ $label }}
                        <span class="ml-1 rounded-full bg-black/20 px-2 py-0.5 text-xs">{{ $counts[$key] ?? 0 }}</span>
                    </a>
                @endforeach
            </div>
        </div>

        @if($notifications->count() > 0)
            <div class="space-y-3">
                @foreach($notifications as $notification)
                <div class="bg-gray-900 rounded-lg p-4 border border-gray-800 hover:border-gray-700 transition-colors {{ !$notification->is_read ? 'border-purple-600/30' : '' }}">
                    <div class="flex items-start justify-between">
                        <div class="flex-1">
                            <div class="flex items-center gap-3 mb-2">
                                <div class="w-2 h-2 rounded-full {{ !$notification->is_read ? 'bg-purple-500' : 'bg-gray-700' }}"></div>
                                <h3 class="font-semibold text-white">{{ $notification->title }}</h3>
                                <span class="rounded-full border border-gray-700 bg-gray-950 px-2 py-0.5 text-[11px] font-semibold text-gray-300">
                                    {{ $notification->category_label }}
                                </span>
                            </div>
                            <p class="text-gray-400 text-sm">{{ $notification->body }}</p>
                            <p class="text-gray-500 text-xs mt-2">{{ $notification->created_at->diffForHumans() }}</p>
                        </div>
                        <div class="ml-4 flex flex-col items-end gap-2 shrink-0">
                            @if($notification->link)
                                <a href="{{ $notification->link }}" class="text-sm font-semibold text-brand-300 hover:text-brand-200 transition-colors">Buka</a>
                            @endif
                            @if(!$notification->is_read)
                                <form action="{{ route('notifications.read', $notification) }}" method="POST" class="inline">
                                    @csrf
                                    <button class="text-gray-400 hover:text-gray-300 transition-colors text-sm font-semibold whitespace-nowrap">
                                        Tandai dibaca
                                    </button>
                                </form>
                            @endif
                            <form action="{{ route('notifications.destroy', $notification) }}" method="POST" class="inline" onsubmit="return confirm('Hapus notifikasi ini?')">
                                @csrf
                                @method('DELETE')
                                <button class="text-red-400 hover:text-red-300 transition-colors text-sm font-semibold whitespace-nowrap">
                                    Hapus
                                </button>
                            </form>
                        </div>
                    </div>
                    @if($notification->link)
                        <a href="{{ $notification->link }}" class="mt-4 inline-flex items-center gap-2 text-sm font-semibold text-brand-300 hover:text-brand-200">
                            Buka notifikasi
                            <span aria-hidden="true">→</span>
                        </a>
                    @endif
                </div>
                @endforeach
            </div>

            {{-- Pagination --}}
            <div class="mt-8">
                {{ $notifications->links() }}
            </div>
        @else
        <div class="bg-gray-900 rounded-xl p-12 text-center border border-gray-800">
            <svg class="w-16 h-16 mx-auto text-gray-600 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
            </svg>
            <p class="text-gray-400 text-lg mb-2">Belum ada notifikasi</p>
            <p class="text-gray-500 text-sm">Notifikasi kategori ini akan muncul di sini.</p>
        </div>
        @endif
    </div>
</div>
@endsection

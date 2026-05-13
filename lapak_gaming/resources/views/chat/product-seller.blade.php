@extends('layouts.app')

@section('title', 'Chat Produk — ' . $product->name)

@section('content')
<div class="max-w-4xl mx-auto px-4 py-8">
    <a href="{{ route('products.show', $product->slug) }}" class="inline-flex items-center gap-2 text-sm text-slate-400 hover:text-white mb-6 transition-colors">
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
        Kembali ke Produk
    </a>

    <div class="mb-6">
        <h1 class="text-2xl font-black text-white">Chat Produk</h1>
        <p class="text-slate-400 text-sm mt-1">🎮 {{ $product->name }}</p>
    </div>

    @if($conversations->isEmpty())
    <div class="rounded-2xl border border-slate-800 bg-slate-900/50 p-16 text-center">
        <div class="text-5xl mb-4">💬</div>
        <p class="text-white font-bold">Belum ada pesan</p>
        <p class="text-slate-400 text-sm mt-1">Belum ada pembeli yang menghubungi untuk produk ini.</p>
    </div>
    @else
    <div class="space-y-3">
        @foreach($conversations as $conv)
        @php $buyer = $conv->buyer; $unread = $conv->unread_seller; @endphp
        <a href="{{ route('chat.show', $conv) }}"
           class="flex items-center gap-4 p-4 rounded-2xl border border-slate-800 bg-slate-900/50 hover:border-slate-600 hover:bg-slate-900 transition-all">
            <div class="relative">
                <img src="{{ $buyer?->avatar_url }}" class="w-12 h-12 rounded-full object-cover" alt="">
                @if($unread > 0)
                <span class="absolute -top-1 -right-1 bg-brand-500 text-white text-xs font-bold rounded-full w-5 h-5 flex items-center justify-center">{{ $unread }}</span>
                @endif
            </div>
            <div class="flex-1 min-w-0">
                <div class="flex justify-between items-center">
                    <h3 class="font-bold text-white">{{ $buyer?->name ?? 'Pembeli' }}</h3>
                    <span class="text-xs text-slate-500">{{ $conv->last_message_at?->diffForHumans() }}</span>
                </div>
                <p class="text-sm text-slate-400 truncate mt-0.5">
                    {{ $conv->last_message ?? 'Belum ada pesan' }}
                </p>
            </div>
            <svg class="w-5 h-5 text-slate-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        </a>
        @endforeach
    </div>
    @endif
</div>
@endsection
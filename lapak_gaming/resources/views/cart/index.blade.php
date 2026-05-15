@extends('layouts.app')

@section('title', 'Keranjang Belanja')

@section('content')
<div class="min-h-screen bg-slate-950 py-8 px-4">
    <div class="mx-auto max-w-5xl space-y-6">
        
        {{-- Header Ringkas --}}
        <div class="flex items-center justify-between border-b border-slate-800 pb-4">
            <div>
                <h1 class="text-2xl font-bold text-white">Keranjang Belanja</h1>
                <p class="text-sm text-slate-400 mt-1">Periksa kembali pesanan Anda sebelum membayar.</p>
            </div>
            <a href="{{ route('home') }}" class="text-sm font-semibold text-amber-500 hover:text-amber-400 transition">
                Lanjut Belanja &rarr;
            </a>
        </div>

        @if($cartItems->isEmpty())
            {{-- Empty State (Lebih Kecil) --}}
            <div class="rounded-3xl border border-slate-800 bg-slate-900/50 p-12 text-center">
                <div class="w-16 h-16 bg-slate-800 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                </div>
                <h2 class="text-lg font-bold text-white mb-1">Keranjang masih kosong</h2>
                <p class="text-sm text-slate-400 mb-6">Yuk, temukan item gaming favoritmu sekarang!</p>
                <a href="{{ route('home') }}" class="px-6 py-2.5 bg-amber-500 text-slate-950 font-bold rounded-xl hover:bg-amber-400 transition">
                    Mulai Belanja
                </a>
            </div>
        @else
            <div class="grid gap-6 lg:grid-cols-3">
                
                {{-- Daftar Item (Kompak dan Rapi) --}}
                <div class="lg:col-span-2 space-y-3">
                    @foreach($cartItems as $item)
                        <div class="flex flex-col sm:flex-row items-center gap-4 rounded-2xl border border-slate-800 bg-slate-900 p-4">
                            
                            {{-- Gambar Produk (Ukuran Pas, Fallback Aman) --}}
                            @if($item->product->image)
                                <img src="{{ asset('storage/' . $item->product->image) }}" alt="Produk" class="w-20 h-20 rounded-xl object-cover border border-slate-700 shrink-0">
                            @else
                                <div class="w-20 h-20 rounded-xl bg-slate-800 border border-slate-700 flex items-center justify-center shrink-0">
                                    <svg class="w-8 h-8 text-slate-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                </div>
                            @endif
                            
                            {{-- Info Produk --}}
                            <div class="flex-1 text-center sm:text-left min-w-0">
                                <h3 class="text-base font-bold text-white truncate">{{ $item->product->name }}</h3>
                                <p class="text-xs text-slate-400 mt-0.5">Toko: <span class="font-semibold text-slate-300">{{ $item->product->seller->name }}</span></p>
                            </div>

                            {{-- Harga --}}
                            <div class="text-center sm:text-right px-4">
                                <div class="text-lg font-black text-amber-500 whitespace-nowrap">
                                    Rp {{ number_format($item->product->price, 0, ',', '.') }}
                                </div>
                            </div>

                            {{-- Tombol Hapus --}}
                            <form action="{{ route('cart.remove', $item->id) }}" method="POST" class="shrink-0">
                                @csrf @method('DELETE')
                                <button class="p-2.5 rounded-xl bg-rose-500/10 text-rose-500 hover:bg-rose-500 hover:text-white transition-colors" title="Hapus Item">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                </button>
                            </form>
                        </div>
                    @endforeach
                </div>

                {{-- Ringkasan Pesanan (Sidebar) --}}
                <div class="lg:col-span-1">
                    <div class="rounded-2xl border border-slate-800 bg-slate-900 p-6 sticky top-6">
                        <h2 class="text-base font-bold text-white mb-4">Ringkasan Belanja</h2>
                        
                        <div class="space-y-3 border-b border-slate-800 pb-4 text-sm">
                            <div class="flex justify-between text-slate-400">
                                <span>Total Item</span>
                                <span class="text-white">{{ $cartItems->count() }} Pcs</span>
                            </div>
                            <div class="flex justify-between text-slate-400">
                                <span>Subtotal</span>
                                <span class="text-white">Rp {{ number_format($cartItems->sum(fn($i) => $i->product->price), 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between text-slate-400">
                                <span>Biaya Admin</span>
                                <span class="text-emerald-500 font-semibold">Gratis</span>
                            </div>
                        </div>

                        <div class="py-4 flex justify-between items-center">
                            <span class="text-sm font-bold text-slate-300">Total Harga</span>
                            <span class="text-xl font-black text-amber-500">
                                Rp {{ number_format($cartItems->sum(fn($i) => $i->product->price), 0, ',', '.') }}
                            </span>
                        </div>

                        <form action="{{ route('checkout.store') }}" method="POST">
                            @csrf
                            <button class="w-full py-3 bg-amber-500 text-slate-950 font-bold rounded-xl hover:bg-amber-400 transition-colors">
                                Checkout Sekarang
                            </button>
                        </form>
                    </div>
                </div>

            </div>
        @endif
    </div>
</div>
@endsection
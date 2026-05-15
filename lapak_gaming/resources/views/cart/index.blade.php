@extends('layouts.app')

@section('title', 'Keranjang Belanja — Lapak Gaming')

@section('content')
<div class="min-h-screen bg-slate-950 py-10 px-4 relative">
    {{-- Subtle Background Glow --}}
    <div class="absolute top-0 left-1/2 -translate-x-1/2 w-[800px] h-[300px] bg-amber-500/5 blur-[120px] pointer-events-none"></div>

    <div class="mx-auto max-w-6xl space-y-8 relative z-10">
        
        {{-- Header --}}
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-4 border-b border-slate-800/60 pb-6">
            <div>
                <div class="flex items-center gap-3 mb-2">
                    <div class="p-2 bg-amber-500/10 rounded-lg">
                        <svg class="w-6 h-6 text-amber-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                    </div>
                    <h1 class="text-3xl font-black text-white italic tracking-tight">KERANJANG BELANJA</h1>
                </div>
                <p class="text-sm text-slate-400 font-medium ml-11">Pastikan item pesananmu sudah benar sebelum lanjut bayar.</p>
            </div>
            <a href="{{ route('home') }}" class="inline-flex items-center gap-2 text-sm font-bold text-slate-400 hover:text-amber-500 transition-colors">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Tambah Item Lain
            </a>
        </div>

        @if($cartItems->isEmpty())
            {{-- Empty State --}}
            <div class="rounded-[2rem] border border-slate-800 bg-slate-900/40 p-16 flex flex-col items-center justify-center text-center shadow-xl">
                <img src="https://cdn3d.iconscout.com/3d/premium/thumb/empty-cart-4852438-4038166.png" alt="Empty Cart" class="w-48 h-48 object-contain mb-6 opacity-80 drop-shadow-2xl">
                <h2 class="text-2xl font-black text-white mb-2 italic">YAH, KERANJANGMU KOSONG!</h2>
                <p class="text-slate-400 mb-8 max-w-md">Lapak Gaming punya ribuan item, voucher, dan akun menanti untuk kamu amankan. Yuk hunting sekarang!</p>
                <a href="{{ route('home') }}" class="px-8 py-3.5 bg-amber-500 text-slate-950 font-black rounded-xl hover:bg-amber-400 transition-all shadow-lg shadow-amber-500/20 active:scale-95">
                    MULAI BELANJA
                </a>
            </div>
        @else
            <div class="grid gap-8 lg:grid-cols-3 items-start">
                
                {{-- Daftar Item --}}
                <div class="lg:col-span-2 space-y-4">
                    <div class="flex items-center justify-between px-2 mb-2">
                        <span class="text-sm font-bold text-slate-400 uppercase tracking-widest">Detail Produk</span>
                        <span class="text-sm font-bold text-slate-400 uppercase tracking-widest hidden sm:block">Harga</span>
                    </div>

                    @foreach($cartItems as $item)
                        <div class="group rounded-2xl border border-slate-800 bg-slate-900/60 p-5 flex flex-col sm:flex-row gap-5 transition-all hover:border-amber-500/40 hover:bg-slate-900 shadow-md">
                            
                            {{-- Gambar Produk --}}
                            <div class="shrink-0 relative">
                                <img src="{{ $item->product->image ? asset('storage/' . $item->product->image) : 'https://ui-avatars.com/api/?name='.urlencode($item->product->name).'&background=1e293b&color=f59e0b&bold=true' }}" 
                                     alt="{{ $item->product->name }}" 
                                     class="w-24 h-24 sm:w-28 sm:h-28 rounded-xl object-cover border border-slate-700 shadow-inner">
                            </div>
                            
                            {{-- Info Produk --}}
                            <div class="flex-1 flex flex-col justify-center">
                                <div class="flex items-center gap-2 mb-1.5">
                                    <span class="px-2.5 py-0.5 bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 text-[10px] font-black uppercase rounded-md">
                                        STOK ADA
                                    </span>
                                    <span class="px-2.5 py-0.5 bg-slate-800 text-slate-400 text-[10px] font-black uppercase rounded-md">
                                        {{ $item->product->type ?? 'ITEM' }}
                                    </span>
                                </div>
                                <h3 class="text-lg font-bold text-white leading-tight mb-2">{{ $item->product->name }}</h3>
                                
                                <div class="flex items-center gap-2 text-xs text-slate-400 font-medium">
                                    <svg class="w-4 h-4 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                    Seller: <span class="text-amber-500 font-bold">{{ $item->product->seller->name }}</span>
                                </div>
                            </div>

                            {{-- Harga & Aksi --}}
                            <div class="flex flex-row sm:flex-col items-center sm:items-end justify-between sm:justify-center gap-4 sm:border-l border-slate-800 sm:pl-6">
                                <div class="text-xl font-black text-amber-500">
                                    Rp {{ number_format($item->product->price, 0, ',', '.') }}
                                </div>
                                
                                <form action="{{ route('cart.remove', $item->id) }}" method="POST">
                                    @csrf @method('DELETE')
                                    <button class="inline-flex items-center gap-2 px-3 py-1.5 rounded-lg text-xs font-bold text-slate-500 hover:text-rose-500 hover:bg-rose-500/10 transition-colors" title="Hapus Item">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        Hapus
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- Sidebar: Info & Ringkasan --}}
                <div class="lg:col-span-1 space-y-6 sticky top-6">
                    
                    {{-- Info Keamanan (Pengganti Kode Promo) --}}
                    <div class="rounded-2xl border border-blue-500/20 bg-blue-500/5 p-5 shadow-lg">
                        <div class="flex items-start gap-3">
                            <div class="p-2 bg-blue-500/20 rounded-lg shrink-0">
                                <svg class="w-6 h-6 text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-sm font-bold text-blue-400 mb-1">Proteksi Pembeli 100%</h3>
                                <p class="text-xs text-slate-400 leading-relaxed">Dana ditahan di sistem hingga pesanan kamu terima & amankan. Belanja bebas khawatir!</p>
                            </div>
                        </div>
                    </div>

                    {{-- Ringkasan Belanja --}}
                    <div class="rounded-2xl border border-slate-800 bg-slate-900/80 p-6 shadow-xl">
                        <h2 class="text-base font-black text-white mb-5 uppercase tracking-widest">Ringkasan Belanja</h2>
                        
                        <div class="space-y-4 border-b border-slate-800 pb-5 text-sm">
                            <div class="flex justify-between items-center text-slate-400">
                                <span>Total Harga ({{ $cartItems->count() }} item)</span>
                                <span class="text-white font-semibold">Rp {{ number_format($cartItems->sum(fn($i) => $i->product->price), 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between items-center text-slate-400">
                                <span>Biaya Layanan</span>
                                <span class="text-emerald-500 font-bold bg-emerald-500/10 px-2 py-0.5 rounded text-xs">Gratis</span>
                            </div>
                        </div>

                        <div class="py-5 flex justify-between items-end">
                            <span class="text-sm font-bold text-slate-400">Total Tagihan</span>
                            <span class="text-2xl font-black text-amber-500 italic">
                                Rp {{ number_format($cartItems->sum(fn($i) => $i->product->price), 0, ',', '.') }}
                            </span>
                        </div>

                        <form action="{{ route('checkout.store') }}" method="POST">
                            @csrf
                            <button class="w-full py-3.5 bg-amber-500 text-slate-950 font-black text-lg rounded-xl hover:bg-amber-400 transition-all shadow-lg shadow-amber-500/20 active:scale-95 flex items-center justify-center gap-2">
                                BELI SEKARANG
                            </button>
                        </form>

                        {{-- Trust Badges --}}
                        <div class="mt-6 pt-5 border-t border-slate-800">
                            <div class="flex items-center justify-center gap-1 text-[10px] text-slate-500 font-bold uppercase tracking-widest mb-3">
                                <svg class="w-3 h-3 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                                Transaksi Aman & Terenkripsi
                            </div>
                            <div class="flex items-center justify-center gap-2 opacity-40 grayscale hover:grayscale-0 transition-all">
                                <img src="https://upload.wikimedia.org/wikipedia/commons/b/b5/PayPal.svg" class="h-4" alt="Paypal">
                                <div class="w-1 h-1 bg-slate-700 rounded-full"></div>
                                <img src="https://upload.wikimedia.org/wikipedia/commons/5/5e/Visa_Inc._logo.svg" class="h-3" alt="Visa">
                                <div class="w-1 h-1 bg-slate-700 rounded-full"></div>
                                <img src="https://upload.wikimedia.org/wikipedia/commons/2/2a/Mastercard-logo.svg" class="h-5" alt="Mastercard">
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        @endif
    </div>
</div>
@endsection
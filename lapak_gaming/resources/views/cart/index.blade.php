@extends('layouts.app')

@section('title', 'Keranjang Belanja')

@section('content')
<div class="min-h-screen bg-slate-950 py-12 px-4">
    <div class="mx-auto max-w-5xl space-y-8 animate-fade-in">
        
        {{-- Header --}}
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-4xl font-black text-white italic tracking-tighter">KERANJANG SAYA</h1>
                <p class="text-slate-400 mt-1">Selesaikan pembayaran untuk item impianmu.</p>
            </div>
            <a href="{{ route('home') }}" class="text-amber-500 font-bold hover:underline text-sm">
                &larr; Lanjut Belanja
            </a>
        </div>

        @if($cartItems->isEmpty())
            {{-- Empty State --}}
            <div class="rounded-[2.5rem] border border-dashed border-slate-800 p-20 text-center">
                <div class="w-20 h-20 bg-slate-900 rounded-full flex items-center justify-center mx-auto mb-6">
                    <svg class="w-10 h-10 text-slate-700" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                    </svg>
                </div>
                <h2 class="text-xl font-bold text-white uppercase italic">Keranjang Kosong, Lek!</h2>
                <p class="text-slate-500 mt-2 max-w-xs mx-auto text-sm">Sepertinya kamu belum memilih item apapun untuk dibeli.</p>
                <a href="{{ route('home') }}" class="mt-8 inline-block px-8 py-4 bg-amber-500 text-slate-950 font-black rounded-2xl hover:bg-amber-400 transition">TELUSURI PRODUK</a>
            </div>
        @else
            <div class="grid gap-8 lg:grid-cols-3">
                
                {{-- List Item --}}
                <div class="lg:col-span-2 space-y-4">
                    @foreach($cartItems as $item)
                        <div class="group rounded-[2rem] border border-slate-800 bg-slate-900 p-6 flex flex-col sm:flex-row items-center gap-6 transition-all hover:border-amber-500/30">
                            {{-- Foto Produk --}}
                            <img src="{{ $item->product->image ? asset('storage/' . $item->product->image) : 'https://ui-avatars.com/api/?name='.urlencode($item->product->name) }}" 
                                 class="w-24 h-24 rounded-2xl object-cover border-2 border-slate-800 group-hover:border-amber-500/50 transition-all">
                            
                            {{-- Detail --}}
                            <div class="flex-1 text-center sm:text-left">
                                <h3 class="text-xl font-black text-white tracking-tight">{{ $item->product->name }}</h3>
                                <p class="text-xs text-slate-500 font-bold uppercase tracking-widest mt-1">
                                    Seller: <span class="text-amber-500">{{ $item->product->seller->name }}</span>
                                </p>
                                <div class="mt-4 text-2xl font-black text-white">
                                    Rp {{ number_format($item->product->price, 0, ',', '.') }}
                                </div>
                            </div>

                            {{-- Aksi --}}
                            <div class="flex items-center gap-4">
                                <form action="{{ route('cart.remove', $item->id) }}" method="POST">
                                    @csrf @method('DELETE')
                                    <button class="p-3 text-slate-600 hover:text-rose-500 transition-colors">
                                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- Ringkasan Belanja --}}
                <div class="lg:col-span-1">
                    <div class="rounded-[2.5rem] border border-slate-800 bg-slate-900 p-8 sticky top-8 shadow-2xl">
                        <h2 class="text-xl font-black text-white italic mb-6">RINGKASAN PESANAN</h2>
                        
                        <div class="space-y-4 border-b border-slate-800 pb-6">
                            <div class="flex justify-between text-slate-400 text-sm">
                                <span>Subtotal</span>
                                <span class="text-white font-bold">Rp {{ number_format($cartItems->sum(fn($i) => $i->product->price), 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between text-slate-400 text-sm">
                                <span>Biaya Layanan</span>
                                <span class="text-white font-bold">Rp 0</span>
                            </div>
                        </div>

                        <div class="py-6">
                            <div class="flex justify-between items-center">
                                <span class="text-slate-300 font-bold">TOTAL HARGA</span>
                                <span class="text-3xl font-black text-amber-500">
                                    Rp {{ number_format($cartItems->sum(fn($i) => $i->product->price), 0, ',', '.') }}
                                </span>
                            </div>
                        </div>

                        <form action="{{ route('checkout.store') }}" method="POST">
                            @csrf
                            <button class="w-full py-5 bg-amber-500 text-slate-950 font-black rounded-2xl hover:bg-amber-400 transition-all shadow-lg shadow-amber-500/20 active:scale-95 uppercase">
                                LANJUT KE PEMBAYARAN
                            </button>
                        </form>
                        
                        <p class="mt-4 text-[10px] text-slate-600 text-center font-bold tracking-widest leading-relaxed">
                            DENGAN MELANJUTKAN, KAMU MENYETUJUI ATURAN PENGGUNAAN LAPAK GAMING.
                        </p>
                    </div>
                </div>

            </div>
        @endif
    </div>
</div>

<style>
    .animate-fade-in { animation: fadeIn 0.5s ease-out; }
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
</style>
@endsection
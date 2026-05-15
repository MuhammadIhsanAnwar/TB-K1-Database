@extends('layouts.app')

@section('title', 'Keranjang Belanja — Lapak Gaming')

@push('styles')
<style>
    /* ── Animasi Masuk ── */
    .animate-slide-up { animation: slideUp 0.6s cubic-bezier(0.16, 1, 0.3, 1); }
    @keyframes slideUp {
        from { opacity: 0; transform: translateY(30px); }
        to { opacity: 1; transform: translateY(0); }
    }

    /* ── Glassmorphism Card ── */
    .glass-card {
        background: rgba(15, 23, 42, 0.6);
        backdrop-filter: blur(12px);
        border: 1px solid rgba(30, 41, 59, 1);
        border-radius: 32px;
    }

    /* ── Glowing Button ── */
    .btn-glow {
        position: relative;
        overflow: hidden;
        transition: all 0.3s ease;
    }
    .btn-glow:hover {
        box-shadow: 0 0 20px rgba(245, 158, 11, 0.4);
        transform: translateY(-2px);
    }
    .btn-glow::after {
        content: '';
        position: absolute;
        top: -50%; left: -50%; width: 200%; height: 200%;
        background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
        transform: scale(0); transition: transform 0.6s ease-out;
    }
    .btn-glow:hover::after { transform: scale(1); }

    /* ── Product Image Hover ── */
    .product-img-wrapper:hover img {
        transform: scale(1.1) rotate(-3deg);
    }
</style>
@endpush

@section('content')
<div class="min-h-screen bg-slate-950 py-12 px-4 relative overflow-hidden">
    {{-- Dekorasi Background --}}
    <div class="absolute top-0 right-0 w-[500px] h-[500px] bg-amber-500/5 blur-[120px] -mr-64 -mt-64"></div>
    <div class="absolute bottom-0 left-0 w-[500px] h-[500px] bg-blue-500/5 blur-[120px] -ml-64 -mb-64"></div>

    <div class="mx-auto max-w-6xl space-y-10 animate-slide-up relative z-10">
        
        {{-- Header Section --}}
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-4 border-b border-slate-800 pb-8">
            <div>
                <p class="text-xs font-black uppercase tracking-[0.4em] text-amber-500 mb-2">Shopping Cart</p>
                <h1 class="text-5xl font-black text-white italic tracking-tighter">KERANJANG BELANJA</h1>
                <p class="text-slate-500 mt-2 font-medium">Satu langkah lagi sebelum item gaming kamu amankan.</p>
            </div>
            <a href="{{ route('home') }}" class="group flex items-center gap-2 text-slate-400 hover:text-white transition-all font-bold">
                <svg class="w-5 h-5 group-hover:-translate-x-1 transition" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/></svg>
                KEMBALI BELANJA
            </a>
        </div>

        @if($cartItems->isEmpty())
            {{-- Empty State Premium --}}
            <div class="glass-card p-24 text-center border-dashed border-2 border-slate-800">
                <div class="relative inline-block mb-8">
                    <div class="absolute inset-0 bg-amber-500/20 blur-3xl rounded-full"></div>
                    <svg class="relative w-24 h-24 text-slate-700 mx-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                </div>
                <h2 class="text-2xl font-black text-white italic">WAH, KERANJANGNYA KOSONG LEK!</h2>
                <p class="text-slate-500 mt-4 max-w-sm mx-auto font-medium leading-relaxed">
                    Sepertinya kamu belum memilih item apapun. Yuk, cari voucher atau akun idamanmu sekarang!
                </p>
                <a href="{{ route('home') }}" class="mt-10 inline-flex px-10 py-4 bg-amber-500 text-slate-950 font-black rounded-2xl hover:bg-amber-400 transition-all shadow-lg shadow-amber-500/20 uppercase tracking-widest">
                    GAS BELANJA!
                </a>
            </div>
        @else
            <div class="grid gap-10 lg:grid-cols-3">
                
                {{-- List Item Section --}}
                <div class="lg:col-span-2 space-y-6">
                    @foreach($cartItems as $item)
                        <div class="glass-card p-8 group transition-all hover:bg-slate-900/80">
                            <div class="flex flex-col sm:flex-row items-center gap-8">
                                {{-- Image with Overlay --}}
                                <div class="product-img-wrapper relative shrink-0">
                                    <div class="absolute -inset-2 bg-gradient-to-tr from-amber-500 to-yellow-300 opacity-0 group-hover:opacity-20 blur-lg transition duration-500"></div>
                                    <img src="{{ $item->product->image ? asset('storage/' . $item->product->image) : 'https://ui-avatars.com/api/?name='.urlencode($item->product->name).'&background=random' }}" 
                                         class="relative w-32 h-32 rounded-[2rem] object-cover border-2 border-slate-800 transition-all duration-500 z-10">
                                </div>
                                
                                {{-- Detail Content --}}
                                <div class="flex-1 space-y-2 text-center sm:text-left">
                                    <div class="flex flex-col sm:flex-row sm:items-center gap-2">
                                        <span class="px-3 py-1 bg-slate-800 text-[10px] font-black text-slate-500 rounded-lg uppercase tracking-widest">{{ $item->product->type }}</span>
                                        <span class="text-slate-600 text-xs font-bold uppercase">Seller: <span class="text-slate-300">{{ $item->product->seller->name }}</span></span>
                                    </div>
                                    <h3 class="text-2xl font-black text-white tracking-tight group-hover:text-amber-500 transition-colors uppercase italic">{{ $item->product->name }}</h3>
                                    <div class="pt-2 text-3xl font-black text-white tracking-tighter">
                                        Rp {{ number_format($item->product->price, 0, ',', '.') }}
                                    </div>
                                </div>

                                {{-- Action Button --}}
                                <div class="shrink-0">
                                    <form action="{{ route('cart.remove', $item->id) }}" method="POST" onsubmit="return confirm('Hapus dari keranjang?')">
                                        @csrf @method('DELETE')
                                        <button class="w-14 h-14 rounded-2xl bg-slate-950 border border-slate-800 text-slate-600 hover:text-rose-500 hover:border-rose-500/50 transition-all flex items-center justify-center shadow-inner">
                                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- Order Summary Section --}}
                <div class="lg:col-span-1">
                    <div class="glass-card p-10 sticky top-8 shadow-2xl border-t-amber-500/30 border-t-2">
                        <h2 class="text-2xl font-black text-white italic tracking-tighter mb-8 uppercase">RINGKASAN PESANAN</h2>
                        
                        <div class="space-y-5 border-b border-slate-800 pb-8">
                            <div class="flex justify-between items-center text-slate-400">
                                <span class="font-bold text-sm uppercase">Total Item</span>
                                <span class="text-white font-black">{{ $cartItems->count() }} Pcs</span>
                            </div>
                            <div class="flex justify-between items-center text-slate-400">
                                <span class="font-bold text-sm uppercase">Subtotal</span>
                                <span class="text-white font-black">Rp {{ number_format($cartItems->sum(fn($i) => $i->product->price), 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-emerald-500 font-bold text-sm uppercase">Biaya Admin</span>
                                <span class="text-emerald-500 font-black">FREE</span>
                            </div>
                        </div>

                        <div class="py-8">
                            <div class="flex flex-col gap-1">
                                <span class="text-slate-500 font-black text-[10px] uppercase tracking-[0.3em]">TOTAL PEMBAYARAN</span>
                                <span class="text-4xl font-black text-amber-500 tracking-tighter italic">
                                    Rp {{ number_format($cartItems->sum(fn($i) => $i->product->price), 0, ',', '.') }}
                                </span>
                            </div>
                        </div>

                        <form action="{{ route('checkout.store') }}" method="POST">
                            @csrf
                            <button class="btn-glow w-full py-5 bg-amber-500 text-slate-950 font-black rounded-3xl transition-all uppercase tracking-widest text-lg shadow-xl shadow-amber-500/10">
                                CHECKOUT SEKARANG
                            </button>
                        </form>
                        
                        <div class="mt-8 flex items-center justify-center gap-3 opacity-30">
                            <img src="https://upload.wikimedia.org/wikipedia/commons/b/b5/PayPal.svg" class="h-4">
                            <img src="https://upload.wikimedia.org/wikipedia/commons/5/5e/Visa_Inc._logo.svg" class="h-3">
                            <img src="https://upload.wikimedia.org/wikipedia/commons/2/2a/Mastercard-logo.svg" class="h-5">
                        </div>

                        <p class="mt-6 text-[9px] text-slate-600 text-center font-bold tracking-widest leading-relaxed uppercase">
                            Keamanan transaksi dijamin 100% oleh sistem enkripsi Lapak Gaming.
                        </p>
                    </div>
                </div>

            </div>
        @endif
    </div>
</div>
@endsection
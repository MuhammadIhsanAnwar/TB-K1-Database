@php
use Illuminate\Support\Facades\Storage;
@endphp

@extends('layouts.app')
@php 
use Illuminate\Support\Str; 
@endphp

@section('title', 'Keranjang Belanja — Lapak Gaming')

@section('content')
<div class="min-h-screen relative px-4 pt-28 pb-16 overflow-hidden">
    {{-- BACKGROUND EFFECT --}}
    <div class="pointer-events-none absolute inset-0 -z-10 overflow-hidden">
        <div class="absolute top-[-120px] left-[-120px] h-[320px] w-[320px] rounded-full bg-amber-500/10 blur-3xl"></div>
        <div class="absolute bottom-[-140px] right-[-120px] h-[320px] w-[320px] rounded-full bg-orange-500/10 blur-3xl"></div>
    </div>

    <div class="relative z-10 mx-auto max-w-6xl space-y-8">

        <!-- Header Section -->
        <div class="reveal-up relative overflow-hidden rounded-[30px] border border-amber-500/20 bg-gradient-to-br from-[#091225] via-[#0B1730] to-[#0A1120] px-7 py-8 shadow-[0_0_40px_rgba(245,158,11,0.12)]">
            
            <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_right,rgba(245,158,11,0.18),transparent_35%)]"></div>

            <div class="relative z-10 flex flex-col gap-6 sm:flex-row sm:items-center sm:justify-between">
                <div class="flex items-center gap-5">
                    <div class="w-14 h-14 rounded-2xl bg-amber-500/10 border border-amber-500/30 flex items-center justify-center shadow-[0_0_20px_rgba(245,158,11,0.2)] shrink-0">
                        <svg class="w-7 h-7 text-amber-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </div>
                    <div>
                        <div class="inline-flex items-center gap-2 rounded-full border border-amber-500/30 bg-amber-500/10 px-4 py-1.5 text-[10px] font-bold tracking-[0.2em] text-amber-300 mb-2">
                            <span class="h-2 w-2 rounded-full bg-amber-400 animate-pulse"></span>
                            SHOPPING CART
                        </div>
                        <h1 class="text-3xl font-black text-white tracking-tight leading-tight uppercase italic">Keranjang Belanja</h1>
                        <p class="mt-2 text-sm text-slate-300">Pastikan item pesananmu sudah benar sebelum lanjut bayar.</p>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <a href="{{ route('home') }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-white/5 hover:bg-white/10 border border-white/10 text-sm font-semibold text-slate-300 hover:text-white transition-all">
                        <svg class="w-4 h-4 text-amber-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        Tambah Item Lain
                    </a>
                </div>
            </div>
        </div>

        @if($cartItems->isEmpty())
            {{-- Empty State --}}
            <div class="reveal-up rounded-[32px] border border-dashed border-white/10 bg-[#0B1220]/75 py-20 px-4 text-center backdrop-blur-xl shadow-xl">
                <div class="w-32 h-32 mx-auto bg-slate-900/50 rounded-full flex items-center justify-center mb-8 shadow-inner border border-white/5 relative">
                    <div class="absolute inset-0 bg-amber-500/10 blur-xl rounded-full animate-pulse"></div>
                    <svg class="w-16 h-16 text-slate-500 relative z-10" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                </div>
                <h2 class="text-2xl font-black text-white mb-3 italic uppercase">Keranjangmu Masih Kosong!</h2>
                <p class="text-slate-400 mb-8 max-w-md mx-auto">Lapak Gaming punya ribuan voucher dan akun menanti untuk kamu amankan. Ayo pilih sekarang!</p>
                <a href="{{ route('home') }}" class="inline-flex px-8 py-3.5 bg-gradient-to-r from-amber-500 to-orange-500 text-slate-950 font-black rounded-xl hover:from-amber-400 hover:to-orange-400 transition-all shadow-[0_0_20px_rgba(245,158,11,0.3)] active:scale-95">
                    MULAI BELANJA
                </a>
            </div>
        @else
            @php
                $cartItemCount = $cartItems->sum(fn($item) => $item->quantity);
                $subtotal = $total;
                $fee = round($subtotal * 0.02);
                $grandTotal = $subtotal + $fee;
            @endphp
            
            <div class="grid gap-8 lg:grid-cols-3 items-start">
                {{-- Daftar Item --}}
                <div class="lg:col-span-2 space-y-4">
                    <div class="reveal-up flex items-center justify-between px-2 mb-2">
                        <span class="text-xs font-black text-slate-400 uppercase tracking-[0.2em]">Detail Produk</span>
                        <span class="text-xs font-black text-slate-400 uppercase tracking-[0.2em] hidden sm:block">Total Harga</span>
                    </div>

                    @foreach($cartItems as $item)
                        <div class="reveal-up group rounded-[24px] border border-white/5 bg-[#0B1220]/90 backdrop-blur-xl p-5 flex flex-col sm:flex-row gap-5 transition-all hover:-translate-y-1 hover:border-amber-500/40 hover:shadow-[0_15px_30px_rgba(245,158,11,0.15)] relative overflow-hidden">

                            <!-- Subtle hover glow effect -->
                            <div class="absolute inset-0 bg-gradient-to-r from-amber-500/0 via-amber-500/5 to-transparent opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none"></div>

                            {{-- Checkbox Pilihan --}}
                            <div class="flex items-center justify-center shrink-0 pr-1 relative z-30">
                                <input type="checkbox" 
                                       class="w-6 h-6 rounded-md border-slate-600 bg-slate-800 text-amber-500 focus:ring-amber-500/50 cursor-pointer relative z-50 pointer-events-auto transition-all checked:shadow-[0_0_10px_rgba(245,158,11,0.5)]"
                                       {{ $item->is_selected ? 'checked' : '' }}
                                       onchange="toggleSelectItem({{ $item->id }})">
                            </div>

                            {{-- Gambar Produk --}}
                            <div class="shrink-0 relative w-28 sm:w-36 aspect-[16/9] z-10">
                                <img src="{{ $item->product->image_url }}"
                                     class="w-full h-full rounded-xl object-cover border border-white/10 shadow-inner group-hover:border-amber-500/30 transition-colors"
                                     onerror="this.onerror=null;this.src='https://ui-avatars.com/api/?name={{ urlencode($item->product->name) }}&background=1e293b&color=f59e0b&bold=true&size=128';">
                            </div>

                            {{-- Info Produk --}}
                            <div class="flex-1 flex flex-col justify-center min-w-0 z-10">
                                <div class="flex items-center gap-2 mb-2">
                                    <span class="px-2.5 py-1 bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 text-[10px] font-black uppercase rounded-md tracking-wider">
                                        STOK TERSEDIA
                                    </span>
                                    <span class="px-2.5 py-1 bg-white/5 border border-white/10 text-slate-300 text-[10px] font-black uppercase rounded-md tracking-wider">
                                        {{ $item->product->type_label ?? 'ITEM' }}
                                    </span>
                                </div>
                                <h3 class="text-lg font-bold text-white leading-tight mb-1 truncate group-hover:text-amber-400 transition-colors">
                                    {{ $item->product->name }}
                                </h3>

                                <div class="flex items-center gap-2 text-xs text-slate-400 mt-2">
                                    <svg class="w-3.5 h-3.5 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                                    Seller: <span class="text-slate-300 font-bold">{{ $item->product->seller->name }}</span>
                                </div>
                            </div>

                            {{-- Harga & Aksi --}}
                            <div class="flex flex-row sm:flex-col items-center sm:items-end justify-between sm:justify-center gap-4 sm:border-l border-white/5 sm:pl-8 min-w-[150px] z-10">
                                <div class="text-xl font-black text-amber-500 italic">
                                    Rp {{ number_format($item->product->price, 0, ',', '.') }}
                                </div>

                                {{-- Quantity Selector --}}
                                <div class="flex items-center gap-1.5 mt-1 bg-black/20 p-1 rounded-xl border border-white/5">
                                    <button type="button" 
                                            data-item-id="{{ $item->id }}"
                                            onclick="decrementQtyFromElement(this)" 
                                            class="w-8 h-8 rounded-lg bg-white/5 text-slate-300 hover:bg-white/10 hover:text-white flex items-center justify-center font-black text-lg select-none active:scale-95 transition-all">-</button>
                                    
                                    <input type="number" 
                                           id="qty-input-{{ $item->id }}" 
                                           data-item-id="{{ $item->id }}"
                                           data-max-stock="{{ $item->product->stock }}"
                                           value="{{ $item->quantity }}" 
                                           min="1" 
                                           max="{{ $item->product->stock }}"
                                           onchange="onQtyInputChangeFromElement(this)"
                                           class="w-10 h-8 text-center bg-transparent border-none text-white font-black text-sm p-0 focus:ring-0 [appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none">
                                    
                                    <button type="button" 
                                            data-item-id="{{ $item->id }}"
                                            data-max-stock="{{ $item->product->stock }}"
                                            onclick="incrementQtyFromElement(this)" 
                                            class="w-8 h-8 rounded-lg bg-white/5 text-slate-300 hover:bg-white/10 hover:text-white flex items-center justify-center font-black text-lg select-none active:scale-95 transition-all">+</button>
                                </div>

                                <form action="{{ route('cart.remove', $item->id) }}" method="POST" class="mt-2">
                                    @csrf @method('DELETE')
                                    <button class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-[11px] font-bold text-slate-500 hover:text-rose-400 hover:bg-rose-500/10 transition-all uppercase tracking-wider" onclick="return confirm('Hapus item ini?')">
                                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                        Hapus
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- Sidebar --}}
                <div class="reveal-up lg:col-span-1 space-y-6 sticky top-28">

                    {{-- Buyer Protection --}}
                    <div class="rounded-3xl border border-blue-500/20 bg-[#0B1220]/90 backdrop-blur-xl p-5 shadow-[0_0_30px_rgba(59,130,246,0.1)] relative overflow-hidden">
                        <div class="absolute -right-4 -top-4 w-24 h-24 bg-blue-500/10 rounded-full blur-xl pointer-events-none"></div>
                        <div class="flex items-start gap-4 relative z-10">
                            <div class="p-2.5 bg-blue-500/10 border border-blue-500/20 rounded-xl shrink-0">
                                <svg class="w-6 h-6 text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-sm font-bold text-blue-400 mb-1">Proteksi Pembeli 100%</h3>
                                <p class="text-[11px] text-slate-300 leading-relaxed font-medium">Dana kamu aman. Saldo hanya diteruskan ke seller setelah pesanan berhasil kamu terima.</p>
                            </div>
                        </div>
                    </div>

                    {{-- Ringkasan --}}
                    <div class="rounded-[32px] border border-white/5 bg-[#0B1220]/90 backdrop-blur-xl p-8 shadow-[0_0_40px_rgba(0,0,0,0.3)]">
                        <h2 class="text-base font-black text-white mb-6 uppercase tracking-[0.2em] italic border-b border-white/5 pb-4">
                            Ringkasan Belanja
                        </h2>

                        <div class="space-y-4 border-b border-white/5 pb-6 text-sm">
                            <div class="flex justify-between items-center">
                                <span class="text-slate-400 font-bold uppercase tracking-tighter text-[11px]">Total Item Terpilih</span>
                                <span class="text-white font-black">{{ $cartItems->filter(fn($c) => $c->is_selected)->sum('quantity') }} Pcs</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-slate-400 font-bold uppercase tracking-tighter text-[11px]">Subtotal</span>
                                <span class="text-white font-black text-base">Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-slate-400 font-bold uppercase tracking-tighter text-[11px]">Biaya Platform</span>
                                <span class="text-emerald-400 font-black bg-emerald-500/10 border border-emerald-500/20 px-2 py-0.5 rounded text-[10px]">2%</span>
                            </div>
                        </div>

                        <div class="py-6">
                            <span class="text-[10px] font-black text-slate-500 uppercase tracking-widest block mb-1">Total Tagihan</span>
                            <span class="text-3xl font-black text-transparent bg-clip-text bg-gradient-to-r from-amber-400 to-orange-500 italic tracking-tighter drop-shadow-[0_0_10px_rgba(245,158,11,0.3)]">
                                Rp {{ number_format($grandTotal, 0, ',', '.') }}
                            </span>
                        </div>

                        @if($cartItems->filter(fn($c) => $c->is_selected)->isNotEmpty())
                            <a href="{{ route('cart.checkout') }}"
                               class="w-full py-4 bg-gradient-to-r from-amber-500 to-orange-500 text-slate-950 font-black text-lg rounded-2xl hover:from-amber-400 hover:to-orange-400 transition-all shadow-[0_0_20px_rgba(245,158,11,0.3)] active:scale-95 flex items-center justify-center gap-2 uppercase tracking-wide">
                                LANJUT PEMBAYARAN
                            </a>
                        @else
                            <button type="button" disabled
                                    class="w-full py-4 bg-white/5 border border-white/10 text-slate-500 font-black text-sm rounded-2xl cursor-not-allowed flex items-center justify-center gap-2 uppercase tracking-wide">
                                Pilih Produk Terlebih Dahulu
                            </button>
                        @endif

                        <div class="mt-8 pt-6 border-t border-white/5">
                            <div class="flex items-center justify-center gap-3 opacity-40 grayscale hover:grayscale-0 transition-all">
                            </div>
                            <p class="mt-4 text-[9px] text-slate-500 text-center font-bold tracking-widest leading-relaxed uppercase flex items-center justify-center gap-1.5">
                                <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                                Transaksi Aman & Terenkripsi
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>

{{-- REVEAL ANIMATION --}}
<style>
.reveal-up{
    opacity:0;
    transform:translateY(50px);
    animation:revealUp 0.8s cubic-bezier(.22,1,.36,1) forwards;
    will-change:transform, opacity;
}

.reveal-up:nth-child(2){animation-delay:.08s;}
.reveal-up:nth-child(3){animation-delay:.16s;}
.reveal-up:nth-child(4){animation-delay:.24s;}
.reveal-up:nth-child(5){animation-delay:.32s;}
.reveal-up:nth-child(6){animation-delay:.40s;}
.reveal-up:nth-child(7){animation-delay:.48s;}

@keyframes revealUp{
    to{
        opacity:1;
        transform:translateY(0);
    }
}
</style>

<script>
    function toggleSelectItem(id) {
        fetch(`/cart/${id}/toggle-select`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(res => res.ok ? res.json() : Promise.reject(res))
        .then(data => data.success ? window.location.reload() : alert("Gagal memilih produk."))
        .catch(() => alert("Terjadi kesalahan koneksi saat memilih produk."));
    }

    function updateQty(id, newQty) {
        fetch(`/cart/${id}`, {
            method: 'PATCH',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ quantity: newQty })
        })
        .then(async res => {
            if (res.ok) window.location.reload();
            else {
                const data = await res.json();
                alert(data.message || 'Gagal mengubah jumlah produk');
                window.location.reload();
            }
        })
        .catch(error => alert("Terjadi kesalahan koneksi: " + error.message));
    }

    function incrementQty(id, stock) {
        const input = document.getElementById(`qty-input-${id}`);
        let current = parseInt(input.value) || 1;
        if (current < stock) updateQty(id, current + 1);
        else alert('Mencapai batas stok maksimal penjual!');
    }

    function decrementQty(id) {
        const input = document.getElementById(`qty-input-${id}`);
        let current = parseInt(input.value) || 1;
        if (current > 1) updateQty(id, current - 1);
    }

    function onQtyInputChange(id, stock) {
        const input = document.getElementById(`qty-input-${id}`);
        let val = parseInt(input.value) || 1;
        if (val < 1) val = 1;
        if (val > stock) {
            alert('Stok tidak cukup!');
            val = stock;
        }
        updateQty(id, val);
    }

    function incrementQtyFromElement(el) {
        incrementQty(el.dataset.itemId, Number(el.dataset.maxStock));
    }

    function decrementQtyFromElement(el) {
        decrementQty(el.dataset.itemId);
    }

    function onQtyInputChangeFromElement(el) {
        onQtyInputChange(el.dataset.itemId, Number(el.dataset.maxStock));
    }
</script>
@endsection
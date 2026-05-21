@php
use Illuminate\Support\Facades\Storage;
@endphp

@extends('layouts.app')
@php 
use Illuminate\Support\Str; 
@endphp

@section('title', 'Keranjang Belanja — Lapak Gaming')

@section('content')
    <div class="min-h-screen bg-slate-950 py-10 px-4 relative">
        {{-- Background Glow --}}
        <div
            class="absolute top-0 left-1/2 -translate-x-1/2 w-[800px] h-[300px] bg-amber-500/5 blur-[120px] pointer-events-none">
        </div>

        <div class="mx-auto max-w-6xl space-y-8 relative z-10">

            {{-- Header --}}
            <div class="flex flex-col md:flex-row md:items-end justify-between gap-4 border-b border-slate-800/60 pb-6">
                <div>
                    <div class="flex items-center gap-3 mb-2">
                        <div class="p-2 bg-amber-500/10 rounded-lg text-amber-500">
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                        </div>
                        <h1 class="text-3xl font-black text-white italic tracking-tight uppercase">Keranjang Belanja</h1>
                    </div>
                    <p class="text-sm text-slate-400 font-medium ml-11">Pastikan item pesananmu sudah benar sebelum lanjut
                        bayar.</p>
                </div>
                <a href="{{ route('home') }}"
                    class="inline-flex items-center gap-2 text-sm font-bold text-slate-400 hover:text-amber-500 transition-colors">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Tambah Item Lain
                </a>
            </div>

            @if($cartItems->isEmpty())
                {{-- Empty State (SVG Based - Anti Pecah) --}}
                <div
                    class="rounded-[2rem] border border-slate-800 bg-slate-900/40 p-16 flex flex-col items-center justify-center text-center shadow-xl">
                    <div
                        class="w-32 h-32 bg-slate-950 rounded-full flex items-center justify-center mb-8 shadow-inner border border-slate-800 relative">
                        <div class="absolute inset-0 bg-amber-500/10 blur-xl rounded-full"></div>
                        <svg class="w-16 h-16 text-slate-700 relative z-10" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </div>
                    <h2 class="text-2xl font-black text-white mb-2 italic uppercase">Keranjangmu Masih Kosong!</h2>
                    <p class="text-slate-400 mb-8 max-w-sm mx-auto">Lapak Gaming punya ribuan voucher dan akun menanti untuk
                        kamu amankan.</p>
                    <a href="{{ route('home') }}"
                        class="px-8 py-3.5 bg-amber-500 text-slate-950 font-black rounded-xl hover:bg-amber-400 transition-all shadow-lg shadow-amber-500/20 active:scale-95">
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
                        <div class="flex items-center justify-between px-2 mb-2">
                            <span class="text-xs font-black text-slate-500 uppercase tracking-[0.2em]">Detail Produk</span>
                            <span class="text-xs font-black text-slate-500 uppercase tracking-[0.2em] hidden sm:block">Total
                                Harga</span>
                        </div>

                        @foreach($cartItems as $item)
                            <div
                                class="group rounded-2xl border border-slate-800 bg-slate-900/60 p-5 flex flex-col sm:flex-row gap-5 transition-all hover:border-amber-500/40 hover:bg-slate-900 shadow-md">

                                {{-- Checkbox Pilihan --}}
                                <div class="flex items-center justify-center shrink-0 pr-1 relative z-30">
                                    <input type="checkbox" 
                                           class="w-5 h-5 rounded border-slate-700 bg-slate-950 text-amber-500 focus:ring-amber-500/50 cursor-pointer relative z-50 pointer-events-auto"
                                           {{ $item->is_selected ? 'checked' : '' }}
                                           onchange="toggleSelectItem({{ $item->id }})">
                                </div>

                                {{-- Gambar Produk --}}
                                <div class="shrink-0 relative">
                                    <img src="{{ $item->product->image_url }}"
                                         class="w-24 h-24 sm:w-28 sm:h-28 rounded-xl object-cover border border-slate-700 shadow-inner"
                                         onerror="this.onerror=null;this.src='https://ui-avatars.com/api/?name={{ urlencode($item->product->name) }}&background=1e293b&color=f59e0b&bold=true&size=128';">
                                </div>

                                {{-- Info Produk --}}
                                <div class="flex-1 flex flex-col justify-center min-w-0">
                                    <div class="flex items-center gap-2 mb-1.5">
                                        <span
                                            class="px-2 py-0.5 bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 text-[10px] font-black uppercase rounded">STOK
                                            TERSEDIA</span>
                                        <span
                                            class="px-2 py-0.5 bg-slate-800 text-slate-400 text-[10px] font-black uppercase rounded">{{ $item->product->type_label ?? 'ITEM' }}</span>
                                    </div>
                                    <h3
                                        class="text-lg font-bold text-white leading-tight mb-1 truncate group-hover:text-amber-500 transition-colors">
                                        {{ $item->product->name }}</h3>

                                    <div class="flex items-center gap-2 text-xs text-slate-400 mb-3">
                                        Seller: <span class="text-amber-500/80 font-bold">{{ $item->product->seller->name }}</span>
                                    </div>

                                    {{-- Catatan untuk Penjual --}}
                                    <div class="mt-1">
                                        <label class="text-[10px] uppercase tracking-wider font-bold text-slate-500 block mb-1">Catatan untuk Penjual</label>
                                        <input type="text" 
                                               id="note-input-{{ $item->id }}" 
                                               data-item-id="{{ $item->id }}"
                                               placeholder="Tulis catatan (misal: kirim instan ya, username/ID game dll)..." 
                                               value="{{ $item->notes }}" 
                                               onchange="updateItemNoteFromElement(this)" 
                                               class="w-full text-xs rounded-xl bg-slate-950 border border-slate-800 text-slate-300 px-3.5 py-2.5 outline-none focus:border-amber-500/50 transition-colors">
                                    </div>
                                </div>

                                {{-- Harga & Aksi --}}
                                <div
                                    class="flex flex-row sm:flex-col items-center sm:items-end justify-between sm:justify-center gap-4 sm:border-l border-slate-800 sm:pl-8 min-w-[150px]">
                                    <div class="text-xl font-black text-amber-500 italic">
                                        Rp {{ number_format($item->product->price, 0, ',', '.') }}
                                    </div>

                                    {{-- Quantity Selector --}}
                                    <div class="flex items-center gap-2 mt-2">
                                        <button type="button" 
                                                data-item-id="{{ $item->id }}"
                                                onclick="decrementQtyFromElement(this)" 
                                                class="w-8 h-8 rounded-lg bg-slate-800 text-slate-400 hover:bg-slate-700 hover:text-white flex items-center justify-center font-black text-lg select-none active:scale-95 transition-all">-</button>
                                        
                                        <input type="number" 
                                               id="qty-input-{{ $item->id }}" 
                                               data-item-id="{{ $item->id }}"
                                               data-max-stock="{{ $item->product->stock }}"
                                               value="{{ $item->quantity }}" 
                                               min="1" 
                                               max="{{ $item->product->stock }}"
                                               onchange="onQtyInputChangeFromElement(this)"
                                               class="w-12 h-8 text-center rounded-lg bg-slate-950 border border-slate-800 text-white font-black text-sm [appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none focus:border-amber-500/50 focus:outline-none">
                                        
                                        <button type="button" 
                                                data-item-id="{{ $item->id }}"
                                                data-max-stock="{{ $item->product->stock }}"
                                                onclick="incrementQtyFromElement(this)" 
                                                class="w-8 h-8 rounded-lg bg-slate-800 text-slate-400 hover:bg-slate-700 hover:text-white flex items-center justify-center font-black text-lg select-none active:scale-95 transition-all">+</button>
                                    </div>

                                    <form action="{{ route('cart.remove', $item->id) }}" method="POST" class="mt-2">
                                        @csrf @method('DELETE')
                                        <button
                                            class="inline-flex items-center gap-2 px-3 py-1.5 rounded-lg text-xs font-bold text-slate-600 hover:text-rose-500 hover:bg-rose-500/10 transition-all"
                                            onclick="return confirm('Hapus item ini?')">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    {{-- Sidebar --}}
                    <div class="lg:col-span-1 space-y-6 sticky top-6">

                        {{-- Buyer Protection --}}
                        <div class="rounded-2xl border border-blue-500/20 bg-blue-500/5 p-5 shadow-lg">
                            <div class="flex items-start gap-3">
                                <div class="p-2 bg-blue-500/20 rounded-lg shrink-0">
                                    <svg class="w-6 h-6 text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-sm font-bold text-blue-400 mb-1">Proteksi Pembeli 100%</h3>
                                    <p class="text-[11px] text-slate-400 leading-relaxed font-medium">Dana kamu aman. Saldo
                                        hanya diteruskan ke seller setelah pesanan berhasil kamu terima.</p>
                                </div>
                            </div>
                        </div>

                        {{-- Ringkasan --}}
                        <div class="rounded-[2rem] border border-slate-800 bg-slate-900/80 p-8 shadow-xl">
                            <h2 class="text-base font-black text-white mb-6 uppercase tracking-[0.2em] italic">Ringkasan Belanja
                            </h2>

                            <div class="space-y-4 border-b border-slate-800 pb-6 text-sm">
                                <div class="flex justify-between items-center">
                                    <span class="text-slate-500 font-bold uppercase tracking-tighter">Total Item Terpilih</span>
                                    <span class="text-white font-black">{{ $cartItems->filter(fn($c) => $c->is_selected)->sum('quantity') }} Pcs</span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-slate-500 font-bold uppercase tracking-tighter">Subtotal</span>
                                    <span class="text-white font-black text-base">Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-slate-500 font-bold uppercase tracking-tighter">Biaya Platform</span>
                                    <span class="text-emerald-500 font-black bg-emerald-500/10 px-2 py-0.5 rounded text-[10px]">2%</span>
                                </div>
                            </div>

                            <div class="py-6">
                                <span class="text-[10px] font-black text-slate-600 uppercase tracking-widest block mb-1">Total
                                    Tagihan</span>
                                <span class="text-3xl font-black text-amber-500 italic tracking-tighter">
                                    Rp {{ number_format($grandTotal, 0, ',', '.') }}
                                </span>
                            </div>

                            @if($cartItems->filter(fn($c) => $c->is_selected)->isNotEmpty())
                                <a href="{{ route('cart.checkout') }}"
                                   class="w-full py-4 bg-amber-500 text-slate-950 font-black text-lg rounded-2xl hover:bg-amber-400 transition-all shadow-lg shadow-amber-500/20 active:scale-95 flex items-center justify-center gap-2 uppercase">
                                    LANJUT PEMBAYARAN
                                </a>
                            @else
                                <button type="button" disabled
                                        class="w-full py-4 bg-slate-800/80 text-slate-500 font-black text-lg rounded-2xl cursor-not-allowed flex items-center justify-center gap-2 uppercase">
                                    Pilih Produk Terlebih Dahulu
                                </button>
                            @endif

                            <div class="mt-8 pt-6 border-t border-slate-800">
                                <div
                                    class="flex items-center justify-center gap-2 opacity-30 grayscale hover:grayscale-0 transition-all">
                                    <img src="https://upload.wikimedia.org/wikipedia/commons/b/b5/PayPal.svg" class="h-3"
                                        alt="Paypal">
                                    <img src="https://upload.wikimedia.org/wikipedia/commons/5/5e/Visa_Inc._logo.svg"
                                        class="h-2.5" alt="Visa">
                                    <img src="https://upload.wikimedia.org/wikipedia/commons/2/2a/Mastercard-logo.svg"
                                        class="h-4" alt="Mastercard">
                                </div>
                                <p
                                    class="mt-4 text-[9px] text-slate-600 text-center font-bold tracking-widest leading-relaxed uppercase">
                                    Transaksi Aman & Terenkripsi
                                </p>
                            </div>
                        </div>
                    </div>

                </div>
            @endif
        </div>
    </div>

    <script>
        function toggleSelectItem(id) {
            console.log("Toggling select for item:", id);
            fetch(`/cart/${id}/toggle-select`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(res => {
                console.log("Toggle Select response status:", res.status);
                if (!res.ok) {
                    throw new Error("HTTP error " + res.status);
                }
                return res.json();
            })
            .then(data => {
                console.log("Toggle Select response data:", data);
                if (data.success) {
                    window.location.reload();
                } else {
                    alert("Gagal memilih produk: " + (data.message || "Error tidak diketahui"));
                }
            })
            .catch(error => {
                console.error("Toggle Select error:", error);
                alert("Terjadi kesalahan koneksi saat memilih produk: " + error.message);
            });
        }

        function updateItemNoteFromElement(el) {
            const id = el.dataset.itemId;
            const note = el.value;
            console.log("Updating note for item:", id, "Note:", note);
            fetch(`/cart/${id}/update-note`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ notes: note })
            })
            .then(res => {
                if (!res.ok) {
                    throw new Error("HTTP error " + res.status);
                }
                return res.json();
            })
            .then(data => {
                console.log("Update Note response data:", data);
                if (data.success) {
                    el.classList.add('border-emerald-500');
                    setTimeout(() => {
                        el.classList.remove('border-emerald-500');
                    }, 1000);
                }
            })
            .catch(error => {
                console.error("Update Note error:", error);
            });
        }

        function updateQty(id, newQty) {
            console.log("Updating qty for item:", id, "New Qty:", newQty);
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
                if (res.ok) {
                    window.location.reload();
                } else {
                    const data = await res.json();
                    alert(data.message || 'Gagal mengubah jumlah produk');
                    window.location.reload();
                }
            })
            .catch(error => {
                console.error("Update Qty error:", error);
                alert("Terjadi kesalahan koneksi: " + error.message);
            });
        }

        function incrementQty(id, stock) {
            const input = document.getElementById(`qty-input-${id}`);
            let current = parseInt(input.value) || 1;
            if (current < stock) {
                updateQty(id, current + 1);
            } else {
                alert('Mencapai batas stok maksimal penjual!');
            }
        }

        function decrementQty(id) {
            const input = document.getElementById(`qty-input-${id}`);
            let current = parseInt(input.value) || 1;
            if (current > 1) {
                updateQty(id, current - 1);
            }
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
            const id = el.dataset.itemId;
            const stock = Number(el.dataset.maxStock);
            incrementQty(id, stock);
        }

        function decrementQtyFromElement(el) {
            const id = el.dataset.itemId;
            decrementQty(id);
        }

        function onQtyInputChangeFromElement(el) {
            const id = el.dataset.itemId;
            const stock = Number(el.dataset.maxStock);
            onQtyInputChange(id, stock);
        }
    </script>
@endsection
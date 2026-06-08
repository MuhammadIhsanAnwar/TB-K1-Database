@extends('layouts.app')
@section('title', 'Checkout — Lapak Gaming')

@push('styles')
<style>
  .checkout-shell {
    background:
      radial-gradient(circle at top left, rgba(56, 189, 248, 0.16), transparent 28%),
      radial-gradient(circle at top right, rgba(249, 115, 22, 0.12), transparent 24%),
      linear-gradient(180deg, #050816 0%, #0a1120 100%);
  }

  .checkout-panel {
    background: linear-gradient(180deg, rgba(9, 18, 37, 0.96), rgba(7, 12, 24, 0.96));
    border: 1px solid rgba(255, 255, 255, 0.08);
    box-shadow: 0 24px 80px rgba(37, 99, 235, 0.12);
  }
</style>
@endpush

@section('content')
<div class="checkout-shell min-h-[calc(100vh-80px)] py-10">
    <div class="mx-auto max-w-6xl px-4">
        
        {{-- Header --}}
        <div class="mb-7 flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <p class="text-xs font-display font-semibold uppercase tracking-[0.24em] text-sky-300">Checkout</p>
                <h1 class="mt-2 font-display text-3xl font-black text-white md:text-4xl">Checkout Pesanan</h1>
                <p class="mt-2 max-w-2xl text-sm leading-relaxed text-slate-400">Periksa item, pilih metode pembayaran, dan pastikan detail pesanan sudah sesuai sebelum dibuat.</p>
            </div>

            <a href="{{ route('marketplace.home') }}" class="inline-flex items-center gap-2 rounded-2xl border border-white/10 bg-white/[0.03] px-4 py-2 text-sm font-semibold text-slate-200 transition hover:border-sky-400/20 hover:bg-sky-500/10 hover:text-sky-200">
                Kembali ke Beranda
            </a>
        </div>

        @if($errors->any())
        <div class="mb-6 flex gap-3 rounded-[24px] border border-rose-500/20 bg-rose-500/10 p-4 text-rose-200">
            <svg class="mt-0.5 h-5 w-5 shrink-0 text-rose-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <div>
                <h3 class="mb-1 text-sm font-bold text-rose-100">Pemesanan Gagal</h3>
                <ul class="list-disc list-inside space-y-1 text-xs text-rose-200/90">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
        @endif

        <form action="{{ route('cart.store') }}" method="POST">
            @csrf
            <input type="hidden" name="payment_method" value="balance">
            
            <div class="grid gap-6 lg:grid-cols-[1fr_380px]">
                
                {{-- Left Column: Items & Payment --}}
                <div class="space-y-6">
                    
                    {{-- Items --}}
                    <div class="checkout-panel overflow-hidden rounded-[30px]">
                        <div class="border-b border-white/8 px-5 py-4">
                            <h2 class="font-display text-sm font-bold uppercase tracking-[0.22em] text-sky-300">Item yang Dibeli</h2>
                        </div>
                        <div class="space-y-4 p-5">
                            @foreach($groupSummaries as $sellerId => $summary)
                                <div class="mb-4 rounded-[18px] border border-white/6 bg-white/[0.02] p-4">
                                    <div class="mb-3 flex items-center justify-between">
                                        <div class="flex items-center gap-3">
                                            <div class="h-10 w-10 rounded-full bg-white/5 flex items-center justify-center text-sm font-bold text-white">{{ strtoupper(substr($summary['seller']->store_name ?? $summary['seller']->name ?? 'T',0,1)) }}</div>
                                            <div>
                                                <div class="text-sm font-bold text-white">{{ $summary['seller']->store_name ?? $summary['seller']->name ?? 'Penjual' }}</div>
                                                <div class="text-xs text-slate-400">Items: {{ $summary['items']->count() }} | Subtotal: Rp {{ number_format($summary['subtotal'],0,',','.') }}</div>
                                            </div>
                                        </div>
                                    </div>

                                    @foreach($summary['items'] as $item)
                                        <div class="flex gap-4 rounded-[16px] border border-white/5 bg-white/[0.02] p-3 mb-3">
                                            <img src="{{ $item->product->image_url }}" class="w-20 shrink-0 rounded-2xl object-cover aspect-[16/9] border border-white/8" alt="">
                                            <div class="min-w-0 flex-1">
                                                <p class="mb-1 text-sm font-bold leading-tight text-white">{{ $item->product->name }}</p>
                                                <div class="mb-2 flex items-center gap-2">
                                                    <span class="rounded-full bg-sky-500/10 px-2.5 py-1 text-[10px] font-bold text-sky-300">{{ $item->product->category->name ?? 'Game' }}</span>
                                                </div>
                                                <div class="flex items-end justify-between gap-3">
                                                    <p class="text-sm font-semibold text-slate-300">Rp {{ number_format($item->product->price, 0, ',', '.') }} <span class="text-xs font-normal text-slate-500">× {{ $item->quantity }}</span></p>
                                                    <p class="text-sm font-black text-sky-300">Rp {{ number_format($item->product->price * $item->quantity, 0, ',', '.') }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach

                                    {{-- Per-seller note for this group --}}
                                    <div class="mt-2">
                                        <label class="block text-sm font-medium text-slate-300 mb-2">Catatan untuk penjual (opsional)</label>
                                        <textarea name="seller_notes[{{ $sellerId }}]" rows="3" placeholder="Contoh: Tolong kirim cepat, sertakan kode lisensi di catatan." class="w-full resize-none rounded-[12px] border border-white/10 bg-white/[0.02] px-3 py-2 text-white placeholder:text-slate-500">{{ old('seller_notes.'.$sellerId) }}</textarea>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- Payment Method --}}
                    <div class="checkout-panel overflow-hidden rounded-[30px]">
                        <div class="border-b border-white/8 px-5 py-4">
                            <h2 class="font-display text-sm font-bold uppercase tracking-[0.22em] text-sky-300">Catatan Pesanan</h2>
                        </div>

                        <div class="p-5">
                            <textarea
                                name="buyer_note"
                                rows="4"
                                placeholder="Contoh: Kirim cepat bang, login via Google, dll."
                                class="input resize-none rounded-[22px] border border-white/10 bg-white/[0.03] px-4 py-3 text-white placeholder:text-slate-500"
                            >{{ old('buyer_note') }}</textarea>
                        </div>
                    </div>

                    <div class="checkout-panel overflow-hidden rounded-[30px]">
                        <div class="border-b border-white/8 px-5 py-4">
                            <h2 class="font-display text-sm font-bold uppercase tracking-[0.22em] text-sky-300">Metode Pembayaran</h2>
                        </div>
                        <div class="p-5">
                            <label class="flex items-center gap-4 rounded-[24px] border border-sky-400/20 bg-sky-500/8 p-4 relative">
                                <input type="radio" checked class="h-4 w-4 text-sky-400">
                                <div class="flex-1">
                                    <div class="mb-0.5 text-sm font-bold text-white">Saldo Lapak Gaming (Dompet)</div>
                                    <div class="text-xs text-slate-400">Saldo saat ini: <span class="font-bold {{ auth()->user()->balance < $total ? 'text-rose-300' : 'text-emerald-300' }}">Rp {{ number_format(auth()->user()->balance, 0, ',', '.') }}</span></div>
                                </div>
                                @if(auth()->user()->balance < $total)
                                    <a href="{{ route('wallet.index') }}" class="rounded-full border border-white/10 bg-white/[0.03] px-3 py-1.5 text-xs font-bold text-slate-200 transition hover:border-sky-400/20 hover:bg-sky-500/10">Isi Saldo</a>
                                @endif
                            </label>
                        </div>
                    </div>

                </div>

                {{-- Right Column: Summary --}}
                <div>
                    <div class="checkout-panel sticky top-20 rounded-[30px] p-6">
                        <h2 class="font-display text-sm font-bold uppercase tracking-[0.22em] text-sky-300 mb-4">Ringkasan Belanja</h2>
                        
                        <div class="space-y-3 mb-5 text-sm">
                            <div class="flex justify-between gap-4 text-slate-400">
                                <span>Total Harga ({{ $cartItems->sum('quantity') }} Item)</span>
                                <span class="font-semibold text-white">Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between gap-4 text-slate-400">
                                <span>Biaya Layanan</span>
                                <span class="font-semibold text-white">Rp {{ number_format($fee, 0, ',', '.') }}</span>
                            </div>
                        </div>
                        
                        <div class="mb-6 rounded-[24px] border border-white/10 bg-white/[0.03] p-4">
                            <div class="flex items-center justify-between gap-4">
                                <span class="font-bold text-white">Total Tagihan</span>
                                <span class="font-display text-2xl font-black text-sky-300">Rp {{ number_format($total, 0, ',', '.') }}</span>
                            </div>
                        </div>

                        @if(auth()->user()->balance >= $total)
                            <button type="submit" class="w-full rounded-2xl bg-gradient-to-r from-sky-500 to-blue-600 py-3.5 font-bold text-white shadow-[0_16px_30px_rgba(37,99,235,0.28)] transition hover:-translate-y-0.5 hover:from-sky-400 hover:to-blue-500">
                                Bayar Sekarang
                            </button>
                        @else
                            <button type="button" disabled class="w-full cursor-not-allowed rounded-2xl bg-white/[0.04] py-3.5 font-bold text-slate-500">
                                Saldo Tidak Cukup
                            </button>
                        @endif
                        
                        <p class="mt-4 flex items-center justify-center gap-1 text-center text-[10px] text-slate-500">
                            <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                            Pembayaran 100% Aman
                        </p>
                    </div>
                </div>

            </div>
        </form>
    </div>
</div>
@endsection
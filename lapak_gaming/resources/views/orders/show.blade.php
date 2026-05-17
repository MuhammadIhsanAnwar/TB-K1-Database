@extends('layouts.app')

@section('title', 'Detail Transaksi')

@section('content')

<style>
    .reveal {
        opacity: 0;
        transform: translateY(30px);
        animation: revealUp .8s ease forwards;
    }

    .reveal-delay-1 {
        animation-delay: .15s;
    }

    .reveal-delay-2 {
        animation-delay: .3s;
    }

    @keyframes revealUp {
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
</style>

<div class="min-h-screen py-12 px-4 bg-[#060816]">
    <div class="mx-auto max-w-5xl space-y-6">
        <div class="reveal relative overflow-hidden rounded-[30px] border border-blue-500/20 bg-[#0B1220]/95 p-8 shadow-[0_0_80px_rgba(37,99,235,0.08)]">
            <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-white">{{ $order->invoice_number }}</h1>
                    <p class="mt-2 text-slate-400">Status: {{ $order->status_label }}</p>
                </div>
                <div class="space-y-1 text-right text-slate-300">
                    <div>Grand Total</div>
                    <div class="text-2xl font-bold text-white">Rp {{ number_format($order->grand_total, 0, ',', '.') }}</div>
                </div>
            </div>

            <div class="mt-8 grid gap-4 md:grid-cols-2">
                <div class="rounded-3xl border border border-white/5 bg-white/[0.03] p-5">
                    <h2 class="text-lg font-semibold text-white">Buyer</h2>
                    <p class="mt-2 text-slate-400">{{ $order->buyer->name ?? '-' }}</p>
                    <p class="text-slate-500">{{ $order->buyer->email ?? '-' }}</p>
                </div>
                <div class="rounded-3xl border border border-white/5 bg-white/[0.03] p-5">
                    <h2 class="text-lg font-semibold text-white">Seller</h2>
                    <p class="mt-2 text-slate-400">{{ $order->seller->name ?? '-' }}</p>
                    <p class="text-slate-500">{{ $order->seller->email ?? '-' }}</p>
                </div>
            </div>

            <div class="border border-white/5 bg-white/[0.03]border border-white/5 bg-white/[0.03]">
                <div class="border-b border-white/5 bg-white/[0.03] px-6 py-4 text-sm uppercase tracking-[0.2em] text-blue-300"> Pesanan</div>
                <div class="divide-y divide-slate-800">
                    @foreach($order->items as $item)
                        @php
                            $itemName = $item->name_snapshot ?? $item->product?->name ?? 'Produk';
                            $itemSubtotal = (float) $item->price_snapshot * (int) $item->quantity;
                        @endphp
                        <div class="flex flex-col gap-3 px-6 py-5 transition duration-300 hover:bg-blue-500/[0.04] md:flex-row md:items-center md:justify-between">
                            <div>
                                <div class="font-semibold text-white">{{ $itemName }}</div>
                                <div class="text-slate-400">Jumlah: {{ $item->quantity }}</div>
                            </div>
                            <div class="text-right text-white">Rp {{ number_format($itemSubtotal, 0, ',', '.') }}</div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
                <div class="rounded-3xl border border border-white/5 bg-white/[0.03] p-5">
                    <div class="text-slate-400">Metode Pembayaran</div>
                    <div class="mt-2 text-white">{{ $order->payment_method ?? 'Belum dipilih' }}</div>
                </div>
                <div class="rounded-3xl border border border-white/5 bg-white/[0.03] p-5">
                    <div class="text-slate-400">Tanggal Pesanan</div>
                    <div class="mt-2 text-white">{{ $order->created_at->translatedFormat('d F Y H:i') }}</div>
                </div>
            </div>

            <div class="mt-6 grid gap-4 md:grid-cols-2">
                <a href="{{ route('chat.order', $order) }}" class="rounded-[22px] border border-blue-500/20 bg-blue-500/10 px-5 py-3 text-center font-semibold text-blue-300 transition duration-300 hover:-translate-y-1 hover:border-blue-400/40 hover:bg-blue-500/20">Chat dengan Seller</a>

                @if(in_array($order->status, [\App\Models\Order::STATUS_PENDING_PAYMENT, \App\Models\Order::STATUS_PAYMENT_UPLOADED], true))
                    <form action="{{ route('orders.cancel', $order) }}" method="POST">
                        @csrf
                        <button type="submit" class="w-full rounded-[22px] border border-red-500/20 bg-red-500/10 px-5 py-3 font-semibold text-red-300 transition duration-300 hover:-translate-y-1 hover:bg-red-500/20">Batalkan Order</button>
                    </form>
                @endif
            </div>

            @if($order->payment_proof)
            <div class="mt-6 rounded-3xl border border border-white/5 bg-white/[0.03] p-6">
                <h2 class="text-lg font-semibold text-white">Bukti Pembayaran</h2>
                <div class="mt-4">
                    <a href="{{ Storage::disk('public')->url($order->payment_proof) }}" target="_blank" rel="noopener noreferrer">
                        <img src="{{ Storage::disk('public')->url($order->payment_proof) }}" alt="Bukti Pembayaran" class="w-full rounded-3xl border border-slate-800 object-cover max-h-96" />
                    </a>
                </div>
            </div>
            @elseif($order->status === \App\Models\Order::STATUS_PENDING_PAYMENT)
            <div class="mt-6 rounded-3xl border border border-white/5 bg-white/[0.03] p-6">
                <h2 class="text-lg font-semibold text-white">Lanjutkan Pembayaran</h2>
                <p class="mt-2 text-slate-400">Metode pembayaran: <span class="font-semibold text-white">{{ ucfirst(str_replace('_', ' ', $order->payment_method ?? 'Belum dipilih')) }}</span></p>

                @if($order->payment_method === 'balance')
                    <form action="{{ route('orders.pay', $order) }}" method="POST" class="mt-4">
                        @csrf
                        <input type="hidden" name="payment_method" value="balance">
                        <button type="submit" class="w-full rounded-[22px] border border-emerald-500/20 bg-emerald-500/10 px-5 py-3 font-semibold text-emerald-300 transition duration-300 hover:-translate-y-1 hover:bg-emerald-500/20">Bayar dengan Saldo</button>
                    </form>
                @else
                    <form action="{{ route('orders.proof', $order) }}" method="POST" enctype="multipart/form-data" class="mt-4 space-y-4">
                        @csrf
                        <label class="block text-sm text-slate-300">
                            Unggah bukti pembayaran (gambar)
                            <input type="file" name="payment_proof" accept="image/*" class="mt-2 w-full rounded-[20px] border border-white/10 bg-white/[0.03] px-4 py-3 text-slate-100 file:mr-4 file:rounded-xl file:border-0 file:bg-blue-500/20 file:px-4 file:py-2 file:text-blue-300" required>
                        </label>
                        <button type="submit" class="w-full rounded-[22px] border border-emerald-500/20 bg-emerald-500/10 px-5 py-3 font-semibold text-emerald-300 transition duration-300 hover:-translate-y-1 hover:bg-emerald-500/20">Kirim Bukti Pembayaran</button>
                    </form>
                @endif
            </div>
            @endif
        </div>
    </div>
</div>
@endsection

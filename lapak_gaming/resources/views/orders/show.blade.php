@extends('layouts.app')

@section('title', 'Detail Transaksi')

@section('content')
<div class="min-h-screen bg-slate-950 py-12 px-4">
    <div class="mx-auto max-w-5xl space-y-6">
        <div class="rounded-3xl border border-slate-800 bg-slate-900 p-8">
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
                <div class="rounded-3xl border border-slate-800 bg-slate-950 p-5">
                    <h2 class="text-lg font-semibold text-white">Buyer</h2>
                    <p class="mt-2 text-slate-400">{{ $order->buyer->name ?? '-' }}</p>
                    <p class="text-slate-500">{{ $order->buyer->email ?? '-' }}</p>
                </div>
                <div class="rounded-3xl border border-slate-800 bg-slate-950 p-5">
                    <h2 class="text-lg font-semibold text-white">Seller</h2>
                    <p class="mt-2 text-slate-400">{{ $order->seller->name ?? '-' }}</p>
                    <p class="text-slate-500">{{ $order->seller->email ?? '-' }}</p>
                </div>
            </div>

            <div class="mt-8 overflow-hidden rounded-3xl border border-slate-800 bg-slate-950">
                <div class="bg-slate-900 px-6 py-4 text-sm uppercase tracking-[0.2em] text-slate-400">Item Pesanan</div>
                <div class="divide-y divide-slate-800">
                    @foreach($order->items as $item)
                        @php
                            $itemName = $item->name_snapshot ?? $item->product?->name ?? 'Produk';
                            $itemSubtotal = (float) $item->price_snapshot * (int) $item->quantity;
                        @endphp
                        <div class="flex flex-col gap-3 px-6 py-5 md:flex-row md:items-center md:justify-between">
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
                <div class="rounded-3xl border border-slate-800 bg-slate-950 p-5">
                    <div class="text-slate-400">Metode Pembayaran</div>
                    <div class="mt-2 text-white">{{ $order->payment_method ?? 'Belum dipilih' }}</div>
                </div>
                <div class="rounded-3xl border border-slate-800 bg-slate-950 p-5">
                    <div class="text-slate-400">Tanggal Pesanan</div>
                    <div class="mt-2 text-white">{{ $order->created_at->translatedFormat('d F Y H:i') }}</div>
                </div>
            </div>

            <div class="mt-6 flex flex-col gap-3 sm:flex-row sm:items-center">
                <a href="{{ route('chat.index', $order) }}" class="rounded-2xl bg-sky-500 px-5 py-3 text-white hover:bg-sky-400">Chat dengan Seller</a>
                @if(in_array($order->status, [\App\Models\Order::STATUS_PENDING_PAYMENT, \App\Models\Order::STATUS_PAYMENT_UPLOADED], true))
                    <form action="{{ route('orders.cancel', $order) }}" method="POST">
                        @csrf
                        <button type="submit" class="rounded-2xl bg-red-600 px-5 py-3 text-white hover:bg-red-500">Batalkan Order</button>
                    </form>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

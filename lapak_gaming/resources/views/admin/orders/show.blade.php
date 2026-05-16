@extends('layouts.app')

@section('title', 'Detail Transaksi')

@section('content')
<div class="min-h-screen bg-slate-950 py-12 px-4">
    <div class="mx-auto max-w-5xl rounded-3xl border border-slate-800 bg-slate-900 p-8">
        <div class="flex items-center justify-between gap-4">
            <div>
                <h1 class="text-3xl font-bold text-white">{{ $order->invoice_number }}</h1>
                <p class="mt-2 text-slate-400">Detail transaksi dan status pembayaran.</p>
            </div>
            <a href="{{ route('admin.orders.index') }}" class="rounded-2xl bg-slate-800 px-4 py-3 text-sm text-slate-300 hover:bg-slate-700">Kembali</a>
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
                    <div class="flex flex-col gap-3 px-6 py-5 md:flex-row md:items-center md:justify-between">
                        <div>
                            <div class="font-semibold text-white">{{ $item->product_name }}</div>
                            <div class="text-slate-400">Jumlah: {{ $item->quantity }}</div>
                        </div>
                        <div class="text-right text-white">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</div>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="mt-6 rounded-3xl border border-slate-800 bg-slate-950 p-6">
            <div class="grid gap-4 md:grid-cols-2">
                <div>
                    <div class="text-slate-400">Grand Total</div>
                    <div class="mt-2 text-2xl font-bold text-white">Rp {{ number_format($order->grand_total, 0, ',', '.') }}</div>
                </div>
                <div>
                    <div class="text-slate-400">Status</div>
                    <div class="mt-2 text-white">{{ $order->status_label }}</div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

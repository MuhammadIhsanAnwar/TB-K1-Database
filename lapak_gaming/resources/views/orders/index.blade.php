@extends('layouts.app')

@section('title', 'Daftar Transaksi')

@section('content')
<div class="min-h-screen bg-slate-950 py-12 px-4">
    <div class="mx-auto max-w-6xl space-y-6">
        <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
            <div>
                <h1 class="text-3xl font-bold text-white">Riwayat Transaksi</h1>
                <p class="mt-2 text-slate-400">Lihat semua order yang sudah Anda buat sebagai buyer.</p>
            </div>
        </div>

        <div class="grid gap-4">
            @forelse($orders as $order)
                <a href="{{ route('orders.show', $order->order_code) }}" class="block rounded-3xl border border-slate-800 bg-slate-900 p-6 hover:border-amber-500 transition">
                    <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                        <div>
                            <h2 class="text-xl font-semibold text-white">{{ $order->invoice_number }}</h2>
                            <p class="mt-2 text-slate-400">{{ $order->created_at->translatedFormat('d F Y') }}</p>
                        </div>
                        <div class="space-y-2 text-right">
                            <div class="text-slate-300">Total</div>
                            <div class="text-xl font-bold text-white">Rp {{ number_format($order->grand_total, 0, ',', '.') }}</div>
                        </div>
                    </div>
                    <div class="mt-4 flex flex-wrap items-center gap-3 text-sm text-slate-400">
                        <span class="rounded-full border border-slate-700 px-3 py-1">{{ $order->status_label }}</span>
                        <span class="rounded-full border border-slate-700 px-3 py-1">Seller: {{ $order->seller?->name ?? '-' }}</span>
                    </div>
                </a>
            @empty
                <div class="rounded-3xl border border-slate-800 bg-slate-900 p-6 text-slate-400">Belum ada transaksi.</div>
            @endforelse
        </div>

        <div>{{ $orders->links() }}</div>
    </div>
</div>
@endsection

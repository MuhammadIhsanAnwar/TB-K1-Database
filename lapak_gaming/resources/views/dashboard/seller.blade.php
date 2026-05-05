@extends('layouts.app')

@section('title', 'Seller Dashboard')

@section('content')
    <div class="grid gap-6 lg:grid-cols-3">
        <div class="rounded-4xl border border-slate-200 bg-white p-6 dark:border-slate-800 dark:bg-slate-900">
            <div class="text-sm font-semibold text-slate-500">Saldo Seller</div>
            <div class="mt-2 text-3xl font-black">Rp {{ number_format($wallet?->balance ?? 0, 0, ',', '.') }}</div>
        </div>
        <div class="rounded-4xl border border-slate-200 bg-white p-6 dark:border-slate-800 dark:bg-slate-900">
            <div class="text-sm font-semibold text-slate-500">Produk Aktif</div>
            <div class="mt-2 text-3xl font-black">{{ count($products) }}</div>
        </div>
        <div class="rounded-4xl border border-slate-200 bg-white p-6 dark:border-slate-800 dark:bg-slate-900">
            <div class="text-sm font-semibold text-slate-500">Order Masuk</div>
            <div class="mt-2 text-3xl font-black">{{ count($orders) }}</div>
        </div>
    </div>

    <div class="mt-8 grid gap-6 lg:grid-cols-2">
        <section class="rounded-4xl border border-slate-200 bg-white p-6 dark:border-slate-800 dark:bg-slate-900">
            <h2 class="text-xl font-black">My Products</h2>
            <div class="mt-4 space-y-3">
                @forelse ($products as $product)
                    <div class="rounded-2xl bg-slate-50 p-4 dark:bg-slate-950/40">
                        <div class="flex items-center justify-between">
                            <div class="font-semibold">{{ $product->name }}</div>
                            <div class="text-sm text-slate-500">Rp {{ number_format($product->price, 0, ',', '.') }}</div>
                        </div>
                        <div class="mt-1 text-xs uppercase tracking-[0.2em] text-slate-400">{{ $product->status_label }}</div>
                    </div>
                @empty
                    <p class="text-sm text-slate-500">Belum ada produk.</p>
                @endforelse
            </div>
        </section>
        <section class="rounded-4xl border border-slate-200 bg-white p-6 dark:border-slate-800 dark:bg-slate-900">
            <h2 class="text-xl font-black">Recent Orders</h2>
            <div class="mt-4 space-y-3">
                @forelse ($orders as $order)
                    <div class="rounded-2xl bg-slate-50 p-4 dark:bg-slate-950/40">
                        <div class="flex items-center justify-between text-sm">
                            <span class="font-bold">{{ $order->invoice_number }}</span>
                            <span>{{ $order->status_label }}</span>
                        </div>
                    </div>
                @empty
                    <p class="text-sm text-slate-500">Belum ada order.</p>
                @endforelse
            </div>
        </section>
    </div>
@endsection
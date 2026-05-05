@extends('layouts.app')

@section('title', 'Buyer Dashboard')

@section('content')
    <div class="grid gap-6 lg:grid-cols-3">
        <div class="rounded-4xl border border-slate-200 bg-white p-6 dark:border-slate-800 dark:bg-slate-900">
            <div class="text-sm font-semibold text-slate-500">Saldo Wallet</div>
            <div class="mt-2 text-3xl font-black">Rp {{ number_format($wallet?->balance ?? 0, 0, ',', '.') }}</div>
            <div class="mt-1 text-sm text-slate-500">Available: Rp {{ number_format($wallet?->available_balance ?? 0, 0, ',', '.') }}</div>
        </div>
        <div class="rounded-4xl border border-slate-200 bg-white p-6 dark:border-slate-800 dark:bg-slate-900">
            <div class="text-sm font-semibold text-slate-500">Pesanan Terbaru</div>
            <div class="mt-2 text-3xl font-black">{{ $orders->count() }}</div>
            <div class="mt-1 text-sm text-slate-500">Riwayat checkout dan escrow</div>
        </div>
        <div class="rounded-4xl border border-slate-200 bg-white p-6 dark:border-slate-800 dark:bg-slate-900">
            <div class="text-sm font-semibold text-slate-500">Notifikasi</div>
            <div class="mt-2 text-3xl font-black">{{ $notifications->count() }}</div>
            <div class="mt-1 text-sm text-slate-500">Status invoice, chat, dan review</div>
        </div>
    </div>

    <div class="mt-8 grid gap-6 lg:grid-cols-2">
        <section class="rounded-4xl border border-slate-200 bg-white p-6 dark:border-slate-800 dark:bg-slate-900">
            <h2 class="text-xl font-black">Recent Orders</h2>
            <div class="mt-4 space-y-3">
                @forelse ($orders as $order)
                    <div class="rounded-2xl bg-slate-50 p-4 dark:bg-slate-950/40">
                        <div class="flex items-center justify-between text-sm">
                            <span class="font-bold">{{ $order->invoice_number }}</span>
                            <span class="rounded-full bg-slate-200 px-3 py-1 text-xs font-bold text-slate-700 dark:bg-slate-800 dark:text-slate-200">{{ $order->status_label }}</span>
                        </div>
                        <div class="mt-2 text-sm text-slate-500">Total Rp {{ number_format($order->grand_total, 0, ',', '.') }}</div>
                    </div>
                @empty
                    <p class="text-sm text-slate-500">Belum ada order.</p>
                @endforelse
            </div>
        </section>
        <section class="rounded-4xl border border-slate-200 bg-white p-6 dark:border-slate-800 dark:bg-slate-900">
            <h2 class="text-xl font-black">Notifications</h2>
            <div class="mt-4 space-y-3">
                @forelse ($notifications as $notification)
                    <div class="rounded-2xl bg-slate-50 p-4 dark:bg-slate-950/40">
                        <div class="font-semibold">{{ $notification->title }}</div>
                        <div class="mt-1 text-sm text-slate-500">{{ $notification->body }}</div>
                    </div>
                @empty
                    <p class="text-sm text-slate-500">Belum ada notifikasi.</p>
                @endforelse
            </div>
        </section>
    </div>
@endsection
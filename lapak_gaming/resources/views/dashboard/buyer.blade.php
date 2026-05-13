@extends('layouts.app')

@section('title', 'Buyer Dashboard')

@section('content')
<div class="mx-auto max-w-6xl space-y-7">

    {{-- HEADER --}}
    <div
        class="relative overflow-hidden rounded-[28px] border border-blue-500/20 bg-gradient-to-br from-[#060816] via-[#091225] to-[#0B1730] px-7 py-8 shadow-2xl">

        <div
            class="absolute inset-0 bg-[radial-gradient(circle_at_top_right,rgba(37,99,235,0.18),transparent_35%)]">
        </div>

        <div class="relative z-10 flex flex-col gap-6 lg:flex-row lg:items-center lg:justify-between">

            <div class="max-w-2xl">
                <div
                    class="inline-flex items-center gap-2 rounded-full border border-blue-500/30 bg-blue-500/10 px-4 py-2 text-[11px] font-bold tracking-wider text-blue-300">
                    <span class="h-2 w-2 rounded-full bg-emerald-400"></span>
                    BUYER DASHBOARD
                </div>

                <h1
                    class="mt-4 text-3xl font-black leading-tight text-white md:text-4xl">
                    Selamat Datang
                </h1>

                <p class="mt-3 max-w-xl text-sm leading-relaxed text-slate-300">
                    Kelola transaksi, cek pesanan terbaru, dan pantau notifikasi akunmu dengan cepat.
                </p>
            </div>

            <div
                class="hidden lg:flex h-[120px] w-[120px] items-center justify-center rounded-full border border-blue-500/20 bg-blue-500/5 backdrop-blur-xl">
                <img src="{{ asset('storage/app/public/logo/logo.png') }}"
                    alt="Logo"
                    class="h-16 w-16 object-contain opacity-95">
            </div>

        </div>
    </div>

    {{-- STATS --}}
    <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-3">

        {{-- WALLET --}}
        <div
            class="group relative overflow-hidden rounded-[24px] border border-blue-500/20 bg-[#0B1220]/95 p-5 transition duration-300 hover:-translate-y-1 hover:border-blue-400/40">

            <div
                class="absolute right-0 top-0 h-24 w-24 rounded-full bg-blue-500/10 blur-3xl">
            </div>

            <div class="relative z-10">
                <div class="text-xs font-semibold uppercase tracking-wider text-blue-300">
                    Saldo Wallet
                </div>

                <div class="mt-3 text-3xl font-black text-white">
                    Rp {{ number_format($wallet?->balance ?? 0, 0, ',', '.') }}
                </div>

                <div class="mt-2 text-xs text-slate-400">
                    Available:
                    Rp {{ number_format($wallet?->available_balance ?? 0, 0, ',', '.') }}
                </div>
            </div>
        </div>

        {{-- ORDERS --}}
        <div
            class="group relative overflow-hidden rounded-[24px] border border-orange-500/20 bg-[#0B1220]/95 p-5 transition duration-300 hover:-translate-y-1 hover:border-orange-400/40">

            <div
                class="absolute right-0 top-0 h-24 w-24 rounded-full bg-orange-500/10 blur-3xl">
            </div>

            <div class="relative z-10">
                <div class="text-xs font-semibold uppercase tracking-wider text-orange-300">
                    Pesanan
                </div>

                <div class="mt-3 text-3xl font-black text-white">
                    {{ $orders->count() }}
                </div>

                <div class="mt-2 text-xs text-slate-400">
                    Riwayat checkout & escrow
                </div>
            </div>
        </div>

        {{-- NOTIFICATION --}}
        <div
            class="group relative overflow-hidden rounded-[24px] border border-emerald-500/20 bg-[#0B1220]/95 p-5 transition duration-300 hover:-translate-y-1 hover:border-emerald-400/40">

            <div
                class="absolute right-0 top-0 h-24 w-24 rounded-full bg-emerald-500/10 blur-3xl">
            </div>

            <div class="relative z-10">
                <div class="text-xs font-semibold uppercase tracking-wider text-emerald-300">
                    Notifikasi
                </div>

                <div class="mt-3 text-3xl font-black text-white">
                    {{ $notifications->count() }}
                </div>

                <div class="mt-2 text-xs text-slate-400">
                    Update invoice & review
                </div>
            </div>
        </div>
    </div>

    {{-- CONTENT --}}
    <div class="mt-6 grid gap-5 lg:grid-cols-3 max-w-6xl mx-auto">

        {{-- RECENT ORDERS --}}
        <section
            class="rounded-[28px] border border-blue-500/20 bg-[#0B1220]/95 p-6">

            <div class="flex items-center justify-between">
                <h2 class="text-xl font-black text-white">
                    Recent Orders
                </h2>

                <div
                    class="rounded-full border border-blue-500/20 bg-blue-500/10 px-3 py-1 text-[11px] font-bold text-blue-300">
                    {{ $orders->count() }} Orders
                </div>
            </div>

            <div class="mt-5 space-y-3">

                @forelse ($orders as $order)
                    <div
                        class="rounded-2xl border border-white/5 bg-white/[0.03] p-4 transition duration-300 hover:border-blue-500/30 hover:bg-blue-500/[0.04]">

                        <div class="flex items-center justify-between">
                            <span class="text-sm font-bold text-white">
                                {{ $order->invoice_number }}
                            </span>

                            <span
                                class="rounded-full border border-blue-500/20 bg-blue-500/10 px-3 py-1 text-[10px] font-bold text-blue-300">
                                {{ $order->status_label }}
                            </span>
                        </div>

                        <div class="mt-2 text-xs text-slate-400">
                            Total Rp
                            {{ number_format($order->grand_total, 0, ',', '.') }}
                        </div>
                    </div>
                @empty
                    <div
                        class="rounded-2xl border border-dashed border-white/10 py-8 text-center text-sm text-slate-500">
                        Belum ada order.
                    </div>
                @endforelse

            </div>
        </section>

        {{-- NOTIFICATIONS --}}
        <section
            class="rounded-[28px] border border-orange-500/20 bg-[#0B1220]/95 p-6">

            <div class="flex items-center justify-between">
                <h2 class="text-xl font-black text-white">
                    Notifications
                </h2>

                <div
                    class="rounded-full border border-orange-500/20 bg-orange-500/10 px-3 py-1 text-[11px] font-bold text-orange-300">
                    {{ $notifications->count() }} Alerts
                </div>
            </div>

            <div class="mt-5 space-y-3">

                @forelse ($notifications as $notification)
                    <div
                        class="rounded-2xl border border-white/5 bg-white/[0.03] p-4 transition duration-300 hover:border-orange-500/30 hover:bg-orange-500/[0.04]">

                        <div class="text-sm font-bold text-white">
                            {{ $notification->title }}
                        </div>

                        <div class="mt-2 text-xs leading-relaxed text-slate-400">
                            {{ $notification->body }}
                        </div>
                    </div>
                @empty
                    <div
                        class="rounded-2xl border border-dashed border-white/10 py-8 text-center text-sm text-slate-500">
                        Belum ada notifikasi.
                    </div>
                @endforelse

            </div>
        </section>

    </div>
</div>
@endsection
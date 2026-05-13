@extends('layouts.app')

@section('title', 'Buyer Dashboard')

@section('content')
<div class="space-y-8">

    {{-- HEADER --}}
    <div
        class="relative overflow-hidden rounded-[32px] border border-blue-500/20 bg-gradient-to-br from-[#060816] via-[#091225] to-[#0B1730] p-8 shadow-2xl">

        <div
            class="absolute inset-0 bg-[radial-gradient(circle_at_top_right,rgba(37,99,235,0.18),transparent_35%)]">
        </div>

        <div class="relative z-10 flex flex-col gap-6 lg:flex-row lg:items-center lg:justify-between">

            <div>
                <div
                    class="inline-flex items-center gap-2 rounded-full border border-blue-500/30 bg-blue-500/10 px-4 py-2 text-xs font-bold tracking-wide text-blue-300">
                    <span class="h-2 w-2 rounded-full bg-emerald-400"></span>
                    BUYER DASHBOARD
                </div>

                <h1
                    class="mt-5 text-4xl font-black leading-tight text-white md:text-5xl">
                    Selamat Datang
                </h1>

                <p class="mt-4 max-w-2xl text-base leading-relaxed text-slate-300">
                    Kelola transaksi, pantau pesanan terbaru, cek notifikasi,
                    dan nikmati pengalaman marketplace gaming yang cepat,
                    aman, dan modern.
                </p>
            </div>

            <div
                class="hidden lg:flex h-[180px] w-[180px] items-center justify-center rounded-full border border-blue-500/20 bg-blue-500/5 backdrop-blur-xl">
                <img src="{{ asset('storage/app/public/logo/logo.png') }}"
                    alt="Logo"
                    class="h-28 w-28 object-contain opacity-95">
            </div>

            </div>
        </div>
    </div>

    {{-- STATS --}}
    <div class="grid gap-6 lg:grid-cols-3">

        {{-- WALLET --}}
        <div
            class="group relative overflow-hidden rounded-[30px] border border-blue-500/20 bg-[#0B1220]/95 p-6 transition duration-300 hover:-translate-y-1 hover:border-blue-400/40">

            <div
                class="absolute right-0 top-0 h-32 w-32 rounded-full bg-blue-500/10 blur-3xl transition duration-300 group-hover:bg-blue-500/20">
            </div>

            <div class="relative z-10">
                <div class="text-sm font-semibold uppercase tracking-wide text-blue-300">
                    Saldo Wallet
                </div>

                <div class="mt-4 text-4xl font-black text-white">
                    Rp {{ number_format($wallet?->balance ?? 0, 0, ',', '.') }}
                </div>

                <div class="mt-2 text-sm text-slate-400">
                    Available:
                    Rp {{ number_format($wallet?->available_balance ?? 0, 0, ',', '.') }}
                </div>
            </div>
        </div>

        {{-- ORDERS --}}
        <div
            class="group relative overflow-hidden rounded-[30px] border border-orange-500/20 bg-[#0B1220]/95 p-6 transition duration-300 hover:-translate-y-1 hover:border-orange-400/40">

            <div
                class="absolute right-0 top-0 h-32 w-32 rounded-full bg-orange-500/10 blur-3xl transition duration-300 group-hover:bg-orange-500/20">
            </div>

            <div class="relative z-10">
                <div class="text-sm font-semibold uppercase tracking-wide text-orange-300">
                    Pesanan Terbaru
                </div>

                <div class="mt-4 text-4xl font-black text-white">
                    {{ $orders->count() }}
                </div>

                <div class="mt-2 text-sm text-slate-400">
                    Riwayat checkout dan escrow
                </div>
            </div>
        </div>

        {{-- NOTIFICATION --}}
        <div
            class="group relative overflow-hidden rounded-[30px] border border-emerald-500/20 bg-[#0B1220]/95 p-6 transition duration-300 hover:-translate-y-1 hover:border-emerald-400/40">

            <div
                class="absolute right-0 top-0 h-32 w-32 rounded-full bg-emerald-500/10 blur-3xl transition duration-300 group-hover:bg-emerald-500/20">
            </div>

            <div class="relative z-10">
                <div class="text-sm font-semibold uppercase tracking-wide text-emerald-300">
                    Notifikasi
                </div>

                <div class="mt-4 text-4xl font-black text-white">
                    {{ $notifications->count() }}
                </div>

                <div class="mt-2 text-sm text-slate-400">
                    Status invoice, chat, dan review
                </div>
            </div>
        </div>
    </div>

    {{-- CONTENT --}}
    <div class="grid gap-6 lg:grid-cols-2">

        {{-- RECENT ORDERS --}}
        <section
            class="rounded-[32px] border border-blue-500/20 bg-[#0B1220]/95 p-7">

            <div class="flex items-center justify-between">
                <h2 class="text-2xl font-black text-white">
                    Recent Orders
                </h2>

                <div
                    class="rounded-full border border-blue-500/20 bg-blue-500/10 px-4 py-2 text-xs font-bold text-blue-300">
                    {{ $orders->count() }} Orders
                </div>
            </div>

            <div class="mt-6 space-y-4">

                @forelse ($orders as $order)
                    <div
                        class="rounded-3xl border border-white/5 bg-white/[0.03] p-5 transition duration-300 hover:border-blue-500/30 hover:bg-blue-500/[0.04]">

                        <div class="flex items-center justify-between">
                            <span class="font-bold text-white">
                                {{ $order->invoice_number }}
                            </span>

                            <span
                                class="rounded-full border border-blue-500/20 bg-blue-500/10 px-3 py-1 text-xs font-bold text-blue-300">
                                {{ $order->status_label }}
                            </span>
                        </div>

                        <div class="mt-3 text-sm text-slate-400">
                            Total Rp
                            {{ number_format($order->grand_total, 0, ',', '.') }}
                        </div>
                    </div>
                @empty
                    <div
                        class="rounded-3xl border border-dashed border-white/10 py-10 text-center text-sm text-slate-500">
                        Belum ada order.
                    </div>
                @endforelse

            </div>
        </section>

        {{-- NOTIFICATIONS --}}
        <section
            class="rounded-[32px] border border-orange-500/20 bg-[#0B1220]/95 p-7">

            <div class="flex items-center justify-between">
                <h2 class="text-2xl font-black text-white">
                    Notifications
                </h2>

                <div
                    class="rounded-full border border-orange-500/20 bg-orange-500/10 px-4 py-2 text-xs font-bold text-orange-300">
                    {{ $notifications->count() }} Alerts
                </div>
            </div>

            <div class="mt-6 space-y-4">

                @forelse ($notifications as $notification)
                    <div
                        class="rounded-3xl border border-white/5 bg-white/[0.03] p-5 transition duration-300 hover:border-orange-500/30 hover:bg-orange-500/[0.04]">

                        <div class="font-bold text-white">
                            {{ $notification->title }}
                        </div>

                        <div class="mt-2 text-sm leading-relaxed text-slate-400">
                            {{ $notification->body }}
                        </div>
                    </div>
                @empty
                    <div
                        class="rounded-3xl border border-dashed border-white/10 py-10 text-center text-sm text-slate-500">
                        Belum ada notifikasi.
                    </div>
                @endforelse

            </div>
        </section>

    </div>
</div>
@endsection
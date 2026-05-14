@extends('layouts.app')

@section('title', 'Buyer Dashboard')

@section('content')
<div class="mx-auto mt-10 max-w-5xl space-y-7 px-4 md:px-6">

    {{-- CUSTOM ANIMATION --}}
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

        .reveal-delay-3 {
            animation-delay: .45s;
        }

        @keyframes revealUp {
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>

    {{-- HEADER --}}
    <div
        class="reveal relative overflow-hidden rounded-[30px] border border-blue-500/20 bg-gradient-to-br from-[#060816] via-[#091225] to-[#0B1730] px-7 py-8 shadow-[0_0_80px_rgba(37,99,235,0.12)]">

        <div
            class="absolute inset-0 bg-[radial-gradient(circle_at_top_right,rgba(37,99,235,0.18),transparent_35%)]">
        </div>

        <div class="relative z-10 flex flex-col gap-7 lg:flex-row lg:items-center lg:justify-between">

            <div class="max-w-2xl">
                <div
                    class="inline-flex items-center gap-2 rounded-full border border-blue-500/30 bg-blue-500/10 px-4 py-2 text-[11px] font-bold tracking-[0.2em] text-blue-300 backdrop-blur-xl">
                    <span class="h-2 w-2 rounded-full bg-emerald-400 animate-pulse"></span>
                    BUYER DASHBOARD
                </div>

                <h1
                    class="mt-5 text-3xl font-black leading-tight text-white md:text-5xl">
                    Selamat Datang
                </h1>

                <p class="mt-4 max-w-xl text-sm leading-relaxed text-slate-300 md:text-[15px]">
                    Kelola transaksi, cek pesanan terbaru, dan pantau notifikasi akunmu dengan tampilan modern dan cepat.
                </p>
            </div>

            {{-- LOGO --}}
            <div
                class="hidden lg:flex h-[180px] w-[180px] items-center justify-center rounded-full border border-blue-500/20 bg-blue-500/5 backdrop-blur-2xl">

                <div
                    class="absolute h-[220px] w-[220px] rounded-full bg-blue-500/10 blur-3xl">
                </div>

                <img src="{{ asset('storage/app/public/logo/logo.png') }}"
                    alt="Logo"
                    class="relative z-10 h-32 w-32 object-contain drop-shadow-[0_0_25px_rgba(59,130,246,0.55)]">
            </div>

        </div>
    </div>

    {{-- PENDING SELLER STATUS --}}
    @if($user->seller_status === 'pending')
    <div class="reveal rounded-[26px] border border-yellow-700/50 bg-yellow-900/20 p-6 shadow-lg">
        <div class="flex items-start gap-4">
            <div class="pt-1">
                <svg class="w-6 h-6 text-yellow-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div class="flex-1">
                <h3 class="text-lg font-bold text-yellow-200">Pengajuan Seller Anda Sedang Diproses</h3>
                <p class="text-sm text-yellow-300 mt-1">Tim kami sedang meninjau pendaftaran toko Anda. Anda akan menerima notifikasi saat status berubah. Terima kasih atas kesabaran Anda!</p>
            </div>
        </div>
    </div>
    @elseif($user->seller_status === 'rejected')
    <div class="reveal rounded-[26px] border border-red-700/50 bg-red-900/20 p-6 shadow-lg">
        <div class="flex items-start gap-4">
            <div class="pt-1">
                <svg class="w-6 h-6 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </div>
            <div class="flex-1">
                <h3 class="text-lg font-bold text-red-200">Pengajuan Seller Ditolak</h3>
                <p class="text-sm text-red-300 mt-1">{{ $user->seller_rejection_reason ?? 'Alasan penolakan tidak tersedia.' }}</p>
                <a href="{{ route('seller.register.form') }}" class="text-sm text-red-400 hover:text-red-300 mt-2 inline-block underline">Ajukan Kembali →</a>
            </div>
        </div>
    </div>
    @endif

    {{-- STATS --}}
    <div class="grid gap-5 md:grid-cols-2 xl:grid-cols-3">

        {{-- WALLET --}}
        <div
            class="reveal reveal-delay-1 group relative overflow-hidden rounded-[26px] border border-blue-500/20 bg-[#0B1220]/95 p-5 transition duration-300 hover:-translate-y-1.5 hover:border-blue-400/40 hover:shadow-[0_0_30px_rgba(59,130,246,0.12)]">

            <div
                class="absolute right-0 top-0 h-28 w-28 rounded-full bg-blue-500/10 blur-3xl">
            </div>

            <div class="relative z-10">
                <div class="text-xs font-semibold uppercase tracking-[0.18em] text-blue-300">
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
            class="reveal reveal-delay-2 group relative overflow-hidden rounded-[26px] border border-orange-500/20 bg-[#0B1220]/95 p-5 transition duration-300 hover:-translate-y-1.5 hover:border-orange-400/40 hover:shadow-[0_0_30px_rgba(249,115,22,0.12)]">

            <div
                class="absolute right-0 top-0 h-28 w-28 rounded-full bg-orange-500/10 blur-3xl">
            </div>

            <div class="relative z-10">
                <div class="text-xs font-semibold uppercase tracking-[0.18em] text-orange-300">
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

        {{-- NOTIFICATIONS --}}
        <div
            class="reveal reveal-delay-3 group relative overflow-hidden rounded-[26px] border border-emerald-500/20 bg-[#0B1220]/95 p-5 transition duration-300 hover:-translate-y-1.5 hover:border-emerald-400/40 hover:shadow-[0_0_30px_rgba(16,185,129,0.12)]">

            <div
                class="absolute right-0 top-0 h-28 w-28 rounded-full bg-emerald-500/10 blur-3xl">
            </div>

            <div class="relative z-10">
                <div class="text-xs font-semibold uppercase tracking-[0.18em] text-emerald-300">
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
    <div class="grid gap-5 lg:grid-cols-2">

        {{-- RECENT ORDERS --}}
        <section
            class="reveal reveal-delay-2 rounded-[30px] border border-blue-500/20 bg-[#0B1220]/95 p-6 shadow-[0_0_50px_rgba(37,99,235,0.05)]">

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
                        class="rounded-2xl border border-white/5 bg-white/[0.03] p-4 transition duration-300 hover:border-blue-500/30 hover:bg-blue-500/[0.04] hover:-translate-y-1">

                        <div class="flex items-center justify-between gap-3">
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
            class="reveal reveal-delay-3 rounded-[30px] border border-orange-500/20 bg-[#0B1220]/95 p-6 shadow-[0_0_50px_rgba(249,115,22,0.05)]">

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
                        class="rounded-2xl border border-white/5 bg-white/[0.03] p-4 transition duration-300 hover:border-orange-500/30 hover:bg-orange-500/[0.04] hover:-translate-y-1">

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
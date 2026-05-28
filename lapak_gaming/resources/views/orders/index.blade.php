@extends('layouts.app')

@section('title', 'Daftar Transaksi')

@section('content')
<div class="relative px-4 pt-28 pb-16 overflow-hidden"> {{-- Ganti overflow-x-hidden jadi overflow-hidden --}}

    {{-- BACKGROUND EFFECT --}}
    <div class="pointer-events-none absolute inset-0 -z-10 overflow-hidden"> {{-- Tambahkan overflow-hidden --}}
        <div class="absolute top-[-120px] right-[-120px] h-[320px] w-[320px] rounded-full bg-blue-500/10 blur-3xl"></div>
        <div class="absolute bottom-[-140px] left-[-120px] h-[320px] w-[320px] rounded-full bg-orange-500/10 blur-3xl"></div>
    </div>

    <div class="relative z-10 mx-auto max-w-5xl">

        {{-- HEADER --}}
        <div
            class="reveal-up relative overflow-hidden rounded-[30px] border border-blue-500/20 bg-gradient-to-br from-[#091225] via-[#0B1730] to-[#0A1120] px-7 py-8 shadow-[0_0_40px_rgba(37,99,235,0.12)]">

            <div
                class="absolute inset-0 bg-[radial-gradient(circle_at_top_right,rgba(37,99,235,0.18),transparent_35%)]">
            </div>

            <div class="relative z-10 flex flex-col gap-5 md:flex-row md:items-center md:justify-between">

                <div>
                    <div
                        class="inline-flex items-center gap-2 rounded-full border border-blue-500/30 bg-blue-500/10 px-4 py-2 text-[11px] font-bold tracking-[0.2em] text-blue-300">
                        <span class="h-2 w-2 rounded-full bg-emerald-400 animate-pulse"></span>
                        TRANSACTION HISTORY
                    </div>

                    <h1 class="mt-4 text-3xl font-black text-white md:text-5xl leading-tight">
                        Riwayat Transaksi
                    </h1>

                    <p class="mt-3 max-w-2xl text-sm leading-relaxed text-slate-300 md:text-[15px]">
                        Lihat seluruh riwayat transaksi, status order, serta detail seller dari akun buyer Anda.
                    </p>
                </div>

                <div
                    class="hidden md:flex h-[140px] w-[140px] items-center justify-center rounded-full border border-blue-500/20 bg-blue-500/10 backdrop-blur-xl shadow-[0_0_40px_rgba(37,99,235,0.15)]">

                    <img src="{{ asset('storage/app/public/logo/logo.png') }}"
                        alt="Logo"
                        class="h-28 w-28 object-contain opacity-95">
                </div>

            </div>
        </div>

        {{-- STATUS FILTERS --}}
        @php
            $statusTabs = [
                'all' => 'Semua',
                \App\Models\Order::STATUS_PENDING_PAYMENT => 'Menunggu Pembayaran',
                \App\Models\Order::STATUS_PAYMENT_UPLOADED => 'Pembayaran Dikirim',
                \App\Models\Order::STATUS_PROCESSING => 'Diproses',
                \App\Models\Order::STATUS_COMPLETED => 'Selesai',
                \App\Models\Order::STATUS_CANCELLED => 'Dibatalkan',
            ];
        @endphp

        <div class="mt-8 flex flex-wrap gap-3 rounded-[26px] border border-white/5 bg-[#0B1220]/90 p-4 backdrop-blur-xl overflow-x-auto"> {{-- Tambahkan overflow-x-auto untuk filter tab --}}
            @foreach($statusTabs as $tabKey => $tabLabel)
                <a href="{{ route('orders.index', ['status' => $tabKey]) }}"
                   class="inline-flex items-center gap-2 rounded-2xl border px-4 py-3 text-sm font-bold transition whitespace-nowrap {{ $status === $tabKey ? 'border-blue-500/30 bg-blue-500/15 text-blue-200' : 'border-white/10 surface-weak text-slate-300 hover:border-blue-500/20 hover:bg-blue-500/[0.08] hover:text-white' }}">
                    <span>{{ $tabLabel }}</span>
                    @if($tabKey !== 'all')
                        <span class="rounded-full bg-black/20 px-2 py-0.5 text-[11px]">{{ $statusCounts[$tabKey] ?? 0 }}</span>
                    @endif
                </a>
            @endforeach
        </div>

        {{-- TRANSACTION LIST --}}
        <div class="mt-8 space-y-5">

            @forelse($orders as $order)

                <div
                    class="reveal-up group block overflow-hidden rounded-[28px] border border-white/5 bg-[#0B1220]/90 p-6 backdrop-blur-xl transition-all duration-300 hover:-translate-y-1.5 hover:border-blue-500/30 hover:shadow-[0_0_35px_rgba(37,99,235,0.14)]">

                    <a href="{{ route('orders.show', $order->order_code) }}" class="block">
                        <div class="flex flex-col gap-5 lg:flex-row lg:items-center lg:justify-between">

                        {{-- LEFT --}}
                        <div class="min-w-0 flex-1"> {{-- Tambahkan min-w-0 flex-1 --}}

                            <div class="flex flex-wrap items-center gap-3">

                                <h2 class="text-xl font-black text-white transition duration-300 group-hover:text-blue-300 break-all"> {{-- Tambahkan break-all --}}
                                    {{ $order->invoice_number }}
                                </h2>

                                <span
                                    class="rounded-full border border-blue-500/20 bg-blue-500/10 px-3 py-1 text-[11px] font-bold uppercase tracking-wide text-blue-300 whitespace-nowrap">
                                    {{ $order->status_label }}
                                </span>

                            </div>

                            <div class="mt-4 flex flex-wrap items-center gap-3">

                                <div
                                    class="rounded-2xl border border-white/5 surface-weak px-4 py-3 transition duration-300 group-hover:border-blue-500/20">

                                    <div class="text-[11px] uppercase tracking-wider text-slate-500">
                                        Tanggal
                                    </div>

                                    <div class="mt-1 text-sm font-semibold text-white whitespace-nowrap">
                                        {{ $order->created_at->translatedFormat('d F Y') }}
                                    </div>
                                </div>

                                <div
                                    class="rounded-2xl border border-white/5 surface-weak px-4 py-3 transition duration-300 group-hover:border-orange-500/20">

                                    <div class="text-[11px] uppercase tracking-wider text-slate-500">
                                        Seller
                                    </div>

                                    <div class="mt-1 text-sm font-semibold text-white max-w-[200px] truncate"> {{-- Tambahkan truncate --}}
                                        {{ $order->seller_label }}
                                    </div>
                                </div>

                            </div>
                        </div>

                        {{-- RIGHT --}}
                        <div
                            class="rounded-[24px] border border-orange-500/20 bg-orange-500/[0.04] px-6 py-5 text-left transition duration-300 group-hover:border-orange-400/30 group-hover:bg-orange-500/[0.06] lg:w-[240px] lg:shrink-0 lg:text-right">

                            <div
                                class="text-[11px] font-semibold uppercase tracking-[0.2em] text-orange-300">
                                Total Pembayaran
                            </div>

                            <div class="mt-2 text-3xl font-black text-white break-all"> {{-- Tambahkan break-all --}}
                                Rp {{ number_format($order->grand_total, 0, ',', '.') }}
                            </div>

                        </div>

                        </div>
                    </a>

                    <div class="mt-5 flex flex-wrap gap-3 border-t border-white/5 pt-5">
                        <a href="{{ route('orders.show', $order->order_code) }}"
                           class="inline-flex items-center justify-center rounded-2xl border border-blue-500/20 bg-blue-500/10 px-4 py-3 text-sm font-bold text-blue-300 transition hover:bg-blue-500/20">
                            Lihat Detail
                        </a>

                        @if($order->status === \App\Models\Order::STATUS_COMPLETED)
                            <a href="{{ route('orders.receipt.pdf', $order->order_code) }}"
                               target="_blank"
                               rel="noopener noreferrer"
                               class="inline-flex items-center justify-center rounded-2xl border border-emerald-500/20 bg-emerald-500/10 px-4 py-3 text-sm font-bold text-emerald-300 transition hover:bg-emerald-500/20">
                                Unduh Kwitansi PDF
                            </a>
                        @endif
                    </div>

                </div>

            @empty

                <div
                    class="reveal-up rounded-[32px] border border-dashed border-white/10 bg-[#0B1220]/75 py-20 text-center backdrop-blur-xl">

                    <div
                        class="mx-auto flex h-20 w-20 items-center justify-center rounded-full border border-blue-500/20 bg-blue-500/10">
                        <svg xmlns="http://www.w3.org/2000/svg"
                            class="h-10 w-10 text-blue-300"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="1.5"
                                d="M3 7h18M6 11h12M8 15h8" />
                        </svg>
                    </div>

                    <div class="mt-6 text-2xl font-bold text-white">
                        Belum Ada Transaksi
                    </div>

                    <p class="mt-3 text-sm text-slate-500">
                        Semua transaksi buyer akan muncul di halaman ini.
                    </p>

                </div>

            @endforelse

        </div>

        {{-- PAGINATION --}}
        <div class="reveal-up mt-10 overflow-x-auto"> {{-- Tambahkan overflow-x-auto --}}
            {{ $orders->links() }}
        </div>

    </div>
</div>

{{-- REVEAL ANIMATION --}}
<style>
.reveal-up{
    opacity:0;
    transform:translateY(50px);
    animation:revealUp 1s cubic-bezier(.22,1,.36,1) forwards;
    will-change:transform, opacity;
}

.reveal-up:nth-child(2){animation-delay:.08s;}
.reveal-up:nth-child(3){animation-delay:.14s;}
.reveal-up:nth-child(4){animation-delay:.20s;}
.reveal-up:nth-child(5){animation-delay:.26s;}
.reveal-up:nth-child(6){animation-delay:.32s;}

@keyframes revealUp{
    to{
        opacity:1;
        transform:translateY(0);
    }
}
</style>
@endsection

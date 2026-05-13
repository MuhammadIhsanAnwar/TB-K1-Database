@extends('layouts.app')

@section('title', 'Daftar Transaksi')

@section('content')
<div class="min-h-screen bg-[#060816] px-4 pb-16 pt-28 overflow-hidden">

    {{-- BACKGROUND EFFECT --}}
    <div class="pointer-events-none absolute inset-0 overflow-hidden">
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
                        <span class="h-2 w-2 rounded-full bg-emerald-400"></span>
                        TRANSACTION HISTORY
                    </div>

                    <h1 class="mt-4 text-3xl font-black text-white md:text-4xl">
                        Riwayat Transaksi
                    </h1>

                    <p class="mt-3 max-w-2xl text-sm leading-relaxed text-slate-300">
                        Lihat seluruh riwayat transaksi, status order, serta detail seller dari akun buyer Anda.
                    </p>
                </div>

                <div
                    class="hidden h-[120px] w-[120px] items-center justify-center rounded-full border border-blue-500/20 bg-blue-500/10 backdrop-blur-xl md:flex">

                    <img src="{{ asset('storage/app/public/logo/logo.png') }}"
                        alt="Logo"
                        class="h-24 w-24 object-contain opacity-95">
                </div>

            </div>
        </div>

        {{-- TRANSACTION LIST --}}
        <div class="mt-8 space-y-5">

            @forelse($orders as $order)

                <a href="{{ route('orders.show', $order->order_code) }}"
                    class="reveal-up group block overflow-hidden rounded-[28px] border border-white/5 bg-[#0B1220]/95 p-6 backdrop-blur-xl transition duration-300 hover:-translate-y-1 hover:border-blue-500/30 hover:shadow-[0_0_30px_rgba(37,99,235,0.12)]">

                    <div class="flex flex-col gap-5 lg:flex-row lg:items-center lg:justify-between">

                        {{-- LEFT --}}
                        <div>

                            <div class="flex flex-wrap items-center gap-3">

                                <h2 class="text-xl font-black text-white">
                                    {{ $order->invoice_number }}
                                </h2>

                                <span
                                    class="rounded-full border border-blue-500/20 bg-blue-500/10 px-3 py-1 text-[11px] font-bold uppercase tracking-wide text-blue-300">
                                    {{ $order->status_label }}
                                </span>

                            </div>

                            <div class="mt-4 flex flex-wrap items-center gap-3">

                                <div
                                    class="rounded-2xl border border-white/5 bg-white/[0.03] px-4 py-3">

                                    <div class="text-[11px] uppercase tracking-wider text-slate-500">
                                        Tanggal
                                    </div>

                                    <div class="mt-1 text-sm font-semibold text-white">
                                        {{ $order->created_at->translatedFormat('d F Y') }}
                                    </div>
                                </div>

                                <div
                                    class="rounded-2xl border border-white/5 bg-white/[0.03] px-4 py-3">

                                    <div class="text-[11px] uppercase tracking-wider text-slate-500">
                                        Seller
                                    </div>

                                    <div class="mt-1 text-sm font-semibold text-white">
                                        {{ $order->seller?->name ?? '-' }}
                                    </div>
                                </div>

                            </div>
                        </div>

                        {{-- RIGHT --}}
                        <div
                            class="rounded-[24px] border border-orange-500/20 bg-orange-500/[0.04] px-6 py-5 text-left lg:min-w-[240px] lg:text-right">

                            <div
                                class="text-[11px] font-semibold uppercase tracking-[0.2em] text-orange-300">
                                Total Pembayaran
                            </div>

                            <div class="mt-2 text-3xl font-black text-white">
                                Rp {{ number_format($order->grand_total, 0, ',', '.') }}
                            </div>

                        </div>

                    </div>
                </a>

            @empty

                <div
                    class="reveal-up rounded-[28px] border border-dashed border-white/10 bg-[#0B1220]/80 py-16 text-center">

                    <div class="text-lg font-semibold text-white">
                        Belum Ada Transaksi
                    </div>

                    <p class="mt-2 text-sm text-slate-500">
                        Semua transaksi buyer akan muncul di halaman ini.
                    </p>

                </div>

            @endforelse

        </div>

        {{-- PAGINATION --}}
        <div class="reveal-up mt-8">
            {{ $orders->links() }}
        </div>

    </div>
</div>

{{-- REVEAL ANIMATION --}}
<style>
.reveal-up{
    opacity:0;
    transform:translateY(45px);
    animation:revealUp .9s cubic-bezier(.22,1,.36,1) forwards;
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
@extends('layouts.app')

@section('title', 'Buyer Dashboard')

@section('content')
<div class="relative min-h-screen overflow-hidden bg-[#050816]">
    
    {{-- ANIMATED BACKGROUND ELEMENTS --}}
    <div class="pointer-events-none fixed inset-0 z-0">
        
        {{-- Grid Pattern --}}
        <div class="absolute inset-0 bg-[linear-gradient(rgba(59,130,246,0.05)_1px,transparent_1px),linear-gradient(90deg,rgba(59,130,246,0.05)_1px,transparent_1px)] bg-[size:50px_50px] [mask-image:radial-gradient(ellipse_at_center,black_30%,transparent_70%)]"></div>
        
        {{-- Animated Gradient Orbs --}}
        <div class="absolute top-0 -left-40 w-96 h-96 bg-blue-500/20 rounded-full blur-[100px] animate-orb-1"></div>
        <div class="absolute top-1/2 -right-40 w-96 h-96 bg-orange-500/20 rounded-full blur-[100px] animate-orb-2"></div>
        <div class="absolute -bottom-40 left-1/2 w-96 h-96 bg-cyan-500/20 rounded-full blur-[100px] animate-orb-3"></div>
        <div class="absolute top-1/4 left-1/3 w-64 h-64 bg-purple-500/10 rounded-full blur-[80px] animate-orb-4"></div>
        
        {{-- Moving Lines --}}
        <div class="absolute inset-0 overflow-hidden">
            <div class="absolute top-20 left-0 w-full h-px bg-gradient-to-r from-transparent via-blue-500/20 to-transparent animate-line-1"></div>
            <div class="absolute top-1/3 left-0 w-full h-px bg-gradient-to-r from-transparent via-orange-500/20 to-transparent animate-line-2"></div>
            <div class="absolute top-2/3 left-0 w-full h-px bg-gradient-to-r from-transparent via-cyan-500/20 to-transparent animate-line-3"></div>
        </div>
        
        {{-- Floating Particles --}}
        <div class="absolute top-1/4 left-1/4 w-2 h-2 bg-blue-400/60 rounded-full animate-float-1"></div>
        <div class="absolute top-1/3 right-1/3 w-1.5 h-1.5 bg-orange-400/60 rounded-full animate-float-2"></div>
        <div class="absolute bottom-1/3 left-1/3 w-2 h-2 bg-cyan-400/60 rounded-full animate-float-3"></div>
        <div class="absolute top-2/3 right-1/4 w-1 h-1 bg-purple-400/60 rounded-full animate-float-4"></div>
        <div class="absolute top-1/2 left-1/2 w-1.5 h-1.5 bg-emerald-400/60 rounded-full animate-float-5"></div>
        <div class="absolute bottom-1/4 right-1/2 w-2 h-2 bg-blue-400/60 rounded-full animate-float-6"></div>
        <div class="absolute top-3/4 left-3/4 w-1 h-1 bg-orange-400/60 rounded-full animate-float-7"></div>
        <div class="absolute top-1/5 right-1/5 w-1.5 h-1.5 bg-cyan-400/60 rounded-full animate-float-8"></div>
    </div>

    {{-- ANIMATIONS STYLE --}}
    <style>
        /* Reveal Animation */
        .reveal {
            opacity: 0;
            transform: translateY(30px);
            animation: revealUp .8s ease forwards;
        }
        .reveal-delay-1 { animation-delay: .15s; }
        .reveal-delay-2 { animation-delay: .3s; }
        .reveal-delay-3 { animation-delay: .45s; }
        
        @keyframes revealUp {
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        /* Orb Animations */
        @keyframes orb1 {
            0%, 100% { transform: translate(0, 0) scale(1); }
            25% { transform: translate(100px, 50px) scale(1.1); }
            50% { transform: translate(50px, 100px) scale(0.9); }
            75% { transform: translate(-50px, 50px) scale(1.05); }
        }
        
        @keyframes orb2 {
            0%, 100% { transform: translate(0, 0) scale(1); }
            25% { transform: translate(-80px, -60px) scale(1.15); }
            50% { transform: translate(-40px, -120px) scale(0.95); }
            75% { transform: translate(60px, -40px) scale(1.1); }
        }
        
        @keyframes orb3 {
            0%, 100% { transform: translate(0, 0) scale(1); }
            25% { transform: translate(-60px, -80px) scale(1.1); }
            50% { transform: translate(-120px, -40px) scale(0.9); }
            75% { transform: translate(-40px, -60px) scale(1.05); }
        }
        
        @keyframes orb4 {
            0%, 100% { transform: translate(0, 0) scale(1); }
            50% { transform: translate(70px, -70px) scale(1.2); }
        }
        
        .animate-orb-1 { animation: orb1 20s ease-in-out infinite; }
        .animate-orb-2 { animation: orb2 25s ease-in-out infinite; }
        .animate-orb-3 { animation: orb3 22s ease-in-out infinite; }
        .animate-orb-4 { animation: orb4 18s ease-in-out infinite; }
        
        /* Moving Lines */
        @keyframes line1 {
            0% { transform: translateY(0) rotate(0deg); }
            50% { transform: translateY(30px) rotate(2deg); }
            100% { transform: translateY(0) rotate(0deg); }
        }
        
        @keyframes line2 {
            0% { transform: translateY(0) rotate(0deg); }
            50% { transform: translateY(-20px) rotate(-1deg); }
            100% { transform: translateY(0) rotate(0deg); }
        }
        
        @keyframes line3 {
            0% { transform: translateY(0) rotate(0deg); }
            50% { transform: translateY(40px) rotate(1deg); }
            100% { transform: translateY(0) rotate(0deg); }
        }
        
        .animate-line-1 { animation: line1 8s ease-in-out infinite; }
        .animate-line-2 { animation: line2 10s ease-in-out infinite; }
        .animate-line-3 { animation: line3 9s ease-in-out infinite; }
        
        /* Floating Particles */
        @keyframes float1 {
            0%, 100% { transform: translate(0, 0); opacity: 0.3; }
            25% { transform: translate(30px, -40px); opacity: 0.8; }
            50% { transform: translate(-20px, -80px); opacity: 0.5; }
            75% { transform: translate(40px, -50px); opacity: 0.9; }
        }
        
        @keyframes float2 {
            0%, 100% { transform: translate(0, 0); opacity: 0.4; }
            33% { transform: translate(-40px, -60px); opacity: 0.9; }
            66% { transform: translate(20px, -30px); opacity: 0.6; }
        }
        
        @keyframes float3 {
            0%, 100% { transform: translate(0, 0); opacity: 0.3; }
            50% { transform: translate(50px, -70px); opacity: 0.8; }
        }
        
        @keyframes float4 {
            0%, 100% { transform: translate(0, 0); opacity: 0.5; }
            25% { transform: translate(-30px, -50px); opacity: 0.9; }
            75% { transform: translate(30px, -40px); opacity: 0.7; }
        }
        
        @keyframes float5 {
            0%, 100% { transform: translate(0, 0); opacity: 0.4; }
            50% { transform: translate(-50px, -90px); opacity: 0.8; }
        }
        
        @keyframes float6 {
            0%, 100% { transform: translate(0, 0); opacity: 0.3; }
            33% { transform: translate(40px, -50px); opacity: 0.9; }
            66% { transform: translate(-20px, -70px); opacity: 0.5; }
        }
        
        @keyframes float7 {
            0%, 100% { transform: translate(0, 0); opacity: 0.5; }
            50% { transform: translate(-40px, -60px); opacity: 0.8; }
        }
        
        @keyframes float8 {
            0%, 100% { transform: translate(0, 0); opacity: 0.4; }
            25% { transform: translate(30px, -80px); opacity: 0.9; }
            75% { transform: translate(-30px, -40px); opacity: 0.6; }
        }
        
        .animate-float-1 { animation: float1 12s ease-in-out infinite; }
        .animate-float-2 { animation: float2 15s ease-in-out infinite; }
        .animate-float-3 { animation: float3 14s ease-in-out infinite; }
        .animate-float-4 { animation: float4 13s ease-in-out infinite; }
        .animate-float-5 { animation: float5 16s ease-in-out infinite; }
        .animate-float-6 { animation: float6 11s ease-in-out infinite; }
        .animate-float-7 { animation: float7 17s ease-in-out infinite; }
        .animate-float-8 { animation: float8 14s ease-in-out infinite; }
    </style>

    {{-- MAIN CONTENT --}}
    <div class="relative z-10 mx-auto mt-10 max-w-5xl space-y-7 px-4 md:px-6 pb-16">

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
</div>
@endsection
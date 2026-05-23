@extends('layouts.app')

@section('content')
<div class="relative min-h-screen overflow-hidden bg-[#050816]">
    
    {{-- ═══════════════════════════════════════ --}}
    {{-- ANIMATED BACKGROUND ELEMENTS          --}}
    {{-- ═══════════════════════════════════════ --}}
    <div class="pointer-events-none fixed inset-0 z-0">
        
        {{-- Grid Pattern --}}
        <div class="absolute inset-0 bg-[linear-gradient(rgba(59,130,246,0.04)_1px,transparent_1px),linear-gradient(90deg,rgba(59,130,246,0.04)_1px,transparent_1px)] bg-[size:60px_60px] [mask-image:radial-gradient(ellipse_at_center,black_25%,transparent_70%)]"></div>
        
        {{-- Animated Gradient Orbs --}}
        <div class="absolute top-0 -left-20 w-[500px] h-[500px] bg-blue-500/20 rounded-full blur-[120px] animate-orb-1"></div>
        <div class="absolute top-1/3 -right-20 w-[450px] h-[450px] bg-orange-500/15 rounded-full blur-[120px] animate-orb-2"></div>
        <div class="absolute -bottom-32 left-1/3 w-[400px] h-[400px] bg-cyan-500/15 rounded-full blur-[120px] animate-orb-3"></div>
        <div class="absolute top-1/2 left-1/2 w-[350px] h-[350px] -translate-x-1/2 -translate-y-1/2 bg-purple-500/10 rounded-full blur-[100px] animate-orb-4"></div>
        
        {{-- Moving Gradient Lines --}}
        <div class="absolute inset-0 overflow-hidden">
            <div class="absolute top-1/4 left-0 w-full h-px bg-gradient-to-r from-transparent via-blue-400/20 to-transparent animate-line-1"></div>
            <div class="absolute top-2/4 left-0 w-full h-px bg-gradient-to-r from-transparent via-cyan-400/15 to-transparent animate-line-2"></div>
            <div class="absolute top-3/4 left-0 w-full h-px bg-gradient-to-r from-transparent via-orange-400/20 to-transparent animate-line-3"></div>
        </div>
        
        {{-- Floating Particles --}}
        <div class="absolute top-1/4 left-1/4 w-2 h-2 bg-blue-400/60 rounded-full animate-float-1"></div>
        <div class="absolute top-1/3 right-1/3 w-1.5 h-1.5 bg-orange-400/50 rounded-full animate-float-2"></div>
        <div class="absolute bottom-1/3 left-1/3 w-2 h-2 bg-cyan-400/50 rounded-full animate-float-3"></div>
        <div class="absolute top-2/3 right-1/4 w-1.5 h-1.5 bg-purple-400/50 rounded-full animate-float-4"></div>
        <div class="absolute top-1/2 left-1/2 w-2 h-2 bg-emerald-400/60 rounded-full animate-float-5"></div>
        <div class="absolute bottom-1/4 right-1/3 w-1.5 h-1.5 bg-blue-400/50 rounded-full animate-float-6"></div>
        <div class="absolute top-3/4 left-3/4 w-1 h-1 bg-orange-400/60 rounded-full animate-float-7"></div>
        <div class="absolute top-1/5 right-1/5 w-2 h-2 bg-cyan-400/50 rounded-full animate-float-8"></div>
        
        {{-- Diagonal Light Rays --}}
        <div class="absolute top-0 left-0 w-full h-full overflow-hidden opacity-20">
            <div class="absolute top-0 left-1/4 w-px h-full bg-gradient-to-b from-transparent via-blue-400/30 to-transparent transform -rotate-12 animate-ray-1"></div>
            <div class="absolute top-0 left-1/2 w-px h-full bg-gradient-to-b from-transparent via-cyan-400/20 to-transparent transform rotate-12 animate-ray-2"></div>
            <div class="absolute top-0 left-3/4 w-px h-full bg-gradient-to-b from-transparent via-purple-400/25 to-transparent transform -rotate-6 animate-ray-3"></div>
        </div>
    </div>

    {{-- ANIMATIONS STYLE --}}
    <style>
        /* Reveal Animation */
        .reveal {
            opacity: 0;
            transform: translateY(45px);
            animation: revealUp .9s cubic-bezier(.22,1,.36,1) forwards;
        }
        .reveal-delay-1 { animation-delay: .12s; }
        .reveal-delay-2 { animation-delay: .22s; }
        .reveal-delay-3 { animation-delay: .32s; }
        
        @keyframes revealUp {
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        select option {
            background: #111827;
            color: white;
        }
        
        /* Orb Animations */
        @keyframes orb1 {
            0%, 100% { transform: translate(0, 0) scale(1); }
            25% { transform: translate(80px, 40px) scale(1.15); }
            50% { transform: translate(40px, 80px) scale(0.9); }
            75% { transform: translate(-40px, 30px) scale(1.1); }
        }
        
        @keyframes orb2 {
            0%, 100% { transform: translate(0, 0) scale(1); }
            25% { transform: translate(-70px, -50px) scale(1.2); }
            50% { transform: translate(-30px, -100px) scale(0.85); }
            75% { transform: translate(50px, -30px) scale(1.05); }
        }
        
        @keyframes orb3 {
            0%, 100% { transform: translate(0, 0) scale(1); }
            33% { transform: translate(-50px, -60px) scale(1.1); }
            66% { transform: translate(60px, -20px) scale(0.95); }
        }
        
        @keyframes orb4 {
            0%, 100% { transform: translate(0, 0) scale(1); }
            50% { transform: translate(60px, -60px) scale(1.25); }
        }
        
        .animate-orb-1 { animation: orb1 18s ease-in-out infinite; }
        .animate-orb-2 { animation: orb2 22s ease-in-out infinite; }
        .animate-orb-3 { animation: orb3 20s ease-in-out infinite; }
        .animate-orb-4 { animation: orb4 16s ease-in-out infinite; }
        
        /* Moving Lines */
        @keyframes line1 {
            0%, 100% { transform: translateY(0) rotate(0deg); opacity: 0.5; }
            50% { transform: translateY(40px) rotate(2deg); opacity: 0.8; }
        }
        
        @keyframes line2 {
            0%, 100% { transform: translateY(0) rotate(0deg); opacity: 0.4; }
            50% { transform: translateY(-30px) rotate(-1deg); opacity: 0.7; }
        }
        
        @keyframes line3 {
            0%, 100% { transform: translateY(0) rotate(0deg); opacity: 0.5; }
            50% { transform: translateY(50px) rotate(1deg); opacity: 0.8; }
        }
        
        .animate-line-1 { animation: line1 8s ease-in-out infinite; }
        .animate-line-2 { animation: line2 10s ease-in-out infinite; }
        .animate-line-3 { animation: line3 9s ease-in-out infinite; }
        
        /* Floating Particles */
        @keyframes float1 {
            0%, 100% { transform: translate(0, 0); opacity: 0.3; }
            25% { transform: translate(35px, -45px); opacity: 0.8; }
            50% { transform: translate(-25px, -85px); opacity: 0.5; }
            75% { transform: translate(45px, -55px); opacity: 0.9; }
        }
        
        @keyframes float2 {
            0%, 100% { transform: translate(0, 0); opacity: 0.4; }
            33% { transform: translate(-45px, -65px); opacity: 0.9; }
            66% { transform: translate(25px, -35px); opacity: 0.6; }
        }
        
        @keyframes float3 {
            0%, 100% { transform: translate(0, 0); opacity: 0.3; }
            50% { transform: translate(55px, -75px); opacity: 0.8; }
        }
        
        @keyframes float4 {
            0%, 100% { transform: translate(0, 0); opacity: 0.5; }
            25% { transform: translate(-35px, -55px); opacity: 0.9; }
            75% { transform: translate(35px, -45px); opacity: 0.7; }
        }
        
        @keyframes float5 {
            0%, 100% { transform: translate(0, 0); opacity: 0.4; }
            50% { transform: translate(-55px, -95px); opacity: 0.8; }
        }
        
        @keyframes float6 {
            0%, 100% { transform: translate(0, 0); opacity: 0.3; }
            33% { transform: translate(45px, -55px); opacity: 0.9; }
            66% { transform: translate(-25px, -75px); opacity: 0.5; }
        }
        
        @keyframes float7 {
            0%, 100% { transform: translate(0, 0); opacity: 0.5; }
            50% { transform: translate(-45px, -65px); opacity: 0.8; }
        }
        
        @keyframes float8 {
            0%, 100% { transform: translate(0, 0); opacity: 0.4; }
            25% { transform: translate(35px, -85px); opacity: 0.9; }
            75% { transform: translate(-35px, -45px); opacity: 0.6; }
        }
        
        .animate-float-1 { animation: float1 11s ease-in-out infinite; }
        .animate-float-2 { animation: float2 14s ease-in-out infinite; }
        .animate-float-3 { animation: float3 13s ease-in-out infinite; }
        .animate-float-4 { animation: float4 12s ease-in-out infinite; }
        .animate-float-5 { animation: float5 15s ease-in-out infinite; }
        .animate-float-6 { animation: float6 10s ease-in-out infinite; }
        .animate-float-7 { animation: float7 16s ease-in-out infinite; }
        .animate-float-8 { animation: float8 13s ease-in-out infinite; }
        
        /* Light Rays */
        @keyframes ray1 {
            0%, 100% { transform: translateX(0) rotate(-12deg); opacity: 0.3; }
            50% { transform: translateX(100px) rotate(-12deg); opacity: 0.6; }
        }
        
        @keyframes ray2 {
            0%, 100% { transform: translateX(0) rotate(12deg); opacity: 0.2; }
            50% { transform: translateX(-80px) rotate(12deg); opacity: 0.5; }
        }
        
        @keyframes ray3 {
            0%, 100% { transform: translateX(0) rotate(-6deg); opacity: 0.25; }
            50% { transform: translateX(60px) rotate(-6deg); opacity: 0.55; }
        }
        
        .animate-ray-1 { animation: ray1 12s ease-in-out infinite; }
        .animate-ray-2 { animation: ray2 15s ease-in-out infinite; }
        .animate-ray-3 { animation: ray3 14s ease-in-out infinite; }
    </style>

    {{-- MAIN CONTENT --}}
    <div class="relative z-10 mx-auto max-w-5xl space-y-7 px-4 py-14">

        {{-- HEADER --}}
        <div
            class="reveal relative overflow-hidden rounded-[34px] border border-blue-500/15 bg-gradient-to-br from-[#060816] via-[#091225] to-[#0B1730] px-7 py-8 shadow-[0_0_80px_rgba(37,99,235,0.12)]">

            <div
                class="absolute inset-0 bg-[radial-gradient(circle_at_top_right,rgba(37,99,235,0.18),transparent_35%)]">
            </div>

            <div class="relative z-10 flex flex-col gap-7 lg:flex-row lg:items-center lg:justify-between">

                <div>

                    <div
                        class="inline-flex items-center gap-2 rounded-full border border-blue-500/30 bg-blue-500/10 px-4 py-2 text-[11px] font-bold tracking-[0.18em] text-blue-300 backdrop-blur-xl">

                        <span class="h-2 w-2 rounded-full bg-emerald-400 animate-pulse"></span>
                        ACCOUNT PROFILE
                    </div>

                    <h1 class="mt-5 text-3xl font-black text-white md:text-5xl">
                        My Profile
                    </h1>

                    <p class="mt-4 max-w-xl text-sm leading-relaxed text-slate-300">
                        Kelola informasi akun dan lihat detail profil akun Lapak Gaming milikmu.
                    </p>

                </div>

                <div class="flex items-center gap-4">

                    {{-- LOGO --}}
                    <div
                        class="relative hidden h-[120px] w-[120px] items-center justify-center rounded-full border border-blue-500/20 bg-blue-500/5 backdrop-blur-2xl md:flex">

                        <div
                            class="absolute h-[160px] w-[160px] rounded-full bg-blue-500/10 blur-3xl">
                        </div>

                        <img src="{{ asset('storage/app/public/logo/logo.png') }}"
                            alt="Logo"
                            class="relative z-10 h-20 w-20 object-contain drop-shadow-[0_0_25px_rgba(59,130,246,0.55)]">
                    </div>

                    {{-- BUTTON --}}
                    <a href="{{ route('profile.edit') }}"
                        class="group rounded-2xl border border-blue-500/30 bg-gradient-to-r from-blue-600 to-blue-500 px-5 py-3 text-sm font-bold tracking-wide text-white transition duration-300 hover:-translate-y-1 hover:shadow-[0_0_30px_rgba(59,130,246,0.35)]">

                        Edit Profile
                    </a>

                </div>

            </div>
        </div>

        {{-- PROFILE CARD --}}
        <div
            class="reveal reveal-delay-1 overflow-hidden rounded-[34px] border border-white/10 bg-[#0B1220]/92 p-7 shadow-[0_0_60px_rgba(37,99,235,0.08)] backdrop-blur-xl">

            {{-- PROFILE HEADER --}}
            <div
                class="flex flex-col gap-6 border-b border-white/10 pb-7 md:flex-row md:items-center">

                {{-- AVATAR --}}
                <div
                    class="relative flex h-24 w-24 items-center justify-center overflow-hidden rounded-full bg-gradient-to-br from-blue-500 via-cyan-400 to-orange-400 text-3xl font-black text-white shadow-[0_0_35px_rgba(59,130,246,0.35)]">

                    <img src="{{ $user->avatar_url }}"
                        alt="Foto profil {{ $user->name }}"
                        class="h-full w-full object-cover">

                    <div
                        class="absolute inset-0 rounded-full border border-white/20">
                    </div>
                </div>

                {{-- USER INFO --}}
                <div class="flex-1">

                    <h2 class="text-3xl font-black text-white">
                        {{ $user->name }}
                    </h2>

                    <p class="mt-2 text-sm text-slate-400">
                        {{ $user->email }}
                    </p>

                    @if($user->phone)
                    <p class="mt-1 text-sm text-slate-400">
                        {{ $user->phone }}
                    </p>
                    @endif

                    <div class="mt-4">

                        <span
                            class="inline-flex items-center gap-2 rounded-full border border-emerald-500/20 bg-emerald-500/10 px-4 py-2 text-xs font-bold tracking-wide text-emerald-300">

                            <span class="h-2 w-2 rounded-full bg-emerald-400 animate-pulse"></span>
                            ACCOUNT ACTIVE
                        </span>

                    </div>
                </div>

            </div>

            {{-- ACCOUNT INFO --}}
            <div
                class="reveal reveal-delay-2 mt-7 grid gap-5 md:grid-cols-2">

                {{-- EMAIL --}}
                <div
                    class="rounded-2xl border border-white/5 bg-white/[0.03] p-5 transition duration-300 hover:-translate-y-1 hover:border-blue-500/20 hover:bg-blue-500/[0.03]">

                    <p
                        class="text-xs font-semibold uppercase tracking-[0.18em] text-blue-300">
                        Email Address
                    </p>

                    <p class="mt-3 text-lg font-bold text-white">
                        {{ $user->email }}
                    </p>
                </div>

                {{-- FULL NAME --}}
                <div
                    class="rounded-2xl border border-white/5 bg-white/[0.03] p-5 transition duration-300 hover:-translate-y-1 hover:border-orange-500/20 hover:bg-orange-500/[0.03]">

                    <p
                        class="text-xs font-semibold uppercase tracking-[0.18em] text-orange-300">
                        Full Name
                    </p>

                    <p class="mt-3 text-lg font-bold text-white">
                        {{ $user->name }}
                    </p>
                </div>

                {{-- MEMBER SINCE --}}
                <div
                    class="rounded-2xl border border-white/5 bg-white/[0.03] p-5 transition duration-300 hover:-translate-y-1 hover:border-cyan-500/20 hover:bg-cyan-500/[0.03]">

                    <p
                        class="text-xs font-semibold uppercase tracking-[0.18em] text-cyan-300">
                        Member Since
                    </p>

                    <p class="mt-3 text-lg font-bold text-white">
                        {{ $user->created_at->format('d M Y') }}
                    </p>
                </div>

                {{-- STATUS --}}
                <div
                    class="rounded-2xl border border-white/5 bg-white/[0.03] p-5 transition duration-300 hover:-translate-y-1 hover:border-emerald-500/20 hover:bg-emerald-500/[0.03]">

                    <p
                        class="text-xs font-semibold uppercase tracking-[0.18em] text-emerald-300">
                        Account Status
                    </p>

                    <div class="mt-3">

                        <span
                            class="inline-flex items-center gap-2 rounded-full border border-emerald-500/20 bg-emerald-500/10 px-4 py-2 text-sm font-bold text-emerald-300">

                            <span class="h-2 w-2 rounded-full bg-emerald-400 animate-pulse"></span>
                            Active
                        </span>

                    </div>
                </div>

            </div>

            {{-- ADDITIONAL INFO --}}
            @if($user->userProfile)

            <div
                class="reveal reveal-delay-3 mt-8 border-t border-white/10 pt-7">

                <div class="flex items-center justify-between">

                    <h3 class="text-2xl font-black text-white">
                        Additional Information
                    </h3>

                    <div
                        class="rounded-full border border-blue-500/20 bg-blue-500/10 px-3 py-1 text-[11px] font-bold text-blue-300">

                        USER DATA
                    </div>
                </div>

                <div class="mt-5 grid gap-5 md:grid-cols-2">

                    @if($user->userProfile->phone_verified_at)

                    <div
                        class="rounded-2xl border border-emerald-500/10 bg-emerald-500/[0.03] p-5">

                        <p
                            class="text-xs font-semibold uppercase tracking-[0.18em] text-emerald-300">

                            Phone Verified
                        </p>

                        <div class="mt-3">

                            <span
                                class="inline-flex items-center gap-2 rounded-full border border-emerald-500/20 bg-emerald-500/10 px-4 py-2 text-sm font-bold text-emerald-300">

                                <span class="h-2 w-2 rounded-full bg-emerald-400 animate-pulse"></span>
                                Verified
                            </span>

                        </div>

                    </div>

                    @endif

                </div>

            </div>

            @endif

        </div>

    </div>
</div>
@endsection
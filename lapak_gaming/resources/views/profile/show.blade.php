@extends('layouts.app')

@section('content')
<div class="min-h-screen px-4 pt-28 pb-14">

    {{-- ═══════════════════════════════════════ --}}
    {{-- ANIMATED BACKGROUND ELEMENTS          --}}
    {{-- ═══════════════════════════════════════ --}}
    <div class="pointer-events-none fixed inset-0 z-0 overflow-hidden">
        
        {{-- Grid Pattern --}}
        <div class="absolute inset-0 bg-[linear-gradient(rgba(59,130,246,0.04)_1px,transparent_1px),linear-gradient(90deg,rgba(59,130,246,0.04)_1px,transparent_1px)] bg-[size:60px_60px] [mask-image:radial-gradient(ellipse_at_center,black_25%,transparent_70%)]"></div>
        
        {{-- Animated Gradient Orbs --}}
        <div class="absolute top-0 -left-20 w-[500px] h-[500px] bg-blue-500/20 rounded-full blur-[120px] animate-[orb1_18s_ease-in-out_infinite]"></div>
        <div class="absolute top-1/3 -right-20 w-[450px] h-[450px] bg-orange-500/15 rounded-full blur-[120px] animate-[orb2_22s_ease-in-out_infinite]"></div>
        <div class="absolute -bottom-32 left-1/3 w-[400px] h-[400px] bg-cyan-500/15 rounded-full blur-[120px] animate-[orb3_20s_ease-in-out_infinite]"></div>
        <div class="absolute top-1/2 left-1/2 w-[350px] h-[350px] -translate-x-1/2 -translate-y-1/2 bg-purple-500/10 rounded-full blur-[100px] animate-[orb4_16s_ease-in-out_infinite]"></div>
        
        {{-- Moving Gradient Lines --}}
        <div class="absolute inset-0 overflow-hidden">
            <div class="absolute top-1/4 left-0 w-full h-px bg-gradient-to-r from-transparent via-blue-400/20 to-transparent animate-[line1_8s_ease-in-out_infinite]"></div>
            <div class="absolute top-2/4 left-0 w-full h-px bg-gradient-to-r from-transparent via-cyan-400/15 to-transparent animate-[line2_10s_ease-in-out_infinite]"></div>
            <div class="absolute top-3/4 left-0 w-full h-px bg-gradient-to-r from-transparent via-orange-400/20 to-transparent animate-[line3_9s_ease-in-out_infinite]"></div>
        </div>
        
        {{-- Floating Particles --}}
        <div class="absolute top-1/4 left-1/4 w-2 h-2 bg-blue-400/60 rounded-full animate-[float1_11s_ease-in-out_infinite]"></div>
        <div class="absolute top-1/3 right-1/3 w-1.5 h-1.5 bg-orange-400/50 rounded-full animate-[float2_14s_ease-in-out_infinite]"></div>
        <div class="absolute bottom-1/3 left-1/3 w-2 h-2 bg-cyan-400/50 rounded-full animate-[float3_13s_ease-in-out_infinite]"></div>
        <div class="absolute top-2/3 right-1/4 w-1.5 h-1.5 bg-purple-400/50 rounded-full animate-[float4_12s_ease-in-out_infinite]"></div>
        <div class="absolute top-1/2 left-1/2 w-2 h-2 bg-emerald-400/60 rounded-full animate-[float5_15s_ease-in-out_infinite]"></div>
        <div class="absolute bottom-1/4 right-1/3 w-1.5 h-1.5 bg-blue-400/50 rounded-full animate-[float6_10s_ease-in-out_infinite]"></div>
    </div>

    {{-- MAIN CONTENT --}}
    <div class="mx-auto max-w-5xl opacity-0 translate-y-8 animate-[fadeReveal_.9s_ease-out_forwards]">

        {{-- HEADER --}}
        <div
            class="relative overflow-hidden rounded-[34px] border border-blue-500/15 bg-gradient-to-br from-[#060816] via-[#091225] to-[#0B1730] px-7 py-8 shadow-[0_0_80px_rgba(37,99,235,0.12)]">

            <div
                class="absolute inset-0 bg-[radial-gradient(circle_at_top_right,rgba(37,99,235,0.18),transparent_35%)]">
            </div>

            <div class="relative z-10 flex flex-col gap-7 lg:flex-row lg:items-center lg:justify-between">

                <div>

                    <div
                        class="inline-flex items-center gap-2 rounded-full border border-blue-500/30 bg-blue-500/10 px-4 py-2 text-[11px] font-bold tracking-[0.18em] text-blue-300 backdrop-blur-xl">

                        <span class="h-2 w-2 rounded-full bg-emerald-400"></span>
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
            class="mt-8 overflow-hidden rounded-[34px] border border-white/10 bg-[#0B1220]/92 p-7 shadow-[0_0_60px_rgba(37,99,235,0.08)] backdrop-blur-xl">

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

                            <span class="h-2 w-2 rounded-full bg-emerald-400"></span>
                            ACCOUNT ACTIVE
                        </span>

                    </div>
                </div>

            </div>

            {{-- ACCOUNT INFO --}}
            <div class="mt-7 grid gap-5 md:grid-cols-2">

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

                            <span class="h-2 w-2 rounded-full bg-emerald-400"></span>
                            Active
                        </span>

                    </div>
                </div>

            </div>

            {{-- ADDITIONAL INFO --}}
            @if($user->userProfile)

            <div class="mt-8 border-t border-white/10 pt-7">

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

                                <span class="h-2 w-2 rounded-full bg-emerald-400"></span>
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

{{-- REVEAL ANIMATION --}}
<style>
    @keyframes fadeReveal {
        0% {
            opacity: 0;
            transform: translateY(35px);
        }
        100% {
            opacity: 1;
            transform: translateY(0);
        }
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
</style>
@endsection
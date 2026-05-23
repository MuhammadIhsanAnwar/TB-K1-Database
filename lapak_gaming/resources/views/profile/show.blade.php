@extends('layouts.app')

@section('content')
<div class="relative min-h-screen overflow-hidden bg-[#050816] px-4 py-14">

    {{-- GLOBAL BACKGROUND EFFECTS --}}
    <div class="pointer-events-none fixed inset-0 -z-10 overflow-hidden">
        {{-- Grid Pattern Background --}}
        <div class="absolute inset-0 bg-[linear-gradient(rgba(59,130,246,0.03)_1px,transparent_1px),linear-gradient(90deg,rgba(59,130,246,0.03)_1px,transparent_1px)] bg-[size:60px_60px]"></div>
        
        {{-- Blue Glow Top Right --}}
        <div class="absolute -top-60 -right-60 h-[500px] w-[500px] rounded-full bg-blue-600/20 blur-[100px] animate-pulse"></div>
        
        {{-- Orange Glow Bottom Left --}}
        <div class="absolute -bottom-60 -left-60 h-[500px] w-[500px] rounded-full bg-orange-500/20 blur-[100px] animate-pulse" style="animation-delay: 1s;"></div>
        
        {{-- Cyan Glow Center --}}
        <div class="absolute top-1/2 left-1/2 h-[400px] w-[400px] -translate-x-1/2 -translate-y-1/2 rounded-full bg-cyan-400/10 blur-[120px] animate-pulse" style="animation-delay: 2s;"></div>
        
        {{-- Small Accent Glows --}}
        <div class="absolute top-20 left-1/4 h-[200px] w-[200px] rounded-full bg-purple-500/10 blur-[80px]"></div>
        <div class="absolute bottom-40 right-1/3 h-[250px] w-[250px] rounded-full bg-emerald-500/10 blur-[80px]"></div>
    </div>

    {{-- FLOATING PARTICLES --}}
    <div class="pointer-events-none fixed inset-0 -z-5 overflow-hidden">
        <div class="absolute top-1/4 left-1/3 h-1 w-1 rounded-full bg-blue-400/50 animate-float"></div>
        <div class="absolute top-2/3 left-1/4 h-1.5 w-1.5 rounded-full bg-orange-400/40 animate-float" style="animation-delay: 0.5s;"></div>
        <div class="absolute top-1/2 left-2/3 h-1 w-1 rounded-full bg-cyan-400/50 animate-float" style="animation-delay: 1s;"></div>
        <div class="absolute top-1/3 left-3/4 h-2 w-2 rounded-full bg-purple-400/30 animate-float" style="animation-delay: 1.5s;"></div>
        <div class="absolute top-3/4 left-1/2 h-1.5 w-1.5 rounded-full bg-emerald-400/40 animate-float" style="animation-delay: 0.8s;"></div>
    </div>

    {{-- REVEAL ANIMATION & ADDITIONAL STYLES --}}
    <style>
        .reveal {
            opacity: 0;
            transform: translateY(45px);
            animation: revealUp 0.9s cubic-bezier(0.22, 1, 0.36, 1) forwards;
        }

        .reveal-delay-1 { animation-delay: 0.12s; }
        .reveal-delay-2 { animation-delay: 0.22s; }
        .reveal-delay-3 { animation-delay: 0.32s; }

        @keyframes revealUp {
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes float {
            0%, 100% {
                transform: translateY(0px) translateX(0px);
                opacity: 0.3;
            }
            25% {
                transform: translateY(-20px) translateX(10px);
                opacity: 0.6;
            }
            50% {
                transform: translateY(-10px) translateX(-10px);
                opacity: 0.4;
            }
            75% {
                transform: translateY(-30px) translateX(5px);
                opacity: 0.7;
            }
        }

        .animate-float {
            animation: float 6s ease-in-out infinite;
        }

        select option {
            background: #111827;
            color: white;
        }

        /* Smooth transitions */
        .transition-all {
            transition-property: all;
            transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
            transition-duration: 300ms;
        }

        /* Glass effect enhancement */
        .glass-effect {
            backdrop-filter: blur(20px) saturate(180%);
            -webkit-backdrop-filter: blur(20px) saturate(180%);
        }

        /* Gradient text effect */
        .gradient-text {
            background: linear-gradient(135deg, #60a5fa 0%, #34d399 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
    </style>

    <div class="relative z-10 mx-auto max-w-5xl space-y-7">

        {{-- HEADER --}}
        <div
            class="reveal relative overflow-hidden rounded-[34px] border border-blue-500/20 bg-gradient-to-br from-[#060816]/95 via-[#091225]/95 to-[#0B1730]/95 px-7 py-8 shadow-[0_0_80px_rgba(37,99,235,0.15),0_0_200px_rgba(37,99,235,0.05)] backdrop-blur-2xl">

            <div
                class="absolute inset-0 bg-[radial-gradient(circle_at_top_right,rgba(37,99,235,0.25),transparent_40%)]">
            </div>
            
            {{-- Additional inner glow --}}
            <div class="absolute inset-0 bg-[radial-gradient(circle_at_bottom_left,rgba(251,146,60,0.15),transparent_40%)]"></div>

            <div class="relative z-10 flex flex-col gap-7 lg:flex-row lg:items-center lg:justify-between">

                <div>

                    <div
                        class="inline-flex items-center gap-2 rounded-full border border-blue-500/40 bg-blue-500/15 px-4 py-2 text-[11px] font-bold tracking-[0.18em] text-blue-300 backdrop-blur-xl shadow-[0_0_20px_rgba(59,130,246,0.2)]">

                        <span class="h-2.5 w-2.5 rounded-full bg-emerald-400 animate-pulse shadow-[0_0_10px_rgba(52,211,153,0.5)]"></span>
                        ACCOUNT PROFILE
                    </div>

                    <h1 class="mt-5 text-4xl font-black text-white md:text-5xl gradient-text">
                        My Profile
                    </h1>

                    <p class="mt-4 max-w-xl text-sm leading-relaxed text-slate-300/90">
                        Kelola informasi akun dan lihat detail profil akun Lapak Gaming milikmu.
                    </p>

                </div>

                <div class="flex items-center gap-4">

                    {{-- LOGO --}}
                    <div
                        class="relative hidden h-[120px] w-[120px] items-center justify-center rounded-full border border-blue-500/30 bg-blue-500/10 backdrop-blur-2xl md:flex shadow-[0_0_50px_rgba(59,130,246,0.2)]">

                        <div
                            class="absolute h-[160px] w-[160px] rounded-full bg-blue-500/20 blur-3xl animate-pulse">
                        </div>

                        <img src="{{ asset('storage/app/public/logo/logo.png') }}"
                            alt="Logo"
                            class="relative z-10 h-20 w-20 object-contain drop-shadow-[0_0_30px_rgba(59,130,246,0.6)]">
                    </div>

                    {{-- BUTTON --}}
                    <a href="{{ route('profile.edit') }}"
                        class="group relative overflow-hidden rounded-2xl border border-blue-500/40 bg-gradient-to-r from-blue-600 to-blue-500 px-5 py-3 text-sm font-bold tracking-wide text-white transition-all duration-300 hover:-translate-y-1 hover:shadow-[0_0_40px_rgba(59,130,246,0.4)] hover:border-blue-400/50">
                        
                        {{-- Button glow effect --}}
                        <div class="absolute inset-0 bg-gradient-to-r from-white/0 via-white/10 to-white/0 translate-x-[-100%] group-hover:translate-x-[100%] transition-transform duration-500"></div>
                        
                        <span class="relative z-10 flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                            </svg>
                            Edit Profile
                        </span>
                    </a>

                </div>

            </div>
        </div>

        {{-- PROFILE CARD --}}
        <div
            class="reveal reveal-delay-1 overflow-hidden rounded-[34px] border border-white/10 bg-[#0B1220]/95 p-7 shadow-[0_0_60px_rgba(37,99,235,0.1),0_0_120px_rgba(37,99,235,0.05)] backdrop-blur-2xl glass-effect">

            {{-- PROFILE HEADER --}}
            <div
                class="flex flex-col gap-6 border-b border-white/10 pb-7 md:flex-row md:items-center">

                {{-- AVATAR --}}
                <div
                    class="relative flex h-28 w-28 items-center justify-center overflow-hidden rounded-full bg-gradient-to-br from-blue-500 via-cyan-400 to-orange-400 text-3xl font-black text-white shadow-[0_0_40px_rgba(59,130,246,0.4),0_0_80px_rgba(59,130,246,0.2)]">
                    
                    {{-- Avatar ring animation --}}
                    <div class="absolute inset-0 rounded-full border-2 border-white/20 animate-spin-slow"></div>

                    <img src="{{ $user->avatar_url }}"
                        alt="Foto profil {{ $user->name }}"
                        class="relative z-10 h-full w-full object-cover">

                    <div
                        class="absolute inset-0 rounded-full border border-white/30">
                    </div>
                </div>

                {{-- USER INFO --}}
                <div class="flex-1">

                    <h2 class="text-3xl font-black text-white">
                        {{ $user->name }}
                    </h2>

                    <p class="mt-2 text-sm text-slate-400/90">
                        <span class="inline-block mr-2">📧</span> {{ $user->email }}
                    </p>

                    @if($user->phone)
                    <p class="mt-1 text-sm text-slate-400/90">
                        <span class="inline-block mr-2">📱</span> {{ $user->phone }}
                    </p>
                    @endif

                    <div class="mt-4">

                        <span
                            class="inline-flex items-center gap-2 rounded-full border border-emerald-500/30 bg-emerald-500/15 px-4 py-2 text-xs font-bold tracking-wide text-emerald-300 shadow-[0_0_20px_rgba(52,211,153,0.2)]">

                            <span class="h-2 w-2 rounded-full bg-emerald-400 animate-pulse shadow-[0_0_10px_rgba(52,211,153,0.5)]"></span>
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
                    class="group rounded-2xl border border-white/5 bg-white/[0.03] p-6 transition-all duration-300 hover:-translate-y-1 hover:border-blue-500/30 hover:bg-blue-500/[0.05] hover:shadow-[0_0_30px_rgba(59,130,246,0.15)]">

                    <div class="flex items-center gap-3 mb-2">
                        <div class="p-2 rounded-lg bg-blue-500/10 border border-blue-500/20">
                            <svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <p class="text-xs font-semibold uppercase tracking-[0.18em] text-blue-300">
                            Email Address
                        </p>
                    </div>

                    <p class="mt-2 text-lg font-bold text-white group-hover:text-blue-200 transition-colors">
                        {{ $user->email }}
                    </p>
                </div>

                {{-- FULL NAME --}}
                <div
                    class="group rounded-2xl border border-white/5 bg-white/[0.03] p-6 transition-all duration-300 hover:-translate-y-1 hover:border-orange-500/30 hover:bg-orange-500/[0.05] hover:shadow-[0_0_30px_rgba(251,146,60,0.15)]">

                    <div class="flex items-center gap-3 mb-2">
                        <div class="p-2 rounded-lg bg-orange-500/10 border border-orange-500/20">
                            <svg class="w-5 h-5 text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        </div>
                        <p class="text-xs font-semibold uppercase tracking-[0.18em] text-orange-300">
                            Full Name
                        </p>
                    </div>

                    <p class="mt-2 text-lg font-bold text-white group-hover:text-orange-200 transition-colors">
                        {{ $user->name }}
                    </p>
                </div>

                {{-- MEMBER SINCE --}}
                <div
                    class="group rounded-2xl border border-white/5 bg-white/[0.03] p-6 transition-all duration-300 hover:-translate-y-1 hover:border-cyan-500/30 hover:bg-cyan-500/[0.05] hover:shadow-[0_0_30px_rgba(34,211,238,0.15)]">

                    <div class="flex items-center gap-3 mb-2">
                        <div class="p-2 rounded-lg bg-cyan-500/10 border border-cyan-500/20">
                            <svg class="w-5 h-5 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <p class="text-xs font-semibold uppercase tracking-[0.18em] text-cyan-300">
                            Member Since
                        </p>
                    </div>

                    <p class="mt-2 text-lg font-bold text-white group-hover:text-cyan-200 transition-colors">
                        {{ $user->created_at->format('d M Y') }}
                    </p>
                </div>

                {{-- STATUS --}}
                <div
                    class="group rounded-2xl border border-white/5 bg-white/[0.03] p-6 transition-all duration-300 hover:-translate-y-1 hover:border-emerald-500/30 hover:bg-emerald-500/[0.05] hover:shadow-[0_0_30px_rgba(52,211,153,0.15)]">

                    <div class="flex items-center gap-3 mb-2">
                        <div class="p-2 rounded-lg bg-emerald-500/10 border border-emerald-500/20">
                            <svg class="w-5 h-5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <p class="text-xs font-semibold uppercase tracking-[0.18em] text-emerald-300">
                            Account Status
                        </p>
                    </div>

                    <div class="mt-3">

                        <span
                            class="inline-flex items-center gap-2 rounded-full border border-emerald-500/30 bg-emerald-500/15 px-4 py-2 text-sm font-bold text-emerald-300 shadow-[0_0_15px_rgba(52,211,153,0.2)]">

                            <span class="h-2 w-2 rounded-full bg-emerald-400 animate-pulse shadow-[0_0_10px_rgba(52,211,153,0.5)]"></span>
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
                        class="rounded-full border border-blue-500/30 bg-blue-500/15 px-3 py-1 text-[11px] font-bold text-blue-300 shadow-[0_0_15px_rgba(59,130,246,0.2)]">

                        USER DATA
                    </div>
                </div>

                <div class="mt-5 grid gap-5 md:grid-cols-2">

                    @if($user->userProfile->phone_verified_at)

                    <div
                        class="group rounded-2xl border border-emerald-500/20 bg-emerald-500/[0.05] p-6 transition-all duration-300 hover:-translate-y-1 hover:border-emerald-400/30 hover:shadow-[0_0_30px_rgba(52,211,153,0.15)]">

                        <div class="flex items-center gap-3 mb-2">
                            <div class="p-2 rounded-lg bg-emerald-500/10 border border-emerald-500/20">
                                <svg class="w-5 h-5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                </svg>
                            </div>
                            <p class="text-xs font-semibold uppercase tracking-[0.18em] text-emerald-300">
                                Phone Verified
                            </p>
                        </div>

                        <div class="mt-3">

                            <span
                                class="inline-flex items-center gap-2 rounded-full border border-emerald-500/30 bg-emerald-500/15 px-4 py-2 text-sm font-bold text-emerald-300 shadow-[0_0_15px_rgba(52,211,153,0.2)]">

                                <span class="h-2 w-2 rounded-full bg-emerald-400 animate-pulse shadow-[0_0_10px_rgba(52,211,153,0.5)]"></span>
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
@extends('layouts.app')

@section('title', 'My Profile')

@section('content')
<div class="min-h-screen bg-[#050816] px-4 pt-28 pb-14">

    {{-- ═══════════════════════════════════════ --}}
    {{-- ANIMATED BACKGROUND ELEMENTS          --}}
    {{-- ═══════════════════════════════════════ --}}
    <div class="pointer-events-none fixed inset-0 z-0 overflow-hidden">
        <div class="absolute -top-40 -right-40 h-[380px] w-[380px] rounded-full bg-blue-500/10 blur-3xl"></div>
        <div class="absolute bottom-[-180px] left-[-120px] h-[360px] w-[360px] rounded-full bg-orange-500/10 blur-3xl"></div>
        <div class="absolute top-1/2 left-1/2 h-[260px] w-[260px] -translate-x-1/2 -translate-y-1/2 rounded-full bg-cyan-500/5 blur-3xl"></div>
    </div>

    <div class="relative z-10 mx-auto max-w-6xl opacity-0 translate-y-8 animate-[fadeReveal_.9s_ease-out_forwards]">

        {{-- HEADER --}}
        <div class="relative overflow-hidden rounded-[30px] border border-blue-500/20 bg-gradient-to-br from-[#060816] via-[#091225] to-[#0B1730] p-8 shadow-[0_0_80px_rgba(37,99,235,0.08)]">
            <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_right,rgba(37,99,235,0.18),transparent_35%)]"></div>
            <div class="absolute -top-20 right-0 h-64 w-64 rounded-full bg-blue-500/10 blur-3xl"></div>

            <div class="relative z-10 flex flex-col gap-7 lg:flex-row lg:items-center lg:justify-between">
                <div class="max-w-2xl">
                    <div class="inline-flex items-center gap-2 rounded-full border border-blue-500/30 bg-blue-500/10 px-4 py-2 text-[11px] font-bold tracking-[0.18em] text-blue-300">
                        <span class="h-2 w-2 rounded-full bg-emerald-400 animate-pulse"></span>
                        ACCOUNT PROFILE
                    </div>
                    <h1 class="mt-5 text-4xl font-black leading-tight text-white md:text-5xl">My Profile</h1>
                    <p class="mt-4 max-w-xl text-sm leading-relaxed text-slate-300 md:text-[15px]">
                        Kelola informasi akun dan lihat detail profil akun Lapak Gaming milikmu dengan tampilan yang lebih fresh.
                    </p>
                </div>
                
                {{-- LOGO --}}
                <div class="hidden lg:flex h-[120px] w-[120px] items-center justify-center rounded-full border border-blue-500/20 bg-blue-500/5 backdrop-blur-2xl">
                    <div class="absolute h-[160px] w-[160px] rounded-full bg-blue-500/10 blur-3xl"></div>
                    <img src="{{ asset('storage/app/public/logo/logo.png') }}" alt="Logo" class="relative z-10 h-20 w-20 object-contain drop-shadow-[0_0_25px_rgba(59,130,246,0.55)]">
                </div>
            </div>
        </div>

        {{-- MAIN CONTENT GRID --}}
        <div class="mt-8 grid gap-6 lg:grid-cols-[0.95fr_1.05fr]">

            {{-- CARD 1: PROFILE INFO --}}
            <section class="rounded-[30px] border border-blue-500/20 bg-[#0B1220]/95 p-8 shadow-[0_0_50px_rgba(37,99,235,0.05)] backdrop-blur-xl">
                
                {{-- PROFILE HEADER --}}
                <div class="flex items-center gap-6 border-b border-white/10 pb-7">
                    <div class="relative flex h-24 w-24 items-center justify-center overflow-hidden rounded-full border-2 border-blue-500/20 bg-gradient-to-br from-blue-500 via-cyan-400 to-orange-400 shadow-[0_0_35px_rgba(59,130,246,0.35)]">
                        <img src="{{ $user->avatar_url }}" alt="Foto profil {{ $user->name }}" class="h-full w-full object-cover">
                    </div>
                    <div>
                        <h2 class="text-2xl font-black text-white">{{ $user->name }}</h2>
                        <p class="text-sm text-slate-400 mt-1">{{ $user->email }}</p>
                        @if($user->phone)
                        <p class="text-sm text-slate-400 mt-1">{{ $user->phone }}</p>
                        @endif
                        <span class="mt-3 inline-flex items-center gap-2 rounded-full border border-emerald-500/20 bg-emerald-500/10 px-3 py-1 text-[11px] font-bold text-emerald-300">
                            <span class="h-1.5 w-1.5 rounded-full bg-emerald-400 animate-pulse"></span> ACTIVE MEMBER
                        </span>
                    </div>
                </div>

                {{-- DETAIL INFO --}}
                <div class="mt-7 space-y-4">
                    <div class="rounded-2xl border border-white/5 bg-white/[0.03] p-5 transition duration-300 hover:-translate-y-1 hover:border-blue-500/20 hover:bg-blue-500/[0.03]">
                        <p class="text-[10px] font-bold uppercase tracking-[0.18em] text-blue-300">Email Address</p>
                        <p class="mt-2 text-sm font-bold text-white">{{ $user->email }}</p>
                    </div>
                    <div class="rounded-2xl border border-white/5 bg-white/[0.03] p-5 transition duration-300 hover:-translate-y-1 hover:border-orange-500/20 hover:bg-orange-500/[0.03]">
                        <p class="text-[10px] font-bold uppercase tracking-[0.18em] text-orange-300">Full Name</p>
                        <p class="mt-2 text-sm font-bold text-white">{{ $user->name }}</p>
                    </div>
                </div>
            </section>

            {{-- CARD 2: ACCOUNT METRICS --}}
            <section class="rounded-[30px] border border-blue-500/20 bg-[#0B1220]/95 p-8 shadow-[0_0_50px_rgba(37,99,235,0.05)] backdrop-blur-xl">
                
                {{-- EDIT BUTTON MOBILE --}}
                <div class="flex items-center justify-between mb-6 lg:hidden">
                    <h3 class="text-xl font-black text-white">Account Overview</h3>
                    <a href="{{ route('profile.edit') }}" class="rounded-xl border border-blue-500/30 bg-blue-600 px-4 py-2 text-sm font-bold text-white transition hover:-translate-y-1 hover:shadow-[0_0_30px_rgba(59,130,246,0.35)]">
                        Edit Profile
                    </a>
                </div>

                <div class="hidden lg:flex items-center justify-between mb-6">
                    <h3 class="text-xl font-black text-white">Account Overview</h3>
                    <a href="{{ route('profile.edit') }}" class="group flex items-center gap-2 rounded-2xl border border-blue-500/30 bg-gradient-to-r from-blue-600 to-blue-500 px-5 py-3 text-sm font-bold text-white transition hover:-translate-y-1 hover:shadow-[0_0_30px_rgba(59,130,246,0.35)]">
                        Edit Profile
                    </a>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div class="rounded-2xl border border-white/5 bg-white/[0.03] p-5 text-center transition duration-300 hover:-translate-y-1 hover:border-cyan-500/20 hover:bg-cyan-500/[0.03]">
                        <p class="text-[10px] font-bold uppercase text-slate-500">Member Since</p>
                        <p class="mt-2 font-bold text-white">{{ $user->created_at->format('d M Y') }}</p>
                    </div>
                    <div class="rounded-2xl border border-white/5 bg-white/[0.03] p-5 text-center transition duration-300 hover:-translate-y-1 hover:border-emerald-500/20 hover:bg-emerald-500/[0.03]">
                        <p class="text-[10px] font-bold uppercase text-slate-500">Status</p>
                        <span class="mt-2 inline-flex items-center gap-1.5 rounded-full border border-emerald-500/20 bg-emerald-500/10 px-3 py-1 text-xs font-bold text-emerald-300">
                            <span class="h-1.5 w-1.5 rounded-full bg-emerald-400 animate-pulse"></span> Active
                        </span>
                    </div>
                </div>

                {{-- ADDITIONAL INFO --}}
                @if($user->userProfile)
                <div class="mt-6 space-y-4">
                    @if($user->userProfile->phone_verified_at)
                    <div class="rounded-2xl border border-emerald-500/10 bg-emerald-500/[0.03] p-5">
                        <p class="text-[10px] font-bold uppercase tracking-[0.18em] text-emerald-300">Phone Verified</p>
                        <span class="mt-2 inline-flex items-center gap-2 rounded-full border border-emerald-500/20 bg-emerald-500/10 px-3 py-1 text-xs font-bold text-emerald-300">
                            <span class="h-1.5 w-1.5 rounded-full bg-emerald-400 animate-pulse"></span> Verified
                        </span>
                    </div>
                    @endif

                    @if($user->userProfile->bio)
                    <div class="rounded-2xl border border-blue-500/10 bg-blue-500/5 p-5">
                        <p class="text-[10px] font-bold uppercase tracking-[0.18em] text-blue-300 mb-2">Bio</p>
                        <p class="text-sm text-slate-300 italic">"{{ $user->userProfile->bio }}"</p>
                    </div>
                    @endif
                </div>
                @endif
            </section>

        </div>
    </div>
</div>

<style>
    @keyframes fadeReveal {
        0% { opacity: 0; transform: translateY(35px); }
        100% { opacity: 1; transform: translateY(0); }
    }
</style>
@endsection
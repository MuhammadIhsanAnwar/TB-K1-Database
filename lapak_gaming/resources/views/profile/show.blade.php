@extends('layouts.app')

@section('content')
<div class="relative overflow-hidden bg-[#050816] px-4 py-14">


    {{-- REVEAL ANIMATION --}}
    <style>
        .reveal{
            opacity:0;
            transform:translateY(45px);
            animation:revealUp .9s cubic-bezier(.22,1,.36,1) forwards;
        }

        .reveal-delay-1{
            animation-delay:.12s;
        }

        .reveal-delay-2{
            animation-delay:.22s;
        }

        .reveal-delay-3{
            animation-delay:.32s;
        }

        @keyframes revealUp{
            to{
                opacity:1;
                transform:translateY(0);
            }
        }

        select option{
            background:#111827;
            color:white;
        }
    </style>

    <div class="relative z-10 mx-auto max-w-5xl space-y-7">

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
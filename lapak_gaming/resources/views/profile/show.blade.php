@extends('layouts.app')

@section('title', 'My Profile')

@section('content')
<div class="min-h-screen px-4 pt-28 pb-14">

    <div class="mx-auto max-w-6xl opacity-0 translate-y-8 animate-[fadeReveal_.9s_ease-out_forwards]">

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
                
                {{-- BUTTON EDIT --}}
                <a href="{{ route('profile.edit') }}" class="group hidden lg:flex items-center gap-2 rounded-2xl border border-blue-500/30 bg-blue-600 px-6 py-4 font-bold text-white transition hover:-translate-y-1 hover:shadow-[0_0_30px_rgba(59,130,246,0.35)]">
                    Edit Profile
                </a>
            </div>
        </div>

        {{-- MAIN CONTENT GRID --}}
        <div class="mt-8 grid gap-6 lg:grid-cols-[0.95fr_1.05fr]">

            {{-- CARD 1: PROFILE INFO --}}
            <section class="rounded-[30px] border border-blue-500/20 bg-[#0B1220]/95 p-8 shadow-[0_0_50px_rgba(37,99,235,0.05)] backdrop-blur-xl">
                <div class="flex items-center gap-6">
                    <div class="relative flex h-24 w-24 items-center justify-center overflow-hidden rounded-full border-2 border-blue-500/20 bg-gradient-to-br from-blue-500 to-cyan-400 shadow-[0_0_35px_rgba(59,130,246,0.35)]">
                        <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}" class="h-full w-full object-cover">
                    </div>
                    <div>
                        <h2 class="text-2xl font-black text-white">{{ $user->name }}</h2>
                        <p class="text-sm text-slate-400 mt-1">{{ $user->email }}</p>
                        <span class="mt-3 inline-flex items-center gap-2 rounded-full border border-emerald-500/20 bg-emerald-500/10 px-3 py-1 text-[11px] font-bold text-emerald-300">
                            <span class="h-1.5 w-1.5 rounded-full bg-emerald-400"></span> ACTIVE MEMBER
                        </span>
                    </div>
                </div>

                <div class="mt-8 space-y-4">
                    <div class="rounded-2xl border border-white/5 bg-white/[0.03] p-5">
                        <p class="text-[10px] font-bold uppercase tracking-[0.18em] text-blue-300">Email Address</p>
                        <p class="mt-2 text-sm font-bold text-white">{{ $user->email }}</p>
                    </div>
                    <div class="rounded-2xl border border-white/5 bg-white/[0.03] p-5">
                        <p class="text-[10px] font-bold uppercase tracking-[0.18em] text-orange-300">Phone Number</p>
                        <p class="mt-2 text-sm font-bold text-white">{{ $user->phone ?? 'Not set' }}</p>
                    </div>
                </div>
            </section>

            {{-- CARD 2: ACCOUNT METRICS --}}
            <section class="rounded-[30px] border border-blue-500/20 bg-[#0B1220]/95 p-8 shadow-[0_0_50px_rgba(37,99,235,0.05)] backdrop-blur-xl">
                <h3 class="text-xl font-black text-white">Account Overview</h3>
                <div class="mt-6 grid grid-cols-2 gap-4">
                    <div class="rounded-2xl border border-white/5 bg-white/[0.03] p-5 text-center">
                        <p class="text-[10px] font-bold uppercase text-slate-500">Member Since</p>
                        <p class="mt-2 font-bold text-white">{{ $user->created_at->format('d M Y') }}</p>
                    </div>
                    <div class="rounded-2xl border border-white/5 bg-white/[0.03] p-5 text-center">
                        <p class="text-[10px] font-bold uppercase text-slate-500">Verified</p>
                        <p class="mt-2 font-bold text-emerald-400">{{ $user->email_verified_at ? 'Yes' : 'No' }}</p>
                    </div>
                </div>

                @if($user->userProfile)
                <div class="mt-6 rounded-2xl border border-blue-500/10 bg-blue-500/5 p-6">
                    <p class="text-sm text-slate-300 italic">"{{ $user->userProfile->bio ?? 'No bio available.' }}"</p>
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
@extends('layouts.app')

@section('content')

<div class="relative overflow-hidden px-4 pb-16 pt-28">

    {{-- BACKGROUND EFFECT --}}
    <div class="pointer-events-none absolute inset-0 overflow-hidden">
        <div class="absolute top-[-120px] right-[-120px] h-[320px] w-[320px] rounded-full bg-blue-500/10 blur-3xl"></div>

        <div class="absolute bottom-[-140px] left-[-120px] h-[320px] w-[320px] rounded-full bg-orange-500/10 blur-3xl"></div>
    </div>

    <div class="relative z-10 mx-auto grid max-w-6xl gap-8 lg:grid-cols-[280px_minmax(0,1fr)]">

        {{-- SIDEBAR --}}
        <aside
            class="reveal-up h-fit rounded-[30px] border border-white/10 bg-[#0B1220]/95 p-6 backdrop-blur-xl">

            <div class="mb-8 text-center">

                <div
                    class="mx-auto flex h-24 w-24 items-center justify-center overflow-hidden rounded-full border border-blue-500/20 bg-blue-500/10">

                    <img src="{{ $user->avatar_url }}"
                        alt="{{ $user->name }}"
                        class="h-full w-full object-cover">
                </div>

                <h2 class="mt-4 text-xl font-black text-white">
                    {{ $user->name }}
                </h2>

                <p class="mt-1 text-sm text-slate-400">
                    {{ $user->email }}
                </p>
            </div>

            <nav class="space-y-3">

                <a href="{{ route('settings.profile') }}"
                    class="block rounded-2xl px-4 py-3 text-sm font-semibold transition duration-300
                    {{ $selectedTab === 'profile'
                        ? 'border border-blue-500/20 bg-blue-500/10 text-white'
                        : 'border border-transparent text-slate-400 hover:border-white/10 hover:bg-white/[0.03] hover:text-white' }}">

                    Edit Profil
                </a>

                <a href="{{ route('settings.account') }}"
                    class="block rounded-2xl px-4 py-3 text-sm font-semibold transition duration-300
                    {{ $selectedTab === 'account'
                        ? 'border border-blue-500/20 bg-blue-500/10 text-white'
                        : 'border border-transparent text-slate-400 hover:border-white/10 hover:bg-white/[0.03] hover:text-white' }}">

                    Pengaturan Akun
                </a>

                <a href="{{ route('settings.password') }}"
                    class="block rounded-2xl px-4 py-3 text-sm font-semibold transition duration-300
                    {{ $selectedTab === 'password'
                        ? 'border border-blue-500/20 bg-blue-500/10 text-white'
                        : 'border border-transparent text-slate-400 hover:border-white/10 hover:bg-white/[0.03] hover:text-white' }}">

                    Ubah Password
                </a>

                <a href="{{ route('settings.seller') }}"
                    class="block rounded-2xl px-4 py-3 text-sm font-semibold transition duration-300
                    {{ $selectedTab === 'seller'
                        ? 'border border-blue-500/20 bg-blue-500/10 text-white'
                        : 'border border-transparent text-slate-400 hover:border-white/10 hover:bg-white/[0.03] hover:text-white' }}">

                    Daftar Jadi Seller
                </a>

            </nav>
        </aside>

        {{-- MAIN CONTENT --}}
        <main
            class="reveal-up rounded-[30px] border border-white/10 bg-[#0B1220]/95 p-8 backdrop-blur-xl">

            {{-- ALERT --}}
            @if (session('success'))
                <div
                    class="mb-6 rounded-2xl border border-emerald-500/20 bg-emerald-500/10 p-4 text-sm text-emerald-200">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('status'))
                <div
                    class="mb-6 rounded-2xl border border-cyan-500/20 bg-cyan-500/10 p-4 text-sm text-cyan-200">
                    {{ session('status') }}
                </div>
            @endif

            @if (session('warning'))
                <div
                    class="mb-6 rounded-2xl border border-orange-500/20 bg-orange-500/10 p-4 text-sm text-orange-200">
                    {{ session('warning') }}
                </div>
            @endif

            @if ($errors->any())
                <div
                    class="mb-6 rounded-2xl border border-rose-500/20 bg-rose-500/10 p-4 text-rose-200">

                    <ul class="list-disc space-y-2 pl-5 text-sm">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>

                </div>
            @endif

            {{-- PROFILE --}}
            @if ($selectedTab === 'profile')

                <div class="mb-8">

                    <div
                        class="inline-flex items-center gap-2 rounded-full border border-blue-500/20 bg-blue-500/10 px-4 py-2 text-[11px] font-bold tracking-[0.2em] text-blue-300">

                        <span class="h-2 w-2 rounded-full bg-emerald-400"></span>
                        PROFILE SETTINGS
                    </div>

                    <h1 class="mt-4 text-3xl font-black text-white">
                        Edit Profil
                    </h1>

                    <p class="mt-3 max-w-2xl text-sm leading-relaxed text-slate-400">
                        Kelola informasi akun dan data profil Anda dengan tampilan modern.
                    </p>
                </div>

                <form action="{{ route('settings.update') }}"
                    method="POST"
                    enctype="multipart/form-data"
                    class="space-y-6">

                    @csrf
                    @method('PUT')

                    <div>

                        <label class="mb-2 block text-sm font-semibold text-slate-300">
                            Foto Profil
                        </label>

                        <input type="file"
                            name="profile_photo"
                            id="profile_photo"
                            accept="image/*"
                            class="w-full rounded-2xl border border-white/10 bg-white/[0.03] px-4 py-3 text-slate-200 file:mr-4 file:rounded-xl file:border-0 file:bg-blue-500/20 file:px-4 file:py-2 file:text-sm file:font-semibold file:text-blue-300">
                    </div>

                    <div>

                        <label class="mb-2 block text-sm font-semibold text-slate-300">
                            Nama Lengkap
                        </label>

                        <input type="text"
                            name="name"
                            value="{{ old('name', $user->name) }}"
                            required
                            class="w-full rounded-2xl border border-white/10 bg-white/[0.03] px-4 py-3 text-white outline-none transition duration-300 focus:border-blue-500/40 focus:bg-blue-500/[0.03]">
                    </div>

                    <div>

                        <label class="mb-2 block text-sm font-semibold text-slate-300">
                            Nomor Telepon
                        </label>

                        <input type="text"
                            name="phone"
                            value="{{ old('phone', $profile?->phone ?? $user->phone) }}"
                            placeholder="08xxxxxxxxxx"
                            class="w-full rounded-2xl border border-white/10 bg-white/[0.03] px-4 py-3 text-white outline-none transition duration-300 focus:border-blue-500/40 focus:bg-blue-500/[0.03]">
                    </div>

                    <div class="grid gap-5 lg:grid-cols-2">

                        <div>

                            <label class="mb-2 block text-sm font-semibold text-slate-300">
                                Jenis Kelamin
                            </label>

                            <select name="gender"
                                class="w-full rounded-2xl border border-white/10 bg-white/[0.03] px-4 py-3 text-white outline-none transition duration-300 focus:border-blue-500/40">

                                <option value="">Pilih</option>

                                <option value="male"
                                    @selected(old('gender', $profile?->gender) === 'male')>
                                    Laki-laki
                                </option>

                                <option value="female"
                                    @selected(old('gender', $profile?->gender) === 'female')>
                                    Perempuan
                                </option>

                                <option value="other"
                                    @selected(old('gender', $profile?->gender) === 'other')>
                                    Lainnya
                                </option>
                            </select>
                        </div>

                        <div>

                            <label class="mb-2 block text-sm font-semibold text-slate-300">
                                Tanggal Lahir
                            </label>

                            <input type="date"
                                name="birth_date"
                                value="{{ old('birth_date', optional($profile?->birth_date)->format('Y-m-d')) }}"
                                class="w-full rounded-2xl border border-white/10 bg-white/[0.03] px-4 py-3 text-white outline-none transition duration-300 focus:border-blue-500/40">
                        </div>

                    </div>

                    <button type="submit"
                        class="w-full group rounded-2xl border border-blue-500/30 bg-gradient-to-r from-blue-600 to-blue-500 px-5 py-3 text-sm font-bold tracking-wide text-white transition duration-300 hover:-translate-y-1 hover:shadow-[0_0_30px_rgba(59,130,246,0.35)]">

                        Simpan Perubahan Profil
                    </button>

                </form>

            @endif

        </main>
    </div>
</div>

{{-- REVEAL ANIMATION --}}
<style>
.reveal-up{
    opacity:0;
    transform:translateY(45px);
    animation:revealUp .9s cubic-bezier(.22,1,.36,1) forwards;
}

.reveal-up:nth-child(2){
    animation-delay:.08s;
}

@keyframes revealUp{
    to{
        opacity:1;
        transform:translateY(0);
    }
}
</style>

@endsection
@extends('layouts.app')

@section('content')
<div class="relative min-h-screen overflow-hidden bg-[#060816] px-4 pb-16 pt-32">

    {{-- BACKGROUND EFFECT --}}
    <div class="pointer-events-none absolute inset-0 overflow-hidden">
        <div class="absolute top-[-160px] right-[-120px] h-[380px] w-[380px] rounded-full bg-blue-500/10 blur-3xl"></div>
        <div class="absolute bottom-[-180px] left-[-120px] h-[380px] w-[380px] rounded-full bg-orange-500/10 blur-3xl"></div>
    </div>

    <div class="relative z-10 mx-auto max-w-7xl">

        <div class="grid gap-7 lg:grid-cols-[300px_minmax(0,1fr)]">

            {{-- SIDEBAR --}}
            <aside
                class="reveal-up h-fit overflow-hidden rounded-[30px] border border-blue-500/15 bg-[#0B1220]/95 p-6 shadow-[0_0_35px_rgba(37,99,235,0.08)] backdrop-blur-xl">

                {{-- PROFILE --}}
                <div class="text-center">

                    <div
                        class="relative mx-auto flex h-28 w-28 items-center justify-center overflow-hidden rounded-full border border-blue-500/20 bg-gradient-to-br from-blue-500/20 to-orange-500/20 p-[3px]">

                        <img src="{{ $user->avatar_url }}"
                            alt="{{ $user->name }}"
                            class="h-full w-full rounded-full object-cover">
                    </div>

                    <h2 class="mt-5 text-xl font-black text-white">
                        {{ $user->name }}
                    </h2>

                    <p class="mt-1 text-sm text-slate-400">
                        {{ $user->email }}
                    </p>

                </div>

                {{-- NAVIGATION --}}
                <nav class="mt-8 space-y-3">

                    <a href="{{ route('settings.profile') }}"
                        class="group flex items-center justify-between rounded-2xl border px-4 py-3 text-sm font-semibold transition duration-300
                        {{ $selectedTab === 'profile'
                            ? 'border-blue-500/30 bg-blue-500/10 text-white shadow-[0_0_25px_rgba(37,99,235,0.12)]'
                            : 'border-white/5 bg-white/[0.02] text-slate-400 hover:border-blue-500/20 hover:bg-blue-500/[0.04] hover:text-white' }}">

                        <span>Edit Profil</span>

                        @if($selectedTab === 'profile')
                            <span class="h-2 w-2 rounded-full bg-blue-400"></span>
                        @endif
                    </a>

                    <a href="{{ route('settings.account') }}"
                        class="group flex items-center justify-between rounded-2xl border px-4 py-3 text-sm font-semibold transition duration-300
                        {{ $selectedTab === 'account'
                            ? 'border-orange-500/30 bg-orange-500/10 text-white shadow-[0_0_25px_rgba(249,115,22,0.12)]'
                            : 'border-white/5 bg-white/[0.02] text-slate-400 hover:border-orange-500/20 hover:bg-orange-500/[0.04] hover:text-white' }}">

                        <span>Pengaturan Akun</span>

                        @if($selectedTab === 'account')
                            <span class="h-2 w-2 rounded-full bg-orange-400"></span>
                        @endif
                    </a>

                    <a href="{{ route('settings.password') }}"
                        class="group flex items-center justify-between rounded-2xl border px-4 py-3 text-sm font-semibold transition duration-300
                        {{ $selectedTab === 'password'
                            ? 'border-emerald-500/30 bg-emerald-500/10 text-white shadow-[0_0_25px_rgba(16,185,129,0.12)]'
                            : 'border-white/5 bg-white/[0.02] text-slate-400 hover:border-emerald-500/20 hover:bg-emerald-500/[0.04] hover:text-white' }}">

                        <span>Ubah Password</span>

                        @if($selectedTab === 'password')
                            <span class="h-2 w-2 rounded-full bg-emerald-400"></span>
                        @endif
                    </a>

                    <a href="{{ route('settings.seller') }}"
                        class="group flex items-center justify-between rounded-2xl border px-4 py-3 text-sm font-semibold transition duration-300
                        {{ $selectedTab === 'seller'
                            ? 'border-amber-500/30 bg-amber-500/10 text-white shadow-[0_0_25px_rgba(245,158,11,0.12)]'
                            : 'border-white/5 bg-white/[0.02] text-slate-400 hover:border-amber-500/20 hover:bg-amber-500/[0.04] hover:text-white' }}">

                        <span>Daftar Jadi Seller</span>

                        @if($selectedTab === 'seller')
                            <span class="h-2 w-2 rounded-full bg-amber-400"></span>
                        @endif
                    </a>

                </nav>
            </aside>

            {{-- MAIN CONTENT --}}
            <main
                class="reveal-up overflow-hidden rounded-[32px] border border-blue-500/15 bg-[#0B1220]/95 p-7 shadow-[0_0_40px_rgba(37,99,235,0.08)] backdrop-blur-xl">

                {{-- ALERT --}}
                @if (session('success'))
                    <div class="mb-6 rounded-2xl border border-emerald-500/20 bg-emerald-500/10 p-4 text-sm font-medium text-emerald-200">
                        {{ session('success') }}
                    </div>
                @endif

                @if (session('status'))
                    <div class="mb-6 rounded-2xl border border-cyan-500/20 bg-cyan-500/10 p-4 text-sm font-medium text-cyan-200">
                        {{ session('status') }}
                    </div>
                @endif

                @if (session('warning'))
                    <div class="mb-6 rounded-2xl border border-amber-500/20 bg-amber-500/10 p-4 text-sm font-medium text-amber-200">
                        {{ session('warning') }}
                    </div>
                @endif

                @if ($errors->any())
                    <div class="mb-6 rounded-2xl border border-rose-500/20 bg-rose-500/10 p-4 text-rose-200">
                        <ul class="list-disc list-inside space-y-2 text-sm">
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
                            class="inline-flex items-center gap-2 rounded-full border border-blue-500/30 bg-blue-500/10 px-4 py-2 text-[11px] font-bold tracking-[0.2em] text-blue-300">
                            <span class="h-2 w-2 rounded-full bg-blue-400"></span>
                            PROFILE SETTINGS
                        </div>

                        <h1 class="mt-4 text-3xl font-black text-white">
                            Edit Profil
                        </h1>

                        <p class="mt-3 text-sm leading-relaxed text-slate-400">
                            Kelola informasi akun dan data pribadi Anda dengan aman.
                        </p>
                    </div>

                    <form action="{{ route('settings.update') }}"
                        method="POST"
                        enctype="multipart/form-data"
                        class="space-y-6">

                        @csrf
                        @method('PUT')

                        <div class="grid gap-6 lg:grid-cols-2">

                            <div>
                                <label class="mb-2 block text-sm font-semibold text-slate-300">
                                    Foto Profil
                                </label>

                                <input type="file"
                                    name="profile_photo"
                                    id="profile_photo"
                                    accept="image/*"
                                    class="w-full rounded-2xl border border-white/5 bg-white/[0.03] px-4 py-3 text-slate-300 file:mr-4 file:rounded-xl file:border-0 file:bg-blue-500 file:px-4 file:py-2 file:text-sm file:font-semibold file:text-white hover:border-blue-500/20">
                            </div>

                            <div>
                                <label class="mb-2 block text-sm font-semibold text-slate-300">
                                    Nama Lengkap
                                </label>

                                <input type="text"
                                    name="name"
                                    id="name"
                                    value="{{ old('name', $user->name) }}"
                                    required
                                    class="w-full rounded-2xl border border-white/5 bg-white/[0.03] px-4 py-3 text-white outline-none transition focus:border-blue-500/30">
                            </div>

                        </div>

                        <div>
                            <label class="mb-2 block text-sm font-semibold text-slate-300">
                                Nomor Telepon
                            </label>

                            <input type="text"
                                name="phone"
                                id="phone"
                                value="{{ old('phone', $profile?->phone ?? $user->phone) }}"
                                placeholder="08xxxxxxxxxx"
                                class="w-full rounded-2xl border border-white/5 bg-white/[0.03] px-4 py-3 text-white outline-none transition focus:border-blue-500/30">
                        </div>

                        <div class="grid gap-6 lg:grid-cols-2">

                            <div>
                                <label class="mb-2 block text-sm font-semibold text-slate-300">
                                    Jenis Kelamin
                                </label>

                                <select name="gender"
                                    id="gender"
                                    class="w-full rounded-2xl border border-white/5 bg-white/[0.03] px-4 py-3 text-white outline-none transition focus:border-blue-500/30">

                                    <option value="">Pilih</option>

                                    <option value="male" @selected(old('gender', $profile?->gender) === 'male')>
                                        Laki-laki
                                    </option>

                                    <option value="female" @selected(old('gender', $profile?->gender) === 'female')>
                                        Perempuan
                                    </option>

                                    <option value="other" @selected(old('gender', $profile?->gender) === 'other')>
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
                                    id="birth_date"
                                    value="{{ old('birth_date', optional($profile?->birth_date)->format('Y-m-d')) }}"
                                    class="w-full rounded-2xl border border-white/5 bg-white/[0.03] px-4 py-3 text-white outline-none transition focus:border-blue-500/30">
                            </div>

                        </div>

                        <button type="submit"
                            class="w-full rounded-2xl bg-gradient-to-r from-blue-600 to-blue-500 px-5 py-3 text-sm font-bold text-white shadow-[0_0_30px_rgba(37,99,235,0.2)] transition duration-300 hover:scale-[1.01] hover:from-blue-500 hover:to-blue-400">

                            Simpan Perubahan Profil
                        </button>

                    </form>

                {{-- ACCOUNT --}}
                @elseif ($selectedTab === 'account')

                    <h1 class="mb-8 text-3xl font-black text-white">
                        Pengaturan Akun
                    </h1>

                    <div class="grid gap-6 lg:grid-cols-2">

                        <section class="rounded-[28px] border border-white/5 bg-white/[0.03] p-6">
                            <h2 class="mb-5 text-xl font-black text-white">
                                Informasi Akun
                            </h2>

                            <div class="space-y-5 text-sm">

                                <div>
                                    <div class="text-slate-500">
                                        Email
                                    </div>

                                    <div class="mt-1 font-semibold text-white">
                                        {{ $user->email }}
                                    </div>
                                </div>

                                <div>
                                    <div class="text-slate-500">
                                        Status Verifikasi
                                    </div>

                                    <div class="mt-1 font-semibold {{ $user->email_verified_at ? 'text-emerald-300' : 'text-amber-300' }}">
                                        {{ $user->email_verified_at ? 'Terverifikasi' : 'Belum Terverifikasi' }}
                                    </div>
                                </div>

                            </div>

                            @if (! $user->email_verified_at)
                                <form action="{{ route('verification.send') }}"
                                    method="POST"
                                    class="mt-6">

                                    @csrf

                                    <button type="submit"
                                        class="w-full rounded-2xl bg-blue-600 px-5 py-3 text-sm font-bold text-white transition hover:bg-blue-500">
                                        Kirim Ulang Email Verifikasi
                                    </button>

                                </form>
                            @endif
                        </section>

                        <section class="rounded-[28px] border border-rose-500/10 bg-rose-500/[0.03] p-6">
                            <h2 class="mb-4 text-xl font-black text-white">
                                Hapus Akun
                            </h2>

                            <p class="mb-6 text-sm leading-relaxed text-slate-400">
                                Sistem akan mengirim kode verifikasi ke email sebelum akun dihapus permanen.
                            </p>

                            <a href="{{ route('settings.account.delete') }}"
                                class="inline-flex w-full items-center justify-center rounded-2xl bg-rose-600 px-5 py-3 text-sm font-bold text-white transition hover:bg-rose-500">

                                Mulai Proses Hapus Akun
                            </a>
                        </section>

                    </div>

                {{-- PASSWORD --}}
                @elseif ($selectedTab === 'password')

                    <h1 class="mb-8 text-3xl font-black text-white">
                        Ubah Password
                    </h1>

                    <div class="grid gap-6 lg:grid-cols-2">

                        <section class="rounded-[28px] border border-white/5 bg-white/[0.03] p-6">

                            <h2 class="mb-4 text-xl font-black text-white">
                                Kirim Kode Verifikasi
                            </h2>

                            <p class="mb-6 text-sm leading-relaxed text-slate-400">
                                Kode verifikasi akan dikirim ke email akun Anda.
                            </p>

                            <form action="{{ route('settings.password.sendCode') }}"
                                method="POST">

                                @csrf

                                <button type="submit"
                                    class="w-full rounded-2xl bg-blue-600 px-5 py-3 text-sm font-bold text-white transition hover:bg-blue-500">

                                    Kirim Kode
                                </button>

                            </form>

                        </section>

                        <section class="rounded-[28px] border border-white/5 bg-white/[0.03] p-6">

                            <h2 class="mb-5 text-xl font-black text-white">
                                Password Baru
                            </h2>

                            <form action="{{ route('settings.password.update') }}"
                                method="POST"
                                class="space-y-5">

                                @csrf
                                @method('PUT')

                                <input type="text"
                                    name="verification_code"
                                    maxlength="6"
                                    placeholder="Kode Verifikasi"
                                    required
                                    class="w-full rounded-2xl border border-white/5 bg-white/[0.03] px-4 py-3 text-white outline-none transition focus:border-emerald-500/30">

                                <input type="password"
                                    name="password"
                                    placeholder="Password Baru"
                                    required
                                    class="w-full rounded-2xl border border-white/5 bg-white/[0.03] px-4 py-3 text-white outline-none transition focus:border-emerald-500/30">

                                <input type="password"
                                    name="password_confirmation"
                                    placeholder="Konfirmasi Password"
                                    required
                                    class="w-full rounded-2xl border border-white/5 bg-white/[0.03] px-4 py-3 text-white outline-none transition focus:border-emerald-500/30">

                                <button type="submit"
                                    class="w-full rounded-2xl bg-emerald-600 px-5 py-3 text-sm font-bold text-white transition hover:bg-emerald-500">

                                    Update Password
                                </button>

                            </form>

                        </section>

                    </div>

                @else

                    {{-- SELLER --}}
                    <h1 class="mb-8 text-3xl font-black text-white">
                        Daftar Jadi Seller
                    </h1>

                    <div class="rounded-[30px] border border-amber-500/10 bg-amber-500/[0.03] p-7">

                        <p class="text-sm leading-relaxed text-slate-300">
                            Aktifkan mode seller untuk mulai membuka toko, menjual item, dan menerima transaksi dari buyer.
                        </p>

                        <div class="mt-6 grid gap-4 sm:grid-cols-2">

                            <a href="{{ route('seller.register.form') }}"
                                class="flex items-center justify-center rounded-2xl bg-amber-500 px-5 py-3 text-sm font-bold text-slate-950 transition hover:bg-amber-400">

                                Ajukan Jadi Seller
                            </a>

                            <a href="{{ route('seller.dashboard') }}"
                                class="flex items-center justify-center rounded-2xl border border-white/10 bg-white/[0.03] px-5 py-3 text-sm font-bold text-white transition hover:border-amber-500/30 hover:bg-amber-500/[0.04]">

                                Dashboard Seller
                            </a>

                        </div>

                    </div>

                @endif

            </main>

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

.reveal-up:nth-child(2){
    animation-delay:.1s;
}

@keyframes revealUp{
    to{
        opacity:1;
        transform:translateY(0);
    }
}
</style>
@endsection
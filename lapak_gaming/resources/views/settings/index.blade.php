@extends('layouts.app')

@section('content')
<div class="relative bg-[#060816] px-4 pb-20 pt-32">

    {{-- BACKGROUND EFFECT --}}
    <div class="pointer-events-none fixed inset-0 -z-10">
    <div class="absolute -top-32 -right-32 h-[340px] w-[340px] rounded-full bg-blue-500/10 blur-3xl"></div>
    <div class="absolute bottom-[-140px] left-[-120px] h-[340px] w-[340px] rounded-full bg-orange-500/10 blur-3xl"></div>
</div>

    <div class="relative z-10 mx-auto max-w-7xl">

        <div class="grid gap-8 lg:grid-cols-[290px_minmax(0,1fr)]">

            {{-- SIDEBAR --}}
            <aside
                class="reveal-up sticky top-28 h-fit overflow-hidden rounded-[32px] border border-white/10 bg-[#0B1220]/90 p-6 backdrop-blur-xl shadow-[0_0_40px_rgba(37,99,235,0.08)]">

                <div
                    class="absolute inset-0 bg-[radial-gradient(circle_at_top_right,rgba(37,99,235,0.14),transparent_35%)]">
                </div>

                <div class="relative z-10">

                    {{-- PROFILE --}}
                    <div class="text-center">

                        <div
                            class="mx-auto flex h-28 w-28 items-center justify-center overflow-hidden rounded-full border border-blue-500/20 bg-[#111827] shadow-[0_0_30px_rgba(37,99,235,0.18)]">

                            <img src="{{ $user->avatar_url }}"
                                alt="{{ $user->name }}"
                                class="h-full w-full object-cover">
                        </div>

                        <h2 class="mt-5 text-xl font-black text-white">
                            {{ $user->name }}
                        </h2>

                        <p class="mt-1 text-sm text-slate-400">
                            {{ $user->email }}
                        </p>

                    </div>

                    {{-- MENU --}}
                    <nav class="mt-8 space-y-3">

                        <a href="{{ route('settings.profile') }}"
                            class="group flex items-center rounded-2xl px-5 py-4 text-sm font-semibold transition duration-300
                            {{ $selectedTab === 'profile'
                                ? 'border border-blue-500/30 bg-blue-500/10 text-white shadow-[0_0_25px_rgba(37,99,235,0.12)]'
                                : 'border border-white/5 bg-white/[0.02] text-slate-400 hover:border-blue-500/20 hover:bg-blue-500/[0.05] hover:text-white' }}">

                            Edit Profil
                        </a>

                        <a href="{{ route('settings.account') }}"
                            class="group flex items-center rounded-2xl px-5 py-4 text-sm font-semibold transition duration-300
                            {{ $selectedTab === 'account'
                                ? 'border border-blue-500/30 bg-blue-500/10 text-white shadow-[0_0_25px_rgba(37,99,235,0.12)]'
                                : 'border border-white/5 bg-white/[0.02] text-slate-400 hover:border-blue-500/20 hover:bg-blue-500/[0.05] hover:text-white' }}">

                            Pengaturan Akun
                        </a>

                        <a href="{{ route('settings.password') }}"
                            class="group flex items-center rounded-2xl px-5 py-4 text-sm font-semibold transition duration-300
                            {{ $selectedTab === 'password'
                                ? 'border border-blue-500/30 bg-blue-500/10 text-white shadow-[0_0_25px_rgba(37,99,235,0.12)]'
                                : 'border border-white/5 bg-white/[0.02] text-slate-400 hover:border-blue-500/20 hover:bg-blue-500/[0.05] hover:text-white' }}">

                            Ubah Password
                        </a>

                        <a href="{{ route('settings.seller') }}"
                            class="group flex items-center rounded-2xl px-5 py-4 text-sm font-semibold transition duration-300
                            {{ $selectedTab === 'seller'
                                ? 'border border-orange-500/30 bg-orange-500/10 text-white shadow-[0_0_25px_rgba(249,115,22,0.12)]'
                                : 'border border-white/5 bg-white/[0.02] text-slate-400 hover:border-orange-500/20 hover:bg-orange-500/[0.05] hover:text-white' }}">

                            Daftar Jadi Seller
                        </a>

                    </nav>

                </div>
            </aside>

            {{-- MAIN CONTENT --}}
            <main
                class="reveal-up overflow-hidden rounded-[34px] border border-white/10 bg-[#0B1220]/92 p-8 backdrop-blur-xl shadow-[0_0_45px_rgba(37,99,235,0.08)]">

                {{-- ALERT --}}
                @if (session('success'))
                    <div class="mb-6 rounded-2xl border border-emerald-500/20 bg-emerald-500/10 p-4 text-emerald-200">
                        {{ session('success') }}
                    </div>
                @endif

                @if (session('status'))
                    <div class="mb-6 rounded-2xl border border-cyan-500/20 bg-cyan-500/10 p-4 text-cyan-200">
                        {{ session('status') }}
                    </div>
                @endif

                @if (session('warning'))
                    <div class="mb-6 rounded-2xl border border-amber-500/20 bg-amber-500/10 p-4 text-amber-200">
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
                            <span class="h-2 w-2 rounded-full bg-emerald-400"></span>
                            PROFILE SETTINGS
                        </div>

                        <h1 class="mt-4 text-4xl font-black text-white">
                            Edit Profil
                        </h1>

                        <p class="mt-3 max-w-2xl text-sm leading-relaxed text-slate-400">
                            Kelola informasi akun dan identitas profil Anda.
                        </p>
                    </div>

                    <form action="{{ route('settings.update') }}"
                        method="POST"
                        enctype="multipart/form-data"
                        class="space-y-6">

                        @csrf
                        @method('PUT')

                        <div
                            class="rounded-[28px] border border-white/5 bg-white/[0.03] p-6">

                            <label for="profile_photo"
                                class="mb-3 block text-sm font-semibold text-slate-300">
                                Foto Profil
                            </label>

                            <input type="file"
                                name="profile_photo"
                                id="profile_photo"
                                accept="image/*"
                                class="w-full rounded-2xl border border-white/10 bg-[#111827] px-4 py-3 text-slate-200 file:mr-4 file:rounded-xl file:border-0 file:bg-blue-500 file:px-4 file:py-2 file:text-white">
                        </div>

                        <div class="grid gap-5 lg:grid-cols-2">

                            <div
                                class="rounded-[28px] border border-white/5 bg-white/[0.03] p-6">

                                <label for="name"
                                    class="mb-3 block text-sm font-semibold text-slate-300">
                                    Nama Lengkap
                                </label>

                                <input type="text"
                                    name="name"
                                    id="name"
                                    value="{{ old('name', $user->name) }}"
                                    required
                                    class="w-full rounded-2xl border border-white/10 bg-[#111827] px-5 py-4 text-white outline-none transition focus:border-blue-500/40">
                            </div>

                            <div
                                class="rounded-[28px] border border-white/5 bg-white/[0.03] p-6">

                                <label for="phone"
                                    class="mb-3 block text-sm font-semibold text-slate-300">
                                    Nomor Telepon
                                </label>

                                <input type="text"
                                    name="phone"
                                    id="phone"
                                    value="{{ old('phone', $profile?->phone ?? $user->phone) }}"
                                    placeholder="08xxxxxxxxxx"
                                    class="w-full rounded-2xl border border-white/10 bg-[#111827] px-5 py-4 text-white outline-none transition focus:border-blue-500/40">
                            </div>

                        </div>

                        <div class="grid gap-5 lg:grid-cols-2">

                            <div
                                class="rounded-[28px] border border-white/5 bg-white/[0.03] p-6">

                                <label for="gender"
                                    class="mb-3 block text-sm font-semibold text-slate-300">
                                    Jenis Kelamin
                                </label>

                                <select name="gender"
                                    id="gender"
                                    class="w-full rounded-2xl border border-white/10 bg-[#111827] px-5 py-4 text-white outline-none transition focus:border-blue-500/40">

                                    <option value="" class="bg-[#111827] text-white">Pilih</option>

                                    <option value="male"
                                        class="bg-[#111827] text-white"
                                        @selected(old('gender', $profile?->gender) === 'male')>
                                        Laki-laki
                                    </option>

                                    <option value="female"
                                        class="bg-[#111827] text-white"
                                        @selected(old('gender', $profile?->gender) === 'female')>
                                        Perempuan
                                    </option>

                                    <option value="other"
                                        class="bg-[#111827] text-white"
                                        @selected(old('gender', $profile?->gender) === 'other')>
                                        Lainnya
                                    </option>

                                </select>
                            </div>

                            <div
                                class="rounded-[28px] border border-white/5 bg-white/[0.03] p-6">

                                <label for="birth_date"
                                    class="mb-3 block text-sm font-semibold text-slate-300">
                                    Tanggal Lahir
                                </label>

                                <input type="date"
                                    name="birth_date"
                                    id="birth_date"
                                    value="{{ old('birth_date', optional($profile?->birth_date)->format('Y-m-d')) }}"
                                    class="w-full rounded-2xl border border-white/10 bg-[#111827] px-5 py-4 text-white outline-none transition focus:border-blue-500/40">
                            </div>

                        </div>

                        <button type="submit"
                            class="w-full rounded-[22px] bg-gradient-to-r from-blue-600 to-blue-500 px-6 py-4 text-sm font-bold text-white transition duration-300 hover:scale-[1.01] hover:shadow-[0_0_30px_rgba(37,99,235,0.25)]">

                            Simpan Perubahan Profil
                        </button>

                    </form>

                {{-- ACCOUNT --}}
                @elseif ($selectedTab === 'account')

                    <h1 class="mb-8 text-4xl font-black text-white">
                        Pengaturan Akun
                    </h1>

                    <div class="grid gap-6 lg:grid-cols-2">

                        <section
                            class="rounded-[30px] border border-white/5 bg-white/[0.03] p-7">

                            <h2 class="text-xl font-bold text-white">
                                Informasi Akun
                            </h2>

                            <dl class="mt-6 space-y-5 text-sm text-slate-300">

                                <div>
                                    <dt class="text-slate-500">
                                        Email
                                    </dt>

                                    <dd class="mt-1 text-white">
                                        {{ $user->email }}
                                    </dd>
                                </div>

                                <div>
                                    <dt class="text-slate-500">
                                        Status Verifikasi
                                    </dt>

                                    <dd class="mt-1 {{ $user->email_verified_at ? 'text-emerald-300' : 'text-amber-300' }}">
                                        {{ $user->email_verified_at ? 'Terverifikasi' : 'Belum Terverifikasi' }}
                                    </dd>
                                </div>

                            </dl>

                        </section>

                        <section
                            class="rounded-[30px] border border-rose-500/10 bg-rose-500/[0.03] p-7">

                            <h2 class="text-xl font-bold text-white">
                                Hapus Akun
                            </h2>

                            <p class="mt-4 text-sm leading-relaxed text-slate-400">
                                Sistem akan mengirimkan kode verifikasi ke email terdaftar sebelum akun dihapus permanen.
                            </p>

                            <a href="{{ route('settings.account.delete') }}"
                                class="mt-6 inline-flex w-full items-center justify-center rounded-2xl bg-rose-600 px-5 py-4 text-sm font-bold text-white transition hover:bg-rose-500">

                                Mulai Proses Hapus Akun
                            </a>

                        </section>

                    </div>

                {{-- PASSWORD --}}
                @elseif ($selectedTab === 'password')

                    <h1 class="mb-8 text-4xl font-black text-white">
                        Ubah Password
                    </h1>

                    <div class="grid gap-6 lg:grid-cols-2">

                        <section
                            class="rounded-[30px] border border-white/5 bg-white/[0.03] p-7">

                            <h2 class="text-xl font-bold text-white">
                                Kirim Kode Verifikasi
                            </h2>

                            <p class="mt-4 text-sm text-slate-400">
                                Kode akan dikirim ke email akun Anda.
                            </p>

                            <form action="{{ route('settings.password.sendCode') }}"
                                method="POST"
                                class="mt-6">

                                @csrf

                                <button type="submit"
                                    class="w-full rounded-2xl bg-blue-600 px-5 py-4 text-sm font-bold text-white transition hover:bg-blue-500">

                                    Kirim Kode
                                </button>

                            </form>

                        </section>

                        <section
                            class="rounded-[30px] border border-white/5 bg-white/[0.03] p-7">

                            <h2 class="text-xl font-bold text-white">
                                Password Baru
                            </h2>

                            <form action="{{ route('settings.password.update') }}"
                                method="POST"
                                class="mt-6 space-y-5">

                                @csrf
                                @method('PUT')

                                <input type="text"
                                    name="verification_code"
                                    placeholder="Kode Verifikasi"
                                    class="w-full rounded-2xl border border-white/10 bg-[#111827] px-5 py-4 text-white">

                                <input type="password"
                                    name="password"
                                    placeholder="Password Baru"
                                    class="w-full rounded-2xl border border-white/10 bg-[#111827] px-5 py-4 text-white">

                                <input type="password"
                                    name="password_confirmation"
                                    placeholder="Konfirmasi Password"
                                    class="w-full rounded-2xl border border-white/10 bg-[#111827] px-5 py-4 text-white">

                                <button type="submit"
                                    class="w-full rounded-2xl bg-emerald-600 px-5 py-4 text-sm font-bold text-white transition hover:bg-emerald-500">

                                    Perbarui Password
                                </button>

                            </form>

                        </section>

                    </div>

                {{-- SELLER --}}
                @else

                    <div class="mb-8">

                        <div
                            class="inline-flex items-center gap-2 rounded-full border border-orange-500/30 bg-orange-500/10 px-4 py-2 text-[11px] font-bold tracking-[0.2em] text-orange-300">

                            <span class="h-2 w-2 rounded-full bg-orange-400"></span>
                            SELLER ACCESS
                        </div>

                        <h1 class="mt-4 text-4xl font-black text-white">
                            Daftar Jadi Seller
                        </h1>

                        <p class="mt-3 max-w-2xl text-sm leading-relaxed text-slate-400">
                            Mulai buka toko digital Anda dan kelola produk langsung dari dashboard seller.
                        </p>

                    </div>

                    <div
                        class="overflow-hidden rounded-[32px] border border-orange-500/10 bg-gradient-to-br from-[#111827] to-[#0B1220] p-8">

                        <div class="grid gap-8 lg:grid-cols-[1fr_260px] lg:items-center">

                            <div>

                                @if ($user->isSellerAccount())

                                    <div
                                        class="inline-flex items-center gap-2 rounded-full border border-emerald-500/20 bg-emerald-500/10 px-4 py-2 text-xs font-bold text-emerald-300">

                                        ✓ Seller Aktif
                                    </div>

                                    <h2 class="mt-5 text-3xl font-black text-white">
                                        Akun Seller Sudah Aktif
                                    </h2>

                                    <p class="mt-4 text-sm leading-relaxed text-slate-400">
                                        Anda sudah dapat menjual produk, mengelola pesanan, serta melihat transaksi seller.
                                    </p>

                                @else

                                    <div
                                        class="inline-flex items-center gap-2 rounded-full border border-orange-500/20 bg-orange-500/10 px-4 py-2 text-xs font-bold text-orange-300">

                                        SELLER REGISTRATION
                                    </div>

                                    <h2 class="mt-5 text-3xl font-black text-white">
                                        Mulai Jadi Seller
                                    </h2>

                                    <p class="mt-4 text-sm leading-relaxed text-slate-400">
                                        Ajukan akun seller dan mulai menjual item gaming Anda di platform ini.
                                    </p>

                                @endif

                                <div class="mt-8 grid gap-4 sm:grid-cols-2">

                                    <a href="{{ route('seller.register.form') }}"
                                        class="flex items-center justify-center rounded-2xl bg-orange-500 px-5 py-4 text-sm font-bold text-slate-950 transition duration-300 hover:scale-[1.02] hover:bg-orange-400">

                                        Ajukan Jadi Seller
                                    </a>

                                    <a href="{{ route('seller.dashboard') }}"
                                        class="flex items-center justify-center rounded-2xl border border-white/10 bg-white/[0.03] px-5 py-4 text-sm font-bold text-white transition duration-300 hover:border-orange-500/30 hover:bg-orange-500/[0.06]">

                                        Buka Dashboard Seller
                                    </a>

                                </div>

                            </div>

                            <div class="hidden lg:flex justify-center">

                                <div
                                    class="flex h-56 w-56 items-center justify-center rounded-full border border-orange-500/20 bg-orange-500/[0.04] shadow-[0_0_40px_rgba(249,115,22,0.12)]">

                                    <img src="{{ asset('storage/app/public/logo/logo.png') }}"
                                        alt="Logo"
                                        class="h-36 w-36 object-contain opacity-95">
                                </div>

                            </div>

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
    animation-delay:.12s;
}

@keyframes revealUp{
    to{
        opacity:1;
        transform:translateY(0);
    }
}
</style>
@endsection
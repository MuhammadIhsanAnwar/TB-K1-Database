@extends('layouts.app')

@section('content')
<div class="relative min-h-screen overflow-hidden bg-[#060816] px-4 pb-16 pt-32">

    {{-- BACKGROUND EFFECT --}}
    <div class="pointer-events-none absolute inset-0 overflow-hidden">
        <div class="absolute top-[-140px] right-[-120px] h-[320px] w-[320px] rounded-full bg-blue-500/10 blur-3xl"></div>
        <div class="absolute bottom-[-140px] left-[-120px] h-[320px] w-[320px] rounded-full bg-orange-500/10 blur-3xl"></div>
    </div>

    <div class="relative z-10 mx-auto max-w-7xl">

        <div class="grid gap-8 lg:grid-cols-[290px_minmax(0,1fr)]">

            {{-- SIDEBAR --}}
            <aside
                class="reveal-up sticky top-28 h-fit overflow-hidden rounded-[30px] border border-white/10 bg-[#0B1220]/95 p-6 backdrop-blur-xl shadow-[0_0_40px_rgba(37,99,235,0.08)]">

                {{-- GLOW --}}
                <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_right,rgba(37,99,235,0.12),transparent_40%)]"></div>

                <div class="relative z-10">

                    {{-- PROFILE --}}
                    <div class="mb-8 text-center">
                        <div
                            class="mx-auto flex h-28 w-28 items-center justify-center overflow-hidden rounded-full border border-blue-500/20 bg-blue-500/10 shadow-[0_0_25px_rgba(37,99,235,0.15)]">

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

                    {{-- NAVIGATION --}}
                    <nav class="space-y-3">

                        <a href="{{ route('settings.profile') }}"
                            class="group flex items-center rounded-2xl px-5 py-4 text-sm font-semibold transition-all duration-300
                            {{ $selectedTab === 'profile'
                                ? 'border border-blue-500/20 bg-blue-500/10 text-white shadow-[0_0_20px_rgba(37,99,235,0.12)]'
                                : 'border border-transparent text-slate-400 hover:border-white/10 hover:bg-white/[0.03] hover:text-white' }}">

                            Edit Profil
                        </a>

                        <a href="{{ route('settings.account') }}"
                            class="group flex items-center rounded-2xl px-5 py-4 text-sm font-semibold transition-all duration-300
                            {{ $selectedTab === 'account'
                                ? 'border border-blue-500/20 bg-blue-500/10 text-white shadow-[0_0_20px_rgba(37,99,235,0.12)]'
                                : 'border border-transparent text-slate-400 hover:border-white/10 hover:bg-white/[0.03] hover:text-white' }}">

                            Pengaturan Akun
                        </a>

                        <a href="{{ route('settings.password') }}"
                            class="group flex items-center rounded-2xl px-5 py-4 text-sm font-semibold transition-all duration-300
                            {{ $selectedTab === 'password'
                                ? 'border border-blue-500/20 bg-blue-500/10 text-white shadow-[0_0_20px_rgba(37,99,235,0.12)]'
                                : 'border border-transparent text-slate-400 hover:border-white/10 hover:bg-white/[0.03] hover:text-white' }}">

                            Ubah Password
                        </a>

                        <a href="{{ route('settings.seller') }}"
                            class="group flex items-center rounded-2xl px-5 py-4 text-sm font-semibold transition-all duration-300
                            {{ $selectedTab === 'seller'
                                ? 'border border-orange-500/20 bg-orange-500/10 text-white shadow-[0_0_20px_rgba(249,115,22,0.12)]'
                                : 'border border-transparent text-slate-400 hover:border-white/10 hover:bg-white/[0.03] hover:text-white' }}">

                            Daftar Jadi Seller
                        </a>

                    </nav>

                </div>
            </aside>

            {{-- MAIN CONTENT --}}
            <main
                class="reveal-up overflow-hidden rounded-[34px] border border-white/10 bg-[#0B1220]/95 p-8 backdrop-blur-xl shadow-[0_0_40px_rgba(37,99,235,0.08)]">

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

                    <h1 class="mb-8 text-3xl font-black text-white">
                        Edit Profil
                    </h1>

                    <form action="{{ route('settings.update') }}"
                        method="POST"
                        enctype="multipart/form-data"
                        class="space-y-6">

                        @csrf
                        @method('PUT')

                        <div>
                            <label for="profile_photo"
                                class="mb-2 block text-sm font-medium text-slate-300">
                                Foto Profil
                            </label>

                            <input type="file"
                                name="profile_photo"
                                id="profile_photo"
                                accept="image/*"
                                class="w-full rounded-2xl border border-white/10 bg-[#09111F] px-4 py-3 text-slate-200 file:mr-4 file:rounded-xl file:border-0 file:bg-blue-500/20 file:px-4 file:py-2 file:text-sm file:font-semibold file:text-blue-300 hover:border-blue-500/20">
                        </div>

                        <div>
                            <label for="name"
                                class="mb-2 block text-sm font-medium text-slate-300">
                                Nama Lengkap
                            </label>

                            <input type="text"
                                name="name"
                                id="name"
                                value="{{ old('name', $user->name) }}"
                                required
                                class="w-full rounded-2xl border border-white/10 bg-[#09111F] px-4 py-3 text-white outline-none transition focus:border-blue-500/30">
                        </div>

                        <div>
                            <label for="phone"
                                class="mb-2 block text-sm font-medium text-slate-300">
                                Nomor Telepon
                            </label>

                            <input type="text"
                                name="phone"
                                id="phone"
                                value="{{ old('phone', $profile?->phone ?? $user->phone) }}"
                                placeholder="08xxxxxxxxxx"
                                class="w-full rounded-2xl border border-white/10 bg-[#09111F] px-4 py-3 text-white outline-none transition focus:border-blue-500/30">
                        </div>

                        <div class="grid gap-5 lg:grid-cols-2">

                            <div>
                                <label for="gender"
                                    class="mb-2 block text-sm font-medium text-slate-300">
                                    Jenis Kelamin
                                </label>

                                <select name="gender"
                                    id="gender"
                                    class="w-full rounded-2xl border border-white/10 bg-[#09111F] px-4 py-3 text-white outline-none transition focus:border-blue-500/30">

                                    <option class="bg-[#09111F] text-white" value="">Pilih</option>

                                    <option class="bg-[#09111F] text-white"
                                        value="male"
                                        @selected(old('gender', $profile?->gender) === 'male')>
                                        Laki-laki
                                    </option>

                                    <option class="bg-[#09111F] text-white"
                                        value="female"
                                        @selected(old('gender', $profile?->gender) === 'female')>
                                        Perempuan
                                    </option>

                                    <option class="bg-[#09111F] text-white"
                                        value="other"
                                        @selected(old('gender', $profile?->gender) === 'other')>
                                        Lainnya
                                    </option>
                                </select>
                            </div>

                            <div>
                                <label for="birth_date"
                                    class="mb-2 block text-sm font-medium text-slate-300">
                                    Tanggal Lahir
                                </label>

                                <input type="date"
                                    name="birth_date"
                                    id="birth_date"
                                    value="{{ old('birth_date', optional($profile?->birth_date)->format('Y-m-d')) }}"
                                    class="w-full rounded-2xl border border-white/10 bg-[#09111F] px-4 py-3 text-white outline-none transition focus:border-blue-500/30">
                            </div>

                        </div>

                        <button type="submit"
                            class="w-full rounded-2xl bg-gradient-to-r from-blue-600 to-cyan-500 px-5 py-4 text-sm font-bold text-white transition duration-300 hover:scale-[1.01] hover:shadow-[0_0_25px_rgba(37,99,235,0.3)]">

                            Simpan Perubahan Profil
                        </button>

                    </form>

                {{-- ACCOUNT --}}
                @elseif ($selectedTab === 'account')

                    <h1 class="mb-8 text-3xl font-black text-white">
                        Pengaturan Akun
                    </h1>

                    <div class="grid gap-6 lg:grid-cols-2">

                        <section class="rounded-[28px] border border-white/10 bg-[#09111F] p-6">

                            <h2 class="mb-5 text-xl font-bold text-white">
                                Informasi Akun
                            </h2>

                            <dl class="space-y-5 text-sm text-slate-300">

                                <div>
                                    <dt class="text-slate-500">Email</dt>
                                    <dd class="mt-1 text-white">{{ $user->email }}</dd>
                                </div>

                                <div>
                                    <dt class="text-slate-500">Status Verifikasi</dt>

                                    <dd class="mt-1 {{ $user->email_verified_at ? 'text-emerald-300' : 'text-amber-300' }}">
                                        {{ $user->email_verified_at ? 'Terverifikasi' : 'Belum Terverifikasi' }}
                                    </dd>
                                </div>

                            </dl>

                            @if (! $user->email_verified_at)
                                <form action="{{ route('verification.send') }}"
                                    method="POST"
                                    class="mt-6">

                                    @csrf

                                    <button type="submit"
                                        class="w-full rounded-2xl bg-blue-600 px-5 py-3 text-sm font-semibold text-white transition hover:bg-blue-500">

                                        Kirim Ulang Email Verifikasi
                                    </button>
                                </form>
                            @endif

                        </section>

                        <section class="rounded-[28px] border border-rose-500/10 bg-[#09111F] p-6">

                            <h2 class="mb-4 text-xl font-bold text-white">
                                Hapus Akun Permanen
                            </h2>

                            <p class="mb-6 text-slate-400">
                                Agar akun bisa dihapus, sistem akan mengirimkan kode verifikasi ke email terdaftar terlebih dahulu.
                            </p>

                            <a href="{{ route('settings.account.delete') }}"
                                class="inline-flex w-full items-center justify-center rounded-2xl bg-rose-600 px-5 py-3 text-sm font-semibold text-white transition hover:bg-rose-500">

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

                        <section class="rounded-[28px] border border-white/10 bg-[#09111F] p-6">

                            <h2 class="mb-4 text-xl font-bold text-white">
                                Kirim Kode Verifikasi
                            </h2>

                            <p class="mb-6 text-slate-400">
                                Kode akan dikirim ke email terdaftar.
                            </p>

                            <form action="{{ route('settings.password.sendCode') }}"
                                method="POST">

                                @csrf

                                <button type="submit"
                                    class="w-full rounded-2xl bg-blue-600 px-5 py-3 text-sm font-semibold text-white transition hover:bg-blue-500">

                                    Kirim Kode ke Email
                                </button>
                            </form>

                        </section>

                        <section class="rounded-[28px] border border-white/10 bg-[#09111F] p-6">

                            <h2 class="mb-4 text-xl font-bold text-white">
                                Simpan Password Baru
                            </h2>

                            <form action="{{ route('settings.password.update') }}"
                                method="POST"
                                class="space-y-4">

                                @csrf
                                @method('PUT')

                                <input type="text"
                                    name="verification_code"
                                    placeholder="Kode Verifikasi"
                                    required
                                    class="w-full rounded-2xl border border-white/10 bg-[#060816] px-4 py-3 text-white">

                                <input type="password"
                                    name="password"
                                    placeholder="Password Baru"
                                    required
                                    class="w-full rounded-2xl border border-white/10 bg-[#060816] px-4 py-3 text-white">

                                <input type="password"
                                    name="password_confirmation"
                                    placeholder="Konfirmasi Password"
                                    required
                                    class="w-full rounded-2xl border border-white/10 bg-[#060816] px-4 py-3 text-white">

                                <button type="submit"
                                    class="w-full rounded-2xl bg-emerald-600 px-5 py-3 text-sm font-semibold text-white transition hover:bg-emerald-500">

                                    Perbarui Password
                                </button>

                            </form>

                        </section>

                    </div>

                {{-- SELLER --}}
                @else

                    <h1 class="mb-8 text-3xl font-black text-white">
                        Daftar Jadi Seller
                    </h1>

                    <div
                        class="relative overflow-hidden rounded-[30px] border border-orange-500/20 bg-gradient-to-br from-[#09111F] via-[#0B1220] to-[#101827] p-8">

                        <div class="absolute right-[-80px] top-[-80px] h-64 w-64 rounded-full bg-orange-500/10 blur-3xl"></div>

                        <div class="relative z-10">

                            <div
                                class="mb-5 inline-flex items-center gap-2 rounded-full border border-orange-500/20 bg-orange-500/10 px-4 py-2 text-[11px] font-bold tracking-[0.2em] text-orange-300">

                                <span class="h-2 w-2 rounded-full bg-orange-400"></span>
                                SELLER ACCESS
                            </div>

                            <h2 class="text-3xl font-black text-white">
                                Mulai Jual Produk Digitalmu
                            </h2>

                            <p class="mt-4 max-w-2xl text-sm leading-relaxed text-slate-300">
                                Aktifkan mode seller untuk membuka toko, mengelola produk,
                                menerima pesanan buyer, dan mengembangkan tokomu di platform.
                            </p>

                            <div class="mt-8 grid gap-5 lg:grid-cols-2">

                                <div
                                    class="rounded-[24px] border border-white/10 bg-white/[0.03] p-5">

                                    <div class="text-lg font-bold text-white">
                                        Status Akun
                                    </div>

                                    @if ($user->isSellerAccount())
                                        <p class="mt-3 text-emerald-300">
                                            Akun Anda sudah memiliki akses seller.
                                        </p>
                                    @else
                                        <p class="mt-3 text-slate-300">
                                            Peran Anda saat ini masih
                                            <span class="font-bold text-white">
                                                {{ ucfirst($user->role) }}
                                            </span>
                                        </p>
                                    @endif
                                </div>

                                <div
                                    class="rounded-[24px] border border-white/10 bg-white/[0.03] p-5">

                                    <div class="text-lg font-bold text-white">
                                        Benefit Seller
                                    </div>

                                    <ul class="mt-3 space-y-2 text-sm text-slate-300">
                                        <li>• Membuka toko sendiri</li>
                                        <li>• Upload produk digital</li>
                                        <li>• Menerima transaksi buyer</li>
                                        <li>• Kelola dashboard seller</li>
                                    </ul>
                                </div>

                            </div>

                            <div class="mt-8 grid gap-4 sm:grid-cols-2">

                                <a href="{{ route('seller.register.form') }}"
                                    class="inline-flex items-center justify-center rounded-2xl bg-orange-500 px-5 py-4 text-sm font-bold text-black transition duration-300 hover:scale-[1.02] hover:bg-orange-400">

                                    Ajukan Jadi Seller
                                </a>

                                <a href="{{ route('seller.dashboard') }}"
                                    class="inline-flex items-center justify-center rounded-2xl border border-white/10 bg-white/[0.03] px-5 py-4 text-sm font-bold text-white transition duration-300 hover:border-orange-500/30 hover:bg-orange-500/10">

                                    Buka Dashboard Seller
                                </a>

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

.reveal-up:nth-child(2){animation-delay:.08s;}
.reveal-up:nth-child(3){animation-delay:.16s;}

@keyframes revealUp{
    to{
        opacity:1;
        transform:translateY(0);
    }
}
</style>
@endsection
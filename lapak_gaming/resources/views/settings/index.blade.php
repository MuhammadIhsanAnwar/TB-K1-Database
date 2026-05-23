@extends('layouts.app')

@section('content')
<div class="relative bg-[#050816] px-4 pb-24 pt-32">

    {{-- BACKGROUND GLOBAL --}}
    <div class="fixed inset-0 -z-50 overflow-hidden pointer-events-none">

        {{-- GRADIENT --}}
        <div class="absolute inset-0 bg-[#050816]"></div>

        {{-- BLUE GLOW --}}
        <div class="absolute top-[-150px] right-[-100px] h-[500px] w-[500px] rounded-full bg-blue-500/15 blur-[120px]"></div>

        {{-- ORANGE GLOW --}}
        <div class="absolute bottom-[-150px] left-[-100px] h-[500px] w-[500px] rounded-full bg-orange-500/10 blur-[120px]"></div>

        {{-- ANIMATED PARTICLES --}}
        <div class="particles"></div>

    </div>

    <div class="relative z-10 mx-auto max-w-7xl">

        <div class="grid gap-8 lg:grid-cols-[290px_minmax(0,1fr)] items-start relative">

            {{-- SIDEBAR --}}
            <aside class="sticky top-28 h-fit z-30 w-full hidden lg:block">
                
                <div class="reveal-up rounded-[30px] border border-white/10 bg-[#0B1220]/45 p-6 backdrop-blur-xl shadow-[0_20px_50px_rgba(0,0,0,0.3)] relative overflow-hidden">

                    <div class="absolute inset-0 rounded-[30px] bg-[radial-gradient(circle_at_top_right,rgba(37,99,235,0.12),transparent_50%)] pointer-events-none">
                    </div>

                    <div class="relative z-10">

                        {{-- PROFILE --}}
                        <div class="text-center pb-6 border-b border-white/5">

                            <div class="mx-auto flex h-24 w-24 overflow-hidden rounded-full border-2 border-blue-500/30 bg-[#111827]/60 shadow-[0_0_30px_rgba(37,99,235,0.2)]">
                                <img src="{{ $user->avatar_url }}"
                                    alt="{{ $user->name }}"
                                    class="h-full w-full object-cover">
                            </div>

                            <h2 class="mt-4 text-lg font-black text-white tracking-wide truncate px-2">
                                {{ $user->name }}
                            </h2>

                            <p class="mt-1 text-xs text-slate-400 truncate px-2">
                                {{ $user->email }}
                            </p>

                        </div>

                        {{-- MENU --}}
                        <nav class="mt-6 space-y-2.5">

                            <a href="{{ Route::has('settings.profile') ? route('settings.profile') : url('/settings/profile') }}"
                                class="menu-item {{ $selectedTab === 'profile' ? 'menu-active-blue' : 'menu-normal' }}">
                                <span class="mr-2.5 text-base">👤</span> Edit Profil
                            </a>

                            <a href="{{ Route::has('settings.account') ? route('settings.account') : url('/settings/account') }}"
                                class="menu-item {{ $selectedTab === 'account' ? 'menu-active-blue' : 'menu-normal' }}">
                                <span class="mr-2.5 text-base">⚙️</span> Pengaturan Akun
                            </a>

                            @if(! $user->isGoogleAccount())
                            <a href="{{ Route::has('settings.password') ? route('settings.password') : url('/settings/password') }}"
                                class="menu-item {{ $selectedTab === 'password' ? 'menu-active-blue' : 'menu-normal' }}">
                                <span class="mr-2.5 text-base">🔑</span> Ubah Password
                            </a>
                            @else
                            <div class="menu-item menu-normal cursor-not-allowed opacity-40 select-none">
                                <span class="mr-2.5 text-base">🔑</span> Ubah Password
                            </div>
                            @endif

                            <a href="{{ Route::has('settings.section') ? route('settings.section', ['section' => 'security']) : url('/settings/security') }}"
                                class="menu-item {{ $selectedTab === 'security' ? 'menu-active-blue' : 'menu-normal' }}">
                                <span class="mr-2.5 text-base">🛡️</span> Verifikasi 2 Langkah
                            </a>

                            @unless($user->isAdmin())
                                <a href="{{ Route::has('settings.seller') ? route('settings.seller') : url('/settings/seller') }}"
                                    class="menu-item {{ $selectedTab === 'seller' ? 'menu-active-orange' : 'menu-normal' }}">
                                    <span class="mr-2.5 text-base">🔥</span> Daftar Jadi Seller
                                </a>
                            @endunless

                        </nav>

                    </div>
                </div>
            </aside>

            {{-- MAIN PANEL --}}
            <main class="reveal-up rounded-[30px] border border-white/10 bg-[#0B1220]/50 p-6 sm:p-8 backdrop-blur-xl shadow-[0_20px_50px_rgba(0,0,0,0.3)] w-full">

                {{-- ALERT --}}
                @if (session('success'))
                    <div class="mb-6 rounded-xl border border-emerald-500/20 bg-emerald-500/10 p-4 text-sm font-medium text-emerald-300 flex items-center gap-2">
                        <span class="text-base">✓</span> {{ session('success') }}
                    </div>
                @endif

                @if (session('status'))
                    <div class="mb-6 rounded-xl border border-cyan-500/20 bg-cyan-500/10 p-4 text-sm font-medium text-cyan-300 flex items-center gap-2">
                        <span class="text-base">ℹ</span> {{ session('status') }}
                    </div>
                @endif

                @if (session('warning'))
                    <div class="mb-6 rounded-xl border border-amber-500/20 bg-amber-500/10 p-4 text-sm font-medium text-amber-300 flex items-center gap-2">
                        <span class="text-base">⚠</span> {{ session('warning') }}
                    </div>
                @endif

                @if ($errors->any())
                    <div class="mb-6 rounded-xl border border-rose-500/20 bg-rose-500/10 p-4 text-rose-300">
                        <div class="font-bold text-sm mb-1.5">❌ Terjadi Kesalahan:</div>
                        <ul class="list-disc list-inside space-y-1 text-xs opacity-90">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @if($user->isAdmin())
                    <div class="mb-6 rounded-xl border border-amber-500/20 bg-amber-500/10 p-4 text-xs text-amber-200/90">
                        Menu buyer dan seller tidak tersedia untuk akun administrator.
                    </div>
                @endif

                {{-- PROFILE TAB --}}
                @if ($selectedTab === 'profile')

                    <div class="mb-8">

                        <div class="inline-flex items-center gap-2 rounded-full border border-blue-500/30 bg-blue-500/10 px-3.5 py-1.5 text-[10px] font-black tracking-[0.2em] text-blue-400">
                            <span class="h-1.5 w-1.5 rounded-full bg-blue-400 animate-pulse"></span>
                            PROFILE SETTINGS
                        </div>

                        <h1 class="mt-4 text-3xl sm:text-4xl font-black text-white tracking-tight">
                            Edit Profil
                        </h1>

                        <p class="mt-2 text-xs sm:text-sm leading-relaxed text-slate-400">
                            Kelola informasi profil akun Anda dengan tampilan modern dan futuristik.
                        </p>

                    </div>

                    <form action="{{ route('settings.update') }}"
                        method="POST"
                        enctype="multipart/form-data"
                        class="space-y-5">

                        @csrf
                        @method('PUT')

                        <div class="card-box">
                            <label for="profile_photo" class="label-style">
                                Foto Profil
                            </label>

                            <input type="file"
                                name="profile_photo"
                                id="profile_photo"
                                accept="image/*"
                                class="input-style file:mr-4 file:rounded-xl file:border-0 file:bg-blue-600 file:hover:bg-blue-500 file:px-4 file:py-1.5 file:text-xs file:font-bold file:text-white file:transition-all cursor-pointer">
                        </div>

                        <div class="grid gap-5 sm:grid-cols-2">

                            <div class="card-box">
                                <label for="name" class="label-style">
                                    Nama Lengkap
                                </label>

                                <input type="text"
                                    name="name"
                                    id="name"
                                    value="{{ old('name', $user->name) }}"
                                    required
                                    class="input-style">
                            </div>

                            <div class="card-box">
                                <label for="phone" class="label-style">
                                    Nomor Telepon
                                </label>

                                <input type="text"
                                    name="phone"
                                    id="phone"
                                    value="{{ old('phone', $profile?->phone ?? $user->phone) }}"
                                    placeholder="08xxxxxxxxxx"
                                    class="input-style">
                            </div>

                        </div>

                        <div class="grid gap-5 sm:grid-cols-2">

                            <div class="card-box">

                                <label for="gender" class="label-style">
                                    Jenis Kelamin
                                </label>

                                <select name="gender"
                                    id="gender"
                                    class="input-style cursor-pointer">

                                    <option value="" class="bg-[#0b1220] text-slate-500">
                                        Pilih Jenis Kelamin
                                    </option>

                                    <option value="male"
                                        class="bg-[#0b1220] text-white"
                                        @selected(old('gender', $profile?->gender) === 'male')>
                                        Laki-laki
                                    </option>

                                    <option value="female"
                                        class="bg-[#0b1220] text-white"
                                        @selected(old('gender', $profile?->gender) === 'female')>
                                        Perempuan
                                    </option>

                                </select>

                            </div>

                            <div class="card-box">

                                <label for="birth_date" class="label-style">
                                    Tanggal Lahir
                                </label>

                                <input type="date"
                                    name="birth_date"
                                    id="birth_date"
                                    value="{{ old('birth_date', optional($profile?->birth_date)->format('Y-m-d')) }}"
                                    class="input-style cursor-pointer">
                            </div>

                        </div>

                        <div class="pt-2">
                            <button type="submit"
                                class="submit-blue">
                                Simpan Perubahan Profil
                            </button>
                        </div>

                    </form>

                {{-- ACCOUNT TAB --}}
                @elseif ($selectedTab === 'account')

                    <div class="mb-8">
                        <h1 class="text-3xl sm:text-4xl font-black text-white tracking-tight">
                            Pengaturan Akun
                        </h1>
                        <p class="mt-2 text-xs sm:text-sm text-slate-400">
                            Kelola privasi, kredensial data verifikasi, dan status penonaktifan identitas user secara aman.
                        </p>
                    </div>

                    <div class="grid gap-6 sm:grid-cols-2">

                        <section class="card-box">

                            <h2 class="text-lg font-bold text-white flex items-center gap-2">
                                <span>📋</span> Informasi Akun
                            </h2>

                            <dl class="mt-6 space-y-4 text-xs sm:text-sm text-slate-300">

                                <div class="border-b border-white/5 pb-3">
                                    <dt class="text-slate-500 font-semibold text-xs uppercase tracking-wider">
                                        Email Akun
                                    </dt>
                                    <dd class="mt-1 text-white font-medium">
                                        {{ $user->email }}
                                    </dd>
                                </div>

                                <div class="pt-1">
                                    <dt class="text-slate-500 font-semibold text-xs uppercase tracking-wider">
                                        Status Verifikasi
                                    </dt>
                                    <dd class="mt-1 inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-xs font-bold {{ $user->email_verified_at ? 'bg-emerald-500/10 text-emerald-400 border border-emerald-500/20' : 'bg-amber-500/10 text-amber-400 border border-amber-500/20' }}">
                                        {{ $user->email_verified_at ? 'Verified' : 'Unverified' }}
                                    </dd>
                                </div>

                            </dl>

                        </section>

                        <section class="card-box border-amber-500/20 bg-amber-500/[0.02]">

                            <h2 class="text-lg font-bold text-amber-400 flex items-center gap-2">
                                <span>⚠️</span> Nonaktifkan Akun
                            </h2>

                            <p class="mt-3 text-xs sm:text-sm leading-relaxed text-slate-400">
                                Kode verifikasi akan dikirim ke email sebelum akun dinonaktifkan. Setelah berhasil, Anda akan otomatis logout.
                            </p>

                            <form action="{{ route('settings.account.sendDeactivationCode') }}"
                                method="POST"
                                class="mt-5">
                                @csrf

                                <button type="submit"
                                    class="w-full rounded-xl border border-amber-400/30 bg-amber-400/10 px-4 py-3 text-xs sm:text-sm font-bold text-amber-300 transition-all duration-300 hover:bg-amber-400/20">
                                    Kirim Kode Nonaktif
                                </button>
                            </form>

                            <form action="{{ route('settings.deactivate') }}"
                                method="POST"
                                class="mt-4 space-y-3">
                                @csrf

                                <input type="text"
                                    name="deactivation_code"
                                    inputmode="numeric"
                                    maxlength="6"
                                    placeholder="Masukkan 6 Digit Kode"
                                    class="input-style text-center font-mono tracking-widest text-base py-3">

                                <button type="submit"
                                    onclick="return confirm('Nonaktifkan akun sekarang? Anda akan otomatis logout.')"
                                    class="w-full rounded-xl bg-amber-500 px-4 py-3 text-xs sm:text-sm font-black text-slate-950 transition-all duration-300 hover:bg-amber-400 shadow-[0_4px_20px_rgba(245,158,11,0.2)]">
                                    Konfirmasi Nonaktifkan Akun
                                </button>
                            </form>

                        </section>

                        <section class="card-box border-rose-500/20 bg-rose-500/[0.02] sm:col-span-2">

                            <h2 class="text-lg font-bold text-rose-400 flex items-center gap-2">
                                <span>🚨</span> Hapus Akun Permanen
                            </h2>

                            <p class="mt-2 text-xs sm:text-sm text-slate-400">
                                Menghapus akun bersifat permanen. Seluruh data transaksi, toko, produk, dan saldo wallet Anda akan dimusnahkan selamanya dari server.
                            </p>

                            <div class="mt-5">
                                <a href="{{ route('settings.account.delete') }}"
                                    class="inline-flex items-center justify-center rounded-xl bg-rose-600 hover:bg-rose-500 px-5 py-3.5 text-xs sm:text-sm font-bold text-white transition-all duration-300 shadow-[0_4px_20px_rgba(225,29,72,0.15)]">
                                    Mulai Proses Hapus Akun
                                </a>
                            </div>

                        </section>

                    </div>

                {{-- PASSWORD TAB --}}
                @elseif ($selectedTab === 'password')

                    <div class="mb-8">
                        <h1 class="text-3xl sm:text-4xl font-black text-white tracking-tight">
                            Ubah Password
                        </h1>
                        <p class="mt-2 text-xs sm:text-sm text-slate-400">
                            Perbarui kata sandi akun secara berkala demi menghindari ancaman brute-force cyber hacking.
                        </p>
                    </div>

                    @if($user->isGoogleAccount())
                        <div class="mb-6 rounded-xl border border-amber-500/20 bg-amber-500/10 p-4 text-xs sm:text-sm font-medium text-amber-300 flex items-center gap-2">
                            <span>💡</span> Akun ini terhubung menggunakan Google Auth. Perubahan password manual dinonaktifkan.
                        </div>
                    @endif

                    <div class="grid gap-6 sm:grid-cols-2">

                        <section class="card-box flex flex-col justify-between">
                            <div>
                                <h2 class="text-lg font-bold text-white flex items-center gap-2">
                                    <span>📩</span> Kirim OTP Verifikasi
                                </h2>
                                <p class="mt-3 text-xs sm:text-sm leading-relaxed text-slate-400">
                                    Sistem akan mengirimkan token konfirmasi keamanan khusus langsung ke alamat email terdaftar Anda.
                                </p>
                            </div>

                            <form action="{{ route('settings.password.sendCode') }}"
                                method="POST"
                                class="mt-6">
                                @csrf

                                <button type="submit" @disabled($user->isGoogleAccount())
                                    class="submit-blue disabled:opacity-40 disabled:transform-none disabled:box-shadow-none">
                                    Kirim Token Ke Email
                                </button>
                            </form>
                        </section>

                        <section class="card-box">

                            <h2 class="text-lg font-bold text-white flex items-center gap-2">
                                <span>🔒</span> Kredensial Sandi Baru
                            </h2>

                            <form action="{{ route('settings.password.update') }}"
                                method="POST"
                                class="mt-5 space-y-4">

                                @csrf
                                @method('PUT')

                                <input type="text"
                                    name="verification_code"
                                    placeholder="Kode Verifikasi OTP"
                                    class="input-style" @disabled($user->isGoogleAccount())>

                                <input type="password"
                                    name="password"
                                    placeholder="Password Baru"
                                    class="input-style" @disabled($user->isGoogleAccount())>

                                <input type="password"
                                    name="password_confirmation"
                                    placeholder="Konfirmasi Password Baru"
                                    class="input-style" @disabled($user->isGoogleAccount())>

                                <div class="pt-1">
                                    <button type="submit" @disabled($user->isGoogleAccount())
                                        class="submit-green disabled:opacity-40 disabled:transform-none disabled:box-shadow-none">
                                        Perbarui Password Akun
                                    </button>
                                </div>

                            </form>

                        </section>

                    </div>

                {{-- SECURITY TAB / 2FA --}}
                @elseif ($selectedTab === 'security')

                    <div class="mb-8">

                        <div class="inline-flex items-center gap-2 rounded-full border border-cyan-500/30 bg-cyan-500/10 px-3.5 py-1.5 text-[10px] font-black tracking-[0.2em] text-cyan-400 uppercase">
                            <span class="h-1.5 w-1.5 rounded-full bg-cyan-400 animate-pulse"></span>
                            TWO STEP VERIFICATION
                        </div>

                        <h1 class="mt-4 text-3xl sm:text-4xl font-black text-white tracking-tight">
                            Verifikasi 2 Langkah
                        </h1>

                        <p class="mt-2 text-xs sm:text-sm text-slate-400 leading-relaxed">
                            Aktifkan lapisan enkripsi perlindungan ganda. Setiap kali login, Anda wajib mengonfirmasi token akses.
                        </p>

                    </div>

                    <form id="two-factor-confirm-form" action="{{ route('settings.security.confirm') }}" method="POST" class="hidden">
                        @csrf
                    </form>

                    <form action="{{ route('settings.security.update') }}" method="POST" class="space-y-5">
                        @csrf
                        @method('PUT')

                        <section class="card-box">
                            <div class="flex items-center justify-between gap-5 flex-wrap">
                                <div>
                                    <h2 class="text-lg font-bold text-white">Status Proteksi 2FA</h2>
                                    <p class="mt-1 text-xs text-slate-400">Pastikan akun Anda selalu dalam kondisi aman terlindungi.</p>
                                </div>

                                <label class="inline-flex items-center gap-3 rounded-xl border border-white/10 bg-[#111827]/40 px-4 py-3 text-xs sm:text-sm text-slate-200 select-none cursor-pointer hover:border-white/20 transition-all">
                                    <input type="checkbox" name="two_factor_enabled" value="1" @checked(old('two_factor_enabled', $user->two_factor_enabled)) class="rounded border-white/10 bg-slate-900 text-blue-500 focus:ring-blue-500/40">
                                    Aktifkan Sistem Dua Faktor
                                </label>
                            </div>
                        </section>

                        <section class="card-box">
                            <h2 class="text-lg font-bold text-white">Metode Enkripsi Gerbang</h2>

                            <div class="mt-5 grid gap-4 sm:grid-cols-2">
                                <label class="rounded-2xl border border-white/5 bg-white/[0.02] p-4 text-xs sm:text-sm text-slate-200 block cursor-pointer hover:border-blue-500/20 hover:bg-blue-500/[0.01] transition-all">
                                    <div class="flex items-center gap-2.5 font-bold text-white">
                                        <input type="checkbox" name="two_factor_methods[]" value="email" @checked(in_array('email', old('two_factor_methods', $twoFactorMethods ?? []), true)) class="rounded border-white/10 bg-slate-900 text-blue-500">
                                        <span>Metode OTP Email</span>
                                    </div>
                                    <p class="mt-2 text-xs text-slate-400 leading-relaxed">Kode konformasi dikirim otomatis ke kotak masuk email terdaftar Anda.</p>
                                </label>

                                <label class="rounded-2xl border border-white/5 bg-white/[0.02] p-4 text-xs sm:text-sm text-slate-200 block cursor-pointer hover:border-cyan-500/20 hover:bg-cyan-500/[0.01] transition-all">
                                    <div class="flex items-center gap-2.5 font-bold text-white">
                                        <input type="checkbox" name="two_factor_methods[]" value="google" @checked(in_array('google', old('two_factor_methods', $twoFactorMethods ?? []), true)) class="rounded border-white/10 bg-slate-900 text-cyan-500">
                                        <span>Google Authenticator</span>
                                    </div>
                                    <p class="mt-2 text-xs text-slate-400 leading-relaxed">Gunakan kode token yang terus sinkron berubah di aplikasi seluler Anda.</p>
                                </label>
                            </div>
                        </section>

                        <section class="card-box">
                            <h2 class="text-lg font-bold text-white mb-4">Konfigurasi Google Authenticator</h2>

                            @if($pendingTwoFactorSetup ?? false)
                                <div class="grid gap-6 sm:grid-cols-[180px_minmax(0,1fr)] items-center">
                                    @if($googleQrCode)
                                        <div class="rounded-2xl border border-white/10 bg-white p-3 flex items-center justify-center shadow-lg">
                                            {!! $googleQrCode !!}
                                        </div>
                                    @endif

                                    <div>
                                        <p class="text-xs sm:text-sm text-slate-400 leading-relaxed">Buka aplikasi Authenticator Anda (Google/Authy), scan gambar QR-code di samping atau salin kunci privat manual di bawah ini:</p>
                                        <div class="mt-3 rounded-xl border border-cyan-500/20 bg-cyan-500/10 px-4 py-2.5 font-mono text-xs text-cyan-300 break-all tracking-wider select-all">
                                            {{ $googleSecret }}
                                        </div>
                                    </div>
                                </div>

                                <div class="mt-5 space-y-3">
                                    <input type="text"
                                        name="verification_code"
                                        inputmode="numeric"
                                        maxlength="6"
                                        placeholder="Masukkan 6 digit kode aplikasi"
                                        form="two-factor-confirm-form"
                                        class="input-style text-center tracking-widest font-mono text-base">

                                    <button type="submit"
                                        form="two-factor-confirm-form"
                                        class="submit-green">
                                        Sinkronkan & Konfirmasi Token Authenticator
                                    </button>
                                </div>

                            @elseif(in_array('google', old('two_factor_methods', $twoFactorMethods ?? []), true) && $user->two_factor_confirmed_at)
                                <div class="rounded-2xl border border-emerald-500/20 bg-emerald-500/5 p-4 flex items-start gap-3">
                                    <span class="text-emerald-400 text-base mt-0.5">🛡️</span>
                                    <div>
                                        <p class="text-xs sm:text-sm text-emerald-300 font-bold">Google Authenticator Aktif</p>
                                        <p class="mt-1 text-xs text-slate-400 leading-relaxed">Aplikasi authenticator Anda telah disinkronkan sepenuhnya ke server database Lapak Gaming.</p>
                                    </div>
                                </div>
                            @else
                                <p class="text-xs sm:text-sm text-slate-400 leading-relaxed">Kunci enkripsi rahasia Google Authenticator akan dibuat otomatis ketika Anda mencentang opsinya di atas dan menekan simpan.</p>
                            @endif
                        </section>

                        <div class="pt-2">
                            <button type="submit" class="submit-blue">
                                Simpan Setelan Verifikasi 2 Langkah
                            </button>
                        </div>
                    </form>

                {{-- SELLER TAB --}}
                @elseif($user->seller_status === 'pending')
                
                    <div class="mb-8">

                        <div class="inline-flex items-center gap-2 rounded-full border border-yellow-500/30 bg-yellow-500/10 px-3.5 py-1.5 text-[10px] font-black tracking-[0.2em] text-yellow-400 uppercase">
                            <span class="h-1.5 w-1.5 rounded-full bg-yellow-400 animate-pulse"></span>
                            SELLER VERIFICATION
                        </div>

                        <h1 class="mt-4 text-3xl sm:text-4xl font-black text-white tracking-tight">
                            Pengajuan Seller Diproses
                        </h1>

                        <p class="mt-2 text-xs sm:text-sm text-slate-400 leading-relaxed">
                            Berkas kemitraan merchant Anda sedang berada di dalam antrean peninjauan data oleh tim administrator audit Lapak Gaming.
                        </p>

                    </div>

                    <div class="overflow-hidden rounded-[24px] border border-yellow-500/20 bg-gradient-to-br from-[#111827]/40 to-[#0B1220]/40 p-6 sm:p-8 backdrop-blur-md">

                        <div class="flex flex-col sm:flex-row items-start gap-4">
                            <div class="w-12 h-12 rounded-xl bg-yellow-500/10 border border-yellow-500/20 flex items-center justify-center text-yellow-400 text-2xl shrink-0">
                                ⏳
                            </div>
                            <div>
                                <h2 class="text-lg sm:text-xl font-black text-yellow-300 tracking-wide">Status: Audit Peninjauan Berkas</h2>
                                <p class="text-xs sm:text-sm text-slate-300 leading-relaxed mt-2">
                                    Proses peninjauan umumnya membutuhkan waktu berkisar <strong>1-3 hari kerja</strong>. Kami memvalidasi keaslian data demi menjaga ekosistem merchant dari fraud/penipuan. Notifikasi keputusan kelulusan merchant akan segera dikirim melalui email Anda.
                                </p>
                            </div>
                        </div>

                    </div>

                @elseif($user->seller_status === 'rejected')

                    <div class="mb-8">

                        <div class="inline-flex items-center gap-2 rounded-full border border-red-500/30 bg-red-500/10 px-3.5 py-1.5 text-[10px] font-black tracking-[0.2em] text-red-400 uppercase">
                            <span class="h-1.5 w-1.5 rounded-full bg-red-400"></span>
                            SELLER REJECTED
                        </div>

                        <h1 class="mt-4 text-3xl sm:text-4xl font-black text-white tracking-tight">
                            Kemitraan Merchant Ditolak
                        </h1>

                        <p class="mt-2 text-xs sm:text-sm text-slate-400 leading-relaxed">
                            Maaf, berkas pendaftaran kemitraan toko yang Anda ajukan belum memenuhi kriteria kualifikasi standar komunitas kami.
                        </p>

                    </div>

                    <div class="overflow-hidden rounded-[24px] border border-red-500/20 bg-gradient-to-br from-[#111827]/40 to-[#0B1220]/40 p-6 sm:p-8 backdrop-blur-md">

                        <div class="flex items-start gap-4 mb-6">
                            <div class="w-10 h-10 rounded-xl bg-rose-500/10 border border-rose-500/20 flex items-center justify-center text-rose-400 text-xl shrink-0 mt-0.5">
                                ❌
                            </div>
                            <div>
                                <h2 class="text-base sm:text-lg font-black text-rose-300">Alasan Penolakan Tim Verifikator:</h2>
                                <p class="text-xs sm:text-sm text-slate-300 mt-2 bg-black/30 p-3 rounded-xl border border-white/5 font-medium leading-relaxed">
                                    {{ $user->seller_rejection_reason ?? 'Berkas KTP/Identitas buram atau tidak cocok dengan data profil terdaftar.' }}
                                </p>
                            </div>
                        </div>

                        <a href="{{ route('seller.register.form') }}"
                            class="inline-flex items-center justify-center rounded-xl bg-rose-600 hover:bg-rose-500 px-5 py-3 text-xs sm:text-sm font-bold text-white transition-all duration-300 hover:scale-[1.02] shadow-[0_4px_15px_rgba(225,29,72,0.2)]">
                            Ajukan Berkas Ulang
                        </a>

                    </div>

                @else

                    <div class="mb-8">

                        <div class="inline-flex items-center gap-2 rounded-full border border-orange-500/30 bg-orange-500/10 px-3.5 py-1.5 text-[10px] font-black tracking-[0.2em] text-orange-400 uppercase">
                            <span class="h-1.5 w-1.5 rounded-full bg-orange-400"></span>
                            SELLER ACCESS
                        </div>

                        <h1 class="mt-4 text-3xl sm:text-4xl font-black text-white tracking-tight">
                            Daftar Jadi Seller
                        </h1>

                        <p class="mt-2 text-xs sm:text-sm text-slate-400 leading-relaxed">
                            Mulai buka lapak dagang digital gaming Anda sekarang and raih omzet jutaan rupiah bersama kami.
                        </p>

                    </div>

                    <div class="overflow-hidden rounded-[24px] border border-white/5 bg-gradient-to-br from-[#111827]/30 to-[#0B1220]/30 p-6 sm:p-8 backdrop-blur-md">

                        <div class="grid gap-6 lg:grid-cols-[1fr_200px] lg:items-center">

                            <div>

                                @if ($user->isSellerAccount())

                                    <div class="inline-flex items-center gap-1.5 rounded-full border border-emerald-500/20 bg-emerald-500/10 px-3 py-1 text-[11px] font-bold text-emerald-400">
                                        ✓ Status: Seller Aktif
                                    </div>

                                    <h2 class="mt-4 text-2xl font-black text-white tracking-wide">
                                        Akun Lapak Merchant Aktif
                                    </h2>

                                    <p class="mt-2 text-xs sm:text-sm leading-relaxed text-slate-400">
                                        Selamat! Hak akses toko Anda telah terbuka. Anda sudah bisa mendaftarkan katalog produk item game and menerima pesanan masuk.
                                    </p>

                                @else

                                    <div class="inline-flex items-center gap-1.5 rounded-full border border-orange-500/20 bg-orange-500/10 px-3 py-1 text-[11px] font-bold text-orange-400">
                                        💼 Pendaftaran Merchant
                                    </div>

                                    <h2 class="mt-4 text-2xl font-black text-white tracking-wide">
                                        Mulai Usaha Toko Game
                                    </h2>

                                    <p class="mt-2 text-xs sm:text-sm leading-relaxed text-slate-400">
                                        Unggah kelengkapan verifikasi KYC identitas diri Anda untuk mendapatkan akses kelola inventori dagangan marketplace secara legal.
                                    </p>

                                @endif

                                <div class="mt-6 flex flex-wrap gap-4">

                                    <a href="{{ route('seller.register.form') }}"
                                        class="flex items-center justify-center rounded-xl bg-orange-500 hover:bg-orange-400 px-4 py-3 text-xs sm:text-sm font-black text-slate-950 transition-all duration-300 hover:scale-[1.02] shadow-[0_4px_15px_rgba(249,115,22,0.25)]">
                                        Ajukan Jadi Seller
                                    </a>

                                    <a href="{{ route('seller.dashboard') }}"
                                        class="flex items-center justify-center rounded-xl border border-white/10 bg-white/[0.02] px-4 py-3 text-xs sm:text-sm font-bold text-white transition-all duration-300 hover:border-orange-500/30 hover:bg-orange-500/[0.04]">
                                        Buka Dashboard Seller
                                    </a>

                                </div>

                            </div>

                            <div class="hidden lg:flex justify-center">
                                <div class="flex h-40 w-40 items-center justify-center rounded-full border border-orange-500/15 bg-orange-500/[0.02] shadow-[0_0_50px_rgba(249,115,22,0.1)]">
                                    <img src="{{ asset('storage/app/public/logo/logo.png') }}"
                                        alt="Logo"
                                        class="h-24 w-24 object-contain opacity-85">
                                </div>
                            </div>

                        </div>

                    </div>

                @endif

            </main>

        </div>

    </div>
</div>

<style>
/* PREVENT HORIZONTAL SCROLL */
html,
body {
    overflow-x: hidden;
    background: #050816;
}

/* REMOVE HOVER LINES FROM LAYOUT INHERITANCE */
body::before,
body::after {
    display: none !important;
}

/* FUTURISTIC PARTICLES LAYER */
.particles {
    position: absolute;
    inset: 0;
    background-image:
        radial-gradient(rgba(37, 99, 235, 0.25) 1px, transparent 1px),
        radial-gradient(rgba(249, 115, 22, 0.12) 1px, transparent 1px);
    background-size: 80px 80px;
    background-position: 0 0, 40px 40px;
    animation: particleMove 25s linear infinite;
    opacity: 0.35;
}

@keyframes particleMove {
    from { transform: translateY(0); }
    to   { transform: translateY(-80px); }
}

/* PREMIUM NAVIGATION MENU */
.menu-item {
    display: flex;
    align-items: center;
    border-radius: 16px;
    padding: 13px 18px;
    font-size: 13px;
    font-weight: 700;
    transition: all 0.3s ease;
}

.menu-normal {
    border: 1px solid rgba(255, 255, 255, 0.04);
    background: rgba(255, 255, 255, 0.01);
    color: #94a3b8;
}

.menu-normal:hover {
    transform: translateX(5px);
    border-color: rgba(59, 130, 246, 0.2);
    background: rgba(59, 130, 246, 0.06);
    color: white;
}

.menu-active-blue {
    border: 1px solid rgba(59, 130, 246, 0.25);
    background: linear-gradient(135deg, rgba(37, 99, 235, 0.15) 0%, rgba(59, 130, 246, 0.05) 100%);
    color: white;
    box-shadow: 0 8px 25px rgba(37, 99, 235, 0.12);
}

.menu-active-orange {
    border: 1px solid rgba(249, 115, 22, 0.25);
    background: linear-gradient(135deg, rgba(249, 115, 22, 0.15) 0%, rgba(249, 115, 22, 0.05) 100%);
    color: white;
    box-shadow: 0 8px 25px rgba(249, 115, 22, 0.1);
}

/* TRANSPARENT BOX CONTAINER (GLASSMORPHISM) */
.card-box {
    border: 1px solid rgba(255, 255, 255, 0.05);
    background: rgba(255, 255, 255, 0.02);
    border-radius: 20px;
    padding: 22px;
}

/* TYPOGRAPHY */
.label-style {
    display: block;
    margin-bottom: 10px;
    font-size: 13px;
    font-weight: 700;
    color: #94a3b8;
    letter-spacing: 0.03em;
}

/* FORMS INPUT */
.input-style {
    width: 100%;
    border-radius: 14px;
    border: 1px solid rgba(255, 255, 255, 0.07);
    background: #090e1a/80;
    padding: 13px 16px;
    font-size: 13px;
    color: white;
    outline: none;
    transition: all 0.25s ease;
}

.input-style:focus {
    border-color: rgba(59, 130, 246, 0.4);
    box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.06);
    background: #090e1a;
}

/* PREMIUM BUTTON GLOW */
.submit-blue {
    width: 100%;
    border-radius: 16px;
    background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
    padding: 14px;
    font-size: 13px;
    font-weight: 800;
    color: white;
    transition: all 0.3s ease;
    box-shadow: 0 4px 15px rgba(37, 99, 235, 0.2);
}

.submit-blue:hover {
    transform: translateY(-1.5px);
    box-shadow: 0 8px 25px rgba(37, 99, 235, 0.35);
}

.submit-green {
    width: 100%;
    border-radius: 16px;
    background: linear-gradient(135deg, #059669 0%, #047857 100%);
    padding: 14px;
    font-size: 13px;
    font-weight: 800;
    color: white;
    transition: all 0.3s ease;
    box-shadow: 0 4px 15px rgba(5, 150, 105, 0.2);
}

.submit-green:hover {
    transform: translateY(-1.5px);
    box-shadow: 0 8px 25px rgba(5, 150, 105, 0.35);
}

/* FADE & UP ENTRANCE ANIMATION */
.reveal-up {
    opacity: 0;
    transform: translateY(30px);
    animation: revealUp 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards;
}

@keyframes revealUp {
    to {
        opacity: 1;
        transform: translateY(0);
    }
}
</style>
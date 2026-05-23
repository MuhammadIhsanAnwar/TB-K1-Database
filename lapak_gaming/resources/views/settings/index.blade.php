@extends('layouts.app')

@section('content')
<div class="relative bg-[#050816] px-4 pb-24 pt-32">

    {{-- BACKGROUND GLOBAL --}}
    <div class="fixed inset-0 -z-50 overflow-hidden">

        {{-- GRADIENT --}}
        <div class="absolute inset-0 bg-[#050816]"></div>

        {{-- BLUE GLOW --}}
        <div class="absolute top-[-180px] right-[-120px] h-[420px] w-[420px] rounded-full bg-blue-500/20 blur-3xl"></div>

        {{-- ORANGE GLOW --}}
        <div class="absolute bottom-[-200px] left-[-140px] h-[420px] w-[420px] rounded-full bg-orange-500/15 blur-3xl"></div>

        {{-- ANIMATED PARTICLES --}}
        <div class="particles"></div>

    </div>

    <div class="relative z-10 mx-auto max-w-7xl">

        <div class="grid gap-8 lg:grid-cols-[290px_minmax(0,1fr)] items-start">

            {{-- SIDEBAR --}}
            <aside class="sticky top-28 h-fit w-full">
                <div class="reveal-up sidebar-box">

                    <div class="absolute inset-0 rounded-[34px] bg-[radial-gradient(circle_at_top_right,rgba(37,99,235,0.15),transparent_40%)] pointer-events-none">
                    </div>

                    <div class="relative z-10">

                        {{-- PROFILE --}}
                        <div class="text-center">

                            <div class="mx-auto flex h-28 w-28 overflow-hidden rounded-full border border-blue-500/20 bg-[#111827]/30 shadow-[0_0_40px_rgba(37,99,235,0.25)]">
                                <img src="{{ $user->avatar_url }}"
                                    alt="{{ $user->name }}"
                                    class="h-full w-full object-cover">
                            </div>

                            <h2 class="mt-5 text-xl font-black text-white">
                                {{ $user->name }}
                            </h2>

                            <p class="mt-2 text-sm text-slate-400">
                                {{ $user->email }}
                            </p>

                        </div>

                        {{-- MENU --}}
                        <nav class="mt-8 space-y-3">

                            <a href="{{ Route::has('settings.profile') ? route('settings.profile') : url('/settings/profile') }}"
                                class="menu-item {{ $selectedTab === 'profile' ? 'menu-active-blue' : 'menu-normal' }}">
                                Edit Profil
                            </a>

                            <a href="{{ Route::has('settings.account') ? route('settings.account') : url('/settings/account') }}"
                                class="menu-item {{ $selectedTab === 'account' ? 'menu-active-blue' : 'menu-normal' }}">
                                Pengaturan Akun
                            </a>

                            @if(! $user->isGoogleAccount())
                            <a href="{{ Route::has('settings.password') ? route('settings.password') : url('/settings/password') }}"
                                class="menu-item {{ $selectedTab === 'password' ? 'menu-active-blue' : 'menu-normal' }}">
                                Ubah Password
                            </a>
                            @else
                            <div class="menu-item menu-normal cursor-not-allowed opacity-40 select-none">
                                Ubah Password
                            </div>
                            @endif

                            <a href="{{ Route::has('settings.section') ? route('settings.section', ['section' => 'security']) : url('/settings/security') }}"
                                class="menu-item {{ $selectedTab === 'security' ? 'menu-active-blue' : 'menu-normal' }}">
                                Verifikasi 2 Langkah
                            </a>

                            @unless($user->isAdmin())
                                <a href="{{ Route::has('settings.seller') ? route('settings.seller') : url('/settings/seller') }}"
                                    class="menu-item {{ $selectedTab === 'seller' ? 'menu-active-orange' : 'menu-normal' }}">
                                    Daftar Jadi Seller
                                </a>
                            @endunless

                        </nav>

                    </div>
                </div>
            </aside>

            {{-- MAIN --}}
            <main class="reveal-up main-box">

                {{-- ALERT --}}
                @if (session('success'))
                    <div class="mb-6 rounded-2xl border border-emerald-500/20 bg-emerald-500/10 p-4 text-emerald-200 backdrop-blur-md">
                        {{ session('success') }}
                    </div>
                @endif

                @if (session('status'))
                    <div class="mb-6 rounded-2xl border border-cyan-500/20 bg-cyan-500/10 p-4 text-cyan-200 backdrop-blur-md">
                        {{ session('status') }}
                    </div>
                @endif

                @if (session('warning'))
                    <div class="mb-6 rounded-2xl border border-amber-500/20 bg-amber-500/10 p-4 text-amber-200 backdrop-blur-md">
                        {{ session('warning') }}
                    </div>
                @endif

                @if ($errors->any())
                    <div class="mb-6 rounded-2xl border border-rose-500/20 bg-rose-500/10 p-4 text-rose-200 backdrop-blur-md">
                        <ul class="list-disc list-inside space-y-2 text-sm">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @if($user->isAdmin())
                    <div class="mb-6 rounded-2xl border border-amber-500/20 bg-amber-500/10 p-4 text-amber-100 backdrop-blur-md">
                        Menu buyer dan seller tidak tersedia untuk akun administrator.
                    </div>
                @endif

                {{-- PROFILE --}}
                @if ($selectedTab === 'profile')

                    <div class="mb-10">

                        <div class="inline-flex items-center gap-2 rounded-full border border-blue-500/30 bg-blue-500/10 px-4 py-2 text-[11px] font-bold tracking-[0.2em] text-blue-300">
                            <span class="h-2 w-2 rounded-full bg-blue-400"></span>
                            PROFILE SETTINGS
                        </div>

                        <h1 class="mt-5 text-4xl font-black text-white">
                            Edit Profil
                        </h1>

                        <p class="mt-3 text-sm leading-relaxed text-slate-400">
                            Kelola informasi profil akun Anda dengan tampilan modern dan futuristik.
                        </p>

                    </div>

                    <form action="{{ route('settings.update') }}"
                        method="POST"
                        enctype="multipart/form-data"
                        class="space-y-6">

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
                                class="input-style file:mr-4 file:rounded-xl file:border-0 file:bg-blue-500 file:px-4 file:py-2 file:text-white cursor-pointer">
                        </div>

                        <div class="grid gap-5 lg:grid-cols-2">

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

                        <div class="grid gap-5 lg:grid-cols-2">

                            <div class="card-box">

                                <label for="gender" class="label-style">
                                    Jenis Kelamin
                                </label>

                                <select name="gender"
                                    id="gender"
                                    class="input-style cursor-pointer">

                                    <option value="" class="bg-[#111827] text-white">
                                        Pilih
                                    </option>

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

                        <button type="submit"
                            class="submit-blue">
                            Simpan Perubahan Profil
                        </button>

                    </form>

                {{-- ACCOUNT --}}
                @elseif ($selectedTab === 'account')

                    <h1 class="mb-8 text-4xl font-black text-white">
                        Pengaturan Akun
                    </h1>

                    <div class="grid gap-6 lg:grid-cols-2">

                        <section class="card-box">

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

                        <section class="card-box border-amber-500/10 bg-amber-500/[0.02]">

                            <h2 class="text-xl font-bold text-white">
                                Nonaktifkan Akun
                            </h2>

                            <p class="mt-4 text-sm text-slate-400">
                                Kode verifikasi akan dikirim ke email sebelum akun dinonaktifkan. Setelah berhasil, Anda akan otomatis logout.
                            </p>

                            <form action="{{ route('settings.account.sendDeactivationCode') }}"
                                method="POST"
                                class="mt-6">
                                @csrf

                                <button type="submit"
                                    class="w-full rounded-2xl border border-amber-500/30 bg-amber-500/10 px-5 py-4 text-sm font-bold text-amber-200 transition hover:bg-amber-500/20">
                                    Kirim Kode Nonaktif
                                </button>
                            </form>

                            <form action="{{ route('settings.deactivate') }}"
                                method="POST"
                                class="mt-5 space-y-4">
                                @csrf

                                <input type="text"
                                    name="deactivation_code"
                                    inputmode="numeric"
                                    maxlength="6"
                                    placeholder="Kode Verifikasi"
                                    class="input-style">

                                <button type="submit"
                                    onclick="return confirm('Nonaktifkan akun sekarang? Anda akan otomatis logout.')"
                                    class="w-full rounded-2xl bg-amber-500 px-5 py-4 text-sm font-bold text-slate-950 transition hover:bg-amber-400">
                                    Nonaktifkan Akun
                                </button>
                            </form>

                        </section>

                        <section class="card-box border-rose-500/10 bg-rose-500/[0.02]">

                            <h2 class="text-xl font-bold text-white">
                                Hapus Akun
                            </h2>

                            <p class="mt-4 text-sm text-slate-400">
                                Sistem akan mengirimkan kode verifikasi sebelum akun dihapus permanen.
                            </p>

                            <a href="{{ route('settings.account.delete') }}"
                                class="mt-6 flex items-center justify-center rounded-2xl bg-rose-600 px-5 py-4 text-sm font-bold text-white transition hover:bg-rose-500">
                                Mulai Proses Hapus Akun
                            </a>

                        </section>

                    </div>

                {{-- PASSWORD --}}
                @elseif ($selectedTab === 'password')

                    <h1 class="mb-8 text-4xl font-black text-white">
                        Ubah Password
                    </h1>

                    @if($user->isGoogleAccount())
                        <div class="mb-6 rounded-2xl border border-amber-500/20 bg-amber-500/10 p-4 text-amber-200">
                            Akun ini login memakai Google, jadi perubahan password manual dinonaktifkan.
                        </div>
                    @endif

                    <div class="grid gap-6 lg:grid-cols-2">

                        <section class="card-box">

                            <h2 class="text-xl font-bold text-white">
                                Kirim Kode Verifikasi
                            </h2>

                            <p class="mt-4 text-sm text-slate-400">
                                Kode akan dikirim langsung ke email akun Anda.
                            </p>

                            <form action="{{ route('settings.password.sendCode') }}"
                                method="POST"
                                class="mt-6">

                                @csrf

                                <button type="submit" @disabled($user->isGoogleAccount())
                                    class="submit-blue disabled:opacity-40">
                                    Kirim Kode
                                </button>

                            </form>

                        </section>

                        <section class="card-box">

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
                                    class="input-style" @disabled($user->isGoogleAccount())>

                                <input type="password"
                                    name="password"
                                    placeholder="Password Baru"
                                    class="input-style" @disabled($user->isGoogleAccount())>

                                <input type="password"
                                    name="password_confirmation"
                                    placeholder="Konfirmasi Password"
                                    class="input-style" @disabled($user->isGoogleAccount())>

                                <button type="submit" @disabled($user->isGoogleAccount())
                                    class="submit-green disabled:opacity-40">
                                    Perbarui Password
                                </button>

                            </form>

                        </section>

                    </div>

                {{-- SECURITY / 2FA --}}
                @elseif ($selectedTab === 'security')

                    <div class="mb-10">

                        <div class="inline-flex items-center gap-2 rounded-full border border-cyan-500/30 bg-cyan-500/10 px-4 py-2 text-[11px] font-bold tracking-[0.2em] text-cyan-300">
                            <span class="h-2 w-2 rounded-full bg-cyan-400"></span>
                            TWO STEP VERIFICATION
                        </div>

                        <h1 class="mt-5 text-4xl font-black text-white">
                            Verifikasi 2 Langkah
                        </h1>

                        <p class="mt-3 text-sm leading-relaxed text-slate-400">
                            Aktifkan perlindungan tambahan untuk login akun Anda. Anda dapat memilih Email atau Google Authenticator.
                        </p>

                    </div>

                    <form id="two-factor-confirm-form" action="{{ route('settings.security.confirm') }}" method="POST" class="hidden">
                        @csrf
                    </form>

                    <form action="{{ route('settings.security.update') }}" method="POST" class="space-y-6">
                        @csrf
                        @method('PUT')

                        <section class="card-box">
                            <div class="flex items-start justify-between gap-4 flex-wrap">
                                <div>
                                    <h2 class="text-xl font-bold text-white">Status Verifikasi 2 Langkah</h2>
                                    <p class="mt-3 text-sm text-slate-400">Gunakan satu atau lebih metode berikut sesuai kebutuhan Anda.</p>
                                </div>

                                <label class="inline-flex items-center gap-3 rounded-2xl border border-white/10 bg-white/[0.02] px-4 py-3 text-sm text-slate-200 cursor-pointer">
                                    <input type="checkbox" name="two_factor_enabled" value="1" @checked(old('two_factor_enabled', $user->two_factor_enabled))>
                                    Aktifkan verifikasi 2 langkah
                                </label>
                            </div>
                        </section>

                        <section class="card-box">
                            <h2 class="text-xl font-bold text-white">Pilih Metode</h2>

                            <div class="mt-6 grid gap-4 lg:grid-cols-3">
                                <label class="rounded-3xl border border-white/10 bg-white/[0.02] p-5 text-sm text-slate-200 cursor-pointer">
                                    <div class="flex items-center gap-3">
                                        <input type="checkbox" name="two_factor_methods[]" value="email" @checked(in_array('email', old('two_factor_methods', $twoFactorMethods ?? []), true))>
                                        <span class="font-semibold text-white">Email</span>
                                    </div>
                                    <p class="mt-3 text-slate-400">Kode akan dikirim ke alamat email akun Anda.</p>
                                </label>

                                <label class="rounded-3xl border border-white/10 bg-white/[0.02] p-5 text-sm text-slate-200 cursor-pointer">
                                    <div class="flex items-center gap-3">
                                        <input type="checkbox" name="two_factor_methods[]" value="google" @checked(in_array('google', old('two_factor_methods', $twoFactorMethods ?? []), true))>
                                        <span class="font-semibold text-white">Google Authenticator</span>
                                    </div>
                                    <p class="mt-3 text-slate-400">Gunakan kode dari aplikasi authenticator.</p>
                                </label>
                            </div>
                        </section>

                        <section class="card-box">
                            <h2 class="text-xl font-bold text-white">Google Authenticator</h2>

                            @if($pendingTwoFactorSetup ?? false)
                                <div class="mt-6 grid gap-6 lg:grid-cols-[220px_minmax(0,1fr)] lg:items-center">
                                    @if($googleQrCode)
                                        <div class="rounded-3xl border border-white/10 bg-white p-4 flex items-center justify-center">
                                            {!! $googleQrCode !!}
                                        </div>
                                    @endif

                                    <div>
                                        <p class="text-sm text-slate-400">Scan QR code di bawah dengan Google Authenticator, lalu masukkan kode 6 digit untuk mengaktifkan dan menyimpan pengaturan.</p>
                                        <div class="mt-4 rounded-2xl border border-cyan-500/20 bg-cyan-500/10 p-4 font-mono text-sm text-cyan-100 break-all">
                                            {{ $googleSecret }}
                                        </div>
                                    </div>
                                </div>

                                <div class="mt-6 space-y-4">
                                    <input type="text"
                                        name="verification_code"
                                        inputmode="numeric"
                                        maxlength="6"
                                        placeholder="Masukkan kode Authenticator"
                                        form="two-factor-confirm-form"
                                        class="input-style">

                                    <button type="submit"
                                        form="two-factor-confirm-form"
                                        class="submit-green">
                                        Konfirmasi & Simpan Google Authenticator
                                    </button>
                                </div>

                                <p class="mt-4 text-xs text-slate-500">Pengaturan belum disimpan permanen sampai kode Authenticator valid.</p>

                            @elseif(in_array('google', old('two_factor_methods', $twoFactorMethods ?? []), true) && $user->two_factor_confirmed_at)
                                <div class="mt-6 rounded-3xl border border-emerald-500/20 bg-emerald-500/10 p-6">
                                    <p class="text-sm text-white font-semibold">Google Authenticator sudah terkonfirmasi.</p>
                                    <p class="mt-2 text-slate-400">QR dan secret tidak ditampilkan setelah konfigurasi berhasil. Anda dapat menggunakan Google Authenticator untuk login.</p>
                                </div>
                            @else
                                <p class="mt-4 text-sm text-slate-400">Secret Google Authenticator akan dibuat otomatis saat Anda mengaktifkan metode ini.</p>
                            @endif
                        </section>

                        <section class="card-box">
                            <h2 class="text-xl font-bold text-white">Informasi Pendukung</h2>
                            <div class="mt-4 grid gap-4 lg:grid-cols-2 text-sm text-slate-300">
                                <div class="rounded-2xl border border-white/10 bg-white/[0.02] p-4">
                                    <div class="text-slate-500">Email Terdaftar</div>
                                    <div class="mt-1 text-white">{{ $user->email }}</div>
                                </div>
                                <div class="rounded-2xl border border-white/10 bg-white/[0.02] p-4">
                                    <div class="text-slate-500">Nomor Telepon</div>
                                    <div class="mt-1 text-white">{{ $user->phone ?? 'Belum diisi' }}</div>
                                </div>
                            </div>
                        </section>

                        <button type="submit" class="submit-blue">
                            Simpan Pengaturan Verifikasi 2 Langkah
                        </button>
                    </form>

                {{-- SELLER --}}
                @elseif($user->seller_status === 'pending')
                
                    <div class="mb-10">

                        <div class="inline-flex items-center gap-2 rounded-full border border-yellow-500/30 bg-yellow-500/10 px-4 py-2 text-[11px] font-bold tracking-[0.2em] text-yellow-300">
                            <span class="h-2 w-2 rounded-full bg-yellow-400 animate-pulse"></span>
                            SELLER VERIFICATION
                        </div>

                        <h1 class="mt-5 text-4xl font-black text-white">
                            Pengajuan Seller Dalam Proses
                        </h1>

                        <p class="mt-3 text-sm leading-relaxed text-slate-400">
                            Terima kasih telah mendaftar sebagai seller. Kami sedang meninjau pengajuan Anda dan akan memberitahu hasil verifikasi segera.
                        </p>

                    </div>

                    <div class="overflow-hidden rounded-[34px] border border-yellow-500/10 bg-gradient-to-br from-[#111827]/30 to-[#0B1220]/30 p-8 backdrop-blur-md">

                        <div class="flex items-center gap-4">
                            <svg class="w-12 h-12 text-yellow-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <div>
                                <h2 class="text-2xl font-black text-yellow-200">Status: Menunggu Verifikasi</h2>
                                <p class="text-sm text-yellow-300 mt-2">Pengajuan Anda sedang ditinjau oleh tim kami. Proses verifikasi biasanya memakan waktu 1-3 hari kerja. Anda akan menerima notifikasi via email saat status berubah.</p>
                            </div>
                        </div>

                    </div>

                @elseif($user->seller_status === 'rejected')

                    <div class="mb-10">

                        <div class="inline-flex items-center gap-2 rounded-full border border-red-500/30 bg-red-500/10 px-4 py-2 text-[11px] font-bold tracking-[0.2em] text-red-300">
                            <span class="h-2 w-2 rounded-full bg-red-400"></span>
                            SELLER REJECTED
                        </div>

                        <h1 class="mt-5 text-4xl font-black text-white">
                            Pengajuan Seller Ditolak
                        </h1>

                        <p class="mt-3 text-sm leading-relaxed text-slate-400">
                            Maaf, pengajuan seller Anda tidak disetujui. Silakan pelajari alasan penolakan di bawah dan coba lagi dengan informasi yang lebih lengkap.
                        </p>

                    </div>

                    <div class="overflow-hidden rounded-[34px] border border-red-500/10 bg-gradient-to-br from-[#111827]/30 to-[#0B1220]/30 p-8 backdrop-blur-md">

                        <div class="flex items-start gap-4 mb-6">
                            <svg class="w-8 h-8 text-red-500 flex-shrink-0 mt-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                            <div>
                                <h2 class="text-xl font-black text-red-200">Alasan Penolakan:</h2>
                                <p class="text-sm text-red-300 mt-2">{{ $user->seller_rejection_reason ?? 'Alasan penolakan tidak tersedia.' }}</p>
                            </div>
                        </div>

                        <a href="{{ route('seller.register.form') }}"
                            class="inline-flex items-center justify-center rounded-2xl bg-red-500 px-5 py-4 text-sm font-bold text-white transition duration-300 hover:scale-[1.02] hover:bg-red-600">
                            Ajukan Ulang
                        </a>

                    </div>

                @else

                    <div class="mb-10">

                        <div class="inline-flex items-center gap-2 rounded-full border border-orange-500/30 bg-orange-500/10 px-4 py-2 text-[11px] font-bold tracking-[0.2em] text-orange-300">
                            <span class="h-2 w-2 rounded-full bg-orange-400"></span>
                            SELLER ACCESS
                        </div>

                        <h1 class="mt-5 text-4xl font-black text-white">
                            Daftar Jadi Seller
                        </h1>

                        <p class="mt-3 text-sm leading-relaxed text-slate-400">
                            Mulai membuka toko digital dan jual item gaming Anda sekarang juga.
                        </p>

                    </div>

                    <div class="overflow-hidden rounded-[34px] border border-orange-500/10 bg-gradient-to-br from-[#111827]/30 to-[#0B1220]/30 p-8 backdrop-blur-md">

                        <div class="grid gap-8 lg:grid-cols-[1fr_280px] lg:items-center">

                            <div>

                                @if ($user->isSellerAccount())

                                    <div class="inline-flex items-center gap-2 rounded-full border border-emerald-500/20 bg-emerald-500/10 px-4 py-2 text-xs font-bold text-emerald-300">
                                        ✓ Seller Aktif
                                    </div>

                                    <h2 class="mt-5 text-3xl font-black text-white">
                                        Akun Seller Sudah Aktif
                                    </h2>

                                    <p class="mt-4 text-sm leading-relaxed text-slate-400">
                                        Anda sudah dapat menjual produk dan menerima transaksi.
                                    </p>

                                @else

                                    <div class="inline-flex items-center gap-2 rounded-full border border-orange-500/20 bg-orange-500/10 px-4 py-2 text-xs font-bold text-orange-300">
                                        SELLER REGISTRATION
                                    </div>

                                    <h2 class="mt-5 text-3xl font-black text-white">
                                        Mulai Jadi Seller
                                    </h2>

                                    <p class="mt-4 text-sm leading-relaxed text-slate-400">
                                        Ajukan akun seller dan mulai bisnis digital gaming Anda.
                                    </p>

                                @endif

                                <div class="mt-8 grid gap-4 sm:grid-cols-2">

                                    <a href="{{ route('seller.register.form') }}"
                                        class="flex items-center justify-center rounded-2xl bg-orange-500 px-5 py-4 text-sm font-bold text-slate-950 transition duration-300 hover:scale-[1.02] hover:bg-orange-400">
                                        Ajukan Jadi Seller
                                    </a>

                                    <a href="{{ route('seller.dashboard') }}"
                                        class="flex items-center justify-center rounded-2xl border border-white/10 bg-white/[0.02] px-5 py-4 text-sm font-bold text-white transition duration-300 hover:border-orange-500/30 hover:bg-orange-500/[0.04]">
                                        Buka Dashboard Seller
                                    </a>

                                </div>

                            </div>

                            <div class="hidden lg:flex justify-center">

                                <div class="flex h-56 w-56 items-center justify-center rounded-full border border-orange-500/20 bg-orange-500/[0.02] shadow-[0_0_60px_rgba(249,115,22,0.1)]">
                                    <img src="{{ asset('storage/app/public/logo/logo.png') }}"
                                        alt="Logo"
                                        class="h-36 w-36 object-contain opacity-85">
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
body{
    overflow-x: hidden;
    background: #050816;
}

/* REMOVE GHOST LINE LAYOUT */
body::before,
body::after{
    display: none !important;
}

/* PARTICLES (FOKUS: Menghidupkan titik partikel di belakang panel) */
.particles{
    position: absolute;
    inset: 0;
    background-image:
        radial-gradient(rgba(37,99,235,.4) 1.5px, transparent 1.5px),
        radial-gradient(rgba(249,115,22,.22) 1.5px, transparent 1.5px);
    background-size: 100px 100px;
    background-position: 0 0, 50px 50px;
    animation: particleMove 20s linear infinite;
    opacity: 0.45;
}

@keyframes particleMove{
    from{ transform: translateY(0); }
    to{ transform: translateY(-100px); }
}

/* SIDEBAR BOX (FOKUS: Efek Kaca Transparan Tembus Pandang) */
.sidebar-box {
    border: 1px solid rgba(255, 255, 255, .1);
    background: rgba(11, 18, 32, 0.35); /* Transparansi tipis 35% */
    border-radius: 34px;
    padding: 24px;
    backdrop-filter: blur(25px);
    -webkit-backdrop-filter: blur(25px);
    box-shadow: 0 20px 50px rgba(0, 0, 0, 0.3);
}

/* MAIN PANEL BOX (FOKUS: Efek Kaca Transparan Tembus Pandang) */
.main-box {
    border: 1px solid rgba(255, 255, 255, .1);
    background: rgba(11, 18, 32, 0.35); /* Transparansi tipis 35% */
    border-radius: 36px;
    padding: 32px;
    backdrop-filter: blur(25px);
    -webkit-backdrop-filter: blur(25px);
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.4);
}

/* MENU NAVIGASI */
.menu-item{
    display: flex;
    align-items: center;
    border-radius: 20px;
    padding: 16px 20px;
    font-size: 14px;
    font-weight: 700;
    transition: .3s cubic-bezier(.25,.8,.25,1);
}

.menu-normal{
    border: 1px solid rgba(255,255,255,.04);
    background: rgba(255,255,255,.01);
    color: #94a3b8;
}

.menu-normal:hover{
    transform: translateX(6px);
    border-color: rgba(59,130,246,.25);
    background: rgba(59,130,246,.06);
    color: white;
}

.menu-active-blue{
    border: 1px solid rgba(59,130,246,.3);
    background: rgba(59,130,246,.12);
    color: white;
    box-shadow: 0 8px 30px rgba(37,99,235,.12);
}

.menu-active-orange{
    border: 1px solid rgba(249,115,22,.3);
    background: rgba(249,115,22,.12);
    color: white;
    box-shadow: 0 8px 30px rgba(249,115,22,.12);
}

/* SUB-CARD CONTAINER DI DALAM PANEL */
.card-box{
    border: 1px solid rgba(255,255,255,.05);
    background: rgba(255,255,255,.015);
    border-radius: 30px;
    padding: 24px;
    backdrop-filter: blur(15px);
}

/* LABEL */
.label-style{
    display: block;
    margin-bottom: 12px;
    font-size: 14px;
    font-weight: 700;
    color: #cbd5e1;
}

/* INPUT FORM */
.input-style{
    width: 100%;
    border-radius: 18px;
    border: 1px solid rgba(255,255,255,.06);
    background: rgba(17, 24, 39, 0.45);
    padding: 16px 18px;
    color: white;
    outline: none;
    transition: .25s ease;
    backdrop-filter: blur(10px);
}

.input-style:focus{
    border-color: rgba(59,130,246,.45);
    box-shadow: 0 0 15px rgba(59,130,246,.15);
    background: rgba(17, 24, 39, 0.75);
}

/* BUTTONS */
.submit-blue{
    width: 100%;
    border-radius: 22px;
    background: linear-gradient(to right, #2563eb, #3b82f6);
    padding: 16px;
    font-size: 14px;
    font-weight: 800;
    color: white;
    transition: .3s ease;
    box-shadow: 0 4px 20px rgba(37,99,235,.2);
}

.submit-blue:hover{
    transform: translateY(-2px);
    box-shadow: 0 10px 30px rgba(37,99,235,.35);
}

.submit-green{
    width: 100%;
    border-radius: 22px;
    background: linear-gradient(to right, #059669, #10b981);
    padding: 16px;
    font-size: 14px;
    font-weight: 800;
    color: white;
    transition: .3s ease;
    box-shadow: 0 4px 20px rgba(16,185,129,.2);
}

.submit-green:hover{
    transform: translateY(-2px);
    box-shadow: 0 10px 30px rgba(16,185,129,.35);
}

/* REVEAL ENTRANCE ANIMATION */
.reveal-up{
    opacity: 0;
    transform: translateY(30px);
    animation: revealUp 0.8s cubic-bezier(.16,1,.3,1) forwards;
}

@keyframes revealUp{
    to{
        opacity: 1;
        transform: translateY(0);
    }
}
</style>
@endsection
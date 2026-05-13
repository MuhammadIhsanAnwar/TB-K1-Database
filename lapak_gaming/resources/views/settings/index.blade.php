@extends('layouts.app')

@section('content')
<div class="relative overflow-x-hidden bg-[#050816] px-4 pb-24 pt-32">

    {{-- BACKGROUND GLOBAL --}}
    <div class="fixed inset-0 -z-50 overflow-hidden">

        {{-- GRADIENT --}}
        <div class="absolute inset-0 bg-[#050816]"></div>

        {{-- BLUE GLOW --}}
        <div class="absolute top-[-180px] right-[-120px] h-[420px] w-[420px] rounded-full bg-blue-500/10 blur-3xl"></div>

        {{-- ORANGE GLOW --}}
        <div class="absolute bottom-[-200px] left-[-140px] h-[420px] w-[420px] rounded-full bg-orange-500/10 blur-3xl"></div>

        {{-- ANIMATED PARTICLES --}}
        <div class="particles"></div>

    </div>

    <div class="relative z-10 mx-auto max-w-7xl">

        <div class="grid gap-8 lg:grid-cols-[290px_minmax(0,1fr)]">

            {{-- SIDEBAR --}}
            <aside
                class="reveal-up sticky top-28 h-fit rounded-[34px] border border-white/10 bg-[#0B1220]/85 p-6 backdrop-blur-2xl shadow-[0_0_60px_rgba(37,99,235,0.08)]">

                <div
                    class="absolute inset-0 rounded-[34px] bg-[radial-gradient(circle_at_top_right,rgba(37,99,235,0.15),transparent_40%)]">
                </div>

                <div class="relative z-10">

                    {{-- PROFILE --}}
                    <div class="text-center">

                        <div
                            class="mx-auto flex h-28 w-28 overflow-hidden rounded-full border border-blue-500/20 bg-[#111827] shadow-[0_0_40px_rgba(37,99,235,0.25)]">

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

                        <a href="{{ route('settings.profile') }}"
                            class="menu-item {{ $selectedTab === 'profile' ? 'menu-active-blue' : 'menu-normal' }}">
                            Edit Profil
                        </a>

                        <a href="{{ route('settings.account') }}"
                            class="menu-item {{ $selectedTab === 'account' ? 'menu-active-blue' : 'menu-normal' }}">
                            Pengaturan Akun
                        </a>

                        <a href="{{ route('settings.password') }}"
                            class="menu-item {{ $selectedTab === 'password' ? 'menu-active-blue' : 'menu-normal' }}">
                            Ubah Password
                        </a>

                        <a href="{{ route('settings.seller') }}"
                            class="menu-item {{ $selectedTab === 'seller' ? 'menu-active-orange' : 'menu-normal' }}">
                            Daftar Jadi Seller
                        </a>

                    </nav>

                </div>
            </aside>

            {{-- MAIN --}}
            <main
                class="reveal-up rounded-[36px] border border-white/10 bg-[#0B1220]/88 p-8 backdrop-blur-2xl shadow-[0_0_70px_rgba(37,99,235,0.08)]">

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

                    <div class="mb-10">

                        <div
                            class="inline-flex items-center gap-2 rounded-full border border-blue-500/30 bg-blue-500/10 px-4 py-2 text-[11px] font-bold tracking-[0.2em] text-blue-300">

                            <span class="h-2 w-2 rounded-full bg-emerald-400"></span>
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
                                class="input-style
                                file:mr-4
                                file:rounded-xl
                                file:border-0
                                file:bg-blue-500
                                file:px-4
                                file:py-2
                                file:text-white">
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
                                    class="input-style">

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

                                    <option value="other"
                                        class="bg-[#111827] text-white"
                                        @selected(old('gender', $profile?->gender) === 'other')>
                                        Lainnya
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
                                    class="input-style">
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

                        <section class="card-box border-rose-500/10 bg-rose-500/[0.03]">

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

                                <button type="submit"
                                    class="submit-blue">
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
                                    class="input-style">

                                <input type="password"
                                    name="password"
                                    placeholder="Password Baru"
                                    class="input-style">

                                <input type="password"
                                    name="password_confirmation"
                                    placeholder="Konfirmasi Password"
                                    class="input-style">

                                <button type="submit"
                                    class="submit-green">
                                    Perbarui Password
                                </button>

                            </form>

                        </section>

                    </div>

                {{-- SELLER --}}
                @else

                    <div class="mb-10">

                        <div
                            class="inline-flex items-center gap-2 rounded-full border border-orange-500/30 bg-orange-500/10 px-4 py-2 text-[11px] font-bold tracking-[0.2em] text-orange-300">

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

                    <div
                        class="overflow-hidden rounded-[34px] border border-orange-500/10 bg-gradient-to-br from-[#111827] to-[#0B1220] p-8">

                        <div class="grid gap-8 lg:grid-cols-[1fr_280px] lg:items-center">

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
                                        Anda sudah dapat menjual produk dan menerima transaksi.
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
                                        Ajukan akun seller dan mulai bisnis digital gaming Anda.
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
                                    class="flex h-56 w-56 items-center justify-center rounded-full border border-orange-500/20 bg-orange-500/[0.04] shadow-[0_0_60px_rgba(249,115,22,0.15)]">

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

<style>
html,
body{
    overflow-x:hidden;
    background:#050816;
}

/* REMOVE GHOST LINE */
body::before,
body::after{
    display:none !important;
}

/* PARTICLES */
.particles{
    position:absolute;
    inset:0;
    background-image:
        radial-gradient(rgba(37,99,235,.35) 1px, transparent 1px),
        radial-gradient(rgba(249,115,22,.18) 1px, transparent 1px);
    background-size:120px 120px;
    background-position:0 0,60px 60px;
    animation:particleMove 18s linear infinite;
    opacity:.25;
}

@keyframes particleMove{
    from{
        transform:translateY(0);
    }
    to{
        transform:translateY(-120px);
    }
}

/* MENU */
.menu-item{
    display:flex;
    align-items:center;
    border-radius:20px;
    padding:16px 20px;
    font-size:14px;
    font-weight:700;
    transition:.35s;
}

.menu-normal{
    border:1px solid rgba(255,255,255,.06);
    background:rgba(255,255,255,.03);
    color:#94a3b8;
}

.menu-normal:hover{
    transform:translateX(6px);
    border-color:rgba(59,130,246,.25);
    background:rgba(59,130,246,.08);
    color:white;
}

.menu-active-blue{
    border:1px solid rgba(59,130,246,.3);
    background:rgba(59,130,246,.12);
    color:white;
    box-shadow:0 0 25px rgba(37,99,235,.16);
}

.menu-active-orange{
    border:1px solid rgba(249,115,22,.3);
    background:rgba(249,115,22,.12);
    color:white;
    box-shadow:0 0 25px rgba(249,115,22,.15);
}

/* CARD */
.card-box{
    border:1px solid rgba(255,255,255,.05);
    background:rgba(255,255,255,.03);
    border-radius:30px;
    padding:24px;
    backdrop-filter:blur(18px);
}

/* LABEL */
.label-style{
    display:block;
    margin-bottom:12px;
    font-size:14px;
    font-weight:700;
    color:#cbd5e1;
}

/* INPUT */
.input-style{
    width:100%;
    border-radius:18px;
    border:1px solid rgba(255,255,255,.08);
    background:#111827;
    padding:16px 18px;
    color:white;
    outline:none;
    transition:.3s;
}

.input-style:focus{
    border-color:rgba(59,130,246,.45);
    box-shadow:0 0 0 4px rgba(59,130,246,.08);
}

/* BUTTON */
.submit-blue{
    width:100%;
    border-radius:22px;
    background:linear-gradient(to right,#2563eb,#3b82f6);
    padding:16px;
    font-size:14px;
    font-weight:800;
    color:white;
    transition:.35s;
}

.submit-blue:hover{
    transform:translateY(-2px);
    box-shadow:0 0 35px rgba(37,99,235,.28);
}

.submit-green{
    width:100%;
    border-radius:22px;
    background:linear-gradient(to right,#059669,#10b981);
    padding:16px;
    font-size:14px;
    font-weight:800;
    color:white;
    transition:.35s;
}

.submit-green:hover{
    transform:translateY(-2px);
    box-shadow:0 0 35px rgba(16,185,129,.25);
}

/* ANIMATION */
.reveal-up{
    opacity:0;
    transform:translateY(50px);
    animation:revealUp 1s cubic-bezier(.22,1,.36,1) forwards;
}

@keyframes revealUp{
    to{
        opacity:1;
        transform:translateY(0);
    }
}
</style>
@endsection
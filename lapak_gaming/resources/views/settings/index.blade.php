@extends('layouts.app')

@section('content')
<div class="relative overflow-x-hidden bg-[#050816] px-4 pb-24 pt-32">

    {{-- BACKGROUND GLOBAL --}}
    <div class="fixed inset-0 -z-50 overflow-hidden">

        {{-- MAIN BG --}}
        <div
            class="absolute inset-0 bg-[radial-gradient(circle_at_top_left,#101b38_0%,#050816_45%),radial-gradient(circle_at_bottom_right,#2a1408_0%,#050816_45%)]">
        </div>

        {{-- BLUE GLOW --}}
        <div
            class="absolute top-[-180px] right-[-120px] h-[420px] w-[420px] rounded-full bg-blue-500/20 blur-3xl animate-pulse">
        </div>

        {{-- ORANGE GLOW --}}
        <div
            class="absolute bottom-[-200px] left-[-140px] h-[420px] w-[420px] rounded-full bg-orange-500/20 blur-3xl animate-pulse">
        </div>

        {{-- PARTICLES --}}
        <div class="particles"></div>

    </div>

    <div class="relative z-10 mx-auto max-w-7xl">

        <div class="grid gap-8 lg:grid-cols-[290px_minmax(0,1fr)]">

            {{-- SIDEBAR --}}
            <aside
                class="reveal-up sticky top-28 h-fit overflow-hidden rounded-[34px] border border-white/10 bg-white/[0.04] p-6 backdrop-blur-3xl shadow-[0_10px_40px_rgba(0,0,0,.45)]">

                {{-- GLOW --}}
                <div
                    class="absolute inset-0 rounded-[34px] bg-[radial-gradient(circle_at_top_right,rgba(37,99,235,0.15),transparent_40%)]">
                </div>

                {{-- LIGHT --}}
                <div
                    class="absolute inset-0 bg-[linear-gradient(120deg,rgba(255,255,255,.08),transparent_35%)]">
                </div>

                <div class="relative z-10">

                    {{-- PROFILE --}}
                    <div class="text-center">

                        <div
                            class="mx-auto flex h-28 w-28 overflow-hidden rounded-full border border-blue-500/20 bg-[#111827]/80 shadow-[0_0_40px_rgba(37,99,235,0.25)] backdrop-blur-xl">

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

                        <div class="menu-item menu-normal cursor-not-allowed opacity-60">
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

            </aside>

            {{-- MAIN --}}
            <main
                class="reveal-up overflow-hidden rounded-[36px] border border-white/10 bg-white/[0.04] p-8 backdrop-blur-3xl shadow-[0_10px_60px_rgba(0,0,0,.45)]">

                {{-- LIGHT --}}
                <div
                    class="pointer-events-none absolute inset-0 bg-[linear-gradient(120deg,rgba(255,255,255,.08),transparent_35%)]">
                </div>

                <div class="relative z-10">

                    {{-- ALERT --}}
                    @if (session('success'))
                    <div class="mb-6 rounded-2xl border border-emerald-500/20 bg-emerald-500/10 p-4 text-emerald-200 backdrop-blur-xl">
                        {{ session('success') }}
                    </div>
                    @endif

                    {{-- PROFILE --}}
                    @if ($selectedTab === 'profile')

                    <div class="mb-10">

                        <div
                            class="inline-flex items-center gap-2 rounded-full border border-blue-500/30 bg-blue-500/10 px-4 py-2 text-[11px] font-bold tracking-[0.2em] text-blue-300 backdrop-blur-xl">

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
                                class="input-style">

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

                        <button type="submit"
                            class="submit-blue">
                            Simpan Perubahan Profil
                        </button>

                    </form>

                    @endif

                </div>

            </main>

        </div>

    </div>
</div>

<style>
html,
body{
    overflow-x:hidden;

    background:
        radial-gradient(circle at top left,#101b38 0%,#050816 45%),
        radial-gradient(circle at bottom right,#2a1408 0%,#050816 45%);

    background-color:#050816;
    color:white;
}

/* GLOBAL */
*{
    -webkit-font-smoothing:antialiased;
    -moz-osx-font-smoothing:grayscale;
    box-sizing:border-box;
}

/* PARTICLES */
.particles{
    position:absolute;
    inset:0;

    background-image:
        radial-gradient(rgba(59,130,246,.45) 1px, transparent 1px),
        radial-gradient(rgba(249,115,22,.22) 1px, transparent 1px);

    background-size:120px 120px;
    background-position:0 0,60px 60px;

    animation:particleMove 18s linear infinite;

    opacity:.30;
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

    border-radius:22px;

    padding:16px 20px;

    font-size:14px;
    font-weight:700;

    transition:.35s ease;

    position:relative;
    overflow:hidden;
}

.menu-item::before{
    content:'';

    position:absolute;
    inset:0;

    background:
        linear-gradient(
            120deg,
            rgba(255,255,255,.08),
            transparent
        );

    opacity:0;
    transition:.35s;
}

.menu-item:hover::before{
    opacity:1;
}

.menu-normal{
    border:1px solid rgba(255,255,255,.06);

    background:
        linear-gradient(
            135deg,
            rgba(255,255,255,.05),
            rgba(255,255,255,.02)
        );

    color:#94a3b8;

    backdrop-filter:blur(12px);
}

.menu-normal:hover{
    transform:translateX(8px);

    border-color:rgba(59,130,246,.28);

    background:
        linear-gradient(
            135deg,
            rgba(59,130,246,.18),
            rgba(59,130,246,.06)
        );

    color:white;

    box-shadow:
        0 0 25px rgba(37,99,235,.18);
}

.menu-active-blue{
    border:1px solid rgba(59,130,246,.35);

    background:
        linear-gradient(
            135deg,
            rgba(59,130,246,.22),
            rgba(59,130,246,.08)
        );

    color:white;

    box-shadow:
        0 0 30px rgba(37,99,235,.22);
}

.menu-active-orange{
    border:1px solid rgba(249,115,22,.35);

    background:
        linear-gradient(
            135deg,
            rgba(249,115,22,.18),
            rgba(249,115,22,.06)
        );

    color:white;

    box-shadow:
        0 0 30px rgba(249,115,22,.18);
}

/* CARD */
.card-box{
    position:relative;
    overflow:hidden;

    border:1px solid rgba(255,255,255,.06);

    background:
        linear-gradient(
            145deg,
            rgba(255,255,255,.06),
            rgba(255,255,255,.025)
        );

    border-radius:32px;

    padding:24px;

    backdrop-filter:blur(22px);

    box-shadow:
        inset 0 1px 0 rgba(255,255,255,.05),
        0 8px 30px rgba(0,0,0,.30);

    transition:.4s ease;
}

.card-box:hover{
    transform:translateY(-4px);

    border-color:rgba(59,130,246,.15);

    box-shadow:
        0 15px 45px rgba(0,0,0,.35),
        0 0 40px rgba(37,99,235,.10);
}

/* LABEL */
.label-style{
    display:block;

    margin-bottom:12px;

    font-size:13px;
    letter-spacing:.04em;

    font-weight:700;

    color:#dbe7ff;
}

/* INPUT */
.input-style{
    width:100%;

    border-radius:20px;

    border:1px solid rgba(255,255,255,.06);

    background:
        linear-gradient(
            135deg,
            rgba(17,24,39,.82),
            rgba(17,24,39,.55)
        );

    backdrop-filter:blur(16px);

    padding:16px 18px;

    color:white;

    outline:none;

    transition:.3s;
}

.input-style::placeholder{
    color:#64748b;
}

.input-style:focus{
    border-color:rgba(59,130,246,.45);

    box-shadow:
        0 0 0 4px rgba(59,130,246,.08),
        0 0 20px rgba(37,99,235,.15);
}

/* BUTTON */
.submit-blue{
    position:relative;
    overflow:hidden;

    width:100%;

    border:none;

    border-radius:22px;

    background:
        linear-gradient(
            to right,
            #2563eb,
            #3b82f6
        );

    padding:16px;

    font-size:14px;
    font-weight:800;

    color:white;

    transition:.35s ease;
}

.submit-blue::before{
    content:'';

    position:absolute;
    inset:0;

    background:
        linear-gradient(
            120deg,
            rgba(255,255,255,.18),
            transparent 30%
        );
}

.submit-blue:hover{
    transform:translateY(-2px);

    box-shadow:
        0 0 35px rgba(37,99,235,.28);
}

/* REVEAL */
.reveal-up{
    opacity:0;

    transform:translateY(50px);

    animation:
        revealUp 1s cubic-bezier(.22,1,.36,1) forwards;
}

@keyframes revealUp{
    to{
        opacity:1;
        transform:translateY(0);
    }
}
</style>
@endsection
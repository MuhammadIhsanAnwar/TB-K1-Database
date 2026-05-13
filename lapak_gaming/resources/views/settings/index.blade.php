@extends('layouts.app')

@section('content')
<div class="min-h-screen overflow-x-hidden bg-[#060816] px-4 pt-28 pb-16">

    {{-- BACKGROUND EFFECT --}}
    <div class="pointer-events-none fixed inset-0 overflow-hidden">
        <div class="absolute top-[-120px] right-[-120px] h-[320px] w-[320px] rounded-full bg-blue-500/10 blur-3xl"></div>
        <div class="absolute bottom-[-140px] left-[-120px] h-[320px] w-[320px] rounded-full bg-orange-500/10 blur-3xl"></div>
    </div>

    <div class="relative z-10 max-w-7xl mx-auto grid gap-8 lg:grid-cols-[300px_minmax(0,1fr)]">

        {{-- SIDEBAR --}}
        <aside class="reveal-left rounded-[30px] border border-blue-500/10 bg-[#0B1220]/95 p-6 backdrop-blur-xl shadow-[0_0_30px_rgba(37,99,235,0.08)]">

            <div class="mb-8 text-center">
                <img
                    src="{{ $user->avatar_url }}"
                    alt="{{ $user->name }}"
                    class="mx-auto h-24 w-24 rounded-full border border-slate-700 object-cover">

                <h2 class="mt-4 text-xl font-semibold text-white">
                    {{ $user->name }}
                </h2>

                <p class="text-sm text-slate-400">
                    {{ $user->email }}
                </p>
            </div>

            <nav class="space-y-2">

                <a href="{{ route('settings.profile') }}"
                    class="block rounded-2xl px-4 py-3 text-sm font-medium transition
                    {{ $selectedTab === 'profile'
                        ? 'bg-blue-500/15 border border-blue-500/20 text-white shadow-[0_0_20px_rgba(37,99,235,0.15)]'
                        : 'text-slate-400 hover:bg-blue-500/10 hover:text-white hover:border hover:border-blue-500/20' }}">
                    Edit Profil
                </a>

                <a href="{{ route('settings.account') }}"
                    class="block rounded-2xl px-4 py-3 text-sm font-medium transition
                    {{ $selectedTab === 'account'
                        ? 'bg-blue-500/15 border border-blue-500/20 text-white shadow-[0_0_20px_rgba(37,99,235,0.15)]'
                        : 'text-slate-400 hover:bg-blue-500/10 hover:text-white hover:border hover:border-blue-500/20' }}">
                    Pengaturan Akun
                </a>

                <a href="{{ route('settings.password') }}"
                    class="block rounded-2xl px-4 py-3 text-sm font-medium transition
                    {{ $selectedTab === 'password'
                        ? 'bg-blue-500/15 border border-blue-500/20 text-white shadow-[0_0_20px_rgba(37,99,235,0.15)]'
                        : 'text-slate-400 hover:bg-blue-500/10 hover:text-white hover:border hover:border-blue-500/20' }}">
                    Ubah Password
                </a>

                <a href="{{ route('settings.seller') }}"
                    class="block rounded-2xl px-4 py-3 text-sm font-medium transition
                    {{ $selectedTab === 'seller'
                        ? 'bg-blue-500/15 border border-blue-500/20 text-white shadow-[0_0_20px_rgba(37,99,235,0.15)]'
                        : 'text-slate-400 hover:bg-blue-500/10 hover:text-white hover:border hover:border-blue-500/20' }}">
                    Daftar Jadi Seller
                </a>

            </nav>
        </aside>

        {{-- MAIN CONTENT --}}
        <main class="reveal-up rounded-[30px] border border-white/5 bg-[#0B1220]/95 p-8 backdrop-blur-xl shadow-[0_0_30px_rgba(37,99,235,0.08)]">

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

                <h1 class="mb-8 text-3xl font-bold text-white">
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
                            class="w-full rounded-2xl border border-slate-700 bg-[#091225] px-4 py-3 text-slate-200">
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
                            class="w-full rounded-2xl border border-slate-700 bg-[#091225] px-4 py-3 text-slate-200 focus:border-blue-500 focus:outline-none">
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
                            class="w-full rounded-2xl border border-slate-700 bg-[#091225] px-4 py-3 text-slate-200 focus:border-blue-500 focus:outline-none">
                    </div>

                    <div class="grid gap-4 lg:grid-cols-2">

                        <div>
                            <label for="gender"
                                class="mb-2 block text-sm font-medium text-slate-300">
                                Jenis Kelamin
                            </label>

                            <select
                                name="gender"
                                id="gender"
                                class="w-full rounded-2xl border border-slate-700 bg-[#091225] px-4 py-3 text-slate-200 focus:border-blue-500 focus:outline-none">

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
                            <label for="birth_date"
                                class="mb-2 block text-sm font-medium text-slate-300">
                                Tanggal Lahir
                            </label>

                            <input type="date"
                                name="birth_date"
                                id="birth_date"
                                value="{{ old('birth_date', optional($profile?->birth_date)->format('Y-m-d')) }}"
                                class="w-full rounded-2xl border border-slate-700 bg-[#091225] px-4 py-3 text-slate-200 focus:border-blue-500 focus:outline-none">
                        </div>

                    </div>

                    <button type="submit"
                        class="w-full rounded-2xl bg-blue-600 px-5 py-3 text-sm font-semibold text-white transition hover:bg-blue-500">
                        Simpan Perubahan Profil
                    </button>

                </form>

            {{-- ACCOUNT --}}
            @elseif ($selectedTab === 'account')

                <h1 class="text-3xl font-bold text-white mb-8">
                    Pengaturan Akun
                </h1>

                <div class="grid gap-6 lg:grid-cols-2">

                    <section class="rounded-3xl border border-white/5 bg-[#091225] p-6">
                        <h2 class="text-lg font-semibold text-white mb-4">
                            Informasi Akun
                        </h2>

                        <dl class="space-y-4 text-sm text-slate-300">

                            <div>
                                <dt class="font-medium text-slate-400">Email</dt>
                                <dd class="mt-1 text-white">{{ $user->email }}</dd>
                            </div>

                            <div>
                                <dt class="font-medium text-slate-400">
                                    Status Verifikasi
                                </dt>

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
                                    class="w-full rounded-2xl bg-blue-600 px-5 py-3 text-sm font-semibold text-white hover:bg-blue-500 transition">
                                    Kirim Ulang Email Verifikasi
                                </button>
                            </form>
                        @endif
                    </section>

                    <section class="rounded-3xl border border-white/5 bg-[#091225] p-6">
                        <h2 class="text-lg font-semibold text-white mb-4">
                            Hapus Akun Permanen
                        </h2>

                        <p class="text-slate-400 mb-6">
                            Agar akun bisa dihapus, sistem akan mengirimkan kode verifikasi ke email terdaftar terlebih dahulu.
                        </p>

                        <a href="{{ route('settings.account.delete') }}"
                            class="inline-flex w-full items-center justify-center rounded-2xl bg-rose-600 px-5 py-3 text-sm font-semibold text-white hover:bg-rose-500 transition">
                            Mulai Proses Hapus Akun
                        </a>
                    </section>

                </div>

            {{-- PASSWORD --}}
            @elseif ($selectedTab === 'password')

                <h1 class="text-3xl font-bold text-white mb-8">
                    Ubah Password
                </h1>

                <div class="grid gap-6 lg:grid-cols-2">

                    <section class="rounded-3xl border border-white/5 bg-[#091225] p-6">

                        <h2 class="text-lg font-semibold text-white mb-4">
                            Kirim Kode Verifikasi
                        </h2>

                        <p class="text-slate-400 mb-6">
                            Kode akan dikirim ke email terdaftar. Setelah itu, masukkan kode tersebut untuk menyimpan password baru.
                        </p>

                        <form action="{{ route('settings.password.sendCode') }}"
                            method="POST">
                            @csrf

                            <button type="submit"
                                class="w-full rounded-2xl bg-blue-600 px-5 py-3 text-sm font-semibold text-white hover:bg-blue-500 transition">
                                Kirim Kode ke Email
                            </button>
                        </form>

                    </section>

                    <section class="rounded-3xl border border-white/5 bg-[#091225] p-6">

                        <h2 class="text-lg font-semibold text-white mb-4">
                            Simpan Password Baru
                        </h2>

                        <form action="{{ route('settings.password.update') }}"
                            method="POST"
                            class="space-y-4">

                            @csrf
                            @method('PUT')

                            <div>
                                <label for="verification_code"
                                    class="mb-2 block text-sm font-medium text-slate-300">
                                    Kode Verifikasi
                                </label>

                                <input type="text"
                                    name="verification_code"
                                    id="verification_code"
                                    maxlength="6"
                                    inputmode="numeric"
                                    placeholder="6 digit kode"
                                    required
                                    class="w-full rounded-2xl border border-slate-700 bg-[#091225] px-4 py-3 text-slate-200">
                            </div>

                            <div>
                                <label for="password"
                                    class="mb-2 block text-sm font-medium text-slate-300">
                                    Password Baru
                                </label>

                                <input type="password"
                                    name="password"
                                    id="password"
                                    required
                                    class="w-full rounded-2xl border border-slate-700 bg-[#091225] px-4 py-3 text-slate-200">
                            </div>

                            <div>
                                <label for="password_confirmation"
                                    class="mb-2 block text-sm font-medium text-slate-300">
                                    Konfirmasi Password Baru
                                </label>

                                <input type="password"
                                    name="password_confirmation"
                                    id="password_confirmation"
                                    required
                                    class="w-full rounded-2xl border border-slate-700 bg-[#091225] px-4 py-3 text-slate-200">
                            </div>

                            <button type="submit"
                                class="w-full rounded-2xl bg-emerald-600 px-5 py-3 text-sm font-semibold text-white hover:bg-emerald-500 transition">
                                Perbarui Password
                            </button>

                        </form>

                    </section>

                </div>

            {{-- SELLER --}}
            @else

                {{-- ISI TETAP SAMA --}}
                {!! '' !!}

            @endif

        </main>
    </div>
</div>

<style>
.reveal-up{
    opacity:0;
    transform:translateY(45px);
    animation:revealUp .9s cubic-bezier(.22,1,.36,1) forwards;
}

.reveal-left{
    opacity:0;
    transform:translateX(-45px);
    animation:revealLeft .9s cubic-bezier(.22,1,.36,1) forwards;
}

@keyframes revealUp{
    to{
        opacity:1;
        transform:translateY(0);
    }
}

@keyframes revealLeft{
    to{
        opacity:1;
        transform:translateX(0);
    }
}

html,body{
    overflow-x:hidden;
    background:#060816;
}

select option{
    background:#091225;
    color:white;
}
</style>
@endsection
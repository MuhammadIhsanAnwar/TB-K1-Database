@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-950 py-12 px-4">
    <div class="max-w-6xl mx-auto grid gap-8 lg:grid-cols-[280px_minmax(0,1fr)]">
        <aside class="rounded-3xl border border-slate-800 bg-slate-950 p-6">
            <div class="mb-8 text-center">
                <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}" class="mx-auto h-24 w-24 rounded-full border border-slate-700 object-cover">
                <h2 class="mt-4 text-xl font-semibold text-white">{{ $user->name }}</h2>
                <p class="text-sm text-slate-400">{{ $user->email }}</p>
            </div>

            <nav class="space-y-2">
                <a href="{{ route('settings.profile') }}" class="block rounded-2xl px-4 py-3 text-sm font-medium transition {{ $selectedTab === 'profile' ? 'bg-slate-800 text-white' : 'text-slate-400 hover:bg-slate-900 hover:text-white' }}">Edit Profil</a>
                <a href="{{ route('settings.account') }}" class="block rounded-2xl px-4 py-3 text-sm font-medium transition {{ $selectedTab === 'account' ? 'bg-slate-800 text-white' : 'text-slate-400 hover:bg-slate-900 hover:text-white' }}">Pengaturan Akun</a>
                <a href="{{ route('settings.buyer') }}" class="block rounded-2xl px-4 py-3 text-sm font-medium transition {{ $selectedTab === 'buyer' ? 'bg-slate-800 text-white' : 'text-slate-400 hover:bg-slate-900 hover:text-white' }}">Daftar Jadi Buyer</a>
            </nav>
        </aside>

        <main class="rounded-3xl border border-slate-800 bg-slate-950 p-8">
            @if (session('success'))
                <div class="mb-6 rounded-2xl border border-emerald-500/20 bg-emerald-500/10 p-4 text-emerald-200">{{ session('success') }}</div>
            @endif
            @if (session('status'))
                <div class="mb-6 rounded-2xl border border-cyan-500/20 bg-cyan-500/10 p-4 text-cyan-200">{{ session('status') }}</div>
            @endif
            @if (session('warning'))
                <div class="mb-6 rounded-2xl border border-amber-500/20 bg-amber-500/10 p-4 text-amber-200">{{ session('warning') }}</div>
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

            @if ($selectedTab === 'profile')
                <h1 class="text-3xl font-bold text-white mb-8">Edit Profil</h1>
                <form action="{{ route('settings.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <div>
                        <label for="profile_photo" class="block text-sm font-medium text-slate-300 mb-2">Foto Profil</label>
                        <input type="file" name="profile_photo" id="profile_photo" class="w-full rounded-2xl border border-slate-800 bg-slate-900 px-4 py-3 text-slate-200" accept="image/*">
                    </div>

                    <div>
                        <label for="name" class="block text-sm font-medium text-slate-300 mb-2">Nama Lengkap</label>
                        <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" class="w-full rounded-2xl border border-slate-800 bg-slate-900 px-4 py-3 text-slate-200" required>
                    </div>

                    <div>
                        <label for="phone" class="block text-sm font-medium text-slate-300 mb-2">Nomor Telepon</label>
                        <input type="text" name="phone" id="phone" value="{{ old('phone', $profile?->phone ?? $user->phone) }}" class="w-full rounded-2xl border border-slate-800 bg-slate-900 px-4 py-3 text-slate-200" placeholder="08xxxxxxxxxx">
                    </div>

                    <div class="grid gap-4 lg:grid-cols-2">
                        <div>
                            <label for="gender" class="block text-sm font-medium text-slate-300 mb-2">Jenis Kelamin</label>
                            <select name="gender" id="gender" class="w-full rounded-2xl border border-slate-800 bg-slate-900 px-4 py-3 text-slate-200">
                                <option value="">Pilih</option>
                                <option value="male" @selected(old('gender', $profile?->gender) === 'male')>Laki-laki</option>
                                <option value="female" @selected(old('gender', $profile?->gender) === 'female')>Perempuan</option>
                                <option value="other" @selected(old('gender', $profile?->gender) === 'other')>Lainnya</option>
                            </select>
                        </div>
                        <div>
                            <label for="birth_date" class="block text-sm font-medium text-slate-300 mb-2">Tanggal Lahir</label>
                            <input type="date" name="birth_date" id="birth_date" value="{{ old('birth_date', optional($profile?->birth_date)->format('Y-m-d')) }}" class="w-full rounded-2xl border border-slate-800 bg-slate-900 px-4 py-3 text-slate-200">
                        </div>
                    </div>

                    <button type="submit" class="w-full rounded-2xl bg-slate-800 px-5 py-3 text-sm font-semibold text-white transition hover:bg-slate-700">Simpan Perubahan Profil</button>
                </form>
            @elseif ($selectedTab === 'account')
                <h1 class="text-3xl font-bold text-white mb-8">Pengaturan Akun</h1>
                <div class="grid gap-6 lg:grid-cols-2">
                    <section class="rounded-3xl border border-slate-800 bg-slate-900 p-6">
                        <h2 class="text-lg font-semibold text-white mb-4">Informasi Akun</h2>
                        <dl class="space-y-4 text-sm text-slate-300">
                            <div>
                                <dt class="font-medium text-slate-400">Email</dt>
                                <dd class="mt-1 text-white">{{ $user->email }}</dd>
                            </div>
                            <div>
                                <dt class="font-medium text-slate-400">Status Verifikasi</dt>
                                <dd class="mt-1 {{ $user->email_verified_at ? 'text-emerald-300' : 'text-amber-300' }}">{{ $user->email_verified_at ? 'Terverifikasi' : 'Belum Terverifikasi' }}</dd>
                            </div>
                        </dl>
                        @if (! $user->email_verified_at)
                            <form action="{{ route('verification.send') }}" method="POST" class="mt-6">
                                @csrf
                                <button type="submit" class="w-full rounded-2xl bg-slate-800 px-5 py-3 text-sm font-semibold text-white hover:bg-slate-700 transition">Kirim Ulang Email Verifikasi</button>
                            </form>
                        @endif
                    </section>
                    <section class="rounded-3xl border border-slate-800 bg-slate-900 p-6">
                        <h2 class="text-lg font-semibold text-white mb-4">Hapus Akun Permanen</h2>
                        <p class="text-slate-400 mb-6">Agar akun bisa dihapus, sistem akan mengirimkan kode verifikasi ke email terdaftar terlebih dahulu.</p>
                        <a href="{{ route('settings.account.delete') }}" class="inline-flex w-full items-center justify-center rounded-2xl bg-rose-600 px-5 py-3 text-sm font-semibold text-white hover:bg-rose-500 transition">Mulai Proses Hapus Akun</a>
                    </section>
                </div>
            @else
                <h1 class="text-3xl font-bold text-white mb-8">Daftar Jadi Buyer</h1>
                <div class="rounded-3xl border border-slate-800 bg-slate-900 p-6 space-y-5 text-slate-300">
                    <p>Semua pengguna otomatis memiliki peran buyer. Setelah akun aktif dan email terverifikasi, Anda dapat langsung berbelanja.</p>
                    @if ($user->isBuyer())
                        <p class="text-white">Akun Anda sudah berstatus buyer. Nikmati fitur belanja dan riwayat pesanan.</p>
                    @else
                        <p class="text-white">Peran Anda saat ini: {{ ucfirst($user->role) }}. Jika ingin kembali menjadi buyer, silakan hubungi administrator.</p>
                    @endif
                    <div class="grid gap-4 sm:grid-cols-2">
                        <a href="{{ route('products.search') }}" class="rounded-2xl bg-cyan-600 px-5 py-3 text-sm font-semibold text-white text-center hover:bg-cyan-500 transition">Jelajahi Produk</a>
                        <a href="{{ route('profile.show') }}" class="rounded-2xl border border-slate-700 px-5 py-3 text-sm font-semibold text-white text-center hover:border-slate-600 transition">Lihat Profil</a>
                    </div>
                </div>
            @endif
        </main>
    </div>
</div>
@endsection

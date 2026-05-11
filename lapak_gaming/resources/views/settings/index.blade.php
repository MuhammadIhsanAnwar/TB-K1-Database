@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-950 py-12 px-4">
    <div class="max-w-2xl mx-auto">
        <h1 class="text-3xl font-bold text-white mb-8">Pengaturan Profil</h1>

        @if (session('success'))
        <div class="mb-6 bg-emerald-500/20 border border-emerald-600/30 rounded-lg p-4 text-emerald-300">
            {{ session('success') }}
        </div>
        @endif

        @if ($errors->any())
        <div class="mb-6 bg-red-500/20 border border-red-600/30 rounded-lg p-4">
            <p class="text-red-400 font-medium mb-2">Periksa kembali data berikut:</p>
            <ul class="list-disc list-inside text-red-400 text-sm space-y-1">
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <div class="bg-gray-900 rounded-xl p-8 space-y-6">
            <div class="flex items-center gap-4">
                <img src="{{ $user->avatar_url }}"
                     alt="Foto profil {{ $user->name }}"
                     class="w-16 h-16 rounded-full object-cover border border-gray-700">
                <div>
                    <p class="text-white font-semibold">{{ $user->name }}</p>
                    <p class="text-gray-400 text-sm">{{ $user->email }}</p>
                    <div class="mt-2 flex gap-2 text-xs">
                        <span class="px-2 py-1 rounded-full border border-brand-500/30 bg-brand-500/10 text-brand-300">Buyer</span>
                        @if($user->isSellerAccount())
                        <span class="px-2 py-1 rounded-full border border-amber-500/30 bg-amber-500/10 text-amber-300">Seller</span>
                        @endif
                    </div>
                </div>
            </div>

            <form action="{{ route('settings.update') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
                @csrf
                @method('PUT')

                <div>
                    <label for="profile_photo" class="block text-sm font-medium text-gray-300 mb-2">Foto Profil</label>
                    <input type="file" name="profile_photo" id="profile_photo"
                           class="w-full bg-gray-800 border border-gray-700 rounded-lg px-4 py-2 text-white">
                    <p class="text-xs text-gray-500 mt-1">Format jpg, jpeg, png, webp. Maksimal 5MB.</p>
                </div>

                <div>
                    <label for="name" class="block text-sm font-medium text-gray-300 mb-2">Nama Lengkap</label>
                    <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}"
                           class="w-full bg-gray-800 border border-gray-700 rounded-lg px-4 py-2 text-white" required>
                </div>

                <div>
                    <label for="phone" class="block text-sm font-medium text-gray-300 mb-2">Nomor Telepon</label>
                    <input type="text" name="phone" id="phone" value="{{ old('phone', $profile?->phone ?? $user->phone) }}"
                           class="w-full bg-gray-800 border border-gray-700 rounded-lg px-4 py-2 text-white" placeholder="08xxxxxxxxxx">
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="gender" class="block text-sm font-medium text-gray-300 mb-2">Jenis Kelamin</label>
                        <select name="gender" id="gender" class="w-full bg-gray-800 border border-gray-700 rounded-lg px-4 py-2 text-white">
                            <option value="">Pilih</option>
                            @foreach(['male' => 'Laki-laki', 'female' => 'Perempuan', 'other' => 'Lainnya'] as $value => $label)
                            <option value="{{ $value }}" @selected(old('gender', $profile?->gender) === $value)>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="birth_date" class="block text-sm font-medium text-gray-300 mb-2">Tanggal Lahir</label>
                        <input type="date" name="birth_date" id="birth_date"
                               value="{{ old('birth_date', optional($profile?->birth_date)->format('Y-m-d')) }}"
                               class="w-full bg-gray-800 border border-gray-700 rounded-lg px-4 py-2 text-white">
                    </div>
                </div>

                <div>
                    <label for="bio" class="block text-sm font-medium text-gray-300 mb-2">Bio</label>
                    <textarea name="bio" id="bio" rows="4"
                              class="w-full bg-gray-800 border border-gray-700 rounded-lg px-4 py-2 text-white"
                              placeholder="Ceritakan profil singkat Anda...">{{ old('bio', $profile?->bio) }}</textarea>
                </div>

                <div class="pt-2">
                    <button type="submit" class="w-full bg-purple-600 hover:bg-purple-700 text-white font-medium py-2.5 px-4 rounded-lg transition-colors">
                        Simpan Pengaturan Profil
                    </button>
                </div>
            </form>

            <div class="mt-10 rounded-3xl border border-red-700 bg-red-950/20 p-6">
                <h2 class="text-xl font-bold text-white">Hapus Akun Permanen</h2>
                <p class="mt-2 text-slate-400">Menghapus akun akan menghapus semua data pribadi dan akses ke Lapak Gaming.</p>
                <form action="{{ route('settings.destroy') }}" method="POST" class="mt-4">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="rounded-2xl bg-red-600 px-5 py-3 font-semibold text-white hover:bg-red-500">Hapus Akun Permanen</button>
                </form>
            </div>

            @unless($user->isSellerAccount())
            <div class="mt-10 rounded-3xl border border-amber-300 bg-amber-950/10 p-6">
                <h2 class="text-xl font-bold text-white">Ingin buka toko?</h2>
                <p class="mt-2 text-slate-400">Daftar jadi seller untuk mendapatkan akses toko dan produk.</p>
                <a href="{{ route('seller.register.form') }}" class="mt-4 inline-flex rounded-2xl bg-amber-500 px-5 py-3 font-semibold text-slate-950 hover:bg-amber-400">Daftar Jadi Seller</a>
            </div>
            @endunless
        </div>
    </div>
</div>
@endsection

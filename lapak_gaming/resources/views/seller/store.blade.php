@extends('layouts.app')

@section('title', 'Kelola Toko')

@section('content')
<div class="min-h-screen bg-slate-950 py-12 px-4">
    <div class="mx-auto max-w-4xl space-y-8">
        @if(session('success'))
            <div class="rounded-3xl border border-emerald-600/30 bg-emerald-500/10 p-4 text-emerald-200">{{ session('success') }}</div>
        @endif

        <div class="rounded-3xl border border-slate-800 bg-slate-900 p-8">
            <div class="flex items-center justify-between gap-4">
                <div>
                    <h1 class="text-3xl font-bold text-white">Kelola Toko</h1>
                    <p class="mt-2 text-slate-400">Sunting detail toko, dan hapus status seller jika ingin kembali menjadi buyer saja.</p>
                </div>
                <span class="rounded-full bg-amber-500/10 px-4 py-2 text-sm text-amber-200">Seller & Buyer</span>
            </div>

            <form action="{{ route('seller.store.update') }}" method="POST" enctype="multipart/form-data" class="mt-8 space-y-6">
                @csrf
                @method('PUT')

                <div>
                    <label class="block text-sm font-medium text-slate-300">Nama Toko</label>
                    <input name="store_name" type="text" value="{{ old('store_name', $user->shop_name ?: $user->name) }}" class="mt-2 w-full rounded-2xl border border-slate-700 bg-slate-950 px-4 py-3 text-white outline-none" required />
                    @error('store_name') <p class="mt-2 text-sm text-rose-400">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-300">Deskripsi Toko</label>
                    <textarea name="bio" rows="4" class="mt-2 w-full rounded-2xl border border-slate-700 bg-slate-950 px-4 py-3 text-white outline-none">{{ old('bio', $user->shop_description ?: $profile?->bio) }}</textarea>
                    @error('bio') <p class="mt-2 text-sm text-rose-400">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-300">Foto Toko</label>
                    <div class="mt-2 flex items-center gap-4">
                        <div class="w-24 h-24 rounded-lg overflow-hidden bg-black/40 border border-white/5">
                            @if(!empty($user->shop_photo_url))
                                <img src="{{ $user->shop_photo_url }}" alt="Foto Toko" class="w-full h-full object-cover">
                            @elseif(!empty($profile?->avatar_path))
                                <img src="{{ asset('storage/' . $profile->avatar_path) }}" alt="Foto Toko" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-sm text-slate-400">No Image</div>
                            @endif
                        </div>

                        <div class="flex-1">
                            <input type="file" name="store_photo" accept="image/*" class="mt-1 block w-full text-sm text-slate-300 file:rounded-lg file:border-0 file:bg-amber-500 file:px-3 file:py-2 file:text-white" />
                            @error('store_photo') <p class="mt-2 text-sm text-rose-400">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>

                <button type="submit" class="rounded-2xl bg-amber-500 px-5 py-3 font-semibold text-slate-950 transition hover:bg-amber-400">Simpan Perubahan Toko</button>
            </form>

                <div class="mt-8 rounded-3xl border border-slate-700 bg-slate-950/70 p-6 space-y-4">
                <h2 class="text-xl font-bold text-white">Nonaktifkan / Hapus Toko</h2>
                <p class="mt-2 text-slate-400">Anda dapat menonaktifkan toko agar tidak menerima pesanan lagi, atau menghapus toko secara permanen jika belum ada transaksi.</p>

                <form action="{{ route('seller.store.deactivate') }}" method="POST" class="mt-2 inline-block">
                    @csrf
                    <button type="submit" class="rounded-2xl bg-amber-500 px-5 py-3 font-semibold text-slate-950 transition hover:bg-amber-400">Nonaktifkan Toko</button>
                </form>

                <form action="{{ route('seller.store.destroy') }}" method="POST" class="mt-2">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="rounded-2xl bg-red-600 px-5 py-3 font-semibold text-white transition hover:bg-red-500">Hapus Toko Permanen</button>
                </form>

                @if($errors->has('store'))
                    <p class="mt-2 text-sm text-rose-400">{{ $errors->first('store') }}</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

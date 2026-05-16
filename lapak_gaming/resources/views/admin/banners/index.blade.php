@extends('layouts.app')

@section('title', 'Kelola Banner')

@section('content')
<div class="min-h-screen bg-slate-950 py-12 px-4">
    <div class="mx-auto max-w-6xl space-y-6">
        <div>
            <h1 class="text-3xl font-bold text-white">Kelola Banner Iklan</h1>
            <p class="mt-2 text-slate-400">Atur banner yang tampil di halaman utama marketplace.</p>
        </div>

        @if ($errors->any())
            <div class="rounded-3xl border border-red-700 bg-red-950 p-6">
                <h3 class="text-sm font-semibold text-red-300">Terjadi kesalahan:</h3>
                <ul class="mt-2 space-y-1 text-sm text-red-200">
                    @foreach ($errors->all() as $error)
                        <li>• {{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.banners.store') }}" method="POST" enctype="multipart/form-data" class="grid gap-4 rounded-3xl border border-slate-800 bg-slate-900 p-6 lg:grid-cols-2">
            @csrf
            <div>
                <label class="block text-sm font-medium text-slate-300">Judul</label>
                <input name="title" type="text" class="mt-2 w-full rounded-2xl border border-slate-700 bg-slate-950 px-4 py-3 text-white @error('title') border-red-600 @enderror" value="{{ old('title') }}" required>
                @error('title')<p class="mt-1 text-xs text-red-400">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-300">Subjudul</label>
                <input name="subtitle" type="text" class="mt-2 w-full rounded-2xl border border-slate-700 bg-slate-950 px-4 py-3 text-white" value="{{ old('subtitle') }}">
                @error('subtitle')<p class="mt-1 text-xs text-red-400">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-300">Unggah Gambar</label>
                <input name="image" type="file" accept="image/*" class="mt-2 w-full rounded-2xl border border-slate-700 bg-slate-950 px-4 py-3 text-slate-400 file:bg-slate-800 file:border-0 file:px-3 file:py-2 file:text-white @error('image') border-red-600 @enderror">
                <p class="mt-1 text-xs text-slate-500">JPG, PNG, GIF, WebP. Max 5MB. Hero: 4:5 portrait, Featured: 3:1 landscape.</p>
                            @error('image')<p class="text-xs text-red-400">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-300">Atau URL Gambar</label>
                <input name="image_url" type="url" class="mt-2 w-full rounded-2xl border border-slate-700 bg-slate-950 px-4 py-3 text-white @error('image_url') border-red-600 @enderror" placeholder="https://..." value="{{ old('image_url') }}">
                <p class="mt-1 text-xs text-slate-500">Jika tidak unggah file, sediakan URL gambar. Hero: 4:5 portrait, Featured: 3:1 landscape.</p>
                            @error('image_url')<p class="text-xs text-red-400">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-300">URL Tujuan</label>
                <input name="link_url" type="url" class="mt-2 w-full rounded-2xl border border-slate-700 bg-slate-950 px-4 py-3 text-white @error('link_url') border-red-600 @enderror" placeholder="https://..." value="{{ old('link_url') }}">
                @error('link_url')<p class="mt-1 text-xs text-red-400">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-300">Posisi</label>
                <select name="position" class="mt-2 w-full rounded-2xl border border-slate-700 bg-slate-950 px-4 py-3 text-white @error('position') border-red-600 @enderror">
                    <option value="hero" @if(old('position') === 'hero') selected @endif>Hero</option>
                    <option value="featured" @if(old('position') === 'featured') selected @endif>Featured</option>
                </select>
                <p class="mt-1 text-xs text-slate-500">Hanya tersedia untuk tampilan hero dan featured di beranda.</p>
                            @error('position')<p class="mt-1 text-xs text-red-400">{{ $message }}</p>@enderror
            </div>
            <div class="flex items-end gap-3">
                <label class="flex items-center gap-2 text-sm text-slate-300">
                    <input type="checkbox" name="is_active" value="1" @if(old('is_active')) checked @endif>
                    Aktif
                </label>
                <button type="submit" class="rounded-2xl bg-amber-500 px-5 py-3 font-semibold text-slate-950 hover:bg-amber-400">Simpan Banner</button>
            </div>
        </form>

        <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-3">
            @forelse ($banners as $banner)
                <article class="overflow-hidden rounded-3xl border border-slate-800 bg-slate-900">
                    <img src="{{ $banner->image_url }}" alt="{{ $banner->title }}" class="h-44 w-full object-cover">
                    <div class="space-y-4 p-5">
                        <div>
                            <h2 class="text-lg font-semibold text-white">{{ $banner->title }}</h2>
                            <p class="text-sm text-slate-400">{{ $banner->subtitle }}</p>
                        </div>
                        <div class="flex items-center justify-between text-xs text-slate-500">
                            <span>{{ strtoupper($banner->position) }}</span>
                            <span>{{ $banner->is_active ? 'Aktif' : 'Nonaktif' }}</span>
                        </div>
                        <form action="{{ route('admin.banners.destroy', $banner) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="w-full rounded-2xl bg-rose-600 px-4 py-3 text-sm font-semibold text-white hover:bg-rose-500">Hapus</button>
                        </form>
                    </div>
                </article>
            @empty
                <div class="rounded-3xl border border-slate-800 bg-slate-900 p-6 text-slate-400">Belum ada banner iklan.</div>
            @endforelse
        </div>
    </div>
</div>
@endsection

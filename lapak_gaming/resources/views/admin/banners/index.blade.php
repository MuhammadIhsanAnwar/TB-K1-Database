@extends('layouts.app')

@section('title', 'Kelola Banner')

@section('content')
<div class="min-h-screen bg-slate-950 py-12 px-4">
    <div class="mx-auto max-w-6xl space-y-6">
        <div>
            <h1 class="text-3xl font-bold text-white">Kelola Banner Iklan</h1>
            <p class="mt-2 text-slate-400">Atur banner yang tampil di halaman utama marketplace.</p>
        </div>

        <form action="{{ route('admin.banners.store') }}" method="POST" enctype="multipart/form-data" class="grid gap-4 rounded-3xl border border-slate-800 bg-slate-900 p-6 lg:grid-cols-2">
            @csrf
            <div>
                <label class="block text-sm font-medium text-slate-300">Judul</label>
                <input name="title" type="text" class="mt-2 w-full rounded-2xl border border-slate-700 bg-slate-950 px-4 py-3 text-white" required>
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-300">Subjudul</label>
                <input name="subtitle" type="text" class="mt-2 w-full rounded-2xl border border-slate-700 bg-slate-950 px-4 py-3 text-white">
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-300">Unggah Gambar</label>
                <input name="image" type="file" accept="image/*" class="mt-2 w-full rounded-2xl border border-slate-700 bg-slate-950 px-4 py-3 text-slate-400 file:bg-slate-800 file:border-0 file:px-3 file:py-2 file:text-white">
                <p class="mt-1 text-xs text-slate-500">JPG, PNG, GIF, WebP. Max 5MB</p>
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-300">Atau URL Gambar</label>
                <input name="image_url" type="url" class="mt-2 w-full rounded-2xl border border-slate-700 bg-slate-950 px-4 py-3 text-white" placeholder="https://...">
                <p class="mt-1 text-xs text-slate-500">Jika tidak unggah file, sediakan URL gambar</p>
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-300">URL Tujuan</label>
                <input name="link_url" type="url" class="mt-2 w-full rounded-2xl border border-slate-700 bg-slate-950 px-4 py-3 text-white" placeholder="https://...">
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-300">Posisi</label>
                <select name="position" class="mt-2 w-full rounded-2xl border border-slate-700 bg-slate-950 px-4 py-3 text-white">
                    <option value="hero">Hero</option>
                    <option value="featured">Featured</option>
                    <option value="sidebar">Sidebar</option>
                </select>
            </div>
            <div class="flex items-end gap-3">
                <label class="flex items-center gap-2 text-sm text-slate-300">
                    <input type="checkbox" name="is_active" value="1" checked>
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

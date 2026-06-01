@extends('layouts.app')

@section('title', 'Edit Banner — Admin')

@section('content')
<div class="min-h-screen py-10">
  <div class="max-w-4xl mx-auto px-4">
    <div class="rounded-2xl bg-slate-900/70 p-6 border border-white/6 shadow-lg">
      <h1 class="text-2xl font-extrabold text-white mb-3">Edit Banner</h1>
      <p class="text-sm text-slate-400 mb-4">Perbarui judul, gambar, link, dan status banner.</p>

      <form action="{{ route('admin.banners.update', $banner) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
        @csrf
        @method('PUT')

        <div>
          <label class="block text-xs font-bold text-slate-300 mb-1">Judul</label>
          <input name="title" value="{{ old('title', $banner->title) }}" required class="w-full rounded-lg px-3 py-2 bg-slate-800 border border-white/6 text-white" />
        </div>

        <div>
          <label class="block text-xs font-bold text-slate-300 mb-1">Subjudul</label>
          <input name="subtitle" value="{{ old('subtitle', $banner->subtitle) }}" class="w-full rounded-lg px-3 py-2 bg-slate-800 border border-white/6 text-white" />
        </div>

        <div class="grid grid-cols-1 gap-3 md:grid-cols-2">
          <div>
            <label class="block text-xs font-bold text-slate-300 mb-1">Ganti Gambar (opsional)</label>
            <input type="file" name="image" accept="image/*" class="w-full text-sm text-slate-300" />
            <p class="text-xs text-slate-400 mt-1">Atau biarkan kosong untuk tetap memakai gambar saat ini.</p>
          </div>
          <div>
            <label class="block text-xs font-bold text-slate-300 mb-1">Atau URL Gambar</label>
            <input type="url" name="image_url" value="{{ old('image_url', $banner->image_url) }}" class="w-full rounded-lg px-3 py-2 bg-slate-800 border border-white/6 text-white" />
          </div>
        </div>

        <div>
          <label class="block text-xs font-bold text-slate-300 mb-1">Preview Gambar Saat Ini</label>
          <div class="w-full h-44 bg-black/30 rounded-lg overflow-hidden border border-white/6">
            <img src="{{ $banner->image_url }}" alt="preview" class="w-full h-full object-cover" onerror="this.onerror=null; this.src='https://placehold.co/600x400/0a111e/94a3b8?text=Image+Missing'" />
          </div>
        </div>

        <div>
          <label class="block text-xs font-bold text-slate-300 mb-1">Link Tujuan</label>
          <input name="link_url" value="{{ old('link_url', $banner->link_url) }}" type="url" class="w-full rounded-lg px-3 py-2 bg-slate-800 border border-white/6 text-white" />
        </div>

        <div class="grid grid-cols-2 gap-3">
          <div>
            <label class="block text-xs font-bold text-slate-300 mb-1">Posisi</label>
            <select name="position" class="w-full rounded-lg px-3 py-2 bg-slate-800 border border-white/6 text-white">
              <option value="hero" @selected(old('position', $banner->position) === 'hero')>Hero</option>
              <option value="featured" @selected(old('position', $banner->position) === 'featured')>Featured</option>
            </select>
          </div>
          <div>
            <label class="block text-xs font-bold text-slate-300 mb-1">Status Aktif</label>
            <label class="inline-flex items-center gap-2">
              <input type="checkbox" name="is_active" value="1" class="w-4 h-4" @if(old('is_active', $banner->is_active)) checked @endif>
              <span class="text-sm text-slate-300">Aktif</span>
            </label>
          </div>
        </div>

        <div class="flex items-center gap-3">
          <button type="submit" class="rounded-xl bg-amber-500 px-4 py-2 text-sm font-bold">Simpan Perubahan</button>
          <a href="{{ route('admin.banners.index') }}" class="text-sm text-slate-300 underline">Batal</a>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection

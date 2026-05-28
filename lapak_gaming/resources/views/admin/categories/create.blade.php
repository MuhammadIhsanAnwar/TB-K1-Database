@extends('layouts.app')

@section('title', 'Tambah Kategori')

@section('content')
<div class="mx-auto max-w-4xl px-4 py-8 sm:px-6 lg:px-8">

    {{-- Breadcrumbs --}}
    <nav class="mb-6 flex" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-1 md:space-x-3">
            <li class="inline-flex items-center">
                <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center text-sm font-medium text-slate-400 hover:text-white">
                    Dashboard
                </a>
            </li>
            <li>
                <div class="flex items-center">
                    <svg class="h-5 w-5 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                    <a href="{{ route('admin.categories.index') }}" class="ml-1 text-sm font-medium text-slate-400 hover:text-white md:ml-2">Kategori</a>
                </div>
            </li>
            <li aria-current="page">
                <div class="flex items-center">
                    <svg class="h-5 w-5 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                    <span class="ml-1 text-sm font-medium text-slate-200 md:ml-2">Tambah</span>
                </div>
            </li>
        </ol>
    </nav>

    <div class="mb-8">
        <h1 class="text-3xl font-black tracking-tight text-white">Tambah Kategori</h1>
        <p class="mt-1 text-sm text-slate-400">Tambahkan kategori atau subkategori baru untuk produk.</p>
    </div>

    @if ($errors->any())
        <div class="mb-6 rounded-[16px] border border-red-500/20 bg-red-500/10 p-4">
            <div class="flex items-start gap-3">
                <div class="shrink-0 text-red-500">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>
                <div>
                    <h3 class="text-sm font-bold text-red-400">Terdapat kesalahan pada isian:</h3>
                    <ul class="mt-2 list-inside list-disc text-xs text-red-300">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    @endif

    <div class="rounded-[24px] border border-blue-500/20 bg-[#0B1220]/95 p-6 shadow-xl shadow-blue-500/5 sm:p-8">
        <form action="{{ route('admin.categories.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div class="space-y-6">
                {{-- Nama Kategori --}}
                <div>
                    <label for="name" class="mb-2 block text-sm font-bold text-slate-300">Nama Kategori <span class="text-red-500">*</span></label>
                    <input type="text" name="name" id="name" value="{{ old('name') }}" class="input w-full" placeholder="Cth: Voucher Game, Diamond Mobile Legends" required>
                </div>

                {{-- Kategori Induk --}}
                <div>
                    <label for="parent_id" class="mb-2 block text-sm font-bold text-slate-300">Kategori Induk</label>
                    <select name="parent_id" id="parent_id" class="input w-full text-slate-300">
                        <option value="">-- Jadikan Kategori Utama (Parent) --</option>
                        @foreach($parentCategories as $parent)
                            <option value="{{ $parent->id }}" @selected(old('parent_id') == $parent->id)>{{ $parent->name }}</option>
                        @endforeach
                    </select>
                    <p class="mt-1.5 text-xs text-slate-500">Pilih kategori induk jika ingin membuat subkategori.</p>
                </div>

                {{-- Ikon --}}
                <div>
                    <label for="icon" class="mb-2 block text-sm font-bold text-slate-300">Ikon (Link URL)</label>
                    <input type="url" name="icon" id="icon" value="{{ old('icon') }}" class="input w-full font-mono text-sm" placeholder="Cth: https://example.com/icon.png">
                    <p class="mt-1.5 text-xs text-slate-500">Masukkan link URL untuk gambar ikon kategori (opsional).</p>
                </div>

                {{-- Gambar --}}
                <div>
                    <label for="image" class="mb-2 block text-sm font-bold text-slate-300">Gambar Banner/Thumb (Opsional)</label>
                    <input type="file" name="image" id="image" accept="image/*" class="input w-full file:mr-4 file:cursor-pointer file:rounded-full file:border-0 file:bg-blue-500/10 file:px-4 file:py-2 file:text-sm file:font-semibold file:text-blue-400 hover:file:bg-blue-500/20" />
                </div>

                {{-- Urutan (Sort Order) --}}
                <div>
                    <label for="sort_order" class="mb-2 block text-sm font-bold text-slate-300">Urutan (Sort Order)</label>
                    <input type="number" name="sort_order" id="sort_order" value="{{ old('sort_order', 0) }}" class="input w-32" min="0">
                    <p class="mt-1.5 text-xs text-slate-500">Angka lebih kecil akan tampil lebih dulu (Cth: 0, 1, 2).</p>
                </div>

                {{-- Status Aktif --}}
                <div class="flex items-center gap-3">
                    <label class="relative inline-flex cursor-pointer items-center">
                        <input type="checkbox" name="is_active" value="1" class="peer sr-only" @checked(old('is_active', true))>
                        <div class="peer h-6 w-11 rounded-full bg-slate-700 after:absolute after:left-[2px] after:top-[2px] after:h-5 after:w-5 after:rounded-full after:border after:border-gray-300 after:bg-white after:transition-all after:content-[''] peer-checked:bg-blue-500 peer-checked:after:translate-x-full peer-checked:after:border-white peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-800"></div>
                        <span class="ml-3 text-sm font-medium text-slate-300">Kategori Aktif</span>
                    </label>
                </div>

            </div>

            <div class="mt-8 flex items-center gap-4">
                <button type="submit" class="btn-primary flex-1 sm:flex-none">
                    Simpan Kategori
                </button>
                <a href="{{ route('admin.categories.index') }}" class="btn-ghost flex-1 text-center sm:flex-none">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection

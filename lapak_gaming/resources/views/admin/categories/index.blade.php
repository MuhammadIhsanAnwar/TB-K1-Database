@extends('layouts.app')

@section('title', 'Kelola Kategori')

@section('content')
<div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">

    {{-- Breadcrumbs --}}
    <nav class="mb-6 flex" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-1 md:space-x-3">
            <li class="inline-flex items-center">
                <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center text-sm font-medium text-slate-400 hover:text-white">
                    <svg class="mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                    Dashboard
                </a>
            </li>
            <li aria-current="page">
                <div class="flex items-center">
                    <svg class="h-5 w-5 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                    <span class="ml-1 text-sm font-medium text-slate-200 md:ml-2">Kategori</span>
                </div>
            </li>
        </ol>
    </nav>

    {{-- Header --}}
    <div class="mb-8 flex flex-col justify-between gap-4 sm:flex-row sm:items-center">
        <div>
            <h1 class="text-3xl font-black tracking-tight text-white">Manajemen Kategori</h1>
            <p class="mt-1 text-sm text-slate-400">Atur kategori dan subkategori produk untuk marketplace.</p>
        </div>
        <a href="{{ route('admin.categories.create') }}" class="btn-primary">
            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4" />
            </svg>
            Tambah Kategori
        </a>
    </div>

    {{-- Search/Filter --}}
    <div class="mb-6 rounded-[20px] border border-blue-500/20 bg-[#0B1220]/95 p-4 shadow-lg shadow-blue-500/5">
        <form action="{{ route('admin.categories.index') }}" method="GET" class="flex flex-col gap-4 sm:flex-row sm:items-center">
            <div class="flex-1">
                <div class="relative">
                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-slate-500">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                    <input type="text" name="q" value="{{ $q }}" placeholder="Cari kategori..." class="input pl-10" />
                </div>
                <div class="flex items-center mt-2">
                    <label for="group" class="text-sm text-slate-300 mr-2">Pengelompokan:</label>>
                    <select name="group" id="group" class="input w-48" onchange="this.form.submit()">
                        <option value="" {{ $group == '' ? 'selected' : '' }}>Campuran</option>
                        <option value="main" {{ $group == 'main' ? 'selected' : '' }}>Kategori Utama</option>
                        <option value="sub" {{ $group == 'sub' ? 'selected' : '' }}>Sub Kategori</option>
                    </select>
                </div>
            </div>
            <button type="submit" class="btn-primary whitespace-nowrap">
                Cari
            </button>
            @if($q)
                <a href="{{ route('admin.categories.index') }}" class="btn-ghost">
                    Reset
                </a>
            @endif
        </form>
    </div>

    {{-- Table --}}
    <div class="overflow-hidden rounded-[24px] border border-blue-500/20 bg-[#0B1220]/95 shadow-xl shadow-blue-500/5">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-300">
                <thead class="bg-[#0f172a]/80 text-xs font-semibold uppercase tracking-wider text-slate-400">
                    <tr>
                        <th scope="col" class="px-6 py-4">Gambar/Ikon</th>
                        <th scope="col" class="px-6 py-4">Kategori</th>
                        <th scope="col" class="px-6 py-4">Tipe</th>
                        <th scope="col" class="px-6 py-4 text-center">Status</th>
                        <th scope="col" class="px-6 py-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-blue-500/10">
                    @forelse($categories as $category)
                    <tr class="transition hover:bg-white/5">
                        <td class="px-6 py-4">
                            @if($category->image)
                                <img src="{{ asset('storage/' . $category->image) }}" alt="{{ $category->name }}" class="h-10 w-10 rounded-lg object-cover ring-1 ring-white/10" />
                            @elseif($category->icon)
                                <img src="{{ $category->icon }}" alt="{{ $category->name }}" class="h-10 w-10 rounded-lg object-cover ring-1 ring-white/10" />
                            @else
                                <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-slate-800 text-xs font-bold text-slate-500 ring-1 ring-white/10">
                                    N/A
                                </div>
                            @endif
                        </td>
                        <td class="px-6 py-4 font-semibold text-white">
                            {{ $category->name }}
                            <div class="text-[11px] font-normal text-slate-500">{{ $category->slug }}</div>
                        </td>
                        <td class="px-6 py-4">
                            @if($category->parent_id)
                                <span class="badge badge-orange">Subkategori dari {{ $category->parent->name ?? 'Unknown' }}</span>
                            @else
                                <span class="badge badge-blue">Kategori Utama</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-center">
                            @if($category->is_active)
                                <span class="badge badge-green">Aktif</span>
                            @else
                                <span class="badge badge-red">Nonaktif</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('admin.categories.edit', $category) }}" class="inline-flex h-8 w-8 items-center justify-center rounded-lg bg-blue-500/10 text-blue-400 transition hover:bg-blue-500/20 hover:text-blue-300" title="Edit">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                    </svg>
                                </a>
                                <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus kategori ini? Subkategori dan produk yang terhubung mungkin akan terdampak.');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="inline-flex h-8 w-8 items-center justify-center rounded-lg bg-red-500/10 text-red-400 transition hover:bg-red-500/20 hover:text-red-300" title="Hapus">
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center">
                            <svg class="mx-auto h-12 w-12 text-slate-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                            </svg>
                            <p class="mt-4 text-sm text-slate-400">Tidak ada kategori yang ditemukan.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($categories->hasPages())
        <div class="border-t border-blue-500/20 px-6 py-4">
            {{ $categories->links() }}
        </div>
        @endif
    </div>

</div>
@endsection

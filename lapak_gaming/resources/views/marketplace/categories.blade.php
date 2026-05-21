@extends('layouts.app')
@section('title', 'Semua Kategori — Lapak Gaming')

@section('content')
<section class="max-w-7xl mx-auto px-4 py-12">
  <div class="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between mb-10">
    <div>
      <div class="inline-flex items-center gap-2 rounded-full border border-cyan-400/15 bg-cyan-400/5 px-4 py-1.5 text-xs font-bold tracking-[0.2em] text-cyan-300 uppercase">
        13 kategori utama
      </div>
      <h1 class="mt-4 text-3xl md:text-5xl font-black text-white leading-tight">Semua Kategori dan Subkategori</h1>
      <p class="mt-3 max-w-2xl text-sm md:text-base text-slate-400">Halaman ini menampilkan struktur kategori marketplace: 13 kategori utama beserta subkategorinya. Tidak ada daftar produk di halaman ini.</p>
    </div>

    <a href="{{ route('products.search') }}" class="inline-flex items-center justify-center rounded-2xl border border-white/10 bg-white/[0.03] px-5 py-3 text-sm font-semibold text-white hover:border-cyan-400/25 hover:bg-cyan-400/10 transition-colors">
      Cari Produk
    </a>
  </div>

  <div class="grid gap-6">
    @foreach($categories as $category)
      <article class="overflow-hidden rounded-[28px] border border-white/10 bg-[#0B1220] shadow-2xl">
        <div class="flex flex-col gap-4 border-b border-white/5 p-5 md:flex-row md:items-center md:justify-between">
          <div class="flex items-center gap-4">
            <img src="{{ $category->image_url }}" alt="{{ $category->name }}" class="h-16 w-16 rounded-2xl object-cover bg-slate-900/40" loading="lazy">
            <div>
              <h2 class="text-2xl font-bold text-white">{{ $category->name }}</h2>
              <p class="mt-1 text-sm text-slate-400">{{ $category->children->count() }} subkategori tersedia</p>
            </div>
          </div>

          <a href="{{ route('categories.show', $category->slug) }}" class="inline-flex items-center justify-center rounded-2xl border border-cyan-400/20 bg-cyan-400/10 px-4 py-2.5 text-sm font-semibold text-cyan-300 hover:bg-cyan-400 hover:text-black transition-colors">
            Buka kategori
          </a>
        </div>

        <div class="p-5">
          <div class="grid gap-3 sm:grid-cols-2 xl:grid-cols-4">
            @foreach($category->children as $subCategory)
              <a href="{{ route('categories.show', $subCategory->slug) }}" class="group flex items-center gap-3 rounded-2xl border border-white/5 bg-white/[0.02] px-4 py-3 text-sm text-slate-300 hover:border-cyan-400/25 hover:bg-cyan-400/10 hover:text-white transition-colors">
                <img src="{{ $subCategory->image_url }}" alt="{{ $subCategory->name }}" class="h-11 w-11 rounded-xl object-cover bg-slate-900/40 shrink-0" loading="lazy">
                <span class="min-w-0 flex-1 truncate font-medium">{{ $subCategory->name }}</span>
                <span class="text-cyan-300 opacity-0 group-hover:opacity-100 transition-opacity">→</span>
              </a>
            @endforeach
          </div>
        </div>
      </article>
    @endforeach
  </div>
</section>
@endsection
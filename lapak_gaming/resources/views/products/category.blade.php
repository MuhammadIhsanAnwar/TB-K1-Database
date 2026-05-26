@extends('layouts.app')
@section('title', $category->name . ' — Lapak Gaming')

@push('styles')
<style>
  /* Page background controlled by layout theme tokens */
</style>
@endpush

@php
    $productsPaginator = is_object($products) && method_exists($products, 'links') ? $products : null;
    $productsList = $productsPaginator ? $productsPaginator->getCollection() : collect($products);
@endphp

@section('content')
<section class="max-w-7xl mx-auto px-4 py-8">
  
  {{-- Breadcrumb --}}
  <nav class="mb-6 flex items-center gap-2 text-xs font-medium text-slate-500">
    <a href="{{ route('marketplace.home') }}" class="transition-colors hover:text-sky-300">Beranda</a>
    <span>/</span>
    <span class="font-semibold text-slate-200">{{ $category->name }}</span>
  </nav>

  <div class="flex flex-col lg:flex-row gap-8">
    
    {{-- Left Sidebar: Filters --}}
    <aside class="w-full lg:w-64 shrink-0">
      <div class="sticky top-24 rounded-[30px] border border-white/10 bg-gradient-to-br from-[#091220] via-[#0b1730] to-[#0a1120] p-5 shadow-[0_25px_80px_rgba(37,99,235,0.12)]">
        <h3 class="mb-4 flex items-center gap-2 font-display font-bold text-white">
          <svg class="h-4 w-4 text-sky-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/></svg>
          Filter Produk
        </h3>
        
        <form action="{{ route('categories.show', $category->slug) }}" method="GET" id="filter-form">
          {{-- Preserve sorting if selected --}}
          @if(request('sort'))
            <input type="hidden" name="sort" value="{{ request('sort') }}">
          @endif

          {{-- Type Filter --}}
          <div class="mb-6">
            <h4 class="mb-3 text-xs font-semibold uppercase tracking-wide text-slate-500">Tipe Produk</h4>
            <div class="space-y-2">
              @foreach(['topup' => 'Top Up', 'key' => 'Game Key', 'account' => 'Akun', 'item' => 'Item'] as $val => $label)
              <label class="flex cursor-pointer items-center gap-2 rounded-2xl border border-white/10 bg-white/[0.03] px-3 py-2 text-sm text-slate-200">
                <input type="radio" name="type" value="{{ $val }}" onchange="this.form.submit()" {{ request('type') === $val ? 'checked' : '' }} class="text-sky-400 focus:ring-sky-400">
                {{ $label }}
              </label>
              @endforeach
            </div>
          </div>

          {{-- Price Range Filter --}}
          <div class="mb-6">
            <h4 class="mb-3 text-xs font-semibold uppercase tracking-wide text-slate-500">Harga</h4>
            <div class="flex items-center gap-2 mb-3">
              <input type="number" name="min_price" value="{{ request('min_price') }}" placeholder="Min" class="input text-sm">
              <span class="surface-muted">-</span>
              <input type="number" name="max_price" value="{{ request('max_price') }}" placeholder="Max" class="input text-sm">
            </div>
            <button type="submit" class="w-full rounded-2xl bg-gradient-to-r from-sky-500 to-blue-600 py-2 text-sm font-semibold text-white transition hover:from-sky-400 hover:to-blue-500">Terapkan Harga</button>
          </div>
          
          {{-- Reset Filter --}}
          @if(request('type') || request('min_price') || request('max_price'))
            <a href="{{ route('categories.show', $category->slug) }}" class="block w-full rounded-2xl border border-rose-500/20 bg-rose-500/10 py-2 text-center text-sm font-semibold text-rose-300 transition hover:bg-rose-500/15">Hapus Filter</a>
          @endif
        </form>
      </div>
    </aside>

    {{-- Right Content: Products --}}
    <div class="flex-1">
      
      {{-- Banner/Header --}}
      <div class="relative mb-6 flex items-center overflow-hidden rounded-[30px] border border-white/10 bg-gradient-to-r from-sky-600 via-blue-600 to-indigo-700 p-6 text-white shadow-[0_25px_80px_rgba(37,99,235,0.18)]">
        <div class="relative z-10">
          <h1 class="font-display mb-2 text-3xl font-black md:text-[2.8rem]">{{ $category->name }}</h1>
          <p class="max-w-lg text-sm text-blue-100/90">Temukan penawaran terbaik untuk {{ $category->name }}. Beli dengan aman dan pengiriman instan.</p>
        </div>
        @if($category->image)
          <img src="{{ $category->image_url }}" alt="" class="absolute right-0 top-1/2 -translate-y-1/2 w-48 opacity-20 mask-gradient-left hidden md:block">
        @endif
      </div>

      {{-- Toolbar (Sort & Count) --}}
      <div class="mb-6 flex flex-col gap-4 rounded-[26px] border border-white/10 bg-white/[0.03] p-4 shadow-sm sm:flex-row sm:items-center sm:justify-between">
        <div class="text-sm text-slate-400">
          Menampilkan <span class="font-bold text-white">{{ $productsPaginator?->firstItem() ?? 0 }}</span> - <span class="font-bold text-white">{{ $productsPaginator?->lastItem() ?? 0 }}</span> dari <span class="font-bold text-white">{{ $productsPaginator?->total() ?? $productsList->count() }}</span> produk
        </div>
        
        <div class="flex items-center gap-3">
          <span class="text-sm font-medium text-slate-400">Urutkan:</span>
          <select onchange="window.location.href=this.value" class="input text-sm">
            @php $q = request()->except('sort'); @endphp
            <option value="{{ route('categories.show', array_merge(['category' => $category->slug, 'sort' => 'popular'], $q)) }}" {{ request('sort') === 'popular' ? 'selected' : '' }}>Paling Populer</option>
            <option value="{{ route('categories.show', array_merge(['category' => $category->slug, 'sort' => 'rating'], $q)) }}" {{ request('sort') === 'rating' ? 'selected' : '' }}>Rating Tertinggi</option>
            <option value="{{ route('categories.show', array_merge(['category' => $category->slug, 'sort' => 'price_asc'], $q)) }}" {{ request('sort') === 'price_asc' ? 'selected' : '' }}>Harga Termurah</option>
            <option value="{{ route('categories.show', array_merge(['category' => $category->slug, 'sort' => 'price_desc'], $q)) }}" {{ request('sort') === 'price_desc' ? 'selected' : '' }}>Harga Termahal</option>
          </select>
        </div>
      </div>

      {{-- Product Grid --}}
      <div class="grid grid-cols-2 gap-4 md:grid-cols-3 lg:grid-cols-4">
        @forelse($productsList as $product)
          @include('components.product-card', ['product' => $product])
        @empty
          <div class="col-span-full flex flex-col items-center justify-center rounded-[28px] border border-white/10 bg-white/[0.03] py-16 text-center shadow-sm">
            <svg class="mb-4 h-16 w-16 text-slate-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <h3 class="mb-1 text-lg font-bold text-white">Produk tidak ditemukan</h3>
            <p class="max-w-md text-sm text-slate-400">Coba sesuaikan filter Anda atau cari produk dengan kata kunci lain.</p>
          </div>
        @endforelse
      </div>

      {{-- Pagination --}}
      <div class="mt-8">
        @if($productsPaginator)
          {{ $productsPaginator->withQueryString()->links() }}
        @endif
      </div>

    </div>
  </div>
</section>
@endsection
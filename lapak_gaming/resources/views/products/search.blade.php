@extends('layouts.app')
@section('title', $title ?? 'Cari Produk — Lapak Gaming')

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
    <span class="font-semibold text-slate-200">Pencarian</span>
  </nav>

  <div class="flex flex-col lg:flex-row gap-8">
    
    {{-- Left Sidebar: Filters --}}
    <aside class="w-full lg:w-72 shrink-0">
      <div class="sticky top-24 space-y-6 rounded-[30px] border border-white/10 bg-gradient-to-br from-[#091220] via-[#0b1730] to-[#0a1120] p-5 shadow-[0_25px_80px_rgba(37,99,235,0.12)]">
        <div>
          <h3 class="mb-2 flex items-center gap-2 font-display font-bold text-white">
          <svg class="h-4 w-4 text-sky-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/></svg>
          Filter Produk
          </h3>
          <p class="text-xs leading-relaxed text-slate-400">Saring produk berdasarkan kategori dan rentang harga agar hasil lebih cepat sesuai.</p>
        </div>
        
        <form action="{{ route('products.search') }}" method="GET" id="filter-form">
          <input type="hidden" name="q" value="{{ request('q') }}">
          @if(request('sort'))
            <input type="hidden" name="sort" value="{{ request('sort') }}">
          @endif
          @if(request('category'))
            <input type="hidden" name="category" value="{{ request('category') }}">
          @endif

          {{-- Category Filter --}}
          @if(isset($categories) && $categories->count())
          <div>
            <h4 class="mb-3 text-xs font-semibold uppercase tracking-wide text-slate-500">Kategori</h4>
            <div class="flex flex-wrap gap-2">
              <a href="{{ route('products.search', array_filter(['q' => request('q'), 'sort' => request('sort'), 'min_price' => request('min_price'), 'max_price' => request('max_price')])) }}"
                 class="rounded-full px-3 py-2 text-xs font-semibold transition-colors {{ request('category') ? 'border border-white/10 bg-white/[0.03] text-slate-400' : 'bg-gradient-to-r from-sky-500 to-blue-600 text-white' }}">
                Semua
              </a>
              @foreach($categories as $category)
                <a href="{{ route('products.search', array_filter(['q' => request('q'), 'sort' => request('sort'), 'min_price' => request('min_price'), 'max_price' => request('max_price'), 'category' => $category->slug])) }}"
                   class="rounded-full px-3 py-2 text-xs font-semibold transition-colors {{ request('category') === $category->slug ? 'bg-gradient-to-r from-sky-500 to-blue-600 text-white' : 'border border-white/10 bg-white/[0.03] text-slate-300 hover:border-sky-400/20 hover:bg-sky-500/10' }}">
                  {{ $category->name }}
                </a>
              @endforeach
            </div>
          </div>
          @endif
          {{-- Type filter removed — only category and price range supported --}}

          {{-- Price Range Filter --}}
          <div>
            <h4 class="mb-3 text-xs font-semibold uppercase tracking-wide text-slate-500">Rentang Harga</h4>
            <div class="grid grid-cols-2 gap-2 mb-3">
              <input type="number" name="min_price" value="{{ request('min_price') }}" placeholder="Min" class="input text-sm">
              <input type="number" name="max_price" value="{{ request('max_price') }}" placeholder="Max" class="input text-sm">
            </div>
            <button type="submit" class="w-full rounded-2xl bg-gradient-to-r from-sky-500 to-blue-600 py-2.5 text-sm font-semibold text-white transition hover:from-sky-400 hover:to-blue-500">Terapkan Filter</button>
          </div>
          
          {{-- Reset Filter --}}
          @if(request('min_price') || request('max_price') || request('category'))
            <a href="{{ route('products.search', ['q' => request('q')]) }}" class="block w-full rounded-2xl border border-rose-500/20 bg-rose-500/10 py-2.5 text-center text-sm font-semibold text-rose-300 transition hover:bg-rose-500/15">Hapus Filter</a>
          @endif
        </form>
      </div>
    </aside>

    {{-- Right Content: Products --}}
    <div class="flex-1">
      
      {{-- Header --}}
      <div class="mb-6 overflow-hidden rounded-[30px] border border-white/10 bg-gradient-to-br from-[#091220] via-[#0b1730] to-[#0a1120] p-6 shadow-[0_25px_80px_rgba(37,99,235,0.12)]">
        <h1 class="mb-2 font-display text-3xl font-black text-white">
          @if(request('q'))
            Hasil pencarian untuk <span class="text-sky-300">"{{ request('q') }}"</span>
          @else
            Semua Produk
          @endif
        </h1>
        <p class="text-sm text-slate-400">Ditemukan {{ $productsPaginator?->total() ?? $productsList->count() }} produk yang sesuai.</p>
        @if(request()->hasAny(['category', 'type', 'min_price', 'max_price']))
          <div class="mt-4 flex flex-wrap gap-2 text-xs">
            @if(request('category'))
              <span class="rounded-full bg-sky-500/10 px-3 py-1 font-semibold text-sky-300">Kategori: {{ optional($categories->firstWhere('slug', request('category')))->name ?? request('category') }}</span>
            @endif
            {{-- Type removed --}}
            @if(request('min_price') || request('max_price'))
              <span class="rounded-full border border-white/10 bg-white/[0.03] px-3 py-1 font-semibold text-slate-300">
                Harga: {{ request('min_price') ? 'Rp'.number_format((int) request('min_price'), 0, ',', '.') : 'Min' }} - {{ request('max_price') ? 'Rp'.number_format((int) request('max_price'), 0, ',', '.') : 'Maks' }}
              </span>
            @endif
          </div>
        @endif
      </div>

      {{-- Toolbar (Sort) --}}
      <div class="mb-6 flex flex-col gap-4 rounded-[26px] border border-white/10 bg-white/[0.03] p-4 shadow-sm sm:flex-row sm:items-center sm:justify-between">
        <div class="text-sm text-slate-400">
          Menampilkan <span class="font-bold text-white">{{ $productsPaginator?->firstItem() ?? 0 }}</span> - <span class="font-bold text-white">{{ $productsPaginator?->lastItem() ?? 0 }}</span> dari <span class="font-bold text-white">{{ $productsPaginator?->total() ?? $productsList->count() }}</span> produk
        </div>
        
        <div class="flex items-center gap-3">
          <span class="text-sm font-medium text-slate-400">Urutkan:</span>
          <form method="GET">

    <input type="hidden" name="q" value="{{ request('q') }}">
    <input type="hidden" name="category" value="{{ request('category') }}">
    <input type="hidden" name="sort" value="{{ request('sort') }}">
    <input type="hidden" name="min_price" value="{{ request('min_price') }}">
    <input type="hidden" name="max_price" value="{{ request('max_price') }}">

    <select
        name="per_page"
        onchange="this.form.submit()"
        class="input min-w-[140px] text-sm"
    >
        @foreach([50,100,300,500,1000] as $size)
            <option
                value="{{ $size }}"
                {{ request('per_page', 50) == $size ? 'selected' : '' }}
            >
                {{ $size }} per halaman
            </option>
        @endforeach
    </select>

</form>
          <select onchange="window.location.href=this.value" class="input min-w-[180px] text-sm">
            @php $q = request()->except('sort'); @endphp
            <option value="{{ route('products.search', array_merge($q, ['sort' => 'popular'])) }}" {{ request('sort') === 'popular' ? 'selected' : '' }}>Paling Populer</option>
            <option value="{{ route('products.search', array_merge($q, ['sort' => 'rating'])) }}" {{ request('sort') === 'rating' ? 'selected' : '' }}>Rating Tertinggi</option>
            <option value="{{ route('products.search', array_merge($q, ['sort' => 'price_asc'])) }}" {{ request('sort') === 'price_asc' ? 'selected' : '' }}>Harga Termurah</option>
            <option value="{{ route('products.search', array_merge($q, ['sort' => 'price_desc'])) }}" {{ request('sort') === 'price_desc' ? 'selected' : '' }}>Harga Termahal</option>
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
            <p class="max-w-md text-sm text-slate-400">Coba kata kunci pencarian yang lain atau sesuaikan filter Anda.</p>
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
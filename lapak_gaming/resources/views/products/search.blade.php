@extends('layouts.app')
@section('title', $title ?? 'Cari Produk — Lapak Gaming')

@push('styles')
<style>
  /* Page background controlled by layout theme tokens */
</style>
@endpush

@section('content')
<section class="max-w-7xl mx-auto px-4 py-8">
  
  {{-- Breadcrumb --}}
  <nav class="flex items-center gap-2 text-xs text-gray-500 mb-6">
    <a href="{{ route('marketplace.home') }}" class="hover:text-itemku-blue transition-colors">Beranda</a>
    <span>/</span>
    <span class="text-gray-800 font-semibold">Pencarian</span>
  </nav>

  <div class="flex flex-col lg:flex-row gap-8">
    
    {{-- Left Sidebar: Filters --}}
    <aside class="w-full lg:w-72 shrink-0">
      <div class="surface-panel rounded-2xl shadow-sm border border-white/10 p-5 sticky top-24 space-y-6">
        <div>
          <h3 class="font-bold surface-text mb-2 flex items-center gap-2">
          <svg class="w-4 h-4 text-itemku-blue" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/></svg>
          Filter Produk
          </h3>
          <p class="text-xs surface-muted leading-relaxed">Saring produk berdasarkan kategori, tipe, dan rentang harga agar hasil lebih cepat sesuai.</p>
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
            <h4 class="text-xs font-semibold surface-muted uppercase mb-3 tracking-wide">Kategori</h4>
            <div class="flex flex-wrap gap-2">
              <a href="{{ route('products.search', array_filter(['q' => request('q'), 'type' => request('type'), 'sort' => request('sort'), 'min_price' => request('min_price'), 'max_price' => request('max_price')])) }}"
                 class="px-3 py-2 rounded-full text-xs font-semibold transition-colors {{ request('category') ? 'surface-weak surface-muted' : 'bg-primary text-white' }}">
                Semua
              </a>
              @foreach($categories as $category)
                <a href="{{ route('products.search', array_filter(['q' => request('q'), 'type' => request('type'), 'sort' => request('sort'), 'min_price' => request('min_price'), 'max_price' => request('max_price'), 'category' => $category->slug])) }}"
                   class="px-3 py-2 rounded-full text-xs font-semibold transition-colors {{ request('category') === $category->slug ? 'bg-primary text-white' : 'surface-weak surface-text hover:brightness-110' }}">
                  {{ $category->name }}
                </a>
              @endforeach
            </div>
          </div>
          @endif

          {{-- Type Filter --}}
          <div>
            <h4 class="text-xs font-semibold surface-muted uppercase mb-3 tracking-wide">Tipe Produk</h4>
            <div class="grid grid-cols-2 gap-2">
              @foreach(['topup' => 'Top Up', 'key' => 'Game Key', 'account' => 'Akun', 'item' => 'Item'] as $val => $label)
              <label class="flex items-center gap-2 text-sm surface-text cursor-pointer rounded-xl border border-white/10 px-3 py-2.5 surface-weak hover:brightness-110 transition-colors {{ request('type') === $val ? 'ring-1 ring-primary/60 border-primary/40' : '' }}">
                <input type="radio" name="type" value="{{ $val }}" onchange="this.form.submit()" {{ request('type') === $val ? 'checked' : '' }} class="text-itemku-blue focus:ring-itemku-blue">
                <span class="font-medium">{{ $label }}</span>
              </label>
              @endforeach
            </div>
          </div>

          {{-- Price Range Filter --}}
          <div>
            <h4 class="text-xs font-semibold surface-muted uppercase mb-3 tracking-wide">Rentang Harga</h4>
            <div class="grid grid-cols-2 gap-2 mb-3">
              <input type="number" name="min_price" value="{{ request('min_price') }}" placeholder="Min" class="input text-sm">
              <input type="number" name="max_price" value="{{ request('max_price') }}" placeholder="Max" class="input text-sm">
            </div>
            <button type="submit" class="w-full py-2.5 bg-primary hover:brightness-110 text-white text-sm font-semibold rounded-xl transition-colors">Terapkan Filter</button>
          </div>
          
          {{-- Reset Filter --}}
          @if(request('type') || request('min_price') || request('max_price'))
            <a href="{{ route('products.search', ['q' => request('q'), 'category' => request('category')]) }}" class="block w-full text-center py-2.5 text-red-300 hover:text-red-200 text-sm font-semibold rounded-xl border border-red-400/20 surface-weak transition-colors">Hapus Filter</a>
          @endif
        </form>
      </div>
    </aside>

    {{-- Right Content: Products --}}
    <div class="flex-1">
      
      {{-- Header --}}
      <div class="surface-panel rounded-2xl p-6 mb-6 shadow-sm border border-white/10">
        <h1 class="text-2xl font-bold surface-text mb-2">
          @if(request('q'))
            Hasil pencarian untuk <span class="text-primary">"{{ request('q') }}"</span>
          @else
            Semua Produk
          @endif
        </h1>
        <p class="surface-muted text-sm">Ditemukan {{ $products->total() }} produk yang sesuai.</p>
        @if(request()->hasAny(['category', 'type', 'min_price', 'max_price']))
          <div class="mt-4 flex flex-wrap gap-2 text-xs">
            @if(request('category'))
              <span class="px-3 py-1 rounded-full bg-primary/15 text-primary font-semibold">Kategori: {{ optional($categories->firstWhere('slug', request('category')))->name ?? request('category') }}</span>
            @endif
            @if(request('type'))
              <span class="px-3 py-1 rounded-full surface-weak surface-text font-semibold">Tipe: {{ strtoupper(request('type')) }}</span>
            @endif
            @if(request('min_price') || request('max_price'))
              <span class="px-3 py-1 rounded-full surface-weak surface-text font-semibold">
                Harga: {{ request('min_price') ? 'Rp'.number_format((int) request('min_price'), 0, ',', '.') : 'Min' }} - {{ request('max_price') ? 'Rp'.number_format((int) request('max_price'), 0, ',', '.') : 'Maks' }}
              </span>
            @endif
          </div>
        @endif
      </div>

      {{-- Toolbar (Sort) --}}
      <div class="flex flex-col sm:flex-row sm:items-center justify-between surface-panel rounded-2xl border border-white/10 p-4 mb-6 shadow-sm gap-4">
        <div class="text-sm surface-muted">
          Menampilkan <span class="font-bold surface-text">{{ $products->firstItem() ?? 0 }}</span> - <span class="font-bold surface-text">{{ $products->lastItem() ?? 0 }}</span> dari <span class="font-bold surface-text">{{ $products->total() }}</span> produk
        </div>
        
        <div class="flex items-center gap-3">
          <span class="text-sm surface-muted font-medium">Urutkan:</span>
          <select onchange="window.location.href=this.value" class="input text-sm min-w-[180px]">
            @php $q = request()->except('sort'); @endphp
            <option value="{{ route('products.search', array_merge($q, ['sort' => 'popular'])) }}" {{ request('sort') === 'popular' ? 'selected' : '' }}>Paling Populer</option>
            <option value="{{ route('products.search', array_merge($q, ['sort' => 'rating'])) }}" {{ request('sort') === 'rating' ? 'selected' : '' }}>Rating Tertinggi</option>
            <option value="{{ route('products.search', array_merge($q, ['sort' => 'price_asc'])) }}" {{ request('sort') === 'price_asc' ? 'selected' : '' }}>Harga Termurah</option>
            <option value="{{ route('products.search', array_merge($q, ['sort' => 'price_desc'])) }}" {{ request('sort') === 'price_desc' ? 'selected' : '' }}>Harga Termahal</option>
          </select>
        </div>
      </div>

      {{-- Product Grid --}}
      <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
        @forelse($products as $product)
          @include('components.product-card', ['product' => $product])
        @empty
          <div class="col-span-full py-16 flex flex-col items-center justify-center text-center surface-panel rounded-xl border border-white/10 shadow-sm">
            <svg class="w-16 h-16 surface-muted mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <h3 class="text-lg font-bold surface-text mb-1">Produk tidak ditemukan</h3>
            <p class="text-sm surface-muted max-w-md">Coba kata kunci pencarian yang lain atau sesuaikan filter Anda.</p>
          </div>
        @endforelse
      </div>

      {{-- Pagination --}}
      <div class="mt-8">
        @if(method_exists($products, 'links'))
          {{ $products->withQueryString()->links() }}
        @endif
      </div>

    </div>
  </div>
</section>
@endsection
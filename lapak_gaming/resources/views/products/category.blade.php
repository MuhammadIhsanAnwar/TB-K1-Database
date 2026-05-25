@extends('layouts.app')
@section('title', $category->name . ' — Lapak Gaming')

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
    <span class="text-gray-800 font-semibold">{{ $category->name }}</span>
  </nav>

  <div class="flex flex-col lg:flex-row gap-8">
    
    {{-- Left Sidebar: Filters --}}
    <aside class="w-full lg:w-64 shrink-0">
      <div class="surface-panel rounded-xl shadow-sm border border-gray-200 p-5 sticky top-24">
        <h3 class="font-bold text-gray-800 mb-4 flex items-center gap-2">
          <svg class="w-4 h-4 text-itemku-blue" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/></svg>
          Filter Produk
        </h3>
        
        <form action="{{ route('categories.show', $category->slug) }}" method="GET" id="filter-form">
          {{-- Preserve sorting if selected --}}
          @if(request('sort'))
            <input type="hidden" name="sort" value="{{ request('sort') }}">
          @endif

          {{-- Type Filter --}}
          <div class="mb-6">
            <h4 class="text-xs font-semibold text-gray-500 uppercase mb-3">Tipe Produk</h4>
            <div class="space-y-2">
              @foreach(['topup' => 'Top Up', 'key' => 'Game Key', 'account' => 'Akun', 'item' => 'Item'] as $val => $label)
              <label class="flex items-center gap-2 text-sm surface-text cursor-pointer">
                <input type="radio" name="type" value="{{ $val }}" onchange="this.form.submit()" {{ request('type') === $val ? 'checked' : '' }} class="text-itemku-blue focus:ring-itemku-blue">
                {{ $label }}
              </label>
              @endforeach
            </div>
          </div>

          {{-- Price Range Filter --}}
          <div class="mb-6">
            <h4 class="text-xs font-semibold surface-muted uppercase mb-3">Harga</h4>
            <div class="flex items-center gap-2 mb-3">
              <input type="number" name="min_price" value="{{ request('min_price') }}" placeholder="Min" class="input text-sm">
              <span class="surface-muted">-</span>
              <input type="number" name="max_price" value="{{ request('max_price') }}" placeholder="Max" class="input text-sm">
            </div>
            <button type="submit" class="w-full py-2 surface-weak hover:brightness-110 surface-text text-sm font-semibold rounded transition-colors">Terapkan Harga</button>
          </div>
          
          {{-- Reset Filter --}}
          @if(request('type') || request('min_price') || request('max_price'))
            <a href="{{ route('categories.show', $category->slug) }}" class="block w-full text-center py-2 text-red-400 hover:brightness-110 text-sm font-semibold rounded transition-colors">Hapus Filter</a>
          @endif
        </form>
      </div>
    </aside>

    {{-- Right Content: Products --}}
    <div class="flex-1">
      
      {{-- Banner/Header --}}
      <div class="bg-itemku-blue rounded-xl p-6 mb-6 text-white shadow-sm overflow-hidden relative flex items-center">
        <div class="relative z-10">
          <h1 class="text-2xl md:text-3xl font-bold mb-2">{{ $category->name }}</h1>
          <p class="text-blue-100 text-sm max-w-lg">Temukan penawaran terbaik untuk {{ $category->name }}. Beli dengan aman dan pengiriman instan.</p>
        </div>
        @if($category->image)
          <img src="{{ $category->image_url }}" alt="" class="absolute right-0 top-1/2 -translate-y-1/2 w-48 opacity-20 mask-gradient-left hidden md:block">
        @endif
      </div>

      {{-- Toolbar (Sort & Count) --}}
      <div class="flex flex-col sm:flex-row sm:items-center justify-between surface-panel rounded-xl border border-white/10 p-3 mb-6 shadow-sm gap-4">
        <div class="text-sm surface-muted">
          Menampilkan <span class="font-bold surface-text">{{ $products->firstItem() ?? 0 }}</span> - <span class="font-bold surface-text">{{ $products->lastItem() ?? 0 }}</span> dari <span class="font-bold surface-text">{{ $products->total() }}</span> produk
        </div>
        
        <div class="flex items-center gap-3">
          <span class="text-sm surface-muted font-medium">Urutkan:</span>
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
      <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
        @forelse($products as $product)
          @include('components.product-card', ['product' => $product])
        @empty
          <div class="col-span-full py-16 flex flex-col items-center justify-center text-center surface-panel rounded-xl border border-gray-200 shadow-sm">
            <svg class="w-16 h-16 text-gray-300 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <h3 class="text-lg font-bold text-gray-800 mb-1">Produk tidak ditemukan</h3>
            <p class="text-sm text-gray-500 max-w-md">Coba sesuaikan filter Anda atau cari produk dengan kata kunci lain.</p>
          </div>
        @endforelse
      </div>

      {{-- Pagination --}}
      <div class="mt-8">
        {{ $products->withQueryString()->links() }}
      </div>

    </div>
  </div>
</section>
@endsection
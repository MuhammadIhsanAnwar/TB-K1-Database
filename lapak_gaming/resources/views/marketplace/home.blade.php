@extends('layouts.app')
@section('title', 'Lapak Gaming — Marketplace Game Terpercaya Indonesia')

@push('styles')
<style>
  body {
    background-color: #f5f5f5; /* itemku light background */
  }
  .banner-track {
    scrollbar-width: none;
    -ms-overflow-style: none;
  }
  .banner-track::-webkit-scrollbar {
    display: none;
  }
  .banner-slide {
    scroll-snap-align: start;
  }
  .category-icon-wrapper {
    transition: transform 0.2s;
  }
  .category-icon-wrapper:hover {
    transform: translateY(-5px);
  }
  .section-title {
    color: #333;
    font-size: 1.25rem;
    font-weight: 700;
    margin-bottom: 1rem;
  }
  .trust-badge-container {
    background: linear-gradient(90deg, #1e4aa3 0%, #307FE2 100%);
  }
</style>
@endpush

@section('content')

{{-- ═══════════════════════════════════════════════════════════ --}}
{{-- HERO SECTION (BANNERS)                                     --}}
{{-- ═══════════════════════════════════════════════════════════ --}}
<section class="surface-panel pb-6 pt-4">
  <div class="max-w-7xl mx-auto px-4">
    <div class="flex gap-4 h-64 md:h-80 lg:h-96">
      {{-- Main Banner Carousel (Left 70%) --}}
      <div class="flex-1 rounded-xl overflow-hidden relative group">
        @if(isset($heroBanners) && $heroBanners->count())
          @php $mainBanner = $heroBanners->first(); @endphp
          <a href="{{ $mainBanner->link_url ?: '#' }}" class="block w-full h-full">
            <img src="{{ $mainBanner->image_url }}" class="w-full h-full object-cover" alt="Main Promo">
          </a>
        @else
          <div class="w-full h-full bg-blue-100 flex items-center justify-center">
            <span class="text-blue-500 font-bold text-xl">Promo Utama</span>
          </div>
        @endif
      </div>
      
      {{-- Side Banners (Right 30%, 2 rows) --}}
      <div class="hidden md:flex w-1/3 flex-col gap-4">
        @if(isset($heroBanners) && $heroBanners->count() > 1)
          @foreach($heroBanners->skip(1)->take(2) as $banner)
          <a href="{{ $banner->link_url ?: '#' }}" class="flex-1 rounded-xl overflow-hidden block">
            <img src="{{ $banner->image_url }}" class="w-full h-full object-cover" alt="Promo {{ $loop->iteration }}">
          </a>
          @endforeach
        @else
          <div class="flex-1 rounded-xl bg-blue-100 flex items-center justify-center">Promo 2</div>
          <div class="flex-1 rounded-xl bg-blue-100 flex items-center justify-center">Promo 3</div>
        @endif
      </div>
    </div>
  </div>
</section>

{{-- ═══════════════════════════════════════════════════════════ --}}
{{-- TRUST BADGES                                               --}}
{{-- ═══════════════════════════════════════════════════════════ --}}
<section class="trust-badge-container py-3 shadow-md mb-8">
  <div class="max-w-7xl mx-auto px-4 flex justify-between items-center overflow-x-auto no-scrollbar gap-6">
    <div class="flex items-center gap-2 text-white whitespace-nowrap">
      <svg class="w-5 h-5 text-green-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg>
      <span class="font-semibold text-sm">Transaksi 100% Aman</span>
    </div>
    <div class="flex items-center gap-2 text-white whitespace-nowrap">
      <svg class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4 2a1 1 0 011 1v2.101a7.002 7.002 0 0111.601 2.566 1 1 0 11-1.885.666A5.002 5.002 0 005.999 7H9a1 1 0 010 2H4a1 1 0 01-1-1V3a1 1 0 011-1zm.008 9.057a1 1 0 011.276.61A5.002 5.002 0 0014.001 13H11a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0v-2.101a7.002 7.002 0 01-11.601-2.566 1 1 0 01.61-1.276z" clip-rule="evenodd"></path></svg>
      <span class="font-semibold text-sm">Garansi Uang Kembali</span>
    </div>
    <div class="flex items-center gap-2 text-white whitespace-nowrap">
      <svg class="w-5 h-5 text-blue-200" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10c0 3.866-3.582 7-8 7a8.841 8.841 0 01-4.083-.98L2 17l1.338-3.123C2.493 12.767 2 11.434 2 10c0-3.866 3.582-7 8-7s8 3.134 8 7zM7 9H5v2h2V9zm8 0h-2v2h2V9zM9 9h2v2H9V9z" clip-rule="evenodd"></path></svg>
      <span class="font-semibold text-sm">Layanan CS 24/7</span>
    </div>
  </div>
</section>

{{-- ═══════════════════════════════════════════════════════════ --}}
{{-- CATEGORY NAVIGATION                                        --}}
{{-- ═══════════════════════════════════════════════════════════ --}}
<section class="max-w-7xl mx-auto px-4 mb-10">
  <div class="surface-panel rounded-xl p-4 shadow-sm border border-gray-100">
    <div class="grid grid-cols-4 md:grid-cols-8 gap-4 justify-items-center text-center">
      @foreach($allCategories->take(8) as $cat)
      <a href="{{ route('categories.show', $cat->slug) }}" class="category-icon-wrapper block w-full">
        <div class="w-12 h-12 md:w-14 md:h-14 mx-auto rounded-full bg-blue-50 border border-blue-100 flex items-center justify-center mb-2 overflow-hidden">
          @if($cat->image)
            <img src="{{ $cat->image_url }}" alt="{{ $cat->name }}" class="w-full h-full object-cover">
          @else
            <span class="text-2xl">{{ $cat->icon ?? '🎮' }}</span>
          @endif
        </div>
        <span class="text-xs font-semibold text-gray-700 leading-tight block">{{ $cat->name }}</span>
      </a>
      @endforeach
    </div>
  </div>
</section>

{{-- ═══════════════════════════════════════════════════════════ --}}
{{-- FEATURED SECTION: UNLOCK THE SIMULATION (GAME KEYS)        --}}
{{-- ═══════════════════════════════════════════════════════════ --}}
@if(isset($featuredGameKeys) && $featuredGameKeys->count() > 0)
<section class="max-w-7xl mx-auto px-4 mb-10">
  <div class="flex items-center justify-between mb-4">
    <h2 class="section-title mb-0">🔑 Unlock the Simulation (Game Keys)</h2>
    <a href="{{ route('products.search', ['category'=>'game-key']) }}" class="text-sm font-semibold text-itemku-blue hover:underline">Lihat Semua</a>
  </div>
  <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4">
    @foreach($featuredGameKeys as $product)
      @include('components.product-card', ['product' => $product])
    @endforeach
  </div>
</section>
@endif

{{-- ═══════════════════════════════════════════════════════════ --}}
{{-- FEATURED SECTION: UNLOCK EPIC RPG WORLDS                   --}}
{{-- ═══════════════════════════════════════════════════════════ --}}
@if(isset($featuredRPGKeys) && $featuredRPGKeys->count() > 0)
<section class="max-w-7xl mx-auto px-4 mb-10">
  <div class="flex items-center justify-between mb-4">
    <h2 class="section-title mb-0">⚔️ Unlock Epic RPG Worlds</h2>
    <a href="{{ route('products.search', ['category'=>'game-key']) }}" class="text-sm font-semibold text-itemku-blue hover:underline">Lihat Semua</a>
  </div>
  <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4">
    @foreach($featuredRPGKeys as $product)
      @include('components.product-card', ['product' => $product])
    @endforeach
  </div>
</section>
@endif

{{-- ═══════════════════════════════════════════════════════════ --}}
{{-- DYNAMIC CATEGORY SECTIONS                                  --}}
{{-- ═══════════════════════════════════════════════════════════ --}}
@if(isset($categorySections) && $categorySections->count() > 0)
  @foreach($categorySections as $section)
  <section class="max-w-7xl mx-auto px-4 mb-10">
    <div class="flex items-center justify-between mb-4">
      <h2 class="section-title mb-0">{{ $section['category']->name }} Pilihan</h2>
      <a href="{{ route('categories.show', $section['category']->slug) }}" class="text-sm font-semibold text-itemku-blue hover:underline">Lihat Semua</a>
    </div>
    
    <div class="relative">
      <div class="banner-track flex gap-4 overflow-x-auto scroll-smooth snap-x snap-mandatory pb-4">
        @foreach($section['products'] as $product)
          <div class="banner-slide flex-none w-40 md:w-48 lg:w-56">
            @include('components.product-card', ['product' => $product])
          </div>
        @endforeach
      </div>
    </div>
  </section>
  @endforeach
@endif

@endsection

@push('scripts')
<script>
  // Simple script for horizontal scroll if needed
  document.querySelectorAll('.banner-track').forEach(track => {
    let isDown = false;
    let startX;
    let scrollLeft;

    track.addEventListener('mousedown', (e) => {
      isDown = true;
      startX = e.pageX - track.offsetLeft;
      scrollLeft = track.scrollLeft;
    });
    track.addEventListener('mouseleave', () => {
      isDown = false;
    });
    track.addEventListener('mouseup', () => {
      isDown = false;
    });
    track.addEventListener('mousemove', (e) => {
      if(!isDown) return;
      e.preventDefault();
      const x = e.pageX - track.offsetLeft;
      const walk = (x - startX) * 2; // scroll-fast
      track.scrollLeft = scrollLeft - walk;
    });
  });
</script>
@endpush
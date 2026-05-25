@extends('layouts.app')
@section('title', 'Lapak Gaming — Marketplace Game Terpercaya Indonesia')

@push('styles')
<style>
  /* Keep page background controlled by layout theme variables for consistency */
  /* Remove hard-coded body background to match other pages' theme */
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
  .category-icon { transition: transform .25s ease, box-shadow .25s ease; }
  .category-icon .icon { width: 100%; height: 100%; display: inline-block; border-radius: 9999px; }
  .category-icon:hover { transform: translateY(-6px) scale(1.04); box-shadow: 0 8px 20px rgba(2,6,23,0.45); }
  @keyframes floaty {
    0% { transform: translateY(0); }
    50% { transform: translateY(-6px); }
    100% { transform: translateY(0); }
  }
  .category-animate { animation: floaty 6s ease-in-out infinite; }
  /* Staggered delays for up to 13 icons */
  .category-list a:nth-child(1) .category-animate { animation-delay: 0s; }
  .category-list a:nth-child(2) .category-animate { animation-delay: 0.08s; }
  .category-list a:nth-child(3) .category-animate { animation-delay: 0.16s; }
  .category-list a:nth-child(4) .category-animate { animation-delay: 0.24s; }
  .category-list a:nth-child(5) .category-animate { animation-delay: 0.32s; }
  .category-list a:nth-child(6) .category-animate { animation-delay: 0.40s; }
  .category-list a:nth-child(7) .category-animate { animation-delay: 0.48s; }
  .category-list a:nth-child(8) .category-animate { animation-delay: 0.56s; }
  .category-list a:nth-child(9) .category-animate { animation-delay: 0.64s; }
  .category-list a:nth-child(10) .category-animate { animation-delay: 0.72s; }
  .category-list a:nth-child(11) .category-animate { animation-delay: 0.80s; }
  .category-list a:nth-child(12) .category-animate { animation-delay: 0.88s; }
  .category-list a:nth-child(13) .category-animate { animation-delay: 0.96s; }
  .section-title {
    color: var(--text);
    font-size: 1.25rem;
    font-weight: 700;
    margin-bottom: 1rem;
  }
  .trust-badge-container {
    background: linear-gradient(90deg, var(--primary) 0%, var(--accent) 100%);
  }

  .auth-radial {
    background: radial-gradient(ellipse 70% 55% at 50% -5%,
      rgba(37,99,235,0.22) 0%,
      rgba(249,115,22,0.06) 60%,
      transparent 100%);
  }
  .auth-particle {
    position: absolute;
    border-radius: 50%;
    pointer-events: none;
    animation: authFloat var(--dur, 6s) ease-in-out infinite var(--delay, 0s);
    opacity: 0;
    animation-fill-mode: both;
  }
  /* Hero slider styles */
  #hero-slider { position: relative; }
  .hero-track { will-change: transform; }
  .hero-slide { min-width: 100%; }

  /* Featured banners marquee */
  @keyframes featuredScroll {
    0% { transform: translateX(0); }
    100% { transform: translateX(-50%); }
  }
  .animate-featured-scroll { display: inline-flex; }
  .featured-scroll .animate-featured-scroll { animation: featuredScroll 18s linear infinite; }
  @keyframes authFloat {
    0%   { transform: translateY(0) scale(1);   opacity: 0; }
    15%  { opacity: var(--op, 0.18); }
    85%  { opacity: var(--op, 0.18); }
    100% { transform: translateY(-120px) scale(0.6); opacity: 0; }
  }
</style>
@endpush

@section('content')
<div class="relative min-h-screen overflow-hidden">
  <div class="auth-radial absolute inset-0 pointer-events-none"></div>
  <div class="auth-particle w-1.5 h-1.5 bg-brand-500 absolute" style="left:12%; top:82%; --dur:7s; --delay:0s; --op:0.25;"></div>
  <div class="auth-particle w-2 h-2 bg-accent-400 absolute" style="left:78%; top:86%; --dur:9s; --delay:1.5s; --op:0.2;"></div>
  <div class="auth-particle w-1 h-1 bg-brand-400 absolute" style="left:45%; top:90%; --dur:8s; --delay:3s; --op:0.22;"></div>
  <div class="auth-particle w-2 h-2 bg-brand-600 absolute" style="left:63%; top:74%; --dur:11s; --delay:0.8s; --op:0.15;"></div>
  <div class="auth-particle w-1 h-1 bg-accent-500 absolute" style="left:28%; top:88%; --dur:6.5s; --delay:2.2s; --op:0.2;"></div>

  {{-- ═══════════════════════════════════════════════════════════ --}}
  {{-- HERO SECTION (BANNERS)                                     --}}
  {{-- ═══════════════════════════════════════════════════════════ --}}
  <section class="surface-panel pb-6 pt-4">
  <div class="max-w-7xl mx-auto px-4">
    <div class="grid gap-4 md:grid-cols-[7fr_3fr] items-start">
      {{-- Main Banner Carousel (Hero 4:5) --}}
      <div class="rounded-xl overflow-hidden relative bg-surface-950 aspect-[4/5]">
        <div id="hero-slider" class="w-full h-full relative">
          <div class="hero-track w-full h-full flex transition-transform duration-700">
            @if(isset($heroBanners) && $heroBanners->count())
              @foreach($heroBanners as $banner)
                <a href="{{ $banner->link_url ?: '#' }}" class="hero-slide flex-none w-full h-full block">
                  <img src="{{ $banner->image_url }}" class="w-full h-full object-cover" alt="{{ $banner->title ?? 'Promo' }}">
                </a>
              @endforeach
            @else
              <div class="hero-slide flex-none w-full h-full surface-panel flex items-center justify-center">
                <span class="text-itemku-blue font-bold text-xl">Promo Utama</span>
              </div>
            @endif
          </div>
          {{-- Controls --}}
          <div class="absolute left-4 top-1/2 -translate-y-1/2">
            <button id="hero-prev" class="p-2 bg-surface-950/50 text-white rounded border border-white/10">‹</button>
          </div>
          <div class="absolute right-4 top-1/2 -translate-y-1/2">
            <button id="hero-next" class="p-2 bg-surface-950/50 text-white rounded border border-white/10">›</button>
          </div>
        </div>
      </div>

      {{-- Side Banners (Hero 4:5) --}}
      <div class="hidden md:grid gap-4">
        @if(isset($heroBanners) && $heroBanners->count() > 1)
          @foreach($heroBanners->skip(1)->take(2) as $banner)
          <a href="{{ $banner->link_url ?: '#' }}" class="rounded-xl overflow-hidden block aspect-[4/5]">
            <img src="{{ $banner->image_url }}" class="w-full h-full object-cover" alt="Promo {{ $loop->iteration }}">
          </a>
          @endforeach
        @else
          <div class="rounded-xl surface-panel flex items-center justify-center aspect-[4/5]">Promo 2</div>
          <div class="rounded-xl surface-panel flex items-center justify-center aspect-[4/5]">Promo 3</div>
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
  <div class="surface-panel rounded-xl p-4 shadow-sm border border-white/10">
    <div class="flex flex-wrap justify-center gap-6 items-center category-list">
      @php $catsToShow = $allCategories->isNotEmpty() ? $allCategories : $displayCategories; @endphp
      @foreach($catsToShow->take(13) as $cat)
        <a href="{{ route('categories.show', $cat->slug) }}" class="category-icon-wrapper block w-20 sm:w-24 text-center" aria-label="Kategori {{ $cat->name }}">
          <div class="w-16 h-16 sm:w-20 sm:h-20 mx-auto rounded-full bg-surface-weak border border-white/6 flex items-center justify-center mb-2 category-icon category-animate">
            @if(!empty($cat->image_url))
              <img src="{{ $cat->image_url }}" alt="{{ $cat->name }}" class="w-full h-full object-cover rounded-full">
            @else
              <span class="text-2xl">{{ $cat->icon ?? '🎮' }}</span>
            @endif
          </div>
          <span class="text-xs font-semibold surface-muted leading-tight block truncate max-w-[80px]">{{ $cat->name }}</span>
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
</div>

{{-- ================= FEATURED BANNERS (moved below products) ================ --}}
@if(isset($featuredBanners) && $featuredBanners->count())
  <section class="max-w-7xl mx-auto px-4 mb-10">
    <div class="rounded-xl overflow-hidden">
      <div class="featured-scroll overflow-hidden">
        <div class="flex gap-4 animate-featured-scroll will-change-transform">
          @foreach($featuredBanners as $fb)
            <a href="{{ $fb->link_url ?: '#' }}" class="flex-none w-[360px] rounded-lg overflow-hidden block aspect-[3/1]">
              <img src="{{ $fb->image_url }}" class="w-full h-full object-cover" alt="{{ $fb->title ?? 'Featured' }}">
            </a>
          @endforeach
        </div>
      </div>
    </div>
  </section>
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

  // Hero slider autoplay and controls
  (function() {
    const track = document.querySelector('.hero-track');
    if (!track) return;
    const slides = Array.from(track.children);
    let index = 0;
    const total = slides.length;

    function goTo(i) {
      index = (i + total) % total;
      track.style.transform = `translateX(${-index * 100}%)`;
    }

    let timer = setInterval(() => { goTo(index + 1); }, 4000);

    const nextBtn = document.getElementById('hero-next');
    const prevBtn = document.getElementById('hero-prev');
    if (nextBtn) nextBtn.addEventListener('click', () => { goTo(index + 1); resetTimer(); });
    if (prevBtn) prevBtn.addEventListener('click', () => { goTo(index - 1); resetTimer(); });

    function resetTimer() { clearInterval(timer); timer = setInterval(() => { goTo(index + 1); }, 4000); }
  })();

  // Duplicate featured items for continuous marquee if needed
  (function() {
    const marquee = document.querySelector('.featured-scroll .animate-featured-scroll');
    if (!marquee) return;
    // Duplicate children to allow seamless loop
    const clone = marquee.cloneNode(true);
    marquee.parentNode.appendChild(clone);
  })();
</script>
@endpush
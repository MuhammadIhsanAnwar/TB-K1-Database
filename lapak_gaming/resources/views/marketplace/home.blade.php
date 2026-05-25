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
  .hero-stage {
    background:
      radial-gradient(circle at top left, rgba(59,130,246,0.18), transparent 38%),
      linear-gradient(180deg, rgba(2,6,23,0.98), rgba(15,23,42,0.94));
    box-shadow: 0 30px 80px rgba(2, 6, 23, 0.28);
  }
  .hero-stage::after {
    content: '';
    position: absolute;
    inset: 0;
    background: linear-gradient(180deg, transparent 0%, rgba(2,6,23,0.18) 100%);
    pointer-events: none;
  }
  .featured-stage {
    background:
      linear-gradient(135deg, rgba(2,6,23,0.95), rgba(30,41,59,0.92)),
      radial-gradient(circle at top right, rgba(249,115,22,0.12), transparent 32%);
    border: 1px solid rgba(255,255,255,0.08);
    box-shadow: 0 16px 40px rgba(2, 6, 23, 0.18);
  }
  .section-kicker {
    letter-spacing: 0.18em;
    text-transform: uppercase;
    font-size: 0.7rem;
    font-weight: 800;
  }

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
  <section class="relative overflow-hidden pb-8">
    <div class="absolute inset-x-0 top-0 h-[380px] bg-gradient-to-b from-[#0f2a5b] via-[#09182e] to-transparent pointer-events-none"></div>
    <div class="max-w-7xl mx-auto px-4 pt-6">
      <div class="rounded-[32px] border border-white/10 bg-[#081125]/95 p-6 shadow-[0_30px_90px_rgba(5,12,35,0.28)]">
        <div class="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
          <div class="max-w-2xl">
            <div class="section-kicker text-blue-300">Hero Banner</div>
            <h2 class="mt-3 text-3xl font-black text-white md:text-4xl">Promo utama yang paling menonjol</h2>
            <p class="mt-4 max-w-2xl text-sm text-slate-300">Temukan penawaran eksklusif, top up cepat, dan game populer dalam satu tampilan banner hero.</p>
          </div>
          <div class="flex flex-wrap gap-3">
            <a href="{{ route('products.search', ['q'=>'top up game']) }}" class="inline-flex items-center justify-center rounded-2xl bg-blue-500 px-5 py-3 text-sm font-semibold text-white transition hover:bg-blue-400">Beli Sekarang</a>
            <a href="{{ route('marketplace.trending') }}" class="inline-flex items-center justify-center rounded-2xl border border-white/10 bg-white/5 px-5 py-3 text-sm font-semibold text-slate-100 transition hover:bg-white/10">Lihat Trending</a>
          </div>
        </div>

        <div class="mt-6 grid gap-4 lg:grid-cols-4">
          @if(isset($heroBanners) && $heroBanners->count())
            @foreach($heroBanners->take(4) as $banner)
              <a href="{{ $banner->link_url ?: '#' }}" class="group relative overflow-hidden rounded-[28px] border border-white/10 bg-slate-950/80 shadow-[0_25px_60px_rgba(3,8,32,0.35)] transition-transform duration-300 hover:-translate-y-1 hover:shadow-[0_32px_90px_rgba(3,8,32,0.45)]">
                <img src="{{ $banner->image_url }}" alt="{{ $banner->title ?? 'Banner Promo' }}" class="h-72 w-full object-cover transition duration-500 group-hover:scale-105" />
                <div class="absolute inset-0 bg-gradient-to-t from-slate-950/90 via-slate-950/40 to-transparent"></div>
                <div class="absolute inset-x-0 bottom-0 p-5">
                  <span class="inline-flex rounded-full bg-amber-500/15 px-3 py-1 text-[11px] font-bold uppercase tracking-[0.24em] text-amber-300">Hot Games</span>
                  <h3 class="mt-4 text-lg font-extrabold text-white line-clamp-2">{{ $banner->title ?? 'Promo Spesial' }}</h3>
                  @if(!empty($banner->subtitle))
                    <p class="mt-2 text-sm text-slate-300 line-clamp-2">{{ $banner->subtitle }}</p>
                  @endif
                  <div class="mt-4 inline-flex items-center gap-2 rounded-full border border-white/10 bg-white/10 px-4 py-2 text-sm font-semibold text-white transition hover:bg-white/15">
                    <span>Explore</span>
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                  </div>
                </div>
              </a>
            @endforeach
          @else
            <div class="col-span-1 lg:col-span-4 rounded-[28px] border border-white/10 bg-slate-950/80 p-10 text-center">
              <p class="text-sm text-slate-400">Belum ada hero banner tersedia. Tambahkan banner hero dari panel admin.</p>
            </div>
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
    <div class="mb-3 flex flex-wrap items-center justify-between gap-3 px-1">
      <div>
        <div class="section-kicker text-orange-300">Featured Banner</div>
        <h2 class="mt-1 text-lg font-bold text-white">Banner promo pendamping</h2>
      </div>
      <span class="inline-flex items-center rounded-full border border-orange-400/20 bg-orange-500/10 px-3 py-1 text-xs font-semibold text-orange-200 backdrop-blur">
        3:1 marquee
      </span>
    </div>
    <div class="featured-stage rounded-2xl overflow-hidden p-3 md:p-4">
      <div class="featured-scroll overflow-hidden">
        <div class="flex gap-3 md:gap-4 animate-featured-scroll will-change-transform">
          @foreach($featuredBanners as $fb)
            <a href="{{ $fb->link_url ?: '#' }}" class="flex-none w-[360px] md:w-[480px] rounded-xl overflow-hidden block aspect-[16/9] border border-white/10 shadow-lg shadow-black/20">
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
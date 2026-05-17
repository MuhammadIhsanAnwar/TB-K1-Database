@extends('layouts.app')
@section('title', 'Lapak Gaming — Marketplace Game Terpercaya Indonesia')

@push('styles')
<style>
  /* ── Hero Background Glow ─────────────────────────────────── */
  .hero-glow {
    background: radial-gradient(ellipse 70% 60% at 50% -10%, rgba(37,99,235,0.25), transparent 70%);
  }

  /* ── Category Buttons ─────────────────────────────────────── */
  .cat-btn {
    background: #0D1421;
    border: 1px solid #1E2D45;
    border-radius: 14px;
    transition: all 0.2s;
  }
  .cat-btn:hover {
    border-color: rgba(37,99,235,0.5);
    background: rgba(37,99,235,0.08);
    transform: translateY(-2px);
  }

  /* ── Topup Cards ──────────────────────────────────────────── */
  .topup-card {
    background: #0D1421;
    border: 1px solid #1E2D45;
    border-radius: 14px;
    overflow: hidden;
    transition: all 0.2s;
  }
  .topup-card:hover {
    border-color: rgba(37,99,235,0.5);
    transform: translateY(-3px);
    box-shadow: 0 12px 32px rgba(0,0,0,0.5);
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

  /* ═══════════════════════════════════════
   DUNIAGAMES STYLE BUTTON
═══════════════════════════════════════ */

.dg-btn {
  background:
    linear-gradient(135deg,#2563eb 0%,#1d4ed8 40%,#0f172a 100%);
  border: 1px solid rgba(96,165,250,0.35);

  box-shadow:
    0 10px 30px rgba(37,99,235,0.25),
    inset 0 1px 0 rgba(255,255,255,0.08);

  letter-spacing: .02em;

  transform-style: preserve-3d;
}

.dg-btn:hover {
  transform:
    translateY(-3px)
    scale(1.02);

  box-shadow:
    0 18px 40px rgba(37,99,235,0.38),
    0 0 30px rgba(96,165,250,0.22);
}

.dg-btn:active {
  transform: scale(.98);
}

.dg-btn-glow {
  position: absolute;
  inset: 0;

  background:
    linear-gradient(
      120deg,
      transparent 20%,
      rgba(255,255,255,0.20) 50%,
      transparent 80%
    );

  transform: translateX(-120%);
  transition: transform .8s ease;
}

.dg-btn:hover .dg-btn-glow {
  transform: translateX(120%);
}
  /* ══════════════════════════════════════════════════════════
     3D ROBOT — animations & layout
  ══════════════════════════════════════════════════════════ */
  #hero-robot-wrapper {
    transform-style: preserve-3d;
  }

  #robot-scene-container {
    width: 100%;
  height: 540px;
  min-height: 540px;
  position: relative;
  z-index: 20;
    
  }

  #robot-tilt-layer{
   width:100%;
   height:100%;
   position: absolute;
   top: 0;
   left: 0;
   z-index: 21;
   will-change: transform;
}

  spline-viewer {
    width: 100%;
    height: 100%;
    background: transparent !important;
    display: block;
    transform-style: preserve-3d;
  }

  spline-viewer::part(logo) { display: none !important; }

  @keyframes robotGlow1 {
    0%, 100% { opacity: 0.28; transform: scale(1); }
    50%       { opacity: 0.50; transform: scale(1.10); }
  }
  @keyframes robotGlow2 {
    0%, 100% { opacity: 0.15; transform: scale(1) translate(-50%,-50%); }
    50%       { opacity: 0.32; transform: scale(1.15) translate(-50%,-50%); }
  }
  .robot-glow-blue   { animation: robotGlow1 4.0s ease-in-out infinite; }
  .robot-glow-orange { animation: robotGlow2 5.5s ease-in-out infinite 1.2s; }

  @keyframes ringCW  { to { transform: rotate(360deg); } }
  @keyframes ringCCW { to { transform: rotate(-360deg); } }
  .ring-cw,
.ring-ccw {
  animation: none !important;
}

  @keyframes badgeFloat {
    0%, 100% { transform: translateY(0px); }
    50%       { transform: translateY(-7px); }
  }
  .badge-float-a { animation: badgeFloat 4.0s ease-in-out infinite; }
  .badge-float-b { animation: badgeFloat 5.2s ease-in-out infinite 1.5s; }
  .badge-float-c { animation: badgeFloat 4.7s ease-in-out infinite 0.8s; }

  @keyframes scanSweep {
    0%   { top: 14%; opacity: 0; }
    6%   { opacity: 1; }
    94%  { opacity: 1; }
    100% { top: 86%; opacity: 0; }
  }
  .scan-line {
    position: absolute;
    left: 12%; right: 12%;
    height: 1px;
    background: linear-gradient(90deg,transparent 0%,rgba(37,99,235,0.55) 20%,rgba(147,197,253,0.9) 50%,rgba(37,99,235,0.55) 80%,transparent 100%);
    animation: scanSweep 7s ease-in-out infinite 2.5s;
    pointer-events: none;
    z-index: 25;
  }

  .corner-bracket {
    position: absolute;
    width: 20px; height: 20px;
    opacity: 0.45;
    pointer-events: none;
    z-index: 26;
  }
  .corner-bracket.tl { top:22px;  left:22px;    border-top:2px solid #60a5fa; border-left:2px solid #60a5fa; }
  .corner-bracket.tr { top:22px;  right:22px;   border-top:2px solid #60a5fa; border-right:2px solid #60a5fa; }
  .corner-bracket.bl { bottom:22px; left:22px;  border-bottom:2px solid #60a5fa; border-left:2px solid #60a5fa; }
  .corner-bracket.br { bottom:22px; right:22px; border-bottom:2px solid #60a5fa; border-right:2px solid #60a5fa; }

  #robot-loader {
  opacity: 1;
  filter: blur(0px);
  transition:
    opacity .7s ease,
    filter .7s ease;
}

#robot-loader.loader-hidden {
  opacity: 0;
  filter: blur(10px);
  pointer-events: none;
}

#robot-tilt-layer{
   width:100%;
   height:100%;
   transform-style: preserve-3d;
   will-change: transform;
}


  #spline-logo-cover {
    position: absolute;
    bottom: 0; right: 0;
    width: 130px; height: 44px;
    background: linear-gradient(135deg,transparent 35%,#060A12 100%);
    pointer-events: none;
    z-index: 28;
  }

  #robot-tilt-layer{
   width:100%;
   height:100%;
   position:absolute;
   inset:0;
   display:flex;
   align-items:center;
   justify-content:center;
   pointer-events:none;
}

spline-viewer::part(watermark) {
  display: none !important;
}

spline-viewer::part(badge) {
  display: none !important;
}

spline-viewer iframe {
  pointer-events: auto;
}

#robot-only{
   width:100%;
   height:100%;
   transform-style:preserve-3d;
   will-change:transform;
}
   /* ── Scroll Reveal Animation ───────────────────────────── */
  /* ── Clean Reveal Animation ───────────────────────────── */
.reveal-card {
  opacity: 0;
  transform: translateY(30px);
  transition:
    opacity 0.7s ease-out,
    transform 0.7s ease-out;
  will-change: opacity, transform;
}

.reveal-card.show {
  opacity: 1;
  transform: translateY(0);
}

/* delay */
.reveal-delay-1 { transition-delay: .05s; }
.reveal-delay-2 { transition-delay: .10s; }
.reveal-delay-3 { transition-delay: .15s; }
.reveal-delay-4 { transition-delay: .20s; }
.reveal-delay-5 { transition-delay: .25s; }
.reveal-delay-6 { transition-delay: .30s; }
</style>
@endpush

@section('content')

{{-- ═══════════════════════════════════════════════════════════ --}}
{{-- HERO SECTION                                               --}}
{{-- ═══════════════════════════════════════════════════════════ --}}
<section class="relative overflow-hidden pt-10 pb-16" id="hero-section">
  <div class="hero-glow absolute inset-0 pointer-events-none"></div>

  <div class="absolute top-10 right-1/4 w-64 h-64 rounded-full pointer-events-none opacity-10"
       style="background:radial-gradient(circle,#2563eb,transparent 70%);filter:blur(40px);"></div>
  <div class="absolute bottom-0 left-1/3 w-48 h-48 rounded-full pointer-events-none opacity-10"
       style="background:radial-gradient(circle,#f97316,transparent 70%);filter:blur(36px);"></div>

  <div class="max-w-7xl mx-auto px-4">
    <div class="flex flex-col lg:flex-row items-center gap-8 xl:gap-12">

      {{-- Left: Copy --}}
      <div class="flex-1 text-center lg:text-left animate-fade-up">
        <div class="inline-flex items-center gap-2 mb-5 px-4 py-1.5 rounded-full text-xs font-display font-semibold"
             style="background:rgba(37,99,235,0.12);border:1px solid rgba(37,99,235,0.3);color:#60a5fa;">
          <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
          100.000+ Transaksi Berhasil Hari Ini
        </div>

        <h1 class="font-display text-4xl sm:text-5xl lg:text-6xl font-extrabold text-white leading-tight mb-5">
          Marketplace<br>
          <span style="background:linear-gradient(135deg,#60a5fa 0%,#fb923c 100%);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;">
            Gaming #1
          </span><br>
          Indonesia
        </h1>

        <p class="text-slate-400 text-base lg:text-lg leading-relaxed mb-8 max-w-xl mx-auto lg:mx-0">
          Top-up diamond, beli item langka, jual akun game, dan tukar voucher dengan harga terbaik. Transaksi aman, instan, dan terjamin.
        </p>

        <div class="flex flex-wrap gap-3 justify-center lg:justify-start">
        </div>

        <div class="flex flex-wrap gap-6 mt-10 justify-center lg:justify-start">
          @php
            $heroStats = [
              ['num' => $activeUsers, 'label' => 'Pengguna Aktif'],
              ['num' => $availableProducts, 'label' => 'Produk Tersedia'],
              ['num' => $verifiedSellers, 'label' => 'Seller Verified'],
              ['num' => $transactionCount, 'label' => 'Transaksi Sukses'],
            ];
          @endphp

          @foreach($heroStats as $stat)
          <div class="text-center lg:text-left">
            <div class="font-display font-bold text-2xl text-white js-stat-counter" data-target="{{ $stat['num'] }}">0</div>
            <div class="text-xs text-slate-500 mt-0.5">{{ $stat['label'] }}</div>
          </div>
          @endforeach
        </div>
      </div>

      {{-- Right: 3D Robot AI Assistant --}}
      <div class="hidden lg:flex flex-shrink-0 items-center justify-center relative"
           id="hero-robot-wrapper"
           style="width:500px;height:540px;">

      

        {{-- Decorative rings --}}
        <div class="ring-cw absolute rounded-full pointer-events-none" style="width:455px;height:455px;z-index:2;border:1px solid rgba(37,99,235,0.18);border-top-color:rgba(96,165,250,0.60);border-right-color:rgba(37,99,235,0.38);"></div>
        <div class="ring-ccw absolute rounded-full pointer-events-none" style="width:398px;height:398px;z-index:2;border:1px dashed rgba(96,165,250,0.10);"></div>

        {{-- Ambient glow --}}
        <div class="robot-glow-blue absolute rounded-full pointer-events-none"
             style="width:290px;height:290px;z-index:1;background:radial-gradient(circle,rgba(37,99,235,0.38) 0%,transparent 70%);filter:blur(24px);"></div>
        <div class="robot-glow-orange absolute pointer-events-none"
             style="width:200px;height:200px;top:58%;left:58%;z-index:1;border-radius:50%;background:radial-gradient(circle,rgba(249,115,22,0.22) 0%,transparent 70%);filter:blur(44px);transform:translate(-50%,-50%);"></div>

        {{-- Loading skeleton --}}
        <div id="robot-loader" class="absolute inset-0 flex items-center justify-center" style="z-index:30;">
          <div class="flex flex-col items-center gap-4">
            <div class="relative w-20 h-20">
              <div class="absolute inset-0 rounded-full border-2 border-brand-500/20 border-t-brand-400 animate-spin"></div>
              <div class="absolute inset-2 rounded-full border border-brand-500/10 border-b-accent-400/50"
                   style="animation:spin 2s linear infinite reverse;"></div>
              <div class="absolute inset-0 flex items-center justify-center">
                <svg class="w-7 h-7" style="color:rgba(96,165,250,0.5);" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 3H5a2 2 0 00-2 2v4m6-6h10a2 2 0 012 2v4M9 3v18m0 0h10a2 2 0 002-2V9M9 21H5a2 2 0 01-2-2V9m0 0h18"/>
                </svg>
              </div>
            </div>
            <div class="flex flex-col items-center gap-2">
              <span class="text-[10px] tracking-[0.25em] uppercase font-display" style="color:rgba(96,165,250,0.6);">Initializing AI</span>
              <div class="flex gap-1.5">
                <span class="w-1.5 h-1.5 rounded-full bg-brand-400/50 animate-bounce" style="animation-delay:0.0s;"></span>
                <span class="w-1.5 h-1.5 rounded-full bg-brand-400/50 animate-bounce" style="animation-delay:0.2s;"></span>
                <span class="w-1.5 h-1.5 rounded-full bg-brand-400/50 animate-bounce" style="animation-delay:0.4s;"></span>
              </div>
            </div>
          </div>
        </div>

        {{-- ★ Spline 3D Robot ★ --}}
        <div id="robot-scene-container">
        </div>
        <div id="robot-tilt-layer">
  <div id="robot-only">
    <spline-viewer
      id="spline-robot"
      loading="eager"
      events-target="global"
      loading-anim-type="spinner-big-dark"
      url="https://prod.spline.design/vNP16bdGzzl-ASAu/scene.splinecode">
    </spline-viewer>
  </div>
</div>
    
        {{-- Spline branding cover --}}
        <div id="spline-logo-cover"></div>

        {{-- Scan line --}}
        <div class="scan-line"></div>

        {{-- Corner brackets --}}
        <div class="corner-bracket tl"></div>
        <div class="corner-bracket tr"></div>
        <div class="corner-bracket bl"></div>
        <div class="corner-bracket br"></div>

        {{-- Badge: AI Online --}}
        <div class="badge-float-a absolute z-30" style="top:28px;right:-8px;">
          <div class="flex items-center gap-2 rounded-2xl px-3.5 py-2.5 backdrop-blur-md"
               style="background:rgba(9,14,26,0.88);border:1px solid rgba(16,185,129,0.45);box-shadow:0 4px 24px rgba(0,0,0,0.55),0 0 14px rgba(16,185,129,0.18);">
            <span class="relative flex h-2 w-2">
              <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
              <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-400"></span>
            </span>
            <span class="text-emerald-300 text-xs font-semibold font-display tracking-wide">AI Online</span>
          </div>
        </div>

        {{-- Badge: Daily transactions --}}
        <div class="badge-float-b absolute z-30" style="bottom:96px;left:-20px;">
          <div class="rounded-2xl px-4 py-2.5 backdrop-blur-md"
               style="background:rgba(9,14,26,0.88);border:1px solid rgba(37,99,235,0.45);box-shadow:0 4px 24px rgba(0,0,0,0.55),0 0 14px rgba(37,99,235,0.18);">
            <div class="text-[10px] tracking-[0.18em] uppercase font-display mb-0.5" style="color:rgba(96,165,250,0.6);">Transaksi Hari Ini</div>
            <div class="font-display font-bold text-white text-lg leading-none">{{ number_format($todayTransactions, 0, ',', '.') }}</div>
          </div>
        </div>

        {{-- Badge: Rating --}}
        <div class="badge-float-c absolute z-30" style="bottom:44px;right:-4px;">
          <div class="flex items-center gap-2 rounded-2xl px-3.5 py-2 backdrop-blur-md"
               style="background:rgba(9,14,26,0.88);border:1px solid rgba(249,115,22,0.38);box-shadow:0 4px 24px rgba(0,0,0,0.55),0 0 12px rgba(249,115,22,0.14);">
            <span style="font-size:14px;">⭐</span>
            <span class="font-display font-bold text-white text-sm">{{ number_format($averageRating ?: 0, 1) }}</span>
            <span class="text-slate-500 text-xs">Rating</span>
          </div>
        </div>

      </div>{{-- /right robot --}}

    </div>
  </div>
</section>

{{-- ═══════════════════════════════════════════════════════════ --}}
{{-- HERO BANNERS                                               --}}
{{-- ═══════════════════════════════════════════════════════════ --}}
@if(isset($heroBanners) && $heroBanners->count())
<section class="relative py-5 sm:py-7">
  <div class="max-w-7xl mx-auto px-4">
    <div class="overflow-hidden rounded-[28px] border border-slate-800 bg-slate-950/40 shadow-card-hover">
      <div class="relative">
        <div id="banner-track" class="banner-track flex gap-4 overflow-x-auto scroll-smooth snap-x snap-mandatory p-3 sm:p-4">
          @foreach($heroBanners as $banner)
          <a href="{{ $banner->link_url ?: '#' }}" class="banner-slide group relative flex-none w-[72%] sm:w-[56%] md:w-[38%] xl:w-[28%] overflow-hidden rounded-[24px] border border-slate-800 bg-slate-900">
            <div class="relative aspect-[4/5] overflow-hidden">
              <img src="{{ $banner->image_url }}" alt="{{ $banner->title }}" class="h-full w-full object-cover transition duration-500 group-hover:scale-105">
              <div class="absolute inset-0 bg-gradient-to-r from-slate-950/90 via-slate-950/35 to-transparent"></div>
              <div class="absolute inset-0 flex items-end p-5 sm:p-6">
                <div class="max-w-md">
                  <span class="inline-flex items-center rounded-full border border-amber-400/30 bg-amber-400/10 px-3 py-1 text-[10px] font-semibold uppercase tracking-[0.22em] text-amber-200">Iklan Beranda</span>
                  <h3 class="mt-3 text-xl sm:text-2xl font-bold text-white">{{ $banner->title }}</h3>
                  <p class="mt-1 text-sm text-slate-300">{{ $banner->subtitle }}</p>
                </div>
              </div>
            </div>
          </a>
        @endforeach
        </div>
        <button id="banner-prev" aria-label="Previous" class="absolute left-3 top-1/2 -translate-y-1/2 z-20 rounded-full bg-slate-800/60 p-2 hover:bg-slate-800 text-white">
          <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        </button>
        <button id="banner-next" aria-label="Next" class="absolute right-3 top-1/2 -translate-y-1/2 z-20 rounded-full bg-slate-800/60 p-2 hover:bg-slate-800 text-white">
          <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        </button>
      </div>
    </div>
  </div>
</section>
@endif

{{-- ═══════════════════════════════════════════════════════════ --}}
{{-- CATEGORY SHORTCUTS                                          --}}
{{-- ═══════════════════════════════════════════════════════════ --}}
<section class="pb-14">
  <div class="max-w-7xl mx-auto px-4">
    <div class="flex items-center justify-between mb-6">
      <h2 class="section-title font-display font-bold text-lg text-white">Kategori Game</h2>
      <a href="{{ route('products.search') }}" class="text-sm text-brand-400 hover:text-brand-300 transition-colors">Lihat semua →</a>
    </div>
    <div class="grid grid-cols-3 sm:grid-cols-4 md:grid-cols-6 lg:grid-cols-8 gap-3">
      @foreach($categories as $cat)
      <a href="{{ route('categories.show', $cat->slug) }}" 
        class="cat-btn reveal-card reveal-delay-{{ ($loop->index % 6) + 1 }} flex flex-col items-center gap-2.5 p-3 group">
        <div class="w-12 h-12 rounded-xl flex items-center justify-center overflow-hidden transition-transform group-hover:scale-110"
             style="background:#162032;">
          @if($cat->image)
            <img src="{{ $cat->image_url }}" alt="{{ $cat->name }}" class="w-10 h-10 object-cover rounded-lg">
          @else
            <span class="text-2xl">{{ $cat->icon ?? '🎮' }}</span>
          @endif
        </div>
        <span class="text-[11px] text-slate-300 text-center font-medium leading-tight">
          {{ \Illuminate\Support\Str::limit($cat->name, 12) }}
        </span>
      </a>
      @endforeach
    </div>
  </div>
</section>

@if(isset($categoryProducts) && $categoryProducts->count())
<section class="pb-20">
  <div class="max-w-7xl mx-auto px-4 space-y-8">
    <div class="flex items-end justify-between gap-4">
      <div>
        <h2 class="section-title font-display font-bold text-lg text-white">Semua Kategori Produk</h2>
        <p class="text-xs text-slate-500 mt-1 pl-4">Geser produk ke kanan dan kiri di setiap kategori</p>
      </div>
      <a href="{{ route('products.search') }}" class="text-sm text-brand-400 hover:text-brand-300 transition-colors">Lihat semua produk →</a>
    </div>

      @foreach($categoryProducts as $group)
  @php
      $category = $group['category'];
      $products = $group['products'];
  @endphp
      <section class="reveal-card rounded-[28px] border border-slate-800 bg-slate-950/60 p-4 sm:p-5 shadow-card-hover">
        <div class="flex items-center justify-between gap-3 mb-4">
          <div>
            <h3 class="font-display text-xl font-bold text-white">{{ $category->name }}</h3>
            <p class="text-xs text-slate-500 mt-1">{{ $products->count() }} produk tersedia</p>
          </div>
          <div class="flex items-center gap-2">
            <button type="button" class="category-scroll-btn rounded-xl border border-slate-800 bg-slate-900 p-2 text-slate-300 hover:text-white" data-target="category-track-{{ $category->id }}" data-dir="left" aria-label="Scroll kiri">
              <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            </button>
            <button type="button" class="category-scroll-btn rounded-xl border border-slate-800 bg-slate-900 p-2 text-slate-300 hover:text-white" data-target="category-track-{{ $category->id }}" data-dir="right" aria-label="Scroll kanan">
              <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </button>
          </div>
        </div>

        <div id="category-track-{{ $category->id }}" class="category-track flex gap-4 overflow-x-auto scroll-smooth snap-x snap-mandatory pb-2">
          @foreach($products as $product)
            <div class="flex-none w-[72%] sm:w-[40%] md:w-[28%] xl:w-[20%] snap-start">
              @include('components.product-card', ['product' => $product])
            </div>
          @endforeach
        </div>
      </section>
    @endforeach
  </div>
</section>
@endif

{{-- ═══════════════════════════════════════════════════════════ --}}
{{-- PREMIUM QUICK ACCESS                                       --}}
{{-- GANTI FULL SECTION "QUICK ACCESS — TYPE BUTTONS" DENGAN INI --}}
{{-- ═══════════════════════════════════════════════════════════ --}}

<section class="pb-24 relative overflow-hidden">

    {{-- Background Glow --}}
    <div class="absolute inset-0 pointer-events-none">
        <div class="absolute top-0 left-0 w-[400px] h-[400px] bg-cyan-500/10 blur-[120px] rounded-full"></div>
        <div class="absolute bottom-0 right-0 w-[400px] h-[400px] bg-purple-500/10 blur-[120px] rounded-full"></div>
    </div>

    <div class="max-w-7xl mx-auto px-4 relative z-10">

        {{-- HEADER --}}
        <div class="text-center mb-12">

            <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full border border-cyan-400/20 bg-cyan-400/5 backdrop-blur-xl mb-5">

                <span class="w-2 h-2 rounded-full bg-cyan-400 animate-pulse"></span>

                <span class="text-cyan-300 text-xs uppercase tracking-[0.25em] font-bold">
                    Gaming Services
                </span>

            </div>

            <h2 class="font-display text-4xl sm:text-5xl font-black text-white leading-tight">

                Akses Cepat
                <span class="bg-gradient-to-r from-cyan-300 via-blue-400 to-purple-400 bg-clip-text text-transparent">
                    Kebutuhan Gaming
                </span>

            </h2>

            <p class="text-slate-400 mt-5 max-w-2xl mx-auto leading-relaxed">
                Pilih layanan favoritmu dengan tampilan premium,
                transaksi cepat, dan pengalaman gaming terbaik.
            </p>

        </div>

        {{-- PREMIUM GRID --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-6">

            @foreach([
                [
                    'type'=>'topup',
                    'icon'=>'⚡',
                    'label'=>'Top Up Game',
                    'desc'=>'Diamond & UC instan otomatis',
                    'gradient'=>'from-cyan-500/20 via-blue-500/10 to-transparent',
                    'border'=>'border-cyan-400/20',
                    'glow'=>'bg-cyan-400/20',
                    'badge'=>'Instant',
                    'badgeColor'=>'text-cyan-300 bg-cyan-400/10 border-cyan-400/20',
                ],

                [
                    'type'=>'joki',
                    'icon'=>'🏆',
                    'label'=>'Jasa Joki',
                    'desc'=>'Push rank cepat & aman',
                    'gradient'=>'from-orange-500/20 via-yellow-500/10 to-transparent',
                    'border'=>'border-orange-400/20',
                    'glow'=>'bg-orange-400/20',
                    'badge'=>'Popular',
                    'badgeColor'=>'text-orange-300 bg-orange-400/10 border-orange-400/20',
                ],

                [
                    'type'=>'akun',
                    'icon'=>'👤',
                    'label'=>'Akun Sultan',
                    'desc'=>'Akun premium ready stock',
                    'gradient'=>'from-purple-500/20 via-fuchsia-500/10 to-transparent',
                    'border'=>'border-purple-400/20',
                    'glow'=>'bg-purple-400/20',
                    'badge'=>'Premium',
                    'badgeColor'=>'text-purple-300 bg-purple-400/10 border-purple-400/20',
                ],

                [
                    'type'=>'item',
                    'icon'=>'⚔️',
                    'label'=>'Item & Skin',
                    'desc'=>'Skin rare & item eksklusif',
                    'gradient'=>'from-emerald-500/20 via-green-500/10 to-transparent',
                    'border'=>'border-emerald-400/20',
                    'glow'=>'bg-emerald-400/20',
                    'badge'=>'Hot',
                    'badgeColor'=>'text-emerald-300 bg-emerald-400/10 border-emerald-400/20',
                ],
            ] as $index => $qt)

            <a href="{{ route('products.by-type', $qt['type']) }}"
               class="group relative overflow-hidden rounded-[30px] border {{ $qt['border'] }} bg-white/[0.03] backdrop-blur-xl p-6 hover:-translate-y-2 transition-all duration-500">

                {{-- Hover Glow --}}
                <div class="absolute inset-0 opacity-0 group-hover:opacity-100 transition duration-500">

                    <div class="absolute inset-0 bg-gradient-to-br {{ $qt['gradient'] }}"></div>

                    <div class="absolute -top-10 -right-10 w-40 h-40 {{ $qt['glow'] }} blur-[80px] rounded-full"></div>

                </div>

                {{-- Floating Border --}}
                <div class="absolute inset-[1px] rounded-[29px] border border-white/5 pointer-events-none"></div>

                <div class="relative z-10 flex flex-col h-full">

                    {{-- TOP --}}
                    <div class="flex items-start justify-between mb-8">

                        {{-- ICON --}}
                        <div class="relative">

                            <div class="absolute inset-0 blur-2xl opacity-40 {{ $qt['glow'] }}"></div>

                            <div class="relative w-20 h-20 rounded-3xl bg-white/[0.04] border border-white/10 backdrop-blur-xl flex items-center justify-center text-4xl group-hover:scale-110 transition duration-500">

                                {{ $qt['icon'] }}

                            </div>

                        </div>

                        {{-- BADGE --}}
                        <div class="px-3 py-1.5 rounded-full border text-[10px] uppercase tracking-[0.18em] font-bold backdrop-blur-xl {{ $qt['badgeColor'] }}">

                            {{ $qt['badge'] }}

                        </div>

                    </div>

                    {{-- CONTENT --}}
                    <div class="flex-1">

                        <h3 class="text-2xl font-black text-white leading-tight mb-3">
                            {{ $qt['label'] }}
                        </h3>

                        <p class="text-slate-400 leading-relaxed text-sm">
                            {{ $qt['desc'] }}
                        </p>

                    </div>

                    {{-- BOTTOM --}}
                    <div class="flex items-center justify-between mt-8 pt-6 border-t border-white/5">

                        <div class="flex items-center gap-2">

                            <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>

                            <span class="text-xs text-slate-400">
                                Online 24/7
                            </span>

                        </div>

                        <div class="w-12 h-12 rounded-2xl border border-white/10 bg-white/[0.04] flex items-center justify-center text-white group-hover:bg-white group-hover:text-black group-hover:scale-110 transition-all duration-300">

                            <svg class="w-5 h-5"
                                 fill="none"
                                 viewBox="0 0 24 24"
                                 stroke="currentColor">

                                <path stroke-linecap="round"
                                      stroke-linejoin="round"
                                      stroke-width="2.3"
                                      d="M14 5l7 7m0 0l-7 7m7-7H3"/>

                            </svg>

                        </div>

                    </div>

                </div>

            </a>

            @endforeach

        </div>

    </div>

</section>


{{-- ═══════════════════════════════════════════════════════════ --}}
{{-- PREMIUM DISCOVERY SECTION                                  --}}
{{-- ═══════════════════════════════════════════════════════════ --}}

<section class="pb-20 relative overflow-hidden">

    {{-- Background Glow --}}
    <div class="absolute inset-0 pointer-events-none">
        <div class="absolute top-0 left-1/2 -translate-x-1/2 w-[900px] h-[400px] bg-cyan-500/10 blur-[140px] rounded-full"></div>
        <div class="absolute bottom-0 right-0 w-[500px] h-[300px] bg-purple-500/10 blur-[120px] rounded-full"></div>
    </div>

    <div class="max-w-7xl mx-auto px-4 relative z-10">

        {{-- HEADER --}}
        <div class="flex flex-col lg:flex-row lg:items-end lg:justify-between gap-6 mb-10">

            <div>
                <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full border border-cyan-400/20 bg-cyan-400/5 mb-5">
                    <span class="w-2 h-2 rounded-full bg-cyan-400 animate-pulse"></span>
                    <span class="text-cyan-300 text-xs tracking-[0.25em] uppercase font-semibold">
                        Featured Collection
                    </span>
                </div>

                <h2 class="font-display text-4xl font-black text-white leading-tight">
                    Produk Pilihan<br>
                    <span class="bg-gradient-to-r from-cyan-300 via-blue-400 to-purple-400 bg-clip-text text-transparent">
                        Gamer Indonesia
                    </span>
                </h2>

                <p class="text-slate-400 mt-4 max-w-xl leading-relaxed">
                    Koleksi produk premium dengan rating tinggi, transaksi tercepat,
                    dan harga terbaik pilihan komunitas gamer.
                </p>
            </div>

            <a href="{{ route('products.search') }}"
               class="group relative overflow-hidden rounded-2xl border border-cyan-400/20 bg-white/5 backdrop-blur-xl px-6 py-4 hover:border-cyan-400/40 transition-all duration-300">

                <div class="absolute inset-0 bg-gradient-to-r from-cyan-500/0 via-cyan-400/10 to-cyan-500/0 translate-x-[-120%] group-hover:translate-x-[120%] transition duration-1000"></div>

                <div class="relative flex items-center gap-3">
                    <span class="text-white font-semibold">
                        Explore Semua Produk
                    </span>

                    <svg class="w-5 h-5 text-cyan-300 group-hover:translate-x-1 transition"
                         fill="none"
                         viewBox="0 0 24 24"
                         stroke="currentColor">
                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              stroke-width="2"
                              d="M9 5l7 7-7 7"/>
                    </svg>
                </div>

            </a>

        </div>

        {{-- PREMIUM GRID --}}
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">

            @forelse($popularProducts as $product)

                {{-- ═══════════════════════════════ --}}
                {{-- HERO CARD --}}
                {{-- ═══════════════════════════════ --}}
                @if($loop->first)

                <a href="{{ route('products.show', $product->slug) }}"
                   class="lg:col-span-7 relative rounded-[34px] overflow-hidden group border border-white/10 bg-[#0B1120] min-h-[540px]">

                    {{-- IMAGE --}}
                    <div class="absolute inset-0">
                        <img
                            src="{{ Storage::url($product->image) }}"
                            alt="{{ $product->name }}"
                            class="w-full h-full object-cover group-hover:scale-105 transition duration-700"
                        >

                        <div class="absolute inset-0 bg-gradient-to-t from-[#020617] via-[#020617]/70 to-transparent"></div>
                        <div class="absolute inset-0 bg-gradient-to-r from-[#020617]/90 to-transparent"></div>
                    </div>

                    {{-- TOP BADGES --}}
                    <div class="absolute top-6 left-6 z-20 flex flex-wrap gap-3">

                        <div class="px-4 py-2 rounded-full bg-cyan-400/15 border border-cyan-400/30 backdrop-blur-xl">
                            <span class="text-cyan-300 text-xs font-bold tracking-[0.18em] uppercase">
                                #1 Trending
                            </span>
                        </div>

                        <div class="px-4 py-2 rounded-full bg-yellow-400/15 border border-yellow-400/30 backdrop-blur-xl">
                            <span class="text-yellow-300 text-xs font-bold">
                                ⭐ {{ number_format($product->average_rating ?? 5, 1) }}
                            </span>
                        </div>

                    </div>

                    {{-- CONTENT --}}
                    <div class="absolute bottom-0 left-0 right-0 p-8 sm:p-10 z-20">

                        <div class="max-w-2xl">

                            <h3 class="text-4xl font-black text-white leading-tight mb-4">
                                {{ $product->name }}
                            </h3>

                            <p class="text-slate-300 leading-relaxed mb-8 line-clamp-3">
                                {{ $product->description }}
                            </p>

                            <div class="flex flex-wrap items-center gap-5">

                                <div>
                                    <div class="text-xs uppercase tracking-[0.18em] text-slate-500 mb-1">
                                        Harga Mulai
                                    </div>

                                    <div class="text-4xl font-black text-cyan-300">
                                        Rp {{ number_format($product->price,0,',','.') }}
                                    </div>
                                </div>

                                <div class="h-14 w-px bg-white/10"></div>

                                <div class="flex items-center gap-4">

                                    <div class="w-14 h-14 rounded-2xl bg-white/10 backdrop-blur-xl flex items-center justify-center border border-white/10">
                                        <svg class="w-7 h-7 text-white"
                                             fill="none"
                                             viewBox="0 0 24 24"
                                             stroke="currentColor">
                                            <path stroke-linecap="round"
                                                  stroke-linejoin="round"
                                                  stroke-width="1.7"
                                                  d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                        </svg>
                                    </div>

                                    <div>
                                        <div class="text-white font-bold">
                                            Instant Delivery
                                        </div>
                                        <div class="text-sm text-slate-400">
                                            Proses super cepat & otomatis
                                        </div>
                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>

                </a>

                {{-- ═══════════════════════════════ --}}
                {{-- SIDE GRID --}}
                {{-- ═══════════════════════════════ --}}
                <div class="lg:col-span-5 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-1 gap-6">

                @elseif($loop->iteration <= 4)

                    <a href="{{ route('products.show', $product->slug) }}"
                       class="group relative overflow-hidden rounded-[28px] border border-white/10 bg-white/[0.03] backdrop-blur-xl hover:border-cyan-400/30 transition-all duration-500">

                        {{-- Glow --}}
                        <div class="absolute inset-0 opacity-0 group-hover:opacity-100 transition duration-500">
                            <div class="absolute -top-20 right-0 w-52 h-52 bg-cyan-400/20 blur-[90px] rounded-full"></div>
                        </div>

                        <div class="relative flex gap-5 p-5">

                            {{-- IMAGE --}}
                            <div class="relative w-36 h-36 rounded-2xl overflow-hidden shrink-0">

                                <img
                                    src="{{ Storage::url($product->image) }}"
                                    alt="{{ $product->name }}"
                                    class="w-full h-full object-cover group-hover:scale-110 transition duration-700"
                                >

                                <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>

                                <div class="absolute bottom-2 left-2 px-2 py-1 rounded-lg bg-black/60 backdrop-blur-xl">
                                    <span class="text-yellow-300 text-xs font-semibold">
                                        ⭐ {{ number_format($product->average_rating ?? 5, 1) }}
                                    </span>
                                </div>

                            </div>

                            {{-- CONTENT --}}
                            <div class="flex-1 flex flex-col justify-between min-w-0">

                                <div>

                                    <div class="inline-flex items-center gap-2 mb-3">
                                        <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                                        <span class="text-[10px] uppercase tracking-[0.18em] text-emerald-300 font-bold">
                                            Ready Stock
                                        </span>
                                    </div>

                                    <h3 class="text-white font-bold text-lg line-clamp-2 leading-snug">
                                        {{ $product->name }}
                                    </h3>

                                    <p class="text-sm text-slate-400 mt-2 line-clamp-2">
                                        {{ $product->description }}
                                    </p>

                                </div>

                                <div class="flex items-end justify-between mt-5">

                                    <div>
                                        <div class="text-xs text-slate-500 mb-1">
                                            Harga
                                        </div>

                                        <div class="text-cyan-300 text-2xl font-black">
                                            Rp {{ number_format($product->price,0,',','.') }}
                                        </div>
                                    </div>

                                    <div class="w-11 h-11 rounded-xl border border-white/10 bg-white/5 flex items-center justify-center group-hover:bg-cyan-400 group-hover:text-black transition-all duration-300">

                                        <svg class="w-5 h-5"
                                             fill="none"
                                             viewBox="0 0 24 24"
                                             stroke="currentColor">
                                            <path stroke-linecap="round"
                                                  stroke-linejoin="round"
                                                  stroke-width="2"
                                                  d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                                        </svg>

                                    </div>

                                </div>

                            </div>

                        </div>

                    </a>

                @endif

                {{-- CLOSE SIDE GRID --}}
                @if($loop->iteration == 4)
                </div>
                @endif

            @empty

                <div class="col-span-full py-28 text-center rounded-[32px] border border-white/10 bg-white/[0.03] backdrop-blur-xl">

                    <div class="text-7xl mb-6">
                        🎮
                    </div>

                    <h3 class="text-3xl font-black text-white mb-3">
                        Produk Akan Segera Hadir
                    </h3>

                    <p class="text-slate-400 max-w-md mx-auto">
                        Tim kami sedang menyiapkan koleksi produk terbaik untuk pengalaman gaming premium.
                    </p>

                </div>

            @endforelse

        </div>

    </div>

</section>
{{-- ═══════════════════════════════════════════════════════════ --}}
{{-- TOPUP SECTION — HORIZONTAL EXPERIENCE                     --}}
{{-- ═══════════════════════════════════════════════════════════ --}}
@if($topupProducts->isNotEmpty())

<section class="pb-20 overflow-hidden">

  <div class="max-w-7xl mx-auto px-4">

    <div class="flex items-center justify-between mb-8">

      <div>

        <div class="inline-flex items-center gap-2
                    px-3 py-1 rounded-full
                    bg-blue-500/10 border border-blue-500/20
                    text-blue-300 text-xs font-bold mb-4">

          ⚡ INSTANT DELIVERY

        </div>

        <h2 class="text-3xl md:text-4xl font-black text-white">
          Top Up Favorit
        </h2>

        <p class="text-slate-400 mt-2">
          Proses tercepat untuk semua game populer
        </p>

      </div>

      <a href="{{ route('products.by-type', 'topup') }}"
         class="hidden md:flex items-center gap-2 text-cyan-300 hover:text-white transition">

        Semua Top Up →

      </a>

    </div>

    <div class="flex gap-5 overflow-x-auto pb-3 banner-track snap-x snap-mandatory">

      @foreach($topupProducts as $product)

      <div class="flex-none w-[88%] sm:w-[52%] lg:w-[32%] snap-start">

        <div class="group relative rounded-[30px]
                    overflow-hidden
                    border border-slate-800
                    bg-[#081120]
                    hover:border-cyan-500/40
                    transition duration-300">

          {{-- IMAGE --}}
          <div class="relative overflow-hidden">

            <img src="{{ Storage::url($product->image) }}"
                 alt="{{ $product->name }}"
                 class="w-full h-72 object-cover group-hover:scale-110 transition duration-700">

            <div class="absolute inset-0 bg-gradient-to-t from-[#020617] via-[#020617]/30 to-transparent"></div>

            <div class="absolute top-4 left-4">

              <div class="px-3 py-1 rounded-full
                          bg-cyan-400/15 border border-cyan-400/20
                          text-cyan-300 text-xs font-bold">

                INSTANT

              </div>

            </div>

          </div>

          {{-- CONTENT --}}
          <div class="p-6">

            <div class="mb-5">

              <h3 class="text-2xl font-black text-white line-clamp-2 mb-2">
                {{ $product->name }}
              </h3>

              <p class="text-slate-400 text-sm line-clamp-2">
                {{ $product->description }}
              </p>

            </div>

            <div class="flex items-center justify-between">

              <div>

                <div class="text-xs text-slate-500 mb-1">
                  Harga Mulai
                </div>

                <div class="text-cyan-300 text-3xl font-black">
                  Rp {{ number_format($product->price,0,',','.') }}
                </div>

              </div>

              <a href="{{ route('products.show', $product->slug) }}"
                 class="w-14 h-14 rounded-2xl
                        bg-cyan-400 hover:bg-cyan-300
                        text-black
                        flex items-center justify-center
                        font-bold transition">

                →

              </a>

            </div>

          </div>

        </div>

      </div>

      @endforeach

    </div>

  </div>

</section>

@endif


{{-- VALUE PROPOSITION BANNER                                    --}}
{{-- ═══════════════════════════════════════════════════════════ --}}
<section class="pb-14">
  <div class="max-w-7xl mx-auto px-4">
    <div class="reveal-card reveal-delay-2 rounded-2xl p-8 sm:p-12 relative overflow-hidden text-center"
         style="background:linear-gradient(135deg,rgba(37,99,235,0.15) 0%,rgba(249,115,22,0.08) 100%);border:1px solid rgba(37,99,235,0.25);">
      <div class="absolute inset-0 pointer-events-none"
           style="background:radial-gradient(ellipse 60% 50% at 50% 100%,rgba(37,99,235,0.1),transparent);"></div>
      <span class="badge badge-blue mb-4">Kenapa Lapak Gaming?</span>
      <h2 class="font-display font-bold text-2xl sm:text-3xl text-white mb-3">Platform terpercaya untuk<br>semua kebutuhan gaming-mu</h2>
      <p class="text-slate-400 text-sm max-w-lg mx-auto mb-8">Bergabung dengan jutaan gamer Indonesia yang sudah percaya transaksi mereka bersama kami.</p>
      <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 max-w-3xl mx-auto mb-8">
        @php $ratingBadge = number_format($averageRating ?: 0, 1); @endphp
      @foreach([
          ['icon'=>'M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z','label'=>'Escrow Aman','sub'=>'Dana terlindungi'],
          ['icon'=>'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z','label'=>'Proses Cepat','sub'=>'< 5 menit selesai'],
          ['icon'=>'M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z','label'=>'Rating Tinggi','sub'=> $ratingBadge . ' dari 5 bintang'],
          ['icon'=>'M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z','label'=>'Support 24/7','sub'=>'Siap bantu kapanpun'],
        ] as $vp)
        <div class="flex flex-col items-center text-center">
          <div class="w-10 h-10 rounded-xl flex items-center justify-center mb-3"
               style="background:rgba(37,99,235,0.12);border:1px solid rgba(37,99,235,0.25);">
            <svg class="w-5 h-5 text-brand-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="{{ $vp['icon'] }}"/>
            </svg>
          </div>
          <div class="text-sm font-semibold text-white">{{ $vp['label'] }}</div>
          <div class="text-xs text-slate-500 mt-0.5">{{ $vp['sub'] }}</div>
        </div>
        @endforeach
      </div>
    </div>
  </div>
</section>

{{-- ═══════════════════════════════════════════════════════════ --}}
{{-- FAQ — GLASSMORPHISM VERSION                               --}}
{{-- ═══════════════════════════════════════════════════════════ --}}
<section class="pb-24">

  <div class="max-w-7xl mx-auto px-4">

    <div class="grid lg:grid-cols-2 gap-10 items-start">

      {{-- LEFT --}}
      <div class="sticky top-24">

        <div class="inline-flex items-center gap-2
                    px-3 py-1 rounded-full
                    bg-cyan-500/10 border border-cyan-500/20
                    text-cyan-300 text-xs font-bold mb-5">

          SUPPORT CENTER

        </div>

        <h2 class="text-4xl md:text-5xl font-black text-white leading-tight mb-5">
          Pertanyaan
          <br>
          yang sering
          <br>
          ditanyakan
        </h2>

        <p class="text-slate-400 text-lg leading-relaxed max-w-lg">
          Semua informasi penting mengenai transaksi,
          keamanan, top up, hingga sistem marketplace
          tersedia di sini.
        </p>

      </div>

      {{-- RIGHT --}}
      <div class="space-y-4">

        @php
        $faqs = [
          [
            'q'=>'Marketplace Games Terbesar dan Terlengkap',
            'a'=>'Lapak Gaming adalah marketplace destinasi utama bagi para gamers untuk yang mencari kenyamanan dan keandalan dalam bertransaksi digital.'
          ],
          [
            'q'=>'Apa itu Lapak Gaming?',
            'a'=>'Kami adalah platform perantara escrow yang menjamin keamanan transaksi antara penjual dan pembeli produk game di Indonesia.'
          ],
          [
            'q'=>'Top-Up Game Terlengkap',
            'a'=>'Nikmati layanan top up berbagai game populer dengan proses instan dan harga terbaik.'
          ],
          [
            'q'=>'Voucher Digital',
            'a'=>'Kami juga menyediakan voucher digital untuk berbagai kebutuhan hiburan digital.'
          ],
        ];
        @endphp

        @foreach($faqs as $index => $faq)

        <div class="overflow-hidden rounded-[28px]
                    border border-white/10
                    bg-white/[0.03]
                    backdrop-blur-xl">

          <button type="button"
                  data-faq-index="{{ $index }}"
                  class="js-faq-toggle w-full p-6 flex items-center justify-between text-left">

            <span class="text-lg font-bold text-white pr-5">
              {{ $faq['q'] }}
            </span>

            <div id="icon-{{ $index }}"
                 class="w-10 h-10 rounded-2xl
                        bg-white/5
                        border border-white/10
                        flex items-center justify-center
                        text-cyan-300
                        transition duration-300">

              +

            </div>

          </button>

          <div id="faq-{{ $index }}"
               class="max-h-0 overflow-hidden transition-all duration-300">

            <div class="px-6 pb-6 text-slate-400 leading-relaxed">
              {{ $faq['a'] }}
            </div>

          </div>

        </div>

        @endforeach

      </div>

    </div>

  </div>

</section>
@push('scripts')
{{--
  ══════════════════════════════════════════════════════════════
  SPLINE 3D VIEWER + CURSOR TRACKING
  - Loaded as ES module from CDN (no npm install, no Vite changes)
  - spline-viewer web component renders automatically
  - Cursor tracking: lerp-based rAF loop, perspective tilt
  - Loading skeleton hidden on 'load' event or 9s timeout
  ══════════════════════════════════════════════════════════════
--}}
<script type="module" src="https://unpkg.com/@splinetool/viewer/build/spline-viewer.js"></script>

<script>
(function () {
  'use strict';

  // stop total di mobile
  if (window.innerWidth < 1024) return;

  /* ── DOM refs ───────────────────────── */
  const heroSection    = document.getElementById('hero-section');
  const robotWrapper   = document.getElementById('hero-robot-wrapper');
  const robotScene     = document.getElementById('robot-scene-container');
  const splineEl       = document.getElementById('spline-robot');
  const tiltLayer = document.getElementById('robot-tilt-layer');
  const loader         = document.getElementById('robot-loader');
  

  if (!heroSection || !robotWrapper || !robotScene || !splineEl) return;

  /* ── Loader ─────────────────────────── */
  function hideLoader() {

    if (!loader) return;

    loader.classList.add('loader-hidden');

    setTimeout(() => {

      loader.style.display = 'none';
      loader.remove();

    }, 700);
  }

  // hide otomatis setelah spline ready
  if (splineEl) {

    splineEl.addEventListener('load', () => {
      hideLoader();
    });

    // fallback max 5 detik
    setTimeout(() => {
      hideLoader();
    }, 5000);

  }

  // fallback terakhir kalau gagal load
  setTimeout(() => {
    hideLoader();
  }, 12000);

  /* ── Helpers ────────────────────────── */
  function lerp(a, b, t) {
    return a + (b - a) * t;
  }

  function clamp(v, lo, hi) {
    return Math.max(lo, Math.min(hi, v));
  }

  /* ── Tilt State ─────────────────────── */
  let targetX = 0;
  let targetY = 0;

  let currentX = 0;
  let currentY = 0;

  let isVisible = true;
  let pendingFrame = false;

  /* ── Apply Transform ────────────────── */
   let splineApp = null;
let robotHead = null;

splineEl.addEventListener('load', async (e) => {

  hideLoader();

  splineApp = e.target.application;

  // GANTI "Head" dengan nama object kepala di spline kamu
  robotHead = splineApp.findObjectByName('Head');

});
  /* ── Animation Step ─────────────────── */
  function updateRobot() {
    pendingFrame = false;

    currentX = lerp(currentX, targetX, 0.18);
    currentY = lerp(currentY, targetY, 0.18);

    applyRobotTransform();

    if (isVisible && (Math.abs(currentX - targetX) > 0.001 || Math.abs(currentY - targetY) > 0.001)) {
      pendingFrame = true;
      requestAnimationFrame(updateRobot);
    }
  }

  function scheduleUpdate() {
    if (!pendingFrame && isVisible) {
      pendingFrame = true;
      requestAnimationFrame(updateRobot);
    }
  }

  /* ── Pause ketika tab tidak aktif ───── */
  document.addEventListener('visibilitychange', () => {

    isVisible = !document.hidden;

    if (!isVisible) {
      pendingFrame = false;
    } else {
      scheduleUpdate();
    }

  });

  /* ── Pause ketika robot tidak terlihat ─ */
  const observer = new IntersectionObserver((entries) => {

    entries.forEach(entry => {

      if (entry.isIntersecting) {

        isVisible = true;
        scheduleUpdate();

      } else {

        isVisible = false;
        pendingFrame = false;

      }

    });

  }, {
    threshold: 0.15
  });

  observer.observe(robotWrapper);

  /* ── Mouse Move ─────────────────────── */
  heroSection.addEventListener('mousemove', (e) => {

    const hr = heroSection.getBoundingClientRect();

    targetX = clamp(
      (e.clientX - (hr.left + hr.width / 2)) / (hr.width / 2),
      -1,
      1
    );

    targetY = clamp(
      (e.clientY - (hr.top + hr.height / 2)) / (hr.height / 2),
      -1,
      1
    );

    scheduleUpdate();

  });

  heroSection.addEventListener('mouseleave', () => {

    targetX = 0;
    targetY = 0;

    scheduleUpdate();

  });

 function applyRobotTransform() {

  if (!robotHead) return;

  const rotY = currentX * 0.35;
  const rotX = -currentY * 0.20;

  robotHead.rotation.y = rotY;
  robotHead.rotation.x = rotX;

}

  /* ── Start Idle Transform ───────────── */
  applyRobotTransform();

  /* ── FAQ ────────────────────────────── */
  window.toggleFaq = function (index) {

    const content = document.getElementById('faq-' + index);
    const icon    = document.getElementById('icon-' + index);

    const isOpen =
      content.style.maxHeight !== '0px' &&
      content.style.maxHeight !== '';

    document.querySelectorAll('[id^="faq-"]').forEach(el => {
      el.style.maxHeight = '0px';
    });

    document.querySelectorAll('[id^="icon-"]').forEach(el => {
      el.style.transform = 'rotate(0deg)';
    });

    if (!isOpen) {

      content.style.maxHeight =
        content.scrollHeight + 'px';

      icon.style.transform = 'rotate(180deg)';

    }

  };

  document.addEventListener('DOMContentLoaded', () => {

  document.querySelectorAll('.js-faq-toggle').forEach((button) => {
    button.addEventListener('click', () => {
      const index = Number(button.getAttribute('data-faq-index'));
      if (!Number.isNaN(index)) {
        window.toggleFaq(index);
      }
    });
  });

  const reveals = document.querySelectorAll('.reveal-card');

  const observer = new IntersectionObserver((entries) => {

    entries.forEach(entry => {

      if (entry.isIntersecting) {

        entry.target.classList.add('show');

      }

    });

  }, {
    threshold: 0.12
  });

  reveals.forEach(el => observer.observe(el));

  const statCounters = document.querySelectorAll('.js-stat-counter');

  function animateCounter(element) {
    const target = Number(element.getAttribute('data-target') || 0);
    const duration = 1400;
    const start = performance.now();

    function tick(now) {
      const progress = Math.min((now - start) / duration, 1);
      const eased = 1 - Math.pow(1 - progress, 3);
      const value = Math.round(target * eased);
      element.textContent = value.toLocaleString('id-ID');

      if (progress < 1) {
        requestAnimationFrame(tick);
      } else {
        element.textContent = target.toLocaleString('id-ID');
      }
    }

    requestAnimationFrame(tick);
  }

  if (statCounters.length) {
    const statObserver = new IntersectionObserver((entries, observerInstance) => {
      entries.forEach((entry) => {
        if (!entry.isIntersecting) return;

        animateCounter(entry.target);
        observerInstance.unobserve(entry.target);
      });
    }, {
      threshold: 0.35,
    });

    statCounters.forEach((counter) => statObserver.observe(counter));
  }

  const initBannerAutoplay = (trackId, intervalMs = 3000) => {
    const track = document.getElementById(trackId);
    if (!track || track.children.length <= 1) return;

    const slides = Array.from(track.children);
    let currentIndex = 0;
    let autoplayTimer = null;

    const scrollToSlide = (index) => {
      const slide = slides[index];
      if (!slide) return;

      track.scrollTo({
        left: slide.offsetLeft - track.offsetLeft,
        behavior: 'smooth',
      });
    };

    const startAutoplay = () => {
      if (autoplayTimer) return;

      autoplayTimer = window.setInterval(() => {
        currentIndex = (currentIndex + 1) % slides.length;
        scrollToSlide(currentIndex);
      }, intervalMs);
    };

    const stopAutoplay = () => {
      if (!autoplayTimer) return;
      window.clearInterval(autoplayTimer);
      autoplayTimer = null;
    };

    track.addEventListener('mouseenter', stopAutoplay);
    track.addEventListener('mouseleave', startAutoplay);
    track.addEventListener('touchstart', stopAutoplay, { passive: true });
    
    // Touch swipe support
    let touchStartX = 0;
    let touchEndX = 0;
    track.addEventListener('touchstart', (e) => {
      touchStartX = e.changedTouches[0].clientX;
      stopAutoplay();
    }, { passive: true });
    track.addEventListener('touchend', (e) => {
      touchEndX = e.changedTouches[0].clientX;
      const dx = touchEndX - touchStartX;
      if (Math.abs(dx) > 50) {
        if (dx < 0) { // swipe left -> next
          currentIndex = (currentIndex + 1) % slides.length;
        } else { // swipe right -> prev
          currentIndex = (currentIndex - 1 + slides.length) % slides.length;
        }
        scrollToSlide(currentIndex);
      }
      startAutoplay();
    }, { passive: true });

    // Prev/Next buttons (if present)
    const parent = track.parentElement;
    const prevBtn = parent?.querySelector('#banner-prev');
    const nextBtn = parent?.querySelector('#banner-next');
    if (prevBtn) prevBtn.addEventListener('click', () => {
      stopAutoplay();
      currentIndex = (currentIndex - 1 + slides.length) % slides.length;
      scrollToSlide(currentIndex);
      startAutoplay();
    });
    if (nextBtn) nextBtn.addEventListener('click', () => {
      stopAutoplay();
      currentIndex = (currentIndex + 1) % slides.length;
      scrollToSlide(currentIndex);
      startAutoplay();
    });

    startAutoplay();
  };

  initBannerAutoplay('banner-track', 3000);
  initBannerAutoplay('featured-banner-track', 3000);

  document.querySelectorAll('.category-scroll-btn').forEach((button) => {
    button.addEventListener('click', () => {
      const targetId = button.getAttribute('data-target');
      const direction = button.getAttribute('data-dir');
      const track = targetId ? document.getElementById(targetId) : null;
      if (!track) return;

      const amount = Math.max(320, track.clientWidth * 0.7);
      track.scrollBy({
        left: direction === 'left' ? -amount : amount,
        behavior: 'smooth',
      });
    });
  });

});

})();


</script>
@endpush

@endsection

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

  .category-track::-webkit-scrollbar {
    display: none;
  }

  .category-track {
    scrollbar-width: none;
    -ms-overflow-style: none;
  }

  .premium-topup-track {
    scrollbar-width: none;
    -ms-overflow-style: none;
  }

  .premium-topup-track::-webkit-scrollbar {
    display: none;
  }

  .banner-slide {
    scroll-snap-align: start;
  }

  /* ═══════════════════════════════════════
   DUNIAGAMES STYLE BUTTON
  ═══════════════════════════════════════ */
  .dg-btn {
    background: linear-gradient(135deg,#2563eb 0%,#1d4ed8 40%,#0f172a 100%);
    border: 1px solid rgba(96,165,250,0.35);
    box-shadow: 0 10px 30px rgba(37,99,235,0.25), inset 0 1px 0 rgba(255,255,255,0.08);
    letter-spacing: .02em;
    transform-style: preserve-3d;
  }

  .dg-btn:hover {
    transform: translateY(-3px) scale(1.02);
    box-shadow: 0 18px 40px rgba(37,99,235,0.38), 0 0 30px rgba(96,165,250,0.22);
  }

  .dg-btn:active {
    transform: scale(.98);
  }

  .dg-btn-glow {
    position: absolute;
    inset: 0;
    background: linear-gradient(120deg, transparent 20%, rgba(255,255,255,0.20) 50%, transparent 80%);
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

  #robot-tilt-layer {
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

  .badge-float-a { animation: badgeFloat 4.0s ease-in-out infinite; }
  .badge-float-b { animation: badgeFloat 5.2s ease-in-out infinite 1.5s; }
  .badge-float-c { animation: badgeFloat 4.7s ease-in-out infinite 0.8s; }

  @keyframes badgeFloat {
    0%, 100% { transform: translateY(0px); }
    50%       { transform: translateY(-7px); }
  }

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
    transition: opacity .7s ease, filter .7s ease;
  }

  #robot-loader.loader-hidden {
    opacity: 0;
    filter: blur(10px);
    pointer-events: none;
  }

  #robot-tilt-layer {
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

  #robot-tilt-layer {
     width:100%;
     height:100%;
     position:absolute;
     inset:0;
     display:flex;
     align-items:center;
     justify-content:center;
     pointer-events:none;
  }

  spline-viewer::part(watermark) { display: none !important; }
  spline-viewer::part(badge) { display: none !important; }
  spline-viewer iframe { pointer-events: auto; }

  #robot-only {
     width:100%;
     height:100%;
     transform-style:preserve-3d;
     will-change:transform;
  }

  /* ── Clean Reveal Animation ───────────────────────────── */
  .reveal-card {
    opacity: 0;
    transform: translateY(30px);
    transition: opacity 0.7s ease-out, transform 0.7s ease-out;
    will-change: opacity, transform;
  }

  .reveal-card.show {
    opacity: 1;
    transform: translateY(0);
  }

  .reveal-delay-1 { transition-delay: .05s; }
  .reveal-delay-2 { transition-delay: .10s; }
  .reveal-delay-3 { transition-delay: .15s; }
  .reveal-delay-4 { transition-delay: .20s; }
  .reveal-delay-5 { transition-delay: .25s; }
  .reveal-delay-6 { transition-delay: .30s; }

  .category-premium-track {
    scrollbar-width:none;
    -webkit-overflow-scrolling:touch;
  }
  .category-premium-track::-webkit-scrollbar { display:none; }
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

        <div class="flex flex-wrap gap-6 mt-10 justify-center lg:justify-start">
          <div class="text-center lg:text-left">
            <div class="font-display font-bold text-2xl text-white js-stat-counter" data-target="{{ $activeUsers }}">{{ number_format($activeUsers, 0, ',', '.') }}</div>
            <div class="text-xs text-slate-500 mt-0.5">Pengguna Aktif</div>
          </div>
          <div class="text-center lg:text-left">
            <div class="font-display font-bold text-2xl text-white js-stat-counter" data-target="{{ $availableProducts }}">{{ number_format($availableProducts, 0, ',', '.') }}</div>
            <div class="text-xs text-slate-500 mt-0.5">Produk Tersedia</div>
          </div>
          <div class="text-center lg:text-left">
            <div class="font-display font-bold text-2xl text-white js-stat-counter" data-target="{{ $verifiedSellers }}">{{ number_format($verifiedSellers, 0, ',', '.') }}</div>
            <div class="text-xs text-slate-500 mt-0.5">Seller Verified</div>
          </div>
          <div class="text-center lg:text-left">
            <div class="font-display font-bold text-2xl text-white js-stat-counter" data-target="{{ $transactionCount }}">{{ number_format($transactionCount, 0, ',', '.') }}</div>
            <div class="text-xs text-slate-500 mt-0.5">Transaksi Sukses</div>
          </div>
        </div>
      </div>

      {{-- Right: 3D Robot AI Assistant --}}
      <div class="hidden lg:flex flex-shrink-0 items-center justify-center relative" id="hero-robot-wrapper" style="width:500px;height:540px;">
        <div class="ring-cw absolute rounded-full pointer-events-none" style="width:455px;height:455px;z-index:2;border:1px solid rgba(37,99,235,0.18);border-top-color:rgba(96,165,250,0.60);border-right-color:rgba(37,99,235,0.38);"></div>
        <div class="ring-ccw absolute rounded-full pointer-events-none" style="width:398px;height:398px;z-index:2;border:1px dashed rgba(96,165,250,0.10);"></div>

        <div class="robot-glow-blue absolute rounded-full pointer-events-none" style="width:290px;height:290px;z-index:1;background:radial-gradient(circle,rgba(37,99,235,0.38) 0%,transparent 70%);filter:blur(24px);"></div>
        <div class="robot-glow-orange absolute pointer-events-none" style="width:200px;height:200px;top:58%;left:58%;z-index:1;border-radius:50%;background:radial-gradient(circle,rgba(249,115,22,0.22) 0%,transparent 70%);filter:blur(44px);transform:translate(-50%,-50%);"></div>

        <div id="robot-loader" class="absolute inset-0 flex items-center justify-center" style="z-index:30;">
          <div class="flex flex-col items-center gap-4">
            <div class="relative w-20 h-20">
              <div class="absolute inset-0 rounded-full border-2 border-brand-500/20 border-t-brand-400 animate-spin"></div>
              <div class="absolute inset-2 rounded-full border border-brand-500/10 border-b-accent-400/50" style="animation:spin 2s linear infinite reverse;"></div>
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

        <div id="robot-scene-container"></div>
        <div id="robot-tilt-layer">
          <div id="robot-only">
            <spline-viewer id="spline-robot" loading="eager" events-target="global" loading-anim-type="spinner-big-dark" url="https://prod.spline.design/vNP16bdGzzl-ASAu/scene.splinecode"></spline-viewer>
          </div>
        </div>
        
        <div id="spline-logo-cover"></div>
        <div class="scan-line"></div>
        <div class="corner-bracket tl"></div>
        <div class="corner-bracket tr"></div>
        <div class="corner-bracket bl"></div>
        <div class="corner-bracket br"></div>

        <div class="badge-float-a absolute z-30" style="top:28px;right:-8px;">
          <div class="flex items-center gap-2 rounded-2xl px-3.5 py-2.5 backdrop-blur-md" style="background:rgba(9,14,26,0.88);border:1px solid rgba(16,185,129,0.45);box-shadow:0 4px 24px rgba(0,0,0,0.55),0 0 14px rgba(16,185,129,0.18);">
            <span class="relative flex h-2 w-2">
              <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
              <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-400"></span>
            </span>
            <span class="text-emerald-300 text-xs font-semibold font-display tracking-wide">AI Online</span>
          </div>
        </div>

        <div class="badge-float-b absolute z-30" style="bottom:96px;left:-20px;">
          <div class="rounded-2xl px-4 py-2.5 backdrop-blur-md" style="background:rgba(9,14,26,0.88);border:1px solid rgba(37,99,235,0.45);box-shadow:0 4px 24px rgba(0,0,0,0.55),0 0 14px rgba(37,99,235,0.18);">
            <div class="text-[10px] tracking-[0.18em] uppercase font-display mb-0.5" style="color:rgba(96,165,250,0.6);">Transaksi Hari Ini</div>
            <div class="font-display font-bold text-white text-lg leading-none">{{ number_format($todayTransactions, 0, ',', '.') }}</div>
          </div>
        </div>

        <div class="badge-float-c absolute z-30" style="bottom:12px;right:7px;">
          <div class="flex items-center gap-2 rounded-2xl px-3.5 py-2 backdrop-blur-md" style="background:rgba(9,14,26,0.88);border:1px solid rgba(249,115,22,0.38);box-shadow:0 4px 24px rgba(0,0,0,0.55),0 0 12px rgba(249,115,22,0.14);">
            <span style="font-size:14px;">⭐</span>
            <span class="font-display font-bold text-white text-sm">{{ number_format($averageRating ?: 0, 1) }}</span>
            <span class="text-slate-500 text-xs">Rating</span>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

{{-- ═══════════════════════════════════════════════════════════ --}}
{{-- HERO BANNERS                                               --}}
{{-- ═══════════════════════════════════════════════════════════ --}}
@if(isset($heroBanners) && $heroBanners->count())
<section class="relative py-5 sm:py-7 reveal-card">
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
{{-- TOP 3 CATEGORIES FEATURED SECTION                          --}}
{{-- ═══════════════════════════════════════════════════════════ --}}
@if(isset($topThreeCategories) && $topThreeCategories->count() > 0)
<section class="py-16 overflow-hidden">
  <div class="max-w-7xl mx-auto px-4">
    <div class="text-center mb-12">
      <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full border border-blue-400/20 bg-blue-400/5 mb-4">
        <span class="w-1.5 h-1.5 rounded-full bg-blue-400 animate-pulse"></span>
        <span class="text-blue-300 text-xs font-bold tracking-wide uppercase">🔥 Kategori Populer</span>
      </div>
      <h2 class="text-4xl md:text-5xl font-black text-white mb-3">Kategori Pilihan Terbaik</h2>
      <p class="text-slate-400 max-w-2xl mx-auto">Koleksi produk gaming paling dicari dengan harga terbaik dan rating tertinggi</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
      @foreach($topThreeCategories as $index => $group)
        @php
          $category = $group['category'];
          $products = $group['products'];
          $colors = [
            ['border' => 'border-cyan-500/40', 'bg-start' => 'from-cyan-500/20', 'bg-end' => 'to-blue-500/10', 'badge-bg' => 'bg-cyan-500/20', 'badge-text' => 'text-cyan-300', 'badge-border' => 'border-cyan-500/40', 'icon-bg' => 'from-cyan-400/15', 'text-accent' => 'text-cyan-300'],
            ['border' => 'border-purple-500/40', 'bg-start' => 'from-purple-500/20', 'bg-end' => 'to-pink-500/10', 'badge-bg' => 'bg-purple-500/20', 'badge-text' => 'text-purple-300', 'badge-border' => 'border-purple-500/40', 'icon-bg' => 'from-purple-400/15', 'text-accent' => 'text-purple-300'],
            ['border' => 'border-orange-500/40', 'bg-start' => 'from-orange-500/20', 'bg-end' => 'to-red-500/10', 'badge-bg' => 'bg-orange-500/20', 'badge-text' => 'text-orange-300', 'badge-border' => 'border-orange-500/40', 'icon-bg' => 'from-orange-400/15', 'text-accent' => 'text-orange-300'],
          ];
          $color = $colors[$index] ?? $colors[0];
        @endphp
        
        <div class="reveal-card reveal-delay-{{ $index + 1 }} group relative rounded-[32px] border {{ $color['border'] }} bg-gradient-to-br {{ $color['bg-start'] }} {{ $color['bg-end'] }} p-7 overflow-hidden hover:-translate-y-2 transition-all duration-500">
          <div class="absolute inset-0 opacity-0 group-hover:opacity-100 transition duration-500 pointer-events-none">
            <div class="absolute -top-20 -right-20 w-64 h-64 rounded-full {{ $color['badge-bg'] }} blur-3xl"></div>
            <div class="absolute -bottom-20 -left-20 w-64 h-64 rounded-full {{ $color['badge-bg'] }} blur-3xl opacity-50"></div>
          </div>
          
          <div class="relative z-10">
            <div class="flex items-start justify-between mb-7">
              <div>
                <div class="w-16 h-16 rounded-2xl bg-gradient-to-br {{ $color['icon-bg'] }} to-transparent border {{ $color['badge-border'] }} flex items-center justify-center text-3xl mb-4 group-hover:scale-110 transition duration-500">
                  @if($category->image)
                    <img src="{{ $category->image_url }}" alt="{{ $category->name }}" class="w-12 h-12 object-cover rounded-lg">
                  @else
                    {{ $category->icon ?? '🎮' }}
                  @endif
                </div>
                <h3 class="text-2xl font-bold text-white mb-2">{{ $category->name }}</h3>
                <p class="text-slate-400 text-sm">{{ $products->count() }} produk tersedia</p>
              </div>
              <a href="{{ route('categories.show', $category->slug) }}" class="flex items-center justify-center w-10 h-10 rounded-full {{ $color['badge-bg'] }} border {{ $color['badge-border'] }} {{ $color['text-accent'] }} hover:scale-110 transition duration-300">→</a>
            </div>
            
            <div class="space-y-3">
              @foreach($products->take(3) as $product)
              <a href="{{ route('products.show', $product->slug) }}" class="flex items-start gap-4 p-4 rounded-xl bg-white/[0.03] hover:bg-white/[0.08] backdrop-blur-sm border border-white/5 hover:border-white/10 transition duration-300 group/item">
                <div class="flex-shrink-0 w-16 h-16 rounded-lg overflow-hidden bg-slate-900 border border-white/5">
                  <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="w-full h-full object-cover group-hover/item:scale-110 transition duration-500">
                </div>
                <div class="flex-1 min-w-0">
                  <h4 class="text-sm font-bold text-white truncate group-hover/item:{{ $color['text-accent'] }} transition">{{ $product->name }}</h4>
                  <div class="flex items-center gap-2 mt-1 mb-2">
                    <span class="text-xs text-yellow-300">⭐ {{ number_format($product->rating_average ?? 0, 1) }}</span>
                    <span class="text-xs text-slate-500">({{ $product->reviews_count ?? 0 }} ulasan)</span>
                  </div>
                  <div class="text-sm font-bold {{ $color['text-accent'] }}">Rp {{ number_format($product->price, 0, ',', '.') }}</div>
                </div>
              </a>
              @endforeach
            </div>
            <a href="{{ route('categories.show', $category->slug) }}" class="mt-6 block w-full text-center px-4 py-3 rounded-xl border {{ $color['badge-border'] }} {{ $color['badge-bg'] }} {{ $color['text-accent'] }} font-bold text-sm hover:scale-105 transition duration-300">Lihat Semua Produk →</a>
          </div>
        </div>
      @endforeach
    </div>
  </div>
</section>
@endif

{{-- ═══════════════════════════════════════════════════════════ --}}
{{-- CATEGORY SHORTCUTS — PREMIUM GRID                          --}}
{{-- ═══════════════════════════════════════════════════════════ --}}
<section class="pb-16">
  <div class="max-w-7xl mx-auto px-4">
    <div class="flex items-center justify-between mb-8">
      <div>
        <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full border border-cyan-400/15 bg-cyan-400/5 text-cyan-300 text-xs font-bold tracking-wide mb-4">🎮 EXPLORE GAME CATEGORY</div>
        <h2 class="text-3xl md:text-4xl font-black text-white">Kategori Game</h2>
        <p class="text-slate-400 mt-2 text-sm">Temukan kebutuhan gaming favoritmu lebih cepat</p>
      </div>
      <a href="{{ route('products.search') }}" class="hidden md:flex items-center gap-2 text-cyan-300 hover:text-white transition font-semibold">Lihat Semua →</a>
    </div>

    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 xl:grid-cols-6 gap-5">
      @foreach($categories->take(3) as $cat)
      <a href="{{ route('categories.show', $cat->slug) }}" class="cat-btn reveal-card group relative overflow-hidden rounded-[28px] border border-slate-800 bg-gradient-to-b from-[#111827] to-[#060b16] p-5 hover:border-cyan-400/40 transition duration-500 hover:-translate-y-1">
        <div class="absolute inset-0 opacity-0 group-hover:opacity-100 transition duration-500">
          <div class="absolute -top-10 right-0 w-32 h-32 rounded-full bg-cyan-400/10 blur-3xl"></div>
          <div class="absolute bottom-0 left-0 w-24 h-24 rounded-full bg-blue-500/10 blur-2xl"></div>
        </div>

        <div class="relative z-10">
          <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-cyan-400/15 to-blue-500/10 border border-cyan-400/10 flex items-center justify-center overflow-hidden mb-5 group-hover:scale-110 group-hover:rotate-3 transition duration-500">
            @if($cat->image)
              <img src="{{ $cat->image_url }}" alt="{{ $cat->name }}" class="w-12 h-12 object-cover rounded-xl">
            @else
              <span class="text-3xl">{{ $cat->icon ?? '🎮' }}</span>
            @endif
          </div>
          <h3 class="text-white font-bold text-lg leading-tight mb-2 group-hover:text-cyan-300 transition">{{ \Illuminate\Support\Str::limit($cat->name, 18) }}</h3>
          <div class="flex items-center justify-between">
            <span class="text-slate-500 text-xs">Explore →</span>
            <div class="w-8 h-8 rounded-full bg-cyan-400/10 border border-cyan-400/10 flex items-center justify-center text-cyan-300 group-hover:bg-cyan-400 group-hover:text-black transition">→</div>
          </div>
        </div>
      </a>
      @endforeach
    </div>
  </div>
</section>

{{-- ═══════════════════════════════════════════════════════════ --}}
{{-- ALL CATEGORY PRODUCTS — ULTRA PREMIUM AUTO SLIDER          --}}
{{-- ═══════════════════════════════════════════════════════════ --}}
@if(isset($categoryProducts) && $categoryProducts->count())
<section class="pb-28 overflow-hidden">
  <div class="max-w-7xl mx-auto px-4 space-y-14">
    <div class="flex flex-col lg:flex-row lg:items-end lg:justify-between gap-6">
      <div>
        <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-white/[0.04] border border-cyan-400/10 backdrop-blur-xl text-cyan-300 text-xs font-black tracking-[0.15em] uppercase mb-5">✦ Curated Gaming Collection</div>
        <h2 class="text-4xl md:text-6xl font-black text-white leading-none">Semua Kategori</h2>
        <p class="text-slate-400 mt-4 text-base md:text-lg max-w-2xl">Pengalaman marketplace gaming premium dengan desain modern, auto-slider smooth, dan visual immersive.</p>
      </div>
      <a href="{{ route('products.search') }}" class="hidden md:flex items-center gap-3 px-6 py-4 rounded-2xl border border-white/10 bg-white/[0.03] backdrop-blur-xl text-white hover:bg-cyan-400 hover:text-black transition duration-300 font-bold">Explore All Products →</a>
    </div>

    @foreach($categoryProducts as $group)
    @php
      $category = $group['category'];
      $products = $group['products'];
    @endphp

    <section class="relative overflow-hidden rounded-[38px] border border-white/10 bg-gradient-to-br from-[#07101d] via-[#0b1220] to-[#04070f] backdrop-blur-2xl">
      <div class="absolute inset-0 overflow-hidden pointer-events-none">
        <div class="absolute -top-20 -right-10 w-[340px] h-[340px] bg-cyan-400/10 blur-3xl rounded-full"></div>
        <div class="absolute bottom-0 left-0 w-[240px] h-[240px] bg-blue-500/10 blur-3xl rounded-full"></div>
      </div>

      <div class="relative z-10 px-7 md:px-10 pt-8 md:pt-10 pb-6">
        <div class="flex items-center justify-between gap-5">
          <div class="flex items-center gap-5">
            <div class="w-16 h-16 rounded-3xl bg-gradient-to-br from-cyan-400/20 to-blue-500/10 border border-cyan-400/15 flex items-center justify-center overflow-hidden shadow-[0_0_30px_rgba(34,211,238,0.12)]">
              @if($category->image)
                <img src="{{ $category->image_url }}" class="w-12 h-12 object-cover rounded-2xl">
              @else
                <span class="text-3xl">{{ $category->icon ?? '🎮' }}</span>
              @endif
            </div>
            <div>
              <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-cyan-400/10 border border-cyan-400/10 text-cyan-300 text-[10px] font-black tracking-[0.15em] uppercase mb-3">Premium Category</div>
              <h3 class="text-3xl md:text-4xl font-black text-white">{{ $category->name }}</h3>
              <p class="text-slate-400 text-sm mt-2">{{ $products->count() }} produk tersedia</p>
            </div>
          </div>
          <a href="{{ route('categories.show', $category->slug) }}" class="hidden md:flex items-center gap-2 px-5 py-3 rounded-2xl border border-white/10 bg-white/[0.03] text-cyan-300 hover:bg-cyan-400 hover:text-black transition duration-300 font-bold">Explore →</a>
        </div>
      </div>

      <div class="relative z-10 pb-10">
        <div class="category-premium-track flex gap-5 overflow-x-auto px-7 md:px-10 scroll-smooth snap-x snap-mandatory" data-category-slider>
          @foreach($products as $product)
          <div class="flex-none w-[72%] sm:w-[40%] md:w-[28%] xl:w-[20%] snap-start reveal-card">
            <a href="{{ route('products.show', $product->slug) }}" class="group relative block h-full rounded-[30px] overflow-hidden border border-white/5 bg-white/[0.03] backdrop-blur-xl hover:border-cyan-400/30 transition duration-500">
              <div class="absolute inset-0 opacity-0 group-hover:opacity-100 transition duration-500">
                <div class="absolute inset-0 bg-gradient-to-b from-cyan-400/10 via-transparent to-transparent"></div>
              </div>

              <div class="relative h-44 overflow-hidden">
                  <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="w-full h-full object-cover group-hover:scale-110 transition duration-700">
                  <div class="absolute inset-0 bg-gradient-to-t from-[#020617] via-[#020617]/20 to-transparent"></div>
                  <div class="absolute top-3 left-3 z-10">
                      <div class="px-3 py-1 rounded-full bg-cyan-400 text-black text-[10px] font-black shadow-lg">HOT</div>
                  </div>
                  <div class="absolute top-14 left-3 z-10">
                      <div class="px-2 py-1 rounded-full bg-black/70 backdrop-blur-md border border-white/10 text-[10px] text-yellow-300 font-bold">⭐ 5.0</div>
                  </div>
              </div>
              <div class="p-5">
                <div class="mb-5">
                  <div class="text-slate-500 text-[11px] uppercase tracking-[0.12em] mb-2">Digital Product</div>
                  <h4 class="text-white text-lg font-black line-clamp-2 leading-snug group-hover:text-cyan-300 transition">{{ $product->name }}</h4>
                </div>
                <div class="flex items-end justify-between gap-3">
                  <div>
                    <div class="text-slate-500 text-xs mb-1">Harga Mulai</div>
                    <div class="text-cyan-300 text-2xl font-black leading-none">Rp {{ number_format($product->price,0,',','.') }}</div>
                  </div>
                  <span class="w-12 h-12 rounded-2xl bg-white/[0.05] border border-white/10 flex items-center justify-center text-white hover:bg-cyan-400 hover:text-black hover:scale-110 transition duration-300">→</span>
                </div>
              </div>
            </a>
          </div>
          @endforeach
        </div>

        <div class="flex justify-center gap-2 mt-8">
          @foreach($products as $product)
            @if($loop->index < 6)
            <button class="category-dot w-10 h-[4px] rounded-full bg-white/10 hover:bg-cyan-400 transition" data-category-dot></button>
            @endif
          @endforeach
        </div>
      </div>
    </section>
    @endforeach
  </div>
</section>
@endif

{{-- ═══════════════════════════════════════════════════════════ --}}
{{-- PREMIUM QUICK ACCESS                                       --}}
{{-- ═══════════════════════════════════════════════════════════ --}}
<section class="relative pb-14 reveal-card">
    <div class="absolute inset-0 pointer-events-none">
        <div class="absolute top-0 left-0 w-[400px] h-[400px] bg-cyan-500/10 blur-[120px] rounded-full"></div>
        <div class="absolute bottom-0 right-0 w-[400px] h-[400px] bg-purple-500/10 blur-[120px] rounded-full"></div>
    </div>

    <div class="max-w-7xl mx-auto px-4 relative z-10">
        <div class="text-center mb-12">
            <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full border border-cyan-400/20 bg-cyan-400/5 backdrop-blur-xl mb-5">
                <span class="w-2 h-2 rounded-full bg-cyan-400 animate-pulse"></span>
                <span class="text-cyan-300 text-xs uppercase tracking-[0.25em] font-bold">Gaming Services</span>
            </div>
            <h2 class="font-display text-4xl sm:text-5xl font-black text-white leading-tight">Akses Cepat <span class="bg-gradient-to-r from-cyan-300 via-blue-400 to-purple-400 bg-clip-text text-transparent">Kebutuhan Gaming</span></h2>
            <p class="text-slate-400 mt-5 max-w-2xl mx-auto leading-relaxed">Pilih layanan favoritmu dengan tampilan premium, transaksi cepat, dan pengalaman gaming terbaik.</p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-6">
            <a href="{{ route('products.by-type', 'topup') }}" class="group relative overflow-hidden rounded-[30px] border border-cyan-400/20 bg-white/[0.03] backdrop-blur-xl p-6 hover:-translate-y-2 transition-all duration-500">
                <div class="absolute inset-0 opacity-0 group-hover:opacity-100 transition duration-500">
                    <div class="absolute inset-0 bg-gradient-to-br from-cyan-500/20 via-blue-500/10 to-transparent"></div>
                    <div class="absolute -top-10 -right-10 w-40 h-40 bg-cyan-400/20 blur-[80px] rounded-full"></div>
                </div>
                <div class="absolute inset-[1px] rounded-[29px] border border-white/5 pointer-events-none"></div>
                <div class="relative z-10 flex flex-col h-full">
                    <div class="flex items-start justify-between mb-8">
                        <div class="relative">
                            <div class="absolute inset-0 blur-2xl opacity-40 bg-cyan-400/20"></div>
                            <div class="relative w-20 h-20 rounded-3xl bg-white/[0.04] border border-white/10 backdrop-blur-xl flex items-center justify-center text-4xl group-hover:scale-110 transition duration-500">⚡</div>
                        </div>
                        <div class="px-3 py-1.5 rounded-full border text-[10px] uppercase tracking-[0.18em] font-bold backdrop-blur-xl text-cyan-300 bg-cyan-400/10 border-cyan-400/20">Instant</div>
                    </div>
                    <div class="flex-1">
                        <h3 class="text-2xl font-black text-white leading-tight mb-3">Top Up Game</h3>
                        <p class="text-slate-400 leading-relaxed text-sm">Diamond & UC instan otomatis</p>
                    </div>
                    <div class="flex items-center justify-between mt-8 pt-6 border-t border-white/5">
                        <div class="flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                            <span class="text-xs text-slate-400">Online 24/7</span>
                        </div>
                        <div class="w-12 h-12 rounded-2xl border border-white/10 bg-white/[0.04] flex items-center justify-center text-white group-hover:bg-white group-hover:text-black group-hover:scale-110 transition-all duration-300">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.3" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                        </div>
                    </div>
                </div>
            </a>

            <a href="{{ route('products.by-type', 'joki') }}" class="group relative overflow-hidden rounded-[30px] border border-orange-400/20 bg-white/[0.03] backdrop-blur-xl p-6 hover:-translate-y-2 transition-all duration-500">
                <div class="absolute inset-0 opacity-0 group-hover:opacity-100 transition duration-500">
                    <div class="absolute inset-0 bg-gradient-to-br from-orange-500/20 via-yellow-500/10 to-transparent"></div>
                    <div class="absolute -top-10 -right-10 w-40 h-40 bg-orange-400/20 blur-[80px] rounded-full"></div>
                </div>
                <div class="absolute inset-[1px] rounded-[29px] border border-white/5 pointer-events-none"></div>
                <div class="relative z-10 flex flex-col h-full">
                    <div class="flex items-start justify-between mb-8">
                        <div class="relative">
                            <div class="absolute inset-0 blur-2xl opacity-40 bg-orange-400/20"></div>
                            <div class="relative w-20 h-20 rounded-3xl bg-white/[0.04] border border-white/10 backdrop-blur-xl flex items-center justify-center text-4xl group-hover:scale-110 transition duration-500">🏆</div>
                        </div>
                        <div class="px-3 py-1.5 rounded-full border text-[10px] uppercase tracking-[0.18em] font-bold backdrop-blur-xl text-orange-300 bg-orange-400/10 border-orange-400/20">Popular</div>
                    </div>
                    <div class="flex-1">
                        <h3 class="text-2xl font-black text-white leading-tight mb-3">Jasa Joki</h3>
                        <p class="text-slate-400 leading-relaxed text-sm">Push rank cepat & aman</p>
                    </div>
                    <div class="flex items-center justify-between mt-8 pt-6 border-t border-white/5">
                        <div class="flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                            <span class="text-xs text-slate-400">Online 24/7</span>
                        </div>
                        <div class="w-12 h-12 rounded-2xl border border-white/10 bg-white/[0.04] flex items-center justify-center text-white group-hover:bg-white group-hover:text-black group-hover:scale-110 transition-all duration-300">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.3" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                        </div>
                    </div>
                </div>
            </a>

            <a href="{{ route('products.by-type', 'akun') }}" class="group relative overflow-hidden rounded-[30px] border border-purple-400/20 bg-white/[0.03] backdrop-blur-xl p-6 hover:-translate-y-2 transition-all duration-500">
                <div class="absolute inset-0 opacity-0 group-hover:opacity-100 transition duration-500">
                    <div class="absolute inset-0 bg-gradient-to-br from-purple-500/20 via-fuchsia-500/10 to-transparent"></div>
                    <div class="absolute -top-10 -right-10 w-40 h-40 bg-purple-400/20 blur-[80px] rounded-full"></div>
                </div>
                <div class="absolute inset-[1px] rounded-[29px] border border-white/5 pointer-events-none"></div>
                <div class="relative z-10 flex flex-col h-full">
                    <div class="flex items-start justify-between mb-8">
                        <div class="relative">
                            <div class="absolute inset-0 blur-2xl opacity-40 bg-purple-400/20"></div>
                            <div class="relative w-20 h-20 rounded-3xl bg-white/[0.04] border border-white/10 backdrop-blur-xl flex items-center justify-center text-4xl group-hover:scale-110 transition duration-500">👤</div>
                        </div>
                        <div class="px-3 py-1.5 rounded-full border text-[10px] uppercase tracking-[0.18em] font-bold backdrop-blur-xl text-purple-300 bg-purple-400/10 border-purple-400/20">Premium</div>
                    </div>
                    <div class="flex-1">
                        <h3 class="text-2xl font-black text-white leading-tight mb-3">Akun Sultan</h3>
                        <p class="text-slate-400 leading-relaxed text-sm">Akun premium ready stock</p>
                    </div>
                    <div class="flex items-center justify-between mt-8 pt-6 border-t border-white/5">
                        <div class="flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                            <span class="text-xs text-slate-400">Online 24/7</span>
                        </div>
                        <div class="w-12 h-12 rounded-2xl border border-white/10 bg-white/[0.04] flex items-center justify-center text-white group-hover:bg-white group-hover:text-black group-hover:scale-110 transition-all duration-300">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.3" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                        </div>
                    </div>
                </div>
            </a>

            <a href="{{ route('products.by-type', 'item') }}" class="group relative overflow-hidden rounded-[30px] border border-emerald-400/20 bg-white/[0.03] backdrop-blur-xl p-6 hover:-translate-y-2 transition-all duration-500">
                <div class="absolute inset-0 opacity-0 group-hover:opacity-100 transition duration-500">
                    <div class="absolute inset-0 bg-gradient-to-br from-emerald-500/20 via-green-500/10 to-transparent"></div>
                    <div class="absolute -top-10 -right-10 w-40 h-40 bg-emerald-400/20 blur-[80px] rounded-full"></div>
                </div>
                <div class="absolute inset-[1px] rounded-[29px] border border-white/5 pointer-events-none"></div>
                <div class="relative z-10 flex flex-col h-full">
                    <div class="flex items-start justify-between mb-8">
                        <div class="relative">
                            <div class="absolute inset-0 blur-2xl opacity-40 bg-emerald-400/20"></div>
                            <div class="relative w-20 h-20 rounded-3xl bg-white/[0.04] border border-white/10 backdrop-blur-xl flex items-center justify-center text-4xl group-hover:scale-110 transition duration-500">⚔️</div>
                        </div>
                        <div class="px-3 py-1.5 rounded-full border text-[10px] uppercase tracking-[0.18em] font-bold backdrop-blur-xl text-emerald-300 bg-emerald-400/10 border-emerald-400/20">Hot</div>
                    </div>
                    <div class="flex-1">
                        <h3 class="text-2xl font-black text-white leading-tight mb-3">Item & Skin</h3>
                        <p class="text-slate-400 leading-relaxed text-sm">Skin rare & item eksklusif</p>
                    </div>
                    <div class="flex items-center justify-between mt-8 pt-6 border-t border-white/5">
                        <div class="flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                            <span class="text-xs text-slate-400">Online 24/7</span>
                        </div>
                        <div class="w-12 h-12 rounded-2xl border border-white/10 bg-white/[0.04] flex items-center justify-center text-white group-hover:bg-white group-hover:text-black group-hover:scale-110 transition-all duration-300">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.3" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                        </div>
                    </div>
                </div>
            </a>
        </div>
    </div>
</section>

{{-- ═══════════════════════════════════════════════════════════ --}}
{{-- PREMIUM DISCOVERY SECTION                                  --}}
{{-- ═══════════════════════════════════════════════════════════ --}}
<section class="pb-20 reveal-card relative overflow-hidden">
    <div class="absolute inset-0 pointer-events-none">
        <div class="absolute top-0 left-1/2 -translate-x-1/2 w-[900px] h-[400px] bg-cyan-500/10 blur-[140px] rounded-full"></div>
        <div class="absolute bottom-0 right-0 w-[500px] h-[300px] bg-purple-500/10 blur-[120px] rounded-full"></div>
    </div>

    <div class="max-w-7xl mx-auto px-4 relative z-10">
        <div class="flex flex-col lg:flex-row lg:items-end lg:justify-between gap-6 mb-10">
            <div>
                <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full border border-cyan-400/20 bg-cyan-400/5 mb-5">
                    <span class="w-2 h-2 rounded-full bg-cyan-400 animate-pulse"></span>
                    <span class="text-cyan-300 text-xs tracking-[0.25em] uppercase font-semibold">Featured Collection</span>
                </div>
                <h2 class="font-display text-4xl font-black text-white leading-tight">Produk Pilihan<br><span class="bg-gradient-to-r from-cyan-300 via-blue-400 to-purple-400 bg-clip-text text-transparent">Gamer Indonesia</span></h2>
                <p class="text-slate-400 mt-4 max-w-xl leading-relaxed">Koleksi produk premium dengan rating tinggi, transaksi tercepat, dan harga terbaik pilihan komunitas gamer.</p>
            </div>
            <a href="{{ route('products.search') }}" class="group relative overflow-hidden rounded-2xl border border-cyan-400/20 bg-white/5 backdrop-blur-xl px-6 py-4 hover:border-cyan-400/40 transition-all duration-300">
                <div class="absolute inset-0 bg-gradient-to-r from-cyan-500/0 via-cyan-400/10 to-cyan-500/0 translate-x-[-120%] group-hover:translate-x-[120%] transition duration-1000"></div>
                <div class="relative flex items-center gap-3">
                    <span class="text-white font-semibold">Explore Semua Produk</span>
                    <svg class="w-5 h-5 text-cyan-300 group-hover:translate-x-1 transition" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </div>
            </a>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
            @forelse($popularProducts as $product)
                @if($loop->first)
                <a href="{{ route('products.show', $product->slug) }}" class="lg:col-span-7 relative rounded-[34px] overflow-hidden group border border-white/10 bg-[#0B1120] min-h-[540px]">
                    <div class="absolute inset-0">
                        <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-700">
                        <div class="absolute inset-0 bg-gradient-to-t from-[#020617]/80 via-[#020617]/20 to-transparent"></div>
                        <div class="absolute inset-0 bg-gradient-to-r from-[#020617]/90 to-transparent"></div>
                    </div>
                    <div class="absolute top-6 left-6 z-20 flex flex-wrap gap-3">
                        <div class="px-4 py-2 rounded-full bg-cyan-400/15 border border-cyan-400/30 backdrop-blur-xl">
                            <span class="text-cyan-300 text-xs font-bold tracking-[0.18em] uppercase">#1 Trending</span>
                        </div>
                        <div class="px-4 py-2 rounded-full bg-yellow-400/15 border border-yellow-400/30 backdrop-blur-xl">
                            <span class="text-yellow-300 text-xs font-bold">⭐ {{ number_format($product->average_rating ?? 5, 1) }}</span>
                        </div>
                    </div>
                    <div class="absolute bottom-0 left-0 right-0 p-8 sm:p-10 z-20">
                        <div class="max-w-2xl">
                            <h3 class="text-4xl font-black text-white leading-tight mb-4">{{ $product->name }}</h3>
                            <p class="text-slate-300 leading-relaxed mb-8 line-clamp-3">{{ $product->description }}</p>
                            <div class="flex flex-wrap items-center gap-5">
                                <div>
                                    <div class="text-xs uppercase tracking-[0.18em] text-slate-500 mb-1">Harga Mulai</div>
                                    <div class="text-4xl font-black text-cyan-300">Rp {{ number_format($product->price,0,',','.') }}</div>
                                </div>
                                <div class="h-14 w-px bg-white/10"></div>
                                <div class="flex items-center gap-4">
                                    <div class="w-14 h-14 rounded-2xl bg-white/10 backdrop-blur-xl flex items-center justify-center border border-white/10">
                                        <svg class="w-7 h-7 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.7" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                                    </div>
                                    <div>
                                        <div class="text-white font-bold">Instant Delivery</div>
                                        <div class="text-sm text-slate-400">Proses super cepat & otomatis</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
                <div class="lg:col-span-5 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-1 gap-6">
                @elseif($loop->iteration <= 4)
                    <a href="{{ route('products.show', $product->slug) }}" class="group relative overflow-hidden rounded-[28px] border border-white/10 bg-white/[0.03] backdrop-blur-xl hover:border-cyan-400/30 transition-all duration-500">
                        <div class="absolute inset-0 opacity-0 group-hover:opacity-100 transition duration-500">
                            <div class="absolute -top-20 right-0 w-52 h-52 bg-cyan-400/20 blur-[90px] rounded-full"></div>
                        </div>
                        <div class="relative flex gap-5 p-5">
                            <div class="relative w-36 h-36 rounded-2xl overflow-hidden shrink-0">
                                <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="w-full h-full object-cover group-hover:scale-110 transition duration-700">
                                <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>
                                <div class="absolute bottom-2 left-2 px-2 py-1 rounded-lg bg-black/60 backdrop-blur-xl">
                                    <span class="text-yellow-300 text-xs font-semibold">⭐ {{ number_format($product->average_rating ?? 5, 1) }}</span>
                                </div>
                            </div>
                            <div class="flex-1 flex flex-col justify-between min-w-0">
                                <div>
                                    <div class="inline-flex items-center gap-2 mb-3">
                                        <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                                        <span class="text-[10px] uppercase tracking-[0.18em] text-emerald-300 font-bold">Ready Stock</span>
                                    </div>
                                    <h3 class="text-white font-bold text-lg line-clamp-2 leading-snug">{{ $product->name }}</h3>
                                    <p class="text-sm text-slate-400 mt-2 line-clamp-2">{{ $product->description }}</p>
                                </div>
                                <div class="flex items-end justify-between mt-5">
                                    <div>
                                        <div class="text-xs text-slate-500 mb-1">Harga</div>
                                        <div class="text-cyan-300 text-2xl font-black">Rp {{ number_format($product->price,0,',','.') }}</div>
                                    </div>
                                    <div class="w-11 h-11 rounded-xl border border-white/10 bg-white/5 flex items-center justify-center group-hover:bg-cyan-400 group-hover:text-black transition-all duration-300">
                                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                @endif
                @if($loop->iteration == 4 || $loop->last)
                </div>
                @endif
            @empty
                <div class="col-span-full py-28 text-center rounded-[32px] border border-white/10 bg-white/[0.03] backdrop-blur-xl">
                    <div class="text-7xl mb-6">🎮</div>
                    <h3 class="text-3xl font-black text-white mb-3">Produk Akan Segera Hadir</h3>
                    <p class="text-slate-400 max-w-md mx-auto">Tim kami sedang menyiapkan koleksi produk terbaik untuk pengalaman gaming premium.</p>
                </div>
            @endforelse
        </div>
    </div>
</section>

{{-- ═══════════════════════════════════════════════════════════ --}}
{{-- TOPUP SECTION — PREMIUM AUTO SLIDER                        --}}
{{-- ═══════════════════════════════════════════════════════════ --}}
@if($topupProducts->isNotEmpty())
<section class="pb-20 overflow-hidden reveal-card">
  <div class="absolute inset-0 pointer-events-none">
    <div class="absolute top-0 left-1/2 -translate-x-1/2 w-[600px] h-[240px] bg-cyan-500/10 blur-[120px] rounded-full"></div>
  </div>

  <div class="max-w-7xl mx-auto px-4 relative z-10">
    <div class="flex items-end justify-between mb-8">
      <div>
        <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-cyan-400/10 border border-cyan-400/20 text-cyan-300 text-[11px] font-black tracking-[0.2em] uppercase mb-4">
          <span class="w-2 h-2 rounded-full bg-cyan-400 animate-pulse"></span>Instant Top Up
        </div>
        <h2 class="text-3xl md:text-4xl font-black text-white">Top Up Favorit</h2>
        <p class="text-slate-400 mt-2 text-sm">Transaksi cepat dengan pengalaman premium</p>
      </div>
      <a href="{{ route('products.by-type', 'topup') }}" class="hidden md:flex items-center gap-3 text-cyan-300 hover:text-white transition">Semua Top Up →</a>
    </div>

    <div class="relative">
       <div id="premium-topup-track" class="premium-topup-track flex gap-5 overflow-x-auto snap-x snap-mandatory scroll-smooth pb-4">
        @foreach($topupProducts as $product)
        <div class="premium-slide flex-none w-[84%] sm:w-[48%] lg:w-[31%] xl:w-[24%] snap-start">
          <a href="{{ route('products.show', $product->slug) }}" class="group relative block rounded-[28px] overflow-hidden border border-white/10 bg-gradient-to-b from-[#0f172a] to-[#020617] hover:border-cyan-400/30 transition duration-500">
            <div class="relative h-56 overflow-hidden">
              <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="w-full h-full object-cover group-hover:scale-110 transition duration-700">
              <div class="absolute inset-0 bg-gradient-to-t from-[#020617] via-[#020617]/30 to-transparent"></div>
              <div class="absolute top-4 left-4">
                <div class="px-3 py-1.5 rounded-full bg-cyan-400/15 border border-cyan-400/20 backdrop-blur-xl text-cyan-300 text-[10px] font-black tracking-[0.15em]">⚡ FAST</div>
              </div>
              <div class="absolute top-4 right-4">
                <div class="flex items-center gap-1 px-3 py-1.5 rounded-full bg-black/40 border border-white/10 text-white text-xs font-bold">⭐ {{ number_format($product->average_rating ?? 5,1) }}</div>
              </div>
              <div class="absolute bottom-0 left-0 right-0 p-5">
                <div class="text-[10px] uppercase tracking-[0.22em] text-cyan-300/80 font-bold mb-2">PREMIUM TOPUP</div>
                <h3 class="text-xl font-black text-white line-clamp-2">{{ $product->name }}</h3>
              </div>
            </div>
            <div class="p-5">
              <div class="flex items-center gap-2 mb-5">
                <div class="px-3 py-2 rounded-xl bg-white/[0.03] border border-white/5 text-[10px] text-slate-300">⚡ Instan</div>
                <div class="px-3 py-2 rounded-xl bg-white/[0.03] border border-white/5 text-[10px] text-slate-300">🛡 Aman</div>
              </div>
              <div class="flex items-end justify-between gap-3">
                <div>
                  <div class="text-[11px] text-slate-500 mb-1">Harga Mulai</div>
                  <div class="text-2xl font-black bg-gradient-to-r from-cyan-200 to-blue-400 bg-clip-text text-transparent">Rp {{ number_format($product->price,0,',','.') }}</div>
                </div>
                <span class="w-12 h-12 rounded-2xl bg-gradient-to-r from-cyan-400 to-blue-500 text-black font-black flex items-center justify-center hover:scale-110 transition">→</span>
              </div>
            </div>
          </a>
        </div>
        @endforeach
      </div>

      <div class="flex items-center justify-center gap-2 mt-6">
        @foreach($topupProducts as $product)
        <button class="premium-dot w-12 h-1.5 rounded-full bg-white/10 transition duration-300" data-index="{{ $loop->index }}"></button>
        @endforeach
      </div>
    </div>
  </div>
</section>
@endif

{{-- ═══════════════════════════════════════════════════════════ --}}
{{-- VALUE PROPOSITION BANNER                                   --}}
{{-- ═══════════════════════════════════════════════════════════ --}}
<section class="pb-14 reveal-card">
  <div class="max-w-7xl mx-auto px-4">
    <div class="rounded-2xl p-8 sm:p-12 relative overflow-hidden text-center" style="background:linear-gradient(135deg,rgba(37,99,235,0.15) 0%,rgba(249,115,22,0.08) 100%);border:1px solid rgba(37,99,235,0.25);">
      <div class="absolute inset-0 pointer-events-none" style="background:radial-gradient(ellipse 60% 50% at 50% 100%,rgba(37,99,235,0.1),transparent);"></div>
      <span class="badge badge-blue mb-4">Kenapa Lapak Gaming?</span>
      <h2 class="font-display font-bold text-2xl sm:text-3xl text-white mb-3">Platform terpercaya untuk<br>semua kebutuhan gaming-mu</h2>
      <p class="text-slate-400 text-sm max-w-lg mx-auto mb-8">Bergabung dengan jutaan gamer Indonesia yang sudah percaya transaksi mereka bersama kami.</p>
      <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 max-w-3xl mx-auto mb-8">
        @php $ratingBadge = number_format($averageRating ?: 0, 1); @endphp
        <div class="flex flex-col items-center text-center">
          <div class="w-10 h-10 rounded-xl flex items-center justify-center mb-3" style="background:rgba(37,99,235,0.12);border:1px solid rgba(37,99,235,0.25);">
            <svg class="w-5 h-5 text-brand-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
            </svg>
          </div>
          <div class="text-sm font-semibold text-white">Escrow Aman</div>
          <div class="text-xs text-slate-500 mt-0.5">Dana terlindungi</div>
        </div>
        <div class="flex flex-col items-center text-center">
          <div class="w-10 h-10 rounded-xl flex items-center justify-center mb-3" style="background:rgba(37,99,235,0.12);border:1px solid rgba(37,99,235,0.25);">
            <svg class="w-5 h-5 text-brand-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
          </div>
          <div class="text-sm font-semibold text-white">Proses Cepat</div>
          <div class="text-xs text-slate-500 mt-0.5">&lt; 5 menit selesai</div>
        </div>
        <div class="flex flex-col items-center text-center">
          <div class="w-10 h-10 rounded-xl flex items-center justify-center mb-3" style="background:rgba(37,99,235,0.12);border:1px solid rgba(37,99,235,0.25);">
            <svg class="w-5 h-5 text-brand-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
            </svg>
          </div>
          <div class="text-sm font-semibold text-white">Rating Tinggi</div>
          <div class="text-xs text-slate-500 mt-0.5">{{ $ratingBadge }} dari 5 bintang</div>
        </div>
        <div class="flex flex-col items-center text-center">
          <div class="w-10 h-10 rounded-xl flex items-center justify-center mb-3" style="background:rgba(37,99,235,0.12);border:1px solid rgba(37,99,235,0.25);">
            <svg class="w-5 h-5 text-brand-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"/>
            </svg>
          </div>
          <div class="text-sm font-semibold text-white">Support 24/7</div>
          <div class="text-xs text-slate-500 mt-0.5">Siap bantu kapanpun</div>
        </div>
      </div>
    </div>
  </div>
</section>

{{-- ═══════════════════════════════════════════════════════════ --}}
{{-- FEATURED BANNERS                                           --}}
{{-- ═══════════════════════════════════════════════════════════ --}}
@if(isset($featuredBanners) && $featuredBanners->count())
<section class="py-16 reveal-card">
  <div class="max-w-7xl mx-auto px-4">
    <div class="flex items-end justify-between mb-8">
      <div>
        <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full border border-amber-400/20 bg-amber-400/10 text-amber-300 text-[11px] font-black tracking-[0.2em] uppercase mb-4">
          <span class="w-2 h-2 rounded-full bg-amber-300 animate-pulse"></span>Featured Banner
        </div>
        <h2 class="text-3xl md:text-4xl font-black text-white">Sorotan Promo</h2>
        <p class="text-slate-400 mt-2 text-sm">Banner pilihan admin yang muncul di tengah alur halaman saat kamu scroll ke bawah.</p>
      </div>
    </div>

    <div class="relative rounded-[28px] border border-white/10 bg-slate-950/50 p-3 sm:p-4 shadow-[0_24px_80px_rgba(0,0,0,0.35)]">
      <div id="featured-banner-track" class="banner-track flex gap-4 overflow-x-auto scroll-smooth snap-x snap-mandatory">
        @foreach($featuredBanners as $banner)
          <a href="{{ $banner->link_url ?: '#' }}" class="banner-slide group relative flex-none w-[86%] sm:w-[58%] lg:w-[42%] overflow-hidden rounded-[24px] border border-slate-800 bg-slate-900 snap-start">
            <div class="relative aspect-[3/1] overflow-hidden">
              <img src="{{ $banner->image_url }}" alt="{{ $banner->title }}" class="h-full w-full object-cover transition duration-500 group-hover:scale-105">
              <div class="absolute inset-0 bg-gradient-to-r from-slate-950/85 via-slate-950/45 to-transparent"></div>
              <div class="absolute inset-0 flex items-end p-5 sm:p-6">
                <div class="max-w-xl">
                  <span class="inline-flex items-center rounded-full border border-emerald-400/30 bg-emerald-400/10 px-3 py-1 text-[10px] font-semibold uppercase tracking-[0.22em] text-emerald-200">Featured</span>
                  <h3 class="mt-3 text-2xl sm:text-3xl font-black text-white">{{ $banner->title }}</h3>
                  <p class="mt-2 text-sm sm:text-base text-slate-300">{{ $banner->subtitle }}</p>
                </div>
              </div>
            </div>
          </a>
        @endforeach
      </div>
    </div>
  </div>
</section>
@endif

{{-- ═══════════════════════════════════════════════════════════ --}}
{{-- FAQ — GLASSMORPHISM FIXED VERSION                          --}}
{{-- ═══════════════════════════════════════════════════════════ --}}
<section class="pb-24 block overflow-visible relative clear-both" id="faq-section" style="content-visibility: auto;">
  <div class="max-w-7xl mx-auto px-4">
    
    <div class="grid lg:grid-cols-2 gap-10 items-start relative h-full">

      {{-- LEFT ACCORDION HEADER (STAY STICKY) --}}
      <div class="sticky top-28 z-30 self-start hidden lg:block w-full">
        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-cyan-500/10 border border-cyan-500/20 text-cyan-300 text-xs font-bold mb-5">
          SUPPORT CENTER
        </div>
        <h2 class="text-4xl md:text-5xl font-black text-white leading-tight mb-5">
          Pertanyaan<br>yang sering<br>ditanyakan
        </h2>
        <p class="text-slate-400 text-lg leading-relaxed max-w-lg">
          Semua informasi penting mengenai transaksi, keamanan, top up, hingga sistem marketplace tersedia di sini.
        </p>
      </div>

      {{-- RESPONSIVE HEADER FOR MOBILE --}}
      <div class="block lg:hidden mb-2">
        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-cyan-500/10 border border-cyan-500/20 text-cyan-300 text-xs font-bold mb-3">
          SUPPORT CENTER
        </div>
        <h2 class="text-3xl font-black text-white leading-tight mb-3">
          Pertanyaan yang sering ditanyakan
        </h2>
      </div>

      {{-- RIGHT ACCORDION ITEMS --}}
      <div class="space-y-4 z-10 w-full">
        <div class="overflow-hidden rounded-[28px] border border-white/10 bg-white/[0.03] backdrop-blur-xl">
          <button type="button" data-faq-index="0" class="js-faq-toggle w-full p-6 flex items-center justify-between text-left transition hover:bg-white/[0.02]">
            <span class="text-lg font-bold text-white pr-5">Marketplace Games Terbesar dan Terlengkap</span>
            <div id="icon-0" class="w-10 h-10 rounded-2xl bg-white/5 border border-white/10 flex items-center justify-center text-cyan-300 transition duration-300 shrink-0">&gt;</div>
          </button>
          <div id="faq-0" class="max-h-0 overflow-hidden transition-all duration-300">
            <div class="px-6 pb-6 text-slate-400 leading-relaxed">Lapak Gaming adalah marketplace destinasi utama bagi para gamers untuk yang mencari kenyamanan dan keandalan dalam bertransaksi digital.</div>
          </div>
        </div>

        <div class="overflow-hidden rounded-[28px] border border-white/10 bg-white/[0.03] backdrop-blur-xl">
          <button type="button" data-faq-index="1" class="js-faq-toggle w-full p-6 flex items-center justify-between text-left transition hover:bg-white/[0.02]">
            <span class="text-lg font-bold text-white pr-5">Apa itu Lapak Gaming?</span>
            <div id="icon-1" class="w-10 h-10 rounded-2xl bg-white/5 border border-white/10 flex items-center justify-center text-cyan-300 transition duration-300 shrink-0">&gt;</div>
          </button>
          <div id="faq-1" class="max-h-0 overflow-hidden transition-all duration-300">
            <div class="px-6 pb-6 text-slate-400 leading-relaxed">Kami adalah platform perantara escrow yang menjamin keamanan transaksi antara penjual and pembeli produk game di Indonesia.</div>
          </div>
        </div>

        <div class="overflow-hidden rounded-[28px] border border-white/10 bg-white/[0.03] backdrop-blur-xl">
          <button type="button" data-faq-index="2" class="js-faq-toggle w-full p-6 flex items-center justify-between text-left transition hover:bg-white/[0.02]">
            <span class="text-lg font-bold text-white pr-5">Top-Up Game Terlengkap</span>
            <div id="icon-2" class="w-10 h-10 rounded-2xl bg-white/5 border border-white/10 flex items-center justify-center text-cyan-300 transition duration-300 shrink-0">&gt;</div>
          </button>
          <div id="faq-2" class="max-h-0 overflow-hidden transition-all duration-300">
            <div class="px-6 pb-6 text-slate-400 leading-relaxed">Nikmati layanan top up berbagai game populer dengan proses instan dan harga terbaik.</div>
          </div>
        </div>

        <div class="overflow-hidden rounded-[28px] border border-white/10 bg-white/[0.03] backdrop-blur-xl">
          <button type="button" data-faq-index="3" class="js-faq-toggle w-full p-6 flex items-center justify-between text-left transition hover:bg-white/[0.02]">
            <span class="text-lg font-bold text-white pr-5">Voucher Digital</span>
            <div id="icon-3" class="w-10 h-10 rounded-2xl bg-white/5 border border-white/10 flex items-center justify-center text-cyan-300 transition duration-300 shrink-0">&gt;</div>
          </button>
          <div id="faq-3" class="max-h-0 overflow-hidden transition-all duration-300">
            <div class="px-6 pb-6 text-slate-400 leading-relaxed">Kami juga menyediakan voucher digital untuk berbagai kebutuhan hiburan digital.</div>
          </div>
        </div>
      </div>

    </div>
  </div>
</section>

@push('scripts')
<script type="module" src="https://unpkg.com/@splinetool/viewer/build/spline-viewer.js"></script>
<script>
(function () {
  'use strict';

  if (window.innerWidth < 1024) return;

  const heroSection    = document.getElementById('hero-section');
  const robotWrapper   = document.getElementById('hero-robot-wrapper');
  const robotScene     = document.getElementById('robot-scene-container');
  const splineEl       = document.getElementById('spline-robot');
  const loader         = document.getElementById('robot-loader');
  
  if (!heroSection || !robotWrapper || !robotScene || !splineEl) return;

  function hideLoader() {
    if (!loader) return;
    loader.classList.add('loader-hidden');
    setTimeout(() => {
      loader.style.display = 'none';
      loader.remove();
    }, 700);
  }

  if (splineEl) {
    splineEl.addEventListener('load', () => { hideLoader(); });
    setTimeout(() => { hideLoader(); }, 5000);
  }
  setTimeout(() => { hideLoader(); }, 12000);

  function lerp(a, b, t) { return a + (b - a) * t; }
  function clamp(v, lo, hi) { return Math.max(lo, Math.min(hi, v)); }

  let targetX = 0, targetY = 0, currentX = 0, currentY = 0;
  let isVisible = true, pendingFrame = false;
  let splineApp = null, robotHead = null;

  splineEl.addEventListener('load', async (e) => {
    hideLoader();
    splineApp = e.target.application;
    robotHead = splineApp.findObjectByName('Head');
  });

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

  document.addEventListener('visibilitychange', () => {
    isVisible = !document.hidden;
    if (!isVisible) pendingFrame = false; else scheduleUpdate();
  });

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
  }, { threshold: 0.15 });

  observer.observe(robotWrapper);

  heroSection.addEventListener('mousemove', (e) => {
    const hr = heroSection.getBoundingClientRect();
    targetX = clamp((e.clientX - (hr.left + hr.width / 2)) / (hr.width / 2), -1, 1);
    targetY = clamp((e.clientY - (hr.top + hr.height / 2)) / (hr.height / 2), -1, 1);
    scheduleUpdate();
  });

  heroSection.addEventListener('mouseleave', () => {
    targetX = 0; targetY = 0;
    scheduleUpdate();
  });

  function applyRobotTransform() {
    if (!robotHead) return;
    robotHead.rotation.y = currentX * 0.35;
    robotHead.rotation.x = -currentY * 0.20;
  }

  applyRobotTransform();

  window.toggleFaq = function (index) {
    const content = document.getElementById('faq-' + index);
    const icon    = document.getElementById('icon-' + index);
    const isOpen = content.style.maxHeight !== '0px' && content.style.maxHeight !== '';

    document.querySelectorAll('[id^="faq-"]').forEach(el => { el.style.maxHeight = '0px'; });
    document.querySelectorAll('[id^="icon-"]').forEach(el => { el.style.transform = 'rotate(0deg)'; });

    if (!isOpen) {
      content.style.maxHeight = content.scrollHeight + 'px';
      icon.style.transform = 'rotate(180deg)';
    }
  };

  document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.js-faq-toggle').forEach((button) => {
      button.addEventListener('click', () => {
        const index = Number(button.getAttribute('data-faq-index'));
        if (!Number.isNaN(index)) window.toggleFaq(index);
      });
    });

    const reveals = document.querySelectorAll('.reveal-card');
    const revObserver = new IntersectionObserver((entries) => {
      entries.forEach(entry => { if (entry.isIntersecting) entry.target.classList.add('show'); });
    }, { threshold: 0.12 });
    reveals.forEach(el => revObserver.observe(el));

    const statCounters = document.querySelectorAll('.js-stat-counter');
    function animateCounter(element) {
      const target = Number(element.getAttribute('data-target') || 0);
      const duration = 1400;
      const start = performance.now();
      function tick(now) {
        const progress = Math.min((now - start) / duration, 1);
        const eased = 1 - Math.pow(1 - progress, 3);
        element.textContent = Math.round(target * eased).toLocaleString('id-ID');
        if (progress < 1) requestAnimationFrame(tick); else element.textContent = target.toLocaleString('id-ID');
      }
      requestAnimationFrame(tick);
    }

    if (statCounters.length) {
      const statObserver = new IntersectionObserver((entries, oi) => {
        entries.forEach((entry) => { if (!entry.isIntersecting) return; animateCounter(entry.target); oi.unobserve(entry.target); });
      }, { threshold: 0.35 });
      statCounters.forEach((counter) => statObserver.observe(counter));
    }

    const initBannerAutoplay = (trackId, intervalMs = 3000) => {
      const track = document.getElementById(trackId);
      if (!track || track.children.length <= 1) return;
      const slides = Array.from(track.children);
      let currentIndex = 0, autoplayTimer = null;

      const scrollToSlide = (index) => {
        const slide = slides[index];
        if (slide) track.scrollTo({ left: slide.offsetLeft - track.offsetLeft, behavior: 'smooth' });
      };

      const startAutoplay = () => {
        if (autoplayTimer) return;
        autoplayTimer = window.setInterval(() => {
          currentIndex = (currentIndex + 1) % slides.length;
          scrollToSlide(currentIndex);
        }, intervalMs);
      };

      const stopAutoplay = () => { clearInterval(autoplayTimer); autoplayTimer = null; };

      track.addEventListener('mouseenter', stopAutoplay);
      track.addEventListener('mouseleave', startAutoplay);
      startAutoplay();
    };

    initBannerAutoplay('banner-track', 3000);
    initBannerAutoplay('featured-banner-track', 3000);
  });
})();

document.addEventListener('DOMContentLoaded', function () {
  const track = document.getElementById('premium-topup-track');
  const slides = document.querySelectorAll('.premium-slide');
  const dots = document.querySelectorAll('.premium-dot');
  if (!track || slides.length === 0) return;
  let currentIndex = 0, autoSlide;

  function updateDots(index) {
    dots.forEach((dot, i) => {
      if (i === index) { dot.classList.remove('bg-white/10'); dot.classList.add('bg-cyan-400'); }
      else { dot.classList.remove('bg-cyan-400'); dot.classList.add('bg-white/10'); }
    });
  }

  function goToSlide(index) {
    const slide = slides[index];
    if (!slide) return;
    track.scrollTo({ left: slide.offsetLeft, behavior: 'smooth' });
    currentIndex = index;
    updateDots(index);
  }

  function startSlider() {
    autoSlide = setInterval(() => {
      currentIndex++;
      if (currentIndex >= slides.length) currentIndex = 0;
      goToSlide(currentIndex);
    }, 3000);
  }
  function stopSlider() { clearInterval(autoSlide); }

  dots.forEach((dot, index) => {
    dot.addEventListener('click', () => { stopSlider(); goToSlide(index); startSlider(); });
  });

  track.addEventListener('mouseenter', stopSlider);
  track.addEventListener('mouseleave', startSlider);
  updateDots(0); startSlider();
});

document.addEventListener('DOMContentLoaded', () => {
  const sliders = document.querySelectorAll('[data-category-slider]');
  sliders.forEach((slider) => {
    const cards = slider.children;
    if (!cards.length) return;
    let current = 0, autoplay;

    const startSlider = () => {
      autoplay = setInterval(() => {
        current++;
        if (current >= cards.length) current = 0;
        slider.scrollTo({ left: cards[current].offsetLeft - 40, behavior: 'smooth' });
        updateDots(current);
      }, 3500);
    };
    const stopSlider = () => clearInterval(autoplay);
    const dots = slider.parentElement.querySelectorAll('[data-category-dot]');

    function updateDots(index){
      dots.forEach(dot => { dot.classList.remove('bg-cyan-400'); dot.classList.add('bg-white/10'); });
      if(dots[index % dots.length]){
        dots[index % dots.length].classList.remove('bg-white/10');
        dots[index % dots.length].classList.add('bg-cyan-400');
      }
    }

    dots.forEach((dot, index) => {
      dot.addEventListener('click', () => { current = index; slider.scrollTo({ left: cards[index].offsetLeft - 40, behavior: 'smooth' }); updateDots(index); });
    });

    slider.addEventListener('mouseenter', stopSlider);
    slider.addEventListener('mouseleave', startSlider);
    updateDots(0); startSlider();
  });
});
</script>
@endpush
@endsection
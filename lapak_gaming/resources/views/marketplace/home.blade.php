@extends('layouts.app')
@section('title', 'Lapak Gaming — Marketplace Game Terpercaya Indonesia')

@push('styles')
<style>
  .home-page {
    position: relative;
    overflow: hidden;
  }

  .home-stage {
    position: relative;
    z-index: 1;
  }

  .home-bg {
    position: absolute;
    inset: 0;
    pointer-events: none;
    overflow: hidden;
    z-index: 0;
  }

  .home-bg::before,
  .home-bg::after {
    content: '';
    position: absolute;
    inset: -10%;
    pointer-events: none;
  }

  .home-bg::before {
    background:
      radial-gradient(circle at 20% 18%, rgba(59,130,246,0.20), transparent 28%),
      radial-gradient(circle at 80% 10%, rgba(249,115,22,0.14), transparent 22%),
      radial-gradient(circle at 55% 82%, rgba(16,185,129,0.10), transparent 24%);
    animation: ambientDrift 26s ease-in-out infinite alternate;
  }

  .home-bg::after {
    background-image:
      linear-gradient(rgba(96,165,250,0.06) 1px, transparent 1px),
      linear-gradient(90deg, rgba(96,165,250,0.06) 1px, transparent 1px);
    background-size: 56px 56px;
    mask-image: linear-gradient(180deg, rgba(0,0,0,0.9), transparent 96%);
    animation: gridDrift 24s linear infinite;
    opacity: 0.45;
  }

  .home-vignette {
    position: absolute;
    inset: 0;
    background: radial-gradient(circle at center, transparent 50%, rgba(2,6,23,0.28) 100%);
  }

  .home-orb {
    position: absolute;
    border-radius: 999px;
    filter: blur(90px);
    opacity: 0.55;
    transform: translate3d(0, 0, 0);
  }

  .home-orb-blue {
    width: 34rem;
    height: 34rem;
    left: -10rem;
    top: 2rem;
    background: rgba(37,99,235,0.18);
    animation: orbFloat 18s ease-in-out infinite alternate;
  }

  .home-orb-orange {
    width: 28rem;
    height: 28rem;
    right: -8rem;
    top: 16rem;
    background: rgba(249,115,22,0.14);
    animation: orbFloat 22s ease-in-out infinite alternate-reverse;
  }

  .home-particles {
    position: absolute;
    inset: 0;
    overflow: hidden;
  }

  .home-particle {
    position: absolute;
    width: 6px;
    height: 6px;
    border-radius: 999px;
    background: radial-gradient(circle at 30% 30%, rgba(255,255,255,0.95), rgba(96,165,250,0.35) 60%, transparent 72%);
    box-shadow: 0 0 18px rgba(96,165,250,0.25);
    opacity: 0.45;
    animation: particleRise var(--dur, 14s) linear infinite;
  }

  .home-particle.is-accent {
    background: radial-gradient(circle at 30% 30%, rgba(255,255,255,0.95), rgba(249,115,22,0.35) 60%, transparent 72%);
    box-shadow: 0 0 18px rgba(249,115,22,0.25);
  }

  @keyframes particleRise {
    0% { transform: translate3d(0, 12vh, 0) scale(0.85); opacity: 0; }
    10% { opacity: var(--opacity, 0.45); }
    90% { opacity: var(--opacity, 0.45); }
    100% { transform: translate3d(calc(var(--drift, 1) * 18px), -120vh, 0) scale(1.05); opacity: 0; }
  }

  @keyframes ambientDrift {
    from { transform: translate3d(-1%, -1%, 0) scale(1); }
    to { transform: translate3d(1.5%, 1%, 0) scale(1.04); }
  }

  @keyframes gridDrift {
    from { transform: translate3d(0, 0, 0); }
    to { transform: translate3d(48px, 48px, 0); }
  }

  @keyframes orbFloat {
    0% { transform: translate3d(0, 0, 0) scale(1); }
    50% { transform: translate3d(18px, -20px, 0) scale(1.06); }
    100% { transform: translate3d(-8px, 14px, 0) scale(0.98); }
  }

  .home-surface {
    background: linear-gradient(145deg, rgba(8,17,37,0.92), rgba(8,17,37,0.70));
    border: 1px solid rgba(255,255,255,0.05);
    box-shadow: 0 24px 65px rgba(2,6,23,0.28);
    backdrop-filter: blur(18px);
  }

  .home-card {
    transition: transform 0.25s ease, box-shadow 0.25s ease, border-color 0.25s ease;
  }

  .home-card:hover {
    transform: translateY(-4px);
    border-color: rgba(96,165,250,0.2);
    box-shadow: 0 26px 65px rgba(2,6,23,0.35);
  }

  .home-chip {
    display: inline-flex;
    align-items: center;
    gap: 0.45rem;
    border-radius: 999px;
    padding: 0.45rem 0.8rem;
    border: 1px solid rgba(255,255,255,0.08);
    background: rgba(255,255,255,0.04);
    color: #dbeafe;
    backdrop-filter: blur(10px);
  }

  .home-hero-title {
    text-wrap: balance;
  }

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
  .hero-banner-track {
    display: flex;
    gap: 1rem;
    width: max-content;
    will-change: transform;
  }
  .hero-banner-scroll {
    overflow: hidden;
    position: relative;
  }
  .hero-banner-marquee {
    animation: heroBannerScroll 28s linear infinite;
  }
  @keyframes heroBannerScroll {
    0% { transform: translateX(0); }
    100% { transform: translateX(-50%); }
  }
  /* Category icons: compact modern look */
  .category-icon-wrapper {
    transition: transform .22s cubic-bezier(.2,.9,.3,1), opacity .22s, box-shadow .22s, border-color .22s;
    text-align: center;
  }
  .category-icon-wrapper:focus,
  .category-icon-wrapper:hover {
    transform: translateY(-6px) scale(1.03);
    opacity: 1;
    z-index: 2;
  }
  .category-icon {
    transition: transform .22s, box-shadow .22s, background .22s;
  }
  .category-panel {
    position: relative;
    overflow: hidden;
    background:
      radial-gradient(circle at top left, rgba(59,130,246,0.14), transparent 28%),
      radial-gradient(circle at top right, rgba(249,115,22,0.10), transparent 24%),
      linear-gradient(180deg, rgba(8,17,37,0.82), rgba(8,17,37,0.56));
    border: 1px solid rgba(255,255,255,0.03);
    box-shadow: 0 20px 50px rgba(2,6,23,0.24);
  }
  .category-panel::before {
    content: '';
    position: absolute;
    inset: 0;
    background-image: linear-gradient(135deg, rgba(255,255,255,0.03) 0%, transparent 35%, rgba(255,255,255,0.02) 70%, transparent 100%);
    pointer-events: none;
  }
  .category-heading {
    position: relative;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 1rem;
    margin-bottom: 1rem;
    padding-bottom: .85rem;
    border-bottom: 1px solid rgba(255,255,255,0.06);
  }
  .category-heading-badge {
    display: inline-flex;
    align-items: center;
    gap: .5rem;
    border-radius: 9999px;
    padding: .45rem .8rem;
    border: 1px solid rgba(255,255,255,0.08);
    background: rgba(255,255,255,0.04);
    color: #e2e8f0;
    font-size: .75rem;
    font-weight: 700;
    letter-spacing: .08em;
    text-transform: uppercase;
  }
  .category-heading-title {
    display: flex;
    flex-direction: column;
    gap: .15rem;
  }
  .category-heading-title h2 {
    margin: 0;
  }
  .category-heading-title p {
    margin: 0;
    color: #94a3b8;
    font-size: .875rem;
  }
  .category-icon .icon {
    width: 56px;
    height: 56px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border-radius: 12px;
    background: linear-gradient(180deg, rgba(255,255,255,0.02), rgba(255,255,255,0.01));
    backdrop-filter: blur(6px);
    box-shadow: 0 6px 18px rgba(2,6,23,0.18);
    overflow: hidden;
  }
  .category-icon .icon img { width: 100%; height: 100%; object-fit: cover; border-radius: inherit; }
  .category-icon .icon span { font-size: 20px; line-height: 1; }
  .category-icon-wrapper .category-animate { transform-origin: center; }
  @media (max-width: 640px) {
    .category-icon .icon { width: 48px; height: 48px; border-radius: 10px; }
    .category-icon-wrapper { width: 64px; }
    .category-heading { align-items: flex-start; flex-direction: column; }
  }
  @keyframes floaty {
    0% { transform: translateY(0); }
    50% { transform: translateY(-4px); }
    100% { transform: translateY(0); }
  }
  .category-animate { animation: floaty 5.6s ease-in-out infinite; }
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
  /* Introduction card with subtle animated gradient + sparkles */
  .intro-card {
    position: relative;
    overflow: hidden;
    background: linear-gradient(135deg, rgba(255,255,255,0.02), rgba(255,255,255,0.01));
    border-radius: 18px;
  }
  .intro-shimmer {
    position: absolute;
    inset: -40% -40% auto -40%;
    height: 220%;
    background: linear-gradient(90deg, rgba(255,255,255,0.02) 0%, rgba(255,255,255,0.06) 50%, rgba(255,255,255,0.02) 100%);
    transform: rotate(12deg);
    animation: shimmer 6s linear infinite;
    mix-blend-mode: overlay;
    pointer-events: none;
  }
  @keyframes shimmer { from { transform: translateX(-30%) rotate(12deg);} to { transform: translateX(30%) rotate(12deg);} }
  .intro-sparkle {
    position: absolute; width: 8px; height: 8px; border-radius: 50%; background: radial-gradient(circle at 30% 30%, #fff, #ffd700 60%); opacity: .9; pointer-events: none;
    animation: sparkle 3.6s ease-in-out infinite;
  }
  @keyframes sparkle { 0%{transform:translateY(0) scale(.8); opacity:.2}50%{transform:translateY(-10px) scale(1.1); opacity:1}100%{transform:translateY(0) scale(.9); opacity:.2} }
  .intro-sparkle { filter: drop-shadow(0 6px 10px rgba(255,200,60,0.06)); }
  .intro-sparkle.sm { width:6px; height:6px; }
  .intro-sparkle.md { width:9px; height:9px; }
  .intro-sparkle.lg { width:12px; height:12px; }
  .intro-star-svg { position: absolute; right:6%; top:6%; width:120px; height:120px; opacity:0.98; pointer-events:none; transform-origin:center; animation: spinSlow 18s linear infinite; z-index:15; }
  @keyframes spinSlow { from { transform: rotate(0deg) translateZ(0); } to { transform: rotate(360deg) translateZ(0); } }
  .intro-shape-pulse { transform-origin:center; animation: pulse 3.2s ease-in-out infinite; }
  @keyframes pulse { 0%{transform:scale(.96); opacity:.85}50%{transform:scale(1.06); opacity:1}100%{transform:scale(.96); opacity:.85} }
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

  /* Robot element removed — UI simplified */

  /* Featured banners: marquee animation */
  @keyframes featuredScroll {
    0% { transform: translateX(0); }
    100% { transform: translateX(-100%); }
  }
  .featured-scroll { overflow: hidden; }
  .animate-featured-scroll { display: flex; gap: 1rem; flex-wrap: nowrap; align-items: center; animation: featuredScroll 20s linear infinite; }
  @keyframes authFloat {
    0%   { transform: translateY(0) scale(1);   opacity: 0; }
    15%  { opacity: var(--op, 0.18); }
    85%  { opacity: var(--op, 0.18); }
    100% { transform: translateY(-120px) scale(0.6); opacity: 0; }
  }
    /* PREMIUM GLOW */
  .premium-glow {
    position: relative;
    overflow: hidden;
  }

  .premium-glow::before {
    content: '';
    position: absolute;
    inset: -1px;
    border-radius: inherit;
    padding: 1px;
    background: linear-gradient(
      135deg,
      rgba(14,165,233,.5),
      rgba(59,130,246,.15),
      rgba(249,115,22,.35)
    );
    -webkit-mask:
      linear-gradient(#fff 0 0) content-box,
      linear-gradient(#fff 0 0);
    -webkit-mask-composite: xor;
    mask:
      linear-gradient(#fff 0 0) content-box,
      linear-gradient(#fff 0 0);
    mask-composite: exclude;
    pointer-events: none;
  }

  /* PRODUCT CARD */
  .product-card {
    backdrop-filter: blur(12px);
    background:
      linear-gradient(
        180deg,
        rgba(15,23,42,.96),
        rgba(2,6,23,.98)
      );
  }

  .product-card:hover {
    transform: translateY(-8px);
    box-shadow:
      0 25px 70px rgba(14,165,233,.16),
      0 10px 35px rgba(0,0,0,.45);
  }

  /* IMAGE OVERLAY */
  .product-card img {
    transition:
      transform .6s cubic-bezier(.2,.8,.2,1),
      filter .3s;
  }

  .product-card:hover img {
    transform: scale(1.08);
    filter: brightness(1.08);
  }

  .product-card .product-card-overlay {
    transition: opacity 0.25s ease, background 0.25s ease;
  }

  .product-card:hover .product-card-overlay {
    background: linear-gradient(180deg, rgba(2,6,23,0.18), rgba(2,6,23,0.72));
  }

  /* HERO LIGHT */
  .hero-light {
    position: absolute;
    width: 500px;
    height: 500px;
    border-radius: 999px;
    filter: blur(120px);
    opacity: .16;
    pointer-events: none;
  }

  .hero-light.blue {
    background: #0ea5e9;
  }

  .hero-light.orange {
    background: #f97316;
  }

  /* CATEGORY HOVER */
  .category-icon-wrapper:hover .category-icon {
    transform: translateY(-5px) scale(1.06);
    box-shadow:
      0 18px 40px rgba(14,165,233,.18);
  }

  /* BUTTON PREMIUM */
  .btn-premium {
    background:
      linear-gradient(
        135deg,
        #0ea5e9,
        #2563eb
      );

    box-shadow:
      0 12px 30px rgba(14,165,233,.28);

    transition:
      transform .25s,
      box-shadow .25s;
  }

  .btn-premium:hover {
    transform: translateY(-2px);
    box-shadow:
      0 20px 45px rgba(14,165,233,.35);
  }

  /* SCROLLBAR */
  ::-webkit-scrollbar {
    width: 8px;
    height: 8px;
  }

  ::-webkit-scrollbar-thumb {
    background: rgba(148,163,184,.35);
    border-radius: 999px;
  }

  ::-webkit-scrollbar-track {
    background: transparent;
  }

  /* SECTION FADE */
  .fade-section {
    opacity: 0;
    transform: translateY(30px);
    transition:
      opacity .8s ease,
      transform .8s ease;
  }

  .fade-section.show {
    opacity: 1;
    transform: translateY(0);
  }

  /* FLOATING ANIMATION */
  .float-soft {
    animation: floatSoft 5s ease-in-out infinite;
  }

  @keyframes floatSoft {
    0% {
      transform: translateY(0);
    }

    50% {
      transform: translateY(-8px);
    }

    100% {
      transform: translateY(0);
    }
  }
  .reveal-item {
  opacity: 0;
  transform: translateY(40px);
  transition:
    opacity .9s ease,
    transform .9s ease;
  }

  .reveal-item.active {
    opacity: 1;
    transform: translateY(0);
  }

  @media (prefers-reduced-motion: reduce) {
    .home-bg::before,
    .home-bg::after,
    .home-orb,
    .home-particle,
    .intro-shimmer,
    .intro-sparkle,
    .intro-star-svg,
    .category-animate,
    .hero-banner-marquee,
    .animate-featured-scroll,
    .product-card img {
      animation: none !important;
      transition: none !important;
    }
  }
</style>
@endpush

@section('content')
<div class="home-page relative overflow-visible">
  <div class="home-bg" aria-hidden="true">
    <div class="home-orb home-orb-blue"></div>
    <div class="home-orb home-orb-orange"></div>
    <div class="home-particles" data-home-particles></div>
    <div class="home-vignette"></div>
  </div>

  {{-- ═══════════════════════════════════════════════════════════ --}}
  {{-- INTRODUCTION (Above Hero)                                   --}}
  {{-- A short, engaging intro to welcome users and highlight value --}}
  {{-- ═══════════════════════════════════════════════════════════ --}}
  <section class="home-section reveal-item home-stage max-w-7xl mx-auto px-4 py-6">
    <div class="intro-card home-surface rounded-2xl p-6 shadow-lg home-card">
      <div class="intro-shimmer" aria-hidden="true"></div>
      <div class="text-center relative z-10">
        <div class="section-kicker text-amber-300 mb-2 home-chip">Selamat Datang di Lapak Gaming</div>
        <h1 class="home-hero-title text-5xl md:text-6xl font-black tracking-tight leading-[1.05]">Temukan Item, Akun, dan Top-up Game Favoritmu</h1>
        <p class="text-slate-300 text-lg md:text-xl leading-relaxed max-w-3xl mx-auto">Marketplace terpercaya untuk pemain Indonesia — transaksi aman, pengiriman cepat, dan pilihan lengkap untuk semua platform. Temukan penawaran harian, game terbaru, dan seller terpercaya.</p>
        <div class="mt-6 flex justify-center">
        <a href="https://lapakgaming.neoverse.my.id/browse/search"
          class="inline-flex items-center justify-center rounded-2xl bg-gradient-to-r from-blue-500 to-indigo-500 px-6 py-3 text-sm font-semibold text-white transition hover:opacity-95 shadow-[0_14px_28px_rgba(37,99,235,0.22)]">
            Lihat Semua Produk
        </a>
      </div>
      <div class="mt-10 flex flex-wrap justify-center gap-10">
          <div class="text-center">
              <div class="text-3xl font-black text-cyan-300">10K+</div>
              <p class="text-slate-400 text-sm">Produk Digital</p>
          </div>

          <div class="text-center">
              <div class="text-3xl font-black text-emerald-300">99.9%</div>
              <p class="text-slate-400 text-sm">Transaksi Aman</p>
          </div>

          <div class="text-center">
              <div class="text-3xl font-black text-orange-300">24/7</div>
              <p class="text-slate-400 text-sm">Fast Support</p>
          </div>

      </div>
        </div>
      </div>
      <div class="intro-sparkle sm" style="left:6%; top:20%; animation-delay:0s; background:radial-gradient(circle at 30% 30%, #fff, #ffea9a 60%);"></div>
      <div class="intro-sparkle md" style="left:12%; top:62%; animation-delay:0.4s; background:radial-gradient(circle at 30% 30%, #fff, #ffd4a3 60%);"></div>
      <div class="intro-sparkle lg" style="left:22%; top:42%; animation-delay:0.8s; background:radial-gradient(circle at 30% 30%, #fff, #ffd4a3 60%);"></div>
      <div class="intro-sparkle md" style="left:36%; top:18%; animation-delay:1.2s; background:radial-gradient(circle at 30% 30%, #fff, #c7f1ff 60%);"></div>
      <div class="intro-sparkle sm" style="left:48%; top:72%; animation-delay:1.6s; background:radial-gradient(circle at 30% 30%, #fff, #ffd4a3 60%);"></div>
      <div class="intro-sparkle lg" style="left:62%; top:28%; animation-delay:2s; background:radial-gradient(circle at 30% 30%, #fff, #c7f1ff 60%);"></div>
      <div class="intro-sparkle md" style="left:82%; top:68%; animation-delay:2.4s; background:radial-gradient(circle at 30% 30%, #fff, #ffd4a3 60%);"></div>
      <svg class="intro-star-svg" viewBox="0 0 120 120" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
        <g fill="none" stroke="rgba(255,255,255,0.06)" stroke-width="0">
          <circle cx="60" cy="60" r="56" fill="url(#g1)" opacity="0.06"></circle>
        </g>
        <defs>
          <radialGradient id="g1" cx="30%" cy="30%">
            <stop offset="0%" stop-color="#fff" stop-opacity="0.14" />
            <stop offset="100%" stop-color="#fff" stop-opacity="0" />
          </radialGradient>
        </defs>
        <g class="intro-shape-pulse" fill="#fff" opacity="0.9">
          <polygon points="60,14 66,44 98,44 71,60 80,90 60,72 40,90 49,60 22,44 54,44" fill="#fff" opacity="0.08" />
          <polygon points="60,26 64,48 86,48 66,60 74,82 60,68 46,82 54,60 34,48 56,48" fill="#ffd580" opacity="0.12" />
        </g>
      </svg>
    </div>
  </section>

  {{-- ═══════════════════════════════════════════════════════════ --}}
  {{-- HERO SECTION (BANNERS)                                     --}}
  {{-- ═══════════════════════════════════════════════════════════ --}}
  <section class="home-section reveal-item home-stage relative overflow-visible pb-6">
    <div class="max-w-7xl mx-auto px-4 pt-3">
      <div class="absolute -top-24 -left-20 w-[420px] h-[420px] rounded-full bg-cyan-500/20 blur-[120px] pointer-events-none"></div>
<div class="absolute -bottom-32 -right-20 w-[420px] h-[420px] rounded-full bg-orange-500/10 blur-[120px] pointer-events-none"></div>
      <div class="relative z-20 rounded-[32px] bg-[#081125]/95 p-6 shadow-[0_30px_90px_rgba(5,12,35,0.28),0_0_0_1px_rgba(255,255,255,0.03)]">
        {{-- Header with interactive robot --}}
        <div class="flex flex-col gap-6">
          {{-- Left & Right text with Robot in center --}}
          <div class="flex flex-col lg:flex-row items-center justify-between gap-4">
            {{-- Left Text --}}
            <div class="hidden lg:flex flex-col justify-center text-left space-y-2 flex-1">
              <div class="robot-text">
                <span class="text-blue-300 font-bold">⚡ Lightning</span>
                <span class="text-slate-400 text-xs">Fast Delivery</span>
              </div>
            </div>
            {{-- Robot removed per request --}}
            {{-- Right Text --}}
            <div class="hidden lg:flex flex-col justify-center text-right space-y-2 flex-1">
              <div class="robot-text">
                <span class="text-blue-300 font-bold">🔒 Secure</span>
                <span class="text-slate-400 text-xs">100% Safe</span>
              </div>
            </div>
          </div>
          {{-- Action Buttons --}}
          <div class="flex flex-wrap gap-3 justify-center lg:justify-start">
            <a href="{{ route('marketplace.trending') }}" class="inline-flex items-center justify-center rounded-2xl border border-white/10 bg-white/5 px-5 py-3 text-sm font-semibold text-slate-100 transition hover:bg-white/10">Lihat Trending</a>
          </div>
        </div>

        <div class="mt-6">
          @if(isset($heroBanners) && $heroBanners->count())
            @if($heroBanners->count() > 4)
              @php
                $heroLoopBanners = $heroBanners->concat($heroBanners);
              @endphp
              <div class="hero-banner-scroll rounded-[28px]">
                <div class="hero-banner-track hero-banner-marquee py-1">
                  @foreach($heroLoopBanners as $banner)
                    <a href="{{ $banner->link_url ?: '#' }}" class="group home-card relative flex-none w-[280px] sm:w-[310px] md:w-[340px] lg:w-[360px] overflow-hidden rounded-[28px] border border-white/10 bg-slate-950/80 shadow-[0_25px_60px_rgba(3,8,32,0.35)] transition-transform duration-300 hover:-translate-y-1 hover:shadow-[0_32px_90px_rgba(3,8,32,0.45)] aspect-[4/5]">
                      <img src="{{ $banner->image_url }}" alt="{{ $banner->title ?? 'Banner Promo' }}" class="absolute inset-0 w-full h-full object-cover transition duration-500 group-hover:scale-105" />
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
                </div>
              </div>
            @else
              <div class="grid gap-4 lg:grid-cols-4">
                @foreach($heroBanners as $banner)
                  <a href="{{ $banner->link_url ?: '#' }}" class="group home-card relative overflow-hidden rounded-[28px] border border-white/10 bg-slate-950/80 shadow-[0_25px_60px_rgba(3,8,32,0.35)] transition-transform duration-300 hover:-translate-y-1 hover:shadow-[0_32px_90px_rgba(3,8,32,0.45)] aspect-[4/5]">
                    <img src="{{ $banner->image_url }}" alt="{{ $banner->title ?? 'Banner Promo' }}" class="absolute inset-0 w-full h-full object-cover transition duration-500 group-hover:scale-105" />
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
              </div>
            @endif
          @else
            <div class="rounded-[28px] border border-white/10 bg-slate-950/80 p-10 text-center">
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
<section class="home-section reveal-item trust-badge-container py-3 shadow-md mb-8">
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
<section class="home-section reveal-item home-stage max-w-7xl mx-auto px-4 mb-10">
  <div class="category-panel rounded-2xl p-5 sm:p-6 shadow-sm">
    <div class="category-heading">
      <div class="category-heading-title">
        <div class="category-heading-badge">Browse cepat</div>
        <h2 class="section-title mb-0">Kategori Produk</h2>
        <p>Pilih kategori favoritmu untuk langsung masuk ke produk yang relevan.</p>
      </div>
      <div class="text-xs font-semibold text-slate-400 hidden sm:block">13 kategori utama</div>
    </div>
    <div class="flex flex-wrap justify-center gap-6 items-center category-list relative z-10">
      @php $catsToShow = $allCategories->isNotEmpty() ? $allCategories : $displayCategories; @endphp
      @foreach($catsToShow->take(13) as $cat)
        <a href="{{ route('categories.show', $cat->slug) }}" class="category-icon-wrapper block w-20 sm:w-24 text-center" aria-label="Kategori {{ $cat->name }}">
          <div class="w-16 h-16 sm:w-20 sm:h-20 mx-auto rounded-full bg-surface-weak border border-white/6 flex items-center justify-center mb-2 category-icon category-animate ring-1 ring-white/5 shadow-[0_10px_30px_rgba(2,6,23,0.18)]">
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
<section class="home-section reveal-item home-stage max-w-7xl mx-auto px-4 mb-10">
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
<section class="home-section reveal-item home-stage max-w-7xl mx-auto px-4 mb-10">
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
  <section class="home-section reveal-item home-stage max-w-7xl mx-auto px-4 mb-10">
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
@if((!isset($categorySections) || $categorySections->isEmpty()) && isset($homepageProducts) && $homepageProducts->count())
  <section class="reveal-item max-w-7xl mx-auto px-4 mb-10">
    <div class="flex items-center justify-between mb-4">
      <h2 class="section-title mb-0">Produk Terbaru & Rekomendasi</h2>
      <a href="{{ route('products.search') }}" class="text-sm font-semibold text-itemku-blue hover:underline">Lihat Semua</a>
    </div>
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
      @foreach($homepageProducts as $p)
        @include('components.product-card', ['product' => $p])
      @endforeach
    </div>
  </section>
@endif
@if(isset($featuredBanners) && $featuredBanners->count())
  <section class="reveal-item max-w-7xl mx-auto px-4 mb-10">
    <div class="featured-stage rounded-2xl overflow-hidden p-3 md:p-4">
      <div class="featured-scroll overflow-hidden">
        <div class="flex gap-3 md:gap-4 animate-featured-scroll will-change-transform">
          @foreach($featuredBanners as $fb)
            <a href="{{ $fb->link_url ?: '#' }}" class="flex-none w-[360px] md:w-[480px] rounded-xl overflow-hidden block aspect-[3/1] border border-white/10 shadow-md">
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

  const homeBg = document.querySelector('.home-bg');
  const particleRoot = document.querySelector('[data-home-particles]');

  if (particleRoot) {
    const totalParticles = 26;
    for (let i = 0; i < totalParticles; i += 1) {
      const dot = document.createElement('span');
      dot.className = 'home-particle';
      const left = Math.random() * 100;
      const top = Math.random() * 100;
      const duration = 12 + Math.random() * 10;
      const drift = (Math.random() * 2 - 1) * 1.1;
      const opacity = 0.18 + Math.random() * 0.32;
      dot.style.left = `${left}%`;
      dot.style.top = `${top}%`;
      dot.style.setProperty('--dur', `${duration}s`);
      dot.style.setProperty('--drift', drift.toFixed(2));
      dot.style.setProperty('--opacity', opacity.toFixed(2));
      particleRoot.appendChild(dot);
    }
  }

  window.addEventListener('mousemove', (event) => {
    if (!homeBg) return;
    const x = (event.clientX / window.innerWidth - 0.5) * 18;
    const y = (event.clientY / window.innerHeight - 0.5) * 12;
    homeBg.style.transform = `translate(${x}px, ${y}px)`;
  });

  // Featured banners: auto-scroll animation retained (single-row, no duplication)

    /* PREMIUM NAVBAR */
    const navbar = document.getElementById('main-navbar');

    window.addEventListener('scroll', () => {

      if (window.scrollY > 10) {

        navbar.style.background =
          'rgba(2,6,23,.78)';

        navbar.style.backdropFilter =
          'blur(26px)';

        navbar.style.boxShadow =
          '0 10px 35px rgba(0,0,0,.35)';

      } else {

        navbar.style.background = '';
        navbar.style.boxShadow = '';

      }

    });

    /* CARD MAGNET EFFECT */
    document.querySelectorAll('.product-card').forEach((card) => {

      card.addEventListener('mousemove', (e) => {

        const rect = card.getBoundingClientRect();

        const x = e.clientX - rect.left;
        const y = e.clientY - rect.top;

        const rotateY =
          ((x / rect.width) - 0.5) * 6;

        const rotateX =
          ((y / rect.height) - 0.5) * -6;

        card.style.transform =
          `
          perspective(1000px)
          rotateX(${rotateX}deg)
          rotateY(${rotateY}deg)
          translateY(-8px)
          `;
      });

      card.addEventListener('mouseleave', () => {

        card.style.transform =
          'perspective(1000px) rotateX(0) rotateY(0)';
      });

    });
    const revealItems = document.querySelectorAll('.reveal-item');

    const revealObserver = new IntersectionObserver((entries) => {

      entries.forEach((entry) => {

        if (entry.isIntersecting) {

          entry.target.classList.add('active');

        }

      });

    }, {
      threshold: 0.12
    });

    revealItems.forEach((item) => {
      revealObserver.observe(item);
    });
</script>
@endpush
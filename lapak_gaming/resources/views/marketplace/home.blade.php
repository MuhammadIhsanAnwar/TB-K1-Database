@extends('layouts.app')
@section('title', 'Lapak Gaming — Marketplace Game Terpercaya Indonesia')

@push('styles')
<style>
/* ===================================================================
   LAPAK GAMING · HOMEPAGE PREMIUM REDESIGN v2.0
   Aesthetic: Gaming × Fintech · Codashop × UniPin × Steam Dark UI
   =================================================================== */

/* ── PAGE SHELL ──────────────────────────────────────────────────── */
.home-page { position: relative; overflow-x: hidden; }

/* ── AMBIENT ORBS ────────────────────────────────────────────────── */
.amb-orb {
  position: absolute;
  border-radius: 999px;
  pointer-events: none;
  will-change: transform;
  filter: blur(100px);
}
.amb-orb-1 {
  width: 700px; height: 700px;
  left: -220px; top: -120px;
  background: radial-gradient(circle, rgba(37,99,235,0.16) 0%, transparent 70%);
  animation: ambFloat1 22s ease-in-out infinite;
}
.amb-orb-2 {
  width: 550px; height: 550px;
  right: -160px; top: 180px;
  background: radial-gradient(circle, rgba(249,115,22,0.11) 0%, transparent 70%);
  animation: ambFloat2 28s ease-in-out infinite;
}
.amb-orb-3 {
  width: 450px; height: 450px;
  left: 35%; bottom: 80px;
  background: radial-gradient(circle, rgba(14,165,233,0.08) 0%, transparent 70%);
  animation: ambFloat3 19s ease-in-out infinite;
}
@keyframes ambFloat1 {
  0%,100% { transform: translate(0, 0) scale(1); }
  33%     { transform: translate(35px, -50px) scale(1.06); }
  66%     { transform: translate(-25px, 25px) scale(0.96); }
}
@keyframes ambFloat2 {
  0%,100% { transform: translate(0, 0) scale(1); }
  50%     { transform: translate(-45px, 35px) scale(1.09); }
}
@keyframes ambFloat3 {
  0%,100% { transform: translate(0, 0); }
  50%     { transform: translate(25px, -35px); }
}

/* ── GRID OVERLAY ────────────────────────────────────────────────── */
.home-grid-bg {
  position: absolute;
  inset: 0;
  background-image:
    linear-gradient(rgba(37,99,235,0.035) 1px, transparent 1px),
    linear-gradient(90deg, rgba(37,99,235,0.035) 1px, transparent 1px);
  background-size: 64px 64px;
  mask-image: linear-gradient(180deg, rgba(0,0,0,0.65) 0%, transparent 55%);
  pointer-events: none;
  z-index: 0;
}

/* ── HERO ────────────────────────────────────────────────────────── */
.hero-section {
  position: relative;
  z-index: 1;
  min-height: 86vh;
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  text-align: center;
  padding: 6.5rem 1rem 4rem;
  overflow: hidden;
}

.hero-section::before {
  content: '';
  position: absolute;
  inset: 0;
  background:
    radial-gradient(ellipse 90% 65% at 50% 0%, rgba(37,99,235,0.18) 0%, transparent 60%),
    radial-gradient(ellipse 55% 45% at 85% 85%, rgba(249,115,22,0.09) 0%, transparent 55%);
  pointer-events: none;
}

/* Eyebrow badge */
.hero-eyebrow {
  display: inline-flex;
  align-items: center;
  gap: 0.5rem;
  padding: 0.4rem 1.1rem;
  border-radius: 999px;
  border: 1px solid rgba(96,165,250,0.25);
  background: rgba(37,99,235,0.09);
  backdrop-filter: blur(12px);
  font-size: 0.72rem;
  font-weight: 700;
  letter-spacing: 0.16em;
  text-transform: uppercase;
  color: #93c5fd;
  margin-bottom: 1.75rem;
  animation: hFadeDown 0.7s ease both;
}
.hero-eyebrow-dot {
  width: 7px; height: 7px;
  border-radius: 50%;
  background: #60a5fa;
  animation: dotPulse 2.2s ease-in-out infinite;
  box-shadow: 0 0 8px rgba(96,165,250,0.6);
}
@keyframes dotPulse {
  0%,100% { opacity:1; transform:scale(1); box-shadow: 0 0 8px rgba(96,165,250,0.6); }
  50%     { opacity:0.5; transform:scale(0.7); box-shadow: 0 0 3px rgba(96,165,250,0.2); }
}

/* Title */
.hero-title {
  font-family: 'Oxanium', sans-serif;
  font-size: clamp(2.4rem, 6.5vw, 4.8rem);
  font-weight: 900;
  line-height: 1.05;
  letter-spacing: -0.025em;
  color: #fff;
  text-wrap: balance;
  margin-bottom: 1.5rem;
  animation: hFadeDown 0.75s 0.08s ease both;
}
.hero-title .grad {
  background: linear-gradient(135deg, #60a5fa 0%, #a78bfa 42%, #fb923c 100%);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  background-clip: text;
}

/* Sub */
.hero-sub {
  font-size: clamp(0.95rem, 2vw, 1.15rem);
  color: #94a3b8;
  max-width: 580px;
  margin: 0 auto 2.75rem;
  line-height: 1.75;
  animation: hFadeDown 0.75s 0.16s ease both;
}

/* CTA row */
.hero-cta {
  display: flex;
  flex-wrap: wrap;
  gap: 0.875rem;
  justify-content: center;
  margin-bottom: 3.75rem;
  animation: hFadeDown 0.75s 0.24s ease both;
}

.btn-cta-primary {
  display: inline-flex; align-items: center; gap: 0.5rem;
  padding: 0.95rem 2.1rem;
  border-radius: 14px;
  font-family: 'Oxanium', sans-serif; font-weight: 700; font-size: 0.95rem;
  color: #fff;
  background: linear-gradient(135deg, #2563eb, #1d4ed8);
  border: 1px solid rgba(59,130,246,0.3);
  box-shadow: 0 0 32px rgba(37,99,235,0.38), 0 8px 22px rgba(0,0,0,0.32);
  text-decoration: none;
  transition: transform 0.3s cubic-bezier(0.2,0.8,0.2,1), box-shadow 0.3s ease;
  position: relative; overflow: hidden;
}
.btn-cta-primary::before {
  content: '';
  position: absolute; inset: 0;
  background: linear-gradient(135deg, rgba(255,255,255,0.12), transparent);
  opacity: 0; transition: opacity 0.3s;
}
.btn-cta-primary:hover {
  transform: translateY(-3px);
  box-shadow: 0 0 55px rgba(37,99,235,0.5), 0 16px 38px rgba(0,0,0,0.42);
}
.btn-cta-primary:hover::before { opacity: 1; }

.btn-cta-secondary {
  display: inline-flex; align-items: center; gap: 0.5rem;
  padding: 0.95rem 2.1rem;
  border-radius: 14px;
  font-family: 'Oxanium', sans-serif; font-weight: 700; font-size: 0.95rem;
  color: #cbd5e1;
  background: rgba(255,255,255,0.04);
  border: 1px solid rgba(255,255,255,0.1);
  backdrop-filter: blur(12px);
  text-decoration: none;
  transition: all 0.3s ease;
}
.btn-cta-secondary:hover {
  background: rgba(255,255,255,0.09);
  border-color: rgba(255,255,255,0.2);
  color: #fff;
  transform: translateY(-2px);
}

/* Stats grid */
.hero-stats {
  display: grid;
  grid-template-columns: repeat(4, 1fr);
  gap: 0.875rem;
  width: 100%;
  max-width: 660px;
  animation: hFadeDown 0.75s 0.32s ease both;
}
@media (max-width: 500px) {
  .hero-stats { grid-template-columns: repeat(2, 1fr); }
}

.hero-stat {
  background: rgba(255,255,255,0.035);
  border: 1px solid rgba(255,255,255,0.07);
  border-radius: 18px;
  padding: 1.1rem 0.75rem;
  backdrop-filter: blur(12px);
  text-align: center;
  transition: all 0.3s ease;
}
.hero-stat:hover {
  background: rgba(255,255,255,0.065);
  border-color: rgba(96,165,250,0.2);
  transform: translateY(-3px);
  box-shadow: 0 12px 32px rgba(0,0,0,0.25);
}
.hero-stat-num {
  font-family: 'Oxanium', sans-serif;
  font-size: 1.65rem;
  font-weight: 900;
  line-height: 1;
  margin-bottom: 0.3rem;
}
.hero-stat-lbl {
  font-size: 0.68rem;
  font-weight: 600;
  text-transform: uppercase;
  letter-spacing: 0.09em;
  color: #475569;
}

/* ── ANIMATIONS ──────────────────────────────────────────────────── */
@keyframes hFadeDown {
  from { opacity: 0; transform: translateY(-18px); }
  to   { opacity: 1; transform: translateY(0); }
}

/* ── SCROLL REVEAL ───────────────────────────────────────────────── */
.reveal-item {
  opacity: 0;
  transform: translateY(34px);
  transition: opacity 0.85s ease, transform 0.85s cubic-bezier(0.2,0.8,0.2,1);
}
.reveal-item.active {
  opacity: 1;
  transform: translateY(0);
}
.reveal-d1 { transition-delay: 0.1s; }
.reveal-d2 { transition-delay: 0.2s; }
.reveal-d3 { transition-delay: 0.3s; }

/* ── TRUST STRIP ─────────────────────────────────────────────────── */
.trust-strip {
  background: linear-gradient(90deg,
    rgba(37,99,235,0.08) 0%,
    rgba(14,165,233,0.06) 50%,
    rgba(37,99,235,0.08) 100%);
  border-top: 1px solid rgba(37,99,235,0.12);
  border-bottom: 1px solid rgba(37,99,235,0.12);
  overflow: hidden;
  padding: 0.8rem 0;
}
.trust-inner {
  display: flex;
  gap: 3.5rem;
  align-items: center;
  width: max-content;
  animation: trustScroll 28s linear infinite;
}
.trust-inner:hover { animation-play-state: paused; }
@keyframes trustScroll {
  from { transform: translateX(0); }
  to   { transform: translateX(-50%); }
}
.trust-item {
  display: inline-flex;
  align-items: center;
  gap: 0.6rem;
  white-space: nowrap;
  font-size: 0.84rem;
  font-weight: 600;
  color: #94a3b8;
}
.trust-icon {
  width: 30px; height: 30px;
  border-radius: 9px;
  display: flex; align-items: center; justify-content: center;
  flex-shrink: 0;
}
.trust-sep {
  width: 1px; height: 18px;
  background: rgba(255,255,255,0.08);
  flex-shrink: 0;
}

/* ── SECTION LAYOUT ──────────────────────────────────────────────── */
.home-wrap {
  max-width: 1280px;
  margin: 0 auto;
  padding: 0 1rem;
}
.sec-gap { padding: 3.5rem 0; }
.sec-gap-sm { padding: 0 0 3rem; }

.sec-header {
  display: flex;
  align-items: flex-end;
  justify-content: space-between;
  gap: 1rem;
  margin-bottom: 2rem;
}
.sec-kicker {
  display: inline-flex;
  align-items: center;
  gap: 0.4rem;
  font-size: 0.7rem;
  font-weight: 800;
  letter-spacing: 0.18em;
  text-transform: uppercase;
  color: #60a5fa;
  margin-bottom: 0.45rem;
}
.sec-title {
  font-family: 'Oxanium', sans-serif;
  font-size: clamp(1.25rem, 3vw, 1.8rem);
  font-weight: 800;
  color: #f1f5f9;
  letter-spacing: -0.01em;
  margin: 0;
  line-height: 1.2;
}
.sec-sub {
  margin-top: 0.3rem;
  font-size: 0.85rem;
  color: #475569;
}
.sec-all {
  display: inline-flex;
  align-items: center;
  gap: 0.4rem;
  font-size: 0.8rem;
  font-weight: 600;
  color: #60a5fa;
  text-decoration: none;
  padding: 0.4rem 1.1rem;
  border-radius: 999px;
  border: 1px solid rgba(96,165,250,0.18);
  transition: all 0.25s ease;
  white-space: nowrap;
  flex-shrink: 0;
}
.sec-all:hover {
  background: rgba(96,165,250,0.08);
  border-color: rgba(96,165,250,0.38);
  color: #93c5fd;
  transform: translateX(2px);
}

/* ── HERO BANNER CARDS ───────────────────────────────────────────── */
.banner-section { position: relative; z-index: 1; }
.banner-marquee-wrap { overflow: hidden; border-radius: 28px; }
.banner-marquee-track {
  display: flex;
  gap: 1rem;
  width: max-content;
  animation: bMarquee 32s linear infinite;
}
.banner-marquee-track:hover { animation-play-state: paused; }
@keyframes bMarquee {
  from { transform: translateX(0); }
  to   { transform: translateX(-50%); }
}
.banner-card {
  position: relative;
  flex-shrink: 0;
  border-radius: 24px;
  overflow: hidden;
  border: 1px solid rgba(255,255,255,0.08);
  box-shadow: 0 22px 55px rgba(0,0,0,0.38);
  transition: transform 0.38s cubic-bezier(0.2,0.8,0.2,1), box-shadow 0.38s ease;
}
.banner-card:hover {
  transform: translateY(-7px) scale(1.015);
  box-shadow: 0 32px 75px rgba(0,0,0,0.5), 0 0 0 1px rgba(96,165,250,0.12);
}

/* ── CATEGORIES ──────────────────────────────────────────────────── */
.cat-panel {
  background: linear-gradient(145deg, rgba(11,17,35,0.96), rgba(7,12,24,0.98));
  border: 1px solid rgba(255,255,255,0.05);
  border-radius: 28px;
  padding: 2rem 2rem 2.25rem;
  box-shadow: 0 24px 65px rgba(0,0,0,0.32);
  backdrop-filter: blur(22px);
  position: relative;
  overflow: hidden;
}
.cat-panel::before {
  content: '';
  position: absolute; inset: 0;
  background:
    radial-gradient(circle at 10% 5%, rgba(37,99,235,0.09), transparent 38%),
    radial-gradient(circle at 90% 90%, rgba(249,115,22,0.07), transparent 38%);
  pointer-events: none;
}

.cat-item {
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 0.65rem;
  text-decoration: none;
  cursor: pointer;
  transition: transform 0.28s cubic-bezier(0.2,0.8,0.2,1);
}
.cat-item:hover { transform: translateY(-7px); }

.cat-icon-box {
  width: 68px; height: 68px;
  border-radius: 20px;
  display: flex; align-items: center; justify-content: center;
  border: 1px solid rgba(255,255,255,0.06);
  background: linear-gradient(145deg, rgba(255,255,255,0.04), rgba(255,255,255,0.01));
  backdrop-filter: blur(8px);
  box-shadow: 0 8px 24px rgba(0,0,0,0.22);
  overflow: hidden;
  position: relative;
  transition: all 0.3s ease;
}
.cat-icon-box::after {
  content: '';
  position: absolute; inset: 0;
  background: linear-gradient(135deg, rgba(255,255,255,0.08), transparent 50%);
  opacity: 0;
  transition: opacity 0.3s;
  border-radius: inherit;
}
.cat-item:hover .cat-icon-box {
  border-color: rgba(96,165,250,0.28);
  box-shadow: 0 14px 38px rgba(37,99,235,0.2), 0 0 0 1px rgba(96,165,250,0.12);
  background: linear-gradient(145deg, rgba(37,99,235,0.09), rgba(255,255,255,0.02));
}
.cat-item:hover .cat-icon-box::after { opacity: 1; }

.cat-lbl {
  font-size: 0.71rem;
  font-weight: 600;
  color: #64748b;
  text-align: center;
  line-height: 1.3;
  max-width: 76px;
  transition: color 0.25s;
}
.cat-item:hover .cat-lbl { color: #e2e8f0; }

/* Floating anim for category icons */
@keyframes catBob {
  0%,100% { transform: translateY(0); }
  50%     { transform: translateY(-5px); }
}

/* ── PRODUCT CARDS ───────────────────────────────────────────────── */
.product-card {
  backdrop-filter: blur(12px);
  transition:
    transform 0.38s cubic-bezier(0.2,0.8,0.2,1),
    box-shadow 0.38s ease,
    border-color 0.28s ease;
}
.product-card:hover {
  transform: translateY(-9px) scale(1.01);
  box-shadow: 0 32px 75px rgba(14,165,233,0.18), 0 10px 30px rgba(0,0,0,0.48);
  border-color: rgba(14,165,233,0.22) !important;
}
.product-card img {
  transition: transform 0.65s cubic-bezier(0.2,0.8,0.2,1), filter 0.3s;
}
.product-card:hover img {
  transform: scale(1.09);
  filter: brightness(1.06);
}

/* ── HORIZONTAL SCROLL ───────────────────────────────────────────── */
.banner-track { scrollbar-width: none; -ms-overflow-style: none; }
.banner-track::-webkit-scrollbar { display: none; }

/* ── FEATURED BANNERS MARQUEE ────────────────────────────────────── */
.feat-scroll-inner {
  display: flex;
  gap: 1rem;
  flex-wrap: nowrap;
  align-items: center;
  width: max-content;
  animation: featScroll 22s linear infinite;
}
.feat-scroll-inner:hover { animation-play-state: paused; }
@keyframes featScroll {
  from { transform: translateX(0); }
  to   { transform: translateX(-50%); }
}
.feat-panel {
  background: linear-gradient(135deg, rgba(5,10,25,0.98), rgba(10,20,40,0.95));
  border: 1px solid rgba(255,255,255,0.06);
  border-radius: 24px;
  overflow: hidden;
}

/* ── GUEST CTA SECTION ───────────────────────────────────────────── */
.cta-panel {
  position: relative;
  overflow: hidden;
  background:
    linear-gradient(135deg, rgba(28,54,130,0.58) 0%, rgba(18,30,72,0.82) 50%, rgba(28,54,130,0.58) 100%);
  border: 1px solid rgba(37,99,235,0.22);
  border-radius: 28px;
  padding: 4rem 2rem;
  text-align: center;
}
.cta-panel::before {
  content: '';
  position: absolute; inset: 0;
  background: radial-gradient(ellipse 75% 65% at 50% 50%, rgba(37,99,235,0.14), transparent 70%);
  pointer-events: none;
}
.cta-panel::after {
  content: '';
  position: absolute;
  width: 300px; height: 300px;
  border-radius: 50%;
  top: -100px; right: -80px;
  background: rgba(249,115,22,0.06);
  filter: blur(60px);
  pointer-events: none;
}

/* ── SCROLLBAR ───────────────────────────────────────────────────── */
::-webkit-scrollbar { width: 6px; height: 6px; }
::-webkit-scrollbar-track { background: transparent; }
::-webkit-scrollbar-thumb { background: rgba(37,99,235,0.35); border-radius: 999px; }
::-webkit-scrollbar-thumb:hover { background: rgba(37,99,235,0.55); }

/* ── REDUCED MOTION ──────────────────────────────────────────────── */
@media (prefers-reduced-motion: reduce) {
  .amb-orb, .banner-marquee-track, .trust-inner,
  .feat-scroll-inner, .product-card img {
    animation: none !important;
    transition: none !important;
  }
  .reveal-item { opacity: 1; transform: none; }
}

/* ── MOBILE TWEAKS ───────────────────────────────────────────────── */
@media (max-width: 640px) {
  .sec-header { flex-direction: column; align-items: flex-start; }
  .hero-section { padding-top: 5rem; }
  .cat-icon-box { width: 58px; height: 58px; border-radius: 16px; }
}
</style>
@endpush

@section('content')
<div class="home-page">

  {{-- ── DECORATIVE BACKGROUND ─────────────────────────────────── --}}
  <div class="amb-orb amb-orb-1" aria-hidden="true"></div>
  <div class="amb-orb amb-orb-2" aria-hidden="true"></div>
  <div class="amb-orb amb-orb-3" aria-hidden="true"></div>
  <div class="home-grid-bg" aria-hidden="true"></div>

  {{-- ═══════════════════════════════════════════════════════════ --}}
  {{-- HERO SECTION                                               --}}
  {{-- ═══════════════════════════════════════════════════════════ --}}
  <section class="hero-section">
    <div class="hero-eyebrow">
      <span class="hero-eyebrow-dot"></span>
      Marketplace Gaming Terpercaya #1 Indonesia
    </div>

    <h1 class="hero-title">
      Top Up &amp; Beli Item Game<br>
      <span class="grad">Mudah, Aman, Instan</span>
    </h1>

    <p class="hero-sub">
      Temukan ribuan produk digital — top up, akun, voucher, dan item game dari seller terpercaya.
      Transaksi aman dengan pengiriman cepat.
    </p>

    <div class="hero-cta">
      <a href="{{ route('products.search') }}" class="btn-cta-primary">
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
        </svg>
        Jelajahi Produk
      </a>
      <a href="{{ route('marketplace.trending') }}" class="btn-cta-secondary">
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
        </svg>
        Lihat Trending
      </a>
    </div>

    {{-- Stats counters --}}
    <div class="hero-stats">
      <div class="hero-stat">
        <div class="hero-stat-num text-cyan-300 counter" data-target="{{ $activeAccountCount }}">0</div>
        <div class="hero-stat-lbl">Akun Aktif</div>
      </div>
      <div class="hero-stat">
        <div class="hero-stat-num text-emerald-300 counter" data-target="{{ $activeProductCount }}">0</div>
        <div class="hero-stat-lbl">Produk Aktif</div>
      </div>
      <div class="hero-stat">
        <div class="hero-stat-num text-amber-300 counter" data-target="{{ $verifiedSellerCount }}">0</div>
        <div class="hero-stat-lbl">Seller Verified</div>
      </div>
      <div class="hero-stat">
        <div class="hero-stat-num text-indigo-300 counter" data-target="{{ $transactionCount }}">0</div>
        <div class="hero-stat-lbl">Transaksi</div>
      </div>
    </div>
  </section>

  {{-- ═══════════════════════════════════════════════════════════ --}}
  {{-- HERO BANNERS                                               --}}
  {{-- ═══════════════════════════════════════════════════════════ --}}
  @if(isset($heroBanners) && $heroBanners->count())
  <section class="reveal-item banner-section sec-gap-sm">
    <div class="home-wrap">
      @if($heroBanners->count() > 4)
        @php $heroLoopBanners = $heroBanners->concat($heroBanners); @endphp
        <div class="banner-marquee-wrap">
          <div class="banner-marquee-track py-2">
            @foreach($heroLoopBanners as $banner)
              <a href="{{ $banner->link_url ?: '#' }}"
                 class="banner-card group flex-none w-[270px] sm:w-[315px] md:w-[355px] aspect-[4/5]">
                <img src="{{ $banner->image_url }}"
                     alt="{{ $banner->title ?? 'Banner Promo' }}"
                     class="absolute inset-0 w-full h-full object-cover transition duration-500 group-hover:scale-106">
                <div class="absolute inset-0 bg-gradient-to-t from-slate-950/92 via-slate-950/28 to-transparent"></div>
                <div class="absolute inset-x-0 bottom-0 p-5">
                  <span class="inline-flex rounded-full bg-amber-500/15 px-3 py-1 text-[10px] font-bold uppercase tracking-[0.22em] text-amber-300 mb-3">Hot Games</span>
                  <h3 class="text-lg font-extrabold text-white line-clamp-2 leading-tight">{{ $banner->title ?? 'Promo Spesial' }}</h3>
                  @if(!empty($banner->subtitle))
                    <p class="mt-1.5 text-sm text-slate-300 line-clamp-2">{{ $banner->subtitle }}</p>
                  @endif
                  <div class="mt-4 inline-flex items-center gap-2 rounded-full border border-white/12 bg-white/8 backdrop-blur-sm px-4 py-2 text-xs font-bold text-white">
                    Explore
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                    </svg>
                  </div>
                </div>
              </a>
            @endforeach
          </div>
        </div>
      @else
        <div class="grid gap-4 grid-cols-2 lg:grid-cols-4">
          @foreach($heroBanners as $banner)
            <a href="{{ $banner->link_url ?: '#' }}" class="banner-card group relative aspect-[4/5]">
              <img src="{{ $banner->image_url }}"
                   alt="{{ $banner->title ?? 'Banner Promo' }}"
                   class="absolute inset-0 w-full h-full object-cover transition duration-500 group-hover:scale-106">
              <div class="absolute inset-0 bg-gradient-to-t from-slate-950/92 via-slate-950/28 to-transparent"></div>
              <div class="absolute inset-x-0 bottom-0 p-4">
                <span class="inline-flex rounded-full bg-amber-500/15 px-2.5 py-0.5 text-[9px] font-bold uppercase tracking-[0.2em] text-amber-300 mb-2">Hot Games</span>
                <h3 class="text-sm font-extrabold text-white line-clamp-2">{{ $banner->title ?? 'Promo Spesial' }}</h3>
                @if(!empty($banner->subtitle))
                  <p class="mt-1 text-xs text-slate-300 line-clamp-2">{{ $banner->subtitle }}</p>
                @endif
                <div class="mt-3 inline-flex items-center gap-1.5 rounded-full border border-white/10 bg-white/8 px-3 py-1.5 text-[10px] font-bold text-white">
                  Explore
                  <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                  </svg>
                </div>
              </div>
            </a>
          @endforeach
        </div>
      @endif
    </div>
  </section>
  @else
    {{-- Empty state --}}
    <div class="home-wrap sec-gap-sm">
      <div class="rounded-[24px] border border-white/6 bg-slate-950/60 p-10 text-center">
        <p class="text-sm text-slate-500">Belum ada hero banner tersedia. Tambahkan dari panel admin.</p>
      </div>
    </div>
  @endif

  {{-- ═══════════════════════════════════════════════════════════ --}}
  {{-- TRUST STRIP                                                --}}
  {{-- ═══════════════════════════════════════════════════════════ --}}
  <div class="trust-strip reveal-item">
    <div class="trust-inner px-12">
      @foreach(range(1,2) as $r)
        <div class="trust-item">
          <div class="trust-icon bg-emerald-500/10">
            <svg class="w-4 h-4 text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
            </svg>
          </div>
          Transaksi 100% Aman
        </div>
        <div class="trust-sep"></div>
        <div class="trust-item">
          <div class="trust-icon bg-amber-500/10">
            <svg class="w-4 h-4 text-amber-400" fill="currentColor" viewBox="0 0 20 20">
              <path fill-rule="evenodd" d="M4 2a1 1 0 011 1v2.101a7.002 7.002 0 0111.601 2.566 1 1 0 11-1.885.666A5.002 5.002 0 005.999 7H9a1 1 0 010 2H4a1 1 0 01-1-1V3a1 1 0 011-1zm.008 9.057a1 1 0 011.276.61A5.002 5.002 0 0014.001 13H11a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0v-2.101a7.002 7.002 0 01-11.601-2.566 1 1 0 01.61-1.276z" clip-rule="evenodd"/>
            </svg>
          </div>
          Garansi Uang Kembali
        </div>
        <div class="trust-sep"></div>
        <div class="trust-item">
          <div class="trust-icon bg-blue-500/10">
            <svg class="w-4 h-4 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
              <path fill-rule="evenodd" d="M18 10c0 3.866-3.582 7-8 7a8.841 8.841 0 01-4.083-.98L2 17l1.338-3.123C2.493 12.767 2 11.434 2 10c0-3.866 3.582-7 8-7s8 3.134 8 7zM7 9H5v2h2V9zm8 0h-2v2h2V9zM9 9h2v2H9V9z" clip-rule="evenodd"/>
            </svg>
          </div>
          Layanan CS 24/7
        </div>
        <div class="trust-sep"></div>
        <div class="trust-item">
          <div class="trust-icon bg-purple-500/10">
            <svg class="w-4 h-4 text-purple-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
            </svg>
          </div>
          Pengiriman Instan
        </div>
        <div class="trust-sep"></div>
        <div class="trust-item">
          <div class="trust-icon bg-rose-500/10">
            <svg class="w-4 h-4 text-rose-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
          </div>
          Harga Kompetitif
        </div>
        <div class="trust-sep"></div>
        <div class="trust-item">
          <div class="trust-icon bg-cyan-500/10">
            <svg class="w-4 h-4 text-cyan-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
            </svg>
          </div>
          Seller Terverifikasi
        </div>
        <div class="trust-sep"></div>
      @endforeach
    </div>
  </div>

  {{-- ═══════════════════════════════════════════════════════════ --}}
  {{-- CATEGORIES                                                 --}}
  {{-- ═══════════════════════════════════════════════════════════ --}}
  <section class="reveal-item home-wrap sec-gap">
    <div class="cat-panel">
      <div class="sec-header" style="position:relative;z-index:1;">
        <div>
          <div class="sec-kicker">
            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
              <path d="M5 3a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2V5a2 2 0 00-2-2H5zM5 11a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2v-2a2 2 0 00-2-2H5zM11 5a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V5zM11 13a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
            </svg>
            Browse Cepat
          </div>
          <h2 class="sec-title">Kategori Produk</h2>
          <p class="sec-sub">Pilih kategori favoritmu untuk langsung masuk ke produk yang relevan</p>
        </div>
        <a href="{{ route('categories.index') }}" class="sec-all">
          Semua Kategori
          <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
          </svg>
        </a>
      </div>

      @php $catsToShow = $allCategories->isNotEmpty() ? $allCategories : $displayCategories; @endphp
      <div class="flex flex-wrap justify-center gap-5 sm:gap-6 relative" style="z-index:1;">
        @foreach($catsToShow->take(13) as $i => $cat)
          <a href="{{ route('categories.show', $cat->slug) }}"
             class="cat-item"
             aria-label="Kategori {{ $cat->name }}">
            <div class="cat-icon-box"
                 style="animation: catBob {{ 4.5 + ($i * 0.28) }}s ease-in-out infinite {{ $i * 0.14 }}s;">
              @if(!empty($cat->image_url))
                <img src="{{ $cat->image_url }}" alt="{{ $cat->name }}" class="w-full h-full object-cover">
              @else
                <span class="text-2xl leading-none select-none">{{ $cat->icon ?? '🎮' }}</span>
              @endif
            </div>
            <span class="cat-lbl">{{ $cat->name }}</span>
          </a>
        @endforeach
      </div>
    </div>
  </section>

  {{-- ═══════════════════════════════════════════════════════════ --}}
  {{-- GAME KEYS SECTION                                          --}}
  {{-- ═══════════════════════════════════════════════════════════ --}}
  @if(isset($featuredGameKeys) && $featuredGameKeys->count() > 0)
  <section class="reveal-item home-wrap" style="padding-bottom:3rem;">
    <div class="sec-header">
      <div>
        <div class="sec-kicker">🔑 Game Keys</div>
        <h2 class="sec-title">Unlock the Simulation</h2>
      </div>
      <a href="{{ route('products.search', ['category'=>'game-key']) }}" class="sec-all">
        Lihat Semua
        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
        </svg>
      </a>
    </div>
    <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4">
      @foreach($featuredGameKeys as $product)
        @include('components.product-card', ['product' => $product])
      @endforeach
    </div>
  </section>
  @endif

  {{-- ═══════════════════════════════════════════════════════════ --}}
  {{-- RPG WORLDS SECTION                                         --}}
  {{-- ═══════════════════════════════════════════════════════════ --}}
  @if(isset($featuredRPGKeys) && $featuredRPGKeys->count() > 0)
  <section class="reveal-item home-wrap" style="padding-bottom:3rem;">
    <div class="sec-header">
      <div>
        <div class="sec-kicker">⚔️ RPG &amp; Adventure</div>
        <h2 class="sec-title">Unlock Epic RPG Worlds</h2>
      </div>
      <a href="{{ route('products.search', ['category'=>'game-key']) }}" class="sec-all">
        Lihat Semua
        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
        </svg>
      </a>
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
    <section class="reveal-item home-wrap" style="padding-bottom:3rem;">
      <div class="sec-header">
        <div>
          <div class="sec-kicker">
            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
              <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
            </svg>
            Pilihan Terbaik
          </div>
          <h2 class="sec-title">{{ $section['category']->name }}</h2>
        </div>
        <a href="{{ route('categories.show', $section['category']->slug) }}" class="sec-all">
          Lihat Semua
          <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
          </svg>
        </a>
      </div>
      <div class="relative">
        <div class="banner-track flex gap-4 overflow-x-auto scroll-smooth snap-x snap-mandatory pb-4" style="cursor: grab;">
          @foreach($section['products'] as $product)
            <div class="banner-slide flex-none w-40 md:w-48 lg:w-56 snap-start">
              @include('components.product-card', ['product' => $product])
            </div>
          @endforeach
        </div>
      </div>
    </section>
    @endforeach
  @endif

  {{-- ═══════════════════════════════════════════════════════════ --}}
  {{-- FALLBACK HOMEPAGE PRODUCTS                                 --}}
  {{-- ═══════════════════════════════════════════════════════════ --}}
  @if((!isset($categorySections) || $categorySections->isEmpty()) && isset($homepageProducts) && $homepageProducts->count())
  <section class="reveal-item home-wrap" style="padding-bottom:3rem;">
    <div class="sec-header">
      <div>
        <div class="sec-kicker">
          <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
          </svg>
          Rekomendasi
        </div>
        <h2 class="sec-title">Produk Terbaru &amp; Rekomendasi</h2>
      </div>
      <a href="{{ route('products.search') }}" class="sec-all">
        Lihat Semua
        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
        </svg>
      </a>
    </div>
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
      @foreach($homepageProducts as $p)
        @include('components.product-card', ['product' => $p])
      @endforeach
    </div>
  </section>
  @endif

  {{-- ═══════════════════════════════════════════════════════════ --}}
  {{-- FEATURED BANNERS (Promotional Strip)                       --}}
  {{-- ═══════════════════════════════════════════════════════════ --}}
  @if(isset($featuredBanners) && $featuredBanners->count())
  <section class="reveal-item home-wrap" style="padding-bottom:3.5rem;">
    <div class="feat-panel p-3 md:p-4">
      <div style="overflow:hidden;">
        <div class="feat-scroll-inner">
          {{-- Render twice for seamless loop --}}
          @foreach($featuredBanners as $fb)
            <a href="{{ $fb->link_url ?: '#' }}"
               class="flex-none w-[360px] md:w-[480px] rounded-xl overflow-hidden block aspect-[3/1] border border-white/8 shadow-md transition-opacity hover:opacity-90">
              <img src="{{ $fb->image_url }}" class="w-full h-full object-cover" alt="{{ $fb->title ?? 'Featured' }}">
            </a>
          @endforeach
          @foreach($featuredBanners as $fb)
            <a href="{{ $fb->link_url ?: '#' }}"
               class="flex-none w-[360px] md:w-[480px] rounded-xl overflow-hidden block aspect-[3/1] border border-white/8 shadow-md transition-opacity hover:opacity-90">
              <img src="{{ $fb->image_url }}" class="w-full h-full object-cover" alt="{{ $fb->title ?? 'Featured' }}">
            </a>
          @endforeach
        </div>
      </div>
    </div>
  </section>
  @endif

  {{-- ═══════════════════════════════════════════════════════════ --}}
  {{-- GUEST CTA BANNER                                           --}}
  {{-- ═══════════════════════════════════════════════════════════ --}}
  @guest
  <section class="reveal-item home-wrap" style="padding-bottom:4rem;">
    <div class="cta-panel">
      <div style="position:relative; z-index:2;">
        <div class="sec-kicker justify-center mb-3">
          <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M10 1.944A11.954 11.954 0 012.166 5C2.056 5.649 2 6.319 2 7c0 5.225 3.34 9.67 8 11.317C14.66 16.67 18 12.225 18 7c0-.682-.057-1.35-.166-2.001A11.954 11.954 0 0110 1.944zM11 14a1 1 0 11-2 0 1 1 0 012 0zm0-7a1 1 0 10-2 0v3a1 1 0 102 0V7z" clip-rule="evenodd"/>
          </svg>
          Bergabung Sekarang
        </div>
        <h2 class="sec-title text-center" style="font-size:clamp(1.5rem,4vw,2.25rem); margin-bottom:1rem;">
          Mulai Belanja di Lapak Gaming
        </h2>
        <p class="text-slate-400 text-center max-w-lg mx-auto" style="margin-bottom:2.25rem; line-height:1.7;">
          Daftar gratis dan dapatkan akses ke ribuan produk digital dari seller terpercaya Indonesia. Transaksi aman dan pengiriman instan.
        </p>
        <div class="flex flex-wrap gap-3 justify-center">
          <a href="{{ route('register') }}" class="btn-cta-primary">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
            </svg>
            Daftar Gratis
          </a>
          <a href="{{ route('login') }}" class="btn-cta-secondary">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
            </svg>
            Masuk ke Akun
          </a>
        </div>
      </div>
    </div>
  </section>
  @endguest

{{-- ═══════════════════════════════════════════════════════════ --}}
{{-- FAQ SECTION (PREMIUM REDESIGN)                              --}}
{{-- ═══════════════════════════════════════════════════════════ --}}
<section class="reveal-item home-wrap sec-gap-sm pb-16" id="faq">
    <div class="cat-panel" style="padding: 3.5rem 2rem;">
        
        {{-- Header FAQ --}}
        <div class="sec-header flex-col items-center text-center mx-auto max-w-2xl" style="align-items: center; justify-content: center; margin-bottom: 3.5rem;">
            <div class="sec-kicker mb-3">
                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                Pusat Bantuan
            </div>
            <h2 class="sec-title" style="font-size:clamp(1.5rem, 3.5vw, 2.2rem);">Pertanyaan yang Sering Diajukan</h2>
            <p class="sec-sub mt-2 max-w-lg mx-auto text-center" style="line-height: 1.6;">
                Temukan jawaban atas pertanyaan yang paling sering ditanyakan mengenai top up game, pembayaran, dan proses transaksi.
            </p>
        </div>

        {{-- Daftar FAQ --}}
        <div class="faq-container max-w-3xl mx-auto space-y-3.5 relative z-10">
            
            @php
            $faqs = [
                [
                    'q' => 'Bagaimana cara melakukan top up game?',
                    'a' => 'Pilih game yang ingin di-top up, masukkan User ID, pilih nominal yang diinginkan, lakukan pembayaran, dan pesanan akan diproses secara otomatis.'
                ],
                [
                    'q' => 'Berapa lama proses top up berlangsung?',
                    'a' => 'Sebagian besar transaksi diproses dalam hitungan detik hingga beberapa menit setelah pembayaran berhasil diverifikasi.'
                ],
                [
                    'q' => 'Metode pembayaran apa saja yang tersedia?',
                    'a' => 'Kami mendukung berbagai metode pembayaran seperti QRIS, Transfer Bank, E-Wallet, Virtual Account, dan metode pembayaran lainnya yang tersedia pada halaman checkout.'
                ],
                [
                    'q' => 'Apakah data akun game saya aman?',
                    'a' => 'Ya. Kami hanya memerlukan User ID atau informasi yang dibutuhkan untuk pengiriman item dan tidak pernah meminta password akun game Anda.'
                ],
                [
                    'q' => 'Apa yang harus dilakukan jika top up belum masuk?',
                    'a' => 'Silakan hubungi customer support dengan menyertakan ID transaksi agar tim kami dapat membantu melakukan pengecekan.'
                ],
                [
                    'q' => 'Apakah layanan tersedia 24 jam?',
                    'a' => 'Ya. Sistem transaksi berjalan 24/7 sehingga Anda dapat melakukan top up kapan saja.'
                ]
            ];
            @endphp

            @foreach($faqs as $index => $faq)
            <div class="faq-item group relative overflow-hidden rounded-[20px] border border-white/[0.05] bg-white/[0.015] transition-all duration-300 hover:bg-white/[0.03] hover:border-sky-500/30">
                {{-- Left Accent Line --}}
                <div class="faq-accent absolute left-0 top-0 bottom-0 w-[3px] bg-gradient-to-b from-sky-400 to-blue-600 opacity-0 transition-all duration-500 scale-y-0 origin-top"></div>

                <button class="faq-btn flex w-full items-center justify-between gap-4 p-5 sm:p-6 text-left cursor-pointer outline-none focus:outline-none">
                    <div class="flex items-center gap-4 sm:gap-6">
                        <span class="faq-num font-['Oxanium'] text-xl sm:text-2xl font-black text-white/10 transition-colors duration-300">{{ sprintf('%02d', $index + 1) }}</span>
                        <h3 class="faq-title font-semibold text-white/80 text-sm sm:text-base transition-colors duration-300 group-hover:text-sky-300">{{ $faq['q'] }}</h3>
                    </div>
                    <div class="faq-icon-box flex h-9 w-9 shrink-0 items-center justify-center rounded-xl border border-white/10 bg-white/5 transition-all duration-300 group-hover:border-sky-400/30 group-hover:bg-sky-500/10">
                        <svg class="faq-icon h-4 w-4 text-sky-400 transition-all duration-300" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path class="faq-icon-path" stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                        </svg>
                    </div>
                </button>

                <div class="faq-content grid grid-rows-[0fr] opacity-0 transition-all duration-500">
                    <div class="overflow-hidden">
                        <div class="pb-6 pl-[4.2rem] pr-6 pt-0 text-sm leading-relaxed text-slate-400 sm:pl-[5.2rem]">
                            <div class="border-l-2 border-white/5 pl-4 text-[13px] sm:text-sm">
                                {{ $faq['a'] }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach

        </div>
    </div>
</section>

  <div class="pb-20 sm:pb-10"></div>

</div>
@endsection

@push('scripts')
<script>
  /* ══ COUNTER ANIMATION ══════════════════════════════════════════ */
  function animateCounters() {
    document.querySelectorAll('.counter').forEach(counter => {
      const target = Number(counter.dataset.target) || 0;
      if (target === 0) { counter.textContent = '0'; return; }
      const duration = 1600;
      const step = Math.max(1, Math.round(target / (duration / 16)));
      let value = 0;
      const tick = () => {
        value = Math.min(value + step, target);
        counter.textContent = value.toLocaleString('id-ID');
        if (value < target) requestAnimationFrame(tick);
      };
      requestAnimationFrame(tick);
    });
  }
  document.addEventListener('DOMContentLoaded', animateCounters);

  /* ══ SCROLL REVEAL ══════════════════════════════════════════════ */
  const revealObs = new IntersectionObserver((entries) => {
    entries.forEach(e => {
      if (e.isIntersecting) {
        e.target.classList.add('active');
        revealObs.unobserve(e.target);
      }
    });
  }, { threshold: 0.09, rootMargin: '0px 0px -30px 0px' });
  document.querySelectorAll('.reveal-item').forEach(el => revealObs.observe(el));

  /* ══ NAVBAR SCROLL GLAZE ════════════════════════════════════════ */
  const navbar = document.getElementById('main-navbar');
  if (navbar) {
    const onScroll = () => {
      if (window.scrollY > 12) {
        navbar.style.background = 'rgba(2,6,23,.9)';
        navbar.style.backdropFilter = 'blur(28px)';
        navbar.style.boxShadow = '0 12px 42px rgba(0,0,0,.42)';
      } else {
        navbar.style.background = '';
        navbar.style.backdropFilter = '';
        navbar.style.boxShadow = '';
      }
    };
    window.addEventListener('scroll', onScroll, { passive: true });
  }

  /* ══ PRODUCT CARD 3D TILT ═══════════════════════════════════════ */
  document.querySelectorAll('.product-card').forEach(card => {
    card.addEventListener('mousemove', e => {
      const r = card.getBoundingClientRect();
      const x = e.clientX - r.left, y = e.clientY - r.top;
      const rY = ((x / r.width)  - 0.5) * 7;
      const rX = ((y / r.height) - 0.5) * -7;
      card.style.transform = `perspective(900px) rotateX(${rX}deg) rotateY(${rY}deg) translateY(-9px)`;
    });
    card.addEventListener('mouseleave', () => { card.style.transform = ''; });
  });

  /* ══ DRAG-TO-SCROLL (horizontal tracks) ════════════════════════ */
  document.querySelectorAll('.banner-track').forEach(track => {
    let isDown = false, startX, scrollLeft;
    track.addEventListener('mousedown', e => {
      isDown = true;
      startX = e.pageX - track.offsetLeft;
      scrollLeft = track.scrollLeft;
      track.style.cursor = 'grabbing';
    });
    track.addEventListener('mouseleave', () => { isDown = false; track.style.cursor = 'grab'; });
    track.addEventListener('mouseup',    () => { isDown = false; track.style.cursor = 'grab'; });
    track.addEventListener('mousemove',  e => {
      if (!isDown) return;
      e.preventDefault();
      track.scrollLeft = scrollLeft - (e.pageX - track.offsetLeft - startX) * 2;
    });
  });

  /* ══ FAQ ACCORDION ═════════════════════════════════════════════ */
  document.querySelectorAll('.faq-btn').forEach(btn => {
    btn.addEventListener('click', () => {
      const item = btn.closest('.faq-item');
      const content = item.querySelector('.faq-content');
      const iconPath = item.querySelector('.faq-icon-path');
      const iconBox = item.querySelector('.faq-icon-box');
      const accent = item.querySelector('.faq-accent');
      const title = item.querySelector('.faq-title');
      const num = item.querySelector('.faq-num');
      
      const isOpen = item.classList.contains('is-open');

      // Close all FAQs first
      document.querySelectorAll('.faq-item').forEach(otherItem => {
        otherItem.classList.remove('is-open');
        otherItem.style.backgroundColor = '';
        otherItem.style.borderColor = '';
        
        const otherContent = otherItem.querySelector('.faq-content');
        const otherIconPath = otherItem.querySelector('.faq-icon-path');
        const otherIconBox = otherItem.querySelector('.faq-icon-box');
        const otherAccent = otherItem.querySelector('.faq-accent');
        const otherTitle = otherItem.querySelector('.faq-title');
        const otherNum = otherItem.querySelector('.faq-num');

        if(otherContent) {
            otherContent.style.gridTemplateRows = '0fr';
            otherContent.style.opacity = '0';
        }
        if(otherIconPath) {
            otherIconPath.setAttribute('d', 'M12 4.5v15m7.5-7.5h-15');
        }
        if(otherIconBox) {
            otherIconBox.style.backgroundColor = '';
            otherIconBox.style.borderColor = '';
        }
        if(otherAccent) {
            otherAccent.style.transform = 'scaleY(0)';
            otherAccent.style.opacity = '0';
        }
        if(otherTitle) {
            otherTitle.classList.remove('text-sky-400');
            otherTitle.classList.add('text-white/80');
        }
        if(otherNum) {
            otherNum.classList.remove('text-sky-500/30');
            otherNum.classList.add('text-white/10');
        }
      });

      // Toggle current FAQ
      if (!isOpen) {
        item.classList.add('is-open');
        item.style.backgroundColor = 'rgba(255, 255, 255, 0.03)';
        item.style.borderColor = 'rgba(14, 165, 233, 0.3)';
        
        content.style.gridTemplateRows = '1fr';
        content.style.opacity = '1';
        
        if (iconPath) iconPath.setAttribute('d', 'M19.5 12h-15'); // minus icon
        if (iconBox) {
            iconBox.style.backgroundColor = 'rgba(14, 165, 233, 0.1)';
            iconBox.style.borderColor = 'rgba(56, 189, 248, 0.3)';
        }
        
        accent.style.transform = 'scaleY(1)';
        accent.style.opacity = '1';
        title.classList.remove('text-white/80');
        title.classList.add('text-sky-400');
        num.classList.remove('text-white/10');
        num.classList.add('text-sky-500/30');
      }
    });
  });

</script>
@endpush
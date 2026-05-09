@extends('layouts.app')
@section('title', 'Lapak Gaming — Marketplace Game Terpercaya Indonesia')

@push('styles')
<style>
  .hero-glow { background: radial-gradient(ellipse 70% 60% at 50% -10%, rgba(37,99,235,0.25), transparent 70%); }
  .cat-btn { background:#0D1421;border:1px solid #1E2D45;border-radius:14px;transition:all 0.2s; }
  .cat-btn:hover { border-color:rgba(37,99,235,0.5);background:rgba(37,99,235,0.08);transform:translateY(-2px); }
  .topup-card { background:#0D1421;border:1px solid #1E2D45;border-radius:14px;overflow:hidden;transition:all 0.2s; }
  .topup-card:hover { border-color:rgba(37,99,235,0.5);transform:translateY(-3px);box-shadow:0 12px 32px rgba(0,0,0,0.5); }
  .section-grid-3col { grid-template-columns:repeat(3,1fr); }
  .scale-108 { transform:scale(1.08); }
  @keyframes heroFloat { 0%,100%{transform:translateY(0) rotate(0deg)}50%{transform:translateY(-12px) rotate(2deg)} }
  .hero-float { animation: heroFloat 6s ease-in-out infinite; }
</style>
@endpush

@section('content')

{{-- ═══════════════════════════════════════════════════════════ --}}
{{-- HERO SECTION                                               --}}
{{-- ═══════════════════════════════════════════════════════════ --}}
<section class="relative overflow-hidden pt-10 pb-16">
  <div class="hero-glow absolute inset-0 pointer-events-none"></div>

  {{-- Decorative orbs --}}
  <div class="absolute top-10 right-1/4 w-64 h-64 rounded-full pointer-events-none opacity-10"
       style="background:radial-gradient(circle,#2563eb,transparent 70%);filter:blur(40px);"></div>
  <div class="absolute bottom-0 left-1/3 w-48 h-48 rounded-full pointer-events-none opacity-10"
       style="background:radial-gradient(circle,#f97316,transparent 70%);filter:blur(36px);"></div>

  <div class="max-w-7xl mx-auto px-4">
    <div class="flex flex-col lg:flex-row items-center gap-12">

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
          <a href="{{ route('products.search') }}" class="btn-primary px-6 py-3.5 rounded-xl text-base">
            <img src="{{ url('storage/app/public/logo/logo.png') }}" alt="Lapak Gaming" class="h-4 w-4 rounded-sm object-contain bg-white/10 p-0.5">
            Mulai Belanja
          </a>
          <a href="{{ route('marketplace.trending') }}" class="btn-ghost px-6 py-3.5 rounded-xl text-base">
            Lihat Trending →
          </a>
        </div>

        {{-- Trust stats --}}
        <div class="flex flex-wrap gap-6 mt-10 justify-center lg:justify-start">
          @foreach([
            ['num'=>'500K+','label'=>'Pengguna Aktif'],
            ['num'=>'10K+', 'label'=>'Produk Tersedia'],
            ['num'=>'5K+',  'label'=>'Seller Verified'],
            ['num'=>'99.8%','label'=>'Kepuasan Pembeli'],
          ] as $stat)
          <div class="text-center lg:text-left">
            <div class="font-display font-bold text-2xl text-white">{{ $stat['num'] }}</div>
            <div class="text-xs text-slate-500 mt-0.5">{{ $stat['label'] }}</div>
          </div>
          @endforeach
        </div>
      </div>

      {{-- Right: Floating game card mockup --}}
      <div class="hidden lg:block flex-shrink-0 relative w-80">
        <div class="hero-float">
          {{-- Main card --}}
          <div class="rounded-2xl p-5 relative overflow-hidden"
               style="background:linear-gradient(135deg,#0D1421,#162032);border:1px solid #1E2D45;box-shadow:0 24px 64px rgba(0,0,0,0.6);">
            <div class="absolute top-0 right-0 w-32 h-32 rounded-full opacity-20"
                 style="background:radial-gradient(circle,#2563eb,transparent);filter:blur(20px);"></div>
            <div class="flex items-center gap-3 mb-5">
              <div class="w-12 h-12 rounded-xl flex items-center justify-center text-2xl"
                   style="background:#1E2D45;">🎮</div>
              <div>
                <div class="font-display font-bold text-white text-sm">Mobile Legends</div>
                <div class="text-xs text-slate-400">1000 Diamond</div>
              </div>
              <span class="ml-auto badge badge-green">Live</span>
            </div>
            <div class="rounded-xl p-4 mb-4" style="background:#090E1A;">
              <div class="text-xs text-slate-500 mb-1">Harga Terbaik</div>
              <div class="font-display font-bold text-2xl text-white">Rp 149.000</div>
              <div class="text-xs text-emerald-400 mt-1">↓ 15% lebih murah dari toko resmi</div>
            </div>
            <div class="grid grid-cols-2 gap-2 text-xs">
              <div class="rounded-lg p-2.5 text-center" style="background:#090E1A;">
                <div class="text-slate-400">Rating</div>
                <div class="font-bold text-amber-400">⭐ 4.9</div>
              </div>
              <div class="rounded-lg p-2.5 text-center" style="background:#090E1A;">
                <div class="text-slate-400">Terjual</div>
                <div class="font-bold text-white">12.4K</div>
              </div>
            </div>
            <button class="w-full mt-4 btn-primary py-3 rounded-xl text-sm">Beli Sekarang →</button>
          </div>

          {{-- Floating mini cards --}}
          <div class="absolute -top-4 -right-4 rounded-xl px-3 py-2 text-xs font-bold"
               style="background:#0D1421;border:1px solid rgba(16,185,129,0.4);color:#34d399;box-shadow:0 4px 16px rgba(0,0,0,0.5);">
            ✓ Pembayaran Berhasil
          </div>
          <div class="absolute -bottom-4 -left-4 rounded-xl px-3 py-2 text-xs"
               style="background:#0D1421;border:1px solid rgba(37,99,235,0.4);box-shadow:0 4px 16px rgba(0,0,0,0.5);">
            <span class="text-slate-400">Saldo Wallet</span>
            <span class="font-display font-bold text-white ml-1">Rp 250K</span>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

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
      <a href="{{ route('categories.show', $cat->slug) }}" class="cat-btn flex flex-col items-center gap-2.5 p-3 group">
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

{{-- ═══════════════════════════════════════════════════════════ --}}
{{-- QUICK ACCESS — TYPE BUTTONS                                 --}}
{{-- ═══════════════════════════════════════════════════════════ --}}
<section class="pb-14">
  <div class="max-w-7xl mx-auto px-4">
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
      @foreach([
        ['type'=>'topup', 'icon'=>'⚡','label'=>'Top Up','desc'=>'Langsung ke akun','class'=>'bg-blue-500/10 border-blue-500/30','badge'=>'Tercepat'],
        ['type'=>'joki',  'icon'=>'🏆','label'=>'Jasa Joki','desc'=>'Naik rank dijamin','class'=>'bg-orange-500/10 border-orange-500/25','badge'=>'Populer'],
        ['type'=>'akun',  'icon'=>'👤','label'=>'Akun Game','desc'=>'Ready stock','class'=>'bg-purple-500/10 border-purple-500/25','badge'=>''],
        ['type'=>'item',  'icon'=>'⚔️','label'=>'Item & Skin','desc'=>'Harga termurah','class'=>'bg-emerald-500/10 border-emerald-500/25','badge'=>''],
      ] as $qt)
      <a href="{{ route('products.by-type', $qt['type']) }}"
        class="flex items-center gap-3 p-4 rounded-2xl transition-all hover:scale-[1.02] hover:shadow-card {{ $qt['class'] }}">
        <span class="text-3xl shrink-0">{{ $qt['icon'] }}</span>
        <div class="flex-1 min-w-0">
          <div class="flex items-center gap-2">
            <span class="font-display font-bold text-white text-sm">{{ $qt['label'] }}</span>
            @if($qt['badge'])<span class="badge badge-orange" style="font-size:0.55rem;">{{ $qt['badge'] }}</span>@endif
          </div>
          <span class="text-xs text-slate-400">{{ $qt['desc'] }}</span>
        </div>
        <svg class="w-4 h-4 text-slate-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
      </a>
      @endforeach
    </div>
  </div>
</section>

{{-- ═══════════════════════════════════════════════════════════ --}}
{{-- POPULAR PRODUCTS                                            --}}
{{-- ═══════════════════════════════════════════════════════════ --}}
<section class="pb-14">
  <div class="max-w-7xl mx-auto px-4">
    <div class="flex items-center justify-between mb-6">
      <div>
        <h2 class="section-title font-display font-bold text-lg text-white">Produk Terpopuler</h2>
        <p class="text-xs text-slate-500 mt-1 pl-4">Paling banyak dibeli minggu ini</p>
      </div>
      <a href="{{ route('products.search') }}" class="text-sm text-brand-400 hover:text-brand-300 transition-colors">Lihat semua →</a>
    </div>
    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-4">
      @forelse($popularProducts as $product)
        @include('components.product-card', ['product' => $product])
      @empty
        <div class="col-span-full py-16 text-center">
          <div class="text-4xl mb-4">🎮</div>
          <p class="text-slate-400">Produk populer akan segera tersedia.</p>
        </div>
      @endforelse
    </div>
  </div>
</section>

{{-- ═══════════════════════════════════════════════════════════ --}}
{{-- TOP UP SECTION                                              --}}
{{-- ═══════════════════════════════════════════════════════════ --}}
@if($topupProducts->isNotEmpty())
<section class="pb-14">
  <div class="max-w-7xl mx-auto px-4">
    {{-- Section header --}}
    <div class="flex items-center justify-between mb-6">
      <div class="flex items-center gap-3">
        <div class="w-8 h-8 rounded-lg flex items-center justify-center"
             style="background:linear-gradient(135deg,#2563eb,#1d4ed8);">
          <img src="{{ url('storage/app/public/logo/logo.png') }}" alt="Lapak Gaming" class="h-4 w-4 rounded-sm object-contain bg-white/10 p-0.5">
        </div>
        <div>
          <h2 class="font-display font-bold text-lg text-white">⚡ Top Up Kilat</h2>
          <p class="text-xs text-slate-500">Proses instan, langsung ke ID</p>
        </div>
      </div>
      <a href="{{ route('products.by-type', 'topup') }}" class="text-sm text-brand-400 hover:text-brand-300 transition-colors">Semua Top Up →</a>
    </div>

    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4">
      @foreach($topupProducts as $product)
        @include('components.product-card', ['product' => $product])
      @endforeach
    </div>
  </div>
</section>
@endif

{{-- ═══════════════════════════════════════════════════════════ --}}
{{-- VALUE PROPOSITION BANNER                                    --}}
{{-- ═══════════════════════════════════════════════════════════ --}}
<section class="pb-14">
  <div class="max-w-7xl mx-auto px-4">
    <div class="rounded-2xl p-8 sm:p-12 relative overflow-hidden text-center"
         style="background:linear-gradient(135deg,rgba(37,99,235,0.15) 0%,rgba(249,115,22,0.08) 100%);border:1px solid rgba(37,99,235,0.25);">
      <div class="absolute inset-0 pointer-events-none"
           style="background:radial-gradient(ellipse 60% 50% at 50% 100%,rgba(37,99,235,0.1),transparent);"></div>
      <span class="badge badge-blue mb-4">Kenapa Lapak Gaming?</span>
      <h2 class="font-display font-bold text-2xl sm:text-3xl text-white mb-3">Platform terpercaya untuk<br>semua kebutuhan gaming-mu</h2>
      <p class="text-slate-400 text-sm max-w-lg mx-auto mb-8">Bergabung dengan jutaan gamer Indonesia yang sudah percaya transaksi mereka bersama kami.</p>

      <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 max-w-3xl mx-auto mb-8">
        @foreach([
          ['icon'=>'M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z',
            'label'=>'Escrow Aman','sub'=>'Dana terlindungi'],
          ['icon'=>'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z',
            'label'=>'Proses Cepat','sub'=>'< 5 menit selesai'],
          ['icon'=>'M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z',
            'label'=>'Rating Tinggi','sub'=>'4.9 dari 5 bintang'],
          ['icon'=>'M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z',
            'label'=>'Support 24/7','sub'=>'Siap bantu kapanpun'],
        ] as $vp)
        <div class="flex flex-col items-center text-center">
          <div class="w-10 h-10 rounded-xl flex items-center justify-center mb-3"
               style="background:rgba(37,99,235,0.12);border:1px solid rgba(37,99,235,0.25);">
            <svg class="w-5 h-5 text-brand-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="{{ $vp['icon'] }}"/></svg>
          </div>
          <div class="text-sm font-semibold text-white">{{ $vp['label'] }}</div>
          <div class="text-xs text-slate-500 mt-0.5">{{ $vp['sub'] }}</div>
        </div>
        @endforeach
      </div>

      <a href="{{ route('register') }}" class="btn-primary px-8 py-3.5 rounded-xl text-base">
        Daftar Gratis Sekarang →
      </a>
    </div>
  </div>
</section>

{{-- ═══════════════════════════════════════════════════════════ --}}
{{-- FAQ SECTION (ACCORDION)                                     --}}
{{-- ═══════════════════════════════════════════════════════════ --}}
<section class="pb-20">
    <div class="max-w-7xl mx-auto px-4">
        <div class="space-y-3">
            
            @php
            $faqs = [
                [
                    'q' => 'Marketplace Games Terbesar dan Terlengkap',
                    'a' => 'Lapak Gaming adalah marketplace destinasi utama bagi para gamers untuk yang mencari kenyamanan dan keandalan dalam bertransaksi digital. Dengan berbagai produk digital yang tersedia, Lapak Gaming menyediakan solusi lengkap untuk kebutuhan hiburan Anda.'
                ],
                [
                    'q' => 'Apa itu Lapak Gaming?',
                    'a' => 'Kami adalah platform perantara (escrow) yang menjamin keamanan transaksi antara penjual dan pembeli produk game di Indonesia. Semua transaksi dilindungi oleh sistem garansi kami.'
                ],
                [
                    'q' => 'Top-Up Game Terlengkap',
                    'a' => 'Nikmati layanan top up berbagai game populer seperti Mobile Legends, Free Fire, dan Genshin Impact dengan proses instan dan harga yang sangat bersaing.'
                ],
                [
                    'q' => 'Voucher Digital untuk Berbagai Kebutuhan',
                    'a' => 'Selain kebutuhan gaming, kami juga menyediakan voucher digital untuk berbagai layanan populer lainnya guna mendukung segala aktivitas hiburan Anda setiap hari.'
                ]
            ];
            @endphp

            @foreach($faqs as $index => $faq)
            <div class="faq-item group">
                <button onclick="toggleFaq({{ $index }})" 
                        class="w-full flex items-center justify-between p-5 text-left bg-gray-900/50 border border-gray-800 rounded-2xl hover:border-blue-500/50 transition-all duration-300">
                    <span class="font-display font-bold text-white text-sm md:text-base">{{ $faq['q'] }}</span>
                    <svg id="icon-{{ $index }}" class="w-5 h-5 text-slate-500 transition-transform duration-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>
                <div id="faq-{{ $index }}" class="max-h-0 overflow-hidden transition-all duration-300 ease-in-out">
                    <div class="p-5 text-sm text-slate-400 leading-relaxed border-x border-b border-gray-800 rounded-b-2xl -mt-2 bg-gray-900/30">
                        {{ $faq['a'] }}
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

@push('scripts')
<script>
    function toggleFaq(index) {
        const content = document.getElementById(`faq-${index}`);
        const icon = document.getElementById(`icon-${index}`);

        // Cek apakah sedang terbuka
        const isOpen = content.style.maxHeight !== '0px' && content.style.maxHeight !== '';

        // Tutup semua FAQ dulu (opsional, jika ingin hanya 1 yang terbuka)
        document.querySelectorAll('[id^="faq-"]').forEach(el => el.style.maxHeight = '0px');
        document.querySelectorAll('[id^="icon-"]').forEach(el => el.style.transform = 'rotate(0deg)');

        if (isOpen) {
            content.style.maxHeight = '0px';
            icon.style.transform = 'rotate(0deg)';
        } else {
            content.style.maxHeight = content.scrollHeight + "px";
            icon.style.transform = 'rotate(180deg)';
        }
    }
</script>
@endpush
@endsection
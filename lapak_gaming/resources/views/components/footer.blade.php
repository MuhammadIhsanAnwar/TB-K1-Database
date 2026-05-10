<footer style="background:#090E1A;border-top:1px solid #1E2D45;" class="mt-20">
  <div class="max-w-7xl mx-auto px-4 pt-14 pb-8">

    {{-- Top section --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-10 mb-12">

      {{-- Brand column --}}
      <div class="lg:col-span-2">
        <a href="{{ route('marketplace.home') }}" class="flex items-center gap-2.5 mb-4">
          <img src="{{ url('storage/app/public/logo/logo.png') }}" alt="Lapak Gaming" class="w-9 h-9 rounded-xl object-contain bg-white/5 p-1" style="box-shadow:0 0 16px rgba(37,99,235,0.35);">
          <span class="font-display font-bold text-xl text-white">{{ config('app.name', 'Lapak Gaming') }}</span>
        </a>
        <p class="text-sm text-slate-400 leading-relaxed max-w-xs">
          Platform marketplace game terpercaya Indonesia. Top-up, jual-beli akun, item, dan voucher dengan aman, cepat, dan terjamin.
        </p>
        <div class="flex items-center gap-3 mt-5">
          {{-- Social icons --}}
          @foreach([
            ['icon'=>'M23 3a10.9 10.9 0 01-3.14 1.53 4.48 4.48 0 00-7.86 3v1A10.66 10.66 0 013 4s-4 9 5 13a11.64 11.64 0 01-7 2c9 5 20 0 20-11.5a4.5 4.5 0 00-.08-.83A7.72 7.72 0 0023 3z','label'=>'Twitter'],
            ['icon'=>'M21 2H3v16h5v4l4-4h5l4-4V2zM11 11V7m4 4V7','label'=>'Discord'],
            ['icon'=>'M22.54 6.42a2.78 2.78 0 00-1.95-1.96C18.88 4 12 4 12 4s-6.88 0-8.59.46a2.78 2.78 0 00-1.95 1.96A29 29 0 001 12a29 29 0 00.46 5.58A2.78 2.78 0 003.41 19.6C5.12 20 12 20 12 20s6.88 0 8.59-.46a2.78 2.78 0 001.95-1.95A29 29 0 0023 12a29 29 0 00-.46-5.58z M9.75 15.02l5.75-3.02-5.75-3.02v6.04z','label'=>'YouTube'],
            ['icon'=>'M21 2H3v16h5v4l4-4h5l4-4V2zm-10 6.5v5m3-5v5','label'=>'Instagram'],
          ] as $soc)
          <a href="#" aria-label="{{ $soc['label'] }}"
             class="w-9 h-9 rounded-xl flex items-center justify-center text-slate-500 hover:text-white transition-colors"
             style="background:#162032;border:1px solid #1E2D45;">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="{{ $soc['icon'] }}"/></svg>
          </a>
          @endforeach
        </div>
      </div>

      {{-- Marketplace links --}}
      <div>
        <h4 class="font-display font-semibold text-white text-sm mb-4">Marketplace</h4>
        <ul class="space-y-2.5">
          @foreach([
            ['route'=>'marketplace.home',    'label'=>'Beranda'],
            ['route'=>'products.search',     'label'=>'Semua Produk'],
            ['route'=>'marketplace.trending','label'=>'Trending'],
            ['route'=>'products.by-type',    'label'=>'Top Up',     'param'=>'topup'],
          ] as $link)
          <li>
            <a href="{{ isset($link['param']) ? route($link['route'], $link['param']) : route($link['route']) }}"
               class="text-sm text-slate-400 hover:text-white transition-colors">{{ $link['label'] }}</a>
          </li>
          @endforeach
        </ul>
      </div>

      {{-- Account links --}}
      <div>
        <h4 class="font-display font-semibold text-white text-sm mb-4">Akun</h4>
        <ul class="space-y-2.5">
          @foreach([
            ['route'=>'login',                'label'=>'Masuk',    'guest'=>true],
            ['route'=>'register',             'label'=>'Daftar',   'guest'=>true],
            ['route'=>'dashboard',            'label'=>'Dashboard','auth'=>true],
            ['route'=>'wallet.index',         'label'=>'Wallet',   'auth'=>true],
            ['route'=>'orders.index',         'label'=>'Pesanan',  'auth'=>true],
            ['route'=>'profile.show',         'label'=>'Profil',   'auth'=>true],
          ] as $link)
          @if((isset($link['guest']) && !Auth::check()) || (isset($link['auth']) && Auth::check()) || (!isset($link['guest']) && !isset($link['auth'])))
          <li>
            <a href="{{ route($link['route']) }}" class="text-sm text-slate-400 hover:text-white transition-colors">{{ $link['label'] }}</a>
          </li>
          @endif
          @endforeach
        </ul>
      </div>

      {{-- Support links --}}
      <div>
        <h4 class="font-display font-semibold text-white text-sm mb-4">Tentang Kami</h4>
        <ul class="space-y-2.5">
          <li><a href="#" class="text-sm text-slate-400 hover:text-white transition-colors">Tentang Lapak Gaming</a></li>
          <li><a href="#" class="text-sm text-slate-400 hover:text-white transition-colors">Syarat & Ketentuan</a></li>
          <li><a href="{{ route('contact') }}" class="text-sm text-slate-400 hover:text-white transition-colors">Hubungi Kami</a></li>
          <li><a href="#" class="text-sm text-slate-400 hover:text-white transition-colors">Aturan Penggunaan</a></li>
          <li><a href="#" class="text-sm text-slate-400 hover:text-white transition-colors">Kebijakan Privasi</a></li>
        </ul>
      </div>
    </div>

    {{-- Trust badges --}}
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 py-8" style="border-top:1px solid #1E2D45;border-bottom:1px solid #1E2D45;">
      @foreach([
        ['icon'=>'M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z',
          'label'=>'Transaksi 100% Aman','sub'=>'Escrow protected'],
        ['icon'=>'M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z',
          'label'=>'Garansi Uang Kembali','sub'=>'7 hari garansi'],
        ['icon'=>'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z',
          'label'=>'Ribuan Seller Verified','sub'=>'Terverifikasi KYC'],
        ['icon'=>'M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z',
          'label'=>'Support 24/7','sub'=>'Siap membantu'],
      ] as $trust)
      <div class="flex items-center gap-3">
        <div class="w-10 h-10 rounded-xl flex items-center justify-center shrink-0"
             style="background:rgba(37,99,235,0.1);border:1px solid rgba(37,99,235,0.2);">
          <svg class="w-5 h-5 text-brand-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="{{ $trust['icon'] }}"/></svg>
        </div>
        <div>
          <div class="text-xs font-semibold text-white">{{ $trust['label'] }}</div>
          <div class="text-xs text-slate-500">{{ $trust['sub'] }}</div>
        </div>
      </div>
      @endforeach
    </div>

    {{-- Bottom bar --}}
    <div class="flex flex-col sm:flex-row items-center justify-between gap-4 pt-8">
      <p class="text-xs text-slate-500">
        © {{ date('Y') }} {{ config('app.name', 'Lapak Gaming') }}. All rights reserved.
      </p>
      <div class="flex items-center gap-4">
        {{-- Payment method icons --}}
        @foreach(['QRIS','GoPay','OVO','DANA','Bank Transfer'] as $pm)
        <span class="px-2 py-1 rounded text-[10px] font-bold text-slate-500" style="background:#162032;border:1px solid #1E2D45;">{{ $pm }}</span>
        @endforeach
      </div>
    </div>
  </div>
</footer>
{{--
  Component: components/footer.blade.php
  Itemku style 5-column footer.
--}}

<footer class="surface-panel border-t border-white/5 mt-20 pt-16 pb-8 bg-gradient-to-b from-slate-950/40 to-slate-950/70 backdrop-blur-sm">
  <div class="max-w-7xl mx-auto px-4">

    {{-- Top section: footer brand and links --}}
    <div class="space-y-10 mb-12">
      <div class="flex items-center gap-4">
        <img src="{{ url('storage/app/public/logo/logo.png') }}" alt="Lapak Gaming" class="w-14 h-14 object-contain rounded-2xl bg-slate-900/50 border border-white/10 p-2">
        <div>
          <h4 class="font-bold surface-text text-2xl">Lapak Gaming</h4>
          <p class="text-sm surface-muted max-w-xl">Marketplace digital untuk top up game, akun, voucher, dan item game terpercaya dengan layanan cepat dan keamanan modern.</p>
        </div>
      </div>

      <div class="grid sm:grid-cols-2 gap-4">
        <div>
          <h5 class="font-semibold surface-text text-sm mb-3">Jelajahi</h5>
          <ul class="space-y-3">
            <li><a href="{{ route('marketplace.home') }}" class="text-sm surface-muted hover:text-itemku-blue transition-colors">Beranda</a></li>
            <li><a href="{{ route('products.search') }}" class="text-sm surface-muted hover:text-itemku-blue transition-colors">Semua Produk</a></li>
            <li><a href="{{ route('marketplace.trending') }}" class="text-sm surface-muted hover:text-itemku-blue transition-colors">Trending</a></li>
          </ul>
        </div>
        <div>
          <h5 class="font-semibold surface-text text-sm mb-3">Dukungan</h5>
          <ul class="space-y-3">
            <li><a href="{{ route('contact') }}" class="text-sm surface-muted hover:text-itemku-blue transition-colors">Hubungi Kami</a></li>
            <li><a href="{{ route('terms') }}" class="text-sm surface-muted hover:text-itemku-blue transition-colors">Aturan Penggunaan</a></li>
            <li><a href="{{ route('privacy') }}" class="text-sm surface-muted hover:text-itemku-blue transition-colors">Kebijakan Privasi</a></li>
          </ul>
        </div>
      </div>
    </div>

    {{-- Bottom bar --}}
    <div class="flex flex-col md:flex-row items-center justify-between gap-6 pt-8 border-t border-white/10">
      
      <div class="flex items-center gap-3">
        <img src="{{ url('storage/app/public/logo/logo.png') }}" alt="Logo" class="w-6 h-6 object-contain grayscale opacity-50">
        <p class="text-xs surface-muted font-medium">
          Lapak Gaming v1.0 © {{ date('Y') }} Hak Cipta Terpelihara.
        </p>
      </div>

    </div>
  </div>
</footer>
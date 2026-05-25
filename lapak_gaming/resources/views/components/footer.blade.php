{{--
  Component: components/footer.blade.php
  Itemku style 5-column footer.
--}}

<footer class="surface-panel border-t border-white/10 mt-20 pt-16 pb-8">
  <div class="max-w-7xl mx-auto px-4">

    {{-- Top section: 5 Columns --}}
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-8 mb-12">

      {{-- Column 1: Tentang Kami --}}
      <div>
        <h4 class="font-bold surface-text text-base mb-4">Lapak Gaming</h4>
        <ul class="space-y-3">
          <li><a href="{{ route('about') }}" class="text-sm surface-muted hover:text-itemku-blue transition-colors">Tentang Kami</a></li>
          <li><a href="{{ route('terms') }}" class="text-sm surface-muted hover:text-itemku-blue transition-colors">Aturan Penggunaan</a></li>
          <li><a href="{{ route('privacy') }}" class="text-sm surface-muted hover:text-itemku-blue transition-colors">Kebijakan Privasi</a></li>
          <li><a href="{{ route('refund') }}" class="text-sm surface-muted hover:text-itemku-blue transition-colors">Kebijakan Pengembalian Dana</a></li>
          <li><a href="{{ route('contact') }}" class="text-sm surface-muted hover:text-itemku-blue transition-colors">Hubungi Kami</a></li>
        </ul>
      </div>

      {{-- Column 2: Bantuan --}}
      <div>
        <h4 class="font-bold surface-text text-base mb-4">Bantuan</h4>
        <ul class="space-y-3">
          <li><a href="{{ route('contact') }}" class="text-sm surface-muted hover:text-itemku-blue transition-colors">Hubungi Kami</a></li>
          <li><a href="{{ route('terms') }}" class="text-sm surface-muted hover:text-itemku-blue transition-colors">Aturan Penggunaan</a></li>
          <li><a href="{{ route('refund') }}" class="text-sm surface-muted hover:text-itemku-blue transition-colors">Pengembalian Dana</a></li>
        </ul>
      </div>

      {{-- Column 3: Pembeli --}}
      <div>
        <h4 class="font-bold surface-text text-base mb-4">Pembeli</h4>
        <ul class="space-y-3">
          <li><a href="{{ route('register') }}" class="text-sm text-gray-500 hover:text-itemku-blue transition-colors">Cara Daftar</a></li>
          <li><a href="{{ route('products.search') }}" class="text-sm text-gray-500 hover:text-itemku-blue transition-colors">Semua Kategori</a></li>
          <li><a href="{{ route('marketplace.trending') }}" class="text-sm surface-muted hover:text-itemku-blue transition-colors">Produk Trending</a></li>
          <li><a href="{{ route('marketplace.deals') }}" class="text-sm surface-muted hover:text-itemku-blue transition-colors">Promo Terkini</a></li>
        </ul>
      </div>

      {{-- Column 4: Informasi Kontak --}}
      <div>
        <h4 class="font-bold surface-text text-base mb-4">Informasi</h4>
        <ul class="space-y-3">
          <li><a href="{{ route('contact') }}" class="text-sm surface-muted hover:text-itemku-blue transition-colors">Hubungi Kami</a></li>
          <li><a href="{{ route('about') }}" class="text-sm surface-muted hover:text-itemku-blue transition-colors">Tentang Kami</a></li>
          <li><a href="{{ route('privacy') }}" class="text-sm surface-muted hover:text-itemku-blue transition-colors">Kebijakan Privasi</a></li>
        </ul>
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
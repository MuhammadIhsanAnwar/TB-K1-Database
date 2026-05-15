{{-- 
  Logic pendeteksi gambar: 
  Cek apakah gambar link luar (http) atau hasil upload sendiri (local storage)
--}}
@php
    $rawImage = $product->image; // Mengambil kolom 'image' dari database
    $displayImage = 'https://ui-avatars.com/api/?name='.urlencode($product->name).'&background=090E1A&color=2563eb&bold=true';

    if ($rawImage) {
        if (str_starts_with($rawImage, 'http')) {
            // Jika dari Faker/Internet
            $displayImage = $rawImage;
        } else {
            // Jika hasil upload manual (tambahkan /storage/ di depannya)
            $displayImage = asset('storage/' . $rawImage);
        }
    }
@endphp

<a href="{{ route('products.show', $product->slug) }}" class="product-card group relative flex flex-col">

  {{-- Ribbon for special types --}}
  @if(($product->type ?? '') === 'topup')
    <span class="ribbon ribbon-blue">Top Up</span>
  @elseif($product->sold_count > 100)
    <span class="ribbon">Bestseller</span>
  @endif

  {{-- Product Image --}}
  <div class="relative aspect-square overflow-hidden" style="background:#090E1A;">
    {{-- Ganti src menjadi variabel $displayImage yang sudah kita olah di atas --}}
    <img src="{{ $displayImage }}"
         alt="{{ $product->name }}"
         class="w-full h-full object-cover transition-transform duration-400 group-hover:scale-108"
         loading="lazy"
         onerror="this.src='https://ui-avatars.com/api/?name=Game&background=0D1421&color=2563eb'">

    {{-- Stock badge --}}
    @if(($product->stock ?? 1) === 0)
      <div class="absolute inset-0 flex items-center justify-center" style="background:rgba(6,10,18,0.7);">
        <span class="badge badge-red text-xs">Habis</span>
      </div>
    @elseif(($product->stock ?? 99) <= 5)
      <div class="absolute bottom-2 right-2">
        <span class="badge badge-orange" style="font-size:0.6rem;">Stok {{ $product->stock }}</span>
      </div>
    @endif

    {{-- Hover overlay --}}
    <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-200 flex items-end p-3">
      <span class="text-xs font-display font-semibold text-white bg-brand-600/90 px-2.5 py-1 rounded-lg">Lihat Produk →</span>
    </div>
  </div>

  {{-- Product Info --}}
  <div class="flex flex-col flex-1 p-3">
    <p class="text-[10px] text-brand-400 font-display font-semibold uppercase tracking-wide mb-1">
      {{ $product->category->name ?? 'Game' }}
    </p>
    <h3 class="text-sm font-semibold text-slate-100 line-clamp-2 leading-snug mb-2 flex-1">
      {{ $product->name }}
    </h3>
    <div class="mt-auto">
      {{-- Pakai price asli jika formatted_price tidak muncul --}}
      <p class="font-display font-bold text-base text-white">
        {{ $product->formatted_price ?? 'Rp ' . number_format($product->price, 0, ',', '.') }}
      </p>
      <div class="flex items-center justify-between mt-1.5">
        <div class="flex items-center gap-1">
          <svg class="w-3 h-3 text-amber-400" viewBox="0 0 20 20" fill="currentColor">
            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
          </svg>
          <span class="text-xs text-slate-400">{{ number_format($product->rating ?? 0, 1) }}</span>
          <span class="text-xs text-slate-600">({{ $product->review_count ?? 0 }})</span>
        </div>
        <span class="text-[10px] text-slate-500">
          {{ number_format($product->sold_count ?? 0) }} terjual
        </span>
      </div>
    </div>
  </div>
</a>
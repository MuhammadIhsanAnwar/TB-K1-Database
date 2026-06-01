{{--
  Component: components/product-card.blade.php
  Itemku style product card.
--}}

@php
    $displayImage = $product->image_url ?? 'https://placehold.co/600x400/08399b/ffffff?text=Product';
    $rating = $product->rating_average ?? $product->rating ?? 0;
@endphp

<a href="{{ route('products.show', $product) }}" class="product-card premium-glow group relative flex flex-col overflow-hidden rounded-[28px] border border-white/[0.04] bg-gradient-to-br from-[#08111f] via-[#091225] to-[#050b15] backdrop-blur-xl transition-all duration-500 hover:-translate-y-3 hover:border-sky-400/30 hover:shadow-[0_35px_90px_rgba(14,165,233,.22)]">

  {{-- Top/Platform Ribbon --}}
  @if(($product->category?->slug ?? '') === 'top-up-game')
    <div class="absolute left-2 top-2 z-10 ribbon ribbon-accent">Top Up</div>
  @elseif(str_contains(strtolower($product->category?->name ?? ''), 'key'))
    <div class="absolute left-2 top-2 z-10 ribbon ribbon-blue">Game Key</div>
  @endif

  {{-- Thumbnail --}}
  <div class="relative w-full aspect-[16/9] overflow-hidden bg-[#020617]">
    <div class="absolute inset-0 bg-gradient-to-t from-[#020617] via-transparent to-transparent z-[1]"></div>
    <img src="{{ $displayImage }}"
         alt="{{ $product->name }}"
         class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
         loading="lazy"
         onerror="this.onerror=null;this.src='https://placehold.co/600x400/f3f4f6/9ca3af?text=No+Image';">
    
    {{-- Stock Badge --}}
    @if(($product->stock ?? 1) === 0)
      <div class="absolute inset-0 flex items-center justify-center bg-black/55 z-10">
        <span class="rounded-full border border-rose-400/20 bg-rose-500/20 px-3 py-1 text-xs font-bold text-rose-200">Habis</span>
      </div>
    @endif
  </div>

  {{-- Product Info --}}
  <div class="flex flex-1 flex-col p-3.5">
    <div class="mb-1 truncate text-[10px] font-semibold uppercase tracking-[0.18em] text-slate-500">
      {{ $product->category->name ?? 'Produk' }} • {{ $product->seller->store_name ?? $product->seller->name ?? 'Toko' }}
    </div>
    
    <h3 class="mb-2 flex-1 line-clamp-2 text-sm font-semibold leading-snug text-white transition-colors group-hover:text-sky-300">
      {{ $product->name }}
    </h3>
    
    <div class="mt-auto">
      <div class="mb-2 font-display text-lg font-black tracking-tight text-sky-300">
        Rp {{ number_format((float) ($product->price ?? 0), 0, ',', '.') }}
      </div>
      
      <div class="flex items-center gap-2 text-xs text-slate-400">
        <div class="flex items-center gap-1">
          <svg class="h-3.5 w-3.5 text-amber-400" fill="currentColor" viewBox="0 0 20 20">
            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
          </svg>
          <span class="font-semibold text-white">{{ number_format($rating, 1) }}</span>
        </div>
        <span class="h-1 w-1 rounded-full bg-slate-600"></span>
        <span class="text-slate-500">{{ number_format($product->sold_count ?? 0) }} terjual</span>
      </div>
    </div>

    {{-- Action Buttons (Dimasukkan ke dalam p-3.5 agar paddingnya sejajar) --}}
    <div class="mt-4 flex translate-y-3 gap-2 opacity-0 transition-all duration-300 group-hover:translate-y-0 group-hover:opacity-100">
      <button class="flex-1 whitespace-nowrap rounded-xl border border-sky-400/20 bg-sky-500/12 py-2 px-2 text-xs font-bold text-sky-200 transition hover:bg-sky-500/20">
        Beli Sekarang
      </button>

      <button class="flex-none rounded-xl border border-white/10 px-3 py-2 text-white/80 transition hover:bg-white/5 hover:text-white">
        +
      </button>
    </div>
  </div>
</a>
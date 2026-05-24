{{--
  Component: components/product-card.blade.php
  Itemku style product card.
--}}

@php
    $displayImage = $product->image_url ?? 'https://placehold.co/600x400/08399b/ffffff?text=Product';
    $rating = $product->rating_average ?? $product->rating ?? 0;
@endphp

<a href="{{ route('products.show', $product) }}" class="group flex flex-col surface-panel rounded-xl border border-gray-200 overflow-hidden hover:shadow-lg hover:border-itemku-blue transition-all duration-300 relative h-full">

  {{-- Top/Platform Ribbon --}}
  @if(($product->category?->slug ?? '') === 'top-up-game')
    <div class="absolute top-2 left-2 z-10 bg-yellow-400 text-yellow-900 text-[10px] font-bold px-2 py-0.5 rounded shadow-sm">
      Top Up
    </div>
  @elseif(str_contains(strtolower($product->category?->name ?? ''), 'key'))
    <div class="absolute top-2 left-2 z-10 bg-gray-800 text-white text-[10px] font-bold px-2 py-0.5 rounded shadow-sm">
      Game Key
    </div>
  @endif

  {{-- Thumbnail --}}
  <div class="relative w-full aspect-[4/3] overflow-hidden bg-gray-100">
    <img src="{{ $displayImage }}"
         alt="{{ $product->name }}"
         class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
         loading="lazy"
         onerror="this.onerror=null;this.src='https://placehold.co/600x400/f3f4f6/9ca3af?text=No+Image';">
    
    {{-- Stock Badge --}}
    @if(($product->stock ?? 1) === 0)
      <div class="absolute inset-0 bg-black/50 flex items-center justify-center">
        <span class="bg-red-500 text-white text-xs font-bold px-2 py-1 rounded">Habis</span>
      </div>
    @endif
  </div>

  {{-- Product Info --}}
  <div class="flex flex-col flex-1 p-3">
    <div class="text-[10px] text-gray-500 font-semibold mb-1 truncate">
      {{ $product->category->name ?? 'Produk' }} • {{ $product->seller->name ?? 'Toko' }}
    </div>
    
    <h3 class="text-sm font-semibold text-gray-800 line-clamp-2 leading-snug mb-2 flex-1 group-hover:text-itemku-blue transition-colors">
      {{ $product->name }}
    </h3>
    
    <div class="mt-auto">
      <div class="font-bold text-base text-gray-900 mb-1.5">
        Rp {{ number_format((float) ($product->price ?? 0), 0, ',', '.') }}
      </div>
      
      <div class="flex items-center text-xs text-gray-500 gap-2">
        <div class="flex items-center gap-1">
          <svg class="w-3.5 h-3.5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
          </svg>
          <span class="font-medium text-gray-700">{{ number_format($rating, 1) }}</span>
        </div>
        <span class="w-1 h-1 rounded-full bg-gray-300"></span>
        <span>{{ number_format($product->sold_count ?? 0) }} terjual</span>
      </div>
    </div>
  </div>
</a>
@extends('layouts.app')

@php
  $productName = data_get($product, 'name', 'Produk');
  $productCategory = data_get($product, 'category');
  $productSeller = data_get($product, 'seller');
  $storeName = data_get($productSeller, 'store_name', data_get($productSeller, 'name', 'Toko'));
  $productRating = data_get($product, 'rating_average', 0);
  $productReviews = data_get($product, 'reviews', collect());
  $productPrice = data_get($product, 'price', 0);
  $productStock = data_get($product, 'stock', '∞');
  $displayImage = data_get($product, 'image_url') ?? 'https://placehold.co/800x600/f3f4f6/9ca3af?text=No+Image';
@endphp

@section('title', $productName . ' — Lapak Gaming')

@push('styles')
<style>
  body { background-color: #f5f5f5; }
  .tab-btn.active {
    border-bottom: 2px solid #1e4aa3;
    color: #1e4aa3;
    font-weight: 600;
  }
</style>
@endpush

@section('content')
<div class="max-w-7xl mx-auto px-4 py-8" id="product-page" data-base-price="{{ data_get($product, 'price', 0) }}">

  {{-- Breadcrumb --}}
  <nav class="flex items-center gap-2 text-xs text-gray-500 mb-6">
    <a href="{{ route('marketplace.home') }}" class="hover:text-itemku-blue transition-colors">Beranda</a>
    <span>/</span>
    @if($productCategory)
      <a href="{{ route('categories.show', $productCategory->slug) }}" class="hover:text-itemku-blue transition-colors">{{ $productCategory->name }}</a>
    @else
      <span>Produk</span>
    @endif
    <span>/</span>
    <span class="text-gray-800 font-semibold truncate max-w-xs">{{ $productName }}</span>
  </nav>

  <div class="grid lg:grid-cols-[1fr_360px] gap-6">

    {{-- ─── LEFT COLUMN ─── --}}
    <div class="space-y-6">

      {{-- Main Product Card --}}
      <div class="surface-panel rounded-xl shadow-sm border border-gray-200 p-5 md:p-6 flex flex-col md:flex-row gap-6">
        
        {{-- Product Image --}}
        <div class="w-full md:w-5/12 shrink-0">
          <div class="rounded-lg overflow-hidden border border-gray-100 bg-gray-50 aspect-square">
            <img src="{{ $displayImage }}" alt="{{ $productName }}" class="w-full h-full object-cover">
          </div>
        </div>

        {{-- Product Basic Info --}}
        <div class="w-full md:w-7/12 flex flex-col">
          <h1 class="text-2xl font-bold text-gray-800 leading-snug mb-3">
            {{ $productName }}
          </h1>

          <div class="flex flex-wrap items-center gap-4 text-sm text-gray-600 mb-6 pb-4 border-b border-gray-100">
            <div class="flex items-center gap-1">
              <svg class="w-4 h-4 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
              </svg>
              <span class="font-bold text-gray-800">{{ number_format($productRating, 1) }}</span>
              <span class="text-gray-500">({{ data_get($product, 'review_count', 0) }} ulasan)</span>
            </div>
            <div class="flex items-center gap-1.5">
              <span>{{ number_format(data_get($product, 'sold_count', 0)) }} terjual</span>
            </div>
          </div>

          {{-- Safety badges --}}
          <div class="grid grid-cols-2 gap-3 mb-6">
            <div class="flex items-center gap-2 text-xs text-green-600 bg-green-50 px-3 py-2 rounded-lg border border-green-100">
              <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
              <span>Transaksi Aman</span>
            </div>
            <div class="flex items-center gap-2 text-xs text-blue-600 bg-blue-50 px-3 py-2 rounded-lg border border-blue-100">
              <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
              <span>Proses Cepat</span>
            </div>
          </div>

          {{-- Seller mini card --}}
          @if($productSeller)
          <div class="mt-auto bg-gray-50 rounded-xl p-4 border border-gray-100 flex items-center justify-between">
            <div class="flex items-center gap-3">
              <div class="w-10 h-10 rounded-full flex items-center justify-center font-bold text-white text-sm" style="background:linear-gradient(135deg,#1e4aa3,#307FE2);">
                {{ strtoupper(substr($storeName, 0, 2)) }}
              </div>
              <div>
                <div class="font-bold text-gray-800 text-sm">{{ $storeName }}</div>
                <div class="text-xs text-gray-500">Aktif {{ $productSeller->updated_at?->diffForHumans() ?? 'baru-baru ini' }}</div>
              </div>
            </div>
            <a href="{{ route('marketplace.store', $productSeller) }}" class="text-xs font-semibold text-itemku-blue hover:underline">
              Kunjungi Toko
            </a>
          </div>
          @endif
        </div>
      </div>

      {{-- Tabs Section --}}
      <div class="surface-panel rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="flex border-b border-gray-200">
          <button class="tab-btn active flex-1 py-3.5 text-sm font-medium text-gray-500 hover:text-gray-800" onclick="showTab('desc')">Deskripsi</button>
          <button class="tab-btn flex-1 py-3.5 text-sm font-medium text-gray-500 hover:text-gray-800" onclick="showTab('reviews')">Ulasan</button>
          <button class="tab-btn flex-1 py-3.5 text-sm font-medium text-gray-500 hover:text-gray-800" onclick="showTab('delivery')">Cara Trading</button>
        </div>

        <div class="p-6">
          {{-- Desc Tab --}}
          <div id="tab-desc" class="tab-content text-sm text-gray-700 leading-relaxed whitespace-pre-wrap">
            {{ data_get($product, 'description', 'Tidak ada deskripsi.') }}
          </div>

          {{-- Reviews Tab --}}
          <div id="tab-reviews" class="tab-content hidden">
            @forelse($productReviews->take(5) as $review)
            <div class="py-4 border-b border-gray-100 last:border-0">
              <div class="flex items-center gap-3 mb-2">
                <div class="w-8 h-8 rounded-full bg-gray-200 flex items-center justify-center font-bold text-xs text-gray-600">
                  {{ strtoupper(substr($review->user->name ?? 'U', 0, 1)) }}
                </div>
                <div>
                  <div class="flex items-center gap-2">
                    <span class="text-sm font-medium text-gray-800">{{ $review->user->name ?? 'Pengguna' }}</span>
                    <div class="flex gap-0.5">
                      @for($i=1;$i<=5;$i++)
                        <svg class="w-3 h-3 {{ $i <= $review->rating ? 'text-yellow-400' : 'text-gray-300' }}" viewBox="0 0 20 20" fill="currentColor"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                      @endfor
                    </div>
                  </div>
                  <span class="text-xs text-gray-400">{{ $review->created_at?->diffForHumans() }}</span>
                </div>
              </div>
              <p class="text-sm text-gray-600 pl-11">{{ $review->comment ?? '-' }}</p>
            </div>
            @empty
            <div class="py-8 text-center text-gray-500 text-sm">
              Belum ada ulasan untuk produk ini.
            </div>
            @endforelse
          </div>

          {{-- Delivery Tab --}}
          <div id="tab-delivery" class="tab-content hidden text-sm text-gray-700 leading-relaxed">
            @if(data_get($product, 'delivery_content'))
              {!! nl2br(e(data_get($product, 'delivery_content'))) !!}
            @else
              <p>1. Silakan lakukan pembayaran.<br>2. Penjual akan memproses pesanan Anda sesuai antrean.<br>3. Hubungi penjual melalui fitur chat jika ada pertanyaan.</p>
            @endif
          </div>
        </div>
      </div>

    </div>

    {{-- ─── RIGHT COLUMN — Sticky Purchase Panel ─── --}}
    <div class="space-y-4">
      <div class="sticky top-24">
        <div class="surface-panel rounded-xl shadow-sm border border-gray-200 p-5">
          <h2 class="text-lg font-bold text-gray-800 mb-4">Beli Produk</h2>
          
          <div class="font-bold text-3xl text-itemku-blue mb-1">
            Rp {{ number_format($productPrice, 0, ',', '.') }}
          </div>
          <div class="text-xs text-gray-500 mb-6">
            Stok Tersedia: <span class="font-medium text-gray-800">{{ $productStock }}</span>
          </div>

          @auth
            @if($errors->any())
            <div class="mb-4 p-3 bg-red-50 border border-red-200 rounded-lg">
              <ul class="list-disc list-inside space-y-1 text-red-600 text-xs">
                @foreach ($errors->all() as $error)
                  <li>{{ $error }}</li>
                @endforeach
              </ul>
            </div>
            @endif

            <form method="GET" action="{{ route('checkout.product', $product) }}" class="space-y-4">
              {{-- Quantity --}}
              <div class="flex items-center justify-between">
                <label class="text-sm text-gray-600 font-medium">Jumlah Pembelian</label>
                <div class="flex items-center gap-1 bg-gray-50 border border-gray-200 rounded-lg p-1">
                  <button type="button" onclick="changeQty(-1)" class="w-7 h-7 flex items-center justify-center font-bold text-gray-500 hover:text-itemku-blue hover:surface-weak rounded transition-colors">−</button>
                  <input type="number" id="qty-input" name="quantity" min="1" max="{{ data_get($product, 'stock', 999) }}" value="1" class="w-10 text-center text-sm font-semibold text-gray-800 bg-transparent border-none outline-none appearance-none" />
                  <button type="button" onclick="changeQty(1)" class="w-7 h-7 flex items-center justify-center font-bold text-gray-500 hover:text-itemku-blue hover:surface-weak rounded transition-colors">+</button>
                </div>
              </div>

              {{-- Total preview --}}
              <div class="flex items-center justify-between pt-4 border-t border-gray-100">
                <span class="text-sm font-semibold text-gray-600">Total Harga</span>
                <span id="total-price" class="font-bold text-lg text-gray-800">
                  Rp {{ number_format($productPrice, 0, ',', '.') }}
                </span>
              </div>

              <button type="submit" class="w-full py-3 bg-itemku-blue hover:bg-blue-800 text-white font-bold rounded-lg transition-colors shadow-sm">
                Beli Sekarang
              </button>
            </form>

            <div class="flex gap-2 mt-3">
              <form method="POST" action="{{ route('cart.add') }}" class="flex-1">
                @csrf
                <input type="hidden" name="product_id" value="{{ data_get($product, 'id') }}">
                <input type="hidden" name="quantity" value="1">
                <button type="submit" class="w-full py-2.5 surface-weak border border-gray-300 hover:brightness-105 text-gray-700 font-semibold text-sm rounded-lg transition-colors">
                  + Keranjang
                </button>
              </form>
              
              @if(auth()->id() !== data_get($productSeller, 'id'))
              <a href="{{ route('chat.product', $product) }}" class="flex-1 flex items-center justify-center py-2.5 surface-weak border border-itemku-blue text-itemku-blue hover:brightness-105 font-semibold text-sm rounded-lg transition-colors">
                💬 Chat
              </a>
              @endif
            </div>

          @else
            <div class="space-y-3">
              <a href="{{ route('login') }}" class="block w-full py-3 bg-itemku-blue hover:bg-blue-800 text-white text-center font-bold rounded-lg transition-colors shadow-sm">
                Masuk untuk Membeli
              </a>
            </div>
          @endauth
        </div>
      </div>
    </div>
  </div>

  {{-- Related Products --}}
  @if($relatedProducts->isNotEmpty())
  <div class="mt-12">
    <h2 class="text-xl font-bold text-gray-800 mb-6">Produk Serupa</h2>
    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4">
      @foreach($relatedProducts as $related)
        @include('components.product-card', ['product' => $related])
      @endforeach
    </div>
  </div>
  @endif

</div>
@endsection

@push('scripts')
<script>
  const productPage = document.getElementById('product-page');
  const basePrice = Number(productPage?.dataset.basePrice || 0);
  const qtyInput = document.getElementById('qty-input');
  const totalEl = document.getElementById('total-price');

  function changeQty(delta) {
    const max = parseInt(qtyInput.max) || 999;
    let val = parseInt(qtyInput.value) + delta;
    qtyInput.value = Math.max(1, Math.min(val, max));
    updateTotal();
  }

  function updateTotal() {
    const qty = parseInt(qtyInput.value) || 1;
    const total = qty * basePrice;
    totalEl.textContent = 'Rp ' + total.toLocaleString('id-ID');
  }

  if(qtyInput) qtyInput.addEventListener('input', updateTotal);

  function showTab(tabName) {
    document.querySelectorAll('.tab-content').forEach(el => el.classList.add('hidden'));
    document.getElementById('tab-' + tabName).classList.remove('hidden');
    
    document.querySelectorAll('.tab-btn').forEach(el => el.classList.remove('active'));
    event.currentTarget.classList.add('active');
  }
</script>
@endpush

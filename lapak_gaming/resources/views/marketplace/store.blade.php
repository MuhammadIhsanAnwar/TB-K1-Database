@extends('layouts.app')

@php
  $storeName = data_get($seller, 'store_name', data_get($seller, 'name', 'Toko'));
  $storePhoto = data_get($seller, 'shop_photo_url');
  $storeBio = data_get($seller, 'shop_description') ?: data_get($seller, 'bio');
  $productsCount = data_get($seller, 'products_count', 0);
@endphp

@section('title', $storeName . ' — Profil Toko')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-10">
  <div class="mb-6">
    <nav class="flex items-center gap-2 text-xs text-slate-500">
      <a href="{{ route('marketplace.home') }}" class="hover:text-slate-300 transition-colors">Beranda</a>
      <span>/</span>
      <span class="text-slate-300">Profil Toko</span>
    </nav>
  </div>

  <div class="grid lg:grid-cols-[320px_1fr] gap-8 items-start">
    <aside class="card p-6 sticky top-24 space-y-5">
      <div class="flex items-center gap-4">
        <div class="w-20 h-20 rounded-3xl overflow-hidden bg-slate-900 border border-slate-800 flex items-center justify-center">
          @if($storePhoto)
            <img src="{{ $storePhoto }}" alt="{{ $storeName }}" class="w-full h-full object-cover">
          @else
            <span class="text-2xl font-bold text-white">{{ strtoupper(substr($storeName, 0, 2)) }}</span>
          @endif
        </div>
        <div>
          <div class="text-xs text-slate-500 uppercase tracking-wider">Profil Toko</div>
          <h1 class="text-2xl font-bold text-white">{{ $storeName }}</h1>
          <p class="text-sm text-slate-400">{{ $productsCount }} produk dijual</p>
        </div>
      </div>

      @if($storeBio)
        <div>
          <h2 class="text-sm font-semibold text-white mb-2">Tentang Toko</h2>
          <p class="text-sm leading-relaxed text-slate-400">{{ $storeBio }}</p>
        </div>
      @endif

      <div class="grid grid-cols-2 gap-3">
        <div class="rounded-2xl p-4 bg-slate-950 border border-slate-800">
          <div class="text-[10px] uppercase tracking-wider text-slate-500">Produk</div>
          <div class="mt-1 text-lg font-bold text-white">{{ $productsCount }}</div>
        </div>
        <div class="rounded-2xl p-4 bg-slate-950 border border-slate-800">
          <div class="text-[10px] uppercase tracking-wider text-slate-500">Toko</div>
          <div class="mt-1 text-lg font-bold text-white">Aktif</div>
        </div>
      </div>
    </aside>

    <section class="space-y-6">
      <div class="flex items-end justify-between gap-4 flex-wrap">
        <div>
          <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full border border-cyan-400/15 bg-cyan-400/5 text-cyan-300 text-xs font-bold tracking-wide mb-3">
            Produk dari toko ini
          </div>
          <h2 class="text-3xl md:text-4xl font-black text-white">{{ $storeName }}</h2>
          <p class="mt-2 text-slate-400">Semua produk aktif yang dijual oleh toko ini.</p>
        </div>
      </div>

      @if($products->count())
        <div class="grid grid-cols-2 sm:grid-cols-3 xl:grid-cols-4 gap-4">
          @foreach($products as $product)
            @include('components.product-card', ['product' => $product])
          @endforeach
        </div>

        <div class="mt-8">
          {{ $products->links() }}
        </div>
      @else
        <div class="card p-8 text-center">
          <div class="text-4xl mb-3">🛍️</div>
          <h3 class="text-lg font-bold text-white">Belum ada produk</h3>
          <p class="mt-2 text-slate-400">Toko ini belum memiliki produk aktif yang bisa dibeli.</p>
        </div>
      @endif
    </section>
  </div>
</div>
@endsection

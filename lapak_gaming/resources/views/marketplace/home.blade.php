@extends('layouts.app')
@section('title', 'Lapak Gaming — Marketplace Game Terpercaya Indonesia')

@section('content')
    {{-- HERO BANNER --}}
    <section class="bg-gradient-to-br from-blue-900 via-gray-900 to-orange-900 py-16">
        <div class="max-w-7xl mx-auto px-4 text-center">
            <h1 class="text-4xl md:text-5xl font-bold mb-4 bg-gradient-to-r from-blue-400 to-orange-400 bg-clip-text text-transparent">
                Marketplace Game <span class="text-cyan-400">Terpercaya</span>
            </h1>
            <p class="text-gray-400 text-lg mb-8">Top up, beli item, akun, dan voucher game favoritmu dengan harga terbaik & transaksi aman.</p>
            <div class="flex flex-wrap justify-center gap-4 text-sm">
                <div class="flex items-center gap-2 bg-gray-800/60 px-4 py-2 rounded-full">
                    <span class="text-cyan-400">✓</span> Transaksi Aman
                </div>
                <div class="flex items-center gap-2 bg-gray-800/60 px-4 py-2 rounded-full">
                    <span class="text-cyan-400">✓</span> Garansi Uang Kembali
                </div>
                <div class="flex items-center gap-2 bg-gray-800/60 px-4 py-2 rounded-full">
                    <span class="text-cyan-400">✓</span> Ribuan Seller Terverifikasi
                </div>
            </div>
        </div>
    </section>

    {{-- SHORTCUT KATEGORI --}}
    <section class="max-w-7xl mx-auto px-4 py-10">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-xl font-bold text-white">Kategori Utama</h2>
            <a href="{{ route('products.search') }}" class="text-smtext-cyan-400 hover:text-cyan-300">Lihat Semua Produk →</a>
        </div>
        <div class="grid grid-cols-2 sm:grid-cols-4 md:grid-cols-5 gap-3">
            @foreach($categories as $cat)
                <a href="{{ route('categories.show', $cat->slug) }}"
                   class="flex flex-col items-center gap-2 group bg-gray-900 rounded-3xl p-4 hover:border-cyan-500 border border-gray-800 transition">
                    <div class="w-16 h-16 rounded-2xl bg-gray-800 group-hover:bg-gradient-to-br group-hover:from-blue-500 group-hover:to-orange-500 transition flex items-center justify-center overflow-hidden">
                        @if($cat->image)
                            <img src="{{ $cat->image_url }}" alt="{{ $cat->name }}" class="w-12 h-12 object-cover rounded-lg">
                        @else
                            <span class="text-2xl">🎮</span>
                        @endif
                    </div>
                    <span class="text-sm text-gray-300 text-center">{{ \Illuminate\Support\Str::limit($cat->name, 14) }}</span>
                </a>
            @endforeach
        </div>
    </section>

    {{-- FITUR PRODUK --}}
    <section class="max-w-7xl mx-auto px-4 pb-10">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-xl font-bold text-white">Produk Terpopuler</h2>
            <a href="{{ route('products.search') }}" class="text-sm text-cyan-400 hover:text-cyan-300">Lihat Semua →</a>
        </div>
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-4">
            @forelse($popularProducts as $product)
                @include('components.product-card', ['product' => $product])
            @empty
                <div class="col-span-full text-center text-gray-400 py-12">Produk populer belum tersedia.</div>
            @endforelse
        </div>
    </section>

    {{-- TOP UP SECTION --}}
    @if($topupProducts->isNotEmpty())
    <section class="max-w-7xl mx-auto px-4 pb-10">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-xl font-bold text-white">⚡ Top Up Game</h2>
            <a href="{{ route('products.by-type', 'topup') }}" class="text-sm text-cyan-400 hover:text-cyan-300">Lihat Semua Top Up →</a>
        </div>
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4">
            @foreach($topupProducts as $product)
                @include('components.product-card', ['product' => $product])
            @endforeach
        </div>
    </section>
    @endif
@endsection
@extends('layouts.app')

@section('title', 'Home - Lapak Gaming')

@section('content')
<!-- Hero Section -->
<div class="bg-gradient-to-r from-blue-600 to-purple-600 text-white py-20">
    <div class="container mx-auto px-4 text-center">
        <h1 class="text-5xl font-bold mb-4">Selamat Datang di Lapak Gaming</h1>
        <p class="text-xl mb-8">Platform terpercaya untuk jual beli akun game, item game, dan voucher game</p>
        <a href="#featured" class="bg-white text-blue-600 px-8 py-3 rounded-full font-semibold hover:bg-gray-100 transition">
            Jelajahi Produk
        </a>
    </div>
</div>

<!-- Featured Products -->
<section id="featured" class="py-16">
    <div class="container mx-auto px-4">
        <h2 class="text-3xl font-bold mb-8">Produk Unggulan</h2>
        
        @if($featured->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach($featured as $product)
                    <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-xl transition">
                        <div class="relative">
                            @if($product->thumbnail)
                                <img src="{{ Storage::url($product->thumbnail) }}" alt="{{ $product->name }}" class="w-full h-48 object-cover">
                            @else
                                <div class="w-full h-48 bg-gray-200 flex items-center justify-center">
                                    <span class="text-gray-400">No Image</span>
                                </div>
                            @endif
                            <span class="absolute top-2 right-2 bg-yellow-400 text-yellow-900 px-2 py-1 rounded text-xs font-bold">
                                Featured
                            </span>
                        </div>
                        <div class="p-4">
                            <h3 class="font-semibold text-lg mb-2 truncate">{{ $product->name }}</h3>
                            <p class="text-gray-600 text-sm mb-2">{{ $product->category->name ?? 'Tanpa Kategori' }}</p>
                            <div class="flex justify-between items-center">
                                <span class="text-blue-600 font-bold">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                                <a href="{{ route('product.show', $product->slug) }}" class="text-blue-600 hover:text-blue-800">
                                    Detail →
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-12 bg-gray-100 rounded-lg">
                <p class="text-gray-600">Belum ada produk unggulan</p>
            </div>
        @endif
    </div>
</section>

<!-- Trending Products -->
<section class="py-16 bg-gray-50">
    <div class="container mx-auto px-4">
        <h2 class="text-3xl font-bold mb-8">Produk Trending</h2>
        
        @if($trending->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach($trending as $product)
                    <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-xl transition">
                        <div class="relative">
                            @if($product->thumbnail)
                                <img src="{{ Storage::url($product->thumbnail) }}" alt="{{ $product->name }}" class="w-full h-48 object-cover">
                            @else
                                <div class="w-full h-48 bg-gray-200 flex items-center justify-center">
                                    <span class="text-gray-400">No Image</span>
                                </div>
                            @endif
                            @if($product->stock < 5)
                                <span class="absolute top-2 right-2 bg-red-500 text-white px-2 py-1 rounded text-xs font-bold">
                                    Stock Terbatas
                                </span>
                            @endif
                        </div>
                        <div class="p-4">
                            <h3 class="font-semibold text-lg mb-2 truncate">{{ $product->name }}</h3>
                            <p class="text-gray-600 text-sm mb-2">{{ $product->category->name ?? 'Tanpa Kategori' }}</p>
                            <div class="flex items-center text-sm text-gray-500 mb-2">
                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"/>
                                    <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"/>
                                </svg>
                                {{ $product->view_count }} views
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-blue-600 font-bold">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                                <a href="{{ route('product.show', $product->slug) }}" class="text-blue-600 hover:text-blue-800">
                                    Detail →
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-12 bg-white rounded-lg">
                <p class="text-gray-600">Belum ada produk trending</p>
            </div>
        @endif
    </div>
</section>

<!-- Newest Products -->
<section class="py-16">
    <div class="container mx-auto px-4">
        <h2 class="text-3xl font-bold mb-8">Produk Terbaru</h2>
        
        @if($newest->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach($newest as $product)
                    <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-xl transition">
                        <div class="relative">
                            @if($product->thumbnail)
                                <img src="{{ Storage::url($product->thumbnail) }}" alt="{{ $product->name }}" class="w-full h-48 object-cover">
                            @else
                                <div class="w-full h-48 bg-gray-200 flex items-center justify-center">
                                    <span class="text-gray-400">No Image</span>
                                </div>
                            @endif
                            <span class="absolute top-2 right-2 bg-green-500 text-white px-2 py-1 rounded text-xs font-bold">
                                New
                            </span>
                        </div>
                        <div class="p-4">
                            <h3 class="font-semibold text-lg mb-2 truncate">{{ $product->name }}</h3>
                            <p class="text-gray-600 text-sm mb-2">{{ $product->category->name ?? 'Tanpa Kategori' }}</p>
                            <p class="text-xs text-gray-500 mb-2">
                                <span class="font-semibold">Seller:</span> {{ $product->seller->sellerAccount->shop_name ?? $product->seller->name ?? 'Seller' }}
                            </p>
                            <div class="flex justify-between items-center">
                                <span class="text-blue-600 font-bold">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                                <a href="{{ route('product.show', $product->slug) }}" class="text-blue-600 hover:text-blue-800">
                                    Detail →
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-12 bg-gray-100 rounded-lg">
                <p class="text-gray-600">Belum ada produk terbaru</p>
            </div>
        @endif
    </div>
</section>

<!-- Categories -->
<section class="py-16 bg-gray-50">
    <div class="container mx-auto px-4">
        <h2 class="text-3xl font-bold mb-8">Kategori Populer</h2>
        
        @if($categories->count() > 0)
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
                @foreach($categories as $category)
                    <a href="{{ route('category', $category->slug) }}" class="bg-white p-6 rounded-lg shadow hover:shadow-lg transition text-center">
                        <div class="text-4xl mb-3">🎮</div>
                        <h3 class="font-semibold">{{ $category->name }}</h3>
                        <p class="text-sm text-gray-600">{{ $category->products_count ?? 0 }} produk</p>
                    </a>
                @endforeach
            </div>
        @else
            <div class="text-center py-12 bg-white rounded-lg">
                <p class="text-gray-600">Belum ada kategori</p>
            </div>
        @endif
    </div>
</section>

<!-- Why Choose Us -->
<section class="py-16">
    <div class="container mx-auto px-4">
        <h2 class="text-3xl font-bold mb-12 text-center">Mengapa Pilih Lapak Gaming?</h2>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="text-center">
                <div class="bg-blue-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                    </svg>
                </div>
                <h3 class="font-bold text-xl mb-2">Transaksi Aman</h3>
                <p class="text-gray-600">Sistem escrow melindungi pembeli dan penjual</p>
            </div>
            
            <div class="text-center">
                <div class="bg-green-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <h3 class="font-bold text-xl mb-2">Harga Kompetitif</h3>
                <p class="text-gray-600">Harga terbaik dari berbagai seller terpercaya</p>
            </div>
            
            <div class="text-center">
                <div class="bg-purple-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                    </svg>
                </div>
                <h3 class="font-bold text-xl mb-2">Proses Cepat</h3>
                <p class="text-gray-600">Sistem otomatis untuk transaksi lebih cepat</p>
            </div>
        </div>
    </div>
</section>
@endsection

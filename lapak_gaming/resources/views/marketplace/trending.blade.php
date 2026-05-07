@extends('layouts.app')

@section('title', 'Sedang Trending - Lapak Gaming')

@section('content')
{{-- Tambahkan relatif z-10 agar tidak tertutup background gradient di layout --}}
<div class="container mx-auto px-4 py-12 relative z-10">
    
    {{-- Header Halaman --}}
    <div class="mb-10 text-center md:text-left">
        <h1 class="text-4xl md:text-5xl font-bold font-display text-white mb-3 tracking-wide">
            🔥 Sedang <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-400 to-orange-400">Trending</span>
        </h1>
        <p class="text-gray-400 text-lg">Item paling panas dan paling banyak diburu gamers saat ini!</p>
    </div>

    {{-- Mengecek apakah ada data produk --}}
    @if($products->count() > 0)
        
        {{-- Grid Produk --}}
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 md:gap-6">
            @foreach($products as $item)
                {{-- Card menggunakan class custom: product-card & card-glow --}}
                <div class="product-card card-glow bg-gray-925/80 backdrop-blur-sm rounded-xl overflow-hidden group cursor-pointer flex flex-col h-full">
                    
                    {{-- Ribbon Badge dari custom CSS --}}
                    <div class="ribbon z-10 shadow-glow-sm">HOT</div>

                    {{-- Area Gambar menggunakan efek Skeleton untuk placeholder --}}
                    <div class="h-36 md:h-44 w-full skeleton flex items-center justify-center relative overflow-hidden shrink-0">
                        {{-- Ganti tag ini nanti kalau gambarnya sudah ada di database --}}
                        {{-- <img src="{{ asset('storage/' . $item->image) }}" alt="{{ $item->name }}" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110"> --}}
                    </div>

                    {{-- Info Produk --}}
                    <div class="p-4 relative z-10 flex flex-col flex-grow">
                        @if($item->category)
                            <span class="text-xs font-semibold text-gray-400 mb-1 block uppercase tracking-wider">{{ $item->category->name }}</span>
                        @endif
                        
                        <h3 class="text-gray-100 font-medium text-sm md:text-base line-clamp-2 mb-4 group-hover:text-blue-400 transition-colors duration-300">
                            {{ $item->name }}
                        </h3>
                        
                        {{-- Harga menggunakan class price-text & ditaruh di bawah menggunakan mt-auto --}}
                        <div class="flex items-center justify-between mt-auto">
                            <span class="font-display font-bold text-lg md:text-xl price-text">
                                Rp {{ number_format($item->price, 0, ',', '.') }}
                            </span>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Pagination (Tombol Next/Prev Halaman) --}}
        <div class="mt-12 flex justify-center">
            {{ $products->links() }} 
        </div>

    @else
        {{-- Tampilan jika produk kosong, disesuaikan dengan tema --}}
        <div class="flex flex-col items-center justify-center py-24 card-glow bg-gray-900/40 backdrop-blur-md rounded-2xl border border-gray-800">
            {{-- Menggunakan animasi badge-live dari CSS kamu --}}
            <div class="text-6xl mb-6 badge-live inline-block">👻</div>
            <h2 class="text-2xl text-white font-display font-semibold mb-2">Belum ada item yang trending</h2>
            <p class="text-gray-400">Ayo mulai transaksi biar item favoritmu masuk ke sini!</p>
        </div>
    @endif

</div>
@endsection
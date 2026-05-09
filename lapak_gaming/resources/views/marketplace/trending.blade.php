@extends('layouts.app')
@section('title', 'Sedang Trending - Lapak Gaming')

@section('content')
    <section class="py-10">
        <div class="max-w-7xl mx-auto px-4">
            
            {{-- Header Halaman --}}
            <div class="mb-8">
                <h1 class="text-3xl md:text-4xl font-bold mb-2 bg-gradient-to-r from-blue-400 to-orange-400 bg-clip-text text-transparent">
                    🔥 Sedang <span class="text-cyan-400">Trending</span>
                </h1>
                <p class="text-gray-400 text-sm md:text-base">Item paling panas dan banyak diburu gamers saat ini!</p>
            </div>

            {{-- Area Produk --}}
            @if($products->count() > 0)
                {{-- Grid mengikuti halaman utama: 2 kolom di HP, 3 di Tablet, 6 di Desktop --}}
                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-4">
                @foreach($products as $product)
                <div class="reveal-card reveal-delay-{{ ($loop->index % 6) + 1 }}">
                    @include('components.product-card', ['product' => $product])
                </div>
                 @endforeach
                </div>

                {{-- Pagination (Tombol Next/Prev Halaman) --}}
                <div class="mt-10 flex justify-center">
                    {{ $products->links() }} 
                </div>

            @else
                {{-- Tampilan jika produk kosong --}}
                <div class="col-span-full text-center text-gray-400 py-20 bg-gray-900 rounded-3xl border border-gray-800">
                    <div class="text-5xl mb-4">👻</div>
                    <h2 class="text-xl font-bold text-white mb-2">Belum ada item yang trending</h2>
                    <p class="text-sm">Ayo mulai transaksi biar item favoritmu masuk ke sini!</p>
                </div>
            @endif

        </div>
    </section>
    <script>
        document.addEventListener("DOMContentLoaded", () => {

            const reveals = document.querySelectorAll(".reveal-card");

            const observer = new IntersectionObserver((entries) => {

                entries.forEach(entry => {

                    if(entry.isIntersecting){
                        entry.target.classList.add("show");
                    }

                });

            }, {
                threshold: 0.12
            });

            reveals.forEach((el) => observer.observe(el));

        });
</script>
@endsection
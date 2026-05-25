@extends('layouts.app')
@section('title', 'Sedang Trending - Lapak Gaming')

@php
    $trendingProductsPaginator = is_object($products) && method_exists($products, 'links') ? $products : null;
    $trendingProductsList = $trendingProductsPaginator ? $trendingProductsPaginator->getCollection() : collect($products);
@endphp

@section('content')
    <section class="py-10">
        <div class="max-w-7xl mx-auto px-4">
            
            {{-- Header Halaman --}}
            <div class="mb-8">
                <h1 class="text-3xl md:text-4xl font-bold mb-2 bg-clip-text text-transparent" style="background-image: linear-gradient(90deg,var(--primary),var(--accent));">
                    🔥 Sedang <span class="text-accent">Trending</span>
                </h1>
                <p class="surface-muted text-sm md:text-base">Item paling panas dan banyak diburu gamers saat ini!</p>
            </div>

            {{-- Area Produk --}}
            @if($trendingProductsList->count() > 0)
                {{-- Grid mengikuti halaman utama: 2 kolom di HP, 3 di Tablet, 6 di Desktop --}}
                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-4">
                @foreach($trendingProductsList as $product)
                <div class="reveal-card reveal-delay-{{ ($loop->index % 6) + 1 }}">
                    @include('components.product-card', ['product' => $product])
                </div>
                 @endforeach
                </div>

                {{-- Pagination (Tombol Next/Prev Halaman) --}}
                <div class="mt-14 flex justify-center pagination-wrapper">
                    @if($trendingProductsPaginator)
                        {{ $trendingProductsPaginator->links() }}
                    @endif
                </div>
            @else
                {{-- Tampilan jika produk kosong --}}
                <div class="col-span-full text-center surface-muted py-20 bg-surface-weak rounded-3xl border border-white/10">
                    <div class="text-5xl mb-4">👻</div>
                    <h2 class="text-xl font-bold surface-text mb-2">Belum ada item yang trending</h2>
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
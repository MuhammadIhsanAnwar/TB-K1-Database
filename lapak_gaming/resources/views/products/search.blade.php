@extends('layouts.app')
@section('title', 'Cari Produk — Lapak Geming')

@section('content')
<section class="max-w-7xl mx-auto px-4 py-12">

    {{-- Header --}}
    <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between mb-8 reveal-card show">
        <div>
            <h1 class="text-3xl font-bold text-white">
                Hasil Pencarian
            </h1>

            <p class="text-sm text-gray-400 mt-2">
                Menemukan {{ $products->total() }} produk untuk "{{ $query }}".
            </p>
        </div>

        {{-- Search Form --}}
        <form action="{{ route('products.search') }}" method="GET"
              class="flex gap-2 w-full md:w-auto">

            <input
                type="text"
                name="q"
                value="{{ $query }}"
                placeholder="Cari produk..."
                class="w-full md:w-80 bg-gray-900 border border-gray-800 rounded-xl px-4 py-3 text-white
                       focus:outline-none focus:border-blue-500
                       transition-all duration-300"
            >

            <button
                type="submit"
                class="px-5 rounded-xl text-white font-medium transition-all duration-300
                       hover:scale-[1.03] active:scale-[0.98]"
                style="
                    background:linear-gradient(135deg,#2563eb,#f97316);
                    box-shadow:0 4px 18px rgba(37,99,235,.25);
                "
            >
                Cari
            </button>
        </form>
    </div>

    {{-- Product Grid --}}
    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4">

        @forelse($products as $index => $product)

            <div class="reveal-card reveal-delay-{{ ($index % 6) + 1 }}">
                @include('components.product-card', ['product' => $product])
            </div>

        @empty

            <div class="col-span-full rounded-3xl border border-gray-800 bg-gray-900 p-12 text-center text-gray-400 reveal-card show">

                <div class="text-5xl mb-4">🔍</div>

                <h2 class="text-xl font-bold text-white mb-2">
                    Produk tidak ditemukan
                </h2>

                <p class="text-sm">
                    Coba kata kunci lain atau lihat semua produk.
                </p>

            </div>

        @endforelse

    </div>

    {{-- Pagination --}}
    <div class="mt-10 flex justify-center reveal-card show">
        {{ $products->withQueryString()->links() }}
    </div>

</section>
@endsection
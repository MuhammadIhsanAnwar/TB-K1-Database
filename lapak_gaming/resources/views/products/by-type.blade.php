@extends('layouts.app')
@section('title', \App\Support\MarketplaceCategoryCatalog::labelForType($type) . ' — Lapak Gaming')

@php
    $productsPaginator = is_object($products) && method_exists($products, 'links') ? $products : null;
    $productsList = $productsPaginator ? $productsPaginator->getCollection() : collect($products);
@endphp

@section('content')
@php
    $label = \App\Support\MarketplaceCategoryCatalog::labelForType($type);
@endphp

<section class="max-w-7xl mx-auto px-4 py-12">
    <div class="mb-8 flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
        <div>
            <h1 class="font-display text-3xl font-black text-white">{{ $label }}</h1>
            <p class="mt-2 text-sm text-slate-400">Menampilkan produk {{ strtolower($label) }} terpopuler.</p>
        </div>
        <a href="{{ route('products.search') }}" class="inline-flex items-center gap-2 rounded-2xl border border-white/10 bg-white/[0.03] px-5 py-3 text-slate-200 transition hover:border-sky-400/20 hover:bg-sky-500/10 hover:text-sky-200">
            Lihat Semua Produk
        </a>
    </div>

    <div class="grid grid-cols-2 gap-4 sm:grid-cols-3 md:grid-cols-4">
        @forelse($productsList as $product)
            @include('components.product-card', ['product' => $product])
        @empty
            <div class="col-span-full rounded-[28px] border border-white/10 bg-white/[0.03] p-12 text-center text-slate-400">
                Belum ada produk {{ strtolower($label) }} tersedia.
            </div>
        @endforelse
    </div>

    <div class="mt-8">
        @if($productsPaginator)
            {{ $productsPaginator->withQueryString()->links() }}
        @endif
    </div>
</section>
@endsection
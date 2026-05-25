@extends('layouts.app')
@section('title', \App\Support\MarketplaceCategoryCatalog::labelForType($type) . ' — Lapak Geming')

@section('content')
@php
    $label = \App\Support\MarketplaceCategoryCatalog::labelForType($type);
@endphp

<section class="max-w-7xl mx-auto px-4 py-12">
    <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between mb-8">
        <div>
            <h1 class="text-3xl font-bold surface-text">{{ $label }}</h1>
                <p class="text-sm surface-muted mt-2">Menampilkan produk {{ strtolower($label) }} terpopuler.</p>
        </div>
        <a href="{{ route('products.search') }}" class="inline-flex items-center gap-2 rounded-xl border border-white/10 bg-surface-weak px-5 py-3 surface-text hover:border-primary hover:text-primary transition">
            Lihat Semua Produk
        </a>
    </div>

    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4">
        @forelse($products as $product)
            @include('components.product-card', ['product' => $product])
        @empty
            <div class="col-span-full rounded-3xl border border-white/10 bg-surface-weak p-12 text-center surface-muted">
                Belum ada produk {{ strtolower($label) }} tersedia.
            </div>
        @endforelse
    </div>

    <div class="mt-8">
        {{ $products->withQueryString()->links() }}
    </div>
</section>
@endsection
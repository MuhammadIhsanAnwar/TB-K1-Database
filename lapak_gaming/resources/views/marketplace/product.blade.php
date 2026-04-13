@extends('layouts.app')

@section('title', $product->name)

@section('content')
    <div class="grid gap-6 lg:grid-cols-[1.2fr_.8fr]">
        <section class="rounded-[2rem] border border-slate-200 bg-white p-8 shadow-sm dark:border-slate-800 dark:bg-slate-900">
            <div class="flex flex-wrap gap-2 text-xs font-bold uppercase tracking-[0.2em] text-slate-500">
                <span>{{ $product->category?->name }}</span>
                <span>•</span>
                <span>{{ $product->seller?->name }}</span>
            </div>
            <h1 class="mt-4 text-4xl font-black tracking-tight">{{ $product->name }}</h1>
            <div class="mt-4 flex items-center gap-4 text-sm text-slate-500">
                <span>⭐ {{ number_format($product->rating_average, 1) }} ({{ $product->review_count }} review)</span>
                <span>{{ $product->views_count }} views</span>
                <span>{{ $product->downloads_count }} download</span>
            </div>
            <div class="mt-8 rounded-[1.75rem] border border-dashed border-slate-200 bg-slate-50 p-8 dark:border-slate-700 dark:bg-slate-950/40">
                <div class="text-sm font-bold uppercase tracking-[0.2em] text-slate-400">Digital delivery</div>
                <p class="mt-4 text-sm leading-7 text-slate-600 dark:text-slate-300">{{ $product->delivery_content ?: 'Produk ini akan dikirim otomatis setelah escrow diaktifkan.' }}</p>
            </div>
            <div class="prose prose-slate mt-6 max-w-none dark:prose-invert">
                <p>{{ $product->description }}</p>
            </div>
        </section>

        <aside class="space-y-6">
            <div class="rounded-[2rem] bg-slate-950 p-6 text-white shadow-glow dark:bg-white dark:text-slate-950">
                <div class="text-sm font-bold uppercase tracking-[0.2em] text-slate-300 dark:text-slate-500">Harga</div>
                <div class="mt-3 text-4xl font-black">Rp {{ number_format($product->price, 0, ',', '.') }}</div>
                <div class="mt-2 text-sm text-slate-300 dark:text-slate-500">Stok tersedia: {{ $product->stock }}</div>

                @auth
                    <form method="POST" action="{{ route('checkout.store') }}" class="mt-6 space-y-3">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                        <input type="number" min="1" name="quantity" value="1" class="w-full rounded-2xl border border-slate-700 bg-slate-900 px-4 py-3 text-white outline-none dark:bg-white dark:text-slate-950" />
                        <button class="w-full rounded-2xl bg-emerald-500 px-4 py-3 font-bold text-white transition hover:bg-emerald-400">Checkout via Escrow</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="mt-6 block rounded-2xl bg-white px-4 py-3 text-center font-bold text-slate-950">Login untuk checkout</a>
                @endauth
            </div>

            <div class="rounded-[2rem] border border-slate-200 bg-white p-6 dark:border-slate-800 dark:bg-slate-900">
                <div class="text-sm font-bold uppercase tracking-[0.2em] text-slate-400">Seller</div>
                <div class="mt-3 text-xl font-black">{{ $product->seller?->name }}</div>
                <div class="mt-1 text-sm text-slate-500">Level: {{ $product->seller?->sellerLevel?->name ?? 'Starter' }}</div>
                <div class="mt-4 grid grid-cols-2 gap-3 text-sm">
                    <div class="rounded-2xl bg-slate-50 p-3 dark:bg-slate-950/40">Order rate<br><span class="font-bold">99%</span></div>
                    <div class="rounded-2xl bg-slate-50 p-3 dark:bg-slate-950/40">Response<br><span class="font-bold">3 menit</span></div>
                </div>
            </div>
        </aside>
    </div>

    <section class="mt-10">
        <h2 class="text-2xl font-black tracking-tight">Related Products</h2>
        <div class="mt-5 grid gap-5 sm:grid-cols-2 xl:grid-cols-3">
            @forelse ($relatedProducts as $related)
                <a href="{{ route('products.show', $related) }}" class="rounded-[1.75rem] border border-slate-200 bg-white p-5 shadow-sm transition hover:-translate-y-1 hover:shadow-glow dark:border-slate-800 dark:bg-slate-900">
                    <h3 class="text-lg font-bold">{{ $related->name }}</h3>
                    <p class="mt-2 text-sm text-slate-500">Rp {{ number_format($related->price, 0, ',', '.') }}</p>
                </a>
            @empty
                <div class="rounded-[1.75rem] border border-slate-200 bg-white p-5 text-sm text-slate-500 dark:border-slate-800 dark:bg-slate-900">Belum ada produk terkait.</div>
            @endforelse
        </div>
    </section>
@endsection
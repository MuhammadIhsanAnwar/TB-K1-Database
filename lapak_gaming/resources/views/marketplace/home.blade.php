@extends('layouts.app')

@section('title', 'Lapak Digital - Marketplace Produk Digital')

@section('content')
    <section class="grid gap-6 lg:grid-cols-[1.2fr_.8fr]">
        <div class="glass rounded-[2rem] border border-white/60 p-8 shadow-glow dark:border-slate-800">
            <span class="inline-flex rounded-full border border-sky-200 bg-sky-50 px-3 py-1 text-xs font-bold uppercase tracking-[0.25em] text-sky-700 dark:border-sky-900/50 dark:bg-sky-950/40 dark:text-sky-300">Marketplace Digital</span>
            <h1 class="mt-5 max-w-3xl text-4xl font-black leading-tight tracking-tight sm:text-5xl">Platform escrow digital untuk buyer, seller, dan admin dalam satu sistem.</h1>
            <p class="mt-4 max-w-2xl text-base leading-7 text-slate-600 dark:text-slate-300">Rancang marketplace produk digital dengan checkout aman, wallet internal, chat polling, invoice otomatis, dan kontrol role yang rapi.</p>

            <div class="mt-6 grid gap-3 sm:grid-cols-3">
                <div class="rounded-2xl border border-slate-200 bg-white/80 p-4 dark:border-slate-800 dark:bg-slate-900/80">
                    <div class="text-2xl font-black text-slate-950 dark:text-white">{{ $latestProducts->count() }}</div>
                    <div class="text-sm text-slate-500">Produk aktif</div>
                </div>
                <div class="rounded-2xl border border-slate-200 bg-white/80 p-4 dark:border-slate-800 dark:bg-slate-900/80">
                    <div class="text-2xl font-black text-slate-950 dark:text-white">{{ $categories->count() }}</div>
                    <div class="text-sm text-slate-500">Kategori bertingkat</div>
                </div>
                <div class="rounded-2xl border border-slate-200 bg-white/80 p-4 dark:border-slate-800 dark:bg-slate-900/80">
                    <div class="text-2xl font-black text-slate-950 dark:text-white">3s</div>
                    <div class="text-sm text-slate-500">Polling chat</div>
                </div>
            </div>

            <div class="mt-6 flex flex-col gap-3 sm:flex-row">
                <input id="market-search" value="{{ $search }}" placeholder="Cari produk, kategori, atau seller..." class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm outline-none ring-0 transition focus:border-slate-400 dark:border-slate-700 dark:bg-slate-950" />
                <button id="search-button" class="rounded-2xl bg-slate-950 px-5 py-3 text-sm font-bold text-white dark:bg-white dark:text-slate-950">Cari</button>
            </div>

            <div class="mt-5 flex flex-wrap gap-2">
                @foreach ($categories as $category)
                    <span class="rounded-full border border-slate-200 px-3 py-1 text-xs font-semibold text-slate-600 dark:border-slate-800 dark:text-slate-300">{{ $category->name }}</span>
                @endforeach
            </div>
        </div>

        <div class="grid gap-4">
            <div class="rounded-[2rem] bg-slate-950 p-8 text-white shadow-glow dark:bg-slate-900">
                <div class="text-sm uppercase tracking-[0.3em] text-slate-300">Escrow Flow</div>
                <div class="mt-4 space-y-3 text-sm text-slate-200">
                    <div>1. Buyer checkout dan dana masuk escrow.</div>
                    <div>2. Seller kirim item digital.</div>
                    <div>3. Buyer konfirmasi dan saldo dilepas.</div>
                    <div>4. Platform memotong fee otomatis.</div>
                </div>
            </div>
            <div class="rounded-[2rem] border border-slate-200 bg-white p-8 dark:border-slate-800 dark:bg-slate-900">
                <div class="text-sm font-bold uppercase tracking-[0.2em] text-slate-500">Fitur inti</div>
                <ul class="mt-4 space-y-3 text-sm text-slate-600 dark:text-slate-300">
                    <li>• Auth, verifikasi email, dan reset password.</li>
                    <li>• Wallet internal, deposit, withdraw, dan histori.</li>
                    <li>• Chat AJAX polling 3 detik dengan unread state.</li>
                    <li>• Review, invoice, notifikasi, dan role middleware.</li>
                </ul>
            </div>
        </div>
    </section>

    <section class="mt-10 grid gap-6">
        <div>
            <h2 class="text-2xl font-black tracking-tight">Featured Products</h2>
            <p class="mt-1 text-sm text-slate-500">Produk unggulan yang paling siap dijual.</p>
        </div>
        <div class="grid gap-5 sm:grid-cols-2 xl:grid-cols-4">
            @foreach ($featuredProducts as $product)
                <a href="{{ route('products.show', $product) }}" class="rounded-[1.75rem] border border-slate-200 bg-white p-5 shadow-sm transition hover:-translate-y-1 hover:shadow-glow dark:border-slate-800 dark:bg-slate-900">
                    <div class="flex items-center justify-between">
                        <span class="rounded-full bg-emerald-100 px-3 py-1 text-xs font-bold text-emerald-700 dark:bg-emerald-950/40 dark:text-emerald-300">{{ $product->category?->name }}</span>
                        <span class="text-xs text-slate-400">{{ $product->seller?->name }}</span>
                    </div>
                    <h3 class="mt-4 text-lg font-bold">{{ $product->name }}</h3>
                    <p class="mt-2 line-clamp-2 text-sm text-slate-500">{{ $product->description }}</p>
                    <div class="mt-5 flex items-center justify-between">
                        <div class="text-lg font-black">Rp {{ number_format($product->price, 0, ',', '.') }}</div>
                        <div class="text-sm text-slate-500">⭐ {{ number_format($product->rating_average, 1) }}</div>
                    </div>
                </a>
            @endforeach
        </div>
    </section>

    <section class="mt-10 grid gap-6">
        <div>
            <h2 class="text-2xl font-black tracking-tight">Trending Products</h2>
            <p class="mt-1 text-sm text-slate-500">Produk yang paling sering dilihat dan dibeli.</p>
        </div>
        <div class="grid gap-5 sm:grid-cols-2 xl:grid-cols-4" id="product-list">
            @foreach ($trendingProducts as $product)
                <a href="{{ route('products.show', $product) }}" class="rounded-[1.75rem] border border-slate-200 bg-white p-5 shadow-sm transition hover:-translate-y-1 hover:shadow-glow dark:border-slate-800 dark:bg-slate-900">
                    <div class="text-xs font-semibold uppercase tracking-[0.25em] text-slate-400">Trending</div>
                    <h3 class="mt-3 text-lg font-bold">{{ $product->name }}</h3>
                    <p class="mt-2 text-sm text-slate-500">{{ $product->category?->name }} • {{ $product->seller?->name }}</p>
                    <div class="mt-5 flex items-center justify-between">
                        <div class="text-lg font-black">Rp {{ number_format($product->price, 0, ',', '.') }}</div>
                        <div class="rounded-full bg-slate-100 px-3 py-1 text-xs font-bold text-slate-600 dark:bg-slate-800 dark:text-slate-300">{{ $product->review_count }} review</div>
                    </div>
                </a>
            @endforeach
        </div>
    </section>

    <section class="mt-10 grid gap-6">
        <div>
            <h2 class="text-2xl font-black tracking-tight">Latest Products</h2>
            <p class="mt-1 text-sm text-slate-500">Katalog terbaru yang langsung bisa dijelajahi.</p>
        </div>
        <div class="grid gap-5 sm:grid-cols-2 xl:grid-cols-4">
            @foreach ($latestProducts as $product)
                <a href="{{ route('products.show', $product) }}" class="rounded-[1.75rem] border border-slate-200 bg-white p-5 shadow-sm transition hover:-translate-y-1 hover:shadow-glow dark:border-slate-800 dark:bg-slate-900">
                    <div class="flex items-center justify-between text-xs font-semibold uppercase tracking-[0.2em] text-slate-400">
                        <span>{{ $product->category?->name }}</span>
                        <span>{{ $product->seller?->name }}</span>
                    </div>
                    <h3 class="mt-4 text-lg font-bold">{{ $product->name }}</h3>
                    <p class="mt-2 text-sm text-slate-500">{{ Str::limit($product->description, 90) }}</p>
                    <div class="mt-5 text-lg font-black">Rp {{ number_format($product->price, 0, ',', '.') }}</div>
                </a>
            @endforeach
        </div>
    </section>

    <script>
        const searchInput = document.getElementById('market-search');
        const searchButton = document.getElementById('search-button');
        const productList = document.getElementById('product-list');
        let searchTimer = null;

        const renderResults = (items) => {
            productList.innerHTML = items.map((item) => `
                <a href="/products/${item.slug}" class="rounded-[1.75rem] border border-slate-200 bg-white p-5 shadow-sm transition hover:-translate-y-1 hover:shadow-glow dark:border-slate-800 dark:bg-slate-900">
                    <div class="text-xs font-semibold uppercase tracking-[0.25em] text-slate-400">${item.category ?? 'Digital'}</div>
                    <h3 class="mt-3 text-lg font-bold">${item.name}</h3>
                    <p class="mt-2 text-sm text-slate-500">${item.seller ?? 'Marketplace'}</p>
                    <div class="mt-5 flex items-center justify-between">
                        <div class="text-lg font-black">Rp ${item.price}</div>
                        <div class="text-sm text-slate-500">⭐ ${Number(item.rating_average).toFixed(1)}</div>
                    </div>
                </a>
            `).join('');
        };

        const fetchResults = async () => {
            const params = new URLSearchParams({ q: searchInput.value });
            const response = await fetch(`{{ route('products.search') }}?${params.toString()}`, {
                headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' },
            });
            const payload = await response.json();
            renderResults(payload.data);
        };

        searchInput?.addEventListener('input', () => {
            clearTimeout(searchTimer);
            searchTimer = setTimeout(fetchResults, 300);
        });

        searchButton?.addEventListener('click', fetchResults);
    </script>
@endsection
@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')

<style>
    @keyframes fadeUp {
        from { opacity: 0; transform: translateY(16px); }
        to   { opacity: 1; transform: translateY(0); }
    }
    @keyframes pulse-dot {
        0%, 100% { opacity: 1; transform: scale(1); }
        50%       { opacity: .5; transform: scale(.75); }
    }
    .anim-fade-up { animation: fadeUp .5s ease both; }
    .anim-delay-1 { animation-delay: .08s; }
    .anim-delay-2 { animation-delay: .16s; }
    .anim-delay-3 { animation-delay: .24s; }
    .anim-delay-4 { animation-delay: .32s; }
    .live-dot { animation: pulse-dot 1.5s ease infinite; }

    /* Stat card accent glow on hover */
    .stat-card { transition: border-color .2s, box-shadow .2s, transform .2s; }
    .stat-card:hover { transform: translateY(-2px); }

    /* Quick link icon scale */
    .quick-link-icon { transition: transform .25s cubic-bezier(.34,1.56,.64,1); }
    .quick-link:hover .quick-link-icon { transform: scale(1.15) rotate(-3deg); }

    /* Subtle grid texture on header */
    .header-grid {
        background-image: radial-gradient(rgba(255,255,255,.04) 1px, transparent 1px);
        background-size: 24px 24px;
    }
</style>

<div class="space-y-6 animate-fade-in">

    {{-- ═══════════════════════════════════════════════
         HEADER
    ═══════════════════════════════════════════════ --}}
    <section class="anim-fade-up relative overflow-hidden rounded-2xl border border-slate-800 bg-slate-950 p-8 shadow-2xl header-grid">
        {{-- Ambient glow --}}
        <div class="pointer-events-none absolute -right-24 -top-24 h-72 w-72 rounded-full bg-amber-500/10 blur-3xl"></div>
        <div class="pointer-events-none absolute -bottom-16 left-1/3 h-48 w-64 rounded-full bg-amber-500/5 blur-3xl"></div>

        <div class="relative flex flex-col gap-6 lg:flex-row lg:items-center lg:justify-between">
            <div>
                <span class="inline-flex items-center gap-2 rounded-full border border-amber-500/30 bg-amber-500/10 px-3 py-1 text-[11px] font-bold uppercase tracking-[0.22em] text-amber-400">
                    <span class="live-dot h-1.5 w-1.5 rounded-full bg-amber-400"></span>
                    Admin Control Center
                </span>
                <h1 class="mt-3 text-3xl font-black tracking-tight text-white lg:text-4xl">Panel Admin Utama</h1>
                <p class="mt-2 max-w-xl text-sm leading-relaxed text-slate-400">
                    Kelola ekosistem marketplace: verifikasi seller, moderasi akun, manajemen iklan banner,
                    hingga pemantauan seluruh transaksi secara real-time.
                </p>
            </div>
            <div class="flex shrink-0 gap-3">
                <a href="{{ route('admin.users.index') }}"
                   class="rounded-xl bg-amber-500 px-5 py-3 text-sm font-bold text-slate-950 shadow-lg shadow-amber-500/20 transition hover:-translate-y-0.5 hover:bg-amber-400 active:translate-y-0">
                    Kelola Akun
                </a>
                <a href="{{ route('admin.orders.index') }}"
                   class="rounded-xl border border-slate-700 bg-slate-800/60 px-5 py-3 text-sm font-bold text-white transition hover:border-slate-500 hover:bg-slate-700 active:scale-95">
                    Transaksi
                </a>
            </div>
        </div>
    </section>

    {{-- ═══════════════════════════════════════════════
         STATS GRID
         Row 1: 4 kolom — Total User, Buyer, Seller, Suspend
         Row 2: Request Seller (lebar), Produk, Transaksi (2 col)
    ═══════════════════════════════════════════════ --}}
    <div class="anim-fade-up anim-delay-1 grid gap-4 sm:grid-cols-2 lg:grid-cols-4">

        {{-- Total Users --}}
        <div class="stat-card group rounded-2xl border border-slate-800 border-t-2 border-t-blue-500/70 bg-slate-900 p-5 hover:border-t-blue-400 hover:shadow-lg hover:shadow-blue-500/10">
            <div class="mb-4 flex items-start justify-between">
                <p class="text-[11px] font-bold uppercase tracking-widest text-slate-500">Total User</p>
                <div class="rounded-xl bg-blue-500/10 p-2 text-blue-400 transition group-hover:bg-blue-500/20">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                </div>
            </div>
            <p class="text-3xl font-black text-white">{{ number_format($totalUsers) }}</p>
            <p class="mt-1 text-[11px] text-slate-600">Pengguna terdaftar</p>
        </div>

        {{-- Buyers --}}
        <div class="stat-card group rounded-2xl border border-slate-800 border-t-2 border-t-indigo-500/70 bg-slate-900 p-5 hover:border-t-indigo-400 hover:shadow-lg hover:shadow-indigo-500/10">
            <div class="mb-4 flex items-start justify-between">
                <p class="text-[11px] font-bold uppercase tracking-widest text-slate-500">Buyer</p>
                <div class="rounded-xl bg-indigo-500/10 p-2 text-indigo-400 transition group-hover:bg-indigo-500/20">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                    </svg>
                </div>
            </div>
            <p class="text-3xl font-black text-white">{{ number_format($buyers) }}</p>
            <p class="mt-1 text-[11px] text-slate-600">Pembeli aktif</p>
        </div>

        {{-- Verified Sellers --}}
        <div class="stat-card group rounded-2xl border border-slate-800 border-t-2 border-t-emerald-500/70 bg-slate-900 p-5 hover:border-t-emerald-400 hover:shadow-lg hover:shadow-emerald-500/10">
            <div class="mb-4 flex items-start justify-between">
                <p class="text-[11px] font-bold uppercase tracking-widest text-emerald-600/80">Verified Seller</p>
                <div class="rounded-xl bg-emerald-500/10 p-2 text-emerald-400 transition group-hover:bg-emerald-500/20">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                    </svg>
                </div>
            </div>
            <p class="text-3xl font-black text-white">{{ number_format($sellers) }}</p>
            <p class="mt-1 text-[11px] text-slate-600">Seller terverifikasi</p>
        </div>

        {{-- Suspended --}}
        <div class="stat-card group rounded-2xl border border-slate-800 border-t-2 border-t-rose-500/70 bg-slate-900 p-5 hover:border-t-rose-400 hover:shadow-lg hover:shadow-rose-500/10">
            <div class="mb-4 flex items-start justify-between">
                <p class="text-[11px] font-bold uppercase tracking-widest text-rose-600/80">Akun Suspend</p>
                <div class="rounded-xl bg-rose-500/10 p-2 text-rose-400 transition group-hover:bg-rose-500/20">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
                    </svg>
                </div>
            </div>
            <p class="text-3xl font-black text-white">{{ number_format($suspendedUsers) }}</p>
            <p class="mt-1 text-[11px] text-slate-600">Akun dinonaktifkan</p>
        </div>

        {{-- Request Seller — highlighted card --}}
        <div class="stat-card group relative overflow-hidden rounded-2xl border border-amber-500/30 bg-gradient-to-br from-amber-500/10 to-slate-900 p-5 hover:border-amber-500/60 hover:shadow-lg hover:shadow-amber-500/10 sm:col-span-2 lg:col-span-1">
            <div class="pointer-events-none absolute -right-6 -top-6 h-24 w-24 rounded-full bg-amber-400/10 blur-2xl"></div>
            <div class="relative mb-4 flex items-start justify-between">
                <p class="text-[11px] font-bold uppercase tracking-widest text-amber-500">Request Seller</p>
                <div class="flex items-center gap-1.5 rounded-full bg-amber-500/15 px-2.5 py-1 text-[10px] font-bold text-amber-400">
                    <span class="live-dot h-1.5 w-1.5 rounded-full bg-amber-400"></span>
                    Live
                </div>
            </div>
            <p class="relative text-3xl font-black text-white">{{ number_format($sellerRequests) }}</p>
            <p class="mt-1 text-[11px] text-amber-500/60">Menunggu verifikasi</p>
        </div>

        {{-- Produk Aktif --}}
        <div class="stat-card group rounded-2xl border border-slate-800 border-t-2 border-t-purple-500/70 bg-slate-900 p-5 hover:border-t-purple-400 hover:shadow-lg hover:shadow-purple-500/10">
            <div class="mb-4 flex items-start justify-between">
                <p class="text-[11px] font-bold uppercase tracking-widest text-slate-500">Produk Aktif</p>
                <div class="rounded-xl bg-purple-500/10 p-2 text-purple-400 transition group-hover:bg-purple-500/20">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                    </svg>
                </div>
            </div>
            <p class="text-3xl font-black text-white">{{ number_format($products) }}</p>
            <p class="mt-1 text-[11px] text-slate-600">Listing tersedia</p>
        </div>

        {{-- Total Transaksi — spans 2 cols --}}
        <div class="stat-card group rounded-2xl border border-slate-800 border-t-2 border-t-cyan-500/70 bg-slate-900 p-5 hover:border-t-cyan-400 hover:shadow-lg hover:shadow-cyan-500/10 sm:col-span-2">
            <div class="mb-4 flex items-start justify-between">
                <p class="text-[11px] font-bold uppercase tracking-widest text-slate-500">Total Transaksi Selesai</p>
                <div class="rounded-xl bg-cyan-500/10 p-2 text-cyan-400 transition group-hover:bg-cyan-500/20">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    </svg>
                </div>
            </div>
            <p class="text-3xl font-black text-white">{{ number_format($orders) }}</p>
            <p class="mt-1 text-[11px] text-slate-600">Order berhasil diselesaikan</p>
        </div>

    </div>

    {{-- ═══════════════════════════════════════════════
         QUICK LINKS
    ═══════════════════════════════════════════════ --}}
    <div class="anim-fade-up anim-delay-2 grid gap-4 lg:grid-cols-3">

        <a href="{{ route('admin.users.index') }}"
           class="quick-link group rounded-2xl border border-slate-800 bg-slate-900 p-7 transition hover:border-amber-500/40 hover:bg-slate-800/60 hover:shadow-xl hover:shadow-amber-500/5">
            <div class="quick-link-icon mb-6 inline-flex h-11 w-11 items-center justify-center rounded-xl bg-amber-500/10 text-amber-500">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37a1.724 1.724 0 002.572-1.065z" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
            </div>
            <div class="flex items-start justify-between">
                <div>
                    <h2 class="text-lg font-bold text-white">Kelola Akun</h2>
                    <p class="mt-1.5 text-sm leading-relaxed text-slate-400">Verifikasi seller baru, moderasi akun bermasalah, dan pengaturan role pengguna.</p>
                </div>
                <svg class="mt-0.5 h-4 w-4 shrink-0 text-slate-600 transition group-hover:translate-x-1 group-hover:text-amber-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                </svg>
            </div>
        </a>

        <a href="{{ route('admin.banners.index') }}"
           class="quick-link group rounded-2xl border border-slate-800 bg-slate-900 p-7 transition hover:border-blue-500/40 hover:bg-slate-800/60 hover:shadow-xl hover:shadow-blue-500/5">
            <div class="quick-link-icon mb-6 inline-flex h-11 w-11 items-center justify-center rounded-xl bg-blue-500/10 text-blue-500">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
            </div>
            <div class="flex items-start justify-between">
                <div>
                    <h2 class="text-lg font-bold text-white">Banner Beranda</h2>
                    <p class="mt-1.5 text-sm leading-relaxed text-slate-400">Atur promosi game terbaru dan event khusus agar langsung terlihat oleh buyer.</p>
                </div>
                <svg class="mt-0.5 h-4 w-4 shrink-0 text-slate-600 transition group-hover:translate-x-1 group-hover:text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                </svg>
            </div>
        </a>

        <a href="{{ route('admin.notifications.index') }}"
           class="quick-link group rounded-2xl border border-slate-800 bg-slate-900 p-7 transition hover:border-purple-500/40 hover:bg-slate-800/60 hover:shadow-xl hover:shadow-purple-500/5">
            <div class="quick-link-icon mb-6 inline-flex h-11 w-11 items-center justify-center rounded-xl bg-purple-500/10 text-purple-500">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                </svg>
            </div>
            <div class="flex items-start justify-between">
                <div>
                    <h2 class="text-lg font-bold text-white">Pesan & Notifikasi</h2>
                    <p class="mt-1.5 text-sm leading-relaxed text-slate-400">Kirim pengumuman massal atau pemberitahuan penting kepada seluruh buyer dan seller.</p>
                </div>
                <svg class="mt-0.5 h-4 w-4 shrink-0 text-slate-600 transition group-hover:translate-x-1 group-hover:text-purple-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                </svg>
            </div>
        </a>

    </div>

    {{-- ═══════════════════════════════════════════════
         CHARTS
    ═══════════════════════════════════════════════ --}}
    <div class="anim-fade-up anim-delay-3 grid gap-4 lg:grid-cols-2">

        {{-- Grafik Transaksi --}}
        <div class="rounded-2xl border border-slate-800 bg-slate-900 p-6">
            <div class="mb-6 flex items-center justify-between">
                <div>
                    <h3 class="font-bold text-white">Tren Transaksi</h3>
                    <p class="text-[11px] text-slate-500">7 Hari Terakhir</p>
                </div>
                <span class="rounded-lg bg-amber-500/10 px-2.5 py-1 text-[11px] font-bold text-amber-400">Order</span>
            </div>
            <canvas id="transactionChart" height="220"></canvas>
        </div>

        {{-- Grafik Keuangan --}}
        <div class="rounded-2xl border border-slate-800 bg-slate-900 p-6">
            <div class="mb-6 flex items-center justify-between">
                <div>
                    <h3 class="font-bold text-white">Pendapatan</h3>
                    <p class="text-[11px] text-slate-500">7 Hari Terakhir</p>
                </div>
                <span class="rounded-lg bg-emerald-500/10 px-2.5 py-1 text-[11px] font-bold text-emerald-400">Revenue</span>
            </div>
            <canvas id="revenueChart" height="220"></canvas>
        </div>

    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const labels = {!! json_encode($chartLabels) !!};

    // Shared chart defaults for dark theme
    const gridColor  = 'rgba(255,255,255,0.05)';
    const labelColor = '#64748b'; // slate-500
    const sharedScales = {
        y: {
            beginAtZero: true,
            grid: { color: gridColor },
            ticks: { color: labelColor, font: { size: 11 } }
        },
        x: {
            grid: { display: false },
            ticks: { color: labelColor, font: { size: 11 } }
        }
    };
    const sharedPlugins = {
        legend: { display: false },
        tooltip: {
            backgroundColor: '#0f172a',
            borderColor: '#334155',
            borderWidth: 1,
            titleColor: '#f8fafc',
            bodyColor: '#94a3b8',
            padding: 10,
            cornerRadius: 8
        }
    };

    // Grafik Transaksi
    new Chart(document.getElementById('transactionChart'), {
        type: 'line',
        data: {
            labels: labels,
            datasets: [{
                label: 'Jumlah Order',
                data: {!! json_encode($chartTransactions) !!},
                borderColor: '#f59e0b',
                backgroundColor: 'rgba(245,158,11,0.08)',
                pointBackgroundColor: '#f59e0b',
                pointBorderColor: '#0f172a',
                pointBorderWidth: 2,
                pointRadius: 4,
                pointHoverRadius: 6,
                fill: true,
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            plugins: sharedPlugins,
            scales: sharedScales
        }
    });

    // Grafik Keuangan
    new Chart(document.getElementById('revenueChart'), {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: 'Total Rupiah',
                data: {!! json_encode($chartRevenue) !!},
                backgroundColor: 'rgba(16,185,129,0.7)',
                hoverBackgroundColor: '#10b981',
                borderRadius: 6,
                borderSkipped: false
            }]
        },
        options: {
            responsive: true,
            plugins: sharedPlugins,
            scales: sharedScales
        }
    });
</script>

@endsection
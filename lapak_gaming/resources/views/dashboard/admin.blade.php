@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')

<style>
    .reveal {
        opacity: 0;
        transform: translateY(30px);
        animation: revealUp .8s ease forwards;
    }

    .reveal-delay-1 {
        animation-delay: .15s;
    }

    .reveal-delay-2 {
        animation-delay: .3s;
    }

    .reveal-delay-3 {
        animation-delay: .45s;
    }

    .reveal-delay-4 {
        animation-delay: .6s;
    }

    @keyframes revealUp {
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .glass-card {
        background: rgba(11, 18, 32, 0.95);
        border: 1px solid rgba(59, 130, 246, 0.15);
        box-shadow:
            0 0 50px rgba(37, 99, 235, 0.06),
            inset 0 1px 0 rgba(255,255,255,0.03);
        backdrop-filter: blur(18px);
    }

    .dashboard-card {
        transition: all .35s ease;
        min-height: 150px;
    }

    .dashboard-card:hover {
        transform: translateY(-6px);
        border-color: rgba(96,165,250,.45);
        box-shadow:
            0 0 40px rgba(59,130,246,.15),
            0 15px 40px rgba(0,0,0,.35);
    }

    .glow-blue {
        box-shadow: 0 0 35px rgba(59,130,246,.18);
    }

    .glow-emerald {
        box-shadow: 0 0 35px rgba(16,185,129,.18);
    }

    .glow-orange {
        box-shadow: 0 0 35px rgba(249,115,22,.18);
    }

    .live-dot {
        animation: pulseDot 1.5s infinite;
    }

    @keyframes pulseDot {
        0%,100% {
            transform: scale(1);
            opacity: 1;
        }

        50% {
            transform: scale(.7);
            opacity: .5;
        }
    }

    .quick-link {
    min-height: 180px;
}

    
</style>
<div class="mx-auto max-w-7xl space-y-10 px-5 py-8 animate-fade-in">

    {{-- ═══════════════════════════════════════════════
         HEADER
    ═══════════════════════════════════════════════ --}}
    <section class="reveal relative overflow-hidden rounded-[30px]
            border border-blue-500/20
            bg-gradient-to-br from-[#060816] via-[#091225] to-[#0B1730]
            px-8 py-7
            shadow-[0_0_80px_rgba(37,99,235,0.12)]">
        {{-- Ambient glow --}}
        <div class="pointer-events-none absolute -right-24 -top-24 h-72 w-72 rounded-full bg-amber-500/10 blur-3xl"></div>
        <div class="pointer-events-none absolute -bottom-16 left-1/3 h-48 w-64 rounded-full bg-amber-500/5 blur-3xl"></div>

        <div class="relative flex flex-col gap-6 lg:flex-row lg:items-center lg:justify-between">
            <div>
                <span class="inline-flex items-center gap-2 rounded-full border border-amber-500/30 bg-amber-500/10 px-3 py-1 text-[11px] font-bold uppercase tracking-[0.22em] text-amber-400">
                    <span class="live-dot h-1.5 w-1.5 rounded-full bg-amber-400"></span>
                    Admin Control Center
                </span>
                <h1 class="mt-4 text-4xl font-black tracking-tight text-white lg:text-4xl">Panel Admin Utama</h1>
                <p class="mt-4 max-w-xl text-sm leading-relaxed text-slate-400">
                    Kelola ekosistem marketplace: verifikasi seller, moderasi akun, manajemen iklan banner,
                    hingga pemantauan seluruh transaksi secara real-time.
                </p>
            </div>
            <div class="flex shrink-0 gap-3">
                <a href="{{ route('admin.users.index') }}"
                   class="rounded-2xl bg-amber-500 transition duration-300 hover:-translate-y-1 hover:shadow-[0_0_30px_rgba(245,158,11,0.35)] px-5 py-3 text-sm font-bold text-slate-950 shadow-lg shadow-amber-500/20 transition hover:-translate-y-0.5 hover:bg-amber-400 active:translate-y-0">
                    Kelola Akun
                </a>
                <a href="{{ route('admin.orders.index') }}"
                   class="rounded-xl border border-slate-700 bg-slate-800/60 px-5 py-3 text-sm font-bold text-white transition hover:border-slate-500 hover:bg-slate-700 active:scale-95">
                    Transaksi
                </a>
                <a href="{{ route('admin.orders.report.pdf') }}"
                   class="rounded-xl border border-emerald-500/30 bg-emerald-500/10 px-5 py-3 text-sm font-bold text-emerald-200 transition hover:border-emerald-400 hover:bg-emerald-500/20 active:scale-95">
                    Download PDF
                </a>
            </div>
        </div>
    </section>

    {{-- ═══════════════════════════════════════════════
         STATS GRID
         Row 1: 4 kolom — Total User, Buyer, Seller, Suspend
         Row 2: Request Seller (lebar), Produk, Transaksi (2 col)
    ═══════════════════════════════════════════════ --}}
    <div class="anim-fade-up anim-delay-1 grid gap-6 sm:grid-cols-2 lg:grid-cols-4">

        {{-- Total Users --}}
        <div class="reveal dashboard-card glass-card group rounded-2xl border border-blue-500/20 border-t-2 border-t-blue-500/70 bg-[#0B1220]/95 p-4 hover:border-t-blue-400 hover:shadow-lg hover:shadow-blue-500/10">
            <div class="mb-4 flex items-start justify-between">
                <p class="text-[11px] font-bold uppercase tracking-widest text-slate-500">Total User</p>
                <div class="rounded-xl bg-blue-500/10 p-2 text-blue-400 transition group-hover:bg-blue-500/20">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                </div>
            </div>
            <p class="text-2xl font-black text-white">{{ number_format($totalUsers) }}</p>
            <p class="mt-1 text-[11px] text-slate-600">Pengguna terdaftar</p>
        </div>

        {{-- Buyers --}}
        <div class="reveal dashboard-card glass-card group rounded-2xl border border-blue-500/20 border-t-2 border-t-indigo-500/70 bg-[#0B1220]/95 p-4 hover:border-t-indigo-400 hover:shadow-lg hover:shadow-indigo-500/10">
            <div class="mb-4 flex items-start justify-between">
                <p class="text-[11px] font-bold uppercase tracking-widest text-slate-500">Buyer</p>
                <div class="rounded-xl bg-indigo-500/10 p-2 text-indigo-400 transition group-hover:bg-indigo-500/20">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                    </svg>
                </div>
            </div>
            <p class="text-2xl font-black text-white">{{ number_format($buyers) }}</p>
            <p class="mt-1 text-[11px] text-slate-600">Pembeli aktif</p>
        </div>

        {{-- Verified Sellers --}}
        <div class="reveal dashboard-card glass-card group rounded-2xl border border-blue-500/20 border-t-2 border-t-emerald-500/70 bg-[#0B1220]/95 p-4 hover:border-t-emerald-400 hover:shadow-lg hover:shadow-emerald-500/10">
            <div class="mb-4 flex items-start justify-between">
                <p class="text-[11px] font-bold uppercase tracking-widest text-emerald-600/80">Verified Seller</p>
                <div class="rounded-xl bg-emerald-500/10 p-2 text-emerald-400 transition group-hover:bg-emerald-500/20">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                    </svg>
                </div>
            </div>
            <p class="text-2xl font-black text-white">{{ number_format($sellers) }}</p>
            <p class="mt-1 text-[11px] text-slate-600">Seller terverifikasi</p>
        </div>

        {{-- Suspended --}}
        <div class="reveal dashboard-card glass-card group rounded-2xl border border-blue-500/20 border-t-2 border-t-rose-500/70 bg-[#0B1220]/95 p-4 hover:border-t-rose-400 hover:shadow-lg hover:shadow-rose-500/10">
            <div class="mb-4 flex items-start justify-between">
                <p class="text-[11px] font-bold uppercase tracking-widest text-rose-600/80">Akun Suspend</p>
                <div class="rounded-xl bg-rose-500/10 p-2 text-rose-400 transition group-hover:bg-rose-500/20">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
                    </svg>
                </div>
            </div>
            <p class="text-2xl font-black text-white">{{ number_format($suspendedUsers) }}</p>
            <p class="mt-1 text-[11px] text-slate-600">Akun dinonaktifkan</p>
        </div>

        {{-- Request Seller — highlighted card --}}
        <div class="reveal dashboard-card glass-card group relative overflow-hidden rounded-2xl border border-amber-500/30 bg-gradient-to-br from-amber-500/10 to-slate-900 p-4 hover:border-amber-500/60 hover:shadow-lg hover:shadow-amber-500/10 sm:col-span-2 lg:col-span-1">
            <div class="pointer-events-none absolute -right-6 -top-6 h-24 w-24 rounded-full bg-amber-400/10 blur-2xl"></div>
            <div class="relative mb-4 flex items-start justify-between">
                <p class="text-[11px] font-bold uppercase tracking-widest text-amber-500">Request Seller</p>
                <div class="flex items-center gap-1.5 rounded-full bg-amber-500/15 px-2.5 py-1 text-[10px] font-bold text-amber-400">
                    <span class="live-dot h-1.5 w-1.5 rounded-full bg-amber-400"></span>
                    Live
                </div>
            </div>
            <p class="relative text-2xl font-black text-white">{{ number_format($sellerRequests) }}</p>
            <p class="mt-1 text-[11px] text-amber-500/60">Menunggu verifikasi</p>
        </div>

        {{-- Pending Email Verification --}}
        <a href="{{ route('admin.users.index', ['tab' => 'pending_verification']) }}"
           class="reveal dashboard-card glass-card group rounded-2xl border border-blue-500/20 border-t-2 border-t-yellow-500/70 bg-[#0B1220]/95 p-4 hover:border-t-yellow-400 hover:shadow-lg hover:shadow-yellow-500/10">
            <div class="mb-4 flex items-start justify-between">
                <p class="text-[11px] font-bold uppercase tracking-widest text-slate-500">Pending Verifikasi</p>
                <div class="rounded-xl bg-yellow-500/10 p-2 text-yellow-400 transition group-hover:bg-yellow-500/20">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8m-18 8h18a2 2 0 002-2V8a2 2 0 00-2-2H3a2 2 0 00-2 2v6a2 2 0 002 2z" />
                    </svg>
                </div>
            </div>
            <p class="text-2xl font-black text-white">{{ number_format($pendingEmailVerifications) }}</p>
            <p class="mt-1 text-[11px] text-slate-600">Register belum verifikasi email</p>
        </a>

        {{-- Produk Aktif --}}
        <div class="reveal dashboard-card glass-card group rounded-2xl border border-blue-500/20 border-t-2 border-t-purple-500/70 bg-[#0B1220]/95 p-4 hover:border-t-purple-400 hover:shadow-lg hover:shadow-purple-500/10">
            <div class="mb-4 flex items-start justify-between">
                <p class="text-[11px] font-bold uppercase tracking-widest text-slate-500">Produk Aktif</p>
                <div class="rounded-[26px] bg-purple-500/10 p-2 text-purple-400 transition group-hover:bg-purple-500/20">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                    </svg>
                </div>
            </div>
            <p class="text-2xl font-black text-white">{{ number_format($products) }}</p>
            <p class="mt-1 text-[11px] text-slate-600">Listing tersedia</p>
        </div>

        {{-- Total Transaksi — spans 2 cols --}}
        <div class="reveal dashboard-card glass-card group rounded-[26px] border border-blue-500/20 border-t-2 border-t-cyan-500/70 bg-[#0B1220]/95 p-4 hover:border-t-cyan-400 hover:shadow-lg hover:shadow-cyan-500/10 sm:col-span-2">
            <div class="mb-4 flex items-start justify-between">
                <p class="text-[11px] font-bold uppercase tracking-widest text-slate-500">Total Transaksi Selesai</p>
                <div class="rounded-[26px] bg-cyan-500/10 p-2 text-cyan-400 transition group-hover:bg-cyan-500/20">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    </svg>
                </div>
            </div>
            <p class="text-2xl font-black text-white">{{ number_format($orders) }}</p>
            <p class="mt-1 text-[11px] text-slate-600">Order berhasil diselesaikan</p>
        </div>

    </div>

    <h2 class="text-sm font-bold tracking-[0.25em] text-blue-400 uppercase">
    Akses Cepat
</h2>

    {{-- ═══════════════════════════════════════════════
         QUICK LINKS
    ═══════════════════════════════════════════════ --}}
    <div class="anim-fade-up anim-delay-2 grid gap-6 xl:grid-cols-3">

        <a href="{{ route('admin.users.index') }}"
           class="quick-link reveal dashboard-card glass-card group rounded-[30px]border border-blue-500/20 bg-[#0B1220]/95 p-6 transition hover:border-amber-500/40 hover:bg-slate-800/60 hover:shadow-xl hover:shadow-amber-500/5">
            <div class="quick-link-icon mb-4 inline-flex h-11 w-11 items-center justify-center rounded-[26px] bg-amber-500/10 text-amber-500">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1。724 1.724 0 00-1.065-2。57２c-１．７５６－．４２６－１．７５６－２．９２４ ０－３．３５a１．７２４ １．７２４ ０ ００１．０６６－２．５７３c－．９４－１．５４３＋．８２６－３．３１ ＋２．３７－２．３７a１．７２４ １．７２４ ０ ００２．５７２－１．０６５z" />
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
           class="quick-link reveal dashboard-card glass-card group rounded-[30px] border border-blue-500/20 bg-[#0B1220]/95 p-6 transition hover:border-blue-500/40 hover:bg-slate-800/60 hover:shadow-xl hover:shadow-blue-500/5">
            <div class="quick-link-icon mb-4 inline-flex h-11 w-11 items-center justify-center rounded-[26px] bg-blue-500/10 text-blue-500">
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
           class="quick-link reveal dashboard-card glass-card group rounded-[30px]-[26px] border border-blue-500/20 bg-[#0B1220]/95 p-6 transition hover:border-purple-500/40 hover:bg-slate-800/60 hover:shadow-xl hover:shadow-purple-500/5">
            <div class="quick-link-icon mb-4 inline-flex h-11 w-11 items-center justify-center rounded-[26px] bg-purple-500/10 text-purple-500">
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
    <div class="anim-fade-up anim-delay-3 grid gap-6 lg:grid-cols-2">

        {{-- Grafik Transaksi --}}
        <div class="rounded-[26px] border border-blue-500/20 bg-[#0B1220]/95 p-6">
            <div class="mb-4 flex items-center justify-between">
                <div>
                    <h3 class="font-bold text-white">Tren Transaksi</h3>
                    <p class="text-[11px] text-slate-500">{{ $chartRangeLabel }}</p>
                </div>
                <span class="rounded-lg bg-amber-500/10 px-2.5 py-1 text-[11px] font-bold text-amber-400">Order</span>
            </div>
            <canvas id="transactionChart" height="150"></canvas>
        </div>

        {{-- Grafik Keuangan --}}
        <div class="rounded-[26px] border border-blue-500/20 bg-[#0B1220]/95 p-6">
            <div class="mb-4 flex items-center justify-between">
                <div>
                    <h3 class="font-bold text-white">Perputaran Uang</h3>
                    <p class="text-[11px] text-slate-500">{{ $chartRangeLabel }}</p>
                </div>
                <span class="rounded-lg bg-emerald-500/10 px-2.5 py-1 text-[11px] font-bold text-emerald-400">Transaksi</span>
            </div>
            <canvas id="revenueChart" height="150"></canvas>
        </div>

    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script type="text/x-template">
window.chartLabels = {!! json_encode($chartLabels) !!};
window.chartTransactions = {!! json_encode($chartTransactions) !!};
window.chartRevenue = {!! json_encode($chartRevenue) !!};
</script>
<script>
    const labels = window.chartLabels;

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
                data: window.chartTransactions,
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
                data: window.chartRevenue,
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

    document.addEventListener('DOMContentLoaded', () => {
    const cards = document.querySelectorAll('.dashboard-card');

    cards.forEach(card => {
        card.addEventListener('mousemove', e => {
            const rect = card.getBoundingClientRect();

            const x = e.clientX - rect.left;
            const y = e.clientY - rect.top;

            card.style.background =
                `radial-gradient(circle at ${x}px ${y}px,
                rgba(59,130,246,0.10),
                rgba(11,18,32,0.95) 45%)`;
        });

        card.addEventListener('mouseleave', () => {
            card.style.background = 'rgba(11,18,32,0.95)';
        });
    });
});
</script>

@endsection

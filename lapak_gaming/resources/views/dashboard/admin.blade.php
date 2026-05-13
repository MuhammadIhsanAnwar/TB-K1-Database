@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
    <div class="space-y-8 animate-fade-in pb-12">
        
        {{-- Header Section --}}
        <section class="relative overflow-hidden rounded-[2rem] border border-slate-800/60 bg-gradient-to-b from-slate-900/80 to-slate-950 p-8 lg:p-10 shadow-2xl backdrop-blur-xl">
            {{-- Decorative Background Glow --}}
            <div class="absolute -top-24 -right-24 w-96 h-96 bg-amber-500/10 blur-[120px] rounded-full pointer-events-none"></div>
            <div class="absolute -bottom-24 -left-24 w-72 h-72 bg-blue-500/10 blur-[100px] rounded-full pointer-events-none"></div>
            
            <div class="relative flex flex-col gap-8 lg:flex-row lg:items-end lg:justify-between z-10">
                <div class="max-w-3xl">
                    <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-amber-500/10 border border-amber-500/20 mb-4">
                        <span class="relative flex h-2 w-2">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-amber-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-2 w-2 bg-amber-500"></span>
                        </span>
                        <p class="text-[11px] uppercase tracking-[0.2em] text-amber-400 font-bold">Admin Control Center</p>
                    </div>
                    <h1 class="text-4xl lg:text-5xl font-black text-white tracking-tight leading-tight">Panel Admin <span class="text-transparent bg-clip-text bg-gradient-to-r from-white to-slate-500">Utama</span></h1>
                    <p class="mt-4 text-base lg:text-lg text-slate-400 leading-relaxed font-light">
                        Kelola ekosistem marketplace: verifikasi seller, moderasi akun, manajemen iklan banner, hingga
                        pemantauan seluruh transaksi secara real-time.
                    </p>
                </div>
                
                <div class="flex flex-wrap gap-3 sm:gap-4 text-sm w-full lg:w-auto">
                    <a href="{{ route('admin.users.index') }}"
                        class="flex-1 lg:flex-none text-center rounded-xl bg-gradient-to-r from-amber-500 to-amber-600 px-6 py-3.5 font-semibold text-slate-950 shadow-[0_0_20px_rgba(245,158,11,0.3)] hover:shadow-[0_0_25px_rgba(245,158,11,0.5)] hover:-translate-y-0.5 transition-all duration-300">
                        Kelola Akun
                    </a>
                    <a href="{{ route('admin.orders.index') }}"
                        class="flex-1 lg:flex-none text-center rounded-xl border border-slate-700 bg-slate-800/50 backdrop-blur-md px-6 py-3.5 font-semibold text-white hover:border-slate-500 hover:bg-slate-700/50 hover:-translate-y-0.5 transition-all duration-300 shadow-sm">
                        Transaksi
                    </a>
                </div>
            </div>
        </section>

        {{-- Stats Grid --}}
        <div class="grid gap-5 grid-cols-1 sm:grid-cols-2 lg:grid-cols-4">

            {{-- Total Users --}}
            <div class="group relative overflow-hidden rounded-2xl border border-slate-800/60 bg-slate-900/40 backdrop-blur-sm p-6 hover:bg-slate-800/60 hover:-translate-y-1 hover:shadow-xl hover:shadow-blue-500/5 transition-all duration-300">
                <div class="flex items-center justify-between mb-6">
                    <div class="text-xs font-semibold uppercase tracking-wider text-slate-400 group-hover:text-slate-300 transition-colors">Total User</div>
                    <div class="p-2.5 rounded-xl bg-gradient-to-br from-blue-500/20 to-blue-600/10 border border-blue-500/20 text-blue-400">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                    </div>
                </div>
                <div class="text-4xl font-black text-white tracking-tight">{{ number_format($totalUsers) }}</div>
            </div>

            {{-- Buyers --}}
            <div class="group relative overflow-hidden rounded-2xl border border-slate-800/60 bg-slate-900/40 backdrop-blur-sm p-6 hover:bg-slate-800/60 hover:-translate-y-1 hover:shadow-xl hover:shadow-indigo-500/5 transition-all duration-300">
                <div class="flex items-center justify-between mb-6">
                    <div class="text-xs font-semibold uppercase tracking-wider text-slate-400 group-hover:text-slate-300 transition-colors">Buyer</div>
                    <div class="p-2.5 rounded-xl bg-gradient-to-br from-indigo-500/20 to-indigo-600/10 border border-indigo-500/20 text-indigo-400">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                        </svg>
                    </div>
                </div>
                <div class="text-4xl font-black text-white tracking-tight">{{ number_format($buyers) }}</div>
            </div>

            {{-- Sellers --}}
            <div class="group relative overflow-hidden rounded-2xl border border-slate-800/60 bg-slate-900/40 backdrop-blur-sm p-6 hover:bg-slate-800/60 hover:-translate-y-1 hover:shadow-xl hover:shadow-emerald-500/5 transition-all duration-300">
                <div class="absolute bottom-0 left-0 w-full h-1 bg-gradient-to-r from-emerald-500/50 to-emerald-400/50 opacity-50 group-hover:opacity-100 transition-opacity"></div>
                <div class="flex items-center justify-between mb-6">
                    <div class="text-xs font-semibold uppercase tracking-wider text-emerald-500/80 group-hover:text-emerald-400 transition-colors">Verified Seller</div>
                    <div class="p-2.5 rounded-xl bg-gradient-to-br from-emerald-500/20 to-emerald-600/10 border border-emerald-500/20 text-emerald-400">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                        </svg>
                    </div>
                </div>
                <div class="text-4xl font-black text-white tracking-tight">{{ number_format($sellers) }}</div>
            </div>

            {{-- Suspended --}}
            <div class="group relative overflow-hidden rounded-2xl border border-slate-800/60 bg-slate-900/40 backdrop-blur-sm p-6 hover:bg-slate-800/60 hover:-translate-y-1 hover:shadow-xl hover:shadow-rose-500/5 transition-all duration-300">
                <div class="absolute bottom-0 left-0 w-full h-1 bg-gradient-to-r from-rose-500/50 to-rose-400/50 opacity-50 group-hover:opacity-100 transition-opacity"></div>
                <div class="flex items-center justify-between mb-6">
                    <div class="text-xs font-semibold uppercase tracking-wider text-rose-500/80 group-hover:text-rose-400 transition-colors">Akun Suspend</div>
                    <div class="p-2.5 rounded-xl bg-gradient-to-br from-rose-500/20 to-rose-600/10 border border-rose-500/20 text-rose-400">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
                        </svg>
                    </div>
                </div>
                <div class="text-4xl font-black text-white tracking-tight">{{ number_format($suspendedUsers) }}</div>
            </div>

            {{-- Request Seller (PENTING: Status Real 0) --}}
            <div class="group relative overflow-hidden rounded-2xl border-l-4 border-l-amber-500 border-y border-r border-slate-800/60 bg-gradient-to-r from-amber-500/5 to-slate-900/40 backdrop-blur-sm p-6 hover:bg-amber-500/10 hover:-translate-y-1 hover:shadow-xl hover:shadow-amber-500/10 transition-all duration-300">
                <div class="flex items-center justify-between mb-4">
                    <div class="text-xs font-semibold uppercase tracking-wider text-amber-500 flex items-center gap-2">
                        Request Seller
                        <span class="relative flex h-2.5 w-2.5">
                            @if($sellerRequests > 0)
                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-amber-400 opacity-75"></span>
                            @endif
                            <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-amber-500"></span>
                        </span>
                    </div>
                </div>
                <div class="text-4xl font-black text-white tracking-tight">{{ number_format($sellerRequests) }}</div>
                <p class="mt-2.5 text-xs text-amber-500/70 font-medium">Menunggu verifikasi admin</p>
            </div>

            {{-- Produk --}}
            <div class="group relative overflow-hidden rounded-2xl border border-slate-800/60 bg-slate-900/40 backdrop-blur-sm p-6 hover:bg-slate-800/60 hover:-translate-y-1 hover:shadow-xl hover:shadow-purple-500/5 transition-all duration-300">
                <div class="flex items-center justify-between mb-6">
                    <div class="text-xs font-semibold uppercase tracking-wider text-slate-400 group-hover:text-slate-300 transition-colors">Produk Aktif</div>
                    <div class="p-2.5 rounded-xl bg-gradient-to-br from-purple-500/20 to-purple-600/10 border border-purple-500/20 text-purple-400">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                        </svg>
                    </div>
                </div>
                <div class="text-4xl font-black text-white tracking-tight">{{ number_format($products) }}</div>
            </div>

            {{-- Transaksi --}}
            <div class="group relative overflow-hidden rounded-2xl border border-slate-800/60 bg-slate-900/40 backdrop-blur-sm p-6 hover:bg-slate-800/60 hover:-translate-y-1 hover:shadow-xl hover:shadow-cyan-500/5 transition-all duration-300 lg:col-span-2">
                <div class="flex items-center justify-between mb-6">
                    <div class="text-xs font-semibold uppercase tracking-wider text-slate-400 group-hover:text-slate-300 transition-colors">Total Transaksi Selesai</div>
                    <div class="p-2.5 rounded-xl bg-gradient-to-br from-cyan-500/20 to-cyan-600/10 border border-cyan-500/20 text-cyan-400">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2h2a2 2 0 002-2zM9 19h6m-6 0l6-6m0 0v6m0 0h6m-6 0V10a2 2 0 00-2-2h-2a2 2 0 00-2 2v9a2 2 0 002 2h2a2 2 0 002-2z" />
                        </svg>
                    </div>
                </div>
                <div class="text-4xl font-black text-white tracking-tight">{{ number_format($orders) }}</div>
            </div>
        </div>

        {{-- Quick Links --}}
        <section class="grid gap-5 lg:grid-cols-3">
            <a href="{{ route('admin.users.index') }}"
                class="group flex flex-col rounded-2xl border border-slate-800/60 bg-slate-900/40 backdrop-blur-sm p-7 transition-all duration-300 hover:-translate-y-1 hover:shadow-2xl hover:shadow-amber-500/5 hover:border-amber-500/30 hover:bg-slate-800/50 relative overflow-hidden">
                <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-amber-500/20 to-amber-600/10 border border-amber-500/20 flex items-center justify-center text-amber-500 mb-5 group-hover:scale-110 group-hover:rotate-3 transition-transform duration-300">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37a1.724 1.724 0 002.572-1.065z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                </div>
                <h2 class="text-xl font-bold text-white group-hover:text-amber-400 transition-colors">Kelola Akun</h2>
                <p class="mt-3 text-sm text-slate-400 leading-relaxed group-hover:text-slate-300 transition-colors">Pusat kendali user: verifikasi seller baru, moderasi akun bermasalah, hingga pengaturan role pengguna.</p>
            </a>

            <a href="{{ route('admin.banners.index') }}"
                class="group flex flex-col rounded-2xl border border-slate-800/60 bg-slate-900/40 backdrop-blur-sm p-7 transition-all duration-300 hover:-translate-y-1 hover:shadow-2xl hover:shadow-blue-500/5 hover:border-blue-500/30 hover:bg-slate-800/50 relative overflow-hidden">
                <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-blue-500/20 to-blue-600/10 border border-blue-500/20 flex items-center justify-center text-blue-500 mb-5 group-hover:scale-110 group-hover:rotate-3 transition-transform duration-300">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                </div>
                <h2 class="text-xl font-bold text-white group-hover:text-blue-400 transition-colors">Banner Beranda</h2>
                <p class="mt-3 text-sm text-slate-400 leading-relaxed group-hover:text-slate-300 transition-colors">Visual utama marketplace. Atur promosi game terbaru dan event khusus agar langsung terlihat oleh buyer.</p>
            </a>

            <a href="{{ route('admin.notifications.index') }}"
                class="group flex flex-col rounded-2xl border border-slate-800/60 bg-slate-900/40 backdrop-blur-sm p-7 transition-all duration-300 hover:-translate-y-1 hover:shadow-2xl hover:shadow-purple-500/5 hover:border-purple-500/30 hover:bg-slate-800/50 relative overflow-hidden">
                <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-purple-500/20 to-purple-600/10 border border-purple-500/20 flex items-center justify-center text-purple-500 mb-5 group-hover:scale-110 group-hover:rotate-3 transition-transform duration-300">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                    </svg>
                </div>
                <h2 class="text-xl font-bold text-white group-hover:text-purple-400 transition-colors">Pesan & Notifikasi</h2>
                <p class="mt-3 text-sm text-slate-400 leading-relaxed group-hover:text-slate-300 transition-colors">Kirim pengumuman massal atau pemberitahuan penting kepada seluruh buyer dan seller sekaligus.</p>
            </a>
        </section>

        {{-- Section Grafik --}}
        <div class="grid gap-5 lg:grid-cols-2 mt-2">
            {{-- Grafik Transaksi --}}
            <div class="rounded-2xl border border-slate-800/60 bg-slate-900/40 backdrop-blur-sm p-6 sm:p-8">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-bold text-white">Tren Transaksi <span class="text-slate-500 text-sm font-medium ml-1">(7 Hari Terakhir)</span></h3>
                </div>
                <div class="relative h-[280px]">
                    <canvas id="transactionChart"></canvas>
                </div>
            </div>

            {{-- Grafik Keuangan --}}
            <div class="rounded-2xl border border-slate-800/60 bg-slate-900/40 backdrop-blur-sm p-6 sm:p-8">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-bold text-white">Pendapatan <span class="text-slate-500 text-sm font-medium ml-1">(7 Hari Terakhir)</span></h3>
                </div>
                <div class="relative h-[280px]">
                    <canvas id="revenueChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    {{-- Script untuk Chart.js (dengan konfigurasi UI/UX Modern) --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const labels = {!! json_encode($chartLabels) !!};
        
        // Konfigurasi Global Chart.js untuk Dark Mode Premium
        Chart.defaults.color = '#94a3b8';
        Chart.defaults.font.family = "'Inter', 'Segoe UI', sans-serif";
        
        const commonOptions = {
            responsive: true,
            maintainAspectRatio: false,
            interaction: {
                mode: 'index',
                intersect: false,
            },
            plugins: { 
                legend: { display: false },
                tooltip: {
                    backgroundColor: 'rgba(15, 23, 42, 0.9)',
                    titleColor: '#fff',
                    bodyColor: '#cbd5e1',
                    borderColor: 'rgba(255, 255, 255, 0.1)',
                    borderWidth: 1,
                    padding: 12,
                    boxPadding: 6,
                    usePointStyle: true,
                    cornerRadius: 8,
                }
            },
            scales: {
                y: { 
                    beginAtZero: true, 
                    grid: { color: 'rgba(255,255,255,0.03)', drawBorder: false },
                    border: { display: false }
                },
                x: { 
                    grid: { display: false, drawBorder: false },
                    border: { display: false }
                }
            }
        };

        // Script untuk Grafik Transaksi (Line Chart - Smooth)
        const txCtx = document.getElementById('transactionChart').getContext('2d');
        const txGradient = txCtx.createLinearGradient(0, 0, 0, 300);
        txGradient.addColorStop(0, 'rgba(245, 158, 11, 0.2)');
        txGradient.addColorStop(1, 'rgba(245, 158, 11, 0)');

        new Chart(txCtx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Jumlah Order',
                    data: {!! json_encode($chartTransactions) !!},
                    borderColor: '#f59e0b',
                    backgroundColor: txGradient,
                    borderWidth: 3,
                    pointBackgroundColor: '#1e293b',
                    pointBorderColor: '#f59e0b',
                    pointBorderWidth: 2,
                    pointRadius: 4,
                    pointHoverRadius: 6,
                    fill: true,
                    tension: 0.4 // Smooth curve
                }]
            },
            options: commonOptions
        });

        // Script untuk Grafik Keuangan (Bar Chart - Modern)
        new Chart(document.getElementById('revenueChart'), {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Total Pendapatan',
                    data: {!! json_encode($chartRevenue) !!},
                    backgroundColor: '#10b981',
                    hoverBackgroundColor: '#34d399',
                    borderRadius: 6,
                    barThickness: 'flex',
                    maxBarThickness: 32
                }]
            },
            options: {
                ...commonOptions,
                plugins: {
                    ...commonOptions.plugins,
                    tooltip: {
                        ...commonOptions.plugins.tooltip,
                        callbacks: {
                            label: function(context) {
                                let value = context.raw;
                                return ' Pendapatan: Rp ' + new Intl.NumberFormat('id-ID').format(value);
                            }
                        }
                    }
                },
                scales: {
                    ...commonOptions.scales,
                    y: {
                        ...commonOptions.scales.y,
                        ticks: {
                            callback: function(value) {
                                if (value >= 1000000) return 'Rp ' + (value / 1000000) + ' Jt';
                                if (value >= 1000) return 'Rp ' + (value / 1000) + ' Rb';
                                return 'Rp ' + value;
                            }
                        }
                    }
                }
            }
        });
    </script>
@endsection
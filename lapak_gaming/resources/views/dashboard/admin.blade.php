@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
    <div class="space-y-6 lg:space-y-10 animate-fade-in pb-12 px-4 sm:px-0">
        
        {{-- Header Section: Optimized for all screens --}}
        <section class="relative overflow-hidden rounded-[1.5rem] lg:rounded-[2.5rem] border border-slate-800/60 bg-gradient-to-br from-slate-900 via-slate-900 to-slate-950 p-6 sm:p-8 lg:p-12 shadow-2xl">
            {{-- Background Accents --}}
            <div class="absolute -top-24 -right-24 w-64 h-64 lg:w-96 lg:h-96 bg-amber-500/10 blur-[80px] lg:blur-[120px] rounded-full pointer-events-none"></div>
            <div class="absolute -bottom-24 -left-24 w-48 h-48 lg:w-72 lg:h-72 bg-blue-500/10 blur-[70px] lg:blur-[100px] rounded-full pointer-events-none"></div>
            
            <div class="relative flex flex-col gap-6 lg:flex-row lg:items-center lg:justify-between z-10">
                <div class="max-w-2xl text-center lg:text-left">
                    <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-amber-500/10 border border-amber-500/20 mb-4 mx-auto lg:mx-0">
                        <span class="relative flex h-2 w-2">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-amber-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-2 w-2 bg-amber-500"></span>
                        </span>
                        <p class="text-[10px] uppercase tracking-[0.15em] text-amber-400 font-bold">Systems Operational</p>
                    </div>
                    <h1 class="text-3xl sm:text-4xl lg:text-6xl font-black text-white tracking-tight leading-[1.1]">
                        Admin <span class="text-transparent bg-clip-text bg-gradient-to-r from-amber-400 to-amber-600">Console</span>
                    </h1>
                    <p class="mt-4 text-sm sm:text-base lg:text-lg text-slate-400 leading-relaxed font-light">
                        Monitor ekosistem secara menyeluruh mulai dari verifikasi merchant, moderasi konten, hingga analisis performa transaksi harian.
                    </p>
                </div>
                
                <div class="flex flex-col sm:flex-row gap-3 w-full lg:w-auto">
                    <a href="{{ route('admin.users.index') }}"
                        class="flex-1 lg:flex-none inline-flex justify-center items-center rounded-xl bg-amber-500 px-6 py-4 font-bold text-slate-950 hover:bg-amber-400 active:scale-95 transition-all duration-200 shadow-lg shadow-amber-500/20">
                        Kelola Akun
                    </a>
                    <a href="{{ route('admin.orders.index') }}"
                        class="flex-1 lg:flex-none inline-flex justify-center items-center rounded-xl border border-slate-700 bg-slate-800/40 backdrop-blur-md px-6 py-4 font-bold text-white hover:bg-slate-700 active:scale-95 transition-all duration-200">
                        Transaksi
                    </a>
                </div>
            </div>
        </section>

        {{-- Stats Grid: Responsive Auto-fit --}}
        <div class="grid gap-4 sm:gap-6 grid-cols-1 sm:grid-cols-2 xl:grid-cols-4">
            {{-- Stat Card Component --}}
            @php
                $stats = [
                    ['label' => 'Total User', 'val' => $totalUsers, 'color' => 'blue', 'icon' => 'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z'],
                    ['label' => 'Buyer', 'val' => $buyers, 'color' => 'indigo', 'icon' => 'M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z'],
                    ['label' => 'Verified Seller', 'val' => $sellers, 'color' => 'emerald', 'icon' => 'M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z'],
                    ['label' => 'Akun Suspend', 'val' => $suspendedUsers, 'color' => 'rose', 'icon' => 'M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636']
                ];
            @endphp

            @foreach($stats as $stat)
            <div class="group relative overflow-hidden rounded-2xl border border-slate-800/60 bg-slate-900/40 p-6 transition-all duration-300 hover:border-{{ $stat['color'] }}-500/30 hover:bg-slate-800/60">
                <div class="flex items-center justify-between mb-4">
                    <span class="text-xs font-bold uppercase tracking-widest text-slate-500 group-hover:text-slate-300 transition-colors">{{ $stat['label'] }}</span>
                    <div class="p-2 rounded-lg bg-{{ $stat['color'] }}-500/10 text-{{ $stat['color'] }}-400 border border-{{ $stat['color'] }}-500/20">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $stat['icon'] }}" />
                        </svg>
                    </div>
                </div>
                <div class="text-3xl font-black text-white tracking-tight">{{ number_format($stat['val']) }}</div>
            </div>
            @endforeach
        </div>

        {{-- Specialized Stats & Quick Links --}}
        <div class="grid gap-6 lg:grid-cols-12">
            {{-- Request Seller (High Priority) --}}
            <div class="lg:col-span-4 group relative overflow-hidden rounded-2xl border-2 border-amber-500/20 bg-gradient-to-br from-amber-500/5 to-transparent p-6 shadow-lg shadow-amber-500/5 transition-all hover:bg-amber-500/10">
                <div class="flex items-center justify-between mb-6">
                    <div class="flex items-center gap-2">
                        <span class="text-xs font-bold uppercase tracking-widest text-amber-500">Request Seller</span>
                        @if($sellerRequests > 0)
                            <span class="flex h-2 w-2 rounded-full bg-amber-500 animate-pulse"></span>
                        @endif
                    </div>
                    <a href="{{ route('admin.users.index') }}" class="text-[10px] font-bold text-amber-500 underline underline-offset-4">LIHAT SEMUA</a>
                </div>
                <div class="text-5xl font-black text-white mb-2">{{ number_format($sellerRequests) }}</div>
                <p class="text-sm text-slate-400 font-medium italic">Butuh peninjauan segera</p>
            </div>

            {{-- Summary Cards --}}
            <div class="lg:col-span-8 grid gap-4 sm:gap-6 grid-cols-1 sm:grid-cols-2">
                <div class="rounded-2xl border border-slate-800/60 bg-slate-900/40 p-6 flex items-center gap-5">
                    <div class="h-12 w-12 rounded-full bg-purple-500/10 flex items-center justify-center text-purple-400 border border-purple-500/20">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" /></svg>
                    </div>
                    <div>
                        <div class="text-[10px] font-bold text-slate-500 uppercase tracking-widest leading-none mb-1">Produk Aktif</div>
                        <div class="text-2xl font-black text-white leading-none">{{ number_format($products) }}</div>
                    </div>
                </div>
                <div class="rounded-2xl border border-slate-800/60 bg-slate-900/40 p-6 flex items-center gap-5">
                    <div class="h-12 w-12 rounded-full bg-cyan-500/10 flex items-center justify-center text-cyan-400 border border-cyan-500/20">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zM9 19h6m-6 0l6-6m0 0v6m0 0h6m-6 0V10a2 2 0 00-2-2h-2a2 2 0 00-2 2v9a2 2 0 002 2h2a2 2 0 002-2z" /></svg>
                    </div>
                    <div>
                        <div class="text-[10px] font-bold text-slate-500 uppercase tracking-widest leading-none mb-1">Total Transaksi</div>
                        <div class="text-2xl font-black text-white leading-none">{{ number_format($orders) }}</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Charts Section --}}
        <div class="grid gap-6 lg:grid-cols-2">
            {{-- Box Grafik --}}
            <div class="rounded-3xl border border-slate-800/60 bg-slate-900/40 p-6 sm:p-8 transition-all hover:bg-slate-900/60">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
                    <h3 class="text-xl font-bold text-white tracking-tight">Trend Transaksi</h3>
                    <div class="px-3 py-1 rounded-md bg-slate-800 text-[10px] font-bold text-slate-400 border border-slate-700 uppercase tracking-wider">7 Hari Terakhir</div>
                </div>
                <div class="relative w-full h-[250px] sm:h-[320px]">
                    <canvas id="transactionChart"></canvas>
                </div>
            </div>

            <div class="rounded-3xl border border-slate-800/60 bg-slate-900/40 p-6 sm:p-8 transition-all hover:bg-slate-900/60">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
                    <h3 class="text-xl font-bold text-white tracking-tight">Revenue Analytics</h3>
                    <div class="px-3 py-1 rounded-md bg-emerald-500/10 text-[10px] font-bold text-emerald-500 border border-emerald-500/20 uppercase tracking-wider">Financial Overview</div>
                </div>
                <div class="relative w-full h-[250px] sm:h-[320px]">
                    <canvas id="revenueChart"></canvas>
                </div>
            </div>
        </div>

        {{-- Quick Actions --}}
        <div class="pt-6 border-t border-slate-800/60">
            <h4 class="text-xs font-black text-slate-500 uppercase tracking-[0.2em] mb-6">Akses Cepat</h4>
            <div class="grid gap-4 grid-cols-1 sm:grid-cols-2 lg:grid-cols-3">
                @php
                    $links = [
                        ['title' => 'Banner Promo', 'desc' => 'Atur hero visual beranda', 'color' => 'blue', 'route' => 'admin.banners.index'],
                        ['title' => 'Broadcast', 'desc' => 'Kirim notifikasi massal', 'color' => 'purple', 'route' => 'admin.notifications.index'],
                        ['title' => 'System Logs', 'desc' => 'Pantau aktivitas server', 'color' => 'slate', 'route' => '#'],
                    ];
                @endphp

                @foreach($links as $link)
                <a href="{{ $link['route'] != '#' ? route($link['route']) : '#' }}" 
                   class="group p-5 rounded-2xl border border-slate-800 bg-slate-900/20 hover:border-{{ $link['color'] }}-500/40 hover:bg-slate-800/40 transition-all duration-300">
                    <h5 class="font-bold text-white group-hover:text-{{ $link['color'] }}-400 transition-colors">{{ $link['title'] }}</h5>
                    <p class="text-xs text-slate-500 mt-1">{{ $link['desc'] }}</p>
                </a>
                @endforeach
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const labels = {!! json_encode($chartLabels) !!};
        Chart.defaults.color = '#64748b';
        Chart.defaults.font.family = "'Plus Jakarta Sans', sans-serif";
        Chart.defaults.font.weight = '600';

        const chartOptions = {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    padding: 12,
                    backgroundColor: '#0f172a',
                    titleColor: '#f8fafc',
                    bodyColor: '#94a3b8',
                    cornerRadius: 12,
                    displayColors: false
                }
            },
            scales: {
                y: { grid: { color: 'rgba(255,255,255,0.03)', drawBorder: false }, border: { display: false } },
                x: { grid: { display: false }, border: { display: false } }
            }
        };

        // Transaction Chart
        new Chart(document.getElementById('transactionChart'), {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    data: {!! json_encode($chartTransactions) !!},
                    borderColor: '#f59e0b',
                    borderWidth: 4,
                    fill: true,
                    backgroundColor: (context) => {
                        const gradient = context.chart.ctx.createLinearGradient(0, 0, 0, 400);
                        gradient.addColorStop(0, 'rgba(245, 158, 11, 0.15)');
                        gradient.addColorStop(1, 'rgba(245, 158, 11, 0)');
                        return gradient;
                    },
                    tension: 0.4,
                    pointRadius: 0,
                    pointHoverRadius: 6,
                    pointHoverBackgroundColor: '#f59e0b',
                    pointHoverBorderColor: '#fff',
                    pointHoverBorderWidth: 3,
                }]
            },
            options: chartOptions
        });

        // Revenue Chart
        new Chart(document.getElementById('revenueChart'), {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    data: {!! json_encode($chartRevenue) !!},
                    backgroundColor: '#10b981',
                    borderRadius: 8,
                    maxBarThickness: 40
                }]
            },
            options: {
                ...chartOptions,
                plugins: {
                    ...chartOptions.plugins,
                    tooltip: {
                        ...chartOptions.plugins.tooltip,
                        callbacks: {
                            label: (context) => ' Rp ' + new Intl.NumberFormat('id-ID').format(context.raw)
                        }
                    }
                }
            }
        });
    </script>
@endsection
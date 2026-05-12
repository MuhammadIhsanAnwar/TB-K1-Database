@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
    <div class="space-y-8 animate-fade-in">
        {{-- Header Section --}}
        <section class="rounded-3xl border border-slate-800 bg-slate-950 p-8 shadow-2xl relative overflow-hidden">
            <div class="absolute top-0 right-0 w-64 h-64 bg-amber-500/10 blur-[100px] -mr-32 -mt-32"></div>
            <div class="relative flex flex-col gap-6 lg:flex-row lg:items-center lg:justify-between">
                <div>
                    <p class="text-xs uppercase tracking-[0.28em] text-amber-400 font-bold">Admin Control Center</p>
                    <h1 class="mt-2 text-4xl font-black text-white tracking-tight">Panel Admin Utama</h1>
                    <p class="mt-3 max-w-2xl text-slate-400 leading-relaxed">
                        Kelola ekosistem marketplace: verifikasi seller, moderasi akun, manajemen iklan banner, hingga pemantauan seluruh transaksi secara real-time.
                    </p>
                </div>
                <div class="flex gap-3 text-sm">
                    <a href="{{ route('admin.users.index') }}" class="rounded-2xl bg-amber-500 px-6 py-3.5 font-bold text-slate-950 shadow-lg shadow-amber-500/20 hover:bg-amber-400 hover:-translate-y-0.5 transition-all">
                        Kelola Akun
                    </a>
                    <a href="{{ route('admin.orders.index') }}" class="rounded-2xl border border-slate-700 bg-slate-900/50 px-6 py-3.5 font-bold text-white hover:border-slate-500 hover:bg-slate-800 transition-all">
                        Transaksi
                    </a>
                </div>
            </div>
        </section>

        {{-- Stats Grid --}}
        {{-- Kita bagi jadi 4 kolom di layar besar, 2 di tablet, 1 di HP --}}
        <div class="grid gap-4 grid-cols-1 sm:grid-cols-2 lg:grid-cols-4">
            
            {{-- Total Users --}}
            <div class="group rounded-3xl border border-slate-800 bg-slate-900 p-6 hover:border-slate-600 transition-all">
                <div class="flex items-center justify-between mb-4">
                    <div class="text-xs font-bold uppercase tracking-widest text-slate-500">Total User</div>
                    <div class="p-2 rounded-xl bg-blue-500/10 text-blue-400">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                    </div>
                </div>
                <div class="text-4xl font-black text-white">{{ number_format($totalUsers) }}</div>
            </div>

            {{-- Buyers --}}
            <div class="group rounded-3xl border border-slate-800 bg-slate-900 p-6 hover:border-slate-600 transition-all">
                <div class="flex items-center justify-between mb-4">
                    <div class="text-xs font-bold uppercase tracking-widest text-slate-500">Buyer</div>
                    <div class="p-2 rounded-xl bg-indigo-500/10 text-indigo-400">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                    </div>
                </div>
                <div class="text-4xl font-black text-white">{{ number_format($buyers) }}</div>
            </div>

            {{-- Sellers --}}
            <div class="group rounded-3xl border border-slate-800 bg-slate-900 p-6 hover:border-slate-600 transition-all border-b-emerald-500/50">
                <div class="flex items-center justify-between mb-4">
                    <div class="text-xs font-bold uppercase tracking-widest text-slate-500 text-emerald-500/70">Verified Seller</div>
                    <div class="p-2 rounded-xl bg-emerald-500/10 text-emerald-400">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                    </div>
                </div>
                <div class="text-4xl font-black text-white">{{ number_format($sellers) }}</div>
            </div>

            {{-- Suspended --}}
            <div class="group rounded-3xl border border-slate-800 bg-slate-900 p-6 hover:border-slate-600 transition-all border-b-rose-500/50">
                <div class="flex items-center justify-between mb-4">
                    <div class="text-xs font-bold uppercase tracking-widest text-slate-500 text-rose-500/70">Akun Suspend</div>
                    <div class="p-2 rounded-xl bg-rose-500/10 text-rose-400">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/></svg>
                    </div>
                </div>
                <div class="text-4xl font-black text-white">{{ number_format($suspendedUsers) }}</div>
            </div>

            {{-- Request Seller (PENTING: Status Real 0) --}}
            <div class="group rounded-3xl border border-slate-800 bg-slate-900 p-6 hover:border-amber-500/50 transition-all border-l-4 border-l-amber-500">
                <div class="flex items-center justify-between mb-4">
                    <div class="text-xs font-bold uppercase tracking-widest text-amber-500">Request Seller</div>
                    <div class="animate-pulse flex h-3 w-3 rounded-full bg-amber-500"></div>
                </div>
                <div class="text-4xl font-black text-white">{{ number_format($sellerRequests) }}</div>
                <p class="mt-2 text-xs text-slate-500 italic">Menunggu verifikasi</p>
            </div>

            {{-- Produk --}}
            <div class="group rounded-3xl border border-slate-800 bg-slate-900 p-6 hover:border-slate-600 transition-all">
                <div class="flex items-center justify-between mb-4">
                    <div class="text-xs font-bold uppercase tracking-widest text-slate-500">Produk Aktif</div>
                    <div class="p-2 rounded-xl bg-purple-500/10 text-purple-400">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                    </div>
                </div>
                <div class="text-4xl font-black text-white">{{ number_format($products) }}</div>
            </div>

            {{-- Transaksi --}}
            <div class="group rounded-3xl border border-slate-800 bg-slate-900 p-6 hover:border-slate-600 transition-all lg:col-span-2">
                <div class="flex items-center justify-between mb-4">
                    <div class="text-xs font-bold uppercase tracking-widest text-slate-500">Total Transaksi Selesai</div>
                    <div class="p-2 rounded-xl bg-cyan-500/10 text-cyan-400">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2h2a2 2 0 002-2zM9 19h6m-6 0l6-6m0 0v6m0 0h6m-6 0V10a2 2 0 00-2-2h-2a2 2 0 00-2 2v9a2 2 0 002 2h2a2 2 0 002-2z"/></svg>
                    </div>
                </div>
                <div class="text-4xl font-black text-white">{{ number_format($orders) }}</div>
            </div>
        </div>

        {{-- Quick Links --}}
        <section class="grid gap-6 lg:grid-cols-3">
            <a href="{{ route('admin.users.index') }}" class="group rounded-3xl border border-slate-800 bg-slate-900 p-8 transition hover:border-amber-500/50 hover:bg-slate-850">
                <div class="w-12 h-12 rounded-2xl bg-amber-500/10 flex items-center justify-center text-amber-500 mb-6 group-hover:scale-110 transition">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37a1.724 1.724 0 002.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                </div>
                <h2 class="text-2xl font-bold text-white">Kelola Akun</h2>
                <p class="mt-3 text-sm text-slate-400 leading-relaxed">Pusat kendali user: verifikasi seller baru, moderasi akun bermasalah, hingga pengaturan role pengguna.</p>
            </a>

            <a href="{{ route('admin.banners.index') }}" class="group rounded-3xl border border-slate-800 bg-slate-900 p-8 transition hover:border-blue-500/50 hover:bg-slate-850">
                <div class="w-12 h-12 rounded-2xl bg-blue-500/10 flex items-center justify-center text-blue-500 mb-6 group-hover:scale-110 transition">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                </div>
                <h2 class="text-2xl font-bold text-white">Banner Beranda</h2>
                <p class="mt-3 text-sm text-slate-400 leading-relaxed">Visual utama marketplace. Atur promosi game terbaru dan event khusus agar langsung terlihat oleh buyer.</p>
            </a>

            <a href="{{ route('admin.notifications.index') }}" class="group rounded-3xl border border-slate-800 bg-slate-900 p-8 transition hover:border-purple-500/50 hover:bg-slate-850">
                <div class="w-12 h-12 rounded-2xl bg-purple-500/10 flex items-center justify-center text-purple-500 mb-6 group-hover:scale-110 transition">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                </div>
                <h2 class="text-2xl font-bold text-white">Pesan & Notifikasi</h2>
                <p class="mt-3 text-sm text-slate-400 leading-relaxed">Kirim pengumuman massal atau pemberitahuan penting kepada seluruh buyer dan seller sekaligus.</p>
            </a>
        </section>
    </div>
@endsection
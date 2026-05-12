@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
    <div class="space-y-8">
        <section class="rounded-3xl border border-slate-800 bg-slate-950 p-8">
            <div class="flex flex-col gap-3 lg:flex-row lg:items-end lg:justify-between">
                <div>
                    <p class="text-xs uppercase tracking-[0.28em] text-amber-300">Admin Control Center</p>
                    <h1 class="mt-2 text-4xl font-bold text-white">Panel Admin Utama</h1>
                    <p class="mt-3 max-w-3xl text-slate-400">Kelola akun buyer dan seller, verifikasi seller yang mendaftar, atur banner beranda, kirim notifikasi massal, dan pantau transaksi dari satu tempat.</p>
                </div>
                <div class="grid grid-cols-2 gap-3 text-sm">
                    <a href="{{ route('admin.users.index') }}" class="rounded-2xl bg-amber-500 px-4 py-3 font-semibold text-slate-950 text-center hover:bg-amber-400">Kelola Akun</a>
                    <a href="{{ route('admin.orders.index') }}" class="rounded-2xl border border-slate-700 px-4 py-3 font-semibold text-white text-center hover:border-slate-500">Transaksi</a>
                </div>
            </div>
        </section>

        <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
            <div class="rounded-3xl border border-slate-800 bg-slate-900 p-6">
                <div class="text-sm text-slate-400">Total User</div>
                <div class="mt-2 text-3xl font-black text-white">{{ $totalUsers }}</div>
            </div>
            <div class="rounded-3xl border border-slate-800 bg-slate-900 p-6">
                <div class="text-sm text-slate-400">Buyer</div>
                <div class="mt-2 text-3xl font-black text-white">{{ $buyers }}</div>
            </div>
            <div class="rounded-3xl border border-slate-800 bg-slate-900 p-6">
                <div class="text-sm text-slate-400">Seller</div>
                <div class="mt-2 text-3xl font-black text-white">{{ $sellers }}</div>
            </div>
            <div class="rounded-3xl border border-slate-800 bg-slate-900 p-6">
                <div class="text-sm text-slate-400">Akun Suspend</div>
                <div class="mt-2 text-3xl font-black text-white">{{ $suspendedUsers }}</div>
            </div>
            <div class="rounded-3xl border border-slate-800 bg-slate-900 p-6">
                <div class="text-sm text-slate-400">Request Seller</div>
                <div class="mt-2 text-3xl font-black text-white">{{ $sellerRequests }}</div>
            </div>
            <div class="rounded-3xl border border-slate-800 bg-slate-900 p-6">
                <div class="text-sm text-slate-400">Produk</div>
                <div class="mt-2 text-3xl font-black text-white">{{ $products }}</div>
            </div>
            <div class="rounded-3xl border border-slate-800 bg-slate-900 p-6">
                <div class="text-sm text-slate-400">Transaksi</div>
                <div class="mt-2 text-3xl font-black text-white">{{ $orders }}</div>
            </div>
        </div>

        <section class="grid gap-4 lg:grid-cols-3">
            <a href="{{ route('admin.users.index') }}" class="rounded-3xl border border-slate-800 bg-slate-900 p-6 transition hover:border-amber-500/50 hover:bg-slate-850">
                <h2 class="text-xl font-semibold text-white">Kelola Akun</h2>
                <p class="mt-3 text-sm text-slate-400">Hapus akun buyer/seller, suspend akun, ubah role, dan verifikasi seller dari sini.</p>
            </a>
            <a href="{{ route('admin.banners.index') }}" class="rounded-3xl border border-slate-800 bg-slate-900 p-6 transition hover:border-amber-500/50 hover:bg-slate-850">
                <h2 class="text-xl font-semibold text-white">Banner Beranda</h2>
                <p class="mt-3 text-sm text-slate-400">Atur banner iklan yang tampil di halaman utama marketplace.</p>
            </a>
            <a href="{{ route('admin.notifications.index') }}" class="rounded-3xl border border-slate-800 bg-slate-900 p-6 transition hover:border-amber-500/50 hover:bg-slate-850">
                <h2 class="text-xl font-semibold text-white">Pesan & Notifikasi</h2>
                <p class="mt-3 text-sm text-slate-400">Kirim pengumuman ke buyer dan seller secara massal.</p>
            </a>
        </section>
    </div>
@endsection
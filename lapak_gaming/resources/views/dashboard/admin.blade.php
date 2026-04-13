@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
    <div class="grid gap-6 sm:grid-cols-2 xl:grid-cols-4">
        <div class="rounded-[2rem] border border-slate-200 bg-white p-6 dark:border-slate-800 dark:bg-slate-900">
            <div class="text-sm text-slate-500">Total Users</div>
            <div class="mt-2 text-3xl font-black">{{ $buyers }}</div>
        </div>
        <div class="rounded-[2rem] border border-slate-200 bg-white p-6 dark:border-slate-800 dark:bg-slate-900">
            <div class="text-sm text-slate-500">Produk</div>
            <div class="mt-2 text-3xl font-black">{{ $products }}</div>
        </div>
        <div class="rounded-[2rem] border border-slate-200 bg-white p-6 dark:border-slate-800 dark:bg-slate-900">
            <div class="text-sm text-slate-500">Transaksi</div>
            <div class="mt-2 text-3xl font-black">{{ $orders }}</div>
        </div>
        <div class="rounded-[2rem] border border-slate-200 bg-white p-6 dark:border-slate-800 dark:bg-slate-900">
            <div class="text-sm text-slate-500">Pending</div>
            <div class="mt-2 text-3xl font-black">{{ $pendingOrders }}</div>
        </div>
    </div>

    <section class="mt-8 rounded-[2rem] border border-slate-200 bg-white p-6 dark:border-slate-800 dark:bg-slate-900">
        <h2 class="text-xl font-black">Admin Controls</h2>
        <div class="mt-4 grid gap-4 md:grid-cols-3 text-sm text-slate-600 dark:text-slate-300">
            <div class="rounded-2xl bg-slate-50 p-4 dark:bg-slate-950/40">Moderasi produk dan review</div>
            <div class="rounded-2xl bg-slate-50 p-4 dark:bg-slate-950/40">Verifikasi seller dan level</div>
            <div class="rounded-2xl bg-slate-50 p-4 dark:bg-slate-950/40">Monitoring dispute dan fee</div>
        </div>
    </section>
@endsection
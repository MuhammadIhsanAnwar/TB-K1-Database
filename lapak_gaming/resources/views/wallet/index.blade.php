@extends('layouts.app')

@section('title', 'Wallet')

@section('content')
    <div class="grid gap-6 lg:grid-cols-[.9fr_1.1fr]">
        <section class="rounded-4xl border border-slate-200 bg-white p-6 dark:border-slate-800 dark:bg-slate-900">
            <h1 class="text-2xl font-black">Wallet</h1>
            <div class="mt-4 rounded-[1.75rem] bg-slate-950 p-6 text-white dark:bg-white dark:text-slate-950">
                <div class="text-sm text-slate-300 dark:text-slate-500">Balance</div>
                <div class="mt-2 text-4xl font-black">Rp {{ number_format($wallet?->balance ?? 0, 0, ',', '.') }}</div>
                <div class="mt-2 text-sm text-slate-300 dark:text-slate-500">Available: Rp {{ number_format($wallet?->available_balance ?? 0, 0, ',', '.') }}</div>
            </div>

            <div class="mt-6 grid gap-3 sm:grid-cols-2">
                <form method="POST" action="{{ route('wallet.deposit') }}" class="rounded-2xl border border-slate-200 p-4 dark:border-slate-800">
                    @csrf
                    <div class="text-sm font-bold">Deposit</div>
                    <input name="amount" type="number" min="1000" placeholder="10000" class="mt-3 w-full rounded-2xl border border-slate-200 px-4 py-3 dark:border-slate-700 dark:bg-slate-950" />
                    <button class="mt-3 rounded-2xl bg-emerald-500 px-4 py-3 text-sm font-bold text-white">Tambah Saldo</button>
                </form>
                <form method="POST" action="{{ route('wallet.withdraw') }}" class="rounded-2xl border border-slate-200 p-4 dark:border-slate-800">
                    @csrf
                    <div class="text-sm font-bold">Withdraw</div>
                    <input name="amount" type="number" min="1000" placeholder="10000" class="mt-3 w-full rounded-2xl border border-slate-200 px-4 py-3 dark:border-slate-700 dark:bg-slate-950" />
                    <button class="mt-3 rounded-2xl bg-slate-950 px-4 py-3 text-sm font-bold text-white dark:bg-white dark:text-slate-950">Tarik Dana</button>
                </form>
            </div>
        </section>

        <section class="rounded-4xl border border-slate-200 bg-white p-6 dark:border-slate-800 dark:bg-slate-900">
            <h2 class="text-xl font-black">Transaction History</h2>
            <div class="mt-4 space-y-3">
                @forelse (($wallet?->transactions ?? collect()) as $transaction)
                    <div class="rounded-2xl bg-slate-50 p-4 dark:bg-slate-950/40">
                        <div class="flex items-center justify-between text-sm">
                            <span class="font-bold">{{ $transaction->type }}</span>
                            <span class="{{ $transaction->direction === 'credit' ? 'text-emerald-500' : 'text-rose-500' }}">{{ $transaction->direction }}</span>
                        </div>
                        <div class="mt-1 text-sm text-slate-500">Rp {{ number_format($transaction->amount, 0, ',', '.') }}</div>
                    </div>
                @empty
                    <p class="text-sm text-slate-500">Belum ada transaksi.</p>
                @endforelse
            </div>
        </section>
    </div>
@endsection
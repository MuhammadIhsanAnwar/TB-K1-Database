@extends('layouts.app')

@section('title', 'Daftar Jadi Seller')

@section('content')
<div class="min-h-screen bg-slate-950 py-12 px-4">
    <div class="mx-auto max-w-3xl rounded-3xl border border-slate-800 bg-slate-900/95 p-8 shadow-2xl shadow-black/40">
        <h1 class="text-3xl font-bold text-white">Daftar sebagai Seller</h1>
        <p class="mt-3 text-slate-400">Akun buyer Anda akan tetap aktif. Setelah mendaftar, Anda bisa mengelola produk dan toko, sekaligus tetap membeli sebagai buyer.</p>

        <div class="mt-8 rounded-3xl bg-slate-800 p-6">
            <form method="POST" action="{{ route('seller.register') }}">
                @csrf

                <div class="space-y-4">
                    <div>
                        <h2 class="text-xl font-semibold text-white">Status saat ini</h2>
                        <p class="mt-2 text-slate-400">Anda masuk sebagai <strong>{{ Auth::user()->role === 'buyer' ? 'Buyer' : Auth::user()->role }}</strong></p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-300">Nama Toko</label>
                        <input name="store_name" type="text" value="{{ old('store_name', Auth::user()->name) }}" class="mt-2 w-full rounded-2xl border border-slate-700 bg-slate-950 px-4 py-3 text-white outline-none" placeholder="Nama toko Anda" />
                        @error('store_name') <p class="mt-2 text-sm text-rose-400">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-300">Deskripsi Singkat Toko</label>
                        <textarea name="bio" rows="3" class="mt-2 w-full rounded-2xl border border-slate-700 bg-slate-950 px-4 py-3 text-white outline-none" placeholder="Jelaskan toko Anda...">{{ old('bio', Auth::user()->profile?->bio) }}</textarea>
                    </div>
                </div>

                <button type="submit" class="mt-6 w-full rounded-2xl bg-amber-500 px-5 py-3 font-semibold text-slate-950 transition hover:bg-amber-400">Daftar Jadi Seller</button>
            </form>
        </div>
    </div>
</div>
@endsection

@extends('layouts.app')

@section('title', 'Seller Dashboard')

@section('content')
<div class="space-y-8">
    {{-- PENDING STATUS WARNING --}}
    @if($user->seller_status === 'pending')
    <div class="rounded-[2rem] border border-yellow-700/50 bg-yellow-900/20 p-6 shadow-lg">
        <div class="flex items-start gap-4">
            <div class="pt-1">
                <svg class="w-6 h-6 text-yellow-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div class="flex-1">
                <h3 class="text-lg font-bold text-yellow-200">Toko Anda Masih Dalam Verifikasi</h3>
                <p class="text-sm text-yellow-300 mt-1">Pendaftaran toko Anda sedang ditinjau oleh tim kami. Anda akan menerima notifikasi saat status berubah. Saat ini, Anda tidak dapat menjual produk hingga persetujuan diterima.</p>
            </div>
        </div>
    </div>
    @elseif($user->seller_status === 'rejected')
    <div class="rounded-[2rem] border border-red-700/50 bg-red-900/20 p-6 shadow-lg">
        <div class="flex items-start gap-4">
            <div class="pt-1">
                <svg class="w-6 h-6 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </div>
            <div class="flex-1">
                <h3 class="text-lg font-bold text-red-200">Pengajuan Toko Ditolak</h3>
                <p class="text-sm text-red-300 mt-1">{{ $user->seller_rejection_reason ?? 'Alasan penolakan tidak tersedia.' }}</p>
                <a href="{{ route('seller.register.form') }}" class="text-sm text-red-400 hover:text-red-300 mt-2 inline-block underline">Ajukan Kembali →</a>
            </div>
        </div>
    </div>
    @endif

    {{-- STATS CARDS --}}
    <div class="grid gap-6 lg:grid-cols-3">
        <div class="rounded-[2rem] border border-slate-800 bg-slate-900 p-8 shadow-xl">
            <div class="text-xs font-black uppercase tracking-widest text-slate-500">Saldo Penjualan</div>
            <div class="mt-2 text-4xl font-black text-amber-500">
                Rp {{ number_format($seller->balance ?? 0, 0, ',', '.') }}
            </div>
        </div>
        <div class="rounded-[2rem] border border-slate-800 bg-slate-900 p-8 shadow-xl">
            <div class="text-xs font-black uppercase tracking-widest text-slate-500">Produk Aktif</div>
            <div class="mt-2 text-4xl font-black text-white">
                {{ $products->where('status', 'published')->count() }}
            </div>
        </div>
        <div class="rounded-[2rem] border border-slate-800 bg-slate-900 p-8 shadow-xl">
            <div class="text-xs font-black uppercase tracking-widest text-slate-500">Total Pesanan</div>
            <div class="mt-2 text-4xl font-black text-white">
                {{ $orders->count() }}
            </div>
        </div>
    </div>

    {{-- QUICK ACTIONS (Ini yang Baru Kita Tambahkan) --}}
    <div class="grid gap-6 md:grid-cols-2">
        <a href="{{ route('seller.produk.create') }}" class="group flex items-center justify-between p-6 bg-amber-500 rounded-3xl transition-all hover:scale-[1.02] active:scale-95 shadow-lg shadow-amber-500/20">
            <div>
                <h3 class="text-xl font-black text-slate-950 italic">TAMBAH PRODUK</h3>
                <p class="text-slate-900/60 text-sm font-bold tracking-tight">Mulai jualan item baru sekarang</p>
            </div>
            <div class="bg-white/20 p-4 rounded-2xl">
                <svg class="w-8 h-8 text-slate-950" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4"/>
                </svg>
            </div>
        </a>
        <a href="{{ route('seller.produk.index') }}" class="group flex items-center justify-between p-6 bg-slate-800 border border-slate-700 rounded-3xl transition-all hover:border-amber-500/50">
            <div>
                <h3 class="text-xl font-black text-white italic">KELOLA TOKO</h3>
                <p class="text-slate-400 text-sm font-bold tracking-tight">Cek stok & update dagangan kamu</p>
            </div>
            <div class="bg-slate-700 p-4 rounded-2xl group-hover:bg-amber-500 transition-colors">
                <svg class="w-8 h-8 text-white group-hover:text-slate-950" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/>
                </svg>
            </div>
        </a>
    </div>

    {{-- TABEL AKTIVITAS (Bawah) --}}
    <div class="grid gap-8 lg:grid-cols-2">
        {{-- My Products Preview --}}
        <section class="rounded-[2.5rem] border border-slate-800 bg-slate-900/50 p-8 shadow-2xl">
            <h2 class="text-2xl font-black text-white italic tracking-tighter">DAFTAR PRODUK</h2>
            <div class="mt-6 space-y-4">
                @forelse ($products->take(5) as $product)
                    <div class="flex items-center justify-between p-4 rounded-2xl bg-slate-950 border border-slate-800">
                        <div class="flex items-center gap-4">
                            <img src="{{ $product->image ? asset('storage/'.$product->image) : 'https://ui-avatars.com/api/?name='.urlencode($product->name) }}" class="w-10 h-10 rounded-lg object-cover">
                            <div>
                                <div class="font-bold text-white">{{ $product->name }}</div>
                                <div class="text-[10px] text-amber-500 font-black uppercase">{{ $product->status }}</div>
                            </div>
                        </div>
                        <div class="text-sm font-black text-white">Rp {{ number_format($product->price, 0, ',', '.') }}</div>
                    </div>
                @empty
                    <p class="text-sm text-slate-500 italic">Belum ada produk jualan.</p>
                @endforelse
            </div>
        </section>

        {{-- Recent Orders Preview --}}
        <section class="rounded-[2.5rem] border border-slate-800 bg-slate-900/50 p-8 shadow-2xl">
            <h2 class="text-2xl font-black text-white italic tracking-tighter">ORDER TERBARU</h2>
            <div class="mt-6 space-y-4">
                @forelse ($orders->take(5) as $item)
                    <div class="p-4 rounded-2xl bg-slate-950 border border-slate-800 flex justify-between items-center">
                        <div>
                            <div class="text-xs font-black text-amber-500 uppercase">#{{ $item->order->invoice_number }}</div>
                            <div class="text-sm font-bold text-white">{{ $item->product->name }}</div>
                        </div>
                        <span class="px-3 py-1 bg-slate-800 text-[10px] font-black text-slate-300 rounded-lg uppercase tracking-widest">{{ $item->order->status }}</span>
                    </div>
                @empty
                    <p class="text-sm text-slate-500 italic">Belum ada orderan masuk.</p>
                @endforelse
            </div>
        </section>
    </div>
</div>
@endsection
@extends('layouts.app')

@section('title', 'Kebijakan Pengembalian Dana — Lapak Gaming')

@section('content')
<div class="max-w-4xl mx-auto px-4 py-12 md:py-20 animate-fade-in relative z-10">
    
    {{-- Background Glow Dekoratif --}}
    <div class="absolute top-10 left-1/2 -translate-x-1/2 w-full max-w-2xl h-64 bg-rose-600/10 blur-[100px] rounded-full pointer-events-none -z-10"></div>

    {{-- Header --}}
    <div class="text-center mb-16 border-b border-gray-800/50 pb-10 relative">
        <h1 class="text-4xl md:text-5xl font-display font-extrabold text-white mb-6 tracking-tight">
            Kebijakan <span class="text-transparent bg-clip-text bg-gradient-to-r from-rose-400 to-orange-500">Refund</span>
        </h1>
        <div class="inline-flex items-center gap-3 bg-gray-900/80 backdrop-blur-sm px-5 py-2.5 rounded-full border border-gray-800 shadow-lg">
            <span class="w-2.5 h-2.5 rounded-full bg-rose-500 animate-pulse"></span>
            <span class="text-slate-300 text-sm font-medium">Pembaruan Terakhir: 10 Mei 2026</span>
        </div>
    </div>

    {{-- Konten Pembuka --}}
    <div class="bg-gray-900/40 border border-gray-700/50 rounded-2xl p-6 md:p-8 mb-10 text-center">
        <p class="text-slate-300 leading-relaxed text-sm md:text-base">
            Di Lapak Gaming, kepuasan dan keamanan transaksi Anda adalah prioritas utama. Jika produk yang Anda terima tidak sesuai atau mengalami kendala, Anda dapat mengajukan pengembalian dana sesuai dengan ketentuan yang berlaku.
        </p>
    </div>

    {{-- Daftar Kebijakan (Full Highlighted Cards) --}}
    <div class="space-y-6">
        
        {{-- Section 1: Syarat Utama --}}
        <div class="group relative bg-rose-900/10 backdrop-blur-sm border border-rose-500/30 rounded-2xl p-6 md:p-8 hover:border-rose-500/60 hover:shadow-[0_8px_30px_rgba(244,63,94,0.15)] hover:-translate-y-1 transition-all duration-300 overflow-hidden">
            <div class="absolute left-0 top-0 bottom-0 w-1.5 bg-rose-500/50 group-hover:bg-rose-500 shadow-[0_0_10px_rgba(244,63,94,0.5)] transition-colors duration-300"></div>
            
            <h2 class="text-xl md:text-2xl font-display font-bold text-white mb-5 flex items-center gap-4">
                <span class="flex items-center justify-center w-10 h-10 rounded-xl bg-rose-500/20 text-rose-400 font-bold text-lg group-hover:scale-110 group-hover:bg-rose-500/30 transition-all duration-300">1</span>
                Kapan Saya Bisa Mengajukan Refund?
            </h2>
            <ul class="space-y-3 text-slate-300 text-sm md:text-base leading-relaxed ml-2">
                <li class="flex gap-3"><span class="text-rose-500 mt-0.5 shrink-0">▹</span> Produk yang diterima tidak sesuai dengan deskripsi penjual.</li>
                <li class="flex gap-3"><span class="text-rose-500 mt-0.5 shrink-0">▹</span> Penjual tidak mengirimkan produk melewati batas waktu yang ditentukan.</li>
                <li class="flex gap-3"><span class="text-rose-500 mt-0.5 shrink-0">▹</span> Voucher atau kode yang dikirimkan sudah terpakai atau tidak valid.</li>
                <li class="flex gap-3"><span class="text-rose-500 mt-0.5 shrink-0">▹</span> Akun game yang dibeli terkena <em>rollback</em> atau di-hack kembali oleh penjual dalam masa garansi.</li>
            </ul>
        </div>

        {{-- Section 2: Prosedur --}}
        <div class="group relative bg-blue-900/10 backdrop-blur-sm border border-blue-500/30 rounded-2xl p-6 md:p-8 hover:border-blue-500/60 hover:shadow-[0_8px_30px_rgba(59,130,246,0.15)] hover:-translate-y-1 transition-all duration-300 overflow-hidden">
            <div class="absolute left-0 top-0 bottom-0 w-1.5 bg-blue-500/50 group-hover:bg-blue-500 shadow-[0_0_10px_rgba(59,130,246,0.5)] transition-colors duration-300"></div>
            
            <h2 class="text-xl md:text-2xl font-display font-bold text-white mb-5 flex items-center gap-4">
                <span class="flex items-center justify-center w-10 h-10 rounded-xl bg-blue-500/20 text-blue-400 font-bold text-lg group-hover:scale-110 group-hover:bg-blue-500/30 transition-all duration-300">2</span>
                Prosedur Pengajuan Refund
            </h2>
            <ul class="space-y-4 text-slate-300 text-sm md:text-base leading-relaxed ml-2">
                <li class="flex gap-4">
                    <span class="w-6 h-6 rounded-full bg-blue-500/20 text-blue-400 flex items-center justify-center text-xs shrink-0 mt-1">A</span>
                    <span>Jangan klik <strong>"Selesaikan Pesanan"</strong>. Gunakan fitur <strong>"Komplain/Dispute"</strong> pada detail transaksi.</span>
                </li>
                <li class="flex gap-4">
                    <span class="w-6 h-6 rounded-full bg-blue-500/20 text-blue-400 flex items-center justify-center text-xs shrink-0 mt-1">B</span>
                    <span>Sertakan bukti pendukung yang jelas (Screenshot kendala atau video unboxing untuk data akun).</span>
                </li>
                <li class="flex gap-4">
                    <span class="w-6 h-6 rounded-full bg-blue-500/20 text-blue-400 flex items-center justify-center text-xs shrink-0 mt-1">C</span>
                    <span>Tim mediator Lapak Gaming akan meninjau laporan Anda dalam waktu maksimal 1x24 jam.</span>
                </li>
            </ul>
        </div>

        {{-- Section 3: Pengembalian Dana --}}
        <div class="group relative bg-emerald-900/10 backdrop-blur-sm border border-emerald-500/30 rounded-2xl p-6 md:p-8 hover:border-emerald-500/60 hover:shadow-[0_8px_30px_rgba(16,185,129,0.15)] hover:-translate-y-1 transition-all duration-300 overflow-hidden">
            <div class="absolute left-0 top-0 bottom-0 w-1.5 bg-emerald-500/50 group-hover:bg-emerald-500 shadow-[0_0_10px_rgba(16,185,129,0.5)] transition-colors duration-300"></div>
            
            <h2 class="text-xl md:text-2xl font-display font-bold text-white mb-5 flex items-center gap-4">
                <span class="flex items-center justify-center w-10 h-10 rounded-xl bg-emerald-500/20 text-emerald-400 font-bold text-lg group-hover:scale-110 group-hover:bg-emerald-500/30 transition-all duration-300">3</span>
                Metode Pengembalian Dana
            </h2>
            <p class="text-slate-300 text-sm md:text-base leading-relaxed ml-2">
                Jika refund disetujui, dana akan dikembalikan melalui:
            </p>
            <ul class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-3 ml-2">
                <li class="bg-gray-900/50 p-3 rounded-xl border border-gray-800 text-sm text-slate-300 flex items-center gap-3">
                    <span class="text-emerald-400 text-xl">💳</span> <strong>Dompet/Wallet:</strong> Instan (0-1 jam)
                </li>
                <li class="bg-gray-900/50 p-3 rounded-xl border border-gray-800 text-sm text-slate-300 flex items-center gap-3">
                    <span class="text-emerald-400 text-xl">🏦</span> <strong>Transfer Bank:</strong> 1-3 hari kerja
                </li>
            </ul>
        </div>

    </div>

    {{-- Penutup/Catatan --}}
    <div class="mt-12 p-8 bg-[#0D1421] border border-[#1E2D45] rounded-3xl text-center relative overflow-hidden group hover:border-rose-500/50 transition-colors duration-500">
        <div class="absolute -top-24 -right-24 w-48 h-48 bg-rose-600/10 rounded-full blur-2xl group-hover:bg-rose-600/20 transition-all duration-500"></div>
        
        <svg class="w-10 h-10 text-rose-500 mx-auto mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        <p class="text-sm md:text-base text-slate-300 leading-relaxed max-w-2xl mx-auto relative z-10">
            Lapak Gaming berhak menolak pengajuan refund jika ditemukan bukti adanya manipulasi, penipuan, atau jika pembeli telah mengonfirmasi pesanan selesai tanpa pengecekan terlebih dahulu.
        </p>
    </div>
</div>
@endsection
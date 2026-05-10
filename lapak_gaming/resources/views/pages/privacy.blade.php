@extends('layouts.app')

@section('title', 'Kebijakan Privasi — Lapak Gaming')

@section('content')
<div class="max-w-4xl mx-auto px-4 py-12 md:py-20 animate-fade-in relative z-10">
    
    {{-- Background Glow Dekoratif --}}
    <div class="absolute top-10 left-1/2 -translate-x-1/2 w-full max-w-2xl h-64 bg-blue-600/10 blur-[100px] rounded-full pointer-events-none -z-10"></div>

    {{-- Header --}}
    <div class="text-center mb-16 border-b border-gray-800/50 pb-10 relative">
        <h1 class="text-4xl md:text-5xl font-display font-extrabold text-white mb-6 tracking-tight">
            Kebijakan <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-400 to-emerald-500">Privasi</span>
        </h1>
        <div class="inline-flex items-center gap-3 bg-gray-900/80 backdrop-blur-sm px-5 py-2.5 rounded-full border border-gray-800 shadow-lg">
            <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 animate-pulse"></span>
            <span class="text-slate-300 text-sm font-medium">Pembaruan Terakhir: 10 Mei 2026</span>
        </div>
    </div>

    {{-- Konten Pembuka --}}
    <div class="bg-gray-900/40 border border-gray-700/50 rounded-2xl p-6 md:p-8 mb-10">
        <p class="text-slate-300 leading-relaxed text-sm md:text-base">
            Lapak Gaming menghargai privasi Anda dan berkomitmen untuk melindungi data pribadi Anda. Kebijakan Privasi ini menjelaskan bagaimana kami mengumpulkan, menggunakan, dan menjaga informasi Anda saat Anda menggunakan situs <strong>lapakgaming.neoverse.my.id</strong>.
        </p>
    </div>

    {{-- Daftar Kebijakan (Full Highlighted Cards) --}}
    <div class="space-y-6">
        
        {{-- Section 1: Data yang Dikumpulkan --}}
        <div class="group relative bg-blue-900/10 backdrop-blur-sm border border-blue-500/30 rounded-2xl p-6 md:p-8 hover:border-blue-500/60 hover:shadow-[0_8px_30px_rgba(59,130,246,0.15)] hover:-translate-y-1 transition-all duration-300 overflow-hidden">
            <div class="absolute left-0 top-0 bottom-0 w-1.5 bg-blue-500/50 group-hover:bg-blue-500 shadow-[0_0_10px_rgba(59,130,246,0.5)] transition-colors duration-300"></div>
            
            <h2 class="text-xl md:text-2xl font-display font-bold text-white mb-5 flex items-center gap-4">
                <span class="flex items-center justify-center w-10 h-10 rounded-xl bg-blue-500/20 text-blue-400 font-bold text-lg group-hover:scale-110 group-hover:bg-blue-500/30 transition-all duration-300">1</span>
                Informasi yang Kami Kumpulkan
            </h2>
            <ul class="space-y-3 text-slate-300 text-sm md:text-base leading-relaxed ml-2">
                <li class="flex gap-3"><span class="text-blue-500 mt-0.5 shrink-0">▹</span> <strong>Identitas Pribadi:</strong> Nama, alamat email, nomor telepon, dan foto profil.</li>
                <li class="flex gap-3"><span class="text-blue-500 mt-0.5 shrink-0">▹</span> <strong>Data Transaksi:</strong> Detail mengenai pembayaran, produk yang dibeli, dan riwayat pesanan.</li>
                <li class="flex gap-3"><span class="text-blue-500 mt-0.5 shrink-0">▹</span> <strong>Data Teknis:</strong> Alamat IP, jenis perangkat, sistem operasi, dan aktivitas penjelajahan di situs kami.</li>
            </ul>
        </div>

        {{-- Section 2: Penggunaan Informasi --}}
        <div class="group relative bg-emerald-900/10 backdrop-blur-sm border border-emerald-500/30 rounded-2xl p-6 md:p-8 hover:border-emerald-500/60 hover:shadow-[0_8px_30px_rgba(16,185,129,0.15)] hover:-translate-y-1 transition-all duration-300 overflow-hidden">
            <div class="absolute left-0 top-0 bottom-0 w-1.5 bg-emerald-500/50 group-hover:bg-emerald-500 shadow-[0_0_10px_rgba(16,185,129,0.5)] transition-colors duration-300"></div>
            
            <h2 class="text-xl md:text-2xl font-display font-bold text-white mb-5 flex items-center gap-4">
                <span class="flex items-center justify-center w-10 h-10 rounded-xl bg-emerald-500/20 text-emerald-400 font-bold text-lg group-hover:scale-110 group-hover:bg-emerald-500/30 transition-all duration-300">2</span>
                Bagaimana Kami Menggunakan Data Anda
            </h2>
            <ul class="space-y-3 text-slate-300 text-sm md:text-base leading-relaxed ml-2">
                <li class="flex gap-3"><span class="text-emerald-400 mt-0.5 shrink-0">▹</span> Memproses transaksi pembelian produk game dan voucher Anda.</li>
                <li class="flex gap-3"><span class="text-emerald-400 mt-0.5 shrink-0">▹</span> Memberikan layanan dukungan pelanggan dan menyelesaikan kendala teknis.</li>
                <li class="flex gap-3"><span class="text-emerald-400 mt-0.5 shrink-0">▹</span> Mengirimkan notifikasi terkait pesanan, pembaruan layanan, dan promo menarik.</li>
                <li class="flex gap-3"><span class="text-emerald-400 mt-0.5 shrink-0">▹</span> Mencegah aktivitas ilegal, penipuan, dan penyalahgunaan akun.</li>
            </ul>
        </div>

        {{-- Section 3: Keamanan Data --}}
        <div class="group relative bg-orange-900/10 backdrop-blur-sm border border-orange-500/30 rounded-2xl p-6 md:p-8 hover:border-orange-500/60 hover:shadow-[0_8px_30px_rgba(249,115,22,0.15)] hover:-translate-y-1 transition-all duration-300 overflow-hidden">
            <div class="absolute left-0 top-0 bottom-0 w-1.5 bg-orange-500/50 group-hover:bg-orange-500 shadow-[0_0_10px_rgba(249,115,22,0.5)] transition-colors duration-300"></div>
            
            <h2 class="text-xl md:text-2xl font-display font-bold text-white mb-5 flex items-center gap-4">
                <span class="flex items-center justify-center w-10 h-10 rounded-xl bg-orange-500/20 text-orange-400 font-bold text-lg group-hover:scale-110 group-hover:bg-orange-500/30 transition-all duration-300">3</span>
                Keamanan Informasi Anda
            </h2>
            <p class="text-slate-300 text-sm md:text-base leading-relaxed ml-2">
                Kami menerapkan langkah-langkah keamanan teknis yang ketat untuk melindungi data Anda dari akses yang tidak sah. Informasi sensitif (seperti password) disimpan menggunakan enkripsi satu arah yang tidak dapat dibaca oleh staf kami sekalipun.
            </p>
        </div>

        {{-- Section 4: Pembagian Data --}}
        <div class="group relative bg-purple-900/10 backdrop-blur-sm border border-purple-500/30 rounded-2xl p-6 md:p-8 hover:border-purple-500/60 hover:shadow-[0_8px_30px_rgba(168,85,247,0.15)] hover:-translate-y-1 transition-all duration-300 overflow-hidden">
            <div class="absolute left-0 top-0 bottom-0 w-1.5 bg-purple-500/50 group-hover:bg-purple-500 shadow-[0_0_10px_rgba(168,85,247,0.5)] transition-colors duration-300"></div>
            
            <h2 class="text-xl md:text-2xl font-display font-bold text-white mb-5 flex items-center gap-4">
                <span class="flex items-center justify-center w-10 h-10 rounded-xl bg-purple-500/20 text-purple-400 font-bold text-lg group-hover:scale-110 group-hover:bg-purple-500/30 transition-all duration-300">4</span>
                Berbagi Informasi dengan Pihak Ketiga
            </h2>
            <p class="text-slate-300 text-sm md:text-base leading-relaxed ml-2">
                Kami tidak akan menjual atau menyewakan data pribadi Anda kepada pihak manapun. Kami hanya membagikan data kepada mitra layanan kami (seperti Payment Gateway) untuk tujuan memproses pembayaran Anda secara sah.
            </p>
        </div>

        {{-- Section 5: Hak Pengguna --}}
        <div class="group relative bg-cyan-900/10 backdrop-blur-sm border border-cyan-500/30 rounded-2xl p-6 md:p-8 hover:border-cyan-500/60 hover:shadow-[0_8px_30px_rgba(6,182,212,0.15)] hover:-translate-y-1 transition-all duration-300 overflow-hidden">
            <div class="absolute left-0 top-0 bottom-0 w-1.5 bg-cyan-500/50 group-hover:bg-cyan-500 shadow-[0_0_10px_rgba(6,182,212,0.5)] transition-colors duration-300"></div>
            
            <h2 class="text-xl md:text-2xl font-display font-bold text-white mb-5 flex items-center gap-4">
                <span class="flex items-center justify-center w-10 h-10 rounded-xl bg-cyan-500/20 text-cyan-400 font-bold text-lg group-hover:scale-110 group-hover:bg-cyan-500/30 transition-all duration-300">5</span>
                Hak Anda atas Informasi
            </h2>
            <ul class="space-y-3 text-slate-300 text-sm md:text-base leading-relaxed ml-2">
                <li class="flex gap-3"><span class="text-cyan-400 mt-0.5 shrink-0">▹</span> Hak untuk mengakses dan memperbarui informasi profil Anda kapan saja.</li>
                <li class="flex gap-3"><span class="text-cyan-400 mt-0.5 shrink-0">▹</span> Hak untuk meminta penghapusan akun dan data pribadi terkait.</li>
                <li class="flex gap-3"><span class="text-cyan-400 mt-0.5 shrink-0">▹</span> Hak untuk menolak menerima komunikasi pemasaran/promo.</li>
            </ul>
        </div>

    </div>

    {{-- Penutup --}}
    <div class="mt-12 p-8 bg-[#0D1421] border border-[#1E2D45] rounded-3xl text-center relative overflow-hidden group hover:border-emerald-500/50 transition-colors duration-500">
        <div class="absolute -top-24 -right-24 w-48 h-48 bg-emerald-600/10 rounded-full blur-2xl group-hover:bg-emerald-600/20 transition-all duration-500"></div>
        
        <svg class="w-10 h-10 text-emerald-500 mx-auto mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
        </svg>
        <p class="text-sm md:text-base text-slate-300 leading-relaxed max-w-2xl mx-auto relative z-10">
            Dengan terus menggunakan layanan kami, Anda dianggap menyetujui Kebijakan Privasi ini. Jika kami melakukan perubahan signifikan, kami akan memberitahukannya melalui situs atau email Anda.
        </p>
    </div>
</div>
@endsection
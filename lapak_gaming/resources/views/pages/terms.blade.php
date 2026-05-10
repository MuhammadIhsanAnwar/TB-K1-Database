@extends('layouts.app')

@section('title', 'Aturan Penggunaan — Lapak Gaming')

@section('content')
{{-- Efek fade-in saat halaman dibuka --}}
<div class="max-w-4xl mx-auto px-4 py-12 md:py-20 animate-fade-in relative z-10">
    
    {{-- Background Glow Dekoratif (Warna Ungu/Biru) --}}
    <div class="absolute top-10 left-1/2 -translate-x-1/2 w-full max-w-2xl h-64 bg-purple-600/10 blur-[100px] rounded-full pointer-events-none -z-10"></div>

    {{-- Header --}}
    <div class="text-center mb-16 border-b border-gray-800/50 pb-10 relative">
        <h1 class="text-4xl md:text-5xl font-display font-extrabold text-white mb-6 tracking-tight">
            Aturan <span class="text-transparent bg-clip-text bg-gradient-to-r from-purple-400 to-blue-500">Penggunaan</span>
        </h1>
        <div class="inline-flex items-center gap-3 bg-gray-900/80 backdrop-blur-sm px-5 py-2.5 rounded-full border border-gray-800 shadow-lg">
            <span class="w-2.5 h-2.5 rounded-full bg-blue-500 animate-pulse"></span>
            <span class="text-slate-300 text-sm font-medium">Pembaruan Terakhir: 10 Mei 2026</span>
        </div>
    </div>

    {{-- Konten Pembuka --}}
    <div class="bg-blue-500/5 border border-blue-500/20 rounded-2xl p-6 md:p-8 mb-10 text-center relative overflow-hidden group">
        <div class="absolute inset-0 bg-gradient-to-r from-blue-600/0 via-blue-600/5 to-blue-600/0 opacity-0 group-hover:opacity-100 group-hover:translate-x-full transition-all duration-1000 ease-out"></div>
        <p class="text-slate-300 leading-relaxed text-sm md:text-base relative z-10">
            Selamat datang di Lapak Gaming. Syarat & ketentuan yang ditetapkan di bawah ini mengatur pemakaian jasa yang ditawarkan oleh PT Lapak Gaming Indonesia terkait penggunaan situs <strong class="text-white font-display tracking-wide">lapakgaming.neoverse.my.id</strong>. Pengguna disarankan membaca dengan saksama karena dapat berdampak kepada hak dan kewajiban Pengguna di bawah hukum.
        </p>
    </div>

    {{-- Daftar Aturan (Dibuat model Card Interaktif) --}}
    <div class="space-y-6">
        
        {{-- Section 1 --}}
        <div class="group bg-gray-925/80 backdrop-blur-sm border border-gray-800 rounded-2xl p-6 md:p-8 hover:border-blue-500/40 hover:shadow-[0_8px_30px_rgba(59,130,246,0.1)] hover:-translate-y-1 transition-all duration-300">
            <h2 class="text-xl md:text-2xl font-display font-bold text-white mb-5 flex items-center gap-4">
                <span class="flex items-center justify-center w-10 h-10 rounded-xl bg-blue-500/10 text-blue-400 font-bold text-lg group-hover:scale-110 group-hover:bg-blue-500/20 group-hover:shadow-[0_0_15px_rgba(59,130,246,0.4)] transition-all duration-300">
                    1
                </span>
                Akun, Password, dan Keamanan
            </h2>
            <ul class="space-y-3 text-slate-400 text-sm md:text-base leading-relaxed ml-2">
                <li class="flex gap-3"><span class="text-blue-500 mt-0.5 shrink-0">▹</span> Pengguna dengan ini menyatakan bahwa pengguna adalah orang yang cakap dan mampu untuk mengikatkan dirinya dalam sebuah perjanjian yang sah menurut hukum.</li>
                <li class="flex gap-3"><span class="text-blue-500 mt-0.5 shrink-0">▹</span> Lapak Gaming tidak memungut biaya pendaftaran kepada Pengguna.</li>
                <li class="flex gap-3"><span class="text-blue-500 mt-0.5 shrink-0">▹</span> Pengguna bertanggung jawab secara pribadi untuk menjaga kerahasiaan akun dan password untuk semua aktivitas yang terjadi dalam akun Pengguna.</li>
                <li class="flex gap-3"><span class="text-blue-500 mt-0.5 shrink-0">▹</span> Lapak Gaming tidak akan meminta password akun Pengguna untuk alasan apapun, oleh karena itu Lapak Gaming menghimbau Pengguna agar tidak memberikan password akun Anda kepada pihak manapun.</li>
            </ul>
        </div>

        {{-- Section 2 --}}
        <div class="group bg-gray-925/80 backdrop-blur-sm border border-gray-800 rounded-2xl p-6 md:p-8 hover:border-orange-500/40 hover:shadow-[0_8px_30px_rgba(249,115,22,0.1)] hover:-translate-y-1 transition-all duration-300">
            <h2 class="text-xl md:text-2xl font-display font-bold text-white mb-5 flex items-center gap-4">
                <span class="flex items-center justify-center w-10 h-10 rounded-xl bg-orange-500/10 text-orange-400 font-bold text-lg group-hover:scale-110 group-hover:bg-orange-500/20 group-hover:shadow-[0_0_15px_rgba(249,115,22,0.4)] transition-all duration-300">
                    2
                </span>
                Transaksi Pembelian
            </h2>
            <ul class="space-y-3 text-slate-400 text-sm md:text-base leading-relaxed ml-2">
                <li class="flex gap-3"><span class="text-orange-500 mt-0.5 shrink-0">▹</span> Pembeli wajib bertransaksi melalui prosedur transaksi yang telah ditetapkan oleh Lapak Gaming. Pembeli melakukan pembayaran dengan menggunakan metode pembayaran yang sebelumnya telah dipilih oleh Pembeli.</li>
                <li class="flex gap-3"><span class="text-orange-500 mt-0.5 shrink-0">▹</span> Saat melakukan pembelian Produk, Pembeli menyetujui bahwa Pembeli bertanggung jawab untuk membaca, memahami, dan menyetujui informasi/deskripsi keseluruhan Produk sebelum membuat komitmen untuk membeli.</li>
                <li class="flex gap-3"><span class="text-orange-500 mt-0.5 shrink-0">▹</span> Semua transaksi menggunakan sistem Escrow (Rekening Bersama). Dana akan diteruskan ke Penjual hanya setelah Pembeli mengonfirmasi pesanan telah diterima dengan baik.</li>
            </ul>
        </div>

        {{-- Section 3 --}}
        <div class="group bg-gray-925/80 backdrop-blur-sm border border-gray-800 rounded-2xl p-6 md:p-8 hover:border-purple-500/40 hover:shadow-[0_8px_30px_rgba(168,85,247,0.1)] hover:-translate-y-1 transition-all duration-300">
            <h2 class="text-xl md:text-2xl font-display font-bold text-white mb-5 flex items-center gap-4">
                <span class="flex items-center justify-center w-10 h-10 rounded-xl bg-purple-500/10 text-purple-400 font-bold text-lg group-hover:scale-110 group-hover:bg-purple-500/20 group-hover:shadow-[0_0_15px_rgba(168,85,247,0.4)] transition-all duration-300">
                    3
                </span>
                Transaksi Penjualan
            </h2>
            <ul class="space-y-3 text-slate-400 text-sm md:text-base leading-relaxed ml-2">
                <li class="flex gap-3"><span class="text-purple-500 mt-0.5 shrink-0">▹</span> Penjual dilarang memanipulasi harga Produk dengan tujuan apapun.</li>
                <li class="flex gap-3"><span class="text-purple-500 mt-0.5 shrink-0">▹</span> Penjual wajib memberikan keterangan yang lengkap, jelas, dan sesuai dengan produk yang ditawarkan (misalnya detail akun, status legalitas diamond/voucher).</li>
                <li class="flex gap-3"><span class="text-purple-500 mt-0.5 shrink-0">▹</span> Penjual wajib menyerahkan produk sesuai dengan spesifikasi yang disepakati maksimal dalam batas waktu pengiriman yang telah ditentukan sistem.</li>
            </ul>
        </div>

        {{-- Section 4 (Highlight Merah karena Terlarang) --}}
        <div class="group bg-gray-925/80 backdrop-blur-sm border border-gray-800 rounded-2xl p-6 md:p-8 hover:border-red-500/40 hover:shadow-[0_8px_30px_rgba(239,68,68,0.1)] hover:-translate-y-1 transition-all duration-300 relative overflow-hidden">
            {{-- Aksen Garis Merah di samping --}}
            <div class="absolute left-0 top-0 bottom-0 w-1 bg-red-500/50"></div>
            
            <h2 class="text-xl md:text-2xl font-display font-bold text-white mb-5 flex items-center gap-4">
                <span class="flex items-center justify-center w-10 h-10 rounded-xl bg-red-500/10 text-red-400 font-bold text-lg group-hover:scale-110 group-hover:bg-red-500/20 group-hover:shadow-[0_0_15px_rgba(239,68,68,0.4)] transition-all duration-300">
                    4
                </span>
                Barang dan Jasa Terlarang
            </h2>
            <p class="text-slate-300 text-sm md:text-base mb-4 ml-2">Lapak Gaming dengan tegas melarang penjualan produk/jasa berikut:</p>
            <ul class="space-y-3 text-slate-400 text-sm md:text-base leading-relaxed ml-2">
                <li class="flex gap-3"><span class="text-red-500 mt-0.5 shrink-0">✕</span> Aplikasi atau tools ilegal (Cheat, Hack, Bot, Mod).</li>
                <li class="flex gap-3"><span class="text-red-500 mt-0.5 shrink-0">✕</span> Produk hasil pencurian (Carding, Phishing, Scamming).</li>
                <li class="flex gap-3"><span class="text-red-500 mt-0.5 shrink-0">✕</span> Data pribadi pihak ketiga atau informasi finansial.</li>
            </ul>
        </div>

        {{-- Section 5 --}}
        <div class="group bg-gray-925/80 backdrop-blur-sm border border-gray-800 rounded-2xl p-6 md:p-8 hover:border-green-500/40 hover:shadow-[0_8px_30px_rgba(34,197,94,0.1)] hover:-translate-y-1 transition-all duration-300">
            <h2 class="text-xl md:text-2xl font-display font-bold text-white mb-5 flex items-center gap-4">
                <span class="flex items-center justify-center w-10 h-10 rounded-xl bg-green-500/10 text-green-400 font-bold text-lg group-hover:scale-110 group-hover:bg-green-500/20 group-hover:shadow-[0_0_15px_rgba(34,197,94,0.4)] transition-all duration-300">
                    5
                </span>
                Penyelesaian Sengketa (Dispute)
            </h2>
            <ul class="space-y-3 text-slate-400 text-sm md:text-base leading-relaxed ml-2">
                <li class="flex gap-3"><span class="text-green-500 mt-0.5 shrink-0">▹</span> Apabila terjadi kendala pesanan (misal: produk tidak sesuai atau tidak dikirim), Pembeli dapat menekan tombol "Komplain" sebelum masa garansi habis.</li>
                <li class="flex gap-3"><span class="text-green-500 mt-0.5 shrink-0">▹</span> Tim Resolusi Lapak Gaming akan menjadi penengah yang adil dengan meninjau bukti dari kedua belah pihak.</li>
                <li class="flex gap-3"><span class="text-green-500 mt-0.5 shrink-0">▹</span> Keputusan Tim Resolusi Lapak Gaming bersifat final dan mengikat baik bagi Pembeli maupun Penjual.</li>
            </ul>
        </div>

    </div>

    {{-- Penutup --}}
    <div class="mt-12 p-8 bg-[#0D1421] border border-[#1E2D45] rounded-3xl text-center relative overflow-hidden group hover:border-blue-500/50 transition-colors duration-500">
        {{-- Efek Radar --}}
        <div class="absolute -top-24 -right-24 w-48 h-48 bg-blue-600/10 rounded-full blur-2xl group-hover:bg-blue-600/20 transition-all duration-500"></div>
        
        <svg class="w-10 h-10 text-blue-500 mx-auto mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
        </svg>
        <p class="text-sm md:text-base text-slate-300 leading-relaxed max-w-2xl mx-auto relative z-10">
            Dengan mendaftar dan/atau menggunakan situs Lapak Gaming, maka pengguna dianggap telah membaca, mengerti, memahami dan menyetujui semua isi dalam Syarat & Ketentuan ini.
        </p>
    </div>
</div>
@endsection
@extends('layouts.app')

@section('title', 'Hubungi Kami — Lapak Gaming')

@section('content')
{{-- Tambahkan animate-fade-in dari konfigurasi Tailwind kamu --}}
<div class="max-w-5xl mx-auto px-4 py-12 md:py-20 animate-fade-in relative z-10">
    
    {{-- Background Glow Dekoratif --}}
    <div class="absolute top-0 left-1/2 -translate-x-1/2 w-full max-w-2xl h-64 bg-blue-600/10 blur-[100px] rounded-full pointer-events-none -z-10"></div>

    {{-- Header --}}
    <div class="text-center mb-16">
        <h1 class="text-4xl md:text-5xl font-display font-extrabold text-white mb-4 tracking-tight">
            Hubungi <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-400 to-purple-500">Kami</span>
        </h1>
        <p class="text-slate-400 text-sm md:text-base max-w-xl mx-auto leading-relaxed">
            Punya pertanyaan seputar transaksi, top-up, atau akun? Tim support Lapak Gaming siap membantu Anda 24/7.
        </p>
    </div>

    <div class="grid lg:grid-cols-5 gap-8 lg:gap-12">
        
        {{-- Kolom Kiri: Informasi Kontak (Porsi 2 kolom) --}}
        <div class="lg:col-span-2 space-y-5">
            
            {{-- Kartu Email --}}
            <div class="group bg-gray-925/80 backdrop-blur-sm border border-gray-800 rounded-2xl p-6 hover:border-blue-500 hover:shadow-[0_8px_30px_rgba(59,130,246,0.15)] hover:-translate-y-1.5 transition-all duration-300 cursor-pointer card-glow">
                <div class="w-12 h-12 bg-blue-500/10 rounded-xl flex items-center justify-center mb-5 group-hover:bg-blue-500/20 transition-colors duration-300">
                    {{-- Ikon membesar dan miring saat di-hover --}}
                    <svg class="w-6 h-6 text-blue-400 group-hover:scale-110 group-hover:rotate-6 transition-transform duration-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                </div>
                <h3 class="text-lg font-display font-bold text-white mb-1">Email Support</h3>
                <p class="text-slate-400 text-sm mb-4 line-clamp-2">Untuk bantuan kendala transaksi & pelaporan akun.</p>
                <a href="mailto:support@lapakgaming.com" class="text-blue-400 font-semibold text-sm group-hover:text-blue-300 flex items-center gap-2">
                    support@lapakgaming.com 
                    <span class="opacity-0 group-hover:opacity-100 group-hover:translate-x-1 transition-all duration-300">→</span>
                </a>
            </div>

            {{-- Kartu WhatsApp --}}
            <div class="group bg-gray-925/80 backdrop-blur-sm border border-gray-800 rounded-2xl p-6 hover:border-green-500 hover:shadow-[0_8px_30px_rgba(34,197,94,0.15)] hover:-translate-y-1.5 transition-all duration-300 cursor-pointer card-glow">
                <div class="w-12 h-12 bg-green-500/10 rounded-xl flex items-center justify-center mb-5 group-hover:bg-green-500/20 transition-colors duration-300">
                    <svg class="w-6 h-6 text-green-400 group-hover:scale-110 group-hover:-rotate-6 transition-transform duration-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z" />
                    </svg>
                </div>
                <h3 class="text-lg font-display font-bold text-white mb-1">WhatsApp CS</h3>
                <p class="text-slate-400 text-sm mb-4 line-clamp-2">Respon super cepat. (Jam Kerja: 08:00 - 22:00 WIB).</p>
                <a href="#" class="text-green-400 font-semibold text-sm group-hover:text-green-300 flex items-center gap-2">
                    +62 812-3456-7890
                    <span class="opacity-0 group-hover:opacity-100 group-hover:translate-x-1 transition-all duration-300">→</span>
                </a>
            </div>
            
            {{-- Kartu Kerjasama --}}
            <div class="group bg-gray-925/80 backdrop-blur-sm border border-gray-800 rounded-2xl p-6 hover:border-purple-500 hover:shadow-[0_8px_30px_rgba(168,85,247,0.15)] hover:-translate-y-1.5 transition-all duration-300 cursor-pointer card-glow">
                <div class="w-12 h-12 bg-purple-500/10 rounded-xl flex items-center justify-center mb-5 group-hover:bg-purple-500/20 transition-colors duration-300">
                    <svg class="w-6 h-6 text-purple-400 group-hover:scale-110 group-hover:rotate-6 transition-transform duration-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                </div>
                <h3 class="text-lg font-display font-bold text-white mb-1">Kerjasama & Bisnis</h3>
                <p class="text-slate-400 text-sm mb-4 line-clamp-2">Penawaran kemitraan, media, atau kerja sama B2B.</p>
                <a href="mailto:business@lapakgaming.com" class="text-purple-400 font-semibold text-sm group-hover:text-purple-300 flex items-center gap-2">
                    business@lapakgaming.com
                    <span class="opacity-0 group-hover:opacity-100 group-hover:translate-x-1 transition-all duration-300">→</span>
                </a>
            </div>
        </div>

        {{-- Kolom Kanan: Form (Porsi 3 kolom) --}}
        <div class="lg:col-span-3">
            <div class="bg-gray-925 border border-gray-800 rounded-3xl p-6 md:p-10 shadow-2xl relative overflow-hidden">
                
                {{-- Aksen Sudut --}}
                <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-bl from-blue-600/20 to-transparent rounded-bl-full pointer-events-none"></div>

                <h2 class="text-2xl font-display font-bold text-white mb-8 flex items-center gap-3">
                    <span class="text-xl">✉️</span> Kirim Pesan Langsung
                </h2>
                
                <form action="#" method="POST" class="space-y-5 relative z-10">
                    @csrf
                    <div class="grid md:grid-cols-2 gap-5">
                        <div class="group">
                            <label class="block text-sm font-medium text-slate-400 mb-2 group-focus-within:text-blue-400 transition-colors">Nama Lengkap</label>
                            <input type="text" placeholder="John Doe" class="w-full bg-gray-900 border border-gray-800 text-white text-sm rounded-xl px-4 py-3.5 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-all hover:border-gray-700">
                        </div>
                        
                        <div class="group">
                            <label class="block text-sm font-medium text-slate-400 mb-2 group-focus-within:text-blue-400 transition-colors">Email Aktif</label>
                            <input type="email" placeholder="john@example.com" class="w-full bg-gray-900 border border-gray-800 text-white text-sm rounded-xl px-4 py-3.5 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-all hover:border-gray-700">
                        </div>
                    </div>
                    
                    <div class="group">
                        <label class="block text-sm font-medium text-slate-400 mb-2 group-focus-within:text-blue-400 transition-colors">Kategori Kendala</label>
                        {{-- Select box dengan styling yang lebih baik --}}
                        <select class="w-full bg-gray-900 border border-gray-800 text-slate-300 text-sm rounded-xl px-4 py-3.5 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-all hover:border-gray-700 appearance-none cursor-pointer" style="background-image: url('data:image/svg+xml;charset=US-ASCII,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20width%3D%22292.4%22%20height%3D%22292.4%22%3E%3Cpath%20fill%3D%22%2394a3b8%22%20d%3D%22M287%2069.4a17.6%2017.6%200%200%200-13-5.4H18.4c-5%200-9.3%201.8-12.9%205.4A17.6%2017.6%200%200%200%200%2082.2c0%205%201.8%209.3%205.4%2012.9l128%20127.9c3.6%203.6%207.8%205.4%2012.8%205.4s9.2-1.8%2012.8-5.4L287%2095c3.5-3.5%205.4-7.8%205.4-12.8%200-5-1.9-9.2-5.5-12.8z%22%2F%3E%3C%2Fsvg%3E'); background-repeat: no-repeat; background-position: right 1rem top 50%; background-size: 0.65rem auto;">
                            <option>Pilih Kategori</option>
                            <option>Top Up & Pembayaran</option>
                            <option>Akun Tertahan / Banned</option>
                            <option>Laporan Penipuan (Fraud)</option>
                            <option>Pertanyaan Umum</option>
                        </select>
                    </div>

                    <div class="group">
                        <label class="block text-sm font-medium text-slate-400 mb-2 group-focus-within:text-blue-400 transition-colors">Detail Pesan</label>
                        <textarea rows="5" placeholder="Jelaskan detail kendala Anda secara lengkap..." class="w-full bg-gray-900 border border-gray-800 text-white text-sm rounded-xl px-4 py-3.5 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-all hover:border-gray-700 resize-none"></textarea>
                    </div>

                    <button type="button" onclick="alert('Pesan dummy terkirim! Form ini masih dalam tahap desain.')" class="w-full relative inline-flex items-center justify-center px-8 py-4 font-bold text-white transition-all duration-300 bg-blue-600 rounded-xl hover:bg-blue-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-600 focus:ring-offset-gray-900 overflow-hidden group/btn mt-4">
                        {{-- Efek kilap pada tombol --}}
                        <span class="absolute w-0 h-0 transition-all duration-500 ease-out bg-white rounded-full group-hover/btn:w-56 group-hover/btn:h-56 opacity-10"></span>
                        <span class="relative flex items-center gap-2">
                            Kirim Pesan Sekarang
                            <svg class="w-4 h-4 group-hover/btn:translate-x-1 group-hover/btn:-translate-y-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                        </span>
                    </button>
                </form>
            </div>
        </div>

    </div>
</div>
@endsection
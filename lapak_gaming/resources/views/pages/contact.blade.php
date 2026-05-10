@extends('layouts.app')

@section('title', 'Hubungi Kami — Lapak Gaming')

@section('content')
<div class="max-w-5xl mx-auto px-4 py-12 md:py-20 animate-fade-in relative z-10">
    
    {{-- Background Glow Dekoratif --}}
    <div class="absolute top-0 left-1/2 -translate-x-1/2 w-full max-w-2xl h-64 bg-blue-600/10 blur-[100px] rounded-full pointer-events-none -z-10"></div>

    {{-- Header --}}
    <div class="text-center mb-16">
        <h1 class="text-4xl md:text-5xl font-display font-extrabold text-white mb-4 tracking-tight">
            Hubungi <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-400 to-purple-500">Kami</span>
        </h1>
        <p class="text-slate-400 text-sm md:text-base max-w-xl mx-auto leading-relaxed">
            Punya pertanyaan seputar transaksi, top-up, atau akun? Tim support Lapak Gaming siap membantu Anda!
        </p>
    </div>

    <div class="grid lg:grid-cols-5 gap-8 lg:gap-12">
        
        {{-- Kolom Kiri: Informasi (Porsi 2 kolom) --}}
        <div class="lg:col-span-2 space-y-5">
            
            {{-- Kartu Email (Utama) --}}
            <div class="group bg-blue-900/10 backdrop-blur-sm border border-blue-500/30 rounded-2xl p-6 hover:border-blue-500/60 hover:shadow-[0_8px_30px_rgba(59,130,246,0.15)] hover:-translate-y-1 transition-all duration-300 cursor-pointer overflow-hidden relative">
                <div class="absolute left-0 top-0 bottom-0 w-1.5 bg-blue-500/50 group-hover:bg-blue-500 shadow-[0_0_10px_rgba(59,130,246,0.5)] transition-colors duration-300"></div>
                
                <div class="w-12 h-12 bg-blue-500/20 rounded-xl flex items-center justify-center mb-5 group-hover:scale-110 group-hover:bg-blue-500/30 transition-all duration-300 ml-2">
                    <svg class="w-6 h-6 text-blue-400 group-hover:-rotate-6 transition-transform duration-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                </div>
                <h3 class="text-lg font-display font-bold text-white mb-1 ml-2">Email Support</h3>
                <p class="text-slate-400 text-sm mb-4 ml-2">Satu-satunya jalur resmi untuk bantuan kendala transaksi & pelaporan akun.</p>
                <a href="mailto:administrator@lapakgaming.neoverse.my.id" class="text-blue-400 font-semibold text-sm group-hover:text-blue-300 flex items-center gap-2 ml-2">
                    administrator@lapakgaming.neoverse.my.id
                    <span class="opacity-0 group-hover:opacity-100 group-hover:translate-x-1 transition-all duration-300">→</span>
                </a>
            </div>

            {{-- Kartu Jam Operasional (Pengganti ruang kosong) --}}
            <div class="group bg-gray-925/80 backdrop-blur-sm border border-gray-800 rounded-2xl p-6 hover:border-orange-500/40 hover:-translate-y-1 transition-all duration-300">
                <div class="flex items-start gap-4">
                    <div class="w-10 h-10 bg-orange-500/10 rounded-xl flex items-center justify-center shrink-0 group-hover:bg-orange-500/20 transition-colors duration-300">
                        <svg class="w-5 h-5 text-orange-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-display font-bold text-white text-sm mb-1">Jam Operasional</h3>
                        <p class="text-slate-400 text-xs leading-relaxed">
                            <strong class="text-slate-300">Senin - Minggu: 08:00 - 22:00 WIB</strong><br>
                            Pesan yang masuk di luar jam kerja akan dibalas pada hari berikutnya.
                        </p>
                    </div>
                </div>
            </div>

            {{-- Kartu FAQ (Pengganti ruang kosong) --}}
            <div class="group bg-gray-925/80 backdrop-blur-sm border border-gray-800 rounded-2xl p-6 hover:border-emerald-500/40 hover:-translate-y-1 transition-all duration-300">
                <div class="flex items-start gap-4">
                    <div class="w-10 h-10 bg-emerald-500/10 rounded-xl flex items-center justify-center shrink-0 group-hover:bg-emerald-500/20 transition-colors duration-300">
                        <svg class="w-5 h-5 text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-display font-bold text-white text-sm mb-1">Butuh Jawaban Cepat?</h3>
                        <p class="text-slate-400 text-xs leading-relaxed mb-2">
                            Sebelum mengirim pesan, mungkin jawaban dari pertanyaanmu sudah ada di halaman FAQ kami.
                        </p>
                        <a href="{{ route('home') }}" class="text-emerald-400 hover:text-emerald-300 text-xs font-semibold flex items-center gap-1">
                            Cek Beranda <span class="group-hover:translate-x-1 transition-transform">→</span>
                        </a>
                    </div>
                </div>
            </div>

        </div>

        {{-- Kolom Kanan: Form (Porsi 3 kolom) --}}
        <div class="lg:col-span-3">
            <div class="bg-gray-925 border border-gray-800 rounded-3xl p-6 md:p-10 shadow-2xl relative overflow-hidden h-full">
                
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
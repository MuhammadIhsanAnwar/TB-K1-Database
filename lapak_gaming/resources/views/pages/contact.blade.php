@extends('layouts.app')

@section('title', 'Hubungi Kami — Lapak Gaming')

@section('content')
<div class="max-w-4xl mx-auto px-4 py-12 md:py-20">
    
    {{-- Header --}}
    <div class="text-center mb-12">
        <h1 class="text-3xl md:text-5xl font-display font-bold text-white mb-4">Hubungi Kami</h1>
        <p class="text-slate-400 text-sm md:text-base max-w-xl mx-auto">
            Punya pertanyaan seputar transaksi, top-up, atau akun? Tim support Lapak Gaming siap membantu Anda 24/7.
        </p>
    </div>

    <div class="grid md:grid-cols-2 gap-8">
        
        {{-- Kolom Kiri: Informasi Kontak --}}
        <div class="space-y-6">
            <div class="bg-gray-900/50 border border-gray-800 rounded-2xl p-6 hover:border-blue-500/50 transition-colors">
                <div class="w-12 h-12 bg-blue-500/10 rounded-xl flex items-center justify-center mb-4">
                    <svg class="w-6 h-6 text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-white mb-1">Email Support</h3>
                <p class="text-slate-400 text-sm mb-3">Untuk bantuan kendala transaksi & akun.</p>
                <a href="mailto:support@lapakgaming.com" class="text-blue-400 font-medium hover:text-blue-300">support@lapakgaming.com</a>
            </div>

            <div class="bg-gray-900/50 border border-gray-800 rounded-2xl p-6 hover:border-green-500/50 transition-colors">
                <div class="w-12 h-12 bg-green-500/10 rounded-xl flex items-center justify-center mb-4">
                    <svg class="w-6 h-6 text-green-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z" />
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-white mb-1">WhatsApp CS</h3>
                <p class="text-slate-400 text-sm mb-3">Respon cepat (Jam Kerja: 08:00 - 22:00 WIB).</p>
                <a href="#" class="text-green-400 font-medium hover:text-green-300">+62 812-3456-7890</a>
            </div>
            
            <div class="bg-gray-900/50 border border-gray-800 rounded-2xl p-6 hover:border-purple-500/50 transition-colors">
                <div class="w-12 h-12 bg-purple-500/10 rounded-xl flex items-center justify-center mb-4">
                    <svg class="w-6 h-6 text-purple-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-white mb-1">Kerjasama & Bisnis</h3>
                <p class="text-slate-400 text-sm mb-3">Penawaran kerjasama partnership atau media.</p>
                <a href="mailto:business@lapakgaming.com" class="text-purple-400 font-medium hover:text-purple-300">business@lapakgaming.com</a>
            </div>
        </div>

        {{-- Kolom Kanan: Form --}}
        <div class="bg-[#0D1421] border border-[#1E2D45] rounded-3xl p-6 md:p-8">
            <h2 class="text-xl font-display font-bold text-white mb-6">Kirim Pesan Langsung</h2>
            
            <form action="#" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-sm text-slate-400 mb-1.5">Nama Lengkap</label>
                    <input type="text" placeholder="Masukkan nama Anda" class="w-full bg-[#162032] border border-[#1E2D45] text-white text-sm rounded-xl px-4 py-3 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-all">
                </div>
                
                <div>
                    <label class="block text-sm text-slate-400 mb-1.5">Email Aktif</label>
                    <input type="email" placeholder="contoh@email.com" class="w-full bg-[#162032] border border-[#1E2D45] text-white text-sm rounded-xl px-4 py-3 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-all">
                </div>
                
                <div>
                    <label class="block text-sm text-slate-400 mb-1.5">Kategori Kendala</label>
                    <select class="w-full bg-[#162032] border border-[#1E2D45] text-slate-300 text-sm rounded-xl px-4 py-3 focus:outline-none focus:border-blue-500 transition-all">
                        <option>Top Up & Pembayaran</option>
                        <option>Akun Tertahan / Banned</option>
                        <option>Laporan Penipuan (Fraud)</option>
                        <option>Pertanyaan Umum</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm text-slate-400 mb-1.5">Detail Pesan</label>
                    <textarea rows="4" placeholder="Jelaskan detail kendala Anda..." class="w-full bg-[#162032] border border-[#1E2D45] text-white text-sm rounded-xl px-4 py-3 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-all"></textarea>
                </div>

                <button type="button" onclick="alert('Pesan terkirim! Tim kami akan membalas via email dalam 1x24 jam.')" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3.5 rounded-xl transition-colors mt-2">
                    Kirim Pesan
                </button>
            </form>
        </div>

    </div>
</div>
@endsection
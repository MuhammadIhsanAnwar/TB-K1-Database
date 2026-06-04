@extends('layouts.app')

@section('title', 'Hubungi Kami — Lapak Gaming')

@push('styles')
<style>
    /* ───────── REVEAL ANIMATION ───────── */
    .reveal {
        opacity: 0;
        transform: translateY(45px);
        transition:
            opacity 1s cubic-bezier(.215,.61,.355,1),
            transform 1s cubic-bezier(.215,.61,.355,1);
        will-change: opacity, transform;
    }

    .reveal.active {
        opacity: 1;
        transform: translateY(0);
    }

    .reveal-left {
        opacity: 0;
        transform: translateX(-60px);
        transition:
            opacity 1s cubic-bezier(.215,.61,.355,1),
            transform 1s cubic-bezier(.215,.61,.355,1);
    }

    .reveal-left.active {
        opacity: 1;
        transform: translateX(0);
    }

    .reveal-right {
        opacity: 0;
        transform: translateX(60px);
        transition:
            opacity 1s cubic-bezier(.215,.61,.355,1),
            transform 1s cubic-bezier(.215,.61,.355,1);
    }

    .reveal-right.active {
        opacity: 1;
        transform: translateX(0);
    }

    .reveal-zoom {
        opacity: 0;
        transform: scale(.92);
        transition:
            opacity 1s cubic-bezier(.215,.61,.355,1),
            transform 1s cubic-bezier(.215,.61,.355,1);
    }

    .reveal-zoom.active {
        opacity: 1;
        transform: scale(1);
    }

    .delay-1 { transition-delay: .12s; }
    .delay-2 { transition-delay: .24s; }
    .delay-3 { transition-delay: .36s; }
    .delay-4 { transition-delay: .48s; }
    .delay-5 { transition-delay: .6s; }

    @media (prefers-reduced-motion: reduce) {
        .reveal,
        .reveal-left,
        .reveal-right,
        .reveal-zoom {
            opacity: 1 !important;
            transform: none !important;
            transition: none !important;
        }
    }
</style>
@endpush

@section('content')
<div class="max-w-5xl mx-auto px-4 py-12 md:py-20 relative z-10">

    {{-- Background Glow Dekoratif --}}
    <div class="absolute top-0 left-1/2 -translate-x-1/2 w-full max-w-2xl h-64 bg-blue-600/10 blur-[100px] rounded-full pointer-events-none -z-10"></div>

    {{-- Header --}}
    <div class="reveal text-center mb-16">
        <h1 class="text-4xl md:text-5xl font-display font-extrabold text-white mb-4 tracking-tight">
            Hubungi <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-400 to-purple-500">Kami</span>
        </h1>

        <p class="text-slate-400 text-sm md:text-base max-w-xl mx-auto leading-relaxed">
            Punya pertanyaan seputar transaksi, top-up, atau akun? Tim support Lapak Gaming siap membantu Anda!
        </p>
    </div>

    <div class="grid lg:grid-cols-5 gap-8 lg:gap-12">

        {{-- Kolom Kiri --}}
        <div class="reveal-left delay-1 lg:col-span-2 space-y-5">

            {{-- Email --}}
            <div class="reveal-zoom delay-1 group bg-blue-900/10 backdrop-blur-sm border border-blue-500/30 rounded-2xl p-6 hover:border-blue-500/60 hover:shadow-[0_8px_30px_rgba(59,130,246,0.15)] hover:-translate-y-1 transition-all duration-300 cursor-pointer overflow-hidden relative">

                <div class="absolute left-0 top-0 bottom-0 w-1.5 bg-blue-500/50 group-hover:bg-blue-500 shadow-[0_0_10px_rgba(59,130,246,0.5)] transition-colors duration-300"></div>

                <div class="w-12 h-12 bg-blue-500/20 rounded-xl flex items-center justify-center mb-5 group-hover:scale-110 group-hover:bg-blue-500/30 transition-all duration-300 ml-2">
                    <svg class="w-6 h-6 text-blue-400 group-hover:-rotate-6 transition-transform duration-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                </div>

                <h3 class="text-lg font-display font-bold text-white mb-1 ml-2">
                    Email Support
                </h3>

                <p class="text-slate-400 text-sm mb-4 ml-2">
                    Satu-satunya jalur resmi untuk bantuan kendala transaksi & pelaporan akun.
                </p>

                <a href="mailto:administrator@lapakgaming.neoverse.my.id"
                    class="text-blue-400 font-semibold text-sm group-hover:text-blue-300 flex items-center gap-2 ml-2">

                    administrator@lapakgaming.neoverse.my.id

                    <span class="opacity-0 group-hover:opacity-100 group-hover:translate-x-1 transition-all duration-300">
                        →
                    </span>
                </a>
            </div>

            {{-- Jam Operasional --}}
            <div class="reveal-zoom delay-2 group bg-gray-925/80 backdrop-blur-sm border border-gray-800 rounded-2xl p-6 hover:border-orange-500/40 hover:-translate-y-1 transition-all duration-300">

                <div class="flex items-start gap-4">

                    <div class="w-10 h-10 bg-orange-500/10 rounded-xl flex items-center justify-center shrink-0 group-hover:bg-orange-500/20 transition-colors duration-300">
                        <svg class="w-5 h-5 text-orange-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>

                    <div>
                        <h3 class="font-display font-bold text-white text-sm mb-1">
                            Jam Operasional
                        </h3>

                        <p class="text-slate-400 text-xs leading-relaxed">
                            <strong class="text-slate-300">
                                Senin - Minggu: 08:00 - 22:00 WIB
                            </strong><br>

                            Pesan yang masuk di luar jam kerja akan dibalas pada hari berikutnya.
                        </p>
                    </div>
                </div>
            </div>

            {{-- FAQ --}}
            <div class="reveal-zoom delay-3 group bg-gray-925/80 backdrop-blur-sm border border-gray-800 rounded-2xl p-6 hover:border-emerald-500/40 transition-all duration-300">

                <div class="flex items-start gap-4">

                    <div class="w-10 h-10 bg-emerald-500/10 rounded-xl flex items-center justify-center shrink-0 group-hover:bg-emerald-500/20 transition-colors duration-300">
                        <svg class="w-5 h-5 text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>

                    <div>
                        <h3 class="font-display font-bold text-white text-sm mb-1">
                            Butuh Jawaban Cepat?
                        </h3>

                        <p class="text-slate-400 text-xs leading-relaxed mb-2">
                            Sebelum mengirim pesan, mungkin jawaban dari pertanyaanmu sudah ada di halaman FAQ kami.
                        </p>

                        <a href="{{ route('home') }}"
                            class="text-emerald-400 hover:text-emerald-300 text-xs font-semibold flex items-center gap-1">

                            Cek Beranda

                            <span class="group-hover:translate-x-1 transition-transform">
                                →
                            </span>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        {{-- Kolom Kanan --}}
        <div class="reveal-right delay-2 lg:col-span-3">

            <div class="bg-gray-925 border border-gray-800 rounded-3xl p-6 md:p-10 shadow-2xl relative overflow-hidden h-full">

                <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-bl from-blue-600/20 to-transparent rounded-bl-full pointer-events-none"></div>

                <h2 class="text-2xl font-display font-bold text-white mb-8 flex items-center gap-3">
                    <span class="text-xl">✉️</span>
                    Kirim Pesan Langsung
                </h2>

                @if(session('success'))
                    <div class="mb-6 rounded-2xl border border-emerald-500/20 bg-emerald-500/10 px-4 py-3 text-sm text-emerald-200">
                        {{ session('success') }}
                    </div>
                @endif

                @if($errors->any())
                    <div class="mb-6 rounded-2xl border border-rose-500/20 bg-rose-500/10 px-4 py-3 text-sm text-rose-200">
                        <ul class="space-y-1 list-disc pl-5">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('contact.store') }}" method="POST" class="space-y-5 relative z-10">
                    @csrf

                    <div class="grid md:grid-cols-2 gap-5">

                        <div class="group">
                            <label class="block text-sm font-medium text-slate-400 mb-2 group-focus-within:text-blue-400 transition-colors">
                                Nama Lengkap
                            </label>

                            <input type="text"
                                name="name"
                                value="{{ old('name', auth()->user()->name ?? '') }}"
                                placeholder="John Doe"
                                class="w-full bg-gray-900 border border-gray-800 text-white text-sm rounded-xl px-4 py-3.5 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-all hover:border-gray-700">
                        </div>

                        <div class="group">
                            <label class="block text-sm font-medium text-slate-400 mb-2 group-focus-within:text-blue-400 transition-colors">
                                Email Aktif
                            </label>

                            <input type="email"
                                name="email"
                                value="{{ old('email', auth()->user()->email ?? '') }}"
                                placeholder="john@example.com"
                                class="w-full bg-gray-900 border border-gray-800 text-white text-sm rounded-xl px-4 py-3.5 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-all hover:border-gray-700">
                        </div>
                    </div>

                    <div class="group">
                        <label class="block text-sm font-medium text-slate-400 mb-2 group-focus-within:text-blue-400 transition-colors">
                            Kategori Kendala
                        </label>

                        <select name="category" class="w-full bg-gray-900 border border-gray-800 text-slate-300 text-sm rounded-xl px-4 py-3.5 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-all hover:border-gray-700 appearance-none cursor-pointer">

                            <option value="general" @selected(old('category', 'general') === 'general')>Pilih Kategori</option>
                            <option value="payment" @selected(old('category') === 'payment')>Top Up & Pembayaran</option>
                            <option value="account" @selected(old('category') === 'account')>Akun Tertahan / Banned</option>
                            <option value="fraud" @selected(old('category') === 'fraud')>Laporan Penipuan (Fraud)</option>
                            <option value="general" @selected(old('category') === 'general')>Pertanyaan Umum</option>
                        </select>
                    </div>

                    <div class="group">
                        <label class="block text-sm font-medium text-slate-400 mb-2 group-focus-within:text-blue-400 transition-colors">
                            Detail Pesan
                        </label>

                        <textarea name="message" rows="5" placeholder="Jelaskan detail kendala Anda secara lengkap..." class="w-full bg-gray-900 border border-gray-800 text-white text-sm rounded-xl px-4 py-3.5 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-all hover:border-gray-700 resize-none">{{ old('message') }}</textarea>
                    </div>
                    <div class="group">
                        <label class="block text-sm font-medium text-slate-400 mb-2 group-focus-within:text-blue-400 transition-colors">Topik Pesan</label>
                        <input type="text" name="subject" value="{{ old('subject') }}" placeholder="Contoh: Kendala pembayaran top up" class="w-full bg-gray-900 border border-gray-800 text-white text-sm rounded-xl px-4 py-3.5 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-all hover:border-gray-700">
                    </div>

                    <button type="submit" class="w-full relative inline-flex items-center justify-center px-8 py-4 font-bold text-white transition-all duration-300 bg-blue-600 rounded-xl hover:bg-blue-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-600 focus:ring-offset-gray-900 overflow-hidden group/btn mt-4">

                        <span class="absolute w-0 h-0 transition-all duration-500 ease-out bg-white rounded-full group-hover/btn:w-56 group-hover/btn:h-56 opacity-10"></span>

                        <span class="relative flex items-center gap-2">Kirim Pesan Sekarang</span>
                    </button>
                </form>
            </div>
        </div>
    </div>

    {{-- FAQ SECTION --}}
    <div class="mt-20 pt-16 border-t border-gray-800">
        <div class="text-center mb-12">
            <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-gradient-to-r from-blue-500/20 to-cyan-500/20 border border-blue-400/30 mb-4">
                <svg class="w-3.5 h-3.5 text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span class="text-xs font-bold text-blue-300 uppercase tracking-wider">Pusat Bantuan</span>
            </div>
            <h2 class="text-3xl md:text-4xl font-bold text-white mb-2">Pertanyaan yang Sering Diajukan</h2>
            <p class="text-slate-400 text-sm md:text-base max-w-xl mx-auto">Temukan jawaban atas pertanyaan yang paling sering ditanyakan mengenai top up, pembayaran, dan transaksi.</p>
        </div>

        <div class="max-w-3xl mx-auto space-y-3.5">
            @php
                $contactFaqs = [
                    [
                        'q' => 'Bagaimana cara melakukan top up game?',
                        'a' => 'Pilih game yang ingin di-top up, masukkan User ID, pilih nominal yang diinginkan, lakukan pembayaran, dan pesanan akan diproses secara otomatis.'
                    ],
                    [
                        'q' => 'Berapa lama proses top up berlangsung?',
                        'a' => 'Sebagian besar transaksi diproses dalam hitungan detik hingga beberapa menit setelah pembayaran berhasil diverifikasi.'
                    ],
                    [
                        'q' => 'Metode pembayaran apa saja yang tersedia?',
                        'a' => 'Kami mendukung berbagai metode pembayaran seperti QRIS, Transfer Bank, E-Wallet, Virtual Account, dan metode pembayaran lainnya yang tersedia pada halaman checkout.'
                    ],
                    [
                        'q' => 'Apakah data akun game saya aman?',
                        'a' => 'Ya. Kami hanya memerlukan User ID atau informasi yang dibutuhkan untuk pengiriman item dan tidak pernah meminta password akun game Anda.'
                    ],
                    [
                        'q' => 'Apa yang harus dilakukan jika top up belum masuk?',
                        'a' => 'Silakan hubungi customer support dengan menyertakan ID transaksi agar tim kami dapat membantu melakukan pengecekan.'
                    ],
                    [
                        'q' => 'Apakah layanan tersedia 24 jam?',
                        'a' => 'Ya. Sistem transaksi berjalan 24/7 sehingga Anda dapat melakukan top up kapan saja.'
                    ]
                ];
            @endphp

            @foreach($contactFaqs as $index => $faq)
                <div class="contact-faq-item group relative overflow-hidden rounded-[20px] border border-white/[0.05] bg-white/[0.015] transition-all duration-300 hover:bg-white/[0.03] hover:border-sky-500/30">
                    <div class="contact-faq-accent absolute left-0 top-0 bottom-0 w-[3px] bg-gradient-to-b from-sky-400 to-blue-600 opacity-0 transition-all duration-500 scale-y-0 origin-top"></div>

                    <button class="contact-faq-btn flex w-full items-center justify-between gap-4 p-5 sm:p-6 text-left cursor-pointer outline-none focus:outline-none">
                        <div class="flex items-center gap-4 sm:gap-6">
                            <span class="contact-faq-num font-['Oxanium'] text-xl sm:text-2xl font-black text-white/10 transition-colors duration-300">{{ sprintf('%02d', $index + 1) }}</span>
                            <h3 class="contact-faq-title font-semibold text-white/80 text-sm sm:text-base transition-colors duration-300 group-hover:text-sky-300">{{ $faq['q'] }}</h3>
                        </div>
                        <div class="contact-faq-icon-box flex h-9 w-9 shrink-0 items-center justify-center rounded-xl border border-white/10 bg-white/5 transition-all duration-300 group-hover:border-sky-400/30 group-hover:bg-sky-500/10">
                            <svg class="contact-faq-icon h-4 w-4 text-sky-400 transition-all duration-300" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path class="contact-faq-icon-path" stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                            </svg>
                        </div>
                    </button>

                    <div class="contact-faq-content grid grid-rows-[0fr] opacity-0 transition-all duration-500">
                        <div class="overflow-hidden">
                            <div class="pb-6 pl-[4.2rem] pr-6 pt-0 text-sm leading-relaxed text-slate-400 sm:pl-[5.2rem]">
                                <div class="border-l-2 border-white/5 pl-4 text-[13px] sm:text-sm">{{ $faq['a'] }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    @keyframes faqSlideIn {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .contact-faq-item {
        animation: faqSlideIn 0.6s ease-out backwards;
    }

    .contact-faq-item:nth-child(1) { animation-delay: 0s; }
    .contact-faq-item:nth-child(2) { animation-delay: 0.1s; }
    .contact-faq-item:nth-child(3) { animation-delay: 0.2s; }
    .contact-faq-item:nth-child(4) { animation-delay: 0.3s; }
    .contact-faq-item:nth-child(5) { animation-delay: 0.4s; }
    .contact-faq-item:nth-child(6) { animation-delay: 0.5s; }

    .contact-faq-item:hover {
        background: linear-gradient(135deg, rgba(14, 165, 233, 0.08) 0%, rgba(59, 130, 246, 0.05) 100%);
        border-color: rgba(59, 130, 246, 0.35);
        box-shadow: 0 8px 32px rgba(59, 130, 246, 0.1), inset 0 1px 1px rgba(255, 255, 255, 0.05);
    }

    .contact-faq-item.active .contact-faq-accent {
        opacity: 1 !important;
        transform: scaleY(1) !important;
        background: linear-gradient(to bottom, #06b6d4, #3b82f6);
    }

    .contact-faq-item:hover .contact-faq-accent {
        opacity: 1;
        transform: scaleY(1);
        background: linear-gradient(to bottom, #06b6d4, #3b82f6);
    }

    .contact-faq-item:hover .contact-faq-num {
        color: rgba(255, 255, 255, 0.25);
        font-size: clamp(1.25rem, 2.5vw, 1.75rem);
    }

    .contact-faq-item:hover .contact-faq-title {
        color: rgba(6, 182, 212, 0.9);
        text-shadow: 0 0 8px rgba(6, 182, 212, 0.3);
    }

    .contact-faq-icon-box {
        background: rgba(59, 130, 246, 0.1);
        border: 1.5px solid rgba(59, 130, 246, 0.2);
    }

    .contact-faq-item:hover .contact-faq-icon-box {
        background: rgba(6, 182, 212, 0.15);
        border-color: rgba(6, 182, 212, 0.5);
        box-shadow: 0 0 16px rgba(6, 182, 212, 0.2);
    }

    .contact-faq-item.active .contact-faq-icon {
        transform: rotate(45deg);
        color: rgba(6, 182, 212, 1);
    }

    .contact-faq-item.active .contact-faq-content {
        grid-template-rows: 1fr;
        opacity: 1;
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {

        const reveals = document.querySelectorAll(
            '.reveal, .reveal-left, .reveal-right, .reveal-zoom'
        );

        const observer = new IntersectionObserver((entries) => {

            entries.forEach(entry => {

                if (entry.isIntersecting) {
                    entry.target.classList.add('active');
                }

            });

        }, {
            threshold: 0.12
        });

        reveals.forEach(el => observer.observe(el));

    });

    /* ══ CONTACT PAGE FAQ ACCORDION ════════════════════════════════ */
    document.querySelectorAll('.contact-faq-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            const item = btn.closest('.contact-faq-item');
            const content = item.querySelector('.contact-faq-content');
            const iconPath = item.querySelector('.contact-faq-icon-path');
            const iconBox = item.querySelector('.contact-faq-icon-box');
            const accent = item.querySelector('.contact-faq-accent');
            const title = item.querySelector('.contact-faq-title');
            const num = item.querySelector('.contact-faq-num');

            const isOpen = item.classList.contains('is-open') || item.classList.contains('active');

            // Close all other items
            document.querySelectorAll('.contact-faq-item').forEach(otherItem => {
                otherItem.classList.remove('is-open', 'active');
                otherItem.style.backgroundColor = '';
                otherItem.style.borderColor = '';

                const otherContent = otherItem.querySelector('.contact-faq-content');
                const otherIconPath = otherItem.querySelector('.contact-faq-icon-path');
                const otherIconBox = otherItem.querySelector('.contact-faq-icon-box');
                const otherAccent = otherItem.querySelector('.contact-faq-accent');
                const otherTitle = otherItem.querySelector('.contact-faq-title');
                const otherNum = otherItem.querySelector('.contact-faq-num');

                if (otherContent) {
                    otherContent.style.gridTemplateRows = '0fr';
                    otherContent.style.opacity = '0';
                }
                if (otherIconPath) {
                    otherIconPath.setAttribute('d', 'M12 4.5v15m7.5-7.5h-15');
                }
                if (otherIconBox) {
                    otherIconBox.style.backgroundColor = '';
                    otherIconBox.style.borderColor = '';
                }
                if (otherAccent) {
                    otherAccent.style.transform = 'scaleY(0)';
                    otherAccent.style.opacity = '0';
                }
                if (otherTitle) {
                    otherTitle.classList.remove('text-sky-400');
                    otherTitle.classList.add('text-white/80');
                }
                if (otherNum) {
                    otherNum.classList.remove('text-sky-500/30');
                    otherNum.classList.add('text-white/10');
                }
            });

            if (!isOpen) {
                item.classList.add('is-open', 'active');
                item.style.backgroundColor = 'rgba(14, 165, 233, 0.05)';
                item.style.borderColor = 'rgba(14, 165, 233, 0.3)';

                content.style.gridTemplateRows = '1fr';
                content.style.opacity = '1';

                if (iconPath) iconPath.setAttribute('d', 'M19.5 12h-15');
                if (iconBox) {
                    iconBox.style.backgroundColor = 'rgba(14, 165, 233, 0.1)';
                    iconBox.style.borderColor = 'rgba(56, 189, 248, 0.3)';
                }

                accent.style.transform = 'scaleY(1)';
                accent.style.opacity = '1';
                title.classList.remove('text-white/80');
                title.classList.add('text-sky-400');
                num.classList.remove('text-white/10');
                num.classList.add('text-sky-500/30');
            }
        });
    });
</script>
@endpush
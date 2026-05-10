@extends('layouts.app')

@section('title', 'Aturan Penggunaan — Lapak Gaming')

@section('content')
<div class="max-w-4xl mx-auto px-4 py-12 md:py-20">
    
    {{-- Header --}}
    <div class="mb-10 border-b border-gray-800 pb-8">
        <h1 class="text-3xl md:text-5xl font-display font-bold text-white mb-4">Aturan Penggunaan</h1>
        <p class="text-slate-400 text-sm md:text-base">
            Pembaruan Terakhir: 10 Mei 2026
        </p>
    </div>

    {{-- Konten Aturan --}}
    <div class="prose prose-invert max-w-none text-slate-300">
        <p class="mb-6">
            Selamat datang di Lapak Gaming. Syarat & ketentuan yang ditetapkan di bawah ini mengatur pemakaian jasa yang ditawarkan oleh PT Lapak Gaming Indonesia terkait penggunaan situs <strong>lapakgaming.neoverse.my.id</strong>. Pengguna disarankan membaca dengan saksama karena dapat berdampak kepada hak dan kewajiban Pengguna di bawah hukum.
        </p>

        <div class="space-y-8">
            {{-- Section 1 --}}
            <section>
                <h2 class="text-xl font-bold text-white mb-3 flex items-center gap-2">
                    <span class="text-blue-500">1.</span> Akun, Password, dan Keamanan
                </h2>
                <ul class="list-disc pl-5 space-y-2 text-sm leading-relaxed">
                    <li>Pengguna dengan ini menyatakan bahwa pengguna adalah orang yang cakap dan mampu untuk mengikatkan dirinya dalam sebuah perjanjian yang sah menurut hukum.</li>
                    <li>Lapak Gaming tidak memungut biaya pendaftaran kepada Pengguna.</li>
                    <li>Pengguna bertanggung jawab secara pribadi untuk menjaga kerahasiaan akun dan password untuk semua aktivitas yang terjadi dalam akun Pengguna.</li>
                    <li>Lapak Gaming tidak akan meminta password akun Pengguna untuk alasan apapun, oleh karena itu Lapak Gaming menghimbau Pengguna agar tidak memberikan password akun Anda kepada pihak manapun.</li>
                </ul>
            </section>

            {{-- Section 2 --}}
            <section>
                <h2 class="text-xl font-bold text-white mb-3 flex items-center gap-2">
                    <span class="text-blue-500">2.</span> Transaksi Pembelian
                </h2>
                <ul class="list-disc pl-5 space-y-2 text-sm leading-relaxed">
                    <li>Pembeli wajib bertransaksi melalui prosedur transaksi yang telah ditetapkan oleh Lapak Gaming. Pembeli melakukan pembayaran dengan menggunakan metode pembayaran yang sebelumnya telah dipilih oleh Pembeli.</li>
                    <li>Saat melakukan pembelian Produk, Pembeli menyetujui bahwa Pembeli bertanggung jawab untuk membaca, memahami, dan menyetujui informasi/deskripsi keseluruhan Produk sebelum membuat tawaran atau komitmen untuk membeli.</li>
                    <li>Pembeli memahami dan menyetujui bahwa ketersediaan stok Produk merupakan tanggung jawab Penjual yang menawarkan Produk tersebut.</li>
                    <li>Semua transaksi menggunakan sistem <em>Escrow</em> (Rekening Bersama). Dana akan diteruskan ke Penjual hanya setelah Pembeli mengonfirmasi pesanan telah diterima dengan baik.</li>
                </ul>
            </section>

            {{-- Section 3 --}}
            <section>
                <h2 class="text-xl font-bold text-white mb-3 flex items-center gap-2">
                    <span class="text-blue-500">3.</span> Transaksi Penjualan
                </h2>
                <ul class="list-disc pl-5 space-y-2 text-sm leading-relaxed">
                    <li>Penjual dilarang memanipulasi harga Produk dengan tujuan apapun.</li>
                    <li>Penjual wajib memberikan keterangan yang lengkap, jelas, dan sesuai dengan produk yang ditawarkan (misalnya detail akun, status legalitas diamond/voucher).</li>
                    <li>Penjual wajib menyerahkan produk sesuai dengan spesifikasi yang disepakati maksimal dalam batas waktu pengiriman yang telah ditentukan sistem.</li>
                    <li>Apabila ditemukan kecurangan atau produk yang diberikan adalah ilegal (hasil carding/hack), Lapak Gaming berhak menahan dana dan membekukan akun Penjual.</li>
                </ul>
            </section>

            {{-- Section 4 --}}
            <section>
                <h2 class="text-xl font-bold text-white mb-3 flex items-center gap-2">
                    <span class="text-blue-500">4.</span> Barang dan Jasa Terlarang
                </h2>
                <p class="text-sm mb-2">Lapak Gaming dengan tegas melarang penjualan produk/jasa berikut:</p>
                <ul class="list-disc pl-5 space-y-2 text-sm leading-relaxed">
                    <li>Aplikasi atau tools ilegal (Cheat, Hack, Bot, Mod).</li>
                    <li>Produk hasil pencurian (Carding, Phishing, Scamming).</li>
                    <li>Data pribadi pihak ketiga atau informasi finansial.</li>
                    <li>Mata uang kripto (Cryptocurrency) atau produk finansial yang tidak memiliki izin resmi di Indonesia.</li>
                </ul>
            </section>

            {{-- Section 5 --}}
            <section>
                <h2 class="text-xl font-bold text-white mb-3 flex items-center gap-2">
                    <span class="text-blue-500">5.</span> Penyelesaian Sengketa (Dispute)
                </h2>
                <ul class="list-disc pl-5 space-y-2 text-sm leading-relaxed">
                    <li>Apabila terjadi kendala pesanan (misal: produk tidak sesuai atau tidak dikirim), Pembeli dapat menekan tombol "Komplain" sebelum masa garansi habis.</li>
                    <li>Tim Resolusi Lapak Gaming akan menjadi penengah yang adil dengan meninjau bukti dari kedua belah pihak.</li>
                    <li>Keputusan Tim Resolusi Lapak Gaming bersifat final dan mengikat baik bagi Pembeli maupun Penjual.</li>
                </ul>
            </section>
        </div>

        <div class="mt-12 p-6 bg-gray-900/50 border border-gray-800 rounded-2xl text-center">
            <p class="text-sm text-slate-400">
                Dengan mendaftar dan/atau menggunakan situs Lapak Gaming, maka pengguna dianggap telah membaca, mengerti, memahami dan menyetujui semua isi dalam Syarat & Ketentuan ini.
            </p>
        </div>
    </div>
</div>
@endsection
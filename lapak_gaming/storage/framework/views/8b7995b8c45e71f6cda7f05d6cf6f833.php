<?php $__env->startSection('title', 'Tentang Kami — Lapak Gaming'); ?>

<?php $__env->startSection('content'); ?>


<style>
    .reveal-up{
        opacity:0;
        transform:translateY(45px);
        transition:
            opacity .9s cubic-bezier(.22,1,.36,1),
            transform .9s cubic-bezier(.22,1,.36,1);
        will-change:transform,opacity;
    }

    .reveal-up.active{
        opacity:1;
        transform:translateY(0);
    }

    .reveal-left{
        opacity:0;
        transform:translateX(-60px);
        transition:
            opacity .9s cubic-bezier(.22,1,.36,1),
            transform .9s cubic-bezier(.22,1,.36,1);
        will-change:transform,opacity;
    }

    .reveal-left.active{
        opacity:1;
        transform:translateX(0);
    }

    .reveal-right{
        opacity:0;
        transform:translateX(60px);
        transition:
            opacity .9s cubic-bezier(.22,1,.36,1),
            transform .9s cubic-bezier(.22,1,.36,1);
        will-change:transform,opacity;
    }

    .reveal-right.active{
        opacity:1;
        transform:translateX(0);
    }

    .reveal-scale{
        opacity:0;
        transform:scale(.9);
        transition:
            opacity .8s ease,
            transform .8s ease;
        will-change:transform,opacity;
    }

    .reveal-scale.active{
        opacity:1;
        transform:scale(1);
    }

    .delay-1{ transition-delay:.1s; }
    .delay-2{ transition-delay:.2s; }
    .delay-3{ transition-delay:.3s; }
    .delay-4{ transition-delay:.4s; }
</style>

<div class="max-w-5xl mx-auto px-4 py-12 md:py-20 relative z-10">

    
    <div class="absolute top-10 left-1/2 -translate-x-1/2 w-full max-w-2xl h-64 bg-cyan-600/10 blur-[100px] rounded-full pointer-events-none -z-10"></div>

    
    <div class="reveal-up text-center mb-16 border-b border-gray-800/50 pb-10 relative">

        <h1 class="text-4xl md:text-5xl font-display font-extrabold text-white mb-6 tracking-tight">
            Tentang
            <span class="text-transparent bg-clip-text bg-gradient-to-r from-cyan-400 to-blue-500">
                Lapak Gaming
            </span>
        </h1>

        <div class="inline-flex items-center gap-3 bg-gray-900/80 backdrop-blur-sm px-5 py-2.5 rounded-full border border-gray-800 shadow-lg">

            <span class="text-xl">🎮</span>

            <span class="text-slate-300 text-sm font-medium">
                Marketplace Gaming #1 di Indonesia
            </span>

        </div>
    </div>

    
    <div class="reveal-scale delay-1 bg-gray-900/40 border border-gray-700/50 rounded-3xl p-8 md:p-12 mb-12 text-center relative overflow-hidden group">

        <div class="absolute inset-0 bg-gradient-to-b from-cyan-500/5 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-700"></div>

        <p class="text-slate-300 leading-relaxed text-base md:text-lg relative z-10 max-w-3xl mx-auto">

            Lahir dari komunitas gamer untuk komunitas gamer.
            <strong class="text-white font-display">Lapak Gaming</strong>
            hadir sebagai solusi marketplace produk digital yang aman, cepat, dan terpercaya.

            Kami menjembatani jutaan gamer Indonesia untuk bertransaksi top-up, item, akun, hingga jasa joki tanpa perlu khawatir akan penipuan berkat sistem
            <em>Escrow</em> (Rekening Bersama) kami yang canggih.

        </p>
    </div>

    
    <div class="grid md:grid-cols-2 gap-6 mb-12">

        
        <div class="reveal-left delay-2 group relative bg-cyan-900/10 backdrop-blur-sm border border-cyan-500/30 rounded-3xl p-8 hover:border-cyan-500/60 hover:shadow-[0_8px_30px_rgba(6,182,212,0.15)] hover:-translate-y-1 transition-all duration-300 overflow-hidden">

            <div class="absolute left-0 top-0 bottom-0 w-1.5 bg-cyan-500/50 group-hover:bg-cyan-500 shadow-[0_0_10px_rgba(6,182,212,0.5)] transition-colors duration-300"></div>

            <div class="w-14 h-14 rounded-2xl bg-cyan-500/20 flex items-center justify-center mb-6 group-hover:scale-110 group-hover:bg-cyan-500/30 transition-all duration-300">

                <svg class="w-7 h-7 text-cyan-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                </svg>

            </div>

            <h2 class="text-2xl font-display font-bold text-white mb-4">
                Visi Kami
            </h2>

            <p class="text-slate-300 leading-relaxed">
                Menjadi ekosistem digital gaming terbesar dan paling dipercaya di Asia Tenggara yang menghubungkan setiap elemen dalam industri esports dan gaming.
            </p>

        </div>

        
        <div class="reveal-right delay-2 group relative bg-blue-900/10 backdrop-blur-sm border border-blue-500/30 rounded-3xl p-8 hover:border-blue-500/60 hover:shadow-[0_8px_30px_rgba(59,130,246,0.15)] hover:-translate-y-1 transition-all duration-300 overflow-hidden">

            <div class="absolute left-0 top-0 bottom-0 w-1.5 bg-blue-500/50 group-hover:bg-blue-500 shadow-[0_0_10px_rgba(59,130,246,0.5)] transition-colors duration-300"></div>

            <div class="w-14 h-14 rounded-2xl bg-blue-500/20 flex items-center justify-center mb-6 group-hover:scale-110 group-hover:bg-blue-500/30 transition-all duration-300">

                <svg class="w-7 h-7 text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                </svg>

            </div>

            <h2 class="text-2xl font-display font-bold text-white mb-4">
                Misi Kami
            </h2>

            <ul class="space-y-3 text-slate-300 leading-relaxed">

                <li class="flex gap-3">
                    <span class="text-blue-500 mt-1 shrink-0">▹</span>
                    Menciptakan platform transaksi yang 100% aman dan bebas penipuan.
                </li>

                <li class="flex gap-3">
                    <span class="text-blue-500 mt-1 shrink-0">▹</span>
                    Memberdayakan gamer untuk mendapatkan penghasilan tambahan.
                </li>

                <li class="flex gap-3">
                    <span class="text-blue-500 mt-1 shrink-0">▹</span>
                    Menyediakan layanan pelanggan yang responsif dan solutif.
                </li>

            </ul>

        </div>

    </div>

    
    <div class="mb-16">

        <h2 class="reveal-up delay-2 text-center text-2xl font-display font-bold text-white mb-8">
            Kenapa Memilih Lapak Gaming?
        </h2>

        <div class="grid md:grid-cols-3 gap-6">

            <?php $__currentLoopData = [
                ['icon'=>'M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z', 'title'=>'Keamanan Terjamin', 'desc'=>'Dana ditahan oleh sistem hingga pesanan dipastikan sesuai dan diterima oleh pembeli.'],
                ['icon'=>'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z', 'title'=>'Proses Cepat', 'desc'=>'Nikmati fitur pengiriman instan untuk kategori produk tertentu tanpa perlu menunggu.'],
                ['icon'=>'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z', 'title'=>'Komunitas Solid', 'desc'=>'Ribuan penjual terverifikasi bersaing memberikan harga termurah dan layanan terbaik.']
            ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $feature): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

            <div class="reveal-up delay-3 bg-gray-900/50 border border-gray-800 rounded-2xl p-6 text-center hover:border-gray-600 transition-colors duration-300">

                <div class="w-12 h-12 mx-auto rounded-full bg-gray-800 flex items-center justify-center mb-4">

                    <svg class="w-6 h-6 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="<?php echo e($feature['icon']); ?>"/>
                    </svg>

                </div>

                <h3 class="text-lg font-bold text-white mb-2">
                    <?php echo e($feature['title']); ?>

                </h3>

                <p class="text-sm text-slate-400 leading-relaxed">
                    <?php echo e($feature['desc']); ?>

                </p>

            </div>

            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

        </div>

    </div>

    
    <div class="reveal-scale delay-4 p-8 md:p-12 bg-gradient-to-r from-blue-900/40 to-cyan-900/40 border border-cyan-500/30 rounded-3xl text-center relative overflow-hidden group">

        <div class="absolute inset-0 bg-[url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjAiIGhlaWdodD0iMjAiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+PGNpcmNsZSBjeD0iMiIgY3k9IjIiIHI9IjIiIGZpbGw9InJnYmEoMjU1LDI1NSwyNTUsMC4wNSkiLz48L3N2Zz4=')] opacity-50"></div>

        <h2 class="text-2xl md:text-3xl font-display font-bold text-white mb-4 relative z-10">
            Siap Untuk Level Up?
        </h2>

        <p class="text-slate-300 mb-8 max-w-xl mx-auto relative z-10">
            Bergabunglah dengan ratusan ribu gamer lainnya yang sudah mempercayakan transaksi digital mereka di Lapak Gaming.
        </p>

        <a href="<?php echo e(route('register')); ?>"
            class="relative z-10 inline-flex items-center gap-2 bg-cyan-500 hover:bg-cyan-400 text-slate-900 font-bold px-8 py-3.5 rounded-xl transition-all duration-300 hover:scale-105 hover:shadow-[0_0_20px_rgba(6,182,212,0.4)]">

            Daftar Sekarang

            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
            </svg>

        </a>

    </div>

</div>


<script>
document.addEventListener('DOMContentLoaded', () => {

    const reveals = document.querySelectorAll(
        '.reveal-up, .reveal-left, .reveal-right, .reveal-scale'
    );

    const observer = new IntersectionObserver((entries) => {

        entries.forEach(entry => {

            if(entry.isIntersecting){

                entry.target.classList.add('active');

            }

        });

    }, {
        threshold: 0.12
    });

    reveals.forEach(el => observer.observe(el));

});
</script>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\0_Project_VS_Code\3. Sistem Basis Data\TB-K1-Database\lapak_gaming\resources\views\pages\about.blade.php ENDPATH**/ ?>
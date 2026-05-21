<header class="sticky top-0 z-40 border-b border-slate-200/70 bg-white/80 backdrop-blur-xl dark:border-slate-800 dark:bg-slate-950/80">
    <div class="mx-auto flex w-full max-w-7xl items-center justify-between px-4 py-4 sm:px-6 lg:px-8">
        <a href="<?php echo e(route('home')); ?>" class="flex items-center gap-3 font-extrabold tracking-tight text-slate-950 dark:text-white">
            <span class="flex h-10 w-10 items-center justify-center rounded-2xl bg-slate-950 text-sm text-white shadow-glow dark:bg-white dark:text-slate-950">LG</span>
            <span>Lapak Digital</span>
        </a>

        <nav class="hidden items-center gap-3 md:flex">
            <?php if(!auth()->check() || auth()->user()->role !== 'admin'): ?>
                <a href="<?php echo e(route('home')); ?>" class="rounded-full px-4 py-2 text-sm font-semibold text-slate-600 transition hover:bg-slate-100 dark:text-slate-300 dark:hover:bg-slate-800">Marketplace</a>
            <?php endif; ?>

            <?php if(auth()->guard()->check()): ?>
                <?php if(auth()->user()->role === 'admin'): ?>
                    <a href="<?php echo e(route('admin.dashboard')); ?>" class="rounded-full px-4 py-2 text-sm font-semibold text-slate-600 transition hover:bg-slate-100 dark:text-slate-300 dark:hover:bg-slate-800">Panel Admin</a>
                    <a href="<?php echo e(route('admin.users.index')); ?>" class="rounded-full px-4 py-2 text-sm font-semibold text-slate-600 transition hover:bg-slate-100 dark:text-slate-300 dark:hover:bg-slate-800">Kelola Akun</a>
                    <a href="<?php echo e(route('admin.orders.index')); ?>" class="rounded-full px-4 py-2 text-sm font-semibold text-slate-600 transition hover:bg-slate-100 dark:text-slate-300 dark:hover:bg-slate-800">Transaksi</a>
                    <a href="<?php echo e(route('admin.banners.index')); ?>" class="rounded-full px-4 py-2 text-sm font-semibold text-slate-600 transition hover:bg-slate-100 dark:text-slate-300 dark:hover:bg-slate-800">Banner</a>
                <?php else: ?>
                    <a href="<?php echo e(route('dashboard')); ?>" class="rounded-full px-4 py-2 text-sm font-semibold text-slate-600 transition hover:bg-slate-100 dark:text-slate-300 dark:hover:bg-slate-800">Dashboard</a>
                    <a href="<?php echo e(route('wallet.index')); ?>" class="rounded-full px-4 py-2 text-sm font-semibold text-slate-600 transition hover:bg-slate-100 dark:text-slate-300 dark:hover:bg-slate-800">Wallet</a>
                <?php endif; ?>
                <form method="POST" action="<?php echo e(route('logout')); ?>">
                    <?php echo csrf_field(); ?>
                    <button class="rounded-full bg-slate-950 px-4 py-2 text-sm font-semibold text-white transition hover:bg-slate-800 dark:bg-white dark:text-slate-950">
                        Logout
                    </button>
                </form>
            <?php else: ?>
                <a href="<?php echo e(route('login')); ?>" class="rounded-full px-4 py-2 text-sm font-semibold text-slate-600 transition hover:bg-slate-100 dark:text-slate-300 dark:hover:bg-slate-800">Login</a>
                <a href="<?php echo e(route('register')); ?>" class="rounded-full bg-slate-950 px-4 py-2 text-sm font-semibold text-white transition hover:bg-slate-800 dark:bg-white dark:text-slate-950">Register</a>
            <?php endif; ?>
            <button type="button" onclick="toggleTheme()" class="rounded-full border border-slate-200 px-4 py-2 text-sm font-semibold text-slate-600 transition hover:bg-slate-100 dark:border-slate-700 dark:text-slate-300 dark:hover:bg-slate-800">
                Theme
            </button>
        </nav>

        <button type="button" onclick="toggleTheme()" class="rounded-full border border-slate-200 px-3 py-2 text-sm font-semibold text-slate-600 transition hover:bg-slate-100 md:hidden dark:border-slate-700 dark:text-slate-300 dark:hover:bg-slate-800">
            Theme
        </button>
    </div>
</header><?php /**PATH D:\0_Project_VS_Code\3. Sistem Basis Data\TB-K1-Database\lapak_gaming\resources\views\partials\navbar.blade.php ENDPATH**/ ?>
<?php $__env->startSection('title', 'Wallet'); ?>

<?php $__env->startSection('content'); ?>
<div class="min-h-screen px-4 pt-28 pb-14">

    <div
        class="mx-auto max-w-6xl opacity-0 translate-y-8 animate-[fadeReveal_.9s_ease-out_forwards]">

        
        <div
            class="relative overflow-hidden rounded-[30px] border border-blue-500/20 bg-gradient-to-br from-[#060816] via-[#091225] to-[#0B1730] p-8 shadow-[0_0_80px_rgba(37,99,235,0.08)]">

            <div
                class="absolute inset-0 bg-[radial-gradient(circle_at_top_right,rgba(37,99,235,0.18),transparent_35%)]">
            </div>

            <div
                class="absolute -top-20 right-0 h-64 w-64 rounded-full bg-blue-500/10 blur-3xl">
            </div>

            <div
                class="relative z-10 flex flex-col gap-7 lg:flex-row lg:items-center lg:justify-between">

                <div class="max-w-2xl">
                    <div
                        class="inline-flex items-center gap-2 rounded-full border border-blue-500/30 bg-blue-500/10 px-4 py-2 text-[11px] font-bold tracking-[0.2em] text-blue-300">

                        <span class="h-2 w-2 rounded-full bg-emerald-400"></span>

                        DIGITAL WALLET
                    </div>

                    <h1
                        class="mt-5 text-4xl font-black leading-tight text-white md:text-5xl">
                        Wallet Center
                    </h1>

                    <p
                        class="mt-4 max-w-xl text-sm leading-relaxed text-slate-300 md:text-[15px]">
                        Kelola saldo akun, tambah dana, tarik saldo, dan pantau
                        seluruh riwayat transaksi dengan tampilan modern dan realtime.
                    </p>
                </div>

                
                <div
                    class="hidden lg:flex h-[170px] w-[170px] items-center justify-center rounded-full border border-blue-500/20 bg-blue-500/10 backdrop-blur-2xl">

                    <img
                        src="<?php echo e(asset('storage/app/public/logo/logo.png')); ?>"
                        alt="Logo"
                        class="h-28 w-28 object-contain opacity-95 drop-shadow-[0_0_25px_rgba(59,130,246,0.45)]">
                </div>

            </div>
        </div>

        
        <div class="mt-8 grid gap-6 lg:grid-cols-[0.95fr_1.05fr]">

            
            <section
                class="rounded-[30px] border border-blue-500/20 bg-[#0B1220]/95 p-7 shadow-[0_0_50px_rgba(37,99,235,0.05)] backdrop-blur-xl">

                <div class="flex items-center justify-between">
                    <div>
                        <div
                            class="text-xs font-bold uppercase tracking-[0.18em] text-blue-300">
                            Wallet Balance
                        </div>

                        <h2
                            class="mt-3 text-4xl font-black text-white">
                            Rp <?php echo e(number_format($wallet?->balance ?? 0, 0, ',', '.')); ?>

                        </h2>

                        <p class="mt-2 text-sm text-slate-400">
                            Available Balance:
                            Rp <?php echo e(number_format($wallet?->available_balance ?? 0, 0, ',', '.')); ?>

                        </p>
                    </div>

                    <div
                        class="hidden md:flex h-20 w-20 items-center justify-center rounded-2xl border border-blue-500/20 bg-blue-500/10">

                        <svg xmlns="http://www.w3.org/2000/svg"
                            class="h-10 w-10 text-blue-300"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor">

                            <path stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="1.5"
                                d="M17 9V7a5 5 0 00-10 0v2m-2 0h14a2 2 0 012 2v7a2 2 0 01-2 2H5a2 2 0 01-2-2v-7a2 2 0 012-2z" />
                        </svg>
                    </div>
                </div>

                
                <div class="mt-8 grid gap-4 sm:grid-cols-2">

                    
                    <form method="POST"
                        action="<?php echo e(route('wallet.deposit')); ?>"
                        class="group rounded-[24px] border border-emerald-500/20 bg-white/[0.03] p-5 transition duration-300 hover:-translate-y-1 hover:border-emerald-400/40 hover:bg-emerald-500/[0.03]">

                        <?php echo csrf_field(); ?>

                        <div
                            class="text-sm font-black uppercase tracking-wide text-emerald-300">
                            Deposit
                        </div>

                        <input
                            name="amount"
                            type="number"
                            min="1000"
                            placeholder="10000"
                            class="mt-4 w-full rounded-2xl border border-white/10 bg-[#101826] px-4 py-3 text-sm text-white outline-none transition focus:border-emerald-400/40 focus:ring-2 focus:ring-emerald-500/10" />

                        <button
                            class="mt-4 w-full rounded-2xl bg-gradient-to-r from-emerald-500 to-emerald-400 px-4 py-3 text-sm font-bold text-white transition duration-300 hover:scale-[1.02]">
                            Tambah Saldo
                        </button>
                    </form>

                    
                    <form method="POST"
                        action="<?php echo e(route('wallet.withdraw')); ?>"
                        class="group rounded-[24px] border border-orange-500/20 bg-white/[0.03] p-5 transition duration-300 hover:-translate-y-1 hover:border-orange-400/40 hover:bg-orange-500/[0.03]">

                        <?php echo csrf_field(); ?>

                        <div
                            class="text-sm font-black uppercase tracking-wide text-orange-300">
                            Withdraw
                        </div>

                        <input
                            name="amount"
                            type="number"
                            min="1000"
                            placeholder="10000"
                            class="mt-4 w-full rounded-2xl border border-white/10 bg-[#101826] px-4 py-3 text-sm text-white outline-none transition focus:border-orange-400/40 focus:ring-2 focus:ring-orange-500/10" />

                        <button
                            class="mt-4 w-full rounded-2xl bg-gradient-to-r from-orange-500 to-amber-400 px-4 py-3 text-sm font-bold text-white transition duration-300 hover:scale-[1.02]">
                            Tarik Dana
                        </button>
                    </form>

                </div>
            </section>

            
            <section
                class="rounded-[30px] border border-blue-500/20 bg-[#0B1220]/95 p-7 shadow-[0_0_50px_rgba(37,99,235,0.05)] backdrop-blur-xl">

                <div class="flex items-center justify-between">

                    <div>
                        <h2 class="text-2xl font-black text-white">
                            Transaction History
                        </h2>

                        <p class="mt-2 text-sm text-slate-400">
                            Riwayat transaksi terbaru akun wallet kamu.
                        </p>
                    </div>

                    <div
                        class="rounded-full border border-blue-500/20 bg-blue-500/10 px-4 py-2 text-xs font-bold text-blue-300">
                        <?php echo e(($wallet?->transactions ?? collect())->count()); ?> Logs
                    </div>

                </div>

                <div class="mt-6 space-y-4">

                    <?php $__empty_1 = true; $__currentLoopData = ($wallet?->transactions ?? collect()); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $transaction): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>

                        <div
                            class="group rounded-[22px] border border-white/5 bg-white/[0.03] p-5 transition duration-300 hover:-translate-y-1 hover:border-blue-500/30 hover:bg-blue-500/[0.03]">

                            <div class="flex items-center justify-between">

                                <div>
                                    <div
                                        class="text-sm font-bold text-white">
                                        <?php echo e($transaction->type); ?>

                                    </div>

                                    <div
                                        class="mt-2 text-xs text-slate-400">
                                        Rp <?php echo e(number_format($transaction->amount, 0, ',', '.')); ?>

                                    </div>
                                </div>

                                <span
                                    class="rounded-full px-4 py-1.5 text-[11px] font-bold border
                                    <?php echo e($transaction->direction === 'credit'
                                        ? 'border-emerald-500/20 bg-emerald-500/10 text-emerald-300'
                                        : 'border-rose-500/20 bg-rose-500/10 text-rose-300'); ?>">

                                    <?php echo e(strtoupper($transaction->direction)); ?>

                                </span>

                            </div>
                        </div>

                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>

                        <div
                            class="rounded-[24px] border border-dashed border-white/10 py-12 text-center">

                            <p class="text-sm text-slate-500">
                                Belum ada transaksi.
                            </p>
                        </div>

                    <?php endif; ?>

                </div>
            </section>

        </div>
    </div>
</div>


<style>
@keyframes fadeReveal {
    0% {
        opacity: 0;
        transform: translateY(35px);
    }

    100% {
        opacity: 1;
        transform: translateY(0);
    }
}
</style>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\0_Project_VS_Code\3. Sistem Basis Data\TB-K1-Database\lapak_gaming\resources\views\wallet\index.blade.php ENDPATH**/ ?>
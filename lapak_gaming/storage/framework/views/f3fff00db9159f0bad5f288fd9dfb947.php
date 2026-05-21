<?php $__env->startSection('content'); ?>
<div class="min-h-screen bg-[#050816] px-4 py-14">

    
    <style>
        .reveal {
            opacity: 0;
            transform: translateY(30px);
            animation: revealUp .8s ease forwards;
        }

        .reveal-delay-1 {
            animation-delay: .15s;
        }

        .reveal-delay-2 {
            animation-delay: .3s;
        }

        @keyframes revealUp {
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>

    <div class="mx-auto max-w-3xl">

        
        <div
            class="reveal relative overflow-hidden rounded-[30px] border border-blue-500/20 bg-gradient-to-br from-[#060816] via-[#091225] to-[#0B1730] px-7 py-8 shadow-[0_0_70px_rgba(37,99,235,0.12)]">

            <div
                class="absolute inset-0 bg-[radial-gradient(circle_at_top_right,rgba(37,99,235,0.16),transparent_35%)]">
            </div>

            <div class="relative z-10 flex items-center justify-between gap-6">

                <div>
                    <div
                        class="inline-flex items-center gap-2 rounded-full border border-blue-500/30 bg-blue-500/10 px-4 py-2 text-[11px] font-bold tracking-[0.18em] text-blue-300 backdrop-blur-xl">
                        <span class="h-2 w-2 rounded-full bg-emerald-400 animate-pulse"></span>
                        PROFILE SETTINGS
                    </div>

                    <h1 class="mt-5 text-3xl font-black text-white md:text-4xl">
                        Edit Profile
                    </h1>

                    <p class="mt-3 max-w-xl text-sm leading-relaxed text-slate-300">
                        Ubah informasi akunmu dengan tampilan dashboard modern ala Lapak Gaming.
                    </p>
                </div>

                
                <div
                    class="hidden md:flex relative h-[130px] w-[130px] items-center justify-center rounded-full border border-blue-500/20 bg-blue-500/5 backdrop-blur-2xl">

                    <div
                        class="absolute h-[170px] w-[170px] rounded-full bg-blue-500/10 blur-3xl">
                    </div>

                    <img src="<?php echo e(asset('storage/app/public/logo/logo.png')); ?>"
                        alt="Logo"
                        class="relative z-10 h-24 w-24 object-contain drop-shadow-[0_0_25px_rgba(59,130,246,0.55)]">
                </div>

            </div>
        </div>

        
        <div
            class="reveal reveal-delay-1 mt-7 overflow-hidden rounded-[30px] border border-white/10 bg-[#0B1220]/95 p-7 shadow-[0_0_50px_rgba(37,99,235,0.05)] backdrop-blur-xl">

            
            <?php if($errors->any()): ?>
            <div
                class="mb-6 rounded-2xl border border-red-500/20 bg-red-500/10 p-5">

                <p class="mb-3 text-sm font-bold tracking-wide text-red-300">
                    Please fix the following errors:
                </p>

                <ul class="space-y-1 text-sm text-red-200">
                    <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li>• <?php echo e($error); ?></li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </ul>
            </div>
            <?php endif; ?>

            <form action="<?php echo e(route('profile.update')); ?>"
                method="POST"
                class="space-y-6">

                <?php echo csrf_field(); ?>
                <?php echo method_field('PUT'); ?>

                
                <div class="reveal reveal-delay-1">
                    <label
                        for="name"
                        class="mb-2 block text-sm font-semibold tracking-wide text-slate-300">
                        Full Name
                    </label>

                    <input type="text"
                        name="name"
                        id="name"
                        value="<?php echo e(old('name', $user->name)); ?>"
                        required
                        class="w-full rounded-2xl border border-white/10 bg-[#111827]/90 px-5 py-3 text-white placeholder-slate-500 transition duration-300 focus:border-blue-500/40 focus:bg-[#131d31] focus:outline-none focus:ring-2 focus:ring-blue-500/10">

                    <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <p class="mt-2 text-sm text-red-400">
                        <?php echo e($message); ?>

                    </p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                
                <div class="reveal reveal-delay-1">
                    <label
                        for="email"
                        class="mb-2 block text-sm font-semibold tracking-wide text-slate-300">
                        Email Address
                    </label>

                    <input type="email"
                        name="email"
                        id="email"
                        value="<?php echo e(old('email', $user->email)); ?>"
                        required
                        class="w-full rounded-2xl border border-white/10 bg-[#111827]/90 px-5 py-3 text-white placeholder-slate-500 transition duration-300 focus:border-blue-500/40 focus:bg-[#131d31] focus:outline-none focus:ring-2 focus:ring-blue-500/10">

                    <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <p class="mt-2 text-sm text-red-400">
                        <?php echo e($message); ?>

                    </p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                
                <div class="reveal reveal-delay-2">
                    <label
                        for="phone"
                        class="mb-2 block text-sm font-semibold tracking-wide text-slate-300">
                        Phone Number (Optional)
                    </label>

                    <input type="tel"
                        name="phone"
                        id="phone"
                        value="<?php echo e(old('phone', $user->phone ?? '')); ?>"
                        placeholder="+62..."
                        class="w-full rounded-2xl border border-white/10 bg-[#111827]/90 px-5 py-3 text-white placeholder-slate-500 transition duration-300 focus:border-blue-500/40 focus:bg-[#131d31] focus:outline-none focus:ring-2 focus:ring-blue-500/10">

                    <?php $__errorArgs = ['phone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <p class="mt-2 text-sm text-red-400">
                        <?php echo e($message); ?>

                    </p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                
                <div
                    class="reveal reveal-delay-2 flex flex-col gap-3 border-t border-white/10 pt-6 sm:flex-row">

                    <button type="submit"
                        class="group flex-1 rounded-2xl border border-blue-500/30 bg-gradient-to-r from-blue-600 to-blue-500 px-5 py-3 text-sm font-bold tracking-wide text-white transition duration-300 hover:-translate-y-1 hover:shadow-[0_0_25px_rgba(59,130,246,0.35)]">

                        <span class="flex items-center justify-center gap-2">
                            Save Changes
                        </span>
                    </button>

                    <a href="<?php echo e(route('profile.show')); ?>"
                        class="flex-1 rounded-2xl border border-white/10 bg-white/[0.03] px-5 py-3 text-center text-sm font-semibold text-slate-300 transition duration-300 hover:-translate-y-1 hover:border-white/20 hover:bg-white/[0.05] hover:text-white">

                        Cancel
                    </a>
                </div>

            </form>
        </div>

    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\0_Project_VS_Code\3. Sistem Basis Data\TB-K1-Database\lapak_gaming\resources\views\profile\edit.blade.php ENDPATH**/ ?>
<?php $__env->startSection('title', 'Kelola Banner — Admin'); ?>

<?php $__env->startPush('styles'); ?>
<style>
    /* ── True Glassmorphism Control Panel ─────────────────────── */
    .dashboard-transparent {
        background: transparent !important;
    }
    
    .panel-card-glass {
        background: rgba(10, 17, 30, 0.35) !important; /* Transparansi murni 35% */
        backdrop-filter: blur(24px) saturate(160%);
        border: 1px solid rgba(255, 255, 255, 0.06);
        box-shadow: 0 12px 40px 0 rgba(0, 0, 0, 0.5);
    }

    .input-glass {
        background: rgba(5, 9, 16, 0.45) !important;
        border: 1px solid rgba(255, 255, 255, 0.08);
        transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .input-glass:focus {
        border-color: rgba(245, 158, 11, 0.5) !important;
        box-shadow: 0 0 14px rgba(245, 158, 11, 0.15);
    }
    .input-glass option {
        background: #0d1421;
        color: #e2e8f0;
    }

    /* ── Cyber Badge Overlays ────────────────────────────────── */
    .floating-badge {
        backdrop-filter: blur(8px);
        font-[900] !important;
        letter-spacing: 0.05em;
        text-transform: uppercase;
    }

    .pill-active { background: rgba(16, 185, 129, 0.2); border: 1px solid rgba(16, 185, 129, 0.4); color: #34d399; }
    .pill-suspended { background: rgba(239, 68, 68, 0.2); border: 1px solid rgba(239, 68, 68, 0.4); color: #f87171; }
    .pill-position { background: rgba(245, 158, 11, 0.2); border: 1px solid rgba(245, 158, 11, 0.4); color: #fbbf24; }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<div class="min-h-screen py-10 relative overflow-hidden dashboard-transparent">
    
    <div class="absolute top-0 right-1/4 w-96 h-96 bg-brand-500/5 rounded-full blur-[150px] pointer-events-none"></div>
    <div class="absolute bottom-0 left-1/4 w-96 h-96 bg-amber-500/5 rounded-full blur-[150px] pointer-events-none"></div>

    <div class="max-w-7xl mx-auto px-4 space-y-6 relative z-10">

        
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between border-b border-white/5 pb-5">
            <div>
                <div class="flex items-center gap-2 mb-1.5">
                    <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span>
                    <span class="text-xs font-bold uppercase tracking-widest text-amber-500/80">Marketplace Campaign Engine</span>
                </div>
                <h1 class="text-3xl font-extrabold text-white tracking-tight">Kelola Banner Iklan</h1>
                <p class="text-slate-400 text-sm mt-0.5">Atur baliho promo, event top-up, dan spanduk penawaran utama di halaman depan web.</p>
            </div>
            <a href="<?php echo e(route('admin.dashboard')); ?>"
               class="inline-flex items-center gap-2 rounded-xl bg-white/5 hover:bg-white/10 border border-white/5 px-4 py-2.5 text-xs font-bold text-slate-300 transition-all tracking-wide self-start sm:self-auto">
                Dashboard
            </a>
        </div>

        
        <?php if(session('success')): ?>
            <div class="flex items-start gap-3 rounded-2xl border border-emerald-500/30 bg-emerald-500/5 p-4 backdrop-blur-md">
                <svg class="w-5 h-5 text-emerald-400 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <p class="text-sm font-medium text-emerald-300"><?php echo e(session('success')); ?></p>
            </div>
        <?php endif; ?>

        <?php if($errors->any()): ?>
            <div class="rounded-2xl border border-rose-500/30 bg-rose-500/5 p-4 backdrop-blur-md">
                <div class="flex items-start gap-3">
                    <svg class="w-5 h-5 text-rose-400 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" w-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <div>
                        <h3 class="text-sm font-bold text-rose-300">Gagal memproses berkas banner:</h3>
                        <ul class="mt-1.5 space-y-1 text-xs text-rose-400/90 font-medium">
                            <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <li>• <?php echo e($error); ?></li>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </ul>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        
        <form action="<?php echo e(route('admin.banners.store')); ?>" method="POST" enctype="multipart/form-data" 
              class="panel-card-glass rounded-3xl p-6 grid gap-5 lg:grid-cols-2">
            <?php echo csrf_field(); ?>
            
            <div class="lg:col-span-2 border-b border-white/5 pb-2">
                <h2 class="text-sm font-bold text-white uppercase tracking-wider flex items-center gap-2">
                    <span>➕</span> Daftarkan Material Promosi Baru
                </h2>
            </div>

            
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-400 mb-1.5">Judul Banner <span class="text-amber-500">*</span></label>
                <input name="title" type="text" 
                       class="w-full rounded-xl input-glass px-4 py-3 text-sm text-white placeholder:text-slate-600 outline-none <?php $__errorArgs = ['title'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500/40 bg-red-500/5 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                       placeholder="Contoh: Mega Flash Sale Ramadhan" value="<?php echo e(old('title')); ?>" required>
                <?php $__errorArgs = ['title'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="mt-1 text-[11px] text-red-400 font-medium"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-400 mb-1.5">Subjudul / Deskripsi Pendek</label>
                <input name="subtitle" type="text" 
                       class="w-full rounded-xl input-glass px-4 py-3 text-sm text-white placeholder:text-slate-600 outline-none" 
                       placeholder="Contoh: Diskon Top-up s.d 80% All Games" value="<?php echo e(old('subtitle')); ?>">
                <?php $__errorArgs = ['subtitle'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="mt-1 text-[11px] text-red-400 font-medium"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-400 mb-1.5">Unggah File Gambar</label>
                <input name="image" type="file" accept="image/*" 
                       class="w-full rounded-xl input-glass px-3 py-2 text-xs text-slate-400 outline-none file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-white/10 file:text-white file:hover:bg-white/20 file:transition-colors <?php $__errorArgs = ['image'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500/40 bg-red-500/5 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                <p class="mt-1.5 text-[10px] text-slate-500 leading-relaxed">Format: JPG, PNG, WebP. Maks 5MB.<br>Rekomendasi rasio -> Hero: 4:5 portrait | Featured: 3:1 landscape.</p>
                <?php $__errorArgs = ['image'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="mt-1 text-[11px] text-red-400 font-medium"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-400 mb-1.5">Atau Pasang URL Gambar Eksternal</label>
                <input name="image_url" type="url" 
                       class="w-full rounded-xl input-glass px-4 py-3 text-sm text-white placeholder:text-slate-600 outline-none <?php $__errorArgs = ['image_url'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500/40 bg-red-500/5 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                       placeholder="https://domain-hosting-gambar.com/foto.webp" value="<?php echo e(old('image_url')); ?>">
                <p class="mt-1.5 text-[10px] text-slate-500 leading-relaxed">Gunakan opsi ini jika aset gambar di-host di luar server aplikasi (Bypass Upload).</p>
                <?php $__errorArgs = ['image_url'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="mt-1 text-[11px] text-red-400 font-medium"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-400 mb-1.5">URL Link Tujuan (Redirect Target)</label>
                <input name="link_url" type="url" 
                       class="w-full rounded-xl input-glass px-4 py-3 text-sm text-white placeholder:text-slate-600 outline-none <?php $__errorArgs = ['link_url'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500/40 bg-red-500/5 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                       placeholder="https://lapakgaming.neoverse.my.id/marketplace/trending" value="<?php echo e(old('link_url')); ?>">
                <?php $__errorArgs = ['link_url'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="mt-1 text-[11px] text-red-400 font-medium"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-400 mb-1.5">Posisi Layout Banner</label>
                <select name="position" class="w-full rounded-xl input-glass px-4 py-3 text-sm text-white outline-none <?php $__errorArgs = ['position'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500/40 bg-red-500/5 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                    <option value="hero" <?php if(old('position') === 'hero'): echo 'selected'; endif; ?>>Slotted: Hero Banner (Halaman Utama Atas)</option>
                    <option value="featured" <?php if(old('position') === 'featured'): echo 'selected'; endif; ?>>Slotted: Featured Promo (Tengah Beranda)</option>
                </select>
                <?php $__errorArgs = ['position'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="mt-1 text-[11px] text-red-400 font-medium"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            
            <div class="lg:col-span-2 border-t border-white/5 pt-4 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <label class="inline-flex items-center gap-3 cursor-pointer group w-max">
                    <input type="checkbox" name="is_active" value="1" class="w-4 h-4 rounded border-white/10 bg-black/40 text-amber-500 focus:ring-0 focus:ring-offset-0" <?php if(old('is_active', true)): ?> checked <?php endif; ?>>
                    <span class="text-xs font-bold uppercase tracking-wider text-slate-400 group-hover:text-white transition-colors">Setel Status Aktif Langsung</span>
                </label>
                
                <button type="submit" 
                        class="w-full sm:w-auto rounded-xl bg-gradient-to-r from-amber-500 to-orange-500 hover:from-amber-400 hover:to-orange-400 px-6 py-3 text-xs font-bold text-slate-950 transition-all shadow-md shadow-amber-500/10 hover:scale-[1.01]">
                    PUBLIKASIKAN BANNER
                </button>
            </div>
        </form>

        
        <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
            <?php $__empty_1 = true; $__currentLoopData = $banners; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $banner): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <article class="panel-card-glass rounded-3xl overflow-hidden flex flex-col group hover:scale-[1.01] hover:border-white/10 transition-all duration-300">
                    
                    
                    <div class="relative h-44 w-full bg-black/40 overflow-hidden shrink-0 border-b border-white/5">
                        
                        
                        <img src="<?php echo e($banner->image_url); ?>" 
                             alt="<?php echo e($banner->title); ?>" 
                             class="h-full w-full object-cover group-hover:scale-105 transition-transform duration-500"
                             onerror="this.onerror=null; this.src='https://placehold.co/600x400/0a111e/94a3b8?text=Image+Missing';">
                        
                        
                        <div class="absolute top-3 left-3">
                            <span class="floating-badge text-[10px] font-extrabold px-2.5 py-1 rounded-md pill-position">
                                📍 <?php echo e($banner->position); ?>

                            </span>
                        </div>
                        <div class="absolute top-3 right-3">
                            <span class="floating-badge text-[10px] font-extrabold px-2.5 py-1 rounded-md <?php echo e($banner->is_active ? 'pill-active' : 'pill-suspended'); ?>">
                                <?php echo e($banner->is_active ? '⚡ Aktif' : '🔒 Off'); ?>

                            </span>
                        </div>
                    </div>

                    
                    <div class="p-5 flex-1 flex flex-col justify-between space-y-4">
                        <div class="space-y-1 min-w-0">
                            <h2 class="text-base font-extrabold text-white tracking-tight truncate" title="<?php echo e($banner->title); ?>">
                                <?php echo e($banner->title); ?>

                            </h2>
                            <p class="text-xs text-slate-400 leading-relaxed line-clamp-2 min-h-[32px]">
                                <?php echo e($banner->subtitle ?? 'Tidak ada deskripsi subjudul.'); ?>

                            </p>
                        </div>

                        
                        <form action="<?php echo e(route('admin.banners.destroy', $banner)); ?>" method="POST"
                              onsubmit="return confirm('Hapus baliho kampanye \'<?php echo e(addslashes($banner->title)); ?>\'?')">
                            <?php echo csrf_field(); ?>
                            <?php echo method_field('DELETE'); ?>
                            <button type="submit" 
                                    class="w-full inline-flex items-center justify-center gap-1.5 rounded-xl border border-rose-500/30 bg-rose-500/10 px-4 py-2.5 text-xs font-bold text-rose-400 hover:bg-rose-600 hover:text-white transition-all">
                                <svg class="w-3.5 h-3.5 stroke-[2.5]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                </svg>
                                COPOT BANNER IKLAN
                            </button>
                        </form>
                    </div>

                </article>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <div class="md:col-span-2 lg:col-span-3 rounded-3xl panel-card-glass py-16 text-center text-slate-500">
                    <div class="text-4xl mb-3">🖼️</div>
                    <p class="font-bold text-slate-400">Belum ada material promosi aktif.</p>
                    <p class="text-xs text-slate-600 mt-1">Gunakan panel formulir di atas untuk mempublikasikan banner pertamamu lek.</p>
                </div>
            <?php endif; ?>
        </div>

    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\0_Project_VS_Code\3. Sistem Basis Data\TB-K1-Database\lapak_gaming\resources\views\admin\banners\index.blade.php ENDPATH**/ ?>
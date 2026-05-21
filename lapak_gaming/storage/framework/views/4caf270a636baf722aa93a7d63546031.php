<?php $__env->startSection('title', 'Verifikasi Email — Lapak Gaming'); ?>

<?php $__env->startPush('styles'); ?>
<style>
  .auth-radial-ve {
    background: radial-gradient(ellipse 65% 50% at 50% -5%,
      rgba(16,185,129,0.15) 0%, rgba(37,99,235,0.08) 50%, transparent 100%);
  }
  .auth-card-ve {
    position: relative;
    background: linear-gradient(145deg, #0D1421 0%, #0A1120 100%);
    border-radius: 20px; overflow: hidden;
  }
  .auth-card-ve::before {
    content: '';
    position: absolute; inset: 0; border-radius: 20px; padding: 1px;
    background: linear-gradient(135deg, rgba(16,185,129,0.4) 0%, rgba(37,99,235,0.15) 50%, rgba(249,115,22,0.2) 100%);
    -webkit-mask: linear-gradient(#fff 0 0) content-box, linear-gradient(#fff 0 0);
    mask: linear-gradient(#fff 0 0) content-box, linear-gradient(#fff 0 0);
    -webkit-mask-composite: xor; mask-composite: exclude;
    pointer-events: none;
  }
  .auth-card-ve::after {
    content: '';
    position: absolute; top: 0; left: 50%; transform: translateX(-50%);
    width: 70%; height: 1px;
    background: linear-gradient(90deg, transparent, rgba(16,185,129,0.45), transparent);
    pointer-events: none;
  }

  /* ── Envelope animation ── */
  .envelope-wrap {
    position: relative; width: 80px; height: 80px;
    display: flex; align-items: center; justify-content: center;
  }
  .envelope-ring {
    position: absolute; inset: 0; border-radius: 50%;
    border: 1.5px solid rgba(16,185,129,0.2);
    animation: envPulse 2.5s ease-in-out infinite;
  }
  .envelope-ring-2 {
    position: absolute; inset: -10px; border-radius: 50%;
    border: 1px solid rgba(16,185,129,0.1);
    animation: envPulse 2.5s ease-in-out 0.8s infinite;
  }
  @keyframes envPulse {
    0%, 100% { transform: scale(1); opacity: 0.5; }
    50%       { transform: scale(1.08); opacity: 0.15; }
  }
  .envelope-core {
    width: 60px; height: 60px; border-radius: 50%;
    background: rgba(16,185,129,0.12);
    border: 1px solid rgba(16,185,129,0.3);
    display: flex; align-items: center; justify-content: center;
    position: relative; z-index: 1;
  }

  /* ── Info box ── */
  .info-box {
    padding: 14px 16px; border-radius: 12px;
    background: rgba(37,99,235,0.06);
    border: 1px solid rgba(37,99,235,0.18);
  }

  /* ── Resend button ── */
  .btn-resend {
    position: relative; overflow: hidden;
    display: flex; align-items: center; justify-content: center; gap: 8px;
    width: 100%; padding: 0.875rem 1.5rem;
    border-radius: 12px;
    font-family: 'Oxanium', sans-serif; font-weight: 700; font-size: 0.9rem;
    color: white;
    background: linear-gradient(135deg, #059669 0%, #10b981 100%);
    border: none; cursor: pointer; transition: all 0.25s;
  }
  .btn-resend:hover { box-shadow: 0 0 24px rgba(16,185,129,0.4); transform: translateY(-1px); }
  .btn-resend:active { transform: scale(0.99); }

  /* ── Status banner ── */
  .status-banner {
    display: flex; align-items: flex-start; gap: 10px;
    padding: 12px 14px; border-radius: 12px;
    background: rgba(16,185,129,0.08); border: 1px solid rgba(16,185,129,0.22);
    animation: fadeIn 0.25s ease-out;
  }

  /* ── Progress dots ── */
  .step-row {
    display: flex; align-items: center; gap: 0;
    margin-bottom: 1.5rem;
  }
  .step-dot {
    width: 28px; height: 28px; border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    font-family: 'Oxanium', sans-serif; font-size: 0.7rem; font-weight: 800;
    flex-shrink: 0;
  }
  .step-dot.done { background: rgba(37,99,235,0.2); border: 1.5px solid rgba(37,99,235,0.4); color: #60a5fa; }
  .step-dot.active { background: rgba(16,185,129,0.2); border: 1.5px solid rgba(16,185,129,0.5); color: #34d399; animation: dotPulse 2s ease-in-out infinite; }
  .step-dot.pending { background: rgba(255,255,255,0.04); border: 1.5px solid rgba(255,255,255,0.1); color: #475569; }
  @keyframes dotPulse { 0%,100%{box-shadow:0 0 0 0 rgba(16,185,129,0.3);} 50%{box-shadow:0 0 0 6px rgba(16,185,129,0);} }
  .step-line { flex: 1; height: 1.5px; background: linear-gradient(90deg, rgba(37,99,235,0.3), rgba(16,185,129,0.15)); }
  .step-line.pending-line { background: rgba(255,255,255,0.07); }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<div class="relative min-h-screen flex items-center justify-center py-16 px-4 overflow-hidden">
  <div class="auth-radial-ve absolute inset-0 pointer-events-none"></div>

  <div class="w-full max-w-[420px] animate-fade-up relative z-10">

    
    <div class="text-center mb-8">
      <a href="<?php echo e(route('marketplace.home')); ?>" class="inline-flex items-center gap-2.5 mb-5 group">
        <div class="relative">
          <div class="absolute inset-0 rounded-xl bg-emerald-600/20 blur-md"></div>
          <img src="<?php echo e(url('storage/app/public/logo/logo.png')); ?>" alt="Lapak Gaming"
               class="relative w-11 h-11 rounded-xl object-contain bg-white/5 p-1 border border-white/10">
        </div>
        <span class="font-display font-bold text-xl text-white group-hover:text-emerald-300 transition-colors">
          <?php echo e(config('app.name', 'Lapak Gaming')); ?>

        </span>
      </a>
    </div>

    
    <div class="auth-card-ve p-8">

      
      <div class="flex justify-center mb-6">
        <div class="envelope-wrap">
          <div class="envelope-ring"></div>
          <div class="envelope-ring-2"></div>
          <div class="envelope-core">
            <svg class="w-7 h-7 text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75"
                    d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
            </svg>
          </div>
        </div>
      </div>

      
      <div class="text-center mb-6">
        <h1 class="font-display text-2xl font-extrabold text-white mb-2 tracking-tight">Cek Email Kamu</h1>
        <p class="text-slate-400 text-sm leading-relaxed">
          Kami mengirim link verifikasi ke email-mu.<br>
          Klik link tersebut untuk mengaktifkan akun.
        </p>
      </div>

      
      <div class="step-row">
        <div class="step-dot done" title="Akun dibuat">✓</div>
        <div class="step-line"></div>
        <div class="step-dot active" title="Verifikasi email">✉</div>
        <div class="step-line pending-line"></div>
        <div class="step-dot pending" title="Akses penuh">★</div>
      </div>
      <div class="flex justify-between text-xs text-slate-600 mb-6 -mt-2 px-0.5">
        <span>Akun dibuat</span>
        <span class="text-emerald-500">Verifikasi email</span>
        <span>Akses penuh</span>
      </div>

      
      <?php if(session('status') || session('warning') || $errors->any()): ?>
      <div class="status-banner mb-5">
        <svg class="w-4 h-4 text-emerald-400 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
        </svg>
        <div class="text-sm">
          <?php if(session('status')): ?>
            <p class="text-emerald-300"><?php echo e(session('status')); ?></p>
          <?php endif; ?>
          <?php if(session('warning')): ?>
            <p class="text-yellow-300"><?php echo e(session('warning')); ?></p>
          <?php endif; ?>
          <?php if($errors->any()): ?>
            <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
              <p class="text-yellow-300"><?php echo e($error); ?></p>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
          <?php endif; ?>
        </div>
      </div>
      <?php endif; ?>

      
      <div class="info-box mb-5">
        <div class="flex items-start gap-3">
          <svg class="w-4 h-4 text-brand-400 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
          </svg>
          <div>
            <p class="text-xs text-slate-300 leading-relaxed">
              Link verifikasi berlaku selama <strong class="text-white">24 jam</strong>.
              Jika tidak ada di inbox, periksa folder <strong class="text-white">Spam/Junk</strong>.
            </p>
          </div>
        </div>
      </div>

      
      <form method="POST" action="<?php echo e(route('verification.send')); ?>">
        <?php echo csrf_field(); ?>
        <button type="submit" class="btn-resend">
          <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
          </svg>
          Kirim Ulang Email Verifikasi
        </button>
      </form>

      
      <div class="relative my-5">
        <div class="absolute inset-0 flex items-center">
          <div class="w-full border-t border-surface-600"></div>
        </div>
        <div class="relative flex justify-center">
          <span class="bg-[#0D1421] px-3 text-xs text-slate-600">sudah verifikasi?</span>
        </div>
      </div>

      
      <a href="<?php echo e(route('dashboard')); ?>"
         class="flex items-center justify-center gap-2 w-full py-3 px-4 rounded-xl border border-surface-600 bg-surface-900/50 text-sm text-slate-300 font-semibold hover:border-brand-600/50 hover:bg-surface-800/50 hover:text-white transition-all">
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        Lanjut ke Dashboard
        <svg class="w-3.5 h-3.5 ml-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
        </svg>
      </a>

      
      <div class="text-center mt-5">
        <form method="POST" action="<?php echo e(route('logout')); ?>" class="inline">
          <?php echo csrf_field(); ?>
          <button type="submit" class="text-xs text-slate-600 hover:text-slate-400 transition-colors">
            Keluar dan masuk dengan akun lain
          </button>
        </form>
      </div>

    </div>
  </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\0_Project_VS_Code\3. Sistem Basis Data\TB-K1-Database\lapak_gaming\resources\views\auth\verify-email.blade.php ENDPATH**/ ?>
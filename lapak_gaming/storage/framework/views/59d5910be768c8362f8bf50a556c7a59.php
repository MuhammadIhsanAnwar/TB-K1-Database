<?php $__env->startSection('title', 'Reset Password — Lapak Gaming'); ?>

<?php $__env->startPush('styles'); ?>
<style>
  .auth-radial-rp {
    background: radial-gradient(ellipse 65% 50% at 50% -5%,
      rgba(37,99,235,0.2) 0%, rgba(16,185,129,0.06) 50%, transparent 100%);
  }
  .auth-card-rp {
    position: relative;
    background: linear-gradient(145deg, #0D1421 0%, #0A1120 100%);
    border-radius: 20px; overflow: hidden;
  }
  .auth-card-rp::before {
    content: '';
    position: absolute; inset: 0; border-radius: 20px; padding: 1px;
    background: linear-gradient(135deg, rgba(16,185,129,0.4) 0%, rgba(37,99,235,0.2) 50%, rgba(249,115,22,0.3) 100%);
    -webkit-mask: linear-gradient(#fff 0 0) content-box, linear-gradient(#fff 0 0);
    mask: linear-gradient(#fff 0 0) content-box, linear-gradient(#fff 0 0);
    -webkit-mask-composite: xor; mask-composite: exclude;
    pointer-events: none;
  }
  .auth-card-rp::after {
    content: '';
    position: absolute; top: 0; left: 50%; transform: translateX(-50%);
    width: 70%; height: 1px;
    background: linear-gradient(90deg, transparent, rgba(16,185,129,0.4), transparent);
    pointer-events: none;
  }

  /* ── Icon ring ── */
  .icon-ring-rp {
    width: 64px; height: 64px; border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    position: relative;
    background: rgba(16,185,129,0.1); border: 1px solid rgba(16,185,129,0.3);
  }

  /* ── Input icon ── */
  .input-icon-wrap { position: relative; }
  .input-icon-wrap .input-icon {
    position: absolute; left: 14px; top: 50%; transform: translateY(-50%);
    color: #475569; pointer-events: none; transition: color 0.2s;
    width: 16px; height: 16px;
  }
  .input-icon-wrap:focus-within .input-icon { color: #2563eb; }
  .input-icon-wrap input { padding-left: 2.75rem; }

  /* ── Eye toggle ── */
  .pwd-toggle {
    position: absolute; right: 12px; top: 50%; transform: translateY(-50%);
    color: #475569; background: none; border: none; cursor: pointer;
    padding: 4px; border-radius: 6px; transition: color 0.2s, background 0.2s;
  }
  .pwd-toggle:hover { color: #94a3b8; background: rgba(255,255,255,0.05); }

  /* ── Password strength ── */
  .strength-bar-track {
    height: 4px; border-radius: 99px; background: #1E2D45; overflow: hidden; margin-top: 8px;
  }
  .strength-bar-fill {
    height: 100%; border-radius: 99px;
    transition: width 0.4s ease, background-color 0.4s ease; width: 0%;
  }

  /* ── Rules ── */
  .pwd-rule {
    display: flex; align-items: center; gap: 8px;
    font-size: 0.75rem; color: #64748b; transition: color 0.2s;
  }
  .pwd-rule .rule-dot {
    width: 6px; height: 6px; border-radius: 50%;
    background: #334155; flex-shrink: 0;
    transition: background-color 0.2s, transform 0.2s;
  }
  .pwd-rule.ok .rule-dot { background: #10b981; transform: scale(1.2); }
  .pwd-rule.ok { color: #34d399; }
  .pwd-rule.fail .rule-dot { background: #ef4444; }
  .pwd-rule.fail { color: #f87171; }

  /* ── Match indicator ── */
  .match-ok  { color: #34d399; font-size: 0.75rem; display: none; align-items: center; gap: 6px; margin-top: 6px; }
  .match-err { color: #f87171; font-size: 0.75rem; display: none; align-items: center; gap: 6px; margin-top: 6px; }

  /* ── Error banner ── */
  .error-banner {
    display: flex; align-items: flex-start; gap: 10px;
    padding: 12px 14px; border-radius: 12px;
    background: rgba(239,68,68,0.08); border: 1px solid rgba(239,68,68,0.22);
    animation: fadeIn 0.25s ease-out;
  }

  /* ── Submit button ── */
  .btn-rp {
    position: relative; overflow: hidden;
    display: flex; align-items: center; justify-content: center; gap: 8px;
    width: 100%; padding: 0.9rem 1.5rem;
    border-radius: 12px;
    font-family: 'Oxanium', sans-serif; font-weight: 700; font-size: 0.9375rem;
    color: white;
    background: linear-gradient(135deg, #059669 0%, #10b981 50%, #2563eb 100%);
    background-size: 200% 100%; background-position: 0% 0%;
    border: none; cursor: pointer; transition: all 0.3s;
  }
  .btn-rp:hover {
    background-position: 100% 0%;
    box-shadow: 0 0 28px rgba(16,185,129,0.4);
    transform: translateY(-1px);
  }
  .btn-rp:active { transform: scale(0.99); }
  .btn-rp:disabled { opacity: 0.55; cursor: not-allowed; transform: none; }

  .btn-spinner { display: none; width: 18px; height: 18px; border: 2px solid rgba(255,255,255,0.25); border-top-color: white; border-radius: 50%; animation: spin 0.7s linear infinite; }
  @keyframes spin { to { transform: rotate(360deg); } }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<div class="relative min-h-screen flex items-center justify-center py-16 px-4 overflow-hidden">
  <div class="auth-radial-rp absolute inset-0 pointer-events-none"></div>

  <div class="w-full max-w-[440px] animate-fade-up relative z-10">

    
    <div class="text-center mb-8">
      <a href="<?php echo e(route('marketplace.home')); ?>" class="inline-flex items-center gap-2.5 mb-5 group">
        <div class="relative">
          <div class="absolute inset-0 rounded-xl bg-brand-600/30 blur-md"></div>
          <img src="<?php echo e(url('storage/app/public/logo/logo.png')); ?>" alt="Lapak Gaming"
               class="relative w-11 h-11 rounded-xl object-contain bg-white/5 p-1 border border-white/10">
        </div>
        <span class="font-display font-bold text-xl text-white group-hover:text-brand-300 transition-colors">
          <?php echo e(config('app.name', 'Lapak Gaming')); ?>

        </span>
      </a>
    </div>

    
    <div class="auth-card-rp p-8">

      
      <div class="flex justify-center mb-5">
        <div class="icon-ring-rp">
          <svg class="w-7 h-7 text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75"
                  d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
          </svg>
        </div>
      </div>

      
      <div class="text-center mb-6">
        <h1 class="font-display text-2xl font-extrabold text-white mb-2 tracking-tight">Buat Password Baru</h1>
        <p class="text-slate-400 text-sm">Masukkan password baru yang kuat untuk akun kamu</p>
      </div>

      
      <?php if($errors->any()): ?>
      <div class="error-banner mb-5">
        <svg class="w-4 h-4 text-red-400 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        <div>
          <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <p class="text-sm text-red-300"><?php echo e($error); ?></p>
          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
      </div>
      <?php endif; ?>

      
      <form method="POST" action="<?php echo e(route('password.update')); ?>" id="rp-form" class="space-y-4">
        <?php echo csrf_field(); ?>

        
        <input type="hidden" name="token" value="<?php echo e($token); ?>">

        
        <div>
          <label for="email" class="block text-xs font-semibold text-slate-400 mb-1.5 tracking-wide">ALAMAT EMAIL</label>
          <div class="input-icon-wrap">
            <svg class="input-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75"
                    d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
            </svg>
            <input name="email" id="email" type="email"
                   value="<?php echo e(old('email', request('email'))); ?>"
                   placeholder="nama@email.com"
                   class="input <?php echo e($errors->has('email') ? 'border-red-500/50' : ''); ?>"
                   required autocomplete="email">
          </div>
          <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="mt-1 text-xs text-red-400"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>

        
        <div>
          <label for="password" class="block text-xs font-semibold text-slate-400 mb-1.5 tracking-wide">PASSWORD BARU</label>
          <div class="input-icon-wrap relative">
            <svg class="input-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75"
                    d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
            </svg>
            <input id="password" name="password" type="password"
                   placeholder="Minimal 8 karakter"
                   class="input pr-11 pl-10
                          <?php echo e($errors->has('password') ? 'border-red-500/50' : ''); ?>"
                   required autocomplete="new-password">
            <button type="button" onclick="togglePwd('password', this)" class="pwd-toggle" aria-label="Toggle">
              <svg class="eye-off w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0zM2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
              <svg class="eye-on w-4 h-4 hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/></svg>
            </button>
          </div>

          
          <div class="strength-bar-track">
            <div class="strength-bar-fill" id="strength-bar"></div>
          </div>
          <p class="text-xs mt-1.5" id="strength-label" style="color:#475569;">Masukkan password baru</p>

          
          <div class="grid grid-cols-2 gap-1.5 mt-3" id="password-requirements">
            <div class="pwd-rule" data-rule="length"><span class="rule-dot"></span> Min. 8 karakter</div>
            <div class="pwd-rule" data-rule="lower"><span class="rule-dot"></span> Huruf kecil (a-z)</div>
            <div class="pwd-rule" data-rule="upper"><span class="rule-dot"></span> Huruf besar (A-Z)</div>
            <div class="pwd-rule" data-rule="number"><span class="rule-dot"></span> Angka (0-9)</div>
            <div class="pwd-rule col-span-2" data-rule="symbol"><span class="rule-dot"></span> Simbol (!@#$%...)</div>
          </div>
          <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="mt-1 text-xs text-red-400"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>

        
        <div>
          <label for="password_confirmation" class="block text-xs font-semibold text-slate-400 mb-1.5 tracking-wide">KONFIRMASI PASSWORD</label>
          <div class="input-icon-wrap relative">
            <svg class="input-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75"
                    d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
            </svg>
            <input id="password_confirmation" name="password_confirmation" type="password"
                   placeholder="Ulangi password baru"
                   class="input pr-11 pl-10" required autocomplete="new-password">
            <button type="button" onclick="togglePwd('password_confirmation', this)" class="pwd-toggle" aria-label="Toggle">
              <svg class="eye-off w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0zM2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
              <svg class="eye-on w-4 h-4 hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/></svg>
            </button>
          </div>
          <div class="match-ok" id="match-ok">
            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
            Password cocok
          </div>
          <div class="match-err" id="match-err">
            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
            Password tidak cocok
          </div>
        </div>

        
        <button type="submit" class="btn-rp mt-1" id="rp-btn">
          <span class="btn-text">Simpan Password Baru</span>
          <div class="btn-spinner" id="rp-spinner"></div>
          <svg class="btn-arr w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
          </svg>
        </button>
      </form>

      
      <div class="text-center mt-5">
        <a href="<?php echo e(route('login')); ?>" class="inline-flex items-center gap-1.5 text-sm text-slate-400 hover:text-white transition-colors">
          <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
          </svg>
          Kembali ke halaman masuk
        </a>
      </div>

    </div>
  </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
  // ── Password toggle ──
  window.togglePwd = function(id, btn) {
    const input = document.getElementById(id);
    const isHidden = input.type === 'password';
    input.type = isHidden ? 'text' : 'password';
    btn.querySelector('.eye-off').classList.toggle('hidden', isHidden);
    btn.querySelector('.eye-on').classList.toggle('hidden', !isHidden);
  };

  // ── Password strength ──
  const pwd = document.getElementById('password');
  const pwdConfirm = document.getElementById('password_confirmation');

  function checkRules(value) {
    return {
      length: value.length >= 8,
      lower:  /[a-z]/.test(value),
      upper:  /[A-Z]/.test(value),
      number: /[0-9]/.test(value),
      symbol: /[^A-Za-z0-9]/.test(value),
    };
  }

  function updateStrength(value) {
    const rules = checkRules(value);
    const passed = Object.values(rules).filter(Boolean).length;

    document.querySelectorAll('#password-requirements .pwd-rule').forEach((li) => {
      const rule = li.getAttribute('data-rule');
      li.classList.toggle('ok',   !!rules[rule]);
      li.classList.toggle('fail', value.length > 0 && !rules[rule]);
    });

    const bar = document.getElementById('strength-bar');
    const label = document.getElementById('strength-label');
    const configs = [
      { w:'0%',   color:'#1E2D45', text:'Masukkan password baru',  lc:'#475569' },
      { w:'20%',  color:'#ef4444', text:'Sangat lemah',             lc:'#f87171' },
      { w:'40%',  color:'#f97316', text:'Lemah',                    lc:'#fb923c' },
      { w:'60%',  color:'#eab308', text:'Cukup',                    lc:'#facc15' },
      { w:'80%',  color:'#3b82f6', text:'Kuat',                     lc:'#60a5fa' },
      { w:'100%', color:'#10b981', text:'✓ Sangat kuat',            lc:'#34d399' },
    ];
    const c = configs[passed];
    if (bar) { bar.style.width = c.w; bar.style.backgroundColor = c.color; }
    if (label) { label.textContent = c.text; label.style.color = c.lc; }
  }

  function checkMatch() {
    if (!pwdConfirm.value) {
      document.getElementById('match-ok').style.display = 'none';
      document.getElementById('match-err').style.display = 'none';
      return;
    }
    const match = pwd.value === pwdConfirm.value;
    document.getElementById('match-ok').style.display  = match ? 'flex' : 'none';
    document.getElementById('match-err').style.display = match ? 'none' : 'flex';
  }

  if (pwd) pwd.addEventListener('input', () => { updateStrength(pwd.value); checkMatch(); });
  if (pwdConfirm) pwdConfirm.addEventListener('input', checkMatch);

  // ── Form submit ──
  document.getElementById('rp-form').addEventListener('submit', function(e) {
    const rules = checkRules(pwd?.value || '');
    const allOk = Object.values(rules).every(Boolean);
    const mismatch = pwd && pwdConfirm && pwd.value !== pwdConfirm.value;

    if (!allOk || mismatch) {
      e.preventDefault();
      showToast(!allOk ? 'Password belum memenuhi syarat keamanan.' : 'Konfirmasi password tidak cocok.', 'error');
      return;
    }

    const btn = document.getElementById('rp-btn');
    const spinner = document.getElementById('rp-spinner');
    const text = btn?.querySelector('.btn-text');
    const arr  = btn?.querySelector('.btn-arr');
    if (btn) btn.disabled = true;
    if (spinner) spinner.style.display = 'block';
    if (text) text.textContent = 'Menyimpan...';
    if (arr) arr.style.display = 'none';
  });

  updateStrength('');
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\0_Project_VS_Code\3. Sistem Basis Data\TB-K1-Database\lapak_gaming\resources\views\auth\reset-password.blade.php ENDPATH**/ ?>
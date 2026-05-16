@extends('layouts.app')

@section('title', 'Detail Pengguna — Admin')

@push('styles')
<style>
  /* ── True Glassmorphism Control Panel ─────────────────────── */
  .dashboard-transparent {
    background: transparent !important; /* Paksa latar belakang tembus pandang */
  }
  
  .panel-card-glass {
    background: rgba(10, 17, 30, 0.35) !important; /* Transparansi murni 35% */
    backdrop-filter: blur(24px) saturate(160%); /* Menembuskan elemen bergerak di belakang */
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

  /* ── Cyber Status Badges & Pills ─────────────────────────── */
  .pill-status {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    padding: 3px 10px;
    border-radius: 8px;
    font-size: .75rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.02em;
  }
  .pill-active, .status-approved   { background: rgba(16, 185, 129, 0.12); border: 1px solid rgba(16, 185, 129, 0.3); color: #34d399; }
  .pill-suspended, .status-rejected { background: rgba(239, 68, 68, 0.12); border: 1px solid rgba(239, 68, 68, 0.3); color: #f87171; }
  .status-pending                   { background: rgba(245, 158, 11, 0.12); border: 1px solid rgba(245, 158, 11, 0.3); color: #fbbf24; }
</style>
@endpush

@section('content')
<div class="min-h-screen py-12 px-4 relative overflow-hidden dashboard-transparent">
  {{-- Ambient Glow Light --}}
  <div class="absolute top-0 left-1/3 w-96 h-96 bg-brand-500/5 rounded-full blur-[150px] pointer-events-none"></div>

  <div class="mx-auto max-w-3xl space-y-6 relative z-10">

    {{-- ── HEADER CONTROL ───────────────────────────────────── --}}
    <div class="flex items-center justify-between gap-4 border-b border-white/5 pb-5">
      <div>
        <div class="flex items-center gap-2 mb-1">
          <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span>
          <span class="text-xs font-bold uppercase tracking-widest text-amber-500/80">User Authority File</span>
        </div>
        <h1 class="text-3xl font-extrabold text-white tracking-tight truncate max-w-[280px] sm:max-w-md">{{ $user->name }}</h1>
        <p class="text-slate-400 text-sm font-medium">{{ $user->email }}</p>
      </div>
      <a href="{{ route('admin.users.index') }}"
         class="inline-flex items-center gap-2 rounded-xl bg-white/5 hover:bg-white/10 border border-white/5 px-4 py-2.5 text-xs font-bold text-slate-300 transition-all tracking-wide shrink-0">
        <svg class="w-4 h-4 stroke-[2.5]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
        </svg>
        KEMBALI
      </a>
    </div>

    {{-- ── ALERTS & NOTIFICATIONS ────────────────────────────── --}}
    @if(session('success'))
      <div class="flex items-start gap-3 rounded-2xl border border-emerald-500/30 bg-emerald-500/5 p-4 backdrop-blur-md">
        <svg class="w-5 h-5 text-emerald-400 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        <p class="text-sm font-medium text-emerald-300">{{ session('success') }}</p>
      </div>
    @endif
    @if($errors->any())
      <div class="flex items-start gap-3 rounded-2xl border border-rose-500/30 bg-rose-500/5 p-4 backdrop-blur-md">
        <svg class="w-5 h-5 text-rose-400 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        <p class="text-sm font-medium text-rose-300">{{ $errors->first() }}</p>
      </div>
    @endif

    {{-- ── CARD 1: USER INFO OVERVIEW ─────────────────────────── --}}
    <div class="rounded-3xl p-6 panel-card-glass">
      <h2 class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-4">Informasi Profil Pengguna</h2>
      
      <div class="flex items-center gap-4 border-b border-white/5 pb-5 mb-5">
        <img src="{{ $user->avatar_url }}" alt="Avatar {{ $user->name }}"
             class="w-14 h-14 rounded-2xl object-cover border border-white/10 bg-black/20" />
        <div>
          <p class="text-xl font-bold text-white tracking-tight">{{ $user->name }}</p>
          <p class="text-slate-400 text-xs font-medium mt-0.5 font-mono">UID #{{ $user->id }}</p>
        </div>
      </div>

      <div class="grid grid-cols-2 gap-y-4 gap-x-6 text-sm">
        <div>
          <p class="text-slate-500 text-[10px] uppercase tracking-wider font-bold mb-0.5">Role Otoritas</p>
          <p class="text-white font-semibold capitalize flex items-center gap-1">👤 {{ $user->role }}</p>
        </div>
        <div>
          <p class="text-slate-500 text-[10px] uppercase tracking-wider font-bold mb-0.5">Tanggal Bergabung</p>
          <p class="text-slate-300 font-mono font-semibold">{{ $user->created_at->format('d M Y') }}</p>
        </div>
        <div>
          <p class="text-slate-500 text-[10px] uppercase tracking-wider font-bold mb-0.5">Status Pengajuan Seller</p>
          <p class="text-white font-semibold capitalize">🏪 {{ $user->seller_status ?? 'none' }}</p>
        </div>
        <div>
          <p class="text-slate-500 text-[10px] uppercase tracking-wider font-bold mb-0.5">Status Hak Akses</p>
          <div class="mt-0.5">
            <span class="pill-status {{ $user->status === 'active' ? 'pill-active' : 'pill-suspended' }}">
              <span class="w-1.5 h-1.5 rounded-full {{ $user->status === 'active' ? 'bg-emerald-400' : 'bg-red-400' }}"></span>
              {{ $user->status === 'active' ? 'Aktif' : 'Suspended' }}
            </span>
          </div>
        </div>
      </div>

      {{-- Suspend Log Banner --}}
      @if($user->status === 'suspended' && $user->suspend_reason)
        <div class="mt-5 rounded-2xl border border-rose-500/30 bg-rose-500/5 p-4 backdrop-blur-sm">
          <p class="text-[10px] text-rose-400 font-bold uppercase tracking-wider mb-1">Log Histori Pembekuan Akun</p>
          <p class="text-sm text-slate-300 leading-relaxed font-medium bg-black/10 p-3 rounded-xl border border-white/5">{{ $user->suspend_reason }}</p>
          @if($user->suspended_at)
            <p class="text-[11px] text-slate-500 font-medium mt-2 font-mono">Timestamp Banned: {{ $user->suspended_at->format('d M Y, H:i') }} WIB</p>
          @endif
        </div>
      @endif
    </div>

    {{-- ── CARD 2: STATUS ACCESS INTERACTIVE CONTROL ───────────── --}}
    <div class="rounded-3xl p-6 panel-card-glass">
      <h2 class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-1">Ubah Status Log Masuk</h2>
      <p class="text-[11px] text-slate-500 font-medium mb-4">
        Kebijakan Keamanan: Admin hanya berhak mengubah status log. Data kredensial privat pengguna terkunci aman.
      </p>

      <form action="{{ route('admin.users.status', $user) }}" method="POST" class="space-y-4">
        @csrf
        @method('PUT')

        <div>
          <label class="block text-xs font-bold uppercase tracking-wider text-slate-400 mb-1.5">Pilih Status Baru</label>
          <select name="status"
            class="w-full rounded-xl input-glass px-4 py-3 text-sm text-white outline-none"
            required
            onchange="toggleSuspendReason(this.value)">
            <option value="active" @selected($user->status === 'active')>⚡ Aktif (Normal Access)</option>
            <option value="suspended" @selected($user->status === 'suspended')>❌ Suspended (Banned Access)</option>
          </select>
        </div>

        <div id="suspend-reason-wrap" class="{{ $user->status === 'suspended' ? '' : 'hidden' }}">
          <label class="block text-xs font-bold uppercase tracking-wider text-slate-400 mb-1.5">
            Deskripsi/Alasan Pembekuan <span class="text-slate-500 font-normal lowercase">(tampil di layar user)</span>
          </label>
          <textarea name="suspend_reason" rows="3" maxlength="1000"
            class="w-full rounded-xl input-glass px-4 py-3 text-sm text-white placeholder:text-slate-600 outline-none resize-none"
            placeholder="Tuliskan alasan banned secara logis...">{{ old('suspend_reason', $user->suspend_reason) }}</textarea>
        </div>

        <div class="flex flex-col sm:flex-row gap-3 pt-2">
          <button type="submit"
            class="rounded-xl bg-gradient-to-r from-amber-500 to-orange-500 hover:from-amber-400 hover:to-orange-400 px-6 py-3 text-xs font-bold text-slate-950 transition-all shadow-md shadow-amber-500/10 hover:scale-[1.01]">
            SIMPAN PERUBAHAN STATUS
          </button>
          <a href="{{ route('admin.accounts') }}"
            class="rounded-xl border border-white/5 bg-white/5 px-6 py-3 text-xs font-bold text-slate-300 hover:bg-white/10 transition-colors text-center">
            BATALKAN
          </a>
        </div>
      </form>
    </div>

    {{-- ── CARD 3: MITRA SHOP APPLICATION PREVIEW (IF AVAILABLE) ── --}}
    @if($user->shop_name)
      <div class="rounded-3xl p-6 panel-card-glass">
        <h2 class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-4">Berkas Kelayakan Toko</h2>
        
        <div class="grid gap-5 sm:grid-cols-2">
          <div>
            <p class="text-[10px] uppercase tracking-wider font-bold text-slate-500 mb-2">Aset Spanduk Lapak</p>
            @if($user->shop_photo)
              <img src="{{ $user->shop_photo_url ?? asset('storage/' . $user->shop_photo) }}"
                   alt="Foto spanduk toko" class="w-full h-40 object-cover rounded-2xl border border-white/5 bg-black/40 shadow-inner"
                   onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode($user->shop_name) }}&size=300&background=1e293b&color=94a3b8'" />
            @else
              <div class="w-full h-40 rounded-2xl border border-dashed border-white/10 bg-black/20 flex items-center justify-center text-slate-600 text-xs font-bold">
                NO REGISTERED SPANDUK IMAGE
              </div>
            @endif
          </div>
          
          <div class="space-y-3">
            <div>
              <p class="text-[10px] uppercase tracking-wider font-bold text-slate-500 mb-0.5">Nama Lapak</p>
              <p class="text-white font-bold text-base tracking-tight text-amber-400">{{ $user->shop_name }}</p>
            </div>
            <div>
              <p class="text-[10px] uppercase tracking-wider font-bold text-slate-500 mb-0.5">Segmentasi Jualan</p>
              <p class="text-slate-300 text-xs leading-relaxed bg-black/20 p-2.5 rounded-xl border border-white/5 max-h-24 overflow-y-auto">{{ $user->shop_description ?? 'Tidak menyertakan deskripsi.' }}</p>
            </div>
            <div>
              <p class="text-[10px] uppercase tracking-wider font-bold text-slate-500 mb-1">Status Verifikasi Administrasi</p>
              @php
                $sellerStatusClass = 'status-pending';
                if($user->seller_status === 'approved') $sellerStatusClass = 'pill-active';
                if($user->seller_status === 'rejected') $sellerStatusClass = 'pill-suspended';
              @endphp
              <span class="pill-status {{ $sellerStatusClass }}">
                {{ $user->seller_status ?? 'none' }}
              </span>
            </div>
          </div>
        </div>

        {{-- Application Inline Decision Form --}}
        @if($user->seller_status === 'pending')
          <div class="flex flex-col sm:flex-row gap-2.5 mt-5 pt-5 border-t border-white/5 justify-end">
            <form method="POST" action="{{ route('admin.users.approve-seller', $user) }}" class="w-full sm:w-auto">
              @csrf
              <button type="submit"
                onclick="return confirm('Approve hak jualan dan buka lapak pedagang untuk {{ addslashes($user->name) }}?')"
                class="w-full sm:w-auto inline-flex items-center justify-center gap-1.5 rounded-xl bg-gradient-to-r from-emerald-500 to-teal-500 hover:from-emerald-400 hover:to-teal-400 px-5 py-2.5 text-xs font-bold text-slate-950 transition-all shadow-md">
                <svg class="w-4 h-4 stroke-[2.5]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/>
                </svg>
                APPROVE MITRA
              </button>
            </form>
            
            <button onclick="document.getElementById('reject-inline-wrap').classList.toggle('hidden')"
              class="w-full sm:w-auto inline-flex items-center justify-center gap-1.5 rounded-xl border border-rose-500/30 bg-rose-500/10 px-5 py-2.5 text-xs font-bold text-rose-400 hover:bg-rose-600 hover:text-white transition-all">
              TOLAK BERKAS
            </button>
          </div>

          <div id="reject-inline-wrap" class="hidden mt-4 border-t border-white/5 pt-4 animate-fade-in">
            <form method="POST" action="{{ route('admin.users.reject-seller', $user) }}" class="space-y-3">
              @csrf
              <textarea name="rejection_reason" rows="3" minlength="10" maxlength="1000" required
                class="w-full rounded-xl input-glass px-4 py-3 text-sm text-white placeholder:text-slate-600 outline-none resize-none"
                placeholder="Tuliskan alasan penolakan secara jelas (minimal 10 karakter)..."></textarea>
              <div class="text-right">
                <button type="submit" class="rounded-xl bg-rose-600 hover:bg-rose-500 px-5 py-2.5 text-xs font-bold text-white transition-all">
                  KIRIM NOTIFIKASI TOLAK
                </button>
              </div>
            </form>
          </div>
        @endif
      </div>
    @endif

    {{-- ── CARD 4: CRITICAL DANGER ZONE (EXCLUDE SELF-ADMIN) ───── --}}
    @if($user->role !== 'admin')
      <div class="rounded-3xl p-6 border border-rose-900/30 bg-black/20 backdrop-blur-md">
        <h2 class="text-xs font-bold text-rose-400 uppercase tracking-widest mb-1">⚠️ Zona Pemusnahan Akun (Danger Zone)</h2>
        <p class="text-[11px] text-slate-500 font-medium mb-4">Peringatan: Menghapus akun bersifat destruktif. Seluruh riwayat transaksi, saldo wallet, dan lapak jualan milik user akan terhapus permanen.</p>
        
        <form action="{{ route('admin.users.destroy', $user) }}" method="POST"
              onsubmit="return confirm('Hapus permanen akun {{ addslashes($user->name) }}? Tindakan krusial ini tidak dapat dibatalkan di kemudian hari.')">
          @csrf @method('DELETE')
          <button type="submit"
            class="rounded-xl border border-red-500/30 bg-rose-600/10 hover:bg-red-600 px-5 py-2.5 text-xs font-bold text-red-400 hover:text-white transition-all tracking-wide">
            HAPUS PENGGUNA PERMANEN
          </button>
        </form>
      </div>
    @endif

  </div>
</div>
@endsection

@push('scripts')
<script>
  function toggleSuspendReason(status) {
    const wrap = document.getElementById('suspend-reason-wrap');
    if(wrap) {
      wrap.classList.toggle('hidden', status !== 'suspended');
    }
  }
</script>
@endpush
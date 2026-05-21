@extends('layouts.app')

@section('title', 'Kelola Akun — Admin')

@push('styles')
<style>

  /* ───────────────────────────────────────────────────────────
   PREMIUM ADMIN MOTION SYSTEM
─────────────────────────────────────────────────────────── */

.dashboard-wrapper{
  max-width:1400px;
  margin:0 auto;
  padding-inline:24px;
}

@media(min-width:1280px){
  .dashboard-wrapper{
    padding-inline:40px;
  }
}

.section-spacing{
  margin-top:2rem;
}

/* ── Floating Ambient Glow ───────────────────────────── */
.dashboard-transparent::before{
  content:'';
  position:absolute;
  inset:-20%;
  background:
    radial-gradient(circle at 20% 20%, rgba(59,130,246,.08), transparent 30%),
    radial-gradient(circle at 80% 30%, rgba(168,85,247,.08), transparent 30%),
    radial-gradient(circle at 50% 80%, rgba(245,158,11,.06), transparent 35%);
  filter:blur(80px);
  pointer-events:none;
}

/* ── Glass Card Improved ─────────────────────────────── */
.panel-card-glass{
  position:relative;
  overflow:hidden;
  border-radius:28px;
  padding:1rem;
  background:
    linear-gradient(
      180deg,
      rgba(15,23,42,.58),
      rgba(2,6,23,.78)
    ) !important;

  backdrop-filter:blur(24px) saturate(160%);
  border:1px solid rgba(255,255,255,.06);

  box-shadow:
    0 10px 30px rgba(0,0,0,.35),
    inset 0 1px 0 rgba(255,255,255,.03);

  transition:
    transform .35s ease,
    border-color .35s ease,
    box-shadow .35s ease;
}

.panel-card-glass:hover{
  transform:translateY(-4px);
  border-color:rgba(255,255,255,.12);

  box-shadow:
    0 20px 40px rgba(0,0,0,.45),
    0 0 0 1px rgba(255,255,255,.02);
}

/* ── Glow Border Animation ───────────────────────────── */
.panel-card-glass::after{
  content:'';
  position:absolute;
  inset:0;
  border-radius:inherit;
  padding:1px;

  background:
    linear-gradient(
      135deg,
      rgba(255,255,255,.08),
      transparent,
      rgba(255,255,255,.03)
    );

  -webkit-mask:
    linear-gradient(#fff 0 0) content-box,
    linear-gradient(#fff 0 0);
  mask:
    linear-gradient(#fff 0 0) content-box,
    linear-gradient(#fff 0 0);

  -webkit-mask-composite:xor;
  mask-composite:exclude;
  pointer-events:none;
}

/* ── Reveal Animation ───────────────────────────────── */
.reveal{
  opacity:0;
  transform:translateY(24px);
  transition:
    opacity .8s ease,
    transform .8s ease;
}

.reveal.active{
  opacity:1;
  transform:none;
}

/* ── Smooth Table ───────────────────────────────────── */
.table-wrapper{
  padding-top:.5rem;
}

tbody tr{
  transition:
    background .25s ease,
    transform .25s ease;
}

tbody tr:hover{
  background:rgba(255,255,255,.03);
  transform:scale(.995);
}

/* ── Button Premium ─────────────────────────────────── */
button,
a{
  transition:
    all .25s ease;
}

/* ── Scrollbar ──────────────────────────────────────── */
::-webkit-scrollbar{
  width:10px;
  height:10px;
}

::-webkit-scrollbar-track{
  background:#020617;
}

::-webkit-scrollbar-thumb{
  background:rgba(255,255,255,.08);
  border-radius:999px;
}

::-webkit-scrollbar-thumb:hover{
  background:rgba(255,255,255,.15);
}

  /* ── True Glassmorphism Control Panel ─────────────────────── */
  .dashboard-transparent {
    background: transparent !important; /* Memaksa latar belakang tembus pandang */
  }
  
  .panel-card-glass {
    background: rgba(10, 17, 30, 0.35) !important; /* Transparansi murni 35% */
    backdrop-filter: blur(24px) saturate(160%); /* Menembuskan elemen bergerak di belakang */
    border: 1px solid rgba(255, 255, 255, 0.06);
    box-shadow: 0 12px 40px 0 rgba(0, 0, 0, 0.5);
  }

  /* ── Layout Spacing Improvement ───────────────────────────── */
.dashboard-wrapper {
  max-width: 1350px; /* jangan full layar */
  margin: 0 auto;
}

.section-spacing {
  margin-top: 2rem;
}

.panel-card-glass {
  border-radius: 28px;
  padding: 1.25rem;
}

/* table biar ada napas */
.table-wrapper {
  padding: 0.5rem 0.5rem 1rem;
}

/* card application seller */
.application-card {
  max-width: 1180px;
  margin: 0 auto;
}

/* responsive spacing */
@media (min-width: 1536px) {
  .dashboard-wrapper {
    max-width: 1450px;
  }
}

  /* ── Premium E-Sports Navigation Tabs ────────────────────── */
  .glossy-tabs {
    background: rgba(9, 14, 23, 0.5);
    backdrop-filter: blur(8px);
    border: 1px solid rgba(255, 255, 255, 0.05);
  }

  .tab-btn {
    position: relative;
    padding: .65rem 1.35rem;
    border-radius: 12px;
    font-size: .8125rem;
    font-weight: 700;
    color: #94a3b8;
    transition: all 0.25s ease;
    white-space: nowrap;
    cursor: pointer;
    border: none; 
    background: none;
  }
  .tab-btn:hover { 
    color: #f8fafc; 
    background: rgba(255, 255, 255, .03); 
  }
  .tab-btn.active { 
    color: #1e293b; 
    background: #fbbf24; /* Oranye/Kuning E-sports aktif */
    box-shadow: 0 4px 12px rgba(245, 158, 11, 0.2);
  }

  .tab-badge {
    display: inline-flex; align-items: center; justify-content: center;
    min-width: 20px; height: 20px; padding: 0 6px; border-radius: 6px;
    font-size: .6875rem; font-weight: 800;
    background: rgba(255, 255, 255, 0.15); 
    color: #fff;
    margin-left: 6px;
    transition: all 0.25s ease;
  }
  .tab-btn.active .tab-badge {
    background: rgba(0, 0, 0, 0.15);
    color: #1e293b;
  }
  .tab-btn .badge-pending { 
    background: rgba(239, 68, 68, 0.2); 
    color: #f87171; 
    animation: pulse 2s infinite;
  }

  /* ── Cyber Status Pills ──────────────────────────────────── */
  .pill {
    display: inline-flex; align-items: center; gap: 5px;
    padding: 3px 10px; border-radius: 8px;
    font-size: .75rem; font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.02em;
  }
  .pill-active, .pill-approved   { background: rgba(16, 185, 129, 0.12); border: 1px solid rgba(16, 185, 129, 0.3); color: #34d399; }
  .pill-suspended, .pill-rejected { background: rgba(239, 68, 68, 0.12); border: 1px solid rgba(239, 68, 68, 0.3); color: #f87171; }
  .pill-pending                   { background: rgba(245, 158, 11, 0.12); border: 1px solid rgba(245, 158, 11, 0.3); color: #fbbf24; }

  /* ── Cyber HUD Modals ────────────────────────────────────── */
  .modal-overlay {
    display: none; position: fixed; inset: 0; z-index: 9999;
    background: rgba(4, 7, 12, 0.6); backdrop-filter: blur(12px);
    align-items: center; justify-content: center; padding: 1rem;
  }
  .modal-overlay.open { display: flex; }
  .modal-box-glass {
    background: rgba(13, 22, 38, 0.85) !important;
    backdrop-filter: blur(24px);
    border: 1px solid rgba(239, 68, 68, 0.2); /* Red alert accent boundary */
    box-shadow: 0 20px 50px rgba(0, 0, 0, 0.6);
  }

  .shop-thumb {
    width: 44px; height: 44px; border-radius: 10px; object-fit: cover;
    border: 1px solid rgba(255, 255, 255, 0.08);
  }

  .input-glass {
    background: rgba(5, 9, 16, 0.5) !important;
    border: 1px solid rgba(255, 255, 255, 0.08);
  }
  .input-glass:focus {
    border-color: rgba(239, 68, 68, 0.5) !important;
    box-shadow: 0 0 14px rgba(239, 68, 68, 0.15);
  }
</style>
@endpush

@section('content')
<div class="min-h-screen py-10 relative overflow-hidden dashboard-transparent">
  {{-- Ambient Glow Light --}}
  <div class="absolute top-0 right-1/4 w-96 h-96 bg-brand-500/5 rounded-full blur-[150px] pointer-events-none"></div>

  <div class="dashboard-container px-6 lg:px-8 space-y-8 relative z-10">

    {{-- ── HEADER CONTROL ───────────────────────────────────── --}}
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between border-b border-white/5 pb-5">
      <div>
        <div class="flex items-center gap-2 mb-1.5">
          <span class="w-1.5 h-1.5 rounded-full bg-red-500 animate-pulse"></span>
          <span class="text-xs font-bold uppercase tracking-widest text-red-400/90">HQ Administration System</span>
        </div>
        <h1 class="text-3xl font-extrabold text-white tracking-tight">Kelola Otoritas Akun</h1>
        <p class="text-slate-400 text-sm mt-0.5">Kelola data pengguna, hak akses seller, dan validasi pengajuan berkas toko.</p>
      </div>
      <a href="{{ route('admin.dashboard') }}"
         class="inline-flex items-center gap-2 rounded-xl bg-white/5 hover:bg-white/10 border border-white/5 px-4 py-2.5 text-xs font-bold text-slate-300 transition-all tracking-wide">
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/>
        </svg>
        KEMBALI KE DASBOR
      </a>
    </div>
    {{-- SEARCH & SORT FORM --}}
    <div class="mt-4 mb-2 flex items-center justify-end gap-3">
      <form method="GET" action="{{ route('admin.users.index') }}" class="flex items-center gap-2">
        <input type="hidden" name="tab" value="{{ $tab }}" />
        <input type="hidden" name="sort" value="{{ $sort ?? 'created_at' }}" />
        <input type="hidden" name="direction" value="{{ $direction ?? 'desc' }}" />
        <input name="q" value="{{ $q ?? '' }}" placeholder="Cari nama atau email..."
          class="rounded-xl border border-white/5 bg-black/10 px-4 py-2 text-sm text-white outline-none" />
        <button type="submit" class="rounded-xl bg-amber-500 px-4 py-2 text-sm font-semibold text-slate-950">Cari</button>
        <a href="{{ route('admin.users.index') }}" class="rounded-xl border border-white/5 px-3 py-2 text-sm text-slate-300">Reset</a>
      </form>
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

    {{-- ── TAB NAVIGATION PANEL ─────────────────────────────── --}}
    <div class="overflow-x-auto table-wrapper">
      <div class="inline-flex gap-1 rounded-2xl p-1.5 glossy-tabs min-w-max">

        <a href="{{ route('admin.users.index', ['tab' => 'users']) }}"
           class="tab-btn {{ $tab === 'users' ? 'active' : '' }}">
          User Reguler
          <span class="tab-badge">{{ $regularUsers->total() }}</span>
        </a>

        <a href="{{ route('admin.users.index', ['tab' => 'sellers']) }}"
           class="tab-btn {{ $tab === 'sellers' ? 'active' : '' }}">
          Mitra Seller
          <span class="tab-badge">{{ $sellers->total() }}</span>
        </a>

        <a href="{{ route('admin.users.index', ['tab' => 'applications']) }}"
           class="tab-btn {{ $tab === 'applications' ? 'active' : '' }}">
          Pengajuan Toko
          @if($applications->total() > 0)
            <span class="tab-badge badge-pending">{{ $applications->total() }}</span>
          @endif
        </a>

        <a href="{{ route('admin.users.index', ['tab' => 'pending_verification']) }}"
           class="tab-btn {{ $tab === 'pending_verification' ? 'active' : '' }}">
          Pending Verifikasi Email
          @if($pendingVerifications->total() > 0)
            <span class="tab-badge badge-pending">{{ $pendingVerifications->total() }}</span>
          @endif
        </a>

      </div>
    </div>

    {{-- ═══════════════════════════════════════════════════════════════════ --}}
    {{-- TAB 1: REGULAR USERS                                              --}}
    {{-- ═══════════════════════════════════════════════════════════════════ --}}
    @if($tab === 'users')
      <div class="rounded-3xl panel-card-glass overflow-hidden section-spacing">
        <div class="px-6 py-4 border-b border-white/5 bg-white/5 font-medium text-slate-300 text-sm">
          Daftar akun pembeli (buyer) terdaftar. Anda berhak melakukan penangguhan (<span class="text-rose-400 font-bold">suspend</span>) jika akun terindikasi curang.
        </div>

        @if($regularUsers->isEmpty())
          <div class="py-16 text-center text-slate-500">
            <div class="text-3xl mb-2">👥</div>
            <p class="text-sm font-medium">Belum ada data pengguna reguler.</p>
          </div>
        @else
          <div class="overflow-x-auto table-wrapper">
            <table class="min-w-full divide-y divide-white/5 text-sm text-left text-slate-300">
              <thead class="bg-black/30 text-[11px] font-bold uppercase tracking-wider text-slate-500 border-b border-white/5">
                <tr>
                  <th class="px-6 py-4">
                    <a href="{{ request()->fullUrlWithQuery(['sort' => 'name', 'direction' => ($sort === 'name' && $direction === 'desc') ? 'asc' : 'desc', 'tab' => 'users']) }}">Nama / Pengguna
                      @if(($sort ?? '') === 'name')
                        <span class="text-xs">{{ $direction === 'asc' ? '↑' : '↓' }}</span>
                      @endif
                    </a>
                  </th>
                  <th class="px-6 py-4">
                    <a href="{{ request()->fullUrlWithQuery(['sort' => 'email', 'direction' => ($sort === 'email' && $direction === 'desc') ? 'asc' : 'desc', 'tab' => 'users']) }}">Alamat Email
                      @if(($sort ?? '') === 'email')
                        <span class="text-xs">{{ $direction === 'asc' ? '↑' : '↓' }}</span>
                      @endif
                    </a>
                  </th>
                  <th class="px-6 py-4">
                    <a href="{{ request()->fullUrlWithQuery(['sort' => 'created_at', 'direction' => ($sort === 'created_at' && $direction === 'desc') ? 'asc' : 'desc', 'tab' => 'users']) }}">Tanggal Gabung
                      @if(($sort ?? '') === 'created_at')
                        <span class="text-xs">{{ $direction === 'asc' ? '↑' : '↓' }}</span>
                      @endif
                    </a>
                  </th>
                  <th class="px-6 py-4">Status Akun</th>
                  <th class="px-6 py-4 text-right">Aksi Otoritas</th>
                </tr>
              </thead>
              <tbody class="divide-y divide-white/5">
                @foreach($regularUsers as $user)
                  <tr class="hover:bg-white/5 transition-colors">
                    <td class="px-6 py-4 whitespace-nowrap">
                      <div class="flex items-center gap-3">
                        <img src="{{ $user->avatar_url }}" alt="Avatar {{ $user->name }}" class="w-9 h-9 rounded-full object-cover border border-white/10 bg-black/20" />
                        <div>
                          <p class="font-bold text-white">{{ $user->name }}</p>
                          <p class="text-xs text-slate-500 font-mono">UID #{{ $user->id }}</p>
                        </div>
                      </div>
                    </td>
                    <td class="px-6 py-4 text-slate-400 whitespace-nowrap font-medium">{{ $user->email }}</td>
                    <td class="px-6 py-4 text-slate-500 text-xs whitespace-nowrap font-medium">{{ $user->created_at->format('d M Y') }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                      <span class="pill {{ $user->status === 'active' ? 'pill-active' : 'pill-suspended' }}">
                        <span class="w-1.5 h-1.5 rounded-full {{ $user->status === 'active' ? 'bg-emerald-400' : 'bg-red-400' }}"></span>
                        {{ $user->status === 'active' ? 'Aktif' : 'Suspended' }}
                      </span>
                      @if($user->status === 'suspended' && $user->suspend_reason)
                        <p class="text-[11px] text-rose-400/80 mt-1 max-w-[220px] truncate font-medium" title="{{ $user->suspend_reason }}">
                          Reason: {{ $user->suspend_reason }}
                        </p>
                      @endif
                    </td>
                    <td class="px-6 py-4 text-right whitespace-nowrap">
                      @if($user->status === 'active')
                        <button type="button" data-modal-action="suspend-user" data-user-id="{{ $user->id }}" data-user-name="{{ e($user->name) }}"
                                class="rounded-xl bg-rose-500/10 border border-rose-500/20 px-4 py-2 text-xs font-bold text-rose-400 hover:bg-rose-600 hover:text-white transition-all">
                          Suspend
                        </button>
                      @else
                        <form method="POST" action="{{ route('admin.users.status', $user) }}" class="inline-block">
                          @csrf @method('PUT')
                          <input type="hidden" name="status" value="active" />
                          <button type="submit" class="rounded-xl bg-emerald-500/10 border border-emerald-500/20 px-4 py-2 text-xs font-bold text-emerald-400 hover:bg-emerald-500 hover:text-slate-950 transition-all">
                            Aktifkan Kembali
                          </button>
                        </form>
                      @endif
                    </td>
                  </tr>
                @endforeach
              </tbody>
            </table>
          </div>
          <div class="px-6 py-4 border-t border-white/5 flex justify-center">
            {{ $regularUsers->links() }}
          </div>
        @endif
      </div>
    @endif

    {{-- ═══════════════════════════════════════════════════════════════════ --}}
    {{-- TAB 2: PENDING VERIFICATION                                       --}}
    {{-- ═══════════════════════════════════════════════════════════════════ --}}
    @if($tab === 'pending_verification')
      <div class="rounded-3xl panel-card-glass overflow-hidden section-spacing">
        <div class="px-6 py-4 border-b border-white/5 bg-white/5 font-medium text-slate-300 text-sm">
          Daftar pendaftaran akun baru yang belum menyelesaikan verifikasi link email.
        </div>

        @if($pendingVerifications->isEmpty())
          <div class="py-16 text-center text-slate-500">
            <div class="text-3xl mb-2">✉️</div>
            <p class="text-sm font-medium">Sempurna! Tidak ada akun tertahan di verifikasi email.</p>
          </div>
        @else
          <div class="overflow-x-auto table-wrapper">
            <table class="min-w-full divide-y divide-white/5 text-sm text-left text-slate-300">
              <thead class="bg-black/30 text-[11px] font-bold uppercase tracking-wider text-slate-500 border-b border-white/5">
                <tr>
                  <th class="px-6 py-4">Nama Pengguna</th>
                  <th class="px-6 py-4">Alamat Email</th>
                  <th class="px-6 py-4">Role Akses</th>
                  <th class="px-6 py-4">Waktu Registrasi</th>
                  <th class="px-6 py-4">Status Log</th>
                </tr>
              </thead>
              <tbody class="divide-y divide-white/5">
                @foreach($pendingVerifications as $user)
                  <tr class="hover:bg-white/5 transition-colors">
                    <td class="px-6 py-4 whitespace-nowrap">
                      <div class="flex items-center gap-3">
                        <img src="{{ $user->avatar_url }}" alt="Avatar {{ $user->name }}" class="w-9 h-9 rounded-full object-cover border border-white/10 bg-black/20" />
                        <div>
                          <p class="font-bold text-white">{{ $user->name }}</p>
                          <p class="text-xs text-slate-500 font-mono">UID #{{ $user->id }}</p>
                        </div>
                      </div>
                    </td>
                    <td class="px-6 py-4 text-slate-400 whitespace-nowrap font-medium">{{ $user->email }}</td>
                    <td class="px-6 py-4 capitalize font-semibold text-xs text-slate-300 whitespace-nowrap">🕹️ {{ $user->role }}</td>
                    <td class="px-6 py-4 text-slate-500 text-xs whitespace-nowrap font-medium">{{ $user->created_at->format('d M Y H:i') }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                      <span class="pill pill-pending">
                        <span class="w-1.5 h-1.5 rounded-full bg-amber-400 animate-ping"></span>
                        Unverified
                      </span>
                    </td>
                  </tr>
                @endforeach
              </tbody>
            </table>
          </div>
          <div class="px-6 py-4 border-t border-white/5 flex justify-center">
            {{ $pendingVerifications->links() }}
          </div>
        @endif
      </div>
    @endif

    {{-- ═══════════════════════════════════════════════════════════════════ --}}
    {{-- TAB 3: MITRA SELLERS                                              --}}
    {{-- ═══════════════════════════════════════════════════════════════════ --}}
    @if($tab === 'sellers')
      <div class="rounded-3xl panel-card-glass overflow-hidden section-spacing">
        <div class="px-6 py-4 border-b border-white/5 bg-white/5 font-medium text-slate-300 text-sm">
          Seluruh toko mitra pedagang aktif. Anda dapat membekukan hak penjualan mereka melalui tombol suspensi.
        </div>

        @if($sellers->isEmpty())
          <div class="py-16 text-center text-slate-500">
            <div class="text-3xl mb-2">🏪</div>
            <p class="text-sm font-medium">Belum ada mitra pedagang (seller) terverifikasi.</p>
          </div>
        @else
          <div class="overflow-x-auto table-wrapper">
            <table class="min-w-full divide-y divide-white/5 text-sm text-left text-slate-300">
              <thead class="bg-black/30 text-[11px] font-bold uppercase tracking-wider text-slate-500 border-b border-white/5">
                <tr>
                  <th class="px-6 py-4">
                    <a href="{{ request()->fullUrlWithQuery(['sort' => 'name', 'direction' => ($sort === 'name' && $direction === 'desc') ? 'asc' : 'desc', 'tab' => 'sellers']) }}">Pemilik Lapak
                      @if(($sort ?? '') === 'name')
                        <span class="text-xs">{{ $direction === 'asc' ? '↑' : '↓' }}</span>
                      @endif
                    </a>
                  </th>
                  <th class="px-6 py-4">Brand / Nama Toko</th>
                  <th class="px-6 py-4">
                    <a href="{{ request()->fullUrlWithQuery(['sort' => 'email', 'direction' => ($sort === 'email' && $direction === 'desc') ? 'asc' : 'desc', 'tab' => 'sellers']) }}">Email Kontak
                      @if(($sort ?? '') === 'email')
                        <span class="text-xs">{{ $direction === 'asc' ? '↑' : '↓' }}</span>
                      @endif
                    </a>
                  </th>
                  <th class="px-6 py-4">Status Toko</th>
                  <th class="px-6 py-4">Status Operasional</th>
                  <th class="px-6 py-4 text-right">Tindakan Ketat</th>
                </tr>
              </thead>
              <tbody class="divide-y divide-white/5">
                @foreach($sellers as $seller)
                  <tr class="hover:bg-white/5 transition-colors">
                    <td class="px-6 py-4 whitespace-nowrap">
                      <div class="flex items-center gap-3">
                        <img src="{{ $seller->avatar_url }}" alt="Avatar {{ $seller->name }}" class="w-9 h-9 rounded-full object-cover border border-white/10" />
                        <div>
                          <p class="font-bold text-white">{{ $seller->name }}</p>
                          <p class="text-xs text-slate-500 font-mono">UID #{{ $seller->id }}</p>
                        </div>
                      </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                      <div class="flex items-center gap-2.5">
                        @if($seller->shop_photo)
                          <img src="{{ $seller->shop_photo_url ?? asset('storage/' . $seller->shop_photo) }}" alt="Shop Thumbnail" class="shop-thumb" />
                        @endif
                        <span class="text-white font-bold text-sm tracking-tight">{{ $seller->shop_name ?? $seller->name }}</span>
                      </div>
                    </td>
                    <td class="px-6 py-4 text-slate-400 font-medium whitespace-nowrap">{{ $seller->email }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                      <span class="pill {{ $seller->seller_status === 'approved' ? 'pill-active' : 'pill-suspended' }}">
                        <span class="w-1.5 h-1.5 rounded-full {{ $seller->seller_status === 'approved' ? 'bg-emerald-400' : 'bg-red-400' }}"></span>
                        {{ $seller->seller_status === 'approved' ? 'Verified Lapak' : 'Suspended' }}
                      </span>
                      @if($seller->seller_status === 'suspended' && $seller->suspend_reason)
                        <p class="text-[11px] text-rose-400/80 mt-1 max-w-[200px] truncate font-medium" title="{{ $seller->suspend_reason }}">
                          Reason: {{ $seller->suspend_reason }}
                        </p>
                      @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                      @if($seller->seller_status !== 'approved')
                        <span class="pill pill-suspended">
                          <span class="w-1.5 h-1.5 rounded-full bg-red-400"></span>
                          Nonaktif
                        </span>
                      @elseif(! empty($seller->deactivated_at))
                        <span class="pill pill-pending">
                          <span class="w-1.5 h-1.5 rounded-full bg-amber-400"></span>
                          Nonaktif
                        </span>
                      @else
                        <span class="pill pill-active">
                          <span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span>
                          Aktif
                        </span>
                      @endif
                    </td>
                    <td class="px-6 py-4 text-right whitespace-nowrap">
                      <div class="inline-flex gap-2">
                        @if($seller->seller_status !== 'suspended')
                          <button type="button" data-modal-action="suspend-shop" data-user-id="{{ $seller->id }}" data-shop-name="{{ e($seller->shop_name ?? $seller->name) }}"
                            class="rounded-xl bg-rose-500/10 border border-rose-500/20 px-4 py-2 text-xs font-bold text-rose-400 hover:bg-rose-600 hover:text-white transition-all">
                            Suspend Toko
                          </button>
                        @else
                          <form method="POST" action="{{ route('admin.verification.reinstate', $seller) }}" class="inline-block">
                            @csrf
                            <button type="submit" onclick="return confirm('Pulihkan status toko {{ addslashes($seller->shop_name ?? $seller->name) }}?')"
                              class="rounded-xl bg-emerald-500/10 border border-emerald-500/20 px-4 py-2 text-xs font-bold text-emerald-400 hover:bg-emerald-500 hover:text-slate-950 transition-all">
                              Aktifkan Kembali Toko
                            </button>
                          </form>
                        @endif

                        @if($seller->status !== 'suspended')
                          <button type="button" data-modal-action="suspend-user" data-user-id="{{ $seller->id }}" data-user-name="{{ e($seller->name) }}"
                            class="rounded-xl bg-amber-500/10 border border-amber-500/20 px-4 py-2 text-xs font-bold text-amber-400 hover:bg-amber-400 hover:text-white transition-all">
                            Suspend Akun
                          </button>
                        @else
                          <form method="POST" action="{{ route('admin.users.status', $seller) }}" class="inline-block">
                            @csrf @method('PUT')
                            <input type="hidden" name="status" value="active" />
                            <button type="submit" onclick="return confirm('Pulihkan status akun {{ addslashes($seller->name) }}?')"
                              class="rounded-xl bg-emerald-500/10 border border-emerald-500/20 px-4 py-2 text-xs font-bold text-emerald-400 hover:bg-emerald-500 hover:text-slate-950 transition-all">
                              Aktifkan Kembali Akun
                            </button>
                          </form>
                        @endif
                      </div>
                    </td>
                  </tr>
                @endforeach
              </tbody>
            </table>
          </div>
          <div class="px-6 py-4 border-t border-white/5 flex justify-center">
            {{ $sellers->links() }}
          </div>
        @endif
      </div>
    @endif

    {{-- ═══════════════════════════════════════════════════════════════════ --}}
    {{-- TAB 4: APPLICATION WAITING LIST                                   --}}
    {{-- ═══════════════════════════════════════════════════════════════════ --}}
    @if($tab === 'applications')
      <div class="space-y-4">
        @if($applications->isEmpty())
          <div class="rounded-3xl panel-card-glass py-16 text-center text-slate-500">
            <div class="text-4xl mb-3 animate-bounce">🛡️</div>
            <p class="font-bold text-slate-300">Tidak Ada Berkas Pengajuan Tertunda</p>
            <p class="text-xs text-slate-500 mt-1 max-w-xs mx-auto">Semua kiriman formulir pengajuan toko baru telah selesai diproses oleh tim administrasi.</p>
          </div>
        @else
          <div class="rounded-2xl border border-amber-500/20 bg-amber-500/5 px-5 py-3.5 backdrop-blur-md flex items-center gap-2.5">
            <span class="w-2 h-2 rounded-full bg-amber-400 animate-ping"></span>
            <p class="text-sm font-medium text-amber-300">
              Menunggu Tindakan: Terdapat <strong class="font-extrabold text-white">{{ $applications->total() }}</strong> formulir permohonan pendaftaran mitra baru.
            </p>
          </div>

          @foreach($applications as $applicant)
            <div class="rounded-3xl panel-card-glass overflow-hidden group transition-all application-card">
              
              {{-- Card Header --}}
              <div class="flex flex-col sm:flex-row sm:items-center gap-4 px-6 pt-6 pb-4">
                <img src="{{ $applicant->avatar_url }}" alt="User {{ $applicant->name }}" class="w-14 h-14 rounded-2xl object-cover border border-white/5 bg-black/30 shrink-0 shadow-inner" />
                <div class="flex-1 min-w-0">
                  <div class="flex items-center gap-2">
                    <h2 class="text-lg font-bold text-white tracking-tight">{{ $applicant->name }}</h2>
                    <span class="pill pill-pending">Review Pending</span>
                  </div>
                  <p class="text-sm text-slate-400 font-medium">{{ $applicant->email }}</p>
                  <p class="text-xs text-slate-500 font-medium mt-0.5">Tanggal Pengajuan: <span class="text-slate-400 font-mono">{{ $applicant->updated_at->format('d M Y, H:i') }}</span></p>
                </div>
              </div>

              {{-- Card Details Panel --}}
              <div class="border-t border-white/5 px-6 py-5 grid gap-6 sm:grid-cols-2 bg-black/10">
                <div>
                  <p class="text-[10px] uppercase tracking-wider font-bold text-slate-500 mb-2">Unggulan Foto Spanduk Toko</p>
                  @if($applicant->shop_photo)
                    <img src="{{ $applicant->shop_photo_url ?? asset('storage/' . $applicant->shop_photo) }}"
                         alt="Foto toko {{ $applicant->shop_name }}"
                         class="w-full max-w-xs h-40 object-cover rounded-2xl border border-white/5 bg-black/40 shadow-inner hover:scale-[1.01] transition-transform"
                         data-fallback-src="{{ 'https://ui-avatars.com/api/?name=' . urlencode($applicant->shop_name ?? 'Toko') . '&size=300&background=1e293b&color=94a3b8' }}"
                         onerror="this.src=this.dataset.fallbackSrc;" />
                  @else
                    <div class="w-full max-w-xs h-40 rounded-2xl border border-dashed border-white/5 bg-black/20 flex items-center justify-center text-slate-600 text-xs font-bold">
                      NO ATTACHED IMAGE FILE
                    </div>
                  @endif
                </div>

                <div class="space-y-4">
                  <div>
                    <p class="text-[10px] uppercase tracking-wider font-bold text-slate-500 mb-0.5">Pengajuan Nama Brand Lapak</p>
                    <p class="font-bold text-lg tracking-tight text-amber-400">{{ $applicant->shop_name ?? '—' }}</p>
                  </div>
                  <div>
                    <p class="text-[10px] uppercase tracking-wider font-bold text-slate-500 mb-1">Rencana Deskripsi & Komoditas Jualan</p>
                    <p class="text-slate-300 text-sm leading-relaxed bg-black/20 p-3 rounded-xl border border-white/5">{{ $applicant->shop_description ?? 'Tidak menyertakan deskripsi.' }}</p>
                  </div>
                </div>
              </div>

              {{-- Footer Action Bar --}}
              <div class="border-t border-white/5 px-6 py-4 flex flex-col sm:flex-row justify-end items-center gap-2.5">
                <form method="POST" action="{{ route('admin.users.approve-seller', $applicant) }}" class="w-full sm:w-auto">
                  @csrf
                  <button type="submit"
                          onclick="return confirm('Approve hak jualan dan buka lapak pedagang untuk {{ addslashes($applicant->name) }}?')"
                          class="w-full sm:w-auto inline-flex items-center justify-center gap-1.5 rounded-xl bg-gradient-to-r from-emerald-500 to-teal-500 hover:from-emerald-400 hover:to-teal-400 px-5 py-2.5 text-xs font-bold text-slate-950 transition-all shadow-md shadow-emerald-500/5">
                    <svg class="w-4 h-4 stroke-[2.5]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/>
                    </svg>
                    APPROVE PERMOHONAN
                  </button>
                </form>

                <button type="button" data-modal-action="reject-application" data-user-id="{{ $applicant->id }}" data-user-name="{{ e($applicant->name) }}"
                        class="w-full sm:w-auto inline-flex items-center justify-center gap-1.5 rounded-xl border border-rose-500/30 bg-rose-500/10 px-5 py-2.5 text-xs font-bold text-rose-400 hover:bg-rose-600 hover:text-white transition-all">
                  <svg class="w-4 h-4 stroke-[2.5]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                  </svg>
                  TOLAK BERKAS
                </button>
              </div>

            </div>
          @endforeach

          <div class="mt-4 flex justify-center">{{ $applications->links() }}</div>
        @endif
      </div>
    @endif

  </div>
</div>

{{-- ─────────────────────────────────────────────────────────────────────── --}}
{{-- MODAL INTERAKTIF HUD: SUSPEND AKUN USER/SELLER                         --}}
{{-- ─────────────────────────────────────────────────────────────────────── --}}
<div class="modal-overlay animate-fade-in" id="suspend-modal" role="dialog" aria-modal="true" aria-labelledby="suspend-modal-title">
  <div class="w-full max-w-md rounded-3xl p-6 sm:p-7 modal-box-glass border-rose-500/30">
    <div class="flex items-center gap-2 border-b border-white/5 pb-3 mb-4">
      <span class="text-xl">⚠️</span>
      <h3 id="suspend-modal-title" class="text-lg font-bold text-white tracking-tight">Otoritas Penangguhan Akun</h3>
    </div>
    
    <p class="text-xs text-slate-400 leading-relaxed mb-4">
      Tindakan Tegas: Akun <strong id="suspend-user-name" class="text-white font-bold"></strong> akan dibekukan permanen/sementara dan hak akses masuk aplikasi akan langsung ditolak sistem.
    </p>

    <form id="suspend-form" method="POST" action="">
      @csrf @method('PUT')
      <input type="hidden" name="status" value="suspended" />

      <div class="mb-5">
        <label class="block text-xs font-bold uppercase tracking-wider text-slate-400 mb-1.5">
          Alasan Pembekuan Lapak / Akun <span class="text-slate-500 font-normal lowercase">(muncul di sisi log user)</span>
        </label>
        <textarea name="suspend_reason" rows="4" maxlength="1000" required
          class="w-full rounded-xl input-glass px-4 py-3 text-sm text-white placeholder:text-slate-600 outline-none resize-none"
          placeholder="Contoh: Terdeteksi melakukan penipuan transaksi top-up ilegal pada invoice pembeli atau penyalahgunaan akun dummy."></textarea>
      </div>

      <div class="flex gap-2.5">
        <button type="submit" class="flex-1 rounded-xl bg-gradient-to-r from-rose-600 to-red-600 hover:from-rose-500 hover:to-red-500 px-5 py-3 text-xs font-bold text-white transition-all shadow-md shadow-rose-600/10">
          EKSEKUSI BANNED
        </button>
        <button type="button" onclick="closeSuspendModal()" class="flex-1 rounded-xl border border-white/5 bg-white/5 px-5 py-3 text-xs font-bold text-slate-300 hover:bg-white/10 transition-colors">
          BATALKAN
        </button>
      </div>
    </form>
  </div>
</div>

{{-- ─────────────────────────────────────────────────────────────────────── --}}
{{-- MODAL INTERAKTIF HUD: SUSPEND TOKO                                    --}}
{{-- ─────────────────────────────────────────────────────────────────────── --}}
<div class="modal-overlay animate-fade-in" id="suspend-shop-modal" role="dialog" aria-modal="true" aria-labelledby="suspend-shop-modal-title">
  <div class="w-full max-w-md rounded-3xl p-6 sm:p-7 modal-box-glass border-rose-500/30">
    <div class="flex items-center gap-2 border-b border-white/5 pb-3 mb-4">
      <span class="text-xl">🏪</span>
      <h3 id="suspend-shop-modal-title" class="text-lg font-bold text-white tracking-tight">Suspend Toko Mitra</h3>
    </div>

    <p class="text-xs text-slate-400 leading-relaxed mb-4">
      Menonaktifkan aktivitas toko dan menolak akses seller dashboard untuk <strong id="suspend-shop-name" class="text-white font-bold"></strong>. Tuliskan catatan yang jelas untuk tim dan pengguna.
    </p>

    <form id="suspend-shop-form" method="POST" action="">
      @csrf
      <div class="mb-5">
        <label class="block text-xs font-bold uppercase tracking-wider text-slate-400 mb-1.5">
          Catatan Suspend Toko <span class="text-slate-500 font-normal lowercase">(muncul pada panel admin dan notifikasi)</span>
        </label>
        <textarea name="notes" rows="4" minlength="10" maxlength="1000" required
          class="w-full rounded-xl input-glass px-4 py-3 text-sm text-white placeholder:text-slate-600 outline-none resize-none"
          placeholder="Contoh: Pelanggaran kebijakan: jual item ilegal, penyalahgunaan sistem top-up."></textarea>
        <p class="mt-1 text-[10px] text-slate-500">Minimal 10 karakter.</p>
      </div>

      <div class="flex gap-2.5">
        <button type="submit" class="flex-1 rounded-xl bg-gradient-to-r from-rose-600 to-red-600 hover:from-rose-500 hover:to-red-500 px-5 py-3 text-xs font-bold text-white transition-all shadow-md shadow-rose-600/10">
          EKSEKUSI SUSPEND TOKO
        </button>
        <button type="button" onclick="closeSuspendShopModal()" class="flex-1 rounded-xl border border-white/5 bg-white/5 px-5 py-3 text-xs font-bold text-slate-300 hover:bg-white/10 transition-colors">
          BATALKAN
        </button>
      </div>
    </form>
  </div>
</div>

{{-- ─────────────────────────────────────────────────────────────────────── --}}
{{-- MODAL INTERAKTIF HUD: TOLAK PENGAJUAN FORM SELLER                      --}}
{{-- ─────────────────────────────────────────────────────────────────────── --}}
<div class="modal-overlay animate-fade-in" id="reject-modal" role="dialog" aria-modal="true" aria-labelledby="reject-modal-title">
  <div class="w-full max-w-md rounded-3xl p-6 sm:p-7 modal-box-glass border-amber-500/30">
    <div class="flex items-center gap-2 border-b border-white/5 pb-3 mb-4">
      <span class="text-xl">❌</span>
      <h3 id="reject-modal-title" class="text-lg font-bold text-white tracking-tight">Tolak Pendaftaran Mitra</h3>
    </div>
    
    <p class="text-xs text-slate-400 leading-relaxed mb-4">
      Berkas formulir milik pembeli <strong id="reject-user-name" class="text-white font-bold"></strong> akan dianulir. Tuliskan alasan objektif agar user dapat memperbaiki berkas toko mereka.
    </p>

    <form id="reject-form" method="POST" action="">
      @csrf

      <div class="mb-5">
        <label class="block text-xs font-bold uppercase tracking-wider text-slate-400 mb-1.5">
          Alasan Penolakan Berkas Resmi <span class="text-rose-400">*</span>
        </label>
        <textarea name="rejection_reason" rows="4" maxlength="1000" minlength="10" required
          class="w-full rounded-xl input-glass px-4 py-3 text-sm text-white placeholder:text-slate-600 outline-none resize-none"
          placeholder="Contoh: Lampiran foto logo toko buram / blur. Harap ajukan kembali dengan deskripsi segmentasi game yang lebih jelas dan resolusi foto yang proporsional."></textarea>
        <p class="text-[10px] text-slate-500 mt-1">Batas minimal pengisian kolom deskripsi alasan adalah 10 karakter.</p>
      </div>

      <div class="flex gap-2.5">
        <button type="submit" class="flex-1 rounded-xl bg-gradient-to-r from-orange-600 to-amber-600 hover:from-orange-500 hover:to-amber-500 px-5 py-3 text-xs font-bold text-white transition-all shadow-md shadow-orange-600/10">
          KONFIRMASI REJECT
        </button>
        <button type="button" onclick="closeRejectModal()" class="flex-1 rounded-xl border border-white/5 bg-white/5 px-5 py-3 text-xs font-bold text-slate-300 hover:bg-white/10 transition-colors">
          BATALKAN
        </button>
      </div>
    </form>
  </div>
</div>
@endsection

@push('scripts')
<script>
  // ── Suspend Modal Handler ──────────────────────────────────────────────
  function openSuspendModal(userId, userName) {
    document.getElementById('suspend-user-name').textContent = userName;
    document.getElementById('suspend-form').action = '/admin/users/' + userId + '/status';
    document.getElementById('suspend-modal').classList.add('open');
    document.body.style.overflow = 'hidden';
  }
  function closeSuspendModal() {
    document.getElementById('suspend-modal').classList.remove('open');
    document.body.style.overflow = '';
  }

  // ── Reject Modal Handler ───────────────────────────────────────────────
  function openRejectModal(userId, userName) {
    document.getElementById('reject-user-name').textContent = userName;
    document.getElementById('reject-form').action = '/admin/users/' + userId + '/reject-seller';
    document.getElementById('reject-modal').classList.add('open');
    document.body.style.overflow = 'hidden';
  }
  function closeRejectModal() {
    document.getElementById('reject-modal').classList.remove('open');
    document.body.style.overflow = '';
  }

  // ── Suspend Shop Modal Handler ───────────────────────────────────────
  function openSuspendShopModal(userId, shopName) {
    document.getElementById('suspend-shop-name').textContent = shopName;
    document.getElementById('suspend-shop-form').action = '/admin/verification/' + userId + '/suspend';
    document.getElementById('suspend-shop-modal').classList.add('open');
    document.body.style.overflow = 'hidden';
  }
  function closeSuspendShopModal() {
    document.getElementById('suspend-shop-modal').classList.remove('open');
    document.body.style.overflow = '';
  }

  document.addEventListener('click', function(e) {
    const trigger = e.target instanceof Element ? e.target.closest('[data-modal-action]') : null;
    if (!trigger) {
      return;
    }

    const action = trigger.dataset.modalAction;
    if (action === 'suspend-user') {
      e.preventDefault();
      openSuspendModal(trigger.dataset.userId, trigger.dataset.userName);
      return;
    }

    if (action === 'suspend-shop') {
      e.preventDefault();
      openSuspendShopModal(trigger.dataset.userId, trigger.dataset.shopName);
      return;
    }

    if (action === 'reject-application') {
      e.preventDefault();
      openRejectModal(trigger.dataset.userId, trigger.dataset.userName);
    }
  });

  // ── Overlay Backdrop click close trigger ──────────────────────────────
  ['suspend-modal', 'suspend-shop-modal', 'reject-modal'].forEach(function(id) {
    document.getElementById(id)?.addEventListener('click', function(e) {
      if (e.target === this) {
        this.classList.remove('open');
        document.body.style.overflow = '';
      }
    });
  });

  // ── ESC Keyboard Key close escape hatch ───────────────────────────────
  document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
      closeSuspendModal();
      closeRejectModal();
    }
  });
</script>
@endpush
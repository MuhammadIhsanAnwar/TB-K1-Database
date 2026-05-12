@extends('layouts.app')

@section('title', 'Kelola Akun — Admin')

@push('styles')
<style>
  /* ── Tab styles ── */
  .tab-btn {
    position: relative;
    padding: .6rem 1.25rem;
    border-radius: 999px;
    font-size: .8125rem;
    font-weight: 600;
    color: #64748b;
    transition: color .2s, background .2s;
    white-space: nowrap;
    cursor: pointer;
    border: none; background: none;
  }
  .tab-btn:hover { color: #cbd5e1; background: rgba(255,255,255,.04); }
  .tab-btn.active { color: #fff; background: rgba(245,158,11,.15); }
  .tab-btn.active::after {
    content: '';
    position: absolute; bottom: -1px; left: 50%; transform: translateX(-50%);
    width: 32px; height: 2px; border-radius: 2px;
    background: #f97316;
  }

  /* ── Badge ── */
  .tab-badge {
    display: inline-flex; align-items: center; justify-content: center;
    min-width: 18px; height: 18px; padding: 0 5px; border-radius: 999px;
    font-size: .6875rem; font-weight: 700;
    background: rgba(245,158,11,.2); color: #fbbf24;
    margin-left: 6px;
  }
  .tab-badge.badge-pending { background: rgba(239,68,68,.2); color: #f87171; }

  /* ── Status pill ── */
  .pill {
    display: inline-flex; align-items: center; gap: 4px;
    padding: 2px 8px; border-radius: 999px;
    font-size: .6875rem; font-weight: 600;
  }
  .pill-active   { background: rgba(16,185,129,.12); color: #34d399; }
  .pill-suspended { background: rgba(239,68,68,.12); color: #f87171; }
  .pill-pending  { background: rgba(245,158,11,.12); color: #fbbf24; }
  .pill-approved { background: rgba(16,185,129,.12); color: #34d399; }
  .pill-rejected { background: rgba(100,116,139,.15); color: #94a3b8; }

  /* ── Table ── */
  .data-table th { white-space: nowrap; }

  /* ── Modal overlay ── */
  .modal-overlay {
    display: none; position: fixed; inset: 0; z-index: 9999;
    background: rgba(0,0,0,.65); backdrop-filter: blur(4px);
    align-items: center; justify-content: center; padding: 1rem;
  }
  .modal-overlay.open { display: flex; }
  .modal-box {
    background: #0D1421; border: 1px solid #1E2D45;
    border-radius: 20px; padding: 1.75rem; width: 100%; max-width: 480px;
  }

  /* ── Shop photo thumbnail ── */
  .shop-thumb {
    width: 44px; height: 44px; border-radius: 10px; object-fit: cover;
    border: 1px solid #1E2D45;
  }
</style>
@endpush

@section('content')
<div class="min-h-screen bg-slate-950 py-10 px-4">
  <div class="mx-auto max-w-7xl space-y-6">

    {{-- Header --}}
    <div class="flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
      <div>
        <p class="text-xs uppercase tracking-widest text-amber-400">Admin Panel</p>
        <h1 class="mt-1 text-3xl font-bold text-white">Kelola Akun</h1>
        <p class="mt-1 text-slate-400 text-sm">Kelola pengguna, seller, dan pengajuan seller dari satu tempat.</p>
      </div>
      <a href="{{ route('admin.dashboard') }}"
         class="self-start sm:self-auto inline-flex items-center gap-1.5 rounded-2xl border border-slate-700 px-4 py-2.5 text-sm text-slate-300 hover:border-slate-500 hover:text-white transition">
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
        </svg>
        Dashboard
      </a>
    </div>

    {{-- Flash Messages --}}
    @if(session('success'))
      <div class="flex items-start gap-3 rounded-2xl border border-emerald-500/20 bg-emerald-500/08 p-4">
        <svg class="w-5 h-5 text-emerald-400 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        <p class="text-sm text-emerald-300">{{ session('success') }}</p>
      </div>
    @endif

    @if($errors->any())
      <div class="flex items-start gap-3 rounded-2xl border border-red-500/20 bg-red-500/08 p-4">
        <svg class="w-5 h-5 text-red-400 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        <p class="text-sm text-red-300">{{ $errors->first() }}</p>
      </div>
    @endif

    {{-- Tab Navigation --}}
    <div class="overflow-x-auto">
      <div class="inline-flex gap-1 rounded-2xl border border-slate-800 bg-slate-900 p-1.5 min-w-max">

        <a href="{{ route('admin.accounts', ['tab' => 'users']) }}"
           class="tab-btn {{ $tab === 'users' ? 'active' : '' }}">
          User
          <span class="tab-badge">{{ $regularUsers->total() }}</span>
        </a>

        <a href="{{ route('admin.accounts', ['tab' => 'sellers']) }}"
           class="tab-btn {{ $tab === 'sellers' ? 'active' : '' }}">
          Seller
          <span class="tab-badge">{{ $sellers->total() }}</span>
        </a>

        <a href="{{ route('admin.accounts', ['tab' => 'applications']) }}"
           class="tab-btn {{ $tab === 'applications' ? 'active' : '' }}">
          Pengajuan Seller
          @if($applications->total() > 0)
            <span class="tab-badge badge-pending">{{ $applications->total() }}</span>
          @endif
        </a>

      </div>
    </div>

    {{-- ═══════════════════════════════════════════════════════════════════ --}}
    {{-- TAB 1: USERS                                                        --}}
    {{-- ═══════════════════════════════════════════════════════════════════ --}}
    @if($tab === 'users')
      <div class="overflow-hidden rounded-3xl border border-slate-800 bg-slate-900">
        <div class="px-6 py-4 border-b border-slate-800">
          <p class="text-sm font-medium text-slate-300">
            Daftar pengguna (buyer) terdaftar.
            Admin hanya dapat mengubah <strong class="text-white">status akun</strong> (aktif/suspend).
          </p>
        </div>

        @if($regularUsers->isEmpty())
          <div class="py-16 text-center text-slate-500">
            <svg class="mx-auto w-12 h-12 mb-3 text-slate-700" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
            <p>Belum ada pengguna.</p>
          </div>
        @else
          <div class="overflow-x-auto">
            <table class="data-table min-w-full divide-y divide-slate-800 text-sm text-left text-slate-300">
              <thead class="bg-slate-950 text-xs uppercase tracking-widest text-slate-500">
                <tr>
                  <th class="px-5 py-3.5">Pengguna</th>
                  <th class="px-5 py-3.5">Email</th>
                  <th class="px-5 py-3.5">Bergabung</th>
                  <th class="px-5 py-3.5">Status</th>
                  <th class="px-5 py-3.5">Aksi</th>
                </tr>
              </thead>
              <tbody class="divide-y divide-slate-800">
                @foreach($regularUsers as $user)
                  <tr class="hover:bg-slate-800/40 transition">
                    <td class="px-5 py-4">
                      <div class="flex items-center gap-3">
                        <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}"
                             class="w-9 h-9 rounded-full object-cover border border-slate-700" />
                        <div>
                          <p class="font-medium text-white">{{ $user->name }}</p>
                          <p class="text-xs text-slate-500">ID #{{ $user->id }}</p>
                        </div>
                      </div>
                    </td>
                    <td class="px-5 py-4 text-slate-400">{{ $user->email }}</td>
                    <td class="px-5 py-4 text-slate-500 text-xs">{{ $user->created_at->format('d M Y') }}</td>
                    <td class="px-5 py-4">
                      <span class="pill {{ $user->status === 'active' ? 'pill-active' : 'pill-suspended' }}">
                        <span class="w-1.5 h-1.5 rounded-full {{ $user->status === 'active' ? 'bg-emerald-400' : 'bg-red-400' }}"></span>
                        {{ $user->status === 'active' ? 'Aktif' : 'Suspended' }}
                      </span>
                      @if($user->status === 'suspended' && $user->suspend_reason)
                        <p class="text-xs text-red-300/70 mt-1 max-w-[200px] truncate" title="{{ $user->suspend_reason }}">
                          {{ $user->suspend_reason }}
                        </p>
                      @endif
                    </td>
                    <td class="px-5 py-4">
                      <div class="flex items-center gap-2">
                        @if($user->status === 'active')
                          <button
                            onclick="openSuspendModal({{ $user->id }}, '{{ addslashes($user->name) }}')"
                            class="rounded-xl bg-red-500/10 border border-red-500/20 px-3 py-1.5 text-xs font-semibold text-red-400 hover:bg-red-500/20 transition">
                            Suspend
                          </button>
                        @else
                          <form method="POST" action="{{ route('admin.users.status', $user) }}">
                            @csrf @method('PUT')
                            <input type="hidden" name="status" value="active" />
                            <button class="rounded-xl bg-emerald-500/10 border border-emerald-500/20 px-3 py-1.5 text-xs font-semibold text-emerald-400 hover:bg-emerald-500/20 transition">
                              Aktifkan
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
          <div class="px-5 py-4 border-t border-slate-800">
            {{ $regularUsers->links() }}
          </div>
        @endif
      </div>
    @endif

    {{-- ═══════════════════════════════════════════════════════════════════ --}}
    {{-- TAB 2: SELLERS                                                      --}}
    {{-- ═══════════════════════════════════════════════════════════════════ --}}
    @if($tab === 'sellers')
      <div class="overflow-hidden rounded-3xl border border-slate-800 bg-slate-900">
        <div class="px-6 py-4 border-b border-slate-800">
          <p class="text-sm font-medium text-slate-300">
            Daftar seller yang telah diverifikasi.
            Admin dapat suspend/aktifkan akun seller.
          </p>
        </div>

        @if($sellers->isEmpty())
          <div class="py-16 text-center text-slate-500">
            <svg class="mx-auto w-12 h-12 mb-3 text-slate-700" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
            </svg>
            <p>Belum ada seller yang diverifikasi.</p>
          </div>
        @else
          <div class="overflow-x-auto">
            <table class="data-table min-w-full divide-y divide-slate-800 text-sm text-left text-slate-300">
              <thead class="bg-slate-950 text-xs uppercase tracking-widest text-slate-500">
                <tr>
                  <th class="px-5 py-3.5">Seller</th>
                  <th class="px-5 py-3.5">Nama Toko</th>
                  <th class="px-5 py-3.5">Email</th>
                  <th class="px-5 py-3.5">Status Akun</th>
                  <th class="px-5 py-3.5">Aksi</th>
                </tr>
              </thead>
              <tbody class="divide-y divide-slate-800">
                @foreach($sellers as $seller)
                  <tr class="hover:bg-slate-800/40 transition">
                    <td class="px-5 py-4">
                      <div class="flex items-center gap-3">
                        <img src="{{ $seller->avatar_url }}" alt="{{ $seller->name }}"
                             class="w-9 h-9 rounded-full object-cover border border-slate-700" />
                        <div>
                          <p class="font-medium text-white">{{ $seller->name }}</p>
                          <p class="text-xs text-slate-500">ID #{{ $seller->id }}</p>
                        </div>
                      </div>
                    </td>
                    <td class="px-5 py-4">
                      <div class="flex items-center gap-2">
                        @if($seller->shop_photo)
                          <img src="{{ $seller->shop_photo_url ?? asset('storage/' . $seller->shop_photo) }}"
                               alt="Foto toko" class="shop-thumb" />
                        @endif
                        <span class="text-white font-medium">{{ $seller->shop_name ?? $seller->name }}</span>
                      </div>
                    </td>
                    <td class="px-5 py-4 text-slate-400">{{ $seller->email }}</td>
                    <td class="px-5 py-4">
                      <span class="pill {{ $seller->status === 'active' ? 'pill-active' : 'pill-suspended' }}">
                        <span class="w-1.5 h-1.5 rounded-full {{ $seller->status === 'active' ? 'bg-emerald-400' : 'bg-red-400' }}"></span>
                        {{ $seller->status === 'active' ? 'Aktif' : 'Suspended' }}
                      </span>
                      @if($seller->status === 'suspended' && $seller->suspend_reason)
                        <p class="text-xs text-red-300/70 mt-1 max-w-[200px] truncate" title="{{ $seller->suspend_reason }}">
                          {{ $seller->suspend_reason }}
                        </p>
                      @endif
                    </td>
                    <td class="px-5 py-4">
                      @if($seller->status === 'active')
                        <button
                          onclick="openSuspendModal({{ $seller->id }}, '{{ addslashes($seller->name) }}')"
                          class="rounded-xl bg-red-500/10 border border-red-500/20 px-3 py-1.5 text-xs font-semibold text-red-400 hover:bg-red-500/20 transition">
                          Suspend
                        </button>
                      @else
                        <form method="POST" action="{{ route('admin.users.status', $seller) }}">
                          @csrf @method('PUT')
                          <input type="hidden" name="status" value="active" />
                          <button class="rounded-xl bg-emerald-500/10 border border-emerald-500/20 px-3 py-1.5 text-xs font-semibold text-emerald-400 hover:bg-emerald-500/20 transition">
                            Aktifkan
                          </button>
                        </form>
                      @endif
                    </td>
                  </tr>
                @endforeach
              </tbody>
            </table>
          </div>
          <div class="px-5 py-4 border-t border-slate-800">
            {{ $sellers->links() }}
          </div>
        @endif
      </div>
    @endif

    {{-- ═══════════════════════════════════════════════════════════════════ --}}
    {{-- TAB 3: PENGAJUAN SELLER                                             --}}
    {{-- ═══════════════════════════════════════════════════════════════════ --}}
    @if($tab === 'applications')
      <div class="space-y-4">

        @if($applications->isEmpty())
          <div class="rounded-3xl border border-slate-800 bg-slate-900 py-16 text-center text-slate-500">
            <svg class="mx-auto w-12 h-12 mb-3 text-slate-700" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
            </svg>
            <p class="font-medium text-slate-400">Tidak ada pengajuan seller yang menunggu.</p>
            <p class="text-sm text-slate-600 mt-1">Semua pengajuan telah diproses.</p>
          </div>
        @else
          {{-- Info header --}}
          <div class="rounded-2xl border border-amber-500/20 bg-amber-500/05 px-5 py-3 flex items-center gap-2">
            <svg class="w-4 h-4 text-amber-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <p class="text-sm text-amber-300">
              Terdapat <strong>{{ $applications->total() }}</strong> pengajuan seller menunggu verifikasi.
            </p>
          </div>

          @foreach($applications as $applicant)
            <div class="rounded-3xl border border-slate-800 bg-slate-900 overflow-hidden">

              {{-- Application Card Header --}}
              <div class="flex flex-col sm:flex-row sm:items-center gap-4 px-6 pt-6 pb-4">
                <img src="{{ $applicant->avatar_url }}" alt="{{ $applicant->name }}"
                     class="w-14 h-14 rounded-2xl object-cover border border-slate-700 shrink-0" />
                <div class="flex-1 min-w-0">
                  <div class="flex flex-wrap items-center gap-2">
                    <h2 class="text-lg font-bold text-white">{{ $applicant->name }}</h2>
                    <span class="pill pill-pending">Pending</span>
                  </div>
                  <p class="text-sm text-slate-400">{{ $applicant->email }}</p>
                  <p class="text-xs text-slate-500 mt-0.5">Pengajuan dikirim: {{ $applicant->updated_at->format('d M Y, H:i') }}</p>
                </div>
              </div>

              <div class="border-t border-slate-800 px-6 py-5 grid gap-5 sm:grid-cols-2">

                {{-- Shop Photo --}}
                <div>
                  <p class="text-xs uppercase tracking-widest text-slate-500 mb-2">Foto Toko</p>
                  @if($applicant->shop_photo)
                    <img
                      src="{{ $applicant->shop_photo_url ?? asset('storage/' . $applicant->shop_photo) }}"
                      alt="Foto toko {{ $applicant->shop_name }}"
                      class="w-full max-w-xs h-48 object-cover rounded-2xl border border-slate-700"
                      onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode($applicant->shop_name ?? 'Toko') }}&size=300&background=1e293b&color=94a3b8'"
                    />
                  @else
                    <div class="w-full max-w-xs h-48 rounded-2xl border border-slate-700 bg-slate-800 flex items-center justify-center text-slate-600 text-sm">
                      Tidak ada foto
                    </div>
                  @endif
                </div>

                {{-- Shop Details --}}
                <div class="space-y-4">
                  <div>
                    <p class="text-xs uppercase tracking-widest text-slate-500 mb-1">Nama Toko</p>
                    <p class="text-white font-semibold text-lg">{{ $applicant->shop_name ?? '—' }}</p>
                  </div>
                  <div>
                    <p class="text-xs uppercase tracking-widest text-slate-500 mb-1">Deskripsi Toko</p>
                    <p class="text-slate-300 text-sm leading-relaxed">{{ $applicant->shop_description ?? '—' }}</p>
                  </div>
                </div>
              </div>

              {{-- Action Buttons --}}
              <div class="border-t border-slate-800 px-6 py-4 flex flex-col sm:flex-row gap-3">

                {{-- Approve --}}
                <form method="POST" action="{{ route('admin.users.approve-seller', $applicant) }}">
                  @csrf
                  <button
                    type="submit"
                    onclick="return confirm('Approve pengajuan seller {{ addslashes($applicant->name) }} ({{ addslashes($applicant->shop_name ?? '') }})?')"
                    class="inline-flex items-center gap-2 rounded-2xl bg-emerald-500 px-5 py-2.5 text-sm font-semibold text-slate-950 hover:bg-emerald-400 transition active:scale-[.98]">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                    </svg>
                    Approve Seller
                  </button>
                </form>

                {{-- Reject --}}
                <button
                  onclick="openRejectModal({{ $applicant->id }}, '{{ addslashes($applicant->name) }}')"
                  class="inline-flex items-center gap-2 rounded-2xl border border-red-500/30 bg-red-500/10 px-5 py-2.5 text-sm font-semibold text-red-400 hover:bg-red-500/20 transition">
                  <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/>
                  </svg>
                  Tolak
                </button>
              </div>

            </div>
          @endforeach

          <div>{{ $applications->links() }}</div>
        @endif
      </div>
    @endif

  </div>
</div>

{{-- ─────────────────────────────────────────────────────────────────────── --}}
{{-- MODAL: Suspend dengan Alasan                                            --}}
{{-- ─────────────────────────────────────────────────────────────────────── --}}
<div class="modal-overlay" id="suspend-modal" role="dialog" aria-modal="true" aria-labelledby="suspend-modal-title">
  <div class="modal-box">
    <h3 id="suspend-modal-title" class="text-lg font-bold text-white mb-1">Suspend Akun</h3>
    <p class="text-sm text-slate-400 mb-5">
      Akun <strong id="suspend-user-name" class="text-white"></strong> akan disuspend dan tidak bisa login.
    </p>

    <form id="suspend-form" method="POST" action="">
      @csrf @method('PUT')
      <input type="hidden" name="status" value="suspended" />

      <div class="mb-4">
        <label class="block text-sm font-medium text-slate-300 mb-1.5">
          Alasan Suspend <span class="text-slate-500 font-normal">(akan ditampilkan ke pengguna)</span>
        </label>
        <textarea
          name="suspend_reason"
          rows="4"
          class="w-full rounded-2xl border border-slate-700 bg-slate-950 px-4 py-3 text-white placeholder:text-slate-600 outline-none focus:border-red-500/50 transition resize-none"
          placeholder="Contoh: Melanggar kebijakan marketplace — penjualan item ilegal."
          maxlength="1000"
        ></textarea>
        <p class="text-xs text-slate-500 mt-1">Kosongkan jika tidak ingin mencantumkan alasan spesifik.</p>
      </div>

      <div class="flex gap-3">
        <button type="submit"
          class="flex-1 rounded-2xl bg-red-600 px-5 py-2.5 text-sm font-semibold text-white hover:bg-red-500 transition">
          Suspend Akun
        </button>
        <button type="button" onclick="closeSuspendModal()"
          class="flex-1 rounded-2xl border border-slate-700 px-5 py-2.5 text-sm font-semibold text-slate-300 hover:border-slate-500 transition">
          Batal
        </button>
      </div>
    </form>
  </div>
</div>

{{-- ─────────────────────────────────────────────────────────────────────── --}}
{{-- MODAL: Reject Seller Application dengan Alasan                         --}}
{{-- ─────────────────────────────────────────────────────────────────────── --}}
<div class="modal-overlay" id="reject-modal" role="dialog" aria-modal="true" aria-labelledby="reject-modal-title">
  <div class="modal-box">
    <h3 id="reject-modal-title" class="text-lg font-bold text-white mb-1">Tolak Pengajuan Seller</h3>
    <p class="text-sm text-slate-400 mb-5">
      Pengajuan dari <strong id="reject-user-name" class="text-white"></strong> akan ditolak.
      Pengguna akan menerima notifikasi beserta alasan penolakan.
    </p>

    <form id="reject-form" method="POST" action="">
      @csrf

      <div class="mb-4">
        <label class="block text-sm font-medium text-slate-300 mb-1.5">
          Alasan Penolakan <span class="text-red-400">*</span>
        </label>
        <textarea
          name="rejection_reason"
          rows="4"
          class="w-full rounded-2xl border border-slate-700 bg-slate-950 px-4 py-3 text-white placeholder:text-slate-600 outline-none focus:border-red-500/50 transition resize-none"
          placeholder="Contoh: Foto toko tidak jelas. Mohon unggah foto toko yang lebih representatif dan deskripsi yang lebih lengkap."
          maxlength="1000"
          required
          minlength="10"
        ></textarea>
        <p class="text-xs text-slate-500 mt-1">Minimal 10 karakter. Alasan ini akan dikirim ke pengguna.</p>
      </div>

      <div class="flex gap-3">
        <button type="submit"
          class="flex-1 rounded-2xl bg-red-600 px-5 py-2.5 text-sm font-semibold text-white hover:bg-red-500 transition">
          Tolak Pengajuan
        </button>
        <button type="button" onclick="closeRejectModal()"
          class="flex-1 rounded-2xl border border-slate-700 px-5 py-2.5 text-sm font-semibold text-slate-300 hover:border-slate-500 transition">
          Batal
        </button>
      </div>
    </form>
  </div>
</div>

@endsection

@push('scripts')
<script>
  // ── Suspend Modal ────────────────────────────────────────────────────────
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

  // ── Reject Modal ─────────────────────────────────────────────────────────
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

  // ── Close modals on overlay click ─────────────────────────────────────────
  ['suspend-modal', 'reject-modal'].forEach(function(id) {
    document.getElementById(id)?.addEventListener('click', function(e) {
      if (e.target === this) {
        this.classList.remove('open');
        document.body.style.overflow = '';
      }
    });
  });

  // ── Close on ESC ─────────────────────────────────────────────────────────
  document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
      closeSuspendModal();
      closeRejectModal();
    }
  });
</script>
@endpush
@extends('layouts.app')

@section('title', 'Detail Pengguna — Admin')

@section('content')
<div class="min-h-screen bg-slate-950 py-12 px-4">
  <div class="mx-auto max-w-3xl space-y-6">

    {{-- Header --}}
    <div class="flex items-center justify-between gap-4">
      <div>
        <p class="text-xs uppercase tracking-widest text-amber-400">Admin Panel</p>
        <h1 class="mt-1 text-3xl font-bold text-white">{{ $user->name }}</h1>
        <p class="mt-1 text-slate-400">{{ $user->email }}</p>
      </div>
      <a href="{{ route('admin.accounts') }}"
         class="inline-flex items-center gap-1.5 rounded-2xl border border-slate-700 px-4 py-2.5 text-sm text-slate-300 hover:border-slate-500 hover:text-white transition">
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
        </svg>
        Kembali
      </a>
    </div>

    {{-- Flash --}}
    @if(session('success'))
      <div class="flex items-center gap-3 rounded-2xl border border-emerald-500/20 bg-emerald-500/08 p-4">
        <svg class="w-5 h-5 text-emerald-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        <p class="text-sm text-emerald-300">{{ session('success') }}</p>
      </div>
    @endif
    @if($errors->any())
      <div class="flex items-center gap-3 rounded-2xl border border-red-500/20 bg-red-500/08 p-4">
        <p class="text-sm text-red-300">{{ $errors->first() }}</p>
      </div>
    @endif

    {{-- User Info Card (read-only) --}}
    <div class="rounded-3xl border border-slate-800 bg-slate-900 p-6">
      <h2 class="text-sm font-semibold text-slate-400 uppercase tracking-widest mb-5">Informasi Pengguna</h2>
      <div class="flex items-center gap-4 mb-6">
        <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}"
             class="w-16 h-16 rounded-2xl object-cover border border-slate-700" />
        <div>
          <p class="text-xl font-bold text-white">{{ $user->name }}</p>
          <p class="text-slate-400 text-sm">{{ $user->email }}</p>
        </div>
      </div>
      <div class="grid grid-cols-2 gap-4 text-sm">
        <div>
          <p class="text-slate-500 text-xs uppercase tracking-wider mb-0.5">Role</p>
          <p class="text-white font-medium capitalize">{{ $user->role }}</p>
        </div>
        <div>
          <p class="text-slate-500 text-xs uppercase tracking-wider mb-0.5">Bergabung</p>
          <p class="text-white font-medium">{{ $user->created_at->format('d M Y') }}</p>
        </div>
        <div>
          <p class="text-slate-500 text-xs uppercase tracking-wider mb-0.5">Status Seller</p>
          <p class="text-white font-medium capitalize">{{ $user->seller_status ?? 'none' }}</p>
        </div>
        <div>
          <p class="text-slate-500 text-xs uppercase tracking-wider mb-0.5">Status Akun</p>
          <span class="inline-flex items-center gap-1.5 text-sm font-semibold {{ $user->status === 'active' ? 'text-emerald-400' : 'text-red-400' }}">
            <span class="w-2 h-2 rounded-full {{ $user->status === 'active' ? 'bg-emerald-400' : 'bg-red-400' }}"></span>
            {{ $user->status === 'active' ? 'Aktif' : 'Suspended' }}
          </span>
        </div>
      </div>

      @if($user->status === 'suspended' && $user->suspend_reason)
        <div class="mt-4 rounded-2xl border border-red-500/20 bg-red-500/05 p-3">
          <p class="text-xs text-slate-500 uppercase tracking-wider mb-1">Alasan Suspend</p>
          <p class="text-sm text-red-300">{{ $user->suspend_reason }}</p>
          @if($user->suspended_at)
            <p class="text-xs text-slate-600 mt-1">Disuspend: {{ $user->suspended_at->format('d M Y, H:i') }}</p>
          @endif
        </div>
      @endif
    </div>

    {{-- ── ONLY: Status Management ─────────────────────────────────────── --}}
    <div class="rounded-3xl border border-slate-800 bg-slate-950 p-6">
      <h2 class="text-sm font-semibold text-slate-400 uppercase tracking-widest mb-1">Ubah Status Akun</h2>
      <p class="text-xs text-slate-600 mb-5">
        Admin hanya dapat mengubah status akun. Data pribadi pengguna tidak dapat diubah dari panel admin.
      </p>

      <form action="{{ route('admin.users.status', $user) }}" method="POST" class="space-y-4">
        @csrf
        @method('PUT')

        <div>
          <label class="block text-sm font-medium text-slate-300 mb-1.5">Status Akun</label>
          <select name="status"
            class="w-full rounded-2xl border border-slate-700 bg-slate-900 px-4 py-3 text-white outline-none focus:border-amber-500/50 transition"
            required
            onchange="toggleSuspendReason(this.value)">
            <option value="active"    @selected($user->status === 'active')>Aktif</option>
            <option value="suspended" @selected($user->status === 'suspended')>Suspended</option>
          </select>
        </div>

        <div id="suspend-reason-wrap" class="{{ $user->status === 'suspended' ? '' : 'hidden' }}">
          <label class="block text-sm font-medium text-slate-300 mb-1.5">
            Alasan Suspend
            <span class="text-slate-500 font-normal">(akan ditampilkan ke pengguna saat login)</span>
          </label>
          <textarea
            name="suspend_reason"
            rows="3"
            class="w-full rounded-2xl border border-slate-700 bg-slate-900 px-4 py-3 text-white placeholder:text-slate-600 outline-none focus:border-red-500/40 transition resize-none"
            placeholder="Contoh: Melanggar kebijakan marketplace — penjualan item tidak sesuai aturan."
            maxlength="1000"
          >{{ old('suspend_reason', $user->suspend_reason) }}</textarea>
        </div>

        <div class="flex flex-col sm:flex-row gap-3 pt-1">
          <button type="submit"
            class="rounded-2xl bg-amber-500 px-6 py-3 text-sm font-semibold text-slate-950 hover:bg-amber-400 transition">
            Simpan Status
          </button>
          <a href="{{ route('admin.accounts') }}"
            class="rounded-2xl border border-slate-700 px-6 py-3 text-sm font-semibold text-slate-300 hover:border-slate-500 transition text-center">
            Batal
          </a>
        </div>
      </form>
    </div>

    {{-- ── Shop Info (if seller applicant) ─────────────────────────────── --}}
    @if($user->shop_name)
      <div class="rounded-3xl border border-slate-800 bg-slate-900 p-6">
        <h2 class="text-sm font-semibold text-slate-400 uppercase tracking-widest mb-4">Data Toko</h2>
        <div class="grid gap-5 sm:grid-cols-2">
          <div>
            @if($user->shop_photo)
              <img src="{{ $user->shop_photo_url ?? asset('storage/' . $user->shop_photo) }}"
                   alt="Foto toko" class="w-full h-44 object-cover rounded-2xl border border-slate-700 mb-3"
                   onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode($user->shop_name) }}&size=300&background=1e293b&color=94a3b8'" />
            @else
              <div class="w-full h-44 rounded-2xl border border-slate-700 bg-slate-800 flex items-center justify-center text-slate-500 text-sm mb-3">
                Tidak ada foto
              </div>
            @endif
          </div>
          <div class="space-y-3">
            <div>
              <p class="text-xs text-slate-500 uppercase tracking-wider mb-0.5">Nama Toko</p>
              <p class="text-white font-semibold">{{ $user->shop_name }}</p>
            </div>
            <div>
              <p class="text-xs text-slate-500 uppercase tracking-wider mb-0.5">Deskripsi</p>
              <p class="text-slate-300 text-sm leading-relaxed">{{ $user->shop_description ?? '—' }}</p>
            </div>
            <div>
              <p class="text-xs text-slate-500 uppercase tracking-wider mb-0.5">Status Pengajuan</p>
              <span class="inline-flex items-center gap-1.5 text-sm font-semibold
                {{ $user->seller_status === 'approved' ? 'text-emerald-400' :
                   ($user->seller_status === 'pending' ? 'text-amber-400' : 'text-red-400') }}">
                {{ ucfirst($user->seller_status ?? 'none') }}
              </span>
            </div>
          </div>
        </div>

        @if($user->seller_status === 'pending')
          <div class="flex flex-col sm:flex-row gap-3 mt-5 pt-5 border-t border-slate-800">
            <form method="POST" action="{{ route('admin.users.approve-seller', $user) }}">
              @csrf
              <button type="submit"
                class="inline-flex items-center gap-2 rounded-2xl bg-emerald-500 px-5 py-2.5 text-sm font-semibold text-slate-950 hover:bg-emerald-400 transition">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                </svg>
                Approve Seller
              </button>
            </form>
            <button onclick="document.getElementById('reject-inline-wrap').classList.toggle('hidden')"
              class="inline-flex items-center gap-2 rounded-2xl border border-red-500/30 bg-red-500/10 px-5 py-2.5 text-sm font-semibold text-red-400 hover:bg-red-500/20 transition">
              Tolak Pengajuan
            </button>
          </div>

          <div id="reject-inline-wrap" class="hidden mt-4">
            <form method="POST" action="{{ route('admin.users.reject-seller', $user) }}" class="space-y-3">
              @csrf
              <textarea name="rejection_reason" rows="3"
                class="w-full rounded-2xl border border-slate-700 bg-slate-950 px-4 py-3 text-white placeholder:text-slate-600 outline-none resize-none focus:border-red-500/40 transition"
                placeholder="Alasan penolakan (minimal 10 karakter)..." required minlength="10" maxlength="1000">
              </textarea>
              <button type="submit"
                class="rounded-2xl bg-red-600 px-5 py-2.5 text-sm font-semibold text-white hover:bg-red-500 transition">
                Kirim Penolakan
              </button>
            </form>
          </div>
        @endif
      </div>
    @endif

    {{-- Delete User --}}
    @if($user->role !== 'admin')
      <div class="rounded-3xl border border-red-900/30 bg-slate-950 p-6">
        <h2 class="text-sm font-semibold text-red-400 uppercase tracking-widest mb-1">Zona Bahaya</h2>
        <p class="text-xs text-slate-500 mb-4">Menghapus akun bersifat permanen dan tidak dapat dibatalkan.</p>
        <form action="{{ route('admin.users.destroy', $user) }}" method="POST"
              onsubmit="return confirm('Hapus permanen akun {{ addslashes($user->name) }}? Tindakan ini tidak bisa dibatalkan.')">
          @csrf @method('DELETE')
          <button type="submit"
            class="rounded-2xl border border-red-700 bg-red-700/10 px-5 py-2.5 text-sm font-semibold text-red-400 hover:bg-red-700/20 transition">
            Hapus Akun Permanen
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
    wrap.classList.toggle('hidden', status !== 'suspended');
  }
</script>
@endpush
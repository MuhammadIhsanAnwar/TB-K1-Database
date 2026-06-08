@extends('layouts.app')

@section('title', 'Pesan Masuk')

@push('styles')
<style>
  .contact-dashboard {
    background:
      radial-gradient(circle at top left, rgba(96,165,250,0.10), transparent 26%),
      radial-gradient(circle at top right, rgba(245,158,11,0.08), transparent 24%),
      linear-gradient(180deg, rgba(5,9,16,0.82), rgba(5,9,16,0.96));
  }

  .contact-shell {
    position: relative;
    overflow: hidden;
    background: linear-gradient(145deg, rgba(13,20,33,0.94), rgba(8,13,24,0.96));
    border: 1px solid rgba(255,255,255,0.08);
    box-shadow: 0 20px 55px rgba(0,0,0,0.44);
    backdrop-filter: blur(24px) saturate(160%);
  }

  .contact-shell::before {
    content: '';
    position: absolute;
    inset: 0;
    background: linear-gradient(135deg, rgba(96,165,250,0.08), transparent 45%, rgba(245,158,11,0.06));
    pointer-events: none;
  }

  .contact-shell::after {
    content: '';
    position: absolute;
    inset: 1px;
    border-radius: inherit;
    border: 1px solid rgba(255,255,255,0.04);
    pointer-events: none;
  }

  .contact-page-bg {
    background:
      radial-gradient(circle at top left, rgba(96,165,250,0.08), transparent 22%),
      radial-gradient(circle at bottom right, rgba(245,158,11,0.06), transparent 20%);
  }

  .contact-chip {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    border-radius: 999px;
    padding: 0.45rem 0.8rem;
    background: rgba(96,165,250,0.08);
    border: 1px solid rgba(96,165,250,0.18);
    color: #bfdbfe;
  }

  .contact-table thead th {
    font-size: 0.72rem;
    letter-spacing: 0.12em;
    text-transform: uppercase;
    color: #94a3b8;
    background: linear-gradient(180deg, rgba(255,255,255,0.03), rgba(255,255,255,0));
  }

  .contact-table tbody tr {
    transition: background-color 0.2s ease, transform 0.2s ease;
  }

  .contact-table tbody tr:hover {
    background: rgba(255,255,255,0.03);
  }

  .contact-pill {
    display: inline-flex;
    align-items: center;
    gap: 0.35rem;
    border-radius: 999px;
    padding: 0.28rem 0.7rem;
    font-size: 0.72rem;
    font-weight: 700;
    letter-spacing: 0.04em;
  }

  .contact-pill--new { background: rgba(245,158,11,0.12); color: #fcd34d; border: 1px solid rgba(245,158,11,0.2); }
  .contact-pill--read { background: rgba(96,165,250,0.12); color: #93c5fd; border: 1px solid rgba(96,165,250,0.2); }
  .contact-pill--replied { background: rgba(16,185,129,0.12); color: #6ee7b7; border: 1px solid rgba(16,185,129,0.2); }
  .contact-pill--closed { background: rgba(148,163,184,0.12); color: #cbd5e1; border: 1px solid rgba(148,163,184,0.18); }

  .contact-action {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 0.4rem;
    border-radius: 0.9rem;
    border: 1px solid rgba(255,255,255,0.08);
    background: rgba(255,255,255,0.04);
    color: #e2e8f0;
    transition: transform 0.2s ease, border-color 0.2s ease, background-color 0.2s ease;
  }

  .contact-action:hover {
    transform: translateY(-1px);
    border-color: rgba(96,165,250,0.24);
    background: rgba(96,165,250,0.10);
  }

  .contact-empty {
    background: linear-gradient(145deg, rgba(13,20,33,0.94), rgba(8,13,24,0.96));
    border: 1px dashed rgba(255,255,255,0.10);
  }
</style>
@endpush

@section('content')
<div class="min-h-screen py-10 relative overflow-hidden contact-dashboard">
  <div class="absolute top-0 right-1/4 w-96 h-96 bg-brand-500/5 rounded-full blur-[150px] pointer-events-none"></div>
  <div class="absolute bottom-0 left-1/4 w-96 h-96 bg-amber-500/5 rounded-full blur-[150px] pointer-events-none"></div>

  <div class="max-w-7xl mx-auto px-4 space-y-6 relative z-10">
    <div class="flex flex-col gap-4 border-b border-white/5 pb-5">
      <div class="mb-2">
        <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center gap-2 rounded-xl border border-white/5 bg-white/5 hover:bg-white/10 px-4 py-2 text-xs font-bold text-slate-300 transition-all uppercase tracking-widest w-fit">
          <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
          Kembali ke Dasbor
        </a>
      </div>
      <div>
        <div class="flex items-center gap-2 mb-1.5">
          <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span>
          <span class="text-xs font-bold uppercase tracking-widest text-amber-500/80">Admin Inbox</span>
        </div>
        <h1 class="text-3xl font-extrabold text-white tracking-tight">Pesan Masuk</h1>
        <p class="text-slate-400 text-sm mt-0.5">Kelola pesan dari pengguna, tandai status, dan buka detail percakapan dengan cepat.</p>
      </div>
    </div>

    @if(session('success'))
      <div class="contact-shell rounded-2xl p-4">
        <p class="text-sm font-medium text-emerald-300">{{ session('success') }}</p>
      </div>
    @endif

    @if ($errors->any())
      <div class="contact-shell rounded-2xl p-4 border border-rose-500/25">
        <div class="flex items-start gap-3">
          <div class="mt-0.5 text-rose-400">⚠️</div>
          <div>
            <h3 class="text-sm font-bold text-rose-300">Ada kesalahan saat memuat aksi pesan:</h3>
            <ul class="mt-1.5 space-y-1 text-xs text-rose-400/90 font-medium">
              @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
              @endforeach
            </ul>
          </div>
        </div>
      </div>
    @endif

    <div class="contact-shell rounded-3xl overflow-hidden">
      <div class="px-6 pt-6 pb-4 border-b border-white/5 flex items-center justify-between gap-4">
        <div>
          <h2 class="text-sm font-bold uppercase tracking-wider text-white flex items-center gap-2">
            <span class="contact-chip"><span>✉</span><span>Inbox Moderasi</span></span>
          </h2>
          <p class="mt-2 text-xs text-slate-500">Urutkan pesan berdasarkan waktu masuk dan status tindak lanjut.</p>
        </div>
        <div class="hidden sm:flex items-center gap-2 text-xs text-slate-500">
          <span class="contact-pill contact-pill--new">Baru</span>
          <span class="contact-pill contact-pill--read">Dibaca</span>
          <span class="contact-pill contact-pill--replied">Dibalas</span>
        </div>
      </div>

      <div class="overflow-x-auto">
    <table class="w-full text-sm contact-table">
      <thead class="text-left text-slate-400 bg-transparent">
        <tr>
          <th class="px-4 py-3">Dari</th>
          <th class="px-4 py-3">Email</th>
          <th class="px-4 py-3">Topik</th>
          <th class="px-4 py-3">Kategori</th>
          <th class="px-4 py-3">Status</th>
          <th class="px-4 py-3">Diterima</th>
          <th class="px-4 py-3">Aksi</th>
        </tr>
      </thead>
      <tbody>
        @forelse($messages as $msg)
          <tr class="border-t border-white/6 hover:bg-white/2">
            <td class="px-4 py-3 font-medium text-white">{{ $msg->name }}</td>
            <td class="px-4 py-3 text-slate-300">{{ $msg->email }}</td>
            <td class="px-4 py-3 text-slate-200">{{ $msg->subject }}</td>
            <td class="px-4 py-3">
              <span class="contact-pill contact-pill--read">{{ ucfirst($msg->category) }}</span>
            </td>
            <td class="px-4 py-3">
              @php
                $statusClass = match ($msg->status) {
                  'new' => 'contact-pill--new',
                  'read' => 'contact-pill--read',
                  'replied' => 'contact-pill--replied',
                  default => 'contact-pill--closed',
                };
              @endphp
              <span class="contact-pill {{ $statusClass }}">{{ ucfirst($msg->status) }}</span>
            </td>
            <td class="px-4 py-3 text-slate-400">{{ $msg->created_at->diffForHumans() }}</td>
            <td class="px-4 py-3 flex flex-wrap gap-2">
              <a href="{{ route('admin.contact-messages.show', $msg->id) }}" class="contact-action px-3 py-2 text-xs font-bold text-white">
                Lihat
              </a>
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="7" class="px-4 py-6">
              <div class="contact-empty rounded-2xl py-14 text-center text-slate-500">
                <div class="text-4xl mb-3">📭</div>
                <p class="font-bold text-slate-400">Belum ada pesan masuk.</p>
                <p class="text-xs text-slate-600 mt-1">Pesan dari pengguna akan muncul di sini setelah mereka menghubungi tim admin.</p>
              </div>
            </td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>
  <div class="pt-2">
    {{ $messages->links() }}
  </div>
  </div>
</div>
@endsection

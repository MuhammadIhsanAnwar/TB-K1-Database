@extends('layouts.app')

@section('title', 'Detail Pesan')

@push('styles')
<style>
  .contact-detail-bg {
    background:
      radial-gradient(circle at top left, rgba(96,165,250,0.10), transparent 26%),
      radial-gradient(circle at top right, rgba(245,158,11,0.08), transparent 24%),
      linear-gradient(180deg, rgba(5,9,16,0.82), rgba(5,9,16,0.96));
  }

  .contact-detail-shell {
    position: relative;
    overflow: hidden;
    background: linear-gradient(145deg, rgba(13,20,33,0.94), rgba(8,13,24,0.96));
    border: 1px solid rgba(255,255,255,0.08);
    box-shadow: 0 20px 55px rgba(0,0,0,0.44);
    backdrop-filter: blur(24px) saturate(160%);
  }

  .contact-detail-shell::before {
    content: '';
    position: absolute;
    inset: 0;
    background: linear-gradient(135deg, rgba(96,165,250,0.08), transparent 45%, rgba(245,158,11,0.06));
    pointer-events: none;
  }

  .contact-detail-shell::after {
    content: '';
    position: absolute;
    inset: 1px;
    border-radius: inherit;
    border: 1px solid rgba(255,255,255,0.04);
    pointer-events: none;
  }

  .contact-label {
    font-size: 0.72rem;
    letter-spacing: 0.12em;
    text-transform: uppercase;
    color: #94a3b8;
    margin-bottom: 0.35rem;
  }

  .contact-value {
    color: #f8fafc;
    font-weight: 600;
  }

  .contact-textarea {
    background: rgba(5,9,16,0.62);
    border: 1px solid rgba(255,255,255,0.08);
    color: #f8fafc;
    border-radius: 16px;
    transition: border-color 0.2s ease, box-shadow 0.2s ease, transform 0.2s ease;
  }

  .contact-textarea:focus {
    border-color: rgba(245,158,11,0.55);
    box-shadow: 0 0 0 3px rgba(245,158,11,0.12), 0 0 14px rgba(245,158,11,0.18);
    transform: translateY(-1px);
  }

  .contact-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border-radius: 0.95rem;
    padding: 0.7rem 1rem;
    font-weight: 700;
    transition: transform 0.2s ease, box-shadow 0.2s ease, border-color 0.2s ease;
  }

  .contact-btn:hover {
    transform: translateY(-1px);
  }

  .contact-btn--primary {
    background: linear-gradient(135deg, #f59e0b, #f97316);
    color: #0f172a;
    box-shadow: 0 14px 26px rgba(245,158,11,0.18);
  }

  .contact-btn--ghost {
    background: rgba(255,255,255,0.04);
    border: 1px solid rgba(255,255,255,0.08);
    color: #e2e8f0;
  }
</style>
@endpush

@section('content')
<div class="min-h-screen py-10 relative overflow-hidden contact-detail-bg">
  <div class="absolute top-0 right-1/4 w-96 h-96 bg-brand-500/5 rounded-full blur-[150px] pointer-events-none"></div>
  <div class="absolute bottom-0 left-1/4 w-96 h-96 bg-amber-500/5 rounded-full blur-[150px] pointer-events-none"></div>

  <div class="max-w-3xl mx-auto px-4 relative z-10">
  <div class="flex items-center justify-between mb-6">
    <div>
      <div class="flex items-center gap-2 mb-1.5">
        <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span>
        <span class="text-xs font-bold uppercase tracking-widest text-amber-500/80">Contact Detail</span>
      </div>
      <h1 class="text-3xl font-extrabold text-white tracking-tight">Detail Pesan</h1>
    </div>
    <a href="{{ route('admin.contact-messages.index') }}" class="contact-btn contact-btn--ghost text-sm">Kembali</a>
  </div>

  <div class="contact-detail-shell rounded-3xl p-6 sm:p-7">
    <div class="mb-4">
      <div class="contact-label">Dari</div>
      <div class="contact-value">{{ $contactMessage->name }} &lt;{{ $contactMessage->email }}&gt;</div>
    </div>

    <div class="mb-4">
      <div class="contact-label">Topik</div>
      <div class="contact-value">{{ $contactMessage->subject }}</div>
    </div>

    <div class="mb-6">
      <div class="contact-label">Pesan</div>
      <div class="mt-2 whitespace-pre-line text-slate-100 leading-7">{{ $contactMessage->message }}</div>
    </div>

    @if($contactMessage->admin_reply)
      <div class="mb-6">
        <div class="contact-label">Balasan Sebelumnya</div>
        <div class="mt-2 whitespace-pre-line text-slate-100 leading-7">{{ $contactMessage->admin_reply }}</div>
      </div>
    @endif

    <form action="{{ route('admin.contact-messages.reply', $contactMessage->id) }}" method="POST">
      @csrf
      <div class="mb-4">
        <label class="block text-sm text-slate-400 mb-2">Balas ke pengguna</label>
        <textarea name="reply_message" rows="6" class="contact-textarea w-full px-4 py-3">{{ old('reply_message') }}</textarea>
      </div>
      <div class="flex items-center gap-3">
        <button type="submit" class="contact-btn contact-btn--primary">Kirim Balasan</button>
        <a href="{{ route('admin.contact-messages.index') }}" class="contact-btn contact-btn--ghost">Tutup</a>
      </div>
    </form>
  </div>
  </div>
</div>
@endsection

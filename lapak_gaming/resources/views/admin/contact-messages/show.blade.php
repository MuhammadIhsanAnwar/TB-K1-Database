@extends('layouts.app')

@section('title', 'Detail Pesan')

@section('content')
<div class="max-w-3xl mx-auto py-8">
  <div class="flex items-center justify-between mb-6">
    <h1 class="text-2xl font-bold">Detail Pesan</h1>
    <a href="{{ route('admin.contact-messages.index') }}" class="text-sm text-itemku-blue">Kembali</a>
  </div>

  <div class="bg-surface-850 rounded-lg border border-white/6 p-6">
    <div class="mb-4">
      <div class="text-sm text-slate-400">Dari</div>
      <div class="font-semibold">{{ $contactMessage->name }} &lt;{{ $contactMessage->email }}&gt;</div>
    </div>

    <div class="mb-4">
      <div class="text-sm text-slate-400">Topik</div>
      <div class="font-semibold">{{ $contactMessage->subject }}</div>
    </div>

    <div class="mb-6">
      <div class="text-sm text-slate-400">Pesan</div>
      <div class="mt-2 whitespace-pre-line text-slate-100">{{ $contactMessage->message }}</div>
    </div>

    @if($contactMessage->admin_reply)
      <div class="mb-6">
        <div class="text-sm text-slate-400">Balasan Sebelumnya</div>
        <div class="mt-2 whitespace-pre-line text-slate-100">{{ $contactMessage->admin_reply }}</div>
      </div>
    @endif

    <form action="{{ route('admin.contact-messages.reply', $contactMessage->id) }}" method="POST">
      @csrf
      <div class="mb-4">
        <label class="block text-sm text-slate-400 mb-2">Balas ke pengguna</label>
        <textarea name="reply_message" rows="6" class="w-full bg-gray-900 border border-gray-800 text-white rounded px-3 py-2">{{ old('reply_message') }}</textarea>
      </div>
      <div class="flex items-center gap-3">
        <button type="submit" class="btn-accent">Kirim Balasan</button>
        <a href="{{ route('admin.contact-messages.index') }}" class="btn-ghost">Tutup</a>
      </div>
    </form>
  </div>
</div>
@endsection

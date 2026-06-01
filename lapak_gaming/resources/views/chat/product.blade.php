@extends('layouts.app')

@section('title', 'Chat Produk')

@section('content')
@php
  $authId = auth()->id();
  $partnerId = $partner?->id;
@endphp

<div class="rounded-3xl surface-panel p-6">
  <div class="flex items-center justify-between gap-3 flex-wrap">
    <div>
      <h1 class="text-2xl font-black">Chat Produk</h1>
      <p class="text-sm surface-muted">
        {{ $product->name }}
        @if($partner)
          • Lawan chat: {{ $partner->name }}
        @endif
      </p>
    </div>

    <a href="{{ route('products.show', $product->slug) }}" class="text-sm text-brand-400 hover:text-brand-300">Kembali ke produk →</a>
  </div>

  @if($authId === $product->seller_id && $participants->isNotEmpty())
  <div class="mt-4 flex gap-2 flex-wrap">
    @foreach($participants as $participant)
      <a href="{{ route('chat.product', ['product' => $product->id, 'buyer' => $participant->id]) }}"
         class="px-3 py-1.5 rounded-xl text-xs {{ ($partnerId === $participant->id) ? 'bg-brand-600 text-white' : 'surface-panel surface-text' }}">
        {{ $participant->name }}
      </a>
    @endforeach
  </div>
  @endif

  <div id="chat-box"
       data-auth-id="{{ $authId }}"
       data-poll-url="{{ $authId === $product->seller_id && $partnerId ? route('chat.product.poll', ['product' => $product->id, 'buyer' => $partnerId]) : route('chat.product.poll', ['product' => $product->id]) }}"
       class="mt-6 h-120 overflow-y-auto rounded-[1.75rem] surface-panel p-4">
    @forelse($messages as $message)
      @php $isMine = $message->sender_id === $authId; @endphp
      <div class="mb-3 flex {{ $isMine ? 'justify-end' : 'justify-start' }}">
        <div class="max-w-[75%] rounded-2xl px-4 py-3 text-sm" style="background: {{ $isMine ? 'var(--primary)' : 'var(--surface)'}}; color: {{ $isMine ? 'white' : 'var(--text)'}};">
          {{ $message->message }}
        </div>
      </div>
    @empty
      <div class="text-center text-sm text-slate-500 py-8">Belum ada pesan. Mulai chat sekarang.</div>
    @endforelse
  </div>

  @if($partner)
  <form method="POST" action="{{ route('chat.product.store', $product) }}" class="mt-4 flex gap-3">
    @csrf
    @if($authId === $product->seller_id)
      <input type="hidden" name="receiver_id" value="{{ $partnerId }}">
    @endif
    <input id="chat-input" name="message" placeholder="Tulis pesan..."
           class="flex-1 rounded-2xl surface-panel px-4 py-3" />
    <button class="rounded-2xl px-5 py-3 font-bold" style="background:var(--primary); color:var(--text)">Kirim</button>
  </form>
  @else
  <div class="mt-4 text-sm text-slate-500">
    @if($authId === $product->seller_id)
      Belum ada buyer yang memulai chat untuk produk ini.
    @else
      Seller tidak tersedia untuk chat.
    @endif
  </div>
  @endif
</div>

<script>
  const chatBox = document.getElementById('chat-box');
  const authId = Number(chatBox?.dataset?.authId || 0);
  const pollUrl = chatBox?.dataset?.pollUrl || '';

  const escapeHtml = (value) => String(value)
    .replace(/&/g, '&amp;')
    .replace(/</g, '&lt;')
    .replace(/>/g, '&gt;')
    .replace(/\"/g, '&quot;')
    .replace(/'/g, '&#039;');

  const scrollBottom = () => {
    chatBox.scrollTop = chatBox.scrollHeight;
  };

  const renderMessages = (messages) => {
    if (!Array.isArray(messages) || messages.length === 0) {
      return;
    }

    chatBox.innerHTML = messages.map((message) => `
      <div class="mb-3 flex ${Number(message.sender_id) === authId ? 'justify-end' : 'justify-start'}">
        <div class="max-w-[75%] rounded-2xl px-4 py-3 text-sm" style="${Number(message.sender_id) === authId ? 'background:var(--primary);color:white;' : 'background:var(--surface);color:var(--text);'}">
          ${escapeHtml(message.message)}
        </div>
      </div>
    `).join('');

    scrollBottom();
  };

  const pollMessages = async () => {
    if (!pollUrl) {
      return;
    }

    try {
      const response = await fetch(pollUrl, {
        headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
      });
      if (!response.ok) {
        return;
      }
      const payload = await response.json();
      renderMessages(payload.messages);
    } catch (error) {
      console.error(error);
    }
  };

  scrollBottom();
  setInterval(pollMessages, 3000);
</script>
@endsection

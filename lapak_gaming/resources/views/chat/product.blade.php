@extends('layouts.app')

@section('title', 'Chat Produk')

@section('content')
@php
  $authId = auth()->id();
  $partnerId = $partner?->id;
@endphp

<div class="rounded-3xl border border-slate-200 bg-white p-6 dark:border-slate-800 dark:bg-slate-900">
  <div class="flex items-center justify-between gap-3 flex-wrap">
    <div>
      <h1 class="text-2xl font-black">Chat Produk</h1>
      <p class="text-sm text-slate-500">
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
         class="px-3 py-1.5 rounded-xl text-xs {{ ($partnerId === $participant->id) ? 'bg-brand-600 text-white' : 'bg-slate-100 text-slate-700 dark:bg-slate-800 dark:text-slate-200' }}">
        {{ $participant->name }}
      </a>
    @endforeach
  </div>
  @endif

  <div id="chat-box"
       data-auth-id="{{ $authId }}"
       data-poll-url="{{ route('chat.product.poll', ['product' => $product->id, 'buyer' => $partnerId]) }}"
       class="mt-6 h-120 overflow-y-auto rounded-[1.75rem] bg-slate-50 p-4 dark:bg-slate-950/40">
    @forelse($messages as $message)
      <div class="mb-3 flex {{ $message->sender_id === $authId ? 'justify-end' : 'justify-start' }}">
        <div class="max-w-[75%] rounded-2xl px-4 py-3 text-sm {{ $message->sender_id === $authId ? 'bg-slate-950 text-white dark:bg-white dark:text-slate-950' : 'bg-white text-slate-900 dark:bg-slate-900 dark:text-white' }}">
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
           class="flex-1 rounded-2xl border border-slate-200 px-4 py-3 dark:border-slate-700 dark:bg-slate-950" />
    <button class="rounded-2xl bg-slate-950 px-5 py-3 font-bold text-white dark:bg-white dark:text-slate-950">Kirim</button>
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
        <div class="max-w-[75%] rounded-2xl px-4 py-3 text-sm ${Number(message.sender_id) === authId ? 'bg-slate-950 text-white dark:bg-white dark:text-slate-950' : 'bg-white text-slate-900 dark:bg-slate-900 dark:text-white'}">
          ${escapeHtml(message.message)}
        </div>
      </div>
    `).join('');

    scrollBottom();
  };

  const pollMessages = async () => {
    if (!pollUrl || !pollUrl.includes('buyer=')) {
      return;
    }

    const response = await fetch(pollUrl, {
      headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
    });
    const payload = await response.json();
    renderMessages(payload.messages);
  };

  scrollBottom();
  setInterval(pollMessages, 3000);
</script>
@endsection

@extends('layouts.app')

@php
    $invoiceNumber = data_get($order, 'invoice_number', '-');
    $orderStatus = data_get($order, 'status', '-');
@endphp

@section('title', 'Chat Order '.$invoiceNumber)

@section('content')
    <div class="rounded-4xl border border-slate-200 bg-white p-6 dark:border-slate-800 dark:bg-slate-900">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-black">Chat Order</h1>
                <p class="text-sm text-slate-500">{{ $invoiceNumber }} • {{ $orderStatus }}</p>
            </div>
            <div class="text-sm text-slate-500">AJAX polling 3 detik</div>
        </div>

        <div id="chat-box" data-auth-id="{{ auth()->id() }}" data-poll-url="{{ route('chat.poll', $order) }}" class="mt-6 h-120 overflow-y-auto rounded-[1.75rem] bg-slate-50 p-4 dark:bg-slate-950/40">
            @foreach ($messages as $message)
                <div class="mb-3 flex {{ $message->sender_id === auth()->id() ? 'justify-end' : 'justify-start' }}">
                    <div class="max-w-[75%] rounded-2xl px-4 py-3 text-sm {{ $message->sender_id === auth()->id() ? 'bg-slate-950 text-white dark:bg-white dark:text-slate-950' : 'bg-white text-slate-900 dark:bg-slate-900 dark:text-white' }}">
                        {{ $message->message }}
                    </div>
                </div>
            @endforeach
        </div>

        <form method="POST" action="{{ route('chat.store', $order) }}" class="mt-4 flex gap-3">
            @csrf
            <input id="chat-input" name="message" placeholder="Tulis pesan..." class="flex-1 rounded-2xl border border-slate-200 px-4 py-3 dark:border-slate-700 dark:bg-slate-950" />
            <button class="rounded-2xl bg-slate-950 px-5 py-3 font-bold text-white dark:bg-white dark:text-slate-950">Kirim</button>
        </form>
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
@extends('layouts.app')

@section('title', 'Chat — ' . ($conversation->partner(auth()->id())?->name ?? 'Percakapan'))

@push('styles')
<style>
.chat-layout { display: flex; height: calc(100vh - 100px); max-height: 800px; border-radius: 16px; overflow: hidden; border: 1px solid rgba(30,45,69,.8); }
.chat-sidebar { width: 280px; shrink: 0; border-right: 1px solid rgba(30,45,69,.8); display: flex; flex-direction: column; background: #060a12; }
.chat-main { flex: 1; display: flex; flex-direction: column; background: #090e1a; min-width: 0; }
.conv-item { padding: 12px 14px; cursor: pointer; transition: background .12s; border-bottom: 1px solid rgba(30,45,69,.4); text-decoration: none; display: block; }
.conv-item:hover { background: rgba(30,45,69,.5); }
.conv-item.active { background: rgba(37,99,235,.12); border-left: 3px solid #3b82f6; }
.msg-bubble { max-width: 72%; word-break: break-word; }
.bubble-mine { background: linear-gradient(135deg, #1d4ed8, #2563eb); color: #fff; border-radius: 18px 18px 4px 18px; }
.bubble-theirs { background: rgba(17,24,39,.9); border: 1px solid rgba(30,45,69,.8); color: #e2e8f0; border-radius: 18px 18px 18px 4px; }
.chat-input-wrap { border-top: 1px solid rgba(30,45,69,.8); padding: 14px 16px; background: #060a12; }
.chat-input { flex: 1; background: rgba(17,24,39,.8); border: 1px solid rgba(30,45,69,.8); border-radius: 999px; padding: 10px 18px; color: #e2e8f0; font-size: .9rem; outline: none; transition: border-color .15s; }
.chat-input:focus { border-color: #3b82f6; }
.send-btn { width: 42px; height: 42px; border-radius: 50%; background: #2563eb; border: none; cursor: pointer; display: flex; align-items: center; justify-content: center; transition: background .15s; flex-shrink: 0; }
.send-btn:hover { background: #1d4ed8; }
.date-divider { text-align: center; margin: 12px 0; }
.date-divider span { background: rgba(30,45,69,.6); border: 1px solid rgba(30,45,69,1); padding: 3px 12px; border-radius: 999px; font-size: .7rem; color: #64748b; }
.unread-badge { background: #3b82f6; color: #fff; border-radius: 999px; font-size: .6rem; font-weight: 800; min-width: 16px; height: 16px; display: inline-flex; align-items: center; justify-content: center; padding: 0 4px; }
@media (max-width: 640px) {
    .chat-sidebar { display: none; }
}
</style>
@endpush

@section('content')
@php
    $user    = auth()->user();
    $partner = $conversation->partner($user->id);
    $product = $conversation->product;
    $order   = $conversation->order;
@endphp

<div class="max-w-7xl mx-auto px-4 py-4">
<div class="chat-layout">

    {{-- Sidebar --}}
    <div class="chat-sidebar">
        <div class="p-3 border-b border-slate-800">
            <a href="{{ route('chat.inbox') }}" class="flex items-center gap-2 text-sm text-slate-400 hover:text-white mb-3 transition-colors">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Semua Pesan
            </a>
            <div class="relative">
                <svg class="absolute left-2.5 top-1/2 -translate-y-1/2 w-3.5 h-3.5 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                <input type="text" id="sideSearch" placeholder="Cari..."
                    class="w-full pl-8 pr-3 py-1.5 rounded-xl bg-slate-900 border border-slate-700 text-xs text-slate-300 placeholder-slate-600 focus:outline-none focus:border-brand-500">
            </div>
        </div>

        <div class="overflow-y-auto flex-1" id="sideList">
            @foreach($sidebarConversations as $conv)
            @php
                $p2     = $conv->partner($user->id);
                $unread = $conv->unreadFor($user->id);
                $active = $conv->id === $conversation->id;
            @endphp
            <a href="{{ route('chat.show', $conv) }}"
               class="conv-item {{ $active ? 'active' : '' }}"
               data-name="{{ strtolower($p2?->name ?? '') }}">
                <div class="flex gap-2.5 items-start">
                    <div class="relative shrink-0">
                        <img src="{{ $p2?->avatar_url ?? 'https://ui-avatars.com/api/?name=?' }}"
                             class="w-9 h-9 rounded-full object-cover" alt="">
                        @if($unread > 0)
                        <span class="absolute -top-1 -right-1 unread-badge">{{ $unread > 9 ? '9+' : $unread }}</span>
                        @endif
                    </div>
                    <div class="min-w-0 flex-1">
                        <div class="flex justify-between">
                            <span class="text-sm font-semibold text-white truncate">{{ $p2?->name ?? '?' }}</span>
                            <span class="text-xs text-slate-600 shrink-0">{{ $conv->last_message_at?->format('H:i') }}</span>
                        </div>
                        <p class="text-xs text-slate-500 truncate mt-0.5 {{ $unread > 0 ? 'text-slate-300' : '' }}">
                            {{ $conv->last_message ? mb_substr($conv->last_message, 0, 40) : '...' }}
                        </p>
                    </div>
                </div>
            </a>
            @endforeach
        </div>
    </div>

    {{-- Main Chat --}}
    <div class="chat-main">

        {{-- Chat Header --}}
        <div class="px-5 py-3.5 border-b border-slate-800 flex items-center gap-3 bg-slate-950/50">
            <img src="{{ $partner?->avatar_url ?? 'https://ui-avatars.com/api/?name=?' }}"
                 class="w-10 h-10 rounded-full object-cover shrink-0" alt="{{ $partner?->name }}">
            <div class="flex-1 min-w-0">
                <h2 class="font-bold text-white text-sm">{{ $partner?->name ?? 'Pengguna' }}</h2>
                @if($product)
                <p class="text-xs text-brand-400 truncate">🎮 {{ $product->name }}</p>
                @elseif($order)
                <p class="text-xs text-amber-400">📦 Order #{{ $order->order_code ?? $order->id }}</p>
                @endif
            </div>
            @if($product)
            <a href="{{ route('products.show', $product->slug) }}"
               class="shrink-0 flex items-center gap-2 px-3 py-1.5 rounded-xl border border-slate-700 hover:border-slate-500 transition-colors text-xs text-slate-300">
                Lihat Produk
                <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
            </a>
            @endif
        </div>

        {{-- Product Card Context --}}
        @if($product)
        <div class="px-4 py-2.5 bg-brand-900/20 border-b border-brand-800/30 flex items-center gap-3">
            @if($product->image)
            <img src="{{ asset('storage/' . $product->image) }}" class="w-12 h-12 rounded-xl object-cover shrink-0 border border-slate-700" alt="">
            @endif
            <div class="flex-1 min-w-0">
                <p class="text-sm font-bold text-white truncate">{{ $product->name }}</p>
                <p class="text-brand-400 text-sm font-bold">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
            </div>
            <a href="{{ route('products.show', $product->slug) }}"
               class="shrink-0 px-3 py-1.5 rounded-xl bg-brand-600 text-white text-xs font-bold hover:bg-brand-500 transition-colors">
                Beli
            </a>
        </div>
        @endif

        {{-- Messages Area --}}
        <div id="messagesArea" class="flex-1 overflow-y-auto px-4 py-4 space-y-1">
            @php $lastDate = null; @endphp
            @foreach($messages as $msg)
            @php
                $msgDate = $msg->created_at->format('d M Y');
                $isMine  = $msg->sender_id === $user->id;
            @endphp
            @if($msgDate !== $lastDate)
            <div class="date-divider"><span>{{ $msgDate }}</span></div>
            @php $lastDate = $msgDate; @endphp
            @endif

            <div class="flex {{ $isMine ? 'justify-end' : 'justify-start' }} items-end gap-2 mb-1"
                 data-msg-id="{{ $msg->id }}">
                @if(!$isMine)
                <img src="{{ $msg->sender?->avatar_url }}" class="w-7 h-7 rounded-full object-cover shrink-0 mb-1" alt="">
                @endif
                <div class="msg-bubble">
                    <div class="px-4 py-2.5 {{ $isMine ? 'bubble-mine' : 'bubble-theirs' }}">
                        <p class="text-sm leading-relaxed">{{ $msg->message }}</p>
                    </div>
                    <div class="flex {{ $isMine ? 'justify-end' : 'justify-start' }} items-center gap-1 mt-1 px-1">
                        <span class="text-xs text-slate-600">{{ $msg->created_at->format('H:i') }}</span>
                        @if($isMine)
                        <svg class="w-3 h-3 {{ $msg->is_read ? 'text-brand-400' : 'text-slate-600' }}" fill="currentColor" viewBox="0 0 16 16">
                            <path d="M12.354 4.354a.5.5 0 00-.708-.708L5 11.293 1.854 8.146a.5.5 0 10-.708.708l3.5 3.5a.5.5 0 00.708 0l7-7zm-4.208 7.209l-.896-.897.707-.707.543.543 6.646-6.647a.5.5 0 01.708.708l-7 7a.5.5 0 01-.708 0z"/>
                        </svg>
                        @endif
                    </div>
                </div>
                @if($isMine)
                <img src="{{ $user->avatar_url }}" class="w-7 h-7 rounded-full object-cover shrink-0 mb-1" alt="">
                @endif
            </div>
            @endforeach

            {{-- Typing indicator placeholder --}}
            <div id="typingIndicator" class="hidden flex items-center gap-2 py-2">
                <img src="{{ $partner?->avatar_url }}" class="w-7 h-7 rounded-full object-cover shrink-0" alt="">
                <div class="bubble-theirs px-4 py-2.5 rounded-2xl">
                    <div class="flex gap-1 items-center h-4">
                        <span class="w-2 h-2 bg-slate-400 rounded-full animate-bounce" style="animation-delay:0s"></span>
                        <span class="w-2 h-2 bg-slate-400 rounded-full animate-bounce" style="animation-delay:.15s"></span>
                        <span class="w-2 h-2 bg-slate-400 rounded-full animate-bounce" style="animation-delay:.3s"></span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Input Area --}}
        <div class="chat-input-wrap">
            <div class="flex items-center gap-3">
                <input type="text" id="msgInput" class="chat-input"
                       placeholder="Tulis pesan..."
                       autocomplete="off" maxlength="2000">
                <button id="sendBtn" class="send-btn" onclick="sendMessage()">
                    <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                    </svg>
                </button>
            </div>
            <p class="text-xs text-slate-600 mt-2 text-center">Pesan dienkripsi end-to-end</p>
        </div>

    </div>
</div>
</div>

@push('scripts')
<script>
const CONV_ID = {{ $conversation->id }};
const AUTH_ID = {{ $user->id }};
const SEND_URL = '{{ route('chat.send', $conversation) }}';
const POLL_URL = '{{ route('chat.poll', $conversation) }}';
const CSRF    = document.querySelector('meta[name="csrf-token"]').content;

let lastId = {{ $messages->last()?->id ?? 0 }};
let pollTimer;

const messagesArea = document.getElementById('messagesArea');
const msgInput     = document.getElementById('msgInput');
const sendBtn      = document.getElementById('sendBtn');

// Auto scroll to bottom on load
function scrollBottom(smooth = true) {
    messagesArea.scrollTo({ top: messagesArea.scrollHeight, behavior: smooth ? 'smooth' : 'instant' });
}
scrollBottom(false);

// Search sidebar
document.getElementById('sideSearch')?.addEventListener('input', function() {
    const q = this.value.toLowerCase();
    document.querySelectorAll('#sideList .conv-item').forEach(el => {
        el.style.display = (el.dataset.name || '').includes(q) ? '' : 'none';
    });
});

// Send message
async function sendMessage() {
    const text = msgInput.value.trim();
    if (!text) return;

    msgInput.value = '';
    sendBtn.disabled = true;

    // Optimistic UI
    appendMessage({
        id: 'tmp-' + Date.now(),
        is_mine: true,
        message: text,
        time: new Date().toLocaleTimeString('id-ID', {hour:'2-digit', minute:'2-digit'}),
        avatar: '{{ $user->avatar_url }}',
        is_read: false,
    });

    try {
        const res = await fetch(SEND_URL, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' },
            body: JSON.stringify({ message: text }),
        });
        const data = await res.json();
        if (data.message) {
            // Replace optimistic with real
            const tmp = document.querySelector('[data-msg-id="tmp-' + (Date.now() - 1) + '"]');
            if (tmp) tmp.remove();
            lastId = data.message.id;
        }
    } catch(e) {
        console.error(e);
    } finally {
        sendBtn.disabled = false;
        msgInput.focus();
    }
}

msgInput.addEventListener('keydown', function(e) {
    if (e.key === 'Enter' && !e.shiftKey) {
        e.preventDefault();
        sendMessage();
    }
});

// Poll for new messages
async function poll() {
    try {
        const url = `${POLL_URL}?since=${lastId}`;
        const res = await fetch(url, {
            headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': CSRF }
        });
        const data = await res.json();
        if (data.messages && data.messages.length) {
            data.messages.forEach(m => {
                if (!document.querySelector(`[data-msg-id="${m.id}"]`)) {
                    appendMessage(m);
                }
            });
            lastId = data.last_id || lastId;
        }
    } catch(e) {}
    pollTimer = setTimeout(poll, 3000);
}

function appendMessage(m) {
    const isMine = m.is_mine || m.sender_id === AUTH_ID;
    const div = document.createElement('div');
    div.className = `flex ${isMine ? 'justify-end' : 'justify-start'} items-end gap-2 mb-1`;
    div.dataset.msgId = m.id;

    const avatarSrc = m.avatar || `https://ui-avatars.com/api/?name=?&background=6366f1&color=fff`;
    const avatarHtml = `<img src="${avatarSrc}" class="w-7 h-7 rounded-full object-cover shrink-0 mb-1" alt="">`;
    const readIcon = isMine ? `<svg class="w-3 h-3 ${m.is_read ? 'text-blue-400' : 'text-slate-600'}" fill="currentColor" viewBox="0 0 16 16"><path d="M12.354 4.354a.5.5 0 00-.708-.708L5 11.293 1.854 8.146a.5.5 0 10-.708.708l3.5 3.5a.5.5 0 00.708 0l7-7zm-4.208 7.209l-.896-.897.707-.707.543.543 6.646-6.647a.5.5 0 01.708.708l-7 7a.5.5 0 01-.708 0z"/></svg>` : '';

    div.innerHTML = `
        ${!isMine ? avatarHtml : ''}
        <div class="msg-bubble">
            <div class="px-4 py-2.5 ${isMine ? 'bubble-mine' : 'bubble-theirs'}">
                <p class="text-sm leading-relaxed">${escHtml(m.message)}</p>
            </div>
            <div class="flex ${isMine ? 'justify-end' : 'justify-start'} items-center gap-1 mt-1 px-1">
                <span class="text-xs text-slate-600">${m.time}</span>
                ${readIcon}
            </div>
        </div>
        ${isMine ? avatarHtml : ''}
    `;

    const typing = document.getElementById('typingIndicator');
    messagesArea.insertBefore(div, typing);
    scrollBottom();
}

function escHtml(str) {
    return str.replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
}

// Start polling
pollTimer = setTimeout(poll, 3000);
document.addEventListener('visibilitychange', () => {
    if (document.hidden) clearTimeout(pollTimer);
    else pollTimer = setTimeout(poll, 1000);
});
</script>
@endpush
@endsection
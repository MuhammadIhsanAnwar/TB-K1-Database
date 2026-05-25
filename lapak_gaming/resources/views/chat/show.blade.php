@extends('layouts.app')

@section('title', 'Chat — ' . ($conversation->partner(auth()->id())?->name ?? 'Percakapan'))

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.1/cropper.min.css" />
<style>
/* WhatsApp/Line Style Chat */
.chat-layout {
    display: flex;
    height: calc(100vh - 80px);
    border-radius: 12px;
    overflow: hidden;
    background: #0a0a0a;
    border: 1px solid #1a1a1a;
    box-shadow: 0 8px 32px rgba(0,0,0,0.5);
}

.chat-sidebar {
    width: 320px;
    background: #111111;
    border-right: 1px solid #1a1a1a;
    display: flex;
    flex-direction: column;
}

.chat-main {
    flex: 1;
    display: flex;
    flex-direction: column;
    background: #0a0a0a;
    min-width: 0;
}

.chat-input {
    transition: all .15s ease;
}

.chat-input:focus {
    transform: translateY(-1px);
}

/* Sidebar Header */
.sidebar-header {
    padding: 16px;
    background: #111111;
    border-bottom: 1px solid #1a1a1a;
}

.sidebar-header h3 {
    font-size: 18px;
    font-weight: 600;
    color: #ffffff;
    margin-bottom: 12px;
}

.search-input {
    width: 100%;
    background: #1a1a1a;
    border: 1px solid #2a2a2a;
    border-radius: 8px;
    padding: 8px 12px;
    color: #ffffff;
    font-size: 14px;
    outline: none;
}

.search-input:focus {
    border-color: #2563eb;
    box-shadow: 0 0 0 2px rgba(37, 99, 235, 0.2);
}

/* Conversation Items */
.conv-item {
    display: flex;
    align-items: center;
    padding: 12px 16px;
    cursor: pointer;
    transition: background-color 0.15s;
    border-bottom: 1px solid #1a1a1a;
    text-decoration: none;
    position: relative;
}

.conv-item:hover {
    background: #1a1a1a;
}

.conv-item.active {
    background: #1e3a8a;
}

.conv-item.active::before {
    content: '';
    position: absolute;
    left: 0;
    top: 0;
    bottom: 0;
    width: 3px;
    background: #2563eb;
}

.conv-avatar {
    width: 48px;
    height: 48px;
    border-radius: 50%;
    object-fit: cover;
    margin-right: 12px;
    border: 2px solid #2a2a2a;
}

.conv-content {
    flex: 1;
    min-width: 0;
}

.conv-name {
    font-size: 16px;
    font-weight: 600;
    color: #ffffff;
    margin-bottom: 4px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.conv-preview {
    font-size: 14px;
    color: #888888;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.conv-time {
    font-size: 12px;
    color: #666666;
    margin-left: 8px;
    flex-shrink: 0;
}

.unread-badge {
    background: #2563eb;
    color: #ffffff;
    border-radius: 10px;
    font-size: 11px;
    font-weight: 600;
    min-width: 18px;
    height: 18px;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 0 6px;
    margin-left: auto;
}

/* Chat Header */
.chat-header {
    padding: 16px 20px;
    background: #111111;
    border-bottom: 1px solid #1a1a1a;
    display: flex;
    align-items: center;
    gap: 12px;
}

.chat-header-avatar {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    object-fit: cover;
    border: 2px solid #2a2a2a;
}

.chat-header-info h2 {
    font-size: 16px;
    font-weight: 600;
    color: #ffffff;
    margin: 0;
}

.chat-header-info p {
    font-size: 12px;
    color: #888888;
    margin: 4px 0 0 0;
}

/* Product Context */
.product-context {
    padding: 12px 20px;
    background: #1a1a1a;
    border-bottom: 1px solid #2a2a2a;
    display: flex;
    align-items: center;
    gap: 12px;
}

.product-context img {
    width: 48px;
    height: 48px;
    border-radius: 8px;
    object-fit: cover;
}

.product-info h3 {
    font-size: 14px;
    font-weight: 600;
    color: #ffffff;
    margin: 0 0 4px 0;
}

.product-price {
    font-size: 14px;
    font-weight: 600;
    color: #2563eb;
    margin: 0;
}

/* Messages Area */
.messages-area {
    flex: 1;
    overflow-y: auto;
    scroll-behavior: smooth;
    padding: 20px;
    background: #0a0a0a;
    background-image:
        radial-gradient(circle at 25% 25%, rgba(37, 99, 235, 0.05) 0%, transparent 50%),
        radial-gradient(circle at 75% 75%, rgba(249, 115, 22, 0.05) 0%, transparent 50%);
}

.message-group {
    margin-bottom: 16px;
}

.message-item {
    display: flex;
    margin-bottom: 4px;
    align-items: flex-end;
    transition: transform 0.18s ease, opacity 0.18s ease;
}

.message-item.mine {
    justify-content: flex-end;
}

.message-item.theirs {
    justify-content: flex-start;
}

.message-avatar {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    object-fit: cover;
    margin-right: 8px;
    border: 1px solid #2a2a2a;
    flex-shrink: 0;
}

.message-avatar.mine {
    margin-left: 8px;
    margin-right: 0;
}

.message-bubble {
    max-width: 70%;
    position: relative;
}

.message-bubble.mine .bubble-content {
    background: #2563eb;
    color: #ffffff;
    border-radius: 18px 18px 4px 18px;
    padding: 8px 12px;
    box-shadow: 0 2px 8px rgba(37, 99, 235, 0.3);
    transition: background 0.18s ease, transform 0.18s ease;
}

.message-bubble.theirs .bubble-content {
    background: #202c33;
    color: #ffffff;
    border-radius: 18px 18px 18px 4px;
    padding: 8px 12px;
    border: 1px solid #2a2a2a;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.3);
}

.message-text {
    font-size: 14px;
    line-height: 1.4;
    word-wrap: break-word;
}

.message-time {
    font-size: 11px;
    color: #666666;
    margin-top: 4px;
    text-align: right;
}

.message-time.mine {
    text-align: right;
}

.message-time.theirs {
    text-align: left;
}

.message-status {
    margin-left: 4px;
    display: inline-flex;
    align-items: center;
}

.message-status svg {
    width: 12px;
    height: 12px;
}

.message-item {
    animation: msgIn .18s ease;
}

@keyframes msgIn {
    from {
        opacity: 0;
        transform: translateY(10px) scale(.98);
    }
    to {
        opacity: 1;
        transform: translateY(0) scale(1);
    }
}

/* Date Divider */
.date-divider {
    text-align: center;
    margin: 20px 0;
}

.date-divider span {
    background: #1a1a1a;
    color: #888888;
    padding: 4px 12px;
    border-radius: 12px;
    font-size: 12px;
    border: 1px solid #2a2a2a;
}

/* Typing Indicator */
.typing-indicator {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 8px 0;
}

.typing-avatar {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    object-fit: cover;
    border: 1px solid #2a2a2a;
}

.typing-bubble {
    background: #1a1a1a;
    border: 1px solid #2a2a2a;
    border-radius: 18px 18px 18px 4px;
    padding: 8px 12px;
}

.typing-dots {
    display: flex;
    gap: 4px;
    align-items: center;
    height: 16px;
}

.typing-dot {
    width: 6px;
    height: 6px;
    background: #888888;
    border-radius: 50%;
    animation: typing 1.4s infinite ease-in-out;
}

.typing-dot:nth-child(2) {
    animation-delay: 0.2s;
}

.typing-dot:nth-child(3) {
    animation-delay: 0.4s;
}

@keyframes typing {
    0%, 60%, 100% {
        transform: translateY(0);
        opacity: 0.4;
    }
    30% {
        transform: translateY(-8px);
        opacity: 1;
    }
}

/* Input Area */
.chat-input-area {
    padding: 16px 20px;
    background: #111111;
    border-top: 1px solid #1a1a1a;
}

.input-container {
    display: flex;
    align-items: center;
    gap: 12px;
    background: #1a1a1a;
    border: 1px solid #2a2a2a;
    border-radius: 24px;
    padding: 8px 16px;
}

.chat-input {
    flex: 1;
    background: transparent;
    border: none;
    color: #ffffff;
    font-size: 14px;
    outline: none;
    resize: none;
    min-height: 20px;
    max-height: 100px;
}

.send-button {
    width: 36px;
    height: 36px;
    border-radius: 50%;
    background: #2563eb;
    border: none;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: background-color 0.15s, transform 0.15s;
    flex-shrink: 0;
    box-shadow: 0 4px 14px rgba(37,99,235,.35);
}

.send-button:hover {
    transform: scale(1.05);
}

.send-button:active {
    transform: scale(.96);
}

#cancelEditBtn {
    min-width: 32px;
    min-height: 32px;
    color: #9ca3af;
    background: transparent;
    border: 1px solid transparent;
    border-radius: 50%;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
}

#cancelEditBtn:hover {
    background: rgba(255,255,255,0.08);
    border-color: rgba(255,255,255,0.16);
    color: #ffffff;
}

/* Responsive */
@media (max-width: 768px) {
    .chat-layout {
        height: 100vh;
        border-radius: 0;
    }

    .chat-sidebar {
        display: none;
    }

    .messages-area {
        padding: 16px;
    }

    .message-bubble {
        max-width: 85%;
    }
}

@media (max-width: 640px) {
    .chat-header,
    .product-context,
    .chat-input-area {
        padding-left: 16px;
        padding-right: 16px;
    }

    .messages-area {
        padding: 12px;
    }
}
</style>
@endpush

@section('content')
@php
    $user    = auth()->user();
    // Identitas Partner untuk Header Utama
    $partner = $role === 'seller' ? $conversation->buyer : $conversation->seller;
    $product = $conversation->product;
    $order   = $conversation->order;
    $chatConfig = [
        'convId' => $conversation->id,
        'authId' => $user->id,
        'sendUrl' => route('chat.send', $conversation),
        'pollUrl' => route('chat.poll', $conversation),
        'lastId' => $messages->last()?->id ?? 0,
        'avatarUrl' => $user->avatar_url,
    ];
@endphp

<div class="max-w-7xl mx-auto px-4 py-4">
<div class="chat-layout">

    {{-- Sidebar --}}
    <div class="chat-sidebar">
        <div class="sidebar-header">
            <a href="{{ route('chat.inbox', ['tab' => $role]) }}" class="flex items-center gap-2 text-slate-400 hover:text-white mb-3 transition-colors">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Kembali ke Inbox
            </a>
            <h3>Pesan</h3>
            <input type="text" id="sideSearch" placeholder="Cari percakapan..." class="search-input">
        </div>

        <div class="overflow-y-auto flex-1" id="sideList">
            @foreach($sidebarConversations as $conv)
            @php
                // 🔥 PERBAIKAN 1: Paksa panggil nama lawan bicara berdasarkan Role (bukan ID) agar tidak muncul nama sendiri 2x
                $p2     = $role === 'seller' ? $conv->buyer : $conv->seller;
                $unread = $conv->unreadFor($user->id);
                $active = $conv->id === $conversation->id;
            @endphp
            <a href="{{ route('chat.show', [
                    'conversation' => $conv->id,
                    'role' => $role
                ]) }}"
               class="conv-item {{ $active ? 'active' : '' }}"
               data-name="{{ strtolower($p2?->name ?? '') }}">
                <img src="{{ $p2?->avatar_url ?? 'https://ui-avatars.com/api/?name=?' }}"
                     class="conv-avatar" alt="">
                <div class="conv-content">
                    <div class="flex justify-between items-center">
                        <span class="conv-name">{{ $p2?->name ?? '?' }}</span>
                        <span class="conv-time">{{ $conv->last_message_at?->format('H:i') }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="conv-preview">
                            {{ $conv->last_message ? mb_substr($conv->last_message, 0, 30) : '...' }}
                        </span>
                        @if($unread > 0)
                        <span class="unread-badge">{{ $unread > 9 ? '9+' : $unread }}</span>
                        @endif
                    </div>
                </div>
            </a>
            @endforeach
        </div>
    </div>

    {{-- Main Chat --}}
    <div class="chat-main">

        {{-- Chat Header --}}
        <div class="chat-header">
            <img src="{{ $partner?->avatar_url ?? 'https://ui-avatars.com/api/?name=?' }}"
                 class="chat-header-avatar" alt="{{ $partner?->name }}">
            <div class="chat-header-info">
                <h2>{{ $partner?->name ?? 'Pengguna' }}</h2>
                <p class="text-xs text-blue-400 mt-1">
                    {{ $role === 'seller' ? 'Mode Seller' : 'Mode Buyer' }}
                </p>
                @if($product)
                <p>🎮 {{ $product->name }}</p>
                @elseif($order)
                <p>📦 Order #{{ $order->order_code ?? $order->id }}</p>
                @else
                <p>Online</p>
                @endif
            </div>
            @if($product)
            <a href="{{ route('products.show', $product->slug) }}"
               class="ml-auto px-4 py-2 rounded-lg bg-blue-600 text-white text-sm font-medium hover:bg-blue-700 transition-colors">
                Lihat Produk
            </a>
            @endif
        </div>

        {{-- Product Card Context --}}
        @if($product)
        <div class="product-context">
            <div class="overflow-hidden rounded-xl aspect-[16/9] bg-black/40 border border-white/5">
                <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
            </div>
            <div class="product-info">
                <h3>{{ $product->name }}</h3>
                <p class="product-price">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
            </div>
            
            {{-- 🔥 PERBAIKAN 2: Tombol Pesan Sekarang hanya muncul jika rolenya adalah Buyer --}}
            @if($role === 'buyer')
                <a href="{{ route('checkout.product', $product) }}"
                   class="ml-auto px-4 py-2 rounded-lg bg-emerald-600 text-white text-sm font-bold hover:bg-emerald-500 transition-colors shadow-lg shadow-emerald-500/20">
                    Pesan Sekarang
                </a>
            @else
                <span class="ml-auto px-4 py-2 rounded-lg bg-slate-800 text-slate-400 text-xs font-bold border border-slate-700 uppercase tracking-widest">
                    Produk Jualan Anda
                </span>
            @endif
        </div>
        @endif

        {{-- Messages Area --}}
        <div id="messagesArea" class="messages-area">
            @php $lastDate = null; @endphp
            @foreach($messages as $msg)
            @php
                $msgDate = $msg->created_at->format('d M Y');
                $isMine  = (int)$msg->sender_id === $user->id;
            @endphp
            @if($msgDate !== $lastDate)
            <div class="date-divider"><span>{{ $msgDate }}</span></div>
            @php $lastDate = $msgDate; @endphp
            @endif

            <div class="message-item {{ $isMine ? 'mine' : 'theirs' }}" data-msg-id="{{ $msg->id }}">
                @if(!$isMine)
                <img src="{{ $msg->sender?->avatar_url }}" class="message-avatar" alt="">
                @endif
               <div class="message-bubble {{ $isMine ? 'mine' : 'theirs' }}">
    <div class="bubble-content group relative">
        
        {{-- Tombol Opsi (Hanya muncul jika pesan milik sendiri) --}}
        @if($isMine)
        <div class="absolute -left-10 top-0 hidden group-hover:flex gap-1">
            <button onclick="prepareEdit('{{ $msg->id }}')" class="p-1 text-gray-500 hover:text-blue-400">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
            </button>
            <button onclick="confirmDelete('{{ $msg->id }}')" class="p-1 text-gray-500 hover:text-red-400">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
            </button>
        </div>
        @endif

        {{-- Cek Jika Gambar --}}
        @if($msg->image_url)
            <div class="max-w-xs rounded-lg mb-2 overflow-hidden aspect-[16/9] cursor-pointer" onclick="window.open(this.querySelector('img').src)">
                <img src="{{ $msg->image_url }}" class="w-full h-full object-cover">
            </div>
        @endif
        
        <p class="message-text" id="text-{{ $msg->id }}">{{ $msg->message }}</p>
    </div>
    
    {{-- Meta data (waktu/status) --}}
    <div class="message-time {{ $isMine ? 'mine' : 'theirs' }}">
        <span>{{ $msg->created_at->format('H:i') }}</span>
    </div>
</div>
                @if($isMine)
                <img src="{{ $user->avatar_url }}" class="message-avatar mine" alt="">
                @endif
            </div>
            @endforeach

            {{-- Typing indicator --}}
            <div id="typingIndicator" class="typing-indicator hidden">
                <img src="{{ $partner?->avatar_url }}" class="typing-avatar" alt="">
                <div class="typing-bubble">
                    <div class="typing-dots">
                        <span class="typing-dot"></span>
                        <span class="typing-dot"></span>
                        <span class="typing-dot"></span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Input Area --}}
        <div class="chat-input-area">
            <div class="input-container">
    {{-- Tombol Lampiran --}}
    <button type="button" onclick="document.getElementById('imgInput').click()" class="text-gray-400 hover:text-white transition-colors">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/></svg>
    </button>
    <input type="file" id="imgInput" class="hidden" accept="image/*" onchange="previewImage(this)">

    <input type="text" id="msgInput" class="chat-input" placeholder="Ketik pesan..." autocomplete="off">
    
    <button id="cancelEditBtn" type="button" class="text-gray-400 hover:text-white transition-colors hidden" onclick="cancelEdit()" title="Batal edit">✕</button>
    <button id="sendBtn" type="button" class="send-button">
        {{-- icon send di-inject dari JS --}}
    </button>
</div>
{{-- Area Preview Sebelum Kirim --}}
<div id="imagePreviewContainer" class="hidden mt-2 p-2 bg-slate-800 rounded-lg items-center gap-3">
    <img id="imagePreview" src="" class="h-12 w-12 object-cover rounded">
    <span class="text-xs text-gray-300 flex-1 truncate" id="fileName"></span>
    <button onclick="cancelImage()" class="text-red-400 text-xs">Batal</button>
</div>
            <div id="chatCropperModal" class="fixed inset-0 z-50 hidden items-center justify-center p-4 bg-black/90 backdrop-blur-md">
                <div class="bg-[#111] border border-white/10 rounded-2xl max-w-xl w-full flex flex-col max-h-[85vh] shadow-2xl">
                    <div class="flex items-center justify-between border-b border-white/5 px-4 py-3">
                        <h3 class="text-xs font-bold text-white uppercase tracking-wider flex items-center gap-2">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                            Pangkas & Sesuaikan Gambar
                        </h3>
                        <button id="closeChatCropperBtn" type="button" class="text-slate-400 hover:text-white transition-colors">✕</button>
                    </div>
                    <div class="p-4 flex-1 overflow-hidden flex items-center justify-center min-h-[250px] max-h-[45vh] bg-black/60">
                        <img id="chatCropperImage" class="max-w-full max-h-full block">
                    </div>
                    <div class="border-t border-white/5 px-4 py-3 flex flex-col gap-3.5">
                        <div class="flex flex-wrap items-center justify-center gap-2.5">
                            <button type="button" id="chatRotateLeftBtn" class="rounded-lg surface-weak hover:surface-weak text-white text-[10px] font-semibold px-2.5 py-1.5 transition-colors">
                                🔄 Putar Kiri
                            </button>
                            <button type="button" id="chatRotateRightBtn" class="rounded-lg surface-weak hover:surface-weak text-white text-[10px] font-semibold px-2.5 py-1.5 transition-colors">
                                🔄 Putar Kanan
                            </button>
                            <span class="w-px h-4 surface-weak mx-1"></span>
                            <button type="button" id="chatRatioFreeBtn" class="rounded-lg surface-weak hover:surface-weak text-white text-[10px] px-2 py-1 transition-colors">Bebas</button>
                            <button type="button" id="chatRatio1Btn" class="rounded-lg surface-weak hover:surface-weak text-white text-[10px] px-2 py-1 transition-colors">1:1</button>
                            <button type="button" id="chatRatio169Btn" class="rounded-lg surface-weak hover:surface-weak text-white text-[10px] px-2 py-1 transition-colors">16:9</button>
                        </div>
                        <div class="flex items-center justify-end gap-2 border-t border-white/5 pt-2.5">
                            <button id="cancelChatCropperBtn" type="button" class="rounded-xl surface-weak hover:surface-weak text-slate-300 px-4 py-2 text-xs font-bold transition-colors">
                                BATAL
                            </button>
                            <button id="saveChatCropperBtn" type="button" class="rounded-xl bg-gradient-to-r from-emerald-500 to-teal-500 hover:from-emerald-400 hover:to-teal-400 text-slate-950 px-4 py-2 text-xs font-bold transition-all">
                                SELESAI & GUNAKAN
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <p class="text-xs text-gray-500 mt-2 text-center">Pesan terenkripsi end-to-end</p>
        </div>

    </div>
</div>
</div>

<script id="chat-config" type="application/json">
{!! json_encode($chatConfig) !!}
</script>

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.1/cropper.min.js"></script>
<script>
const chatConfig = JSON.parse(document.getElementById('chat-config').textContent);

const CONV_ID  = chatConfig.convId;
const AUTH_ID  = chatConfig.authId;
const SEND_URL = chatConfig.sendUrl;
const POLL_URL = chatConfig.pollUrl;

const CSRF = document.querySelector('meta[name="csrf-token"]').content;

let lastId = Number(chatConfig.lastId || 0);
let pollController = null;
let editingId = null;
let isPolling = false;

const messagesArea  = document.getElementById('messagesArea');
const msgInput      = document.getElementById('msgInput');
const sendBtn       = document.getElementById('sendBtn');
const imgInput      = document.getElementById('imgInput');
const cancelEditBtn = document.getElementById('cancelEditBtn');


// ======================================================
// SEND ICON
// ======================================================

const SEND_ICON = `
<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
        d="M22 2L11 13">
    </path>
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
        d="M22 2L15 22L11 13L2 9L22 2Z">
    </path>
</svg>
`;

sendBtn.innerHTML = SEND_ICON;


// ======================================================
// SCROLL
// ======================================================

function scrollBottom(smooth = true) {
    requestAnimationFrame(() => {
        messagesArea.scrollTo({
            top: messagesArea.scrollHeight,
            behavior: smooth ? 'smooth' : 'auto'
        });
    });
}

scrollBottom(false);


// ======================================================
// FORMAT TIME REALTIME
// ======================================================

function formatTimeRealtime(dateString) {

    if(!dateString) return '';

    const date = new Date(dateString);

    return date.toLocaleTimeString('id-ID', {
        hour: '2-digit',
        minute: '2-digit',
        hour12: false,
        timeZone: 'Asia/Jakarta'
    });

}


// ======================================================
// UPDATE ALL TIMES EVERY 15 SEC
// ======================================================

function refreshAllMessageTimes() {

    document.querySelectorAll('[data-time]').forEach(el => {

        const raw = el.dataset.time;

        if(raw) {
            el.innerText = formatTimeRealtime(raw);
        }

    });

}

setInterval(refreshAllMessageTimes, 15000);


// ======================================================
// ESCAPE HTML
// ======================================================

function escHtml(str = '') {

    return str
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;');

}


// ======================================================
// SEND BUTTON STATE
// ======================================================

function updateSendButtonState() {

    const hasText = msgInput.value.trim().length > 0;
    const hasFile = imgInput.files.length > 0;

    sendBtn.disabled = !(hasText || hasFile);

    if(sendBtn.disabled) {
        sendBtn.style.opacity = '.5';
        sendBtn.style.cursor = 'not-allowed';
    } else {
        sendBtn.style.opacity = '1';
        sendBtn.style.cursor = 'pointer';
    }

}

msgInput.addEventListener('input', updateSendButtonState);
imgInput.addEventListener('change', updateSendButtonState);

updateSendButtonState();


// ======================================================
// SIDEBAR SEARCH
// ======================================================

document.getElementById('sideSearch')?.addEventListener('input', function () {

    const q = this.value.toLowerCase();

    document.querySelectorAll('#sideList .conv-item').forEach(el => {

        el.style.display = (el.dataset.name || '').includes(q)
            ? ''
            : 'none';

    });

});


// ======================================================
// PREVIEW IMAGE & INTERACTIVE CROPPING
// ======================================================

let chatCropper = null;
let originalChatFile = null;

function previewImage(input) {
    if (input.files && input.files[0]) {
        const file = input.files[0];
        originalChatFile = file;

        const reader = new FileReader();
        reader.onload = function(e) {
            const modal = document.getElementById('chatCropperModal');
            const cropperImg = document.getElementById('chatCropperImage');

            cropperImg.src = e.target.result;
            modal.classList.remove('hidden');
            modal.classList.add('flex');

            if (chatCropper) {
                chatCropper.destroy();
            }

            chatCropper = new Cropper(cropperImg, {
                viewMode: 1,
                dragMode: 'move',
                autoCropArea: 0.9,
                restore: false,
                modal: true,
                guides: true,
                highlight: false,
                cropBoxMovable: true,
                cropBoxResizable: true,
                toggleDragModeOnDblclick: false
            });
        };
        reader.readAsDataURL(file);
    }
}

// Ratio & Control events for Chat Cropper
document.getElementById('chatRotateLeftBtn')?.addEventListener('click', () => chatCropper?.rotate(-90));
document.getElementById('chatRotateRightBtn')?.addEventListener('click', () => chatCropper?.rotate(90));
document.getElementById('chatRatioFreeBtn')?.addEventListener('click', () => chatCropper?.setAspectRatio(NaN));
document.getElementById('chatRatio1Btn')?.addEventListener('click', () => chatCropper?.setAspectRatio(1));
document.getElementById('chatRatio169Btn')?.addEventListener('click', () => chatCropper?.setAspectRatio(16/9));

function closeChatCropper() {
    const modal = document.getElementById('chatCropperModal');
    if (modal) {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }
    if (chatCropper) {
        chatCropper.destroy();
        chatCropper = null;
    }
}

document.getElementById('closeChatCropperBtn')?.addEventListener('click', () => {
    closeChatCropper();
    cancelImage();
});
document.getElementById('cancelChatCropperBtn')?.addEventListener('click', () => {
    closeChatCropper();
    cancelImage();
});

document.getElementById('saveChatCropperBtn')?.addEventListener('click', () => {
    if (!chatCropper || !originalChatFile) return;

    const canvas = chatCropper.getCroppedCanvas();
    const mimeType = originalChatFile.type;

    canvas.toBlob((blob) => {
        if (!blob) {
            closeChatCropper();
            return;
        }

        const croppedFile = new File([blob], originalChatFile.name, {
            type: mimeType,
            lastModified: Date.now()
        });

        // HTML5 canvas compression (maxWidth 1200, quality 0.8)
        const maxWidth = 1200;
        const maxHeight = 1200;
        const quality = 0.8;

        const imgReader = new FileReader();
        imgReader.readAsDataURL(croppedFile);
        imgReader.onload = (event) => {
            const img = new Image();
            img.src = event.target.result;
            img.onload = () => {
                let width = img.width;
                let height = img.height;

                if (width > maxWidth || height > maxHeight) {
                    if (width > height) {
                        height = Math.round((height * maxWidth) / width);
                        width = maxWidth;
                    } else {
                        width = Math.round((width * maxHeight) / height);
                        height = maxHeight;
                    }
                }

                const compressCanvas = document.createElement('canvas');
                compressCanvas.width = width;
                compressCanvas.height = height;
                const ctx = compressCanvas.getContext('2d');

                if (mimeType === 'image/jpeg') {
                    ctx.fillStyle = '#FFFFFF';
                    ctx.fillRect(0, 0, width, height);
                }

                ctx.drawImage(img, 0, 0, width, height);

                compressCanvas.toBlob((compressedBlob) => {
                    const finalFile = new File([compressedBlob], originalChatFile.name, {
                        type: mimeType,
                        lastModified: Date.now()
                    });

                    // Sync with file input using DataTransfer
                    const dt = new DataTransfer();
                    dt.items.add(finalFile);
                    imgInput.files = dt.files;

                    // Show preview container in Chat view
                    const container = document.getElementById('imagePreviewContainer');
                    const preview   = document.getElementById('imagePreview');
                    const fileName  = document.getElementById('fileName');

                    preview.src = URL.createObjectURL(finalFile);
                    const savedPercent = Math.round((1 - finalFile.size / originalChatFile.size) * 100);
                    fileName.textContent = `${finalFile.name} (${savedPercent > 0 ? `Hemat ${savedPercent}%` : 'Optimized'}, ${(finalFile.size / 1024).toFixed(0)} KB)`;
                    container.classList.remove('hidden');
                    container.classList.add('flex');

                    closeChatCropper();
                    updateSendButtonState();
                }, mimeType, quality);
            };
        };
    }, mimeType, 0.9);
});

function cancelImage() {
    imgInput.value = '';
    const container = document.getElementById('imagePreviewContainer');
    if (container) {
        container.classList.add('hidden');
        container.classList.remove('flex');
    }
    updateSendButtonState();
}


// ======================================================
// EDIT MESSAGE
// ======================================================

function prepareEdit(msgId) {

    editingId = msgId;

    const currentText = document.getElementById(`text-${msgId}`).innerText;

    msgInput.value = currentText;

    msgInput.focus();

    msgInput.placeholder = 'Edit pesan...';

    msgInput.classList.add('bg-blue-900/30');

    sendBtn.innerHTML = '💾';

    cancelEditBtn.classList.remove('hidden');

    updateSendButtonState();

}


function cancelEdit() {

    editingId = null;

    msgInput.value = '';

    msgInput.placeholder = 'Ketik pesan...';

    msgInput.classList.remove('bg-blue-900/30');

    sendBtn.innerHTML = SEND_ICON;

    cancelEditBtn.classList.add('hidden');

    updateSendButtonState();

}


async function updateMessage(id, newText) {

    try {

        const res = await fetch(`/chat/message/${id}`, {
            method: 'PATCH',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': CSRF,
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                message: newText
            })
        });

        if(!res.ok) throw new Error();

        document.getElementById(`text-${id}`).innerText = newText;

        cancelEdit();

    } catch(e) {

        alert('Gagal update pesan');

    }

}


// ======================================================
// DELETE MESSAGE
// ======================================================

async function confirmDelete(msgId) {

    if(!confirm('Hapus pesan ini?')) return;

    const el = document.querySelector(`[data-msg-id="${msgId}"]`);

    try {

        const res = await fetch(`/chat/message/${msgId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': CSRF,
                'Accept': 'application/json'
            }
        });

        if(!res.ok) throw new Error();

        el.remove();

    } catch(e) {

        alert('Gagal menghapus pesan');

    }

}


// ======================================================
// APPEND MESSAGE
// ======================================================

function appendMessage(m) {

    if(document.querySelector(`[data-msg-id="${m.id}"]`)) {
        return;
    }

    const isMine = m.is_mine || Number(m.sender_id) === AUTH_ID;

    const div = document.createElement('div');

    div.className = `message-item ${isMine ? 'mine' : 'theirs'}`;
    div.dataset.msgId = m.id;

    const avatarSrc =
        m.avatar ||
        `https://ui-avatars.com/api/?name=?&background=2563eb&color=fff`;

    const avatarHtml = `
        <img src="${avatarSrc}"
             class="message-avatar ${isMine ? 'mine' : ''}"
             alt="">
    `;

    const attachmentUrl =
    m.image_url ||
    m.attachment_url ||
    (m.attachment_path
        ? `/storage/${m.attachment_path}`
        : null);

    const imgHtml = attachmentUrl
        ? `
            <img src="${attachmentUrl}"
                 class="max-w-xs rounded-2xl mb-2 cursor-pointer hover:opacity-90 transition"
                 onclick="window.open(this.src)">
        `
        : '';

    const readIcon = isMine
        ? `
        <span class="message-status">
            <svg class="${m.is_read ? 'text-blue-400' : 'text-gray-500'}"
                fill="currentColor"
                viewBox="0 0 16 16">
                <path d="M12.354 4.354a.5.5 0 00-.708-.708L5 11.293 1.854 8.146a.5.5 0 10-.708.708l3.5 3.5a.5.5 0 00.708 0l7-7z"/>
            </svg>
        </span>
        `
        : '';

    div.innerHTML = `

        ${!isMine ? avatarHtml : ''}

        <div class="message-bubble ${isMine ? 'mine' : 'theirs'}">

            <div class="bubble-content group relative">

                ${isMine ? `
                <div class="absolute -left-10 top-0 hidden group-hover:flex gap-1">

                    <button onclick="prepareEdit('${m.id}')"
                        class="p-1 text-gray-500 hover:text-blue-400 transition">

                        <svg class="w-4 h-4"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24">

                            <path stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M15.232 5.232l3.536 3.536m-2.036-5.036
                                a2.5 2.5 0 113.536 3.536L6.5
                                21.036H3v-3.572L16.732 3.732z"/>
                        </svg>

                    </button>

                    <button onclick="confirmDelete('${m.id}')"
                        class="p-1 text-gray-500 hover:text-red-400 transition">

                        <svg class="w-4 h-4"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24">

                            <path stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M19 7l-.867 12.142A2 2 0
                                0116.138 21H7.862a2 2 0
                                01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4
                                a1 1 0 00-1-1h-4a1 1 0 00-1
                                1v3M4 7h16"/>
                        </svg>

                    </button>

                </div>
                ` : ''}

                ${imgHtml}

                <p class="message-text"
                   id="text-${m.id}">
                   ${escHtml(m.message || '')}
                </p>

            </div>

            <div class="message-time ${isMine ? 'mine' : 'theirs'}">

                <span data-time="${m.created_at}">
                    ${m.time}
                </span>

                ${readIcon}

            </div>

        </div>

        ${isMine ? avatarHtml : ''}

    `;

    const typing = document.getElementById('typingIndicator');

    messagesArea.insertBefore(div, typing);

    requestAnimationFrame(() => {

        div.style.opacity = '1';
        div.style.transform = 'translateY(0)';

    });

    scrollBottom();

}


// ======================================================
// SEND MESSAGE
// ======================================================

async function sendMessage() {

    if(editingId) {

        await updateMessage(
            editingId,
            msgInput.value.trim()
        );

        return;

    }

    const text = msgInput.value.trim();
    const file = imgInput.files[0];

    if(!text && !file) return;

    sendBtn.disabled = true;

    const tempId = 'tmp-' + Date.now();

    appendMessage({
        id: tempId,
        sender_id: AUTH_ID,
        is_mine: true,
        message: text,
        created_at: new Date().toISOString(),
        avatar: chatConfig.avatarUrl,
        attachment_url: file
        ? URL.createObjectURL(file)
        : null,
        is_read: false
    });

    msgInput.value = '';

    updateSendButtonState();

    try {

        const form = new FormData();

        form.append('message', text);

        if(file) {
            form.append('attachment', file);
        }

        form.append('conversation_id', CONV_ID);

        const res = await fetch(SEND_URL, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': CSRF,
                'Accept': 'application/json'
            },
            body: form
        });

        const data = await res.json();

        if(!res.ok) {
            throw new Error(data.message || 'Gagal kirim');
        }

        lastId = Number(data.id);

        const tempEl = document.querySelector(
            `[data-msg-id="${tempId}"]`
        );

        if(tempEl) {

            tempEl.dataset.msgId = data.id;

        }

        cancelImage();

    } catch(e) {

        console.error(e);

        alert(e.message);

    } finally {

        sendBtn.disabled = false;

        msgInput.focus();

        updateSendButtonState();

    }

}


// ======================================================
// REALTIME POLLING
// ======================================================

async function pollMessages() {

    if(isPolling) return;

    isPolling = true;

    try {

        if(pollController) {
            pollController.abort();
        }

        pollController = new AbortController();

        const res = await fetch(
            `${POLL_URL}?since=${lastId}&_=${Date.now()}`,
            {
                method: 'GET',
                cache: 'no-store',
                signal: pollController.signal,
                headers: {
                    'Accept': 'application/json',
                    'Cache-Control': 'no-cache'
                }
            }
        );

        const data = await res.json();

        if(data.messages && data.messages.length > 0) {

            data.messages.forEach(m => {

                appendMessage(m);

            });

            lastId = Number(
                data.messages[data.messages.length - 1].id
            );

            refreshSidebar();

        }

    } catch(e) {

        if(e.name !== 'AbortError') {
            console.log(e);
        }

    } finally {

        isPolling = false;

        setTimeout(pollMessages, 1200);

    }

}


// ======================================================
// REFRESH SIDEBAR
// ======================================================

async function refreshSidebar() {

    try {

        const res = await fetch(window.location.href, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        });

        const html = await res.text();

        const parser = new DOMParser();

        const doc = parser.parseFromString(html, 'text/html');

        const newSidebar = doc.getElementById('sideList');

        if(newSidebar) {

            document.getElementById('sideList').innerHTML =
                newSidebar.innerHTML;

        }

    } catch(e) {}

}


// ======================================================
// EVENTS
// ======================================================

msgInput.addEventListener('keydown', function(e) {

    if(e.key === 'Enter' && !e.shiftKey) {

        e.preventDefault();

        sendMessage();

    }

});

sendBtn.addEventListener('click', sendMessage);


// ======================================================
// TAB ACTIVE / INACTIVE
// ======================================================

document.addEventListener('visibilitychange', () => {

    if(!document.hidden) {

        pollMessages();

    }

});


// ======================================================
// START
// ======================================================

pollMessages();

</script>
@endpush
@endsection

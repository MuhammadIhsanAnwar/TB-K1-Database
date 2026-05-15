@extends('layouts.app')

@section('title', 'Chat — ' . ($conversation->seller?->name ?? $conversation->partner(auth()->id())?->name ?? 'Percakapan'))

@push('styles')
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
    background: linear-gradient(135deg, #2563eb, #1d4ed8);
    color: #ffffff;
    border-radius: 18px 18px 4px 18px;
    padding: 8px 12px;
    box-shadow: 0 2px 8px rgba(37, 99, 235, 0.3);
    transition: background 0.18s ease, transform 0.18s ease;
}

.message-bubble.theirs .bubble-content {
    background: #1a1a1a;
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
    }

    .send-button:hover {
        transform: translateY(-1px);
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
    $partner = $conversation->seller ?? $conversation->partner($user->id);
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
            <a href="{{ route('chat.inbox') }}" class="flex items-center gap-2 text-slate-400 hover:text-white mb-3 transition-colors">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Kembali ke Inbox
            </a>
            <h3>Pesan</h3>
            <input type="text" id="sideSearch" placeholder="Cari percakapan..."
                   class="search-input">
        </div>

        <div class="overflow-y-auto flex-1" id="sideList">
            @foreach($sidebarConversations as $conv)
            @php
                $p2     = $conv->seller ?? $conv->partner($user->id);
                $unread = $conv->unreadFor($user->id);
                $active = $conv->id === $conversation->id;
            @endphp
            <a href="{{ route('chat.show', $conv) }}"
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
            <img src="{{ $product->image_url }}" alt="{{ $product->name }}">
            <div class="product-info">
                <h3>{{ $product->name }}</h3>
                <p class="product-price">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
            </div>
            <a href="{{ route('checkout.product', $product) }}"
               class="ml-auto px-4 py-2 rounded-lg bg-green-600 text-white text-sm font-medium hover:bg-green-700 transition-colors">
                Pesan Sekarang
            </a>
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
    <div class="bubble-content group relative"> {{-- Tambah class group --}}
        
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
            <img src="{{ $msg->image_url }}" class="max-w-xs rounded-lg mb-2 cursor-pointer" onclick="window.open(this.src)">
        @endif
        
        <p class="message-text" id="text-{{ $msg->id }}">{{ $msg->message }}</p>
    </div>
    
    {{-- Meta data (waktu/status) tetap sama --}}
    <div class="message-time {{ $isMine ? 'mine' : 'theirs' }}">
        <span>{{ $msg->created_at->format('H:i') }}</span>
        {{-- ... status icon ... --}}
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
        {{-- icon send --}}
    </button>
</div>
{{-- Area Preview Sebelum Kirim --}}
<div id="imagePreviewContainer" class="hidden mt-2 p-2 bg-slate-800 rounded-lg flex items-center gap-3">
    <img id="imagePreview" src="" class="h-12 w-12 object-cover rounded">
    <span class="text-xs text-gray-300 flex-1 truncate" id="fileName"></span>
    <button onclick="cancelImage()" class="text-red-400 text-xs">Batal</button>
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
<script>
const chatConfig = JSON.parse(document.getElementById('chat-config').textContent);
const CONV_ID = chatConfig.convId;
const AUTH_ID = chatConfig.authId;
const SEND_URL = chatConfig.sendUrl;
const POLL_URL = chatConfig.pollUrl;
const CSRF    = document.querySelector('meta[name="csrf-token"]').content;

let lastId = chatConfig.lastId;
let pollTimer;

const messagesArea = document.getElementById('messagesArea');
const msgInput = document.getElementById('msgInput');
const sendBtn = document.getElementById('sendBtn');
const imgInput = document.getElementById('imgInput');

// Set default send icon (was empty which caused button to look missing)
sendBtn.innerHTML = `
<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
          d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8">
    </path>
</svg>
`;

// Manage send button enabled state
function updateSendButtonState() {
    const hasText = msgInput.value.trim().length > 0;
    const hasFile = imgInput.files && imgInput.files.length > 0;
    sendBtn.disabled = !(hasText || hasFile);
}
msgInput.addEventListener('input', updateSendButtonState);
imgInput.addEventListener('change', updateSendButtonState);
updateSendButtonState();



// --- LOGIC DELETE ---
async function confirmDelete(msgId) {
    if(!confirm('Hapus pesan ini?')) return;
    
    const el = document.querySelector(`[data-msg-id="${msgId}"]`);
    el.style.opacity = '0.5'; // Visual feedback

    try {
        const res = await fetch(`/chat/message/${msgId}`, {
            method: 'DELETE',
            headers: { 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' }
        });
        if(res.ok) el.remove(); // Hapus dari layar
    } catch(e) {
        el.style.opacity = '1';
        alert('Gagal menghapus pesan');
    }
}

// --- LOGIC EDIT ---
let editingId = null;
const cancelEditBtn = document.getElementById('cancelEditBtn');

function prepareEdit(msgId) {
    editingId = msgId;
    const currentText = document.getElementById(`text-${msgId}`).innerText;
    msgInput.value = currentText;
    msgInput.focus();
    msgInput.classList.add('bg-blue-900/30'); // Beri tanda sedang mengedit
    msgInput.placeholder = 'Edit pesan...';
    sendBtn.innerHTML = '💾'; // Ganti icon jadi save
    sendBtn.disabled = false;
    cancelEditBtn.classList.remove('hidden');
    updateSendButtonState();
}

// Modifikasi fungsi sendMessage Anda sedikit:


async function updateMessage(id, newText) {
    try {
        const res = await fetch(`/chat/message/${id}`, {
            method: 'PATCH',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF },
            body: JSON.stringify({ message: newText })
        });
        if(res.ok) {
            document.getElementById(`text-${id}`).innerText = newText;
            cancelEdit();
        }
    } catch(e) { alert('Gagal update'); }
}

function cancelEdit() {
    editingId = null;
    msgInput.value = '';
    msgInput.placeholder = 'Ketik pesan...';
    msgInput.classList.remove('bg-blue-900/30');
    cancelEditBtn.classList.add('hidden');
    sendBtn.innerHTML = `
<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
          d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8">
    </path>
</svg>
`;
    updateSendButtonState();
}

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


async function sendMessage() {

    if (editingId) {
        await updateMessage(editingId, msgInput.value.trim());
        return;
    }

    const text = msgInput.value.trim();
    const file = imgInput.files && imgInput.files[0] ? imgInput.files[0] : null;

    if (!text && !file) return;

    sendBtn.disabled = true;

    const tempId = 'tmp-' + Date.now();

    // Show optimistic UI (if image selected, use temporary object URL)
    appendMessage({
        id: tempId,
        is_mine: true,
        message: text || '',
        time: new Date().toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' }),
        avatar: '{{ $user->avatar_url }}',
        is_read: false,
        attachment_url: file ? URL.createObjectURL(file) : null,
    });

    // clear inputs in UI (but keep preview until sent)
    msgInput.value = '';
    updateSendButtonState();

    try {
        const form = new FormData();
        form.append('message', text);
        if (file) form.append('attachment', file);
        form.append('conversation_id', CONV_ID);

        const res = await fetch(SEND_URL, {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' },
            body: form,
        });

        let data = null;
        try {
            data = await res.json();
        } catch (parseErr) {
            // response is not JSON (likely 500 HTML), capture text
            const text = await res.text();
            if (!res.ok) throw new Error(text.substring(0, 200));
        }

        if (!res.ok) {
            // Try to extract useful error info from Laravel response
            let errMsg = data?.message || null;
            if (!errMsg && data.errors) {
                const firstField = Object.keys(data.errors)[0];
                errMsg = Array.isArray(data.errors[firstField]) ? data.errors[firstField][0] : JSON.stringify(data.errors[firstField]);
            }
            if (!errMsg) errMsg = JSON.stringify(data);
            throw new Error(errMsg || 'Gagal mengirim pesan.');
        }

        if (data && data.id) {
            lastId = data.id;
            // Replace temp message id with real id
            const tmpEl = document.querySelector(`[data-msg-id="${tempId}"]`);
            if (tmpEl) {
                tmpEl.dataset.msgId = data.id;
                // If server returned an attachment_path, replace the optimistic image src
                if (data.attachment_path) {
                    const img = tmpEl.querySelector('img');
                    if (img) img.src = `/storage/${data.attachment_path}`;
                }
            }
        }

        // clear preview if any
        cancelImage();

    } catch (e) {
        console.error('Send message error:', e);
        alert('Gagal mengirim pesan: ' + (e.message || e));
    } finally {
        sendBtn.disabled = false;
        msgInput.focus();
    }
}

function previewImage(input) {
    const container = document.getElementById('imagePreviewContainer');
    const preview = document.getElementById('imagePreview');
    const fileName = document.getElementById('fileName');

    if (input.files && input.files[0]) {
        const reader = new FileReader();

        reader.onload = function(e) {
            preview.src = e.target.result;
            fileName.textContent = input.files[0].name;
            container.classList.remove('hidden');
        }

        reader.readAsDataURL(input.files[0]);
    }
}

function cancelImage() {
    const fileInput = document.getElementById('imgInput');
    const container = document.getElementById('imagePreviewContainer');
    fileInput.value = '';
    container.classList.add('hidden');
}

  

// end of functions

msgInput.addEventListener('keydown', function(e) {
    if (e.key === 'Enter' && !e.shiftKey) {
        e.preventDefault();
        sendMessage();
    }
});

sendBtn.addEventListener('click', function() {
    sendMessage();
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
    const isMine = m.is_mine || Number(m.sender_id) === AUTH_ID;
    const div = document.createElement('div');
    div.className = `message-item ${isMine ? 'mine' : 'theirs'}`;
    div.dataset.msgId = m.id;
    div.style.opacity = '0';
    div.style.transform = 'translateY(10px)';

    const avatarSrc = m.avatar || `https://ui-avatars.com/api/?name=?&background=6366f1&color=fff`;
    const avatarHtml = `<img src="${avatarSrc}" class="message-avatar ${isMine ? 'mine' : ''}" alt="">`;
    const readIcon = isMine ? `<span class="message-status"><svg class="${m.is_read ? 'text-blue-400' : 'text-gray-500'}" fill="currentColor" viewBox="0 0 16 16"><path d="M12.354 4.354a.5.5 0 00-.708-.708L5 11.293 1.854 8.146a.5.5 0 10-.708.708l3.5 3.5a.5.5 0 00.708 0l7-7zm-4.208 7.209l-.896-.897.707-.707.543.543 6.646-6.647a.5.5 0 01.708.708l-7 7a.5.5 0 01-.708 0z"/></svg></span>` : '';

    const typing = document.getElementById('typingIndicator');
    messagesArea.insertBefore(div, typing);
    scrollBottom();
    // keep cache in sync
    // Logika gambar (Sesuaikan dengan properti dari Controller)
    // Determine attachment URL: optimistic frontend may provide attachment_url; server returns attachment_path
    const attachmentUrl = m.attachment_url || (m.attachment_path ? `/storage/${m.attachment_path}` : null);
    const imgHtml = attachmentUrl ? `<img src="${attachmentUrl}" class="max-w-xs rounded-lg mb-2 cursor-pointer" onclick="window.open(this.src)">` : '';

    div.innerHTML = `
        ${!isMine ? avatarHtml : ''}
        <div class="message-bubble ${isMine ? 'mine' : 'theirs'}">
            <div class="bubble-content group relative">
                ${isMine ? `
                <div class="absolute -left-10 top-0 hidden group-hover:flex gap-1">
                    <button onclick="prepareEdit('${m.id}')" class="p-1 text-gray-500 hover:text-blue-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                    </button>
                    <button onclick="confirmDelete('${m.id}')" class="p-1 text-gray-500 hover:text-red-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                    </button>
                </div>` : ''}
                
                ${imgHtml}
                <p class="message-text" id="text-${m.id}">${escHtml(m.message)}</p>
            </div>
            <div class="message-time ${isMine ? 'mine' : 'theirs'}">
                <span>${m.time}</span>
                ${readIcon}
            </div>
        </div>
        ${isMine ? avatarHtml : ''}
    `;
    requestAnimationFrame(() => {
        div.style.opacity = '1';
        div.style.transform = 'translateY(0)';
    });
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

// Persist cache on unload
window.addEventListener('beforeunload', () => {
    try {
        if (messagesArea) localStorage.setItem(cacheKey, messagesArea.innerHTML);
    } catch (e) {}
});
</script>
@endpush
@endsection

@extends('layouts.app')

@section('title', 'Pesan — Lapak Gaming')

@push('styles')
<style>
/* WhatsApp Style Inbox */
.inbox-container {
    display: flex;
    height: calc(100vh - 80px);
    background: #0a0a0a;
    border-radius: 12px;
    overflow: hidden;
    border: 1px solid #1a1a1a;
    box-shadow: 0 8px 32px rgba(0,0,0,0.5);
}

.inbox-sidebar {
    width: 350px;
    background: #111111;
    border-right: 1px solid #1a1a1a;
    display: flex;
    flex-direction: column;
}

.inbox-header {
    padding: 20px 16px;
    background: #111111;
    border-bottom: 1px solid #1a1a1a;
}

.inbox-title {
    font-size: 24px;
    font-weight: 600;
    color: #ffffff;
    margin-bottom: 4px;
}

.inbox-subtitle {
    font-size: 14px;
    color: #888888;
}

.search-container {
    margin-top: 16px;
}

.search-input {
    width: 100%;
    background: #1a1a1a;
    border: 1px solid #2a2a2a;
    border-radius: 8px;
    padding: 10px 12px;
    color: #ffffff;
    font-size: 14px;
    outline: none;
}

.search-input:focus {
    border-color: #2563eb;
    box-shadow: 0 0 0 2px rgba(37, 99, 235, 0.2);
}

.search-input::placeholder {
    color: #666666;
}

.conversations-list {
    flex: 1;
    overflow-y: auto;
}

.conv-item {
    display: flex;
    align-items: center;
    padding: 16px;
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
    width: 50px;
    height: 50px;
    border-radius: 50%;
    object-fit: cover;
    margin-right: 14px;
    border: 2px solid #2a2a2a;
}

.conv-content {
    flex: 1;
    min-width: 0;
}

.conv-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 6px;
}

.conv-name {
    font-size: 16px;
    font-weight: 600;
    color: #ffffff;
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

.conv-context {
    font-size: 13px;
    color: #2563eb;
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

.conv-preview.unread {
    color: #ffffff;
    font-weight: 500;
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

.empty-state {
    flex: 1;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    background: #0a0a0a;
    color: #888888;
}

.empty-icon {
    font-size: 64px;
    margin-bottom: 16px;
}

.empty-title {
    font-size: 18px;
    font-weight: 600;
    color: #ffffff;
    margin-bottom: 8px;
}

.empty-text {
    font-size: 14px;
    text-align: center;
    max-width: 300px;
}

/* Responsive */
@media (max-width: 768px) {
    .inbox-container {
        height: 100vh;
        border-radius: 0;
    }

    .inbox-sidebar {
        width: 100%;
    }

    .empty-state {
        display: none;
    }
}
</style>
@endpush

@section('content')
<div class="max-w-7xl mx-auto px-4 py-4">
    <div class="inbox-container">

        {{-- Sidebar: Conversation List --}}
        <div class="inbox-sidebar">
            <div class="inbox-header">
                <h1 class="inbox-title">Pesan</h1>
                <p class="inbox-subtitle">Semua percakapan dengan pembeli dan penjual</p>
                <div class="search-container">
                    <input id="searchInput" type="text" placeholder="Cari percakapan..."
                           class="search-input">
                </div>
            </div>

            <div class="conversations-list" id="convList">
                @forelse($conversations as $conv)
                @php
                    $user   = auth()->user();
                    $sellerDisplay = $conv->seller ?? $conv->partner($user->id);
                    $unread  = $conv->unreadFor($user->id);
                @endphp
                <a href="{{ route('chat.show', $conv) }}"
                   class="conv-item {{ request()->routeIs('chat.show') && request()->route('conversation') == $conv->id ? 'active' : '' }}"
                   data-name="{{ strtolower($sellerDisplay?->name ?? '') }}">
                    <img src="{{ $sellerDisplay?->avatar_url ?? 'https://ui-avatars.com/api/?name=?' }}"
                         class="conv-avatar" alt="{{ $sellerDisplay?->name }}">
                    <div class="conv-content">
                        <div class="conv-header">
                            <span class="conv-name">{{ $sellerDisplay?->name ?? 'Penjual' }}</span>
                            <span class="conv-time">
                                {{ $conv->last_message_at?->diffForHumans(null, true) ?? '' }}
                            </span>
                        </div>
                        @if($conv->product)
                        <p class="conv-context">🎮 {{ $conv->product->name }}</p>
                        @elseif($conv->order)
                        <p class="conv-context">📦 Order #{{ $conv->order->order_code ?? $conv->order_id }}</p>
                        @endif
                        <div class="flex justify-between items-center">
                            <span class="conv-preview {{ $unread > 0 ? 'unread' : '' }}">
                                @if($conv->last_message)
                                    @if($conv->last_message_sender_id === $user->id)Kamu: @endif
                                    {{ mb_substr($conv->last_message, 0, 50) }}
                                @else
                                    Belum ada pesan
                                @endif
                            </span>
                            @if($unread > 0)
                            <span class="unread-badge">{{ $unread > 9 ? '9+' : $unread }}</span>
                            @endif
                        </div>
                    </div>
                </a>
                @empty
                <div class="flex flex-col items-center justify-center py-16 text-center px-6">
                    <div class="text-4xl mb-3">💬</div>
                    <p class="text-slate-400 text-sm font-medium">Belum ada percakapan</p>
                    <p class="text-slate-500 text-xs mt-1">Chat dari halaman produk untuk memulai</p>
                </div>
                @endforelse
            </div>

            @if($conversations->hasPages())
            <div class="p-3 border-t border-slate-800 text-xs text-center text-slate-500">
                {{ $conversations->links() }}
            </div>
            @endif
        </div>

        {{-- Main pane: Empty state --}}
        <div class="empty-state">
            <div class="empty-icon">💬</div>
            <h2 class="empty-title">Pilih percakapan</h2>
            <p class="empty-text">Klik salah satu percakapan di kiri untuk membukanya</p>
        </div>

    </div>
</div>
</div>

@push('scripts')
<script>
document.getElementById('searchInput').addEventListener('input', function() {
    const q = this.value.toLowerCase();
    document.querySelectorAll('#convList .conv-item').forEach(el => {
        el.style.display = el.dataset.name.includes(q) ? '' : 'none';
    });
});
</script>
@endpush
@endsection
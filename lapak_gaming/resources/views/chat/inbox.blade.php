@extends('layouts.app')

@section('title', 'Pesan — Lapak Gaming')

@push('styles')
<style>
.inbox-page { display: flex; min-height: calc(100vh - 80px); }
.sidebar { width: 320px; shrink: 0; border-right: 1px solid rgba(30,45,69,.8); }
.conv-item { padding: 14px 16px; cursor: pointer; transition: background .15s; border-bottom: 1px solid rgba(30,45,69,.5); }
.conv-item:hover { background: rgba(30,45,69,.5); }
.conv-item.active { background: rgba(37,99,235,.1); border-left: 2px solid #3b82f6; }
.unread-badge { background: #3b82f6; color: #fff; border-radius: 999px; font-size: .65rem; font-weight: 800; min-width: 18px; height: 18px; display: inline-flex; align-items: center; justify-content: center; padding: 0 5px; }
.empty-pane { flex: 1; display: flex; flex-direction: column; align-items: center; justify-content: center; gap: 12px; opacity: .6; }
</style>
@endpush

@section('content')
<div class="max-w-7xl mx-auto px-4 py-6">
    <div class="flex gap-4 items-center mb-6">
        <div>
            <h1 class="text-2xl font-black text-white">Pesan</h1>
            <p class="text-slate-400 text-sm">Semua percakapan dengan pembeli dan penjual</p>
        </div>
    </div>

    <div class="rounded-2xl border border-slate-800 bg-slate-950 overflow-hidden flex" style="min-height: 600px">

        {{-- Sidebar: Conversation List --}}
        <div class="w-80 shrink-0 border-r border-slate-800 flex flex-col">
            <div class="p-4 border-b border-slate-800">
                <div class="relative">
                    <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    <input id="searchInput" type="text" placeholder="Cari percakapan..."
                        class="w-full pl-9 pr-3 py-2 rounded-xl bg-slate-900 border border-slate-700 text-sm text-slate-200 placeholder-slate-500 focus:outline-none focus:border-brand-500 transition-colors">
                </div>
            </div>

            <div class="overflow-y-auto flex-1" id="convList">
                @forelse($conversations as $conv)
                @php
                    $user   = auth()->user();
                    $partner = $conv->partner($user->id);
                    $unread  = $conv->unreadFor($user->id);
                @endphp
                <a href="{{ route('chat.show', $conv) }}"
                   class="conv-item flex gap-3 items-start hover:no-underline block"
                   data-name="{{ strtolower($partner?->name ?? '') }}">
                    <div class="relative shrink-0">
                        <img src="{{ $partner?->avatar_url ?? 'https://ui-avatars.com/api/?name=?' }}"
                             class="w-11 h-11 rounded-full object-cover" alt="{{ $partner?->name }}">
                        @if($unread > 0)
                        <span class="absolute -top-1 -right-1 unread-badge">{{ $unread > 9 ? '9+' : $unread }}</span>
                        @endif
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="flex justify-between items-center gap-2">
                            <span class="font-semibold text-white text-sm truncate">{{ $partner?->name ?? 'Pengguna' }}</span>
                            <span class="text-xs text-slate-500 shrink-0">
                                {{ $conv->last_message_at?->diffForHumans(null, true) ?? '' }}
                            </span>
                        </div>
                        @if($conv->product)
                        <p class="text-xs text-brand-400 truncate mt-0.5">🎮 {{ $conv->product->name }}</p>
                        @elseif($conv->order)
                        <p class="text-xs text-amber-400 truncate mt-0.5">📦 Order #{{ $conv->order->order_code ?? $conv->order_id }}</p>
                        @endif
                        <p class="text-xs text-slate-500 truncate mt-0.5 {{ $unread > 0 ? 'text-slate-300 font-medium' : '' }}">
                            @if($conv->last_message)
                                @if($conv->last_message_sender_id === $user->id)Kamu: @endif
                                {{ mb_substr($conv->last_message, 0, 50) }}
                            @else
                                Belum ada pesan
                            @endif
                        </p>
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
        <div class="flex-1 empty-pane">
            <div class="text-5xl">💬</div>
            <p class="text-slate-400 font-medium">Pilih percakapan</p>
            <p class="text-slate-500 text-sm">Klik salah satu percakapan di kiri untuk membukanya</p>
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
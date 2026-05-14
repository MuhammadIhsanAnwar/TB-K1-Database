@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-950 py-12 px-4">
    <div class="max-w-4xl mx-auto">
        <div class="flex justify-between items-center mb-8">
            <h1 class="text-3xl font-bold text-white">Notifications</h1>
            <form action="{{ route('notifications.read-all') }}" method="POST">
                @csrf
                <button type="submit" class="text-sm font-semibold text-brand-300 hover:text-brand-200">
                    Tandai semua dibaca
                </button>
            </form>
        </div>

        @if($notifications->count() > 0)
            <div class="space-y-3">
                @foreach($notifications as $notification)
                <div class="bg-gray-900 rounded-lg p-4 border border-gray-800 hover:border-gray-700 transition-colors {{ !$notification->is_read ? 'border-purple-600/30' : '' }}">
                    <div class="flex items-start justify-between">
                        <div class="flex-1">
                            <div class="flex items-center gap-3 mb-2">
                                <div class="w-2 h-2 rounded-full {{ !$notification->is_read ? 'bg-purple-500' : 'bg-gray-700' }}"></div>
                                <h3 class="font-semibold text-white">{{ $notification->title }}</h3>
                            </div>
                            <p class="text-gray-400 text-sm">{{ $notification->body }}</p>
                            <p class="text-gray-500 text-xs mt-2">{{ $notification->created_at->diffForHumans() }}</p>
                        </div>
                        <div class="ml-4 flex flex-col gap-2">
                            @if($notification->link)
                                <a href="{{ $notification->link }}" class="text-sm font-semibold text-brand-300 hover:text-brand-200">Buka</a>
                            @endif
                            @if(!$notification->is_read)
                                <form action="{{ route('notifications.read', $notification) }}" method="POST">
                                    @csrf
                                    <button class="text-gray-400 hover:text-gray-300 transition-colors text-sm font-semibold">
                                        Tandai dibaca
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                    @if($notification->link)
                        <a href="{{ $notification->link }}" class="mt-4 inline-flex items-center gap-2 text-sm font-semibold text-brand-300 hover:text-brand-200">
                            Buka notifikasi
                            <span aria-hidden="true">→</span>
                        </a>
                    @endif
                </div>
                @endforeach
            </div>

            {{-- Pagination --}}
            <div class="mt-8">
                {{ $notifications->links() }}
            </div>
        @else
        <div class="bg-gray-900 rounded-xl p-12 text-center border border-gray-800">
            <svg class="w-16 h-16 mx-auto text-gray-600 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
            </svg>
            <p class="text-gray-400 text-lg mb-2">No notifications yet</p>
            <p class="text-gray-500 text-sm">When you get new notifications, they'll appear here</p>
        </div>
        @endif
    </div>
</div>
@endsection

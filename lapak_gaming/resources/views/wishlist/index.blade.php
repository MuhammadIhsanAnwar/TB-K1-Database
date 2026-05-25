@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-surface py-12 px-4">
    <div class="max-w-6xl mx-auto">
        <h1 class="text-3xl font-bold surface-text mb-8">My Wishlist</h1>
        
        <div class="surface-panel rounded-xl p-8 text-center border border-white/10">
            <svg class="w-16 h-16 mx-auto surface-muted mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
            </svg>
            <p class="surface-muted text-lg">Your wishlist is empty</p>
            <p class="surface-muted text-sm mt-2">Add items to your wishlist to keep track of products you love</p>
            <a href="{{ route('marketplace.home') }}" class="btn-accent inline-block mt-6">
                Continue Shopping
            </a>
        </div>
    </div>
</div>
@endsection

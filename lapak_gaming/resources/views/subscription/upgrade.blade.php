@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-950 py-12 px-4">
    <div class="max-w-6xl mx-auto">
        <div class="text-center mb-12">
            <h1 class="text-4xl font-bold text-white mb-4">Upgrade to {{ config('app.name') }} Pro</h1>
            <p class="text-gray-400 text-lg">Unlock premium features and exclusive benefits</p>
        </div>

        {{-- Pricing Plans --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-12">
            {{-- Basic Plan --}}
            <div class="bg-gray-900 rounded-xl p-8 border border-gray-800 hover:border-gray-700 transition-all">
                <h2 class="text-xl font-bold text-white mb-2">Basic</h2>
                <p class="text-gray-400 text-sm mb-6">Perfect for getting started</p>
                <div class="mb-6">
                    <span class="text-4xl font-bold text-white">Free</span>
                </div>
                <ul class="space-y-3 mb-8 text-sm text-gray-300">
                    <li class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-green-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                        Browse products
                    </li>
                    <li class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-green-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                        Buy products
                    </li>
                    <li class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-gray-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/></svg>
                        <span class="line-through">Premium features</span>
                    </li>
                </ul>
                <button disabled class="w-full bg-gray-800 text-gray-400 py-2 px-4 rounded-lg font-medium cursor-not-allowed">
                    Current Plan
                </button>
            </div>

            {{-- Pro Plan (Highlighted) --}}
            <div class="bg-gradient-to-br from-purple-900 to-purple-800 rounded-xl p-8 border border-purple-600 relative transform md:scale-105 shadow-2xl">
                <div class="absolute -top-4 left-1/2 -translate-x-1/2 bg-purple-500 text-white px-4 py-1 rounded-full text-xs font-semibold">
                    MOST POPULAR
                </div>
                <h2 class="text-xl font-bold text-white mb-2">Pro</h2>
                <p class="text-purple-200 text-sm mb-6">For power users and sellers</p>
                <div class="mb-6">
                    <span class="text-4xl font-bold text-white">$9.99</span>
                    <span class="text-purple-200 text-sm ml-2">per month</span>
                </div>
                <ul class="space-y-3 mb-8 text-sm text-purple-100">
                    <li class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-green-300" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                        All Basic features
                    </li>
                    <li class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-green-300" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                        Priority support
                    </li>
                    <li class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-green-300" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                        Analytics dashboard
                    </li>
                    <li class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-green-300" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                        Sell on marketplace
                    </li>
                </ul>
                <form action="{{ route('subscription.store') }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full bg-purple-500 hover:bg-purple-600 text-white py-2 px-4 rounded-lg font-medium transition-colors">
                        Upgrade Now
                    </button>
                </form>
            </div>

            {{-- Enterprise Plan --}}
            <div class="bg-gray-900 rounded-xl p-8 border border-gray-800 hover:border-gray-700 transition-all">
                <h2 class="text-xl font-bold text-white mb-2">Enterprise</h2>
                <p class="text-gray-400 text-sm mb-6">For large-scale operations</p>
                <div class="mb-6">
                    <span class="text-4xl font-bold text-white">Custom</span>
                </div>
                <ul class="space-y-3 mb-8 text-sm text-gray-300">
                    <li class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-green-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                        All Pro features
                    </li>
                    <li class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-green-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                        Dedicated account manager
                    </li>
                    <li class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-green-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                        Custom integrations
                    </li>
                    <li class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-green-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                        API access
                    </li>
                </ul>
                <a href="mailto:sales@lapakgaming.com" class="w-full bg-gray-800 hover:bg-gray-700 text-white py-2 px-4 rounded-lg font-medium transition-colors text-center block">
                    Contact Sales
                </a>
            </div>
        </div>

        {{-- Benefits Section --}}
        <div class="bg-gray-900 rounded-xl p-12 border border-gray-800">
            <h2 class="text-2xl font-bold text-white mb-8">Why Upgrade?</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="flex gap-4">
                    <div class="flex-shrink-0">
                        <div class="flex items-center justify-center h-12 w-12 rounded-md bg-purple-500/20 text-purple-400">
                            <img src="{{ url('storage/app/public/logo/logo.png') }}" alt="Lapak Gaming" class="h-6 w-6 rounded-sm object-contain surface-weak p-0.5">
                        </div>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-white">Increased Visibility</h3>
                        <p class="text-gray-400 text-sm mt-2">Get featured in search results and recommendations to reach more customers.</p>
                    </div>
                </div>
                <div class="flex gap-4">
                    <div class="flex-shrink-0">
                        <div class="flex items-center justify-center h-12 w-12 rounded-md bg-purple-500/20 text-purple-400">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-white">Analytics & Insights</h3>
                        <p class="text-gray-400 text-sm mt-2">Track sales, customer behavior, and market trends with detailed analytics.</p>
                    </div>
                </div>
                <div class="flex gap-4">
                    <div class="flex-shrink-0">
                        <div class="flex items-center justify-center h-12 w-12 rounded-md bg-purple-500/20 text-purple-400">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                        </div>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-white">Priority Support</h3>
                        <p class="text-gray-400 text-sm mt-2">Get faster responses and dedicated support from our team.</p>
                    </div>
                </div>
                <div class="flex gap-4">
                    <div class="flex-shrink-0">
                        <div class="flex items-center justify-center h-12 w-12 rounded-md bg-purple-500/20 text-purple-400">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-white">Higher Profit Margins</h3>
                        <p class="text-gray-400 text-sm mt-2">Reduced commission rates for Pro members selling products.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection


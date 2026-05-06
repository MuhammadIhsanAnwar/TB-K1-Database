@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-950 py-12 px-4">
    <div class="max-w-4xl mx-auto">
        <div class="flex justify-between items-center mb-8">
            <h1 class="text-3xl font-bold text-white">My Profile</h1>
            <a href="{{ route('profile.edit') }}" class="bg-purple-600 hover:bg-purple-700 text-white font-medium py-2 px-4 rounded-lg transition-colors">
                Edit Profile
            </a>
        </div>

        <div class="bg-gray-900 rounded-xl p-8 space-y-6">
            {{-- Profile Header --}}
            <div class="flex items-center gap-6 pb-6 border-b border-gray-800">
                <div class="w-20 h-20 bg-gradient-to-br from-purple-500 to-pink-500 rounded-full flex items-center justify-center text-white text-2xl font-bold">
                    {{ substr($user->name, 0, 1) }}
                </div>
                <div>
                    <h2 class="text-2xl font-bold text-white">{{ $user->name }}</h2>
                    <p class="text-gray-400">{{ $user->email }}</p>
                    @if($user->phone)
                    <p class="text-gray-400">{{ $user->phone }}</p>
                    @endif
                </div>
            </div>

            {{-- Account Information --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <p class="text-gray-400 text-sm font-medium mb-2">Email Address</p>
                    <p class="text-white text-lg">{{ $user->email }}</p>
                </div>
                <div>
                    <p class="text-gray-400 text-sm font-medium mb-2">Full Name</p>
                    <p class="text-white text-lg">{{ $user->name }}</p>
                </div>
                <div>
                    <p class="text-gray-400 text-sm font-medium mb-2">Member Since</p>
                    <p class="text-white text-lg">{{ $user->created_at->format('d M Y') }}</p>
                </div>
                <div>
                    <p class="text-gray-400 text-sm font-medium mb-2">Account Status</p>
                    <span class="inline-block bg-green-500/20 text-green-400 border border-green-600/30 px-3 py-1 rounded-full text-sm">
                        Active
                    </span>
                </div>
            </div>

            {{-- User Profile Section --}}
            @if($user->userProfile)
            <div class="border-t border-gray-800 pt-6">
                <h3 class="text-xl font-semibold text-white mb-4">Additional Information</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @if($user->userProfile->phone_verified_at)
                    <div>
                        <p class="text-gray-400 text-sm font-medium mb-2">Phone Verified</p>
                        <span class="inline-block bg-green-500/20 text-green-400 border border-green-600/30 px-3 py-1 rounded-full text-sm">
                            ✓ Verified
                        </span>
                    </div>
                    @endif
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection

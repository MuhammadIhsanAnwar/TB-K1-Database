@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-950 py-12 px-4">
    <div class="max-w-2xl mx-auto">
        <h1 class="text-3xl font-bold text-white mb-8">Settings</h1>
        
        <div class="bg-gray-900 rounded-xl p-8">
            <div class="space-y-6">
                <div>
                    <h2 class="text-xl font-semibold text-white mb-4">Account Information</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="bg-gray-800 p-4 rounded-lg">
                            <p class="text-gray-400 text-sm">Name</p>
                            <p class="text-white font-medium">{{ Auth::user()->name }}</p>
                        </div>
                        <div class="bg-gray-800 p-4 rounded-lg">
                            <p class="text-gray-400 text-sm">Email</p>
                            <p class="text-white font-medium">{{ Auth::user()->email }}</p>
                        </div>
                    </div>
                </div>

                <div class="border-t border-gray-800 pt-6">
                    <h2 class="text-xl font-semibold text-white mb-4">Preferences</h2>
                    <p class="text-gray-400">Settings preferences will be available soon.</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

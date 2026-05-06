@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-950 py-12 px-4">
    <div class="max-w-2xl mx-auto">
        <h1 class="text-3xl font-bold text-white mb-8">Edit Profile</h1>

        <div class="bg-gray-900 rounded-xl p-8">
            @if ($errors->any())
            <div class="mb-6 bg-red-500/20 border border-red-600/30 rounded-lg p-4">
                <p class="text-red-400 font-medium mb-2">Please fix the following errors:</p>
                <ul class="list-disc list-inside text-red-400 text-sm space-y-1">
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <form action="{{ route('profile.update') }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')

                <div>
                    <label for="name" class="block text-sm font-medium text-gray-300 mb-2">Full Name</label>
                    <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" 
                        class="w-full bg-gray-800 border border-gray-700 rounded-lg px-4 py-2 text-white placeholder-gray-500 focus:border-purple-500 focus:outline-none"
                        required>
                    @error('name')
                    <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="email" class="block text-sm font-medium text-gray-300 mb-2">Email Address</label>
                    <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" 
                        class="w-full bg-gray-800 border border-gray-700 rounded-lg px-4 py-2 text-white placeholder-gray-500 focus:border-purple-500 focus:outline-none"
                        required>
                    @error('email')
                    <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="phone" class="block text-sm font-medium text-gray-300 mb-2">Phone Number (Optional)</label>
                    <input type="tel" name="phone" id="phone" value="{{ old('phone', $user->phone ?? '') }}" 
                        class="w-full bg-gray-800 border border-gray-700 rounded-lg px-4 py-2 text-white placeholder-gray-500 focus:border-purple-500 focus:outline-none"
                        placeholder="+62...">
                    @error('phone')
                    <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="border-t border-gray-800 pt-6 flex gap-3">
                    <button type="submit" class="flex-1 bg-purple-600 hover:bg-purple-700 text-white font-medium py-2 px-4 rounded-lg transition-colors">
                        Save Changes
                    </button>
                    <a href="{{ route('profile.show') }}" class="flex-1 bg-gray-800 hover:bg-gray-700 text-white font-medium py-2 px-4 rounded-lg transition-colors text-center">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

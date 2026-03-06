@extends('layouts.app')

@section('title', 'Reset Password')

@section('content')
<div class="max-w-md mx-auto px-4 py-10">
    <div class="bg-white rounded-lg shadow p-6">
        <h1 class="text-xl font-bold mb-4">Reset Password</h1>
        <form method="POST" action="{{ route('password.update') }}">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">
            <input type="email" name="email" class="w-full border rounded p-2 mb-3" placeholder="Email" required>
            <input type="password" name="password" class="w-full border rounded p-2 mb-3" placeholder="Password baru" required>
            <input type="password" name="password_confirmation" class="w-full border rounded p-2" placeholder="Konfirmasi password" required>
            <button class="w-full mt-4 bg-indigo-600 text-white py-2 rounded">Update Password</button>
        </form>
    </div>
</div>
@endsection

@extends('layouts.app')

@section('title', 'Profile')

@section('content')
<div class="max-w-xl mx-auto px-4 py-8">
    <h1 class="text-2xl font-bold mb-4">Edit Profile</h1>
    <form method="POST" action="{{ route('profile.update') }}" class="bg-white rounded-lg shadow p-6">
        @csrf
        <button class="px-4 py-2 bg-indigo-600 text-white rounded">Simpan Perubahan</button>
    </form>
</div>
@endsection

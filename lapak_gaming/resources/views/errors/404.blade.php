@extends('layouts.app')

@section('title', '404')

@section('content')
<div class="max-w-3xl mx-auto px-4 py-16 text-center">
    <h1 class="text-4xl font-bold mb-3">404</h1>
    <p class="text-gray-600 mb-6">Halaman tidak ditemukan.</p>
    <a href="{{ route('home') }}" class="px-4 py-2 bg-indigo-600 text-white rounded">Kembali ke Home</a>
</div>
@endsection

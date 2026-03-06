@extends('layouts.app')

@section('title', 'Setup Seller')

@section('content')
<div class="max-w-xl mx-auto px-4 py-8">
    <h1 class="text-2xl font-bold mb-4">Setup Akun Seller</h1>
    <form method="POST" action="{{ route('seller.setup.complete') }}" class="bg-white rounded-lg shadow p-6 space-y-3">
        @csrf
        <input name="shop_name" class="w-full border rounded p-2" placeholder="Nama toko" required>
        <input name="address" class="w-full border rounded p-2" placeholder="Alamat" required>
        <input name="city" class="w-full border rounded p-2" placeholder="Kota" required>
        <input name="province" class="w-full border rounded p-2" placeholder="Provinsi" required>
        <button class="px-4 py-2 bg-indigo-600 text-white rounded">Aktifkan Seller</button>
    </form>
</div>
@endsection

@extends('layouts.app')

@section('title', 'Seller Dashboard')

@section('content')
<div class="max-w-6xl mx-auto px-4 py-8">
    <h1 class="text-2xl font-bold mb-4">Seller Dashboard</h1>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <div class="bg-white rounded-lg shadow p-4">Total Sales: {{ $stats['total_sales'] ?? 0 }}</div>
        <div class="bg-white rounded-lg shadow p-4">Total Products: {{ $stats['total_products'] ?? 0 }}</div>
        <div class="bg-white rounded-lg shadow p-4">Pending Orders: {{ $stats['pending_orders'] ?? 0 }}</div>
    </div>
    <div class="bg-white rounded-lg shadow p-6 text-gray-600">Ringkasan toko seller.</div>
</div>
@endsection

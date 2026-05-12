@extends('layouts.app')

@section('title', 'Manajemen Akun — Admin')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-10 animate-fade-in">
    {{-- Header --}}
    <div class="mb-8">
        <h1 class="text-3xl font-display font-extrabold text-white mb-2">Manajemen Akun</h1>
        <p class="text-slate-400 text-sm">Kelola verifikasi seller dan data buyer platform.</p>
    </div>

    {{-- Statistik Singkat --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
        <div class="bg-gray-900/50 border border-gray-800 p-6 rounded-2xl card-glow">
            <div class="text-slate-500 text-xs font-bold uppercase tracking-wider mb-1">Total Buyers</div>
            <div class="text-3xl font-display font-bold text-white">{{ $buyers->total() }}</div>
        </div>
        <div class="bg-gray-900/50 border border-gray-800 p-6 rounded-2xl card-glow">
            <div class="text-slate-500 text-xs font-bold uppercase tracking-wider mb-1">Verified Sellers</div>
            <div class="text-3xl font-display font-bold text-emerald-500">{{ $sellers->total() }}</div>
        </div>
        <div class="bg-gray-900/50 border border-gray-800 p-6 rounded-2xl card-glow border-l-orange-500/50">
            <div class="text-slate-500 text-xs font-bold uppercase tracking-wider mb-1">Pending Verification</div>
            <div class="text-3xl font-display font-bold text-orange-500">
                {{-- Angka dummy jika belum ada variabelnya --}}
                {{ $pendingCount ?? 0 }}
            </div>
        </div>
    </div>

    {{-- Tab Navigation --}}
    <div class="flex gap-4 mb-6 border-b border-gray-800 pb-px">
        <a href="?tab=buyers" class="pb-4 px-2 text-sm font-bold transition-all {{ ($tab ?? 'buyers') == 'buyers' ? 'text-blue-500 border-b-2 border-blue-500' : 'text-slate-500 hover:text-slate-300' }}">
            Daftar Buyers
        </a>
        <a href="?tab=sellers" class="pb-4 px-2 text-sm font-bold transition-all {{ ($tab ?? '') == 'sellers' ? 'text-emerald-500 border-b-2 border-emerald-500' : 'text-slate-500 hover:text-slate-300' }}">
            Daftar Sellers
        </a>
    </div>

    {{-- Tabel Data --}}
    <div class="bg-gray-900/50 border border-gray-800 rounded-3xl overflow-hidden shadow-2xl">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-800/50">
                    <th class="p-5 text-xs font-bold text-slate-400 uppercase">User</th>
                    <th class="p-5 text-xs font-bold text-slate-400 uppercase">Kontak</th>
                    <th class="p-5 text-xs font-bold text-slate-400 uppercase">Status</th>
                    <th class="p-5 text-xs font-bold text-slate-400 uppercase text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-800">
                @php $currentData = ($tab ?? 'buyers') == 'sellers' ? $sellers : $buyers; @endphp
                
                @forelse($currentData as $user)
                <tr class="hover:bg-white/[0.02] transition-colors">
                    <td class="p-5">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-gray-800 flex items-center justify-center font-bold text-blue-500 border border-gray-700">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </div>
                            <div>
                                <div class="text-sm font-bold text-white">{{ $user->name }}</div>
                                <div class="text-xs text-slate-500">ID: #{{ $user->id }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="p-5">
                        <div class="text-sm text-slate-300">{{ $user->email }}</div>
                        <div class="text-xs text-slate-500">{{ $user->phone ?? '-' }}</div>
                    </td>
                    <td class="p-5">
                        @if($user->role == 'seller')
                            <span class="px-3 py-1 rounded-full text-[10px] font-bold bg-emerald-500/10 text-emerald-500 border border-emerald-500/20">VERIFIED SELLER</span>
                        @else
                            <span class="px-3 py-1 rounded-full text-[10px] font-bold bg-blue-500/10 text-blue-500 border border-blue-500/20">BUYER</span>
                        @endif
                    </td>
                    <td class="p-5 text-right">
                        <button class="text-xs font-bold text-slate-400 hover:text-white transition-colors px-3 py-2 bg-gray-800 rounded-lg">Detail</button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="p-20 text-center text-slate-500 text-sm">Tidak ada data ditemukan.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    <div class="mt-6">
        {{ $currentData->links() }}
    </div>
</div>
@endsection
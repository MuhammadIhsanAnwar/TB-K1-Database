@extends('layouts.app')

@section('title', 'Kelola Akun')

@section('content')
<div class="space-y-8 animate-fade-in">
    {{-- Header Section --}}
    <section class="rounded-3xl border border-slate-800 bg-slate-950 p-8 shadow-2xl relative overflow-hidden">
        <div class="absolute top-0 right-0 w-64 h-64 bg-amber-500/10 blur-[100px] -mr-32 -mt-32"></div>
        <div class="relative flex flex-col gap-6 lg:flex-row lg:items-center lg:justify-between">
            <div>
                <p class="text-xs uppercase tracking-[0.28em] text-amber-400 font-bold">User Management</p>
                <h1 class="mt-2 text-4xl font-black text-white tracking-tight">Kelola Akun</h1>
                <p class="mt-3 max-w-2xl text-slate-400 leading-relaxed">
                    Pusat kendali untuk memantau aktivitas buyer, mengelola seller terverifikasi, dan memproses pengajuan seller baru.
                </p>
            </div>
            <a href="{{ route('admin.dashboard') }}" class="group flex items-center gap-2 px-6 py-3 bg-slate-900 border border-slate-700 text-white rounded-2xl hover:border-amber-500/50 transition-all">
                <svg class="w-5 h-5 text-slate-500 group-hover:text-amber-500 transition" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                <span class="font-bold text-sm">Kembali ke Dashboard</span>
            </a>
        </div>
    </section>

    {{-- Tabs Navigation --}}
    <div class="flex flex-wrap gap-3 p-1.5 bg-slate-900/50 w-fit rounded-3xl border border-slate-800 backdrop-blur-sm">
        <a href="?tab=users" class="group flex items-center gap-3 px-6 py-3 rounded-2xl text-sm font-bold transition-all {{ $tab == 'users' ? 'bg-amber-500 text-slate-950 shadow-lg shadow-amber-500/20' : 'text-slate-400 hover:text-white hover:bg-slate-800' }}">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
            Buyer
            <span class="px-2 py-0.5 rounded-lg text-[10px] {{ $tab == 'users' ? 'bg-black/20' : 'bg-slate-800' }}">{{ number_format($counts['users']) }}</span>
        </a>
        
        <a href="?tab=sellers" class="group flex items-center gap-3 px-6 py-3 rounded-2xl text-sm font-bold transition-all {{ $tab == 'sellers' ? 'bg-amber-500 text-slate-950 shadow-lg shadow-amber-500/20' : 'text-slate-400 hover:text-white hover:bg-slate-800' }}">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
            Seller
            <span class="px-2 py-0.5 rounded-lg text-[10px] {{ $tab == 'sellers' ? 'bg-black/20' : 'bg-slate-800' }}">{{ number_format($counts['sellers']) }}</span>
        </a>

        <a href="?tab=applications" class="group flex items-center gap-3 px-6 py-3 rounded-2xl text-sm font-bold transition-all {{ $tab == 'applications' ? 'bg-amber-500 text-slate-950 shadow-lg shadow-amber-500/20' : 'text-slate-400 hover:text-white hover:bg-slate-800' }}">
            <div class="relative">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                @if($counts['apps'] > 0)
                    <span class="absolute -top-1 -right-1 flex h-2 w-2">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-rose-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2 w-2 bg-rose-500"></span>
                    </span>
                @endif
            </div>
            Pengajuan Seller
            <span class="px-2 py-0.5 rounded-lg text-[10px] {{ $counts['apps'] > 0 ? 'bg-rose-500 text-white font-black' : 'bg-slate-800' }}">
                {{ $counts['apps'] }}
            </span>
        </a>
    </div>

    {{-- Main Table Section --}}
    <div class="bg-slate-900 border border-slate-800 rounded-[2rem] overflow-hidden shadow-2xl">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead class="bg-slate-950/50 text-slate-500 text-[10px] uppercase tracking-[0.2em]">
                    <tr>
                        <th class="px-8 py-5 font-black">Informasi Pengguna</th>
                        <th class="px-8 py-5 font-black">Kontak & Detail</th>
                        <th class="px-8 py-5 font-black">Status Role</th>
                        <th class="px-8 py-5 font-black text-right">Aksi Manajemen</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800/50">
                    @php $data = ($tab == 'users') ? $users : (($tab == 'sellers') ? $sellers : $applications); @endphp
                    
                    @forelse($data as $u)
                    <tr class="group hover:bg-slate-800/30 transition-all">
                        {{-- User Profile --}}
                        <td class="px-8 py-6">
                            <div class="flex items-center gap-5">
                                <div class="relative">
                                    <img src="{{ $u->profile_photo_url ?? 'https://ui-avatars.com/api/?background=random&name='.urlencode($u->name) }}" 
                                         class="w-14 h-14 rounded-2xl object-cover border-2 border-slate-800 group-hover:border-amber-500/30 transition-all">
                                    <div class="absolute -bottom-1 -right-1 w-5 h-5 bg-slate-900 rounded-full border border-slate-800 flex items-center justify-center">
                                        <div class="w-2 h-2 rounded-full {{ $u->status == 'active' ? 'bg-emerald-500' : 'bg-rose-500' }}"></div>
                                    </div>
                                </div>
                                <div>
                                    <div class="font-bold text-white text-base group-hover:text-amber-400 transition">{{ $u->name }}</div>
                                    <div class="text-xs text-slate-500 mt-0.5 font-mono italic">User ID: #{{ $u->id }}</div>
                                </div>
                            </div>
                        </td>

                        {{-- Contact Detail --}}
                        <td class="px-8 py-6">
                            <div class="flex flex-col gap-1">
                                <div class="flex items-center gap-2 text-sm text-slate-300">
                                    <svg class="w-3.5 h-3.5 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2-2v10a2 2 0 002 2z"/></svg>
                                    {{ $u->email }}
                                </div>
                                <div class="text-xs text-slate-500 font-medium">Terdaftar: {{ $u->created_at->format('d M Y') }}</div>
                            </div>
                        </td>

                        {{-- Role Status --}}
                        <td class="px-8 py-6">
                            @if($tab == 'applications')
                                <div class="inline-flex items-center gap-2 px-3 py-1.5 bg-amber-500/10 border border-amber-500/20 rounded-xl">
                                    <span class="w-1.5 h-1.5 bg-amber-500 rounded-full animate-pulse"></span>
                                    <span class="text-[10px] font-black text-amber-500 uppercase tracking-tighter">Menunggu Verifikasi</span>
                                </div>
                            @else
                                <span class="px-3 py-1.5 {{ $u->role == 'seller' ? 'bg-emerald-500/10 text-emerald-400 border-emerald-500/20' : 'bg-blue-500/10 text-blue-400 border-blue-500/20' }} border text-[10px] font-black uppercase rounded-xl tracking-tighter">
                                    {{ $u->role }}
                                </span>
                            @endif
                        </td>

                        {{-- Action Buttons --}}
                        <td class="px-8 py-6">
                            <div class="flex justify-end items-center gap-3">
                                @if($tab == 'applications')
                                    <form action="{{ route('admin.users.approve-seller', $u) }}" method="POST">
                                        @csrf
                                        <button class="px-5 py-2.5 bg-emerald-600 hover:bg-emerald-500 text-white text-xs font-bold rounded-xl shadow-lg shadow-emerald-600/20 transition-all hover:-translate-y-0.5">Approve Seller</button>
                                    </form>
                                    <form action="{{ route('admin.users.reject-seller', $u) }}" method="POST">
                                        @csrf
                                        <button class="px-5 py-2.5 bg-slate-800 hover:bg-rose-600 text-slate-400 hover:text-white text-xs font-bold rounded-xl transition-all">Reject</button>
                                    </form>
                                @else
                                    <form action="{{ route('admin.users.status', $u) }}" method="POST" class="flex items-center gap-3 p-1.5 bg-slate-950 rounded-2xl border border-slate-800">
                                        @csrf @method('PUT')
                                        <select name="status" class="bg-transparent border-none text-slate-300 text-xs font-bold focus:ring-0 cursor-pointer">
                                            <option value="active" {{ $u->status == 'active' ? 'selected' : '' }}>🟢 Active</option>
                                            <option value="suspended" {{ $u->status == 'suspended' ? 'selected' : '' }}>🔴 Suspend</option>
                                        </select>
                                        <button class="p-2 bg-amber-500 text-slate-950 rounded-xl hover:bg-amber-400 transition shadow-lg shadow-amber-500/10">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                        </button>
                                    </form>
                                    
                                    <form action="{{ route('admin.users.destroy', $u) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus user ini selamanya?')">
                                        @csrf @method('DELETE')
                                        <button class="p-3 text-slate-600 hover:text-rose-500 transition-colors">
                                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-8 py-32 text-center">
                            <div class="flex flex-col items-center gap-4">
                                <div class="w-20 h-20 rounded-full bg-slate-800 flex items-center justify-center text-slate-600">
                                    <svg class="w-10 h-10" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/></svg>
                                </div>
                                <div class="text-slate-500 font-bold italic">Oops! Belum ada data di kategori ini.</div>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Pagination Styling --}}
    <div class="flex justify-center pt-4">
        <div class="px-6 py-4 bg-slate-900 border border-slate-800 rounded-2xl shadow-xl">
            {{ $data->links() }}
        </div>
    </div>
</div>

<style>
    /* Custom Scrollbar for the table */
    .overflow-x-auto::-webkit-scrollbar { height: 8px; }
    .overflow-x-auto::-webkit-scrollbar-track { background: #0f172a; }
    .overflow-x-auto::-webkit-scrollbar-thumb { background: #334155; border-radius: 10px; }
    .overflow-x-auto::-webkit-scrollbar-thumb:hover { background: #475569; }
    
    .animate-fade-in { animation: fadeIn 0.5s ease-out; }
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
</style>
@endsection
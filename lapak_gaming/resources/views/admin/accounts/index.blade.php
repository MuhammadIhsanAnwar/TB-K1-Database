@extends('layouts.app')

@section('title', 'Kelola Akun')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-white">Kelola Akun</h1>
            <p class="text-slate-400 mt-1">Manajemen pengguna, penjual, dan verifikasi pendaftaran.</p>
        </div>
        <a href="{{ route('admin.dashboard') }}" class="px-4 py-2 bg-slate-800 text-white rounded-xl hover:bg-slate-700 transition">
            &larr; Dashboard
        </a>
    </div>

    {{-- TABS NAVIGATION --}}
    <div class="flex gap-2 p-1 bg-slate-900 w-fit rounded-2xl border border-slate-800">
        <a href="?tab=users" class="px-6 py-2.5 rounded-xl text-sm font-bold transition {{ $tab == 'users' ? 'bg-amber-500 text-slate-950' : 'text-slate-400 hover:text-white' }}">
            User <span class="ml-2 px-2 py-0.5 bg-black/20 rounded-md">{{ $counts['users'] }}</span>
        </a>
        <a href="?tab=sellers" class="px-6 py-2.5 rounded-xl text-sm font-bold transition {{ $tab == 'sellers' ? 'bg-amber-500 text-slate-950' : 'text-slate-400 hover:text-white' }}">
            Seller <span class="ml-2 px-2 py-0.5 bg-black/20 rounded-md">{{ $counts['sellers'] }}</span>
        </a>
        <a href="?tab=applications" class="px-6 py-2.5 rounded-xl text-sm font-bold transition {{ $tab == 'applications' ? 'bg-amber-500 text-slate-950' : 'text-slate-400 hover:text-white' }}">
            Pengajuan Seller <span class="ml-2 px-2 py-0.5 {{ $counts['apps'] > 0 ? 'bg-rose-500 text-white' : 'bg-black/20' }} rounded-md">{{ $counts['apps'] }}</span>
        </a>
    </div>

    {{-- TAB CONTENT --}}
    <div class="bg-slate-900 border border-slate-800 rounded-3xl overflow-hidden shadow-xl">
        <table class="w-full text-left border-collapse">
            <thead class="bg-slate-950 text-slate-400 text-xs uppercase tracking-widest">
                <tr>
                    <th class="px-6 py-4 font-semibold">User</th>
                    <th class="px-6 py-4 font-semibold">Kontak</th>
                    <th class="px-6 py-4 font-semibold">Status</th>
                    <th class="px-6 py-4 font-semibold text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-800">
                @php $data = ($tab == 'users') ? $users : (($tab == 'sellers') ? $sellers : $applications); @endphp
                
                @forelse($data as $u)
                <tr class="hover:bg-slate-850/50 transition">
                    <td class="px-6 py-5">
                        <div class="flex items-center gap-4">
                            <img src="{{ $u->profile_photo_url ?? 'https://ui-avatars.com/api/?name='.urlencode($u->name) }}" class="w-12 h-12 rounded-2xl object-cover border border-slate-700">
                            <div>
                                <div class="font-bold text-white">{{ $u->name }}</div>
                                <div class="text-xs text-slate-500">ID #{{ $u->id }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-5">
                        <div class="text-sm text-slate-300 font-medium">{{ $u->email }}</div>
                        <div class="text-xs text-slate-500">{{ $u->phone ?? '-' }}</div>
                    </td>
                    <td class="px-6 py-5">
                        @if($tab == 'applications')
                            <span class="px-3 py-1 bg-amber-500/10 text-amber-500 text-[10px] font-bold uppercase rounded-lg border border-amber-500/20">Pending Review</span>
                        @else
                            <span class="px-3 py-1 {{ $u->role == 'seller' ? 'bg-emerald-500/10 text-emerald-500' : 'bg-blue-500/10 text-blue-400' }} text-[10px] font-bold uppercase rounded-lg">
                                {{ $u->role }}
                            </span>
                        @endif
                    </td>
                    <td class="px-6 py-5">
                        <div class="flex justify-end gap-2">
                            @if($tab == 'applications')
                                <form action="{{ route('admin.users.approve-seller', $u) }}" method="POST">
                                    @csrf
                                    <button class="px-4 py-2 bg-emerald-600 text-white text-xs font-bold rounded-xl hover:bg-emerald-500">Approve</button>
                                </form>
                                <form action="{{ route('admin.users.reject-seller', $u) }}" method="POST">
                                    @csrf
                                    <button class="px-4 py-2 bg-rose-600 text-white text-xs font-bold rounded-xl hover:bg-rose-500">Reject</button>
                                </form>
                            @else
                                {{-- Form Suspend/Update Status --}}
                                <form action="{{ route('admin.users.status', $u) }}" method="POST" class="flex items-center gap-2">
                                    @csrf @method('PUT')
                                    <select name="status" class="bg-slate-950 border-slate-700 text-white text-xs rounded-lg focus:ring-amber-500">
                                        <option value="active" {{ $u->status == 'active' ? 'selected' : '' }}>Active</option>
                                        <option value="suspended" {{ $u->status == 'suspended' ? 'selected' : '' }}>Suspend</option>
                                    </select>
                                    <button class="p-2 bg-amber-500 text-slate-950 rounded-lg hover:bg-amber-400">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                    </button>
                                </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="px-6 py-20 text-center">
                        <div class="text-slate-500 italic">Tidak ada data ditemukan di tab ini.</div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- PAGINATION --}}
    <div class="mt-4">
        {{ $data->links() }}
    </div>
</div>
@endsection
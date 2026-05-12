@extends('layouts.app')

@section('title', 'Kelola Akun — Admin')

@push('styles')
<style>
    /* ── Animasi Fade In ── */
    .animate-fade-in { animation: fadeIn 0.4s ease-out; }
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }

    /* ── Tab styles (Premium Version) ── */
    .tab-container {
        display: inline-flex;
        gap: 0.5rem;
        padding: 0.5rem;
        background: rgba(15, 23, 42, 0.6);
        backdrop-filter: blur(12px);
        border: 1px solid rgba(30, 41, 59, 1);
        border-radius: 24px;
    }
    .tab-btn {
        position: relative;
        padding: 0.75rem 1.5rem;
        border-radius: 18px;
        font-size: 0.875rem;
        font-weight: 700;
        color: #94a3b8;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        display: flex;
        align-items: center;
        gap: 0.75rem;
        border: none;
        background: transparent;
    }
    .tab-btn:hover { color: #fff; background: rgba(255, 255, 255, 0.05); }
    .tab-btn.active {
        color: #0f172a;
        background: #f59e0b;
        box-shadow: 0 10px 15px -3px rgba(245, 158, 11, 0.25);
    }

    /* ── Badge ── */
    .tab-badge {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 0.125rem 0.5rem;
        border-radius: 8px;
        font-size: 0.75rem;
        font-weight: 800;
        background: rgba(0, 0, 0, 0.15);
    }
    .badge-active-tab { background: rgba(255, 255, 255, 0.2); color: #000; }
    
    /* Efek Pulse untuk Pengajuan Seller */
    .pulse-ring {
        position: relative;
    }
    .pulse-ring::before {
        content: '';
        position: absolute;
        width: 8px; height: 8px;
        background: #ef4444;
        border-radius: 50%;
        top: -2px; right: -2px;
        animation: pulse 2s infinite;
    }
    @keyframes pulse {
        0% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(239, 68, 68, 0.7); }
        70% { transform: scale(1); box-shadow: 0 0 0 10px rgba(239, 68, 68, 0); }
        100% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(239, 68, 68, 0); }
    }

    /* ── Table Card ── */
    .table-container {
        background: #0f172a;
        border: 1px solid #1e293b;
        border-radius: 32px;
        overflow: hidden;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
    }
    .data-table thead {
        background: rgba(2, 6, 23, 0.5);
    }
    .data-table th {
        color: #64748b;
        font-size: 0.65rem;
        text-transform: uppercase;
        letter-spacing: 0.15em;
        font-weight: 800;
        padding: 1.25rem 1.5rem;
    }
    .data-table tr { transition: background 0.2s; }
    .data-table tr:hover { background: rgba(30, 41, 59, 0.4); }

    /* ── Status Pills (Refined) ── */
    .pill {
        padding: 0.35rem 0.85rem;
        border-radius: 12px;
        font-size: 0.7rem;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.025em;
        border: 1px solid transparent;
    }
    .pill-active { background: rgba(16, 185, 129, 0.1); color: #10b981; border-color: rgba(16, 185, 129, 0.2); }
    .pill-suspended { background: rgba(239, 68, 68, 0.1); color: #f87171; border-color: rgba(239, 68, 68, 0.2); }
    .pill-pending { background: rgba(245, 158, 11, 0.1); color: #f59e0b; border-color: rgba(245, 158, 11, 0.2); }

    /* ── Modals (Glassmorphism) ── */
    .modal-box {
        background: rgba(13, 20, 33, 0.95);
        backdrop-filter: blur(20px);
        border: 1px solid rgba(255, 255, 255, 0.05);
        box-shadow: 0 0 40px rgba(0,0,0,0.5);
    }
</style>
@endpush

@section('content')
<div class="min-h-screen bg-slate-950 py-12 px-4 animate-fade-in">
    <div class="mx-auto max-w-7xl space-y-8">

        {{-- Header dengan Glow Efek --}}
        <div class="relative p-8 rounded-[2.5rem] bg-slate-900 border border-slate-800 overflow-hidden shadow-2xl">
            <div class="absolute top-0 right-0 w-80 h-80 bg-amber-500/10 blur-[100px] -mr-40 -mt-40"></div>
            <div class="relative flex flex-col gap-6 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <p class="text-xs font-black uppercase tracking-[0.3em] text-amber-500">Admin Control Center</p>
                    <h1 class="mt-2 text-4xl font-black text-white tracking-tight">Kelola Akun</h1>
                    <p class="mt-2 text-slate-400 max-w-xl leading-relaxed">
                        Manajemen penuh terhadap ekosistem pengguna. Lakukan moderasi akun, verifikasi toko, dan pantau status merchant secara real-time.
                    </p>
                </div>
                <a href="{{ route('admin.dashboard') }}"
                   class="inline-flex items-center gap-3 rounded-2xl bg-slate-800 px-6 py-3.5 text-sm font-bold text-white hover:bg-slate-700 transition shadow-lg shadow-black/20">
                    <svg class="w-5 h-5 text-amber-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Dashboard
                </a>
            </div>
        </div>

        {{-- Tab Navigation --}}
        <div class="tab-container">
            <a href="{{ route('admin.accounts', ['tab' => 'users']) }}"
               class="tab-btn {{ $tab === 'users' ? 'active' : '' }}">
               <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
               Buyer
               <span class="tab-badge {{ $tab === 'users' ? 'badge-active-tab' : '' }}">{{ $regularUsers->total() }}</span>
            </a>

            <a href="{{ route('admin.accounts', ['tab' => 'sellers']) }}"
               class="tab-btn {{ $tab === 'sellers' ? 'active' : '' }}">
               <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
               Seller
               <span class="tab-badge {{ $tab === 'sellers' ? 'badge-active-tab' : '' }}">{{ $sellers->total() }}</span>
            </a>

            <a href="{{ route('admin.accounts', ['tab' => 'applications']) }}"
               class="tab-btn {{ $tab === 'applications' ? 'active' : '' }} {{ $applications->total() > 0 ? 'pulse-ring' : '' }}">
               <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
               Pengajuan
               @if($applications->total() > 0)
                 <span class="tab-badge {{ $tab === 'applications' ? 'badge-active-tab' : 'badge-pending' }}">{{ $applications->total() }}</span>
               @endif
            </a>
        </div>

        {{-- Tabel Data --}}
        <div class="table-container">
            @if($tab === 'users' || $tab === 'sellers')
                @php $currentData = ($tab === 'users') ? $regularUsers : $sellers; @endphp
                
                @if($currentData->isEmpty())
                    <div class="py-24 text-center">
                        <div class="w-20 h-20 bg-slate-800 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-10 h-10 text-slate-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        </div>
                        <p class="text-slate-500 font-bold italic">Belum ada data {{ $tab }} ditemukan.</p>
                    </div>
                @else
                    <table class="data-table w-full text-left">
                        <thead>
                            <tr>
                                <th>IDENTITAS PENGGUNA</th>
                                @if($tab === 'sellers') <th>NAMA TOKO</th> @endif
                                <th>KONTAK / EMAIL</th>
                                <th>STATUS AKUN</th>
                                <th class="text-right">KENDALI</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-800/50">
                            @foreach($currentData as $user)
                                <tr>
                                    <td class="px-6 py-5">
                                        <div class="flex items-center gap-4">
                                            <img src="{{ $user->avatar_url }}" class="w-12 h-12 rounded-2xl border-2 border-slate-800 group-hover:border-amber-500/50 transition-all shadow-lg" />
                                            <div>
                                                <p class="font-bold text-white">{{ $user->name }}</p>
                                                <p class="text-[10px] text-slate-500 font-mono tracking-tighter">UID: #{{ $user->id }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    @if($tab === 'sellers')
                                    <td class="px-6 py-5">
                                        <div class="flex items-center gap-3">
                                            @if($user->shop_photo)
                                                <img src="{{ $user->shop_photo_url }}" class="w-10 h-10 rounded-xl object-cover ring-1 ring-slate-700" />
                                            @endif
                                            <span class="text-sm font-semibold text-slate-200">{{ $user->shop_name ?? '—' }}</span>
                                        </div>
                                    </td>
                                    @endif
                                    <td class="px-6 py-5 text-sm text-slate-400 font-medium">{{ $user->email }}</td>
                                    <td class="px-6 py-5">
                                        <span class="pill {{ $user->status === 'active' ? 'pill-active' : 'pill-suspended' }}">
                                            {{ $user->status === 'active' ? '● Aktif' : '● Terhenti' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-5 text-right">
                                        @if($user->status === 'active')
                                            <button onclick="openSuspendModal({{ $user->id }}, '{{ addslashes($user->name) }}')"
                                                    class="px-5 py-2 bg-rose-600/10 text-rose-500 text-xs font-black rounded-xl border border-rose-500/20 hover:bg-rose-600 hover:text-white transition-all">
                                                SUSPEND
                                            </button>
                                        @else
                                            <form method="POST" action="{{ route('admin.users.status', $user) }}" class="inline">
                                                @csrf @method('PUT')
                                                <input type="hidden" name="status" value="active" />
                                                <button class="px-5 py-2 bg-emerald-600/10 text-emerald-400 text-xs font-black rounded-xl border border-emerald-500/20 hover:bg-emerald-600 hover:text-white transition-all">
                                                    AKTIFKAN
                                                </button>
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="p-6 bg-slate-950/30">
                        {{ $currentData->links() }}
                    </div>
                @endif
            @endif

            {{-- Tampilan Khusus Tab Applications (Card Mode lebih keren) --}}
            @if($tab === 'applications')
                @if($applications->isEmpty())
                     {{-- Gaya Empty State --}}
                @else
                    <div class="p-8 grid gap-6">
                        @foreach($applications as $applicant)
                            <div class="group rounded-[2rem] border border-slate-800 bg-slate-900/50 p-6 hover:border-amber-500/30 transition-all shadow-xl">
                                <div class="flex flex-col md:flex-row gap-8">
                                    <div class="shrink-0">
                                        <p class="text-[10px] font-black text-slate-600 uppercase tracking-[0.2em] mb-4">Foto Toko</p>
                                        <img src="{{ $applicant->shop_photo_url }}" class="w-full md:w-64 h-44 object-cover rounded-3xl border-4 border-slate-800 group-hover:border-amber-500/20 transition-all shadow-2xl" />
                                    </div>
                                    <div class="flex-1 space-y-6">
                                        <div class="flex items-center justify-between">
                                            <div class="flex items-center gap-4">
                                                <img src="{{ $applicant->avatar_url }}" class="w-12 h-12 rounded-2xl border-2 border-slate-800" />
                                                <div>
                                                    <h3 class="text-xl font-black text-white tracking-tight">{{ $applicant->shop_name }}</h3>
                                                    <p class="text-sm text-amber-500 font-bold">{{ $applicant->name }} <span class="text-slate-600 font-normal ml-2">({{ $applicant->email }})</span></p>
                                                </div>
                                            </div>
                                            <span class="pill pill-pending">MENUNGGU REVIEW</span>
                                        </div>
                                        <div class="p-5 rounded-2xl bg-slate-950/50 border border-slate-800">
                                            <p class="text-[10px] font-black text-slate-500 uppercase tracking-widest mb-2">Deskripsi Toko</p>
                                            <p class="text-slate-300 text-sm leading-relaxed">{{ $applicant->shop_description }}</p>
                                        </div>
                                        <div class="flex gap-3 pt-2">
                                            <form method="POST" action="{{ route('admin.users.approve-seller', $applicant) }}" class="flex-1">
                                                @csrf
                                                <button type="submit" class="w-full py-4 bg-emerald-600 text-white font-black rounded-2xl shadow-lg shadow-emerald-600/20 hover:bg-emerald-500 transition-all active:scale-95">APPROVE SELLER</button>
                                            </form>
                                            <button onclick="openRejectModal({{ $applicant->id }}, '{{ addslashes($applicant->name) }}')"
                                                    class="flex-1 py-4 bg-slate-800 text-slate-400 font-black rounded-2xl hover:bg-rose-600 hover:text-white transition-all active:scale-95">TOLAK</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            @endif
        </div>
    </div>
</div>

{{-- Modals tetap sama logikanya, hanya poles sedikit CSS di class modal-box --}}
@endsection
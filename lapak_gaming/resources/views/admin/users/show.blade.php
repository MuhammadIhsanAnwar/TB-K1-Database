@extends('layouts.app')

@section('title', 'Detail Pengguna')

@section('content')
<div class="min-h-screen bg-slate-950 py-12 px-4">
    <div class="mx-auto max-w-4xl rounded-3xl border border-slate-800 bg-slate-900 p-8">
        <div class="flex items-center justify-between gap-4">
            <div>
                <h1 class="text-3xl font-bold text-white">{{ $user->name }}</h1>
                <p class="mt-2 text-slate-400">{{ $user->email }}</p>
            </div>
            <a href="{{ route('admin.users.index') }}" class="rounded-2xl bg-slate-800 px-4 py-3 text-sm text-slate-300 hover:bg-slate-700">Kembali</a>
        </div>

        <form action="{{ route('admin.users.update', $user) }}" method="POST" class="mt-8 space-y-6 rounded-3xl border border-slate-800 bg-slate-950 p-6">
            @csrf
            @method('PUT')

            <div>
                <label class="text-sm font-medium text-slate-300">Nama</label>
                <input name="name" type="text" value="{{ old('name', $user->name) }}" class="mt-2 w-full rounded-2xl border border-slate-700 bg-slate-900 px-4 py-3 text-white outline-none" required />
            </div>

            <div>
                <label class="text-sm font-medium text-slate-300">Email</label>
                <input name="email" type="email" value="{{ old('email', $user->email) }}" class="mt-2 w-full rounded-2xl border border-slate-700 bg-slate-900 px-4 py-3 text-white outline-none" required />
            </div>

            <div class="grid gap-4 lg:grid-cols-2">
                <label class="block">
                    <span class="text-sm font-medium text-slate-300">Role</span>
                    <select name="role" class="mt-2 w-full rounded-2xl border border-slate-700 bg-slate-900 px-4 py-3 text-white outline-none" required>
                        @foreach(['buyer' => 'Buyer', 'seller' => 'Seller', 'admin' => 'Admin'] as $value => $label)
                            <option value="{{ $value }}" @selected(old('role', $user->role) === $value)>{{ $label }}</option>
                        @endforeach
                    </select>
                </label>
                <label class="block">
                    <span class="text-sm font-medium text-slate-300">Status</span>
                    <select name="status" class="mt-2 w-full rounded-2xl border border-slate-700 bg-slate-900 px-4 py-3 text-white outline-none" required>
                        @foreach(['active' => 'Active', 'suspended' => 'Suspended'] as $value => $label)
                            <option value="{{ $value }}" @selected(old('status', $user->status) === $value)>{{ $label }}</option>
                        @endforeach
                    </select>
                </label>
            </div>

            <div class="flex flex-col gap-3 sm:flex-row sm:items-center">
                <button type="submit" class="rounded-2xl bg-emerald-500 px-5 py-3 font-semibold text-slate-950 hover:bg-emerald-400">Simpan</button>
            </div>
        </form>

        @if ($user->role !== 'admin' && $user->is_seller && $user->role !== 'seller')
            <form action="{{ route('admin.users.approve-seller', $user) }}" method="POST" class="mt-6 rounded-3xl border border-amber-700 bg-slate-950 p-6">
                @csrf
                <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <h2 class="text-lg font-bold text-white">Verifikasi Seller</h2>
                        <p class="mt-2 text-slate-400">Set akun buyer ini menjadi seller dan aktifkan akses toko.</p>
                    </div>
                    <button type="submit" class="rounded-2xl bg-amber-500 px-5 py-3 font-semibold text-slate-950 hover:bg-amber-400">Verifikasi Seller</button>
                </div>
            </form>
        @endif

        <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="mt-6 rounded-3xl border border-red-700 bg-slate-950 p-6">
            @csrf
            @method('DELETE')
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h2 class="text-lg font-bold text-white">Hapus Pengguna</h2>
                    <p class="mt-2 text-slate-400">Tindakan ini permanen dan akan menghapus semua akses pengguna.</p>
                </div>
                <button type="submit" class="rounded-2xl bg-red-600 px-5 py-3 font-semibold text-white hover:bg-red-500">Hapus Akun</button>
            </div>
        </form>
    </div>
</div>
@endsection

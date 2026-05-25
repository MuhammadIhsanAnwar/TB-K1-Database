@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-surface py-12 px-4">
    <div class="max-w-3xl mx-auto">
        <div class="surface-panel rounded-3xl border border-white/10 p-8">
            <h1 class="text-3xl font-bold surface-text mb-4">Verifikasi Hapus Akun</h1>
            <p class="surface-muted mb-8">Untuk menghapus akun secara permanen, kami akan mengirimkan kode verifikasi ke email Anda. Masukkan kode tersebut untuk melanjutkan.</p>

            @if(session('status'))
                <div class="mb-6 rounded-2xl border border-cyan-500/20 bg-cyan-500/10 p-4 text-cyan-200">{{ session('status') }}</div>
            @endif
            @if($errors->any())
                <div class="mb-6 rounded-2xl border border-rose-500/20 bg-rose-500/10 p-4 text-rose-200">
                    <ul class="list-disc list-inside space-y-2 text-sm">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="grid gap-6 lg:grid-cols-2 mb-8">
                <div class="rounded-3xl surface-panel border border-white/10 p-6">
                    <h2 class="font-semibold surface-text mb-3">Nonaktifkan Akun</h2>
                    <p class="surface-muted mb-4">Akun Anda akan dinonaktifkan sementara. Anda dapat mengaktifkannya kembali dalam 6 bulan dengan login biasa. Jika tidak diaktifkan dalam 6 bulan, akun akan dihapus permanen.</p>
                    <form action="{{ route('settings.deactivate') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn-accent w-full">Nonaktifkan Akun</button>
                    </form>
                </div>
                <div class="rounded-3xl surface-panel border border-white/10 p-6">
                    <h2 class="font-semibold surface-text mb-3">Kirim Kode Verifikasi</h2>
                    <p class="surface-muted mb-4">Kode akan dikirimkan ke email terdaftar yang digunakan untuk akun ini.</p>
                    <form action="{{ route('settings.account.sendDeletionCode') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn-primary w-full">Kirim Kode ke Email</button>
                    </form>
                </div>
            </div>

            <form action="{{ route('settings.destroy') }}" method="POST" class="space-y-4">
                @csrf
                @method('DELETE')

                <div>
                    <label for="deletion_code" class="block text-sm font-medium surface-muted mb-2">Kode Verifikasi</label>
                    <input id="deletion_code" name="deletion_code" type="text" maxlength="6" value="{{ old('deletion_code') }}" class="w-full rounded-2xl border border-white/10 bg-surface-weak px-4 py-3 surface-text" placeholder="Masukkan 6 digit kode">
                </div>

                <button type="submit" class="btn-accent w-full">Hapus Akun Sekarang</button>
            </form>

            <div class="mt-6 text-sm surface-muted">
                <p><strong>Nonaktifkan Akun:</strong> Akun dinonaktifkan sementara, bisa diaktifkan kembali dalam 6 bulan. Jika tidak diaktifkan, akan dihapus permanen otomatis.</p>
                <p class="mt-2"><strong>Hapus Permanen:</strong> Akun dan data dihapus selamanya, tidak bisa dikembalikan.</p>
            </div>
        </div>
    </div>
</div>
@endsection

@extends('layouts.app')

@section('title', 'Pesan & Notifikasi Admin')

@section('content')
<div class="min-h-screen bg-slate-950 py-12 px-4">
    <div class="mx-auto max-w-6xl space-y-6">
        <div>
            <h1 class="text-3xl font-bold text-white">Pesan & Notifikasi</h1>
            <p class="mt-2 text-slate-400">Kirim pengumuman ke buyer, seller, atau semua akun sekaligus.</p>
        </div>

        <form action="{{ route('admin.notifications.send') }}" method="POST" class="grid gap-4 rounded-3xl border border-slate-800 bg-slate-900 p-6 lg:grid-cols-2">
            @csrf
            <div>
                <label class="block text-sm font-medium text-slate-300">Target</label>
                <select name="audience" class="mt-2 w-full rounded-2xl border border-slate-700 bg-slate-950 px-4 py-3 text-white">
                    <option value="all">Semua Akun</option>
                    <option value="buyer">Buyer</option>
                    <option value="seller">Seller</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-300">Kategori Pesan</label>
                <select name="category" class="mt-2 w-full rounded-2xl border border-slate-700 bg-slate-950 px-4 py-3 text-white" required>
                    <option value="general">Umum</option>
                    <option value="event_reward">Event & Hadiah</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-300">Judul</label>
                <input name="title" type="text" class="mt-2 w-full rounded-2xl border border-slate-700 bg-slate-950 px-4 py-3 text-white" required>
            </div>
            <div class="lg:col-span-2">
                <label class="block text-sm font-medium text-slate-300">Isi Pesan</label>
                <textarea name="body" rows="5" class="mt-2 w-full rounded-2xl border border-slate-700 bg-slate-950 px-4 py-3 text-white" required></textarea>
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-300">Link Opsional</label>
                <input name="link" type="url" class="mt-2 w-full rounded-2xl border border-slate-700 bg-slate-950 px-4 py-3 text-white" placeholder="https://...">
            </div>
            <div class="flex items-end justify-end">
                <button type="submit" class="rounded-2xl bg-amber-500 px-5 py-3 font-semibold text-slate-950 hover:bg-amber-400">Kirim Notifikasi</button>
            </div>
        </form>

        <div class="rounded-3xl border border-slate-800 bg-slate-900 p-6">
            <h2 class="text-xl font-semibold text-white">Riwayat Notifikasi Terbaru</h2>
            <div class="mt-4 space-y-4">
                @forelse ($notifications as $notification)
                    <article class="rounded-2xl border border-slate-800 bg-slate-950 p-4">
                        <div class="flex items-center justify-between gap-4">
                            <div class="flex flex-wrap items-center gap-2">
                                <h3 class="font-semibold text-white">{{ $notification->title }}</h3>
                                <span class="rounded-full border border-slate-700 bg-slate-900 px-2 py-0.5 text-[11px] text-slate-300">
                                    {{ $notification->category_label }}
                                </span>
                            </div>
                            <span class="text-xs text-slate-500">{{ $notification->created_at->diffForHumans() }}</span>
                        </div>
                        <p class="mt-2 text-sm text-slate-400">{{ $notification->body }}</p>
                    </article>
                @empty
                    <div class="text-slate-400">Belum ada notifikasi admin yang dikirim.</div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection

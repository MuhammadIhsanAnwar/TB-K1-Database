@extends('layouts.app')

@section('title', 'Pesan Masuk')

@section('content')
<div class="max-w-6xl mx-auto py-8">
  <h1 class="text-2xl font-bold mb-4">Pesan Masuk</h1>

  <div class="bg-surface-850 rounded-lg border border-white/6 overflow-hidden">
    <table class="w-full text-sm">
      <thead class="text-left text-slate-400 bg-transparent">
        <tr>
          <th class="px-4 py-3">Dari</th>
          <th class="px-4 py-3">Email</th>
          <th class="px-4 py-3">Topik</th>
          <th class="px-4 py-3">Kategori</th>
          <th class="px-4 py-3">Status</th>
          <th class="px-4 py-3">Diterima</th>
          <th class="px-4 py-3">Aksi</th>
        </tr>
      </thead>
      <tbody>
        @forelse($messages as $msg)
        <tr class="border-t border-white/6 hover:bg-white/2">
          <td class="px-4 py-3">{{ $msg->name }}</td>
          <td class="px-4 py-3">{{ $msg->email }}</td>
          <td class="px-4 py-3">{{ $msg->subject }}</td>
          <td class="px-4 py-3">{{ ucfirst($msg->category) }}</td>
          <td class="px-4 py-3">{{ ucfirst($msg->status) }}</td>
          <td class="px-4 py-3">{{ $msg->created_at->diffForHumans() }}</td>
          <td class="px-4 py-3">
            <a href="{{ route('admin.contact-messages.show', $msg->id) }}" class="text-itemku-blue">Lihat</a>
          </td>
        </tr>
        @empty
        <tr>
          <td colspan="7" class="px-4 py-6 text-center text-slate-400">Belum ada pesan masuk.</td>
        </tr>
        @endforelse
      </tbody>
    </table>
  </div>

  <div class="mt-4">
    {{ $messages->links() }}
  </div>
</div>
@endsection

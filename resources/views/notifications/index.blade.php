@extends('layouts.app')

@section('title', 'Notifikasi - SURATIN')

@section('content')
<div class="rounded-3xl border border-pink-100 bg-white/90 p-8 shadow-[0_30px_60px_-40px_rgba(236,72,153,0.45)]">
    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
        <div>
            <h1 class="text-2xl font-semibold text-slate-900">Notifikasi</h1>
            <div class="mt-3 flex flex-wrap items-center gap-2">
                <a class="rounded-full px-4 py-2 text-sm font-semibold transition {{ $filter === 'all' ? 'bg-pink-600 text-white shadow-sm' : 'border border-pink-200 bg-white text-pink-700 hover:bg-pink-50' }}" href="{{ route('notifications.index') }}">Semua</a>
                <a class="rounded-full px-4 py-2 text-sm font-semibold transition {{ $filter === 'unread' ? 'bg-pink-600 text-white shadow-sm' : 'border border-pink-200 bg-white text-pink-700 hover:bg-pink-50' }}" href="{{ route('notifications.index', ['status' => 'unread']) }}">Baru</a>
                <a class="rounded-full px-4 py-2 text-sm font-semibold transition {{ $filter === 'read' ? 'bg-pink-600 text-white shadow-sm' : 'border border-pink-200 bg-white text-pink-700 hover:bg-pink-50' }}" href="{{ route('notifications.index', ['status' => 'read']) }}">Dibaca</a>
            </div>
        </div>
    </div>

    @if ($notifications->isEmpty())
        <p class="text-sm text-slate-500">Belum ada notifikasi.</p>
    @else
        <div class="overflow-hidden rounded-2xl border border-pink-100">
            <table class="w-full text-sm">
                <thead class="bg-pink-50/60 text-left text-xs font-semibold uppercase tracking-wider text-slate-600">
                    <tr>
                        <th class="px-4 py-3">Waktu</th>
                        <th class="px-4 py-3">Pesan</th>
                        <th class="px-4 py-3">Status</th>
                        <th class="px-4 py-3 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-pink-100/70 bg-white">
                    @foreach ($notifications as $notification)
                        <tr>
                            <td class="px-4 py-3 text-slate-600">{{ $notification->created_at->timezone(config('app.timezone'))->format('d M Y H:i') }}</td>
                            <td class="px-4 py-3 font-medium text-slate-800">{{ $notification->message }}</td>
                            <td class="px-4 py-3">
                                @if ($notification->read_at)
                                    <span class="inline-flex rounded-full bg-emerald-100 px-3 py-1 text-xs font-semibold text-emerald-700">Dibaca</span>
                                @else
                                    <span class="inline-flex rounded-full bg-pink-100 px-3 py-1 text-xs font-semibold text-pink-700">Baru</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-right">
                                <form method="POST" action="{{ route('notifications.open', $notification) }}">
                                    @csrf
                                    <button class="text-sm font-semibold text-pink-700 hover:text-pink-800" type="submit">Buka Surat</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        @if ($notifications->hasPages())
            <div class="mt-6 flex flex-wrap items-center justify-between gap-3 text-sm text-slate-600">
                <div>Halaman {{ $notifications->currentPage() }} dari {{ $notifications->lastPage() }}</div>
                <div class="flex items-center gap-2">
                    @if ($notifications->onFirstPage())
                        <span class="rounded-full border border-pink-100 px-3 py-1 text-slate-400">Sebelumnya</span>
                    @else
                        <a class="rounded-full border border-pink-200 px-3 py-1 font-semibold text-pink-700 hover:bg-pink-50" href="{{ $notifications->previousPageUrl() }}">Sebelumnya</a>
                    @endif

                    @if ($notifications->hasMorePages())
                        <a class="rounded-full border border-pink-200 px-3 py-1 font-semibold text-pink-700 hover:bg-pink-50" href="{{ $notifications->nextPageUrl() }}">Berikutnya</a>
                    @else
                        <span class="rounded-full border border-pink-100 px-3 py-1 text-slate-400">Berikutnya</span>
                    @endif
                </div>
            </div>
        @endif
    @endif
</div>
@endsection

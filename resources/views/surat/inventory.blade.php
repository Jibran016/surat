@extends('layouts.app')

@section('title', 'Inventori File - Surat Menyurat')

@section('content')
<div class="rounded-2xl border border-slate-200 bg-white p-6">
    <div class="mb-4 flex flex-wrap items-start justify-between gap-3">
        <div>
            <h1 class="inline-flex items-center gap-2 text-2xl font-bold text-slate-900">
                <svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                    <path d="M7 10l5 5 5-5"></path>
                    <path d="M12 15V3"></path>
                </svg>
                Inventori File
            </h1>
            <p class="mt-1 text-sm text-slate-500">Daftar lampiran surat masuk dan surat keluar dalam satu halaman.</p>
        </div>
        <div class="grid grid-cols-3 gap-2 text-center text-xs">
            <div class="rounded-lg border border-slate-200 bg-slate-50 px-3 py-2">
                <div class="font-semibold text-slate-500">Total</div>
                <div class="mt-0.5 text-lg font-bold text-slate-900">{{ $totalFileCount }}</div>
            </div>
            <div class="rounded-lg border border-slate-200 bg-slate-50 px-3 py-2">
                <div class="font-semibold text-slate-500">Masuk</div>
                <div class="mt-0.5 text-lg font-bold text-slate-900">{{ $incomingFileCount }}</div>
            </div>
            <div class="rounded-lg border border-slate-200 bg-slate-50 px-3 py-2">
                <div class="font-semibold text-slate-500">Keluar</div>
                <div class="mt-0.5 text-lg font-bold text-slate-900">{{ $outgoingFileCount }}</div>
            </div>
        </div>
    </div>

    <form method="GET" action="{{ route('surat.inventory') }}" class="mb-4 flex gap-2">
        <input
            type="text"
            name="q"
            value="{{ $search }}"
            placeholder="Cari judul, nomor, jenis, nama file..."
            class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm text-slate-700 outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-100"
        >
        <button type="submit" class="inline-flex shrink-0 items-center rounded-lg bg-slate-900 px-3 py-2 text-sm font-semibold text-white hover:bg-slate-800">
            Cari
        </button>
    </form>

    <div class="mb-4 flex justify-end">
        <a href="{{ route('surat.inventory.upload') }}" class="inline-flex items-center gap-1 rounded-lg bg-slate-900 px-3 py-2 text-sm font-semibold text-white hover:bg-slate-800">
            Upload Surat Lama
            <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M12 3v12"></path>
                <path d="m7 10 5-5 5 5"></path>
                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
            </svg>
        </a>
    </div>

    <div class="overflow-hidden rounded-xl border border-slate-200">
        <div class="overflow-auto">
            <table class="w-full text-sm">
                <thead class="bg-slate-50 text-left text-xs font-semibold uppercase tracking-wider text-slate-600">
                    <tr>
                        <th class="px-4 py-3">Tanggal</th>
                        <th class="px-4 py-3">Tipe</th>
                        <th class="px-4 py-3">Jenis Surat</th>
                        <th class="px-4 py-3">Nomor</th>
                        <th class="px-4 py-3">Judul</th>
                        <th class="px-4 py-3">File</th>
                        <th class="px-4 py-3 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 bg-white">
                    @forelse ($files as $surat)
                        @php
                            $isOutgoing = $surat->sender_user_id === auth()->id();
                        @endphp
                        <tr>
                            <td class="px-4 py-3 text-slate-600">{{ optional($surat->sent_at)?->timezone(config('app.timezone'))->format('d M Y H:i') ?? '-' }}</td>
                            <td class="px-4 py-3">
                                <span class="inline-flex rounded-full px-2.5 py-1 text-xs font-semibold {{ $isOutgoing ? 'bg-emerald-100 text-emerald-700' : 'bg-blue-100 text-blue-700' }}">
                                    {{ $isOutgoing ? 'Keluar' : 'Masuk' }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-slate-700">{{ $surat->jenis ?? '-' }}</td>
                            <td class="px-4 py-3 font-medium text-slate-900">{{ $surat->nomor_surat ?? '-' }}</td>
                            <td class="px-4 py-3 text-slate-700">{{ $surat->judul }}</td>
                            <td class="px-4 py-3 text-slate-700">{{ $surat->lampiran_name ?? '-' }}</td>
                            <td class="px-4 py-3 text-right">
                                <div class="inline-flex items-center gap-2">
                                    <a class="rounded-md border border-slate-300 px-2 py-1 text-xs font-semibold text-slate-700 hover:bg-slate-100" href="{{ route('surat.show', $surat) }}">Detail</a>
                                    <a class="rounded-md border border-slate-300 px-2 py-1 text-xs font-semibold text-slate-700 hover:bg-slate-100" href="{{ route('surat.attachment', $surat) }}" target="_blank" rel="noopener">Buka</a>
                                    <a class="rounded-md border border-slate-300 px-2 py-1 text-xs font-semibold text-slate-700 hover:bg-slate-100" href="{{ route('surat.attachment', ['surat' => $surat, 'download' => 1]) }}">Download</a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-4 py-5 text-sm text-slate-500">Belum ada file lampiran yang dapat ditampilkan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-4 flex justify-end">
        @if ($files->hasMorePages())
            <a href="{{ $files->nextPageUrl() }}" class="inline-flex items-center gap-1 rounded-lg bg-slate-900 px-3 py-2 text-sm font-semibold text-white hover:bg-slate-800">
                Next
                <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="m9 18 6-6-6-6"></path>
                </svg>
            </a>
        @else
            <span class="inline-flex items-center gap-1 rounded-lg border border-slate-300 px-3 py-2 text-sm font-semibold text-slate-400">
                Next
                <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="m9 18 6-6-6-6"></path>
                </svg>
            </span>
        @endif
    </div>
</div>
@endsection

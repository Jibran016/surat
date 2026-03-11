@extends('layouts.app')

@section('title', 'Arsip Surat - SURATIN')

@section('content')
<div class="rounded-3xl border border-pink-100 bg-white/90 p-8 shadow-[0_30px_60px_-40px_rgba(236,72,153,0.45)]">
    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-semibold text-slate-900">Arsip Surat</h1>
            <p class="mt-2 text-sm text-slate-500">Semua surat dengan filter masuk atau keluar.</p>
        </div>
        <a class="inline-flex items-center gap-2 rounded-full border border-pink-200 bg-white px-4 py-2 text-sm font-semibold text-pink-700 shadow-sm transition hover:bg-pink-50" href="{{ route('dashboard') }}">Dashboard</a>
    </div>

    <div class="mb-5 flex flex-wrap items-center gap-2">
        <a class="rounded-full px-4 py-2 text-sm font-semibold transition {{ ($tipe ?? 'all') === 'all' ? 'bg-pink-600 text-white shadow-sm' : 'border border-pink-200 bg-white text-pink-700 hover:bg-pink-50' }}" href="{{ route('surat.archive') }}">Semua</a>
        <a class="rounded-full px-4 py-2 text-sm font-semibold transition {{ ($tipe ?? 'all') === 'masuk' ? 'bg-pink-600 text-white shadow-sm' : 'border border-pink-200 bg-white text-pink-700 hover:bg-pink-50' }}" href="{{ route('surat.archive', ['tipe' => 'masuk']) }}">Surat Masuk</a>
        <a class="rounded-full px-4 py-2 text-sm font-semibold transition {{ ($tipe ?? 'all') === 'keluar' ? 'bg-pink-600 text-white shadow-sm' : 'border border-pink-200 bg-white text-pink-700 hover:bg-pink-50' }}" href="{{ route('surat.archive', ['tipe' => 'keluar']) }}">Surat Keluar</a>
    </div>

    @if ($surats->isEmpty())
        <p class="text-sm text-slate-500">Belum ada data surat.</p>
    @else
        <div class="overflow-hidden rounded-2xl border border-pink-100">
            <table class="w-full text-sm">
                <thead class="bg-pink-50/60 text-left text-xs font-semibold uppercase tracking-wider text-slate-600">
                    <tr>
                        <th class="px-4 py-3">Tanggal Surat</th>
                        <th class="px-4 py-3">Nomor</th>
                        <th class="px-4 py-3">Judul</th>
                        <th class="px-4 py-3">Pengirim</th>
                        <th class="px-4 py-3">Penerima</th>
                        <th class="px-4 py-3">Tipe</th>
                        <th class="px-4 py-3">Status</th>
                        <th class="px-4 py-3 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-pink-100/70 bg-white">
                    @foreach ($surats as $surat)
                        @php
                            $statusClass = match (strtolower($surat->status)) {
                                'draft' => 'bg-slate-100 text-slate-700',
                                'sent' => 'bg-sky-100 text-sky-700',
                                'done' => 'bg-emerald-100 text-emerald-700',
                                'archived' => 'bg-amber-100 text-amber-700',
                                default => 'bg-pink-100 text-pink-700',
                            };
                        @endphp
                        <tr>
                            <td class="px-4 py-3 text-slate-600">{{ optional($surat->sent_at)?->timezone(config('app.timezone'))->format('d M Y H:i') ?? '-' }}</td>
                            <td class="px-4 py-3 font-medium text-slate-800">{{ $surat->nomor_surat ?? '-' }}</td>
                            <td class="px-4 py-3 font-medium text-slate-800">{{ $surat->judul }}</td>
                            <td class="px-4 py-3 text-slate-600">{{ $surat->sender_division }}</td>
                            <td class="px-4 py-3 text-slate-600">{{ $surat->recipient_division }}</td>
                            <td class="px-4 py-3 text-slate-600">
                                @if ($surat->recipient_division === auth()->user()->division)
                                    Masuk
                                @elseif ($surat->sender_user_id === auth()->id())
                                    Keluar
                                @else
                                    -
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                <span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold {{ $statusClass }}">{{ $surat->status }}</span>
                            </td>
                            <td class="px-4 py-3 text-right">
                                <a class="text-sm font-semibold text-pink-700 hover:text-pink-800" href="{{ route('surat.show', $surat) }}">Detail</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
@endsection

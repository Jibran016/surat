@extends('layouts.app')

@section('title', 'Surat Keluar - SURATIN')

@push('styles')
    <style>
        .outbox-table {
            font-size: 0.8125rem;
        }

        .outbox-target-cell {
            white-space: normal;
            word-break: normal;
            overflow-wrap: anywhere;
        }
    </style>
@endpush

@section('content')
<div class="rounded-3xl border border-pink-100 bg-white/90 p-8 shadow-[0_30px_60px_-40px_rgba(236,72,153,0.45)]">
    <div class="mb-6">
        <h1 class="text-2xl font-semibold text-slate-900">Surat Keluar</h1>
        <p class="mt-2 text-sm text-slate-500">Daftar surat yang sudah dikirim.</p>
    </div>

    @if ($outboxGroups->isEmpty())
        <p class="text-sm text-slate-500">Belum ada surat keluar.</p>
    @else
        <div class="rounded-2xl border border-pink-100">
            <table class="outbox-table w-full table-auto">
                <thead class="bg-pink-50/60 text-left text-xs font-semibold uppercase tracking-wider text-slate-600">
                    <tr>
                        <th class="px-3 py-3">Tanggal / Jam</th>
                        <th class="px-3 py-3">Nomor</th>
                        <th class="px-3 py-3">Judul</th>
                        <th class="px-3 py-3">Tujuan Divisi</th>
                        <th class="px-3 py-3 text-center">Jumlah</th>
                        <th class="px-3 py-3 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-pink-100/70 bg-white">
                    @foreach ($outboxGroups as $group)
                        @php
                            $surat = $group->surat;
                        @endphp
                        <tr class="align-top">
                            <td class="px-3 py-3 text-slate-600 whitespace-nowrap">
                                <div>{{ optional($surat->sent_at)?->timezone(config('app.timezone'))->format('d M Y') ?? '-' }}</div>
                                <div class="text-xs text-slate-500">{{ optional($surat->sent_at)?->timezone(config('app.timezone'))->format('H:i') ?? '-' }}</div>
                            </td>
                            <td class="px-3 py-3 font-medium text-slate-800 whitespace-nowrap">
                                {{ $surat->nomor_surat ?? '-' }}
                            </td>
                            <td class="px-3 py-3 font-medium text-slate-800">
                                {{ $surat->judul }}
                            </td>
                            <td class="outbox-target-cell px-3 py-3 text-slate-600">
                                <div class="space-y-1">
                                    @foreach ($group->recipient_divisions as $division)
                                        <div>{{ $division }}</div>
                                    @endforeach
                                </div>
                            </td>
                            <td class="px-3 py-3 text-center text-slate-600 whitespace-nowrap">
                                {{ $group->recipient_count }}
                            </td>
                            <td class="px-3 py-3">
                                <div class="flex items-center justify-end gap-3 whitespace-nowrap">
                                    <a class="text-sm font-semibold text-pink-700 hover:text-pink-800" href="{{ route('surat.show', $surat) }}">
                                        Detail
                                    </a>
                                    <a class="text-sm font-semibold text-pink-700 hover:text-pink-800" href="{{ route('surat.pdf', $surat) }}" target="_blank" rel="noopener">
                                        PDF
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
@endsection

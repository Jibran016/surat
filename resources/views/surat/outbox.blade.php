@extends('layouts.app')

@section('title', 'Surat Keluar - SURATIN')

@section('content')
<div class="rounded-3xl border border-pink-100 bg-white/90 p-8 shadow-[0_30px_60px_-40px_rgba(236,72,153,0.45)]">
    <div class="mb-6">
        <h1 class="text-2xl font-semibold text-slate-900">Surat Keluar</h1>
        <p class="mt-2 text-sm text-slate-500">Pantau status surat yang sudah dikirim.</p>
    </div>

    @if ($surats->isEmpty())
        <p class="text-sm text-slate-500">Belum ada surat keluar.</p>
    @else
        <div class="overflow-hidden rounded-2xl border border-pink-100">
            <table class="w-full text-sm">
                <thead class="bg-pink-50/60 text-left text-xs font-semibold uppercase tracking-wider text-slate-600">
                    <tr>
                        <th class="px-4 py-3">Tanggal</th>
                        <th class="px-4 py-3">Nomor</th>
                        <th class="px-4 py-3">Tujuan</th>
                        <th class="px-4 py-3">Judul</th>
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
                            <td class="px-4 py-3 text-slate-600">{{ $surat->recipient_division }}</td>
                            <td class="px-4 py-3 font-medium text-slate-800">{{ $surat->judul }}</td>
                            <td class="px-4 py-3">
                                <span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold {{ $statusClass }}">{{ $surat->status }}</span>
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex items-center justify-end gap-3">
                                    <a class="text-sm font-semibold text-pink-700 hover:text-pink-800" href="{{ route('surat.show', $surat) }}">Detail</a>
                                    <a class="text-sm font-semibold text-pink-700 hover:text-pink-800" href="{{ route('surat.pdf', $surat) }}" target="_blank" rel="noopener">PDF</a>
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

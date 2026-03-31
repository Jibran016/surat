@extends('layouts.app')

@section('title', 'Surat Semua Divisi - Surat Menyurat')

@section('content')
@php
    $suratCount = collect($surats ?? [])->count();
@endphp

<div class="rounded-2xl border border-slate-200 bg-white p-6">
    <div class="mb-5 flex flex-wrap items-start justify-between gap-3">
        <div>
            <h1 class="inline-flex items-center gap-2 text-2xl font-bold text-slate-900">
                <svg viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="5" width="18" height="14" rx="2"></rect><path d="m3 7 9 6 9-6"></path></svg>
                Surat Semua Divisi
            </h1>
            <p class="mt-1 text-sm text-slate-500">Lihat surat masuk dan keluar seluruh divisi.</p>
        </div>
        <div class="inline-flex items-center gap-2 rounded-lg border border-slate-200 bg-slate-50 px-3 py-2 text-sm font-semibold text-slate-700">
            <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 3h18v18H3z"></path><path d="M9 9h6v6H9z"></path></svg>
            Total: {{ $suratCount }}
        </div>
    </div>

    <div class="mb-4 flex flex-wrap items-center justify-between gap-3">
        <div class="inline-flex rounded-full border border-slate-200 bg-slate-50 p-1 text-sm font-semibold">
            <a class="rounded-full px-3 py-1.5 {{ ($tipe ?? 'masuk') === 'masuk' ? 'bg-white text-slate-900 shadow-sm' : 'text-slate-600' }}" href="{{ route('admin.surat.index', ['tipe' => 'masuk', 'q' => $search ?? '']) }}">Surat Masuk</a>
            <a class="rounded-full px-3 py-1.5 {{ ($tipe ?? 'masuk') === 'keluar' ? 'bg-white text-slate-900 shadow-sm' : 'text-slate-600' }}" href="{{ route('admin.surat.index', ['tipe' => 'keluar', 'q' => $search ?? '']) }}">Surat Keluar</a>
        </div>
        <form method="GET" action="{{ route('admin.surat.index') }}" class="flex w-full max-w-md items-center gap-2 sm:w-auto">
            <input type="hidden" name="tipe" value="{{ $tipe ?? 'masuk' }}">
            <input
                type="text"
                name="q"
                value="{{ $search ?? '' }}"
                placeholder="Cari nomor, judul, pengirim, penerima..."
                class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm text-slate-700 outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-100"
            >
            <button type="submit" class="inline-flex shrink-0 items-center rounded-lg bg-slate-900 px-3 py-2 text-sm font-semibold text-white hover:bg-slate-800">
                Cari
            </button>
        </form>
    </div>

    <div class="overflow-hidden rounded-xl border border-slate-200">
        <div class="overflow-auto">
            <table class="w-full text-sm">
                <thead class="bg-slate-50 text-left text-xs font-semibold uppercase tracking-wider text-slate-600">
                    <tr>
                        <th class="px-4 py-3">Tanggal</th>
                        <th class="px-4 py-3">Nomor</th>
                        <th class="px-4 py-3">Pengirim</th>
                        <th class="px-4 py-3">Penerima</th>
                        <th class="px-4 py-3">CC</th>
                        <th class="px-4 py-3">Perihal</th>
                        <th class="px-4 py-3 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 bg-white">
                    @forelse ($surats ?? [] as $surat)
                        @php
                            $ccList = collect($surat->cc_divisions ?? [])->filter()->implode(', ');
                        @endphp
                        <tr>
                            <td class="px-4 py-3 text-slate-600">{{ optional($surat->sent_at)?->timezone(config('app.timezone'))->format('d M Y H:i') ?? '-' }}</td>
                            <td class="px-4 py-3 font-medium text-slate-900">{{ $surat->nomor_surat ?? '-' }}</td>
                            <td class="px-4 py-3 text-slate-600">{{ $surat->sender_division }}</td>
                            <td class="px-4 py-3 text-slate-600">{{ $surat->recipient_division }}</td>
                            <td class="px-4 py-3 text-slate-600">{{ $ccList !== '' ? $ccList : '-' }}</td>
                            <td class="px-4 py-3 font-medium text-slate-900">{{ $surat->judul }}</td>
                            <td class="px-4 py-3 text-right">
                                <a class="inline-flex items-center gap-1 text-sm font-semibold text-slate-900 hover:text-blue-700" href="{{ route('surat.show', $surat) }}">Detail <svg viewBox="0 0 24 24" width="12" height="12" fill="none" stroke="currentColor" stroke-width="2"><path d="m9 18 6-6-6-6"></path></svg></a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-4 py-4 text-sm text-slate-500">Belum ada data surat.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@extends('layouts.app')

@section('title', 'Monitoring Divisi - SURATIN')

@section('content')
<script src="https://unpkg.com/lucide@latest"></script>

@php
    $totalMasuk = collect($divisionMonitoring ?? [])->sum('masuk_count');
    $totalKeluar = collect($divisionMonitoring ?? [])->sum('keluar_count');
@endphp

<div class="rounded-3xl border border-pink-100 bg-white/90 p-8 shadow-[0_30px_60px_-40px_rgba(236,72,153,0.45)]">
    <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <div class="text-sm font-semibold uppercase tracking-widest text-pink-600">Admin</div>
            <h1 class="mt-1 text-2xl font-semibold text-slate-900">Monitoring Surat</h1>
            <p class="mt-1 text-sm text-slate-500">Rekap surat masuk dan keluar.</p>
        </div>
        <a href="{{ route('dashboard') }}" aria-label="Kembali" title="Kembali" class="inline-flex items-center justify-center rounded-full border border-pink-200 bg-white p-2.5 text-pink-700 shadow-sm transition hover:bg-pink-50">
            <i data-lucide="arrow-left" class="h-5 w-5"></i>
        </a>
    </div>

    <div class="mb-5 grid gap-3 sm:grid-cols-2">
        <div class="rounded-2xl border border-pink-100 bg-pink-50/50 px-4 py-3">
            <div class="text-xs uppercase tracking-wider text-slate-500">Total Surat Masuk</div>
            <div class="mt-1 text-2xl font-semibold text-slate-900">{{ $totalMasuk }}</div>
        </div>
        <div class="rounded-2xl border border-pink-100 bg-pink-50/50 px-4 py-3">
            <div class="text-xs uppercase tracking-wider text-slate-500">Total Surat Keluar</div>
            <div class="mt-1 text-2xl font-semibold text-slate-900">{{ $totalKeluar }}</div>
        </div>
    </div>

    <div class="overflow-hidden rounded-2xl border border-pink-100">
        <table class="w-full text-sm">
            <thead class="bg-pink-50/60 text-left text-xs font-semibold uppercase tracking-wider text-slate-600">
                <tr>
                    <th class="px-4 py-3">Divisi</th>
                    <th class="px-4 py-3">No. Divisi</th>
                    <th class="px-4 py-3">Surat Masuk</th>
                    <th class="px-4 py-3">Surat Keluar</th>
                    <th class="px-4 py-3">Total</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-pink-100/70 bg-white">
                @forelse ($divisionMonitoring ?? [] as $item)
                    <tr>
                        <td class="px-4 py-3 font-medium text-slate-800">{{ $item->name }}</td>
                        <td class="px-4 py-3 text-slate-600">{{ $item->unit_code ?: '-' }}</td>
                        <td class="px-4 py-3 text-slate-600">{{ $item->masuk_count }}</td>
                        <td class="px-4 py-3 text-slate-600">{{ $item->keluar_count }}</td>
                        <td class="px-4 py-3 text-slate-600">{{ $item->total_count }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-4 py-4 text-sm text-slate-500">Belum ada data.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<script>
    lucide.createIcons();
</script>
@endsection

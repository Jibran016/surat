@extends('layouts.app')

@section('title', 'Monitoring Divisi - Surat Menyurat')

@section('content')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

@php
    $totalMasuk = collect($divisionMonitoring ?? [])->sum('masuk_count');
    $totalKeluar = collect($divisionMonitoring ?? [])->sum('keluar_count');
    $chartLabels = collect($divisionMonitoring ?? [])->pluck('name')->values();
    $chartMasuk = collect($divisionMonitoring ?? [])->pluck('masuk_count')->values();
    $chartKeluar = collect($divisionMonitoring ?? [])->pluck('keluar_count')->values();
@endphp

<div class="rounded-2xl border border-slate-200 bg-white p-6">
    <div class="mb-5">
        <h1 class="inline-flex items-center gap-2 text-2xl font-bold text-slate-900">
            <svg viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 3v18h18"></path><path d="m19 9-5 5-4-4-3 3"></path></svg>
            Monitoring Surat
        </h1>
        <p class="mt-1 text-sm text-slate-500">Rekap surat masuk dan keluar per divisi.</p>
    </div>

    <div class="mb-5 grid gap-3 sm:grid-cols-3">
        <div class="rounded-xl border border-slate-200 bg-slate-50 p-4">
            <div class="text-xs uppercase tracking-wide text-slate-500">Total Semua Surat</div>
            <div class="mt-1 text-2xl font-bold text-slate-900">{{ $totalSuratCount ?? 0 }}</div>
        </div>
        <div class="rounded-xl border border-slate-200 bg-slate-50 p-4">
            <div class="text-xs uppercase tracking-wide text-slate-500">Total Surat Masuk</div>
            <div class="mt-1 text-2xl font-bold text-slate-900">{{ $totalMasuk }}</div>
        </div>
        <div class="rounded-xl border border-slate-200 bg-slate-50 p-4">
            <div class="text-xs uppercase tracking-wide text-slate-500">Total Surat Keluar</div>
            <div class="mt-1 text-2xl font-bold text-slate-900">{{ $totalKeluar }}</div>
        </div>
    </div>

    <div class="mb-5 rounded-xl border border-slate-200 bg-white p-4">
        <div class="mb-3 text-sm font-semibold text-slate-800">Grafik Surat per Divisi</div>
        <div class="h-[260px]"><canvas id="divisionChart"></canvas></div>
    </div>

    <div class="overflow-hidden rounded-xl border border-slate-200">
        <table class="w-full text-sm">
            <thead class="bg-slate-50 text-left text-xs font-semibold uppercase tracking-wider text-slate-600">
                <tr>
                    <th class="px-4 py-3">Divisi</th>
                    <th class="px-4 py-3">Surat Masuk</th>
                    <th class="px-4 py-3">Surat Keluar</th>
                    <th class="px-4 py-3">Total</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-200 bg-white">
                @forelse ($divisionMonitoring ?? [] as $item)
                    <tr>
                        <td class="px-4 py-3 font-medium text-slate-900">{{ $item->name }}</td>
                        <td class="px-4 py-3 text-slate-600">{{ $item->masuk_count }}</td>
                        <td class="px-4 py-3 text-slate-600">{{ $item->keluar_count }}</td>
                        <td class="px-4 py-3 text-slate-600">{{ $item->total_count }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-4 py-4 text-sm text-slate-500">Belum ada data.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<script>
    const divisionChartEl = document.getElementById('divisionChart');
    if (divisionChartEl) {
        const labels = @json($chartLabels ?? []);
        const masukData = @json($chartMasuk ?? []);
        const keluarData = @json($chartKeluar ?? []);

        new Chart(divisionChartEl, {
            type: 'bar',
            data: {
                labels,
                datasets: [
                    {
                        label: 'Surat Masuk',
                        data: masukData,
                        backgroundColor: 'rgba(37, 99, 235, 0.75)',
                        borderRadius: 8,
                    },
                    {
                        label: 'Surat Keluar',
                        data: keluarData,
                        backgroundColor: 'rgba(16, 185, 129, 0.75)',
                        borderRadius: 8,
                    },
                ],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: true },
                },
                scales: {
                    y: { beginAtZero: true, ticks: { precision: 0 } },
                    x: { grid: { display: false } },
                },
            },
        });
    }
</script>
@endsection

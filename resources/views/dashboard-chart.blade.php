@extends('layouts.app')

@section('title', 'Grafik Surat - SURATIN')

@section('content')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<div class="mx-auto w-full max-w-6xl rounded-3xl border border-slate-200 bg-white p-6">
    <div class="mb-4 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-semibold text-slate-900">Dashboard</h1>
            <p class="mt-1 text-sm text-slate-500">Ringkasan surat masuk dan surat keluar divisi Anda.</p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('dashboard.chart', ['mode' => 'mingguan']) }}" class="rounded-full px-4 py-2 text-sm font-semibold transition {{ ($chartMode ?? 'bulanan') === 'mingguan' ? 'bg-slate-900 text-white shadow-sm' : 'border border-slate-300 bg-white text-slate-700 hover:bg-slate-100' }}">
                Mingguan
            </a>
            <a href="{{ route('dashboard.chart', ['mode' => 'bulanan']) }}" class="rounded-full px-4 py-2 text-sm font-semibold transition {{ ($chartMode ?? 'bulanan') === 'bulanan' ? 'bg-slate-900 text-white shadow-sm' : 'border border-slate-300 bg-white text-slate-700 hover:bg-slate-100' }}">
                Bulanan
            </a>
        </div>
    </div>

    <div class="mb-2 text-sm font-semibold text-slate-700">Ringkasan Total Jumlah Surat</div>
    <div class="mb-4 grid gap-3 sm:grid-cols-3">
        <a href="{{ route('surat.index', ['tipe' => 'masuk']) }}" class="group rounded-xl border border-slate-200 bg-slate-50 p-4 transition hover:border-blue-300 hover:bg-blue-50">
            <div class="text-xs uppercase tracking-wide text-slate-500">Total Surat Masuk</div>
            <div class="mt-1 text-2xl font-bold text-slate-900">{{ $summaryMasuk ?? 0 }}</div>
        </a>
        <a href="{{ route('surat.index', ['tipe' => 'keluar']) }}" class="group rounded-xl border border-slate-200 bg-slate-50 p-4 transition hover:border-emerald-300 hover:bg-emerald-50">
            <div class="text-xs uppercase tracking-wide text-slate-500">Total Surat Keluar</div>
            <div class="mt-1 text-2xl font-bold text-slate-900">{{ $summaryKeluar ?? 0 }}</div>
        </a>
        <a href="{{ route('surat.index', ['tipe' => 'all']) }}" class="group rounded-xl border border-slate-200 bg-slate-50 p-4 transition hover:border-slate-300 hover:bg-slate-100">
            <div class="text-xs uppercase tracking-wide text-slate-500">Total Semua Surat</div>
            <div class="mt-1 text-2xl font-bold text-slate-900">{{ $summaryTotal ?? 0 }}</div>
        </a>
    </div>

    <div class="grid gap-4 md:grid-cols-2">
        <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
            <div class="mb-3 text-sm font-semibold text-slate-800">Grafik Surat (Bar)</div>
            <div class="h-[320px]">
                <canvas id="fullSuratChart"></canvas>
            </div>
        </div>

        <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
            <div class="mb-3 text-sm font-semibold text-slate-800">Surat Masuk Terbaru</div>
            <div class="overflow-hidden rounded-xl border border-slate-200 bg-white">
                <table class="w-full text-sm">
                    <thead class="bg-slate-50 text-left text-xs font-semibold uppercase tracking-wider text-slate-600">
                        <tr>
                            <th class="px-3 py-2">Tanggal</th>
                            <th class="px-3 py-2">Pengirim</th>
                            <th class="px-3 py-2">Judul</th>
                            <th class="px-3 py-2 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200">
                        @forelse ($inboxPreview ?? [] as $surat)
                            <tr>
                                <td class="px-3 py-2 text-slate-600">{{ optional($surat->sent_at)?->timezone(config('app.timezone'))->format('d/m H:i') ?? '-' }}</td>
                                <td class="px-3 py-2 text-slate-700">{{ $surat->sender_division }}</td>
                                <td class="px-3 py-2 font-medium text-slate-900">{{ \Illuminate\Support\Str::limit($surat->judul, 42) }}</td>
                                <td class="px-3 py-2 text-right">
                                    <a href="{{ route('surat.show', $surat) }}" class="text-xs font-semibold text-slate-700 hover:text-blue-700">Detail</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-3 py-3 text-xs text-slate-500">Belum ada surat masuk.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
    const fullChartEl = document.getElementById('fullSuratChart');
    if (fullChartEl) {
        const labels = @json($chartLabels ?? []);
        const masukData = @json($chartMasukData ?? []);
        const keluarData = @json($chartKeluarData ?? []);
        const maxValue = Math.max(...masukData, ...keluarData, 1);

        new Chart(fullChartEl, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [
                    {
                        label: 'Surat Masuk',
                        data: masukData,
                        backgroundColor: 'rgba(37, 99, 235, 0.78)',
                        borderRadius: 8,
                        barPercentage: 0.64,
                        categoryPercentage: 0.7,
                    },
                    {
                        label: 'Surat Keluar',
                        data: keluarData,
                        backgroundColor: 'rgba(16, 185, 129, 0.78)',
                        borderRadius: 8,
                        barPercentage: 0.64,
                        categoryPercentage: 0.7,
                    },
                ],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: true,
                        labels: {
                            color: '#334155',
                            boxWidth: 12,
                            usePointStyle: true,
                        },
                    },
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        suggestedMax: maxValue + 1,
                        ticks: { precision: 0, color: '#475569' },
                        grid: { color: 'rgba(148, 163, 184, 0.35)' },
                    },
                    x: {
                        ticks: { color: '#475569' },
                        grid: { display: false },
                    },
                },
            },
        });
    }
</script>
@endsection

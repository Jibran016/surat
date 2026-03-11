@extends('layouts.app')

@section('title', 'Grafik Surat - SURATIN')

@section('content')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<div class="mx-auto w-full max-w-5xl rounded-3xl border border-pink-100 bg-white/90 p-6 shadow-[0_30px_60px_-40px_rgba(236,72,153,0.45)]">
    <div class="mb-5 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-semibold text-slate-900">Grafik Surat</h1>
            <p class="mt-1 text-sm text-slate-500">Grafik surat masuk dan surat keluar.</p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('dashboard.chart', ['mode' => 'mingguan']) }}" class="rounded-full px-4 py-2 text-sm font-semibold transition {{ ($chartMode ?? 'bulanan') === 'mingguan' ? 'bg-pink-600 text-white shadow-sm' : 'border border-pink-200 bg-white text-pink-700 hover:bg-pink-50' }}">
                Mingguan
            </a>
            <a href="{{ route('dashboard.chart', ['mode' => 'bulanan']) }}" class="rounded-full px-4 py-2 text-sm font-semibold transition {{ ($chartMode ?? 'bulanan') === 'bulanan' ? 'bg-pink-600 text-white shadow-sm' : 'border border-pink-200 bg-white text-pink-700 hover:bg-pink-50' }}">
                Bulanan
            </a>
        </div>
    </div>

    <div class="rounded-2xl border border-pink-100/70 bg-black/20 p-4">
        <canvas id="fullSuratChart" height="130"></canvas>
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
            type: 'line',
            data: {
                labels: labels,
                datasets: [
                    {
                        label: 'Surat Masuk',
                        data: masukData,
                        borderColor: '#ff73ea',
                        backgroundColor: 'rgba(255, 115, 234, 0.2)',
                        pointBackgroundColor: '#ff9cf0',
                        pointRadius: 3,
                        tension: 0.35,
                        fill: true,
                    },
                    {
                        label: 'Surat Keluar',
                        data: keluarData,
                        borderColor: '#ffd0f7',
                        backgroundColor: 'rgba(255, 208, 247, 0.12)',
                        pointBackgroundColor: '#ffe6fb',
                        pointRadius: 3,
                        tension: 0.35,
                        fill: true,
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
                            color: '#f5f2ff',
                            boxWidth: 12,
                            usePointStyle: true,
                        },
                    },
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        suggestedMax: maxValue + 1,
                        ticks: { precision: 0, color: '#e8dff7' },
                        grid: { color: 'rgba(255, 122, 232, 0.18)' },
                    },
                    x: {
                        ticks: { color: '#f5f2ff' },
                        grid: { display: false },
                    },
                },
            },
        });
    }
</script>
@endsection


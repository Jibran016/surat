@extends('layouts.app')

@section('title', 'Dashboard - SURATIN')

@section('content')
<script src="https://unpkg.com/lucide@latest"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<div class="mx-auto w-full max-w-6xl">
    <section class="grid h-[calc(100vh-200px)] grid-cols-12 gap-2 overflow-hidden">
        <div class="col-span-7 flex h-[86px] items-center justify-between rounded-3xl border border-pink-100 bg-white/90 px-5 py-3 shadow-[0_26px_50px_-40px_rgba(236,72,153,0.45)]">
            <a href="{{ route('users.profile') }}" class="group flex flex-col">
                <div class="text-[11px] uppercase tracking-widest text-pink-600">Divisi</div>
                <div class="text-lg font-semibold text-slate-900 group-hover:text-pink-700 transition">{{ auth()->user()->division ?? '-' }}</div>
                <div class="text-xs text-slate-500">{{ auth()->user()->username }}</div>
            </a>
            <a href="{{ route('surat.create') }}" class="inline-flex items-center justify-center gap-2 rounded-full bg-pink-600 px-4 py-2 text-xs font-semibold text-white shadow-sm transition hover:bg-pink-700">
                <i data-lucide="plus" class="h-4 w-4"></i>
                Kirim Surat
            </a>
        </div>

        <div class="col-span-5 grid h-[86px] grid-cols-2 gap-3">
            <a href="{{ route('surat.outbox') }}" class="flex flex-col justify-between rounded-2xl border border-pink-100 bg-pink-50/60 p-3.5 transition hover:bg-pink-100/60">
                <div class="inline-flex h-7 w-7 items-center justify-center rounded-lg bg-pink-500/20 text-pink-700">
                    <i data-lucide="send" class="h-3.5 w-3.5"></i>
                </div>
                <div>
                    <div class="text-base font-semibold text-slate-900">{{ $keluarCount }}</div>
                    <div class="text-[10px] uppercase tracking-wide text-slate-500">Keluar</div>
                </div>
            </a>

            <a href="{{ route('surat.archive') }}" class="flex flex-col justify-between rounded-2xl border border-pink-100 bg-pink-50/60 p-3.5 transition hover:bg-pink-100/60">
                <div class="inline-flex h-7 w-7 items-center justify-center rounded-lg bg-pink-500/20 text-pink-700">
                    <i data-lucide="archive" class="h-3.5 w-3.5"></i>
                </div>
                <div>
                    <div class="text-base font-semibold text-slate-900">{{ $arsipCount }}</div>
                    <div class="text-[10px] uppercase tracking-wide text-slate-500">Arsip</div>
                </div>
            </a>
        </div>

        <div class="col-span-8 flex flex-col gap-3 rounded-3xl border border-pink-100 bg-white/90 p-4 shadow-sm" style="height: calc(100vh - 200px - 86px - 84px - 8px);">
            <div class="flex items-center justify-between">
                <h2 class="text-sm font-semibold text-slate-900">Surat Masuk Terbaru</h2>
                <a href="{{ route('surat.inbox') }}" class="text-xs font-semibold text-pink-700 hover:text-pink-800">Lihat Semua</a>
            </div>

            @if (!empty($inboxPreview) && $inboxPreview->isNotEmpty())
                <div class="flex-1 overflow-y-auto overflow-x-hidden rounded-2xl border border-pink-100">
                    <table class="w-full text-xs">
                        <thead class="bg-pink-50/60 text-left text-xs font-semibold uppercase tracking-wider text-slate-600">
                            <tr>
                                <th class="px-3 py-2">Tanggal</th>
                                <th class="px-3 py-2">Dari</th>
                                <th class="px-3 py-2">Judul</th>
                                <th class="px-3 py-2 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-pink-100/70 bg-white">
                            @foreach ($inboxPreview as $surat)
                                <tr>
                                    <td class="px-3 py-2 text-slate-600">{{ optional($surat->sent_at)?->timezone(config('app.timezone'))->format('d M Y H:i') ?? '-' }}</td>
                                    <td class="px-3 py-2 text-slate-600">{{ $surat->sender_division }}</td>
                                    <td class="px-3 py-2 font-medium text-slate-800">{{ $surat->judul }}</td>
                                    <td class="px-3 py-2 text-right">
                                        <div class="flex items-center justify-end gap-3">
                                            <a class="text-xs font-semibold text-pink-700 hover:text-pink-800" href="{{ route('surat.show', $surat) }}">Detail</a>
                                            <a class="text-xs font-semibold text-pink-700 hover:text-pink-800" href="{{ route('surat.reply', $surat) }}">Balas</a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="rounded-2xl border border-pink-100 bg-pink-50/30 p-4 text-sm text-slate-500">Belum ada surat masuk.</div>
            @endif
        </div>

        <div class="col-span-4 flex flex-col overflow-hidden rounded-3xl border border-pink-100 bg-white/90 p-4 shadow-sm" style="height: calc(100vh - 200px - 86px - 84px - 8px);">
            <div class="mb-2 flex items-center justify-between">
                <a href="{{ route('dashboard.chart', ['mode' => ($chartMode ?? 'bulanan')]) }}" class="text-sm font-semibold text-slate-900 hover:text-pink-700">Grafik Surat</a>
                <div class="flex h-8 w-8 items-center justify-center rounded-2xl bg-pink-100 text-pink-700">
                    <i data-lucide="chart-column" class="h-4 w-4"></i>
                </div>
            </div>
            <div class="mb-2 flex items-center gap-2 text-[11px] text-pink-100/80">
                <span class="rounded-full border border-pink-200/60 px-2.5 py-0.5 {{ ($chartMode ?? 'bulanan') === 'mingguan' ? 'bg-pink-600 text-white' : 'bg-white/10 text-pink-100/80' }}">Mingguan</span>
                <span class="rounded-full border border-pink-200/60 px-2.5 py-0.5 {{ ($chartMode ?? 'bulanan') === 'bulanan' ? 'bg-pink-600 text-white' : 'bg-white/10 text-pink-100/80' }}">Bulanan</span>
            </div>
            <div class="flex-1 overflow-hidden rounded-2xl border border-pink-100/70 bg-black/20 p-3 cursor-pointer" data-chart-link="{{ route('dashboard.chart', ['mode' => ($chartMode ?? 'bulanan')]) }}">
                <canvas id="suratChart" height="190" style="width: 100%; max-width: 100%;"></canvas>
            </div>
        </div>
    </section>
</div>

<script>
    lucide.createIcons();

    const chartEl = document.getElementById('suratChart');
    const chartBox = document.querySelector('[data-chart-link]');
    if (chartBox) {
        chartBox.addEventListener('click', function () {
            const url = chartBox.getAttribute('data-chart-link');
            if (url) {
                window.location.href = url;
            }
        });
    }

    if (chartEl) {
        const chartLabels = @json($chartLabels ?? []);
        const masukData = @json($chartMasukData ?? []);
        const keluarData = @json($chartKeluarData ?? []);
        const maxValue = Math.max(...masukData, ...keluarData, 1);

        new Chart(chartEl, {
            type: 'line',
            data: {
                labels: chartLabels,
                datasets: [
                    {
                        label: 'Surat Masuk',
                        data: masukData,
                        borderColor: '#16a34a',
                        backgroundColor: 'rgba(22, 163, 74, 0.18)',
                        pointBackgroundColor: '#15803d',
                        pointRadius: 3,
                        tension: 0.35,
                        fill: true,
                    },
                    {
                        label: 'Surat Keluar',
                        data: keluarData,
                        borderColor: '#ef4444',
                        backgroundColor: 'rgba(239, 68, 68, 0.16)',
                        pointBackgroundColor: '#dc2626',
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

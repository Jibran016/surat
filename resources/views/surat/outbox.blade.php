@extends('layouts.app')

@section('title', 'Surat Keluar - Surat Menyurat')

@push('styles')
<style>
    .outbox-wrap {
        border: 1px solid #e5e7eb;
        border-radius: 14px;
        background: #ffffff;
        padding: 1.5rem;
    }

    .outbox-head {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 1rem;
    }

    .outbox-title {
        margin: 0;
        font-size: 2rem;
        line-height: 1.1;
        font-weight: 700;
        color: #0f172a;
    }

    .outbox-subtitle {
        margin-top: 0.3rem;
        color: #64748b;
        font-size: 0.92rem;
    }

    .new-btn {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        border-radius: 10px;
        background: #020617;
        color: #ffffff;
        text-decoration: none;
        font-size: 0.95rem;
        font-weight: 600;
        padding: 0.6rem 1rem;
        border: 1px solid #0f172a;
    }

    .new-btn:hover { background: #0f172a; }

    .stat-grid {
        margin-top: 1.1rem;
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 0.9rem;
    }

    .stat-card {
        border: 1px solid #e5e7eb;
        border-radius: 14px;
        background: #ffffff;
        padding: 1rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 1rem;
    }

    .stat-label {
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        font-size: 0.92rem;
        color: #64748b;
    }

    .stat-value {
        margin-top: 0.35rem;
        font-size: 2rem;
        line-height: 1;
        font-weight: 700;
        color: #0f172a;
    }

    .stat-icon {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }

    .stat-icon.green { background: #dcfce7; color: #16a34a; }
    .stat-icon.blue { background: #dbeafe; color: #2563eb; }

    .toolbar {
        margin-top: 1rem;
        border: 1px solid #e5e7eb;
        border-radius: 14px;
        background: #f8fafc;
        padding: 0.8rem;
        display: flex;
        gap: 0.7rem;
    }

    .toolbar-input,
    .toolbar-select,
    .toolbar-btn {
        height: 44px;
        border-radius: 10px;
        border: 1px solid #d1d5db;
        background: #fff;
        font-size: 0.92rem;
        padding: 0 0.8rem;
    }

    .toolbar-input { flex: 1; }
    .toolbar-select { min-width: 200px; }
    .toolbar-btn {
        background: #2563eb;
        border-color: #2563eb;
        color: #fff;
        font-weight: 600;
    }

    .mail-list {
        margin-top: 1rem;
        display: flex;
        flex-direction: column;
        gap: 0.75rem;
    }

    .mail-card {
        border: 1px solid #e5e7eb;
        border-radius: 14px;
        background: #fff;
        padding: 1rem;
        display: flex;
        justify-content: space-between;
        gap: 1rem;
    }

    .mail-card h2 {
        margin: 0;
        font-size: 1.05rem;
        font-weight: 700;
        color: #0f172a;
    }

    .mail-meta {
        margin-top: 0.45rem;
        display: flex;
        flex-wrap: wrap;
        gap: 0.7rem;
        font-size: 0.86rem;
        color: #64748b;
    }

    .mail-meta span {
        display: inline-flex;
        align-items: center;
        gap: 0.3rem;
    }

    .mail-tags {
        margin-top: 0.6rem;
        display: flex;
        flex-wrap: wrap;
        gap: 0.45rem;
    }

    .tag {
        display: inline-flex;
        align-items: center;
        gap: 0.25rem;
        border: 1px solid #e5e7eb;
        border-radius: 999px;
        padding: 0.15rem 0.55rem;
        font-size: 0.74rem;
        font-weight: 700;
        color: #334155;
        background: #fff;
    }

    .tag.warn { background: #ffedd5; color: #c2410c; border-color: #fed7aa; }
    .tag.normal { background: #e2e8f0; color: #475569; border-color: #cbd5e1; }
    .tag.sent { background: #dcfce7; color: #15803d; border-color: #bbf7d0; }

    .mail-link {
        display: inline-flex;
        align-items: center;
        gap: 0.25rem;
        color: #0f172a;
        text-decoration: none;
        font-weight: 700;
        white-space: nowrap;
    }

    .mail-link:hover { color: #1d4ed8; }

    @media (max-width: 980px) {
        .stat-grid { grid-template-columns: 1fr; }
        .toolbar { flex-direction: column; }
        .toolbar-select { min-width: 0; }
        .mail-card { flex-direction: column; }
    }
</style>
@endpush

@section('content')
<div class="outbox-wrap">
    <div class="outbox-head">
        <div>
            <h1 class="outbox-title inline-flex items-center gap-2">
                <svg viewBox="0 0 24 24" width="30" height="30" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 2 11 13"></path><path d="m22 2-7 20-4-9-9-4Z"></path></svg>
                Surat Keluar
            </h1>
            <p class="outbox-subtitle">Riwayat surat yang telah Anda kirim</p>
        </div>
        <a href="{{ route('surat.create') }}" class="new-btn">
            <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M22 2 11 13"></path>
                <path d="m22 2-7 20-4-9-9-4Z"></path>
            </svg>
            Kirim Surat Baru
        </a>
    </div>

    <div class="stat-grid">
        <div class="stat-card">
            <div>
                <div class="stat-label">
                    <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 3h18v18H3z"></path><path d="M9 9h6v6H9z"></path></svg>
                    Total Surat Terkirim
                </div>
                <div class="stat-value">{{ $totalSentCount }}</div>
            </div>
            <span class="stat-icon green">
                <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M22 2 11 13"></path>
                    <path d="m22 2-7 20-4-9-9-4Z"></path>
                </svg>
            </span>
        </div>

        <div class="stat-card">
            <div>
                <div class="stat-label">
                    <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2"></rect><path d="M16 2v4M8 2v4M3 10h18"></path></svg>
                    Bulan Ini
                </div>
                <div class="stat-value">{{ $monthSentCount }}</div>
            </div>
            <span class="stat-icon blue">
                <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2">
                    <rect x="3" y="4" width="18" height="18" rx="2"></rect>
                    <path d="M16 2v4M8 2v4M3 10h18"></path>
                </svg>
            </span>
        </div>
    </div>

    <form method="GET" action="{{ route('surat.outbox') }}" class="toolbar">
        <input class="toolbar-input" type="text" name="q" value="{{ $search }}" placeholder="Cari surat, penerima, atau divisi...">
        <select class="toolbar-select" name="jenis">
            <option value="">Semua Jenis</option>
            @foreach ($jenisOptions as $jenis)
                <option value="{{ $jenis }}" @selected($jenisFilter === $jenis)>{{ $jenis }}</option>
            @endforeach
        </select>
    </form>

    <div class="mail-list">
        @forelse ($outboxGroups as $group)
            @php
                $surat = $group->surat;
                $jenisLower = strtolower((string) $surat->jenis);
                $isNormal = str_contains($jenisLower, 'memo') || str_contains($jenisLower, 'laporan');
                $priorityLabel = $isNormal ? 'Normal' : 'Penting';
                $priorityClass = $isNormal ? 'normal' : 'warn';
            @endphp

            <article class="mail-card">
                <div>
                    <h2>{{ $surat->judul }}</h2>
                    <div class="mail-meta">
                        <span>
                            <svg viewBox="0 0 24 24" width="13" height="13" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                            Kepada: {{ $group->recipient_summary }}
                        </span>
                        <span>
                            <svg viewBox="0 0 24 24" width="13" height="13" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2"></rect><path d="M16 2v4M8 2v4M3 10h18"></path></svg>
                            {{ optional($surat->sent_at)?->timezone(config('app.timezone'))->format('d/m/Y') ?? '-' }} - {{ optional($surat->sent_at)?->timezone(config('app.timezone'))->format('H:i') ?? '-' }}
                        </span>
                    </div>
                    <div class="mail-tags">
                        <span class="tag">
                            <svg viewBox="0 0 24 24" width="12" height="12" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 13V7a2 2 0 0 0-2-2h-6"></path><path d="M14 3v4h4"></path><path d="M14 17H6a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h6"></path></svg>
                            {{ $surat->jenis }}
                        </span>
                        <span class="tag sent">
                            <svg viewBox="0 0 24 24" width="12" height="12" fill="none" stroke="currentColor" stroke-width="2"><path d="m20 6-11 11-5-5"></path></svg>
                            Terkirim
                        </span>
                    </div>
                </div>
                <div>
                    <a class="mail-link" href="{{ route('surat.show', $surat) }}">Lihat <svg viewBox="0 0 24 24" width="12" height="12" fill="none" stroke="currentColor" stroke-width="2"><path d="m9 18 6-6-6-6"></path></svg></a>
                </div>
            </article>
        @empty
            <div class="rounded-xl border border-dashed border-slate-300 bg-slate-50 px-4 py-6 text-sm text-slate-500">
                Tidak ada data surat keluar sesuai filter.
            </div>
        @endforelse
    </div>
</div>
@endsection


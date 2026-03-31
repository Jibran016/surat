@extends('layouts.app')

@section('title', 'Surat Masuk - Surat Menyurat')

@push('styles')
<style>
    .panel {
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 14px;
    }

    .stat-grid {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 0.85rem;
        margin-top: 1rem;
    }

    .stat-box {
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        background: #fff;
        padding: 0.9rem 1rem;
    }

    .stat-label {
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        font-size: 0.9rem;
        color: #64748b;
    }

    .stat-value {
        margin-top: 0.2rem;
        font-size: 2rem;
        line-height: 1;
        font-weight: 700;
        color: #0f172a;
    }

    .inbox-toolbar {
        margin-top: 1rem;
        padding: 0.8rem;
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        background: #f8fafc;
        display: flex;
        gap: 0.65rem;
    }

    .inbox-toolbar input,
    .inbox-toolbar select,
    .inbox-toolbar button {
        height: 42px;
        border-radius: 10px;
        border: 1px solid #d1d5db;
        background: #fff;
        padding: 0 0.8rem;
        font-size: 0.92rem;
    }

    .inbox-toolbar input {
        flex: 1;
    }

    .inbox-toolbar button {
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

    .mail-item {
        border: 1px solid #dbeafe;
        border-radius: 14px;
        background: #fff;
        padding: 1rem 1.1rem;
        display: flex;
        justify-content: space-between;
        gap: 1rem;
    }

    .mail-item.unread {
        background: #eff6ff;
    }

    .mail-title {
        margin: 0;
        font-size: 1.6rem;
        font-weight: 700;
        color: #0f172a;
    }

    .mail-meta {
        margin-top: 0.35rem;
        font-size: 0.85rem;
        color: #64748b;
        display: flex;
        flex-wrap: wrap;
        gap: 0.6rem;
    }

    .mail-meta span {
        display: inline-flex;
        align-items: center;
        gap: 0.25rem;
    }

    .mail-tags {
        margin-top: 0.55rem;
        display: flex;
        flex-wrap: wrap;
        gap: 0.45rem;
    }

    .tag {
        display: inline-flex;
        align-items: center;
        gap: 0.25rem;
        border-radius: 999px;
        border: 1px solid #e5e7eb;
        background: #fff;
        padding: 0.18rem 0.55rem;
        font-size: 0.73rem;
        font-weight: 700;
        color: #334155;
    }

    .tag.level-high { background: #fee2e2; color: #b91c1c; border-color: #fecaca; }
    .tag.level-mid { background: #ffedd5; color: #c2410c; border-color: #fed7aa; }
    .tag.level-low { background: #dbeafe; color: #1d4ed8; border-color: #bfdbfe; }

    .mail-link {
        display: inline-flex;
        align-items: center;
        gap: 0.25rem;
        color: #0f172a;
        font-weight: 700;
        text-decoration: none;
        white-space: nowrap;
    }

    .mail-link:hover {
        color: #1d4ed8;
    }

    @media (max-width: 980px) {
        .stat-grid { grid-template-columns: 1fr; }
        .inbox-toolbar { flex-direction: column; }
        .mail-item { flex-direction: column; }
    }
</style>
@endpush

@section('content')
<div class="panel p-6">
    <h1 class="mail-title inline-flex items-center gap-2">
        <svg viewBox="0 0 24 24" width="30" height="30" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
            <path d="M22 12h-6l-2 3h-4l-2-3H2"></path>
            <path d="M5.5 5h13a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2h-13a2 2 0 0 1-2-2V7a2 2 0 0 1 2-2Z"></path>
        </svg>
        Surat Masuk
    </h1>
    <p class="mt-1 text-sm text-slate-500">Daftar surat yang masuk ke divisi Anda</p>

    <div class="stat-grid">
        <div class="stat-box">
            <div class="stat-label">
                <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 3h18v18H3z"></path><path d="M9 9h6v6H9z"></path></svg>
                Total Surat Masuk
            </div>
            <div class="stat-value">{{ $totalCount }}</div>
        </div>
        <div class="stat-box">
            <div class="stat-label">
                <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><path d="M12 8v5"></path><path d="M12 16h.01"></path></svg>
                Belum Dibaca
            </div>
            <div class="stat-value">{{ $unreadCount }}</div>
        </div>
        <div class="stat-box">
            <div class="stat-label">
                <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2"><path d="m20 6-11 11-5-5"></path></svg>
                Sudah Dibaca
            </div>
            <div class="stat-value">{{ $readCount }}</div>
        </div>
    </div>

    <form method="GET" action="{{ route('surat.inbox') }}" class="inbox-toolbar">
        <input type="text" name="q" value="{{ $search }}" placeholder="Cari surat, pengirim, atau jenis...">
        <select name="status">
            <option value="all" @selected($statusFilter === 'all')>Semua Status</option>
            <option value="unread" @selected($statusFilter === 'unread')>Belum Dibaca</option>
            <option value="read" @selected($statusFilter === 'read')>Sudah Dibaca</option>
        </select>

    </form>

    <div class="mail-list">
        @forelse ($surats as $surat)
            @php
                $isUnread = $surat->read_at === null && $surat->status === 'Terkirim';
                $ageInHours = optional($surat->sent_at)->diffInHours(now(config('app.timezone'))) ?? 0;
                $levelText = $isUnread ? ($ageInHours >= 24 ? 'Mendesak' : 'Penting') : 'Normal';
                $levelClass = $isUnread ? ($ageInHours >= 24 ? 'level-high' : 'level-mid') : 'level-low';
                $senderName = $surat->sender?->username ?? $surat->sender_division;
            @endphp

            <article class="mail-item {{ $isUnread ? 'unread' : '' }}">
                <div>
                    <h2 class="text-xl font-semibold text-slate-900">{{ $surat->judul }}</h2>
                    <div class="mail-meta">
                        <span>
                            <svg viewBox="0 0 24 24" width="13" height="13" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                            {{ $senderName }}
                        </span>
                        <span>
                            <svg viewBox="0 0 24 24" width="13" height="13" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 21h18"></path><path d="M5 21V7l7-4 7 4v14"></path></svg>
                            {{ $surat->sender_division }}
                        </span>
                        <span>
                            <svg viewBox="0 0 24 24" width="13" height="13" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2"></rect><path d="M16 2v4M8 2v4M3 10h18"></path></svg>
                            {{ optional($surat->sent_at)?->timezone(config('app.timezone'))->format('d/m/Y - H:i') ?? '-' }}
                        </span>
                    </div>
                    <div class="mail-tags">
                        <span class="tag">
                            <svg viewBox="0 0 24 24" width="12" height="12" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 13V7a2 2 0 0 0-2-2h-6"></path><path d="M14 3v4h4"></path><path d="M14 17H6a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h6"></path></svg>
                            {{ $surat->jenis }}
                        </span>
                  
                    </div>
                </div>
                <div>
                    <a href="{{ route('surat.show', $surat) }}" class="mail-link">
                        Lihat
                        <svg viewBox="0 0 24 24" width="12" height="12" fill="none" stroke="currentColor" stroke-width="2"><path d="m9 18 6-6-6-6"></path></svg>
                    </a>
                </div>
            </article>
        @empty
            <div class="rounded-xl border border-dashed border-slate-300 bg-slate-50 px-4 py-6 text-sm text-slate-500">
                Tidak ada surat pada filter ini.
            </div>
        @endforelse
    </div>

    @if ($surats->hasPages())
        <div class="mt-4">{{ $surats->links() }}</div>
    @endif
</div>
@endsection


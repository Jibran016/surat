@extends('layouts.app')

@section('title', 'Detail Surat - SURATIN')

@push('styles')
    <style>
        .attachment-thumb {
            max-height: 220px;
            width: auto;
            max-width: 100%;
            object-fit: contain;
            border-radius: 1rem;
            border: 1px solid rgba(15, 23, 42, 0.12);
            background: #fff;
        }
    </style>
@endpush

@section('content')
<div class="flex min-h-[calc(100vh-200px)] flex-col gap-4">
    <div class="rounded-3xl border border-pink-100 bg-white/90 p-6 shadow-[0_30px_60px_-40px_rgba(236,72,153,0.45)]">
        <div class="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
            <div>
                <h1 class="text-xl font-semibold text-slate-900">Detail Surat</h1>
                <p class="mt-1 text-sm text-slate-500">Informasi lengkap surat internal.</p>
            </div>

            <div class="flex flex-wrap items-center gap-2">
                @if ($isRecipient)
                    <a class="inline-flex items-center gap-2 rounded-full bg-pink-600 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-pink-700" href="{{ route('surat.reply', $surat) }}">Balas</a>
                @endif
                @if ($isRecipient)
                    <form method="POST" action="{{ route('surat.done', $surat) }}">
                        @csrf
                        <button class="inline-flex items-center gap-2 rounded-full border border-pink-200 bg-white px-4 py-2 text-sm font-semibold text-pink-700 shadow-sm transition hover:bg-pink-50" type="submit">Selesai</button>
                    </form>
                @endif
            </div>
        </div>

        @php
            $statusClass = match (strtolower($surat->status)) {
                'draft' => 'bg-slate-100 text-slate-700',
                'sent' => 'bg-sky-100 text-sky-700',
                'done' => 'bg-emerald-100 text-emerald-700',
                'archived' => 'bg-amber-100 text-amber-700',
                default => 'bg-pink-100 text-pink-700',
            };
        @endphp

        <div class="mt-4 flex flex-wrap items-center gap-3">
            <span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold {{ $statusClass }}">{{ $surat->status }}</span>
            <span class="text-sm text-slate-500">{{ optional($surat->sent_at)?->timezone(config('app.timezone'))->format('d M Y H:i') ?? '-' }}</span>
        </div>
    </div>

    <article class="flex-1 rounded-3xl border border-pink-100 bg-white p-6 shadow-sm">
        @php
            $documentTitle = filled($surat->jenis) ? strtoupper($surat->jenis) : 'SURAT INTERNAL';
            $lampiranUrl = $surat->lampiran_path ? route('surat.attachment', $surat) : null;
            $lampiranName = strtolower((string) ($surat->lampiran_name ?? ''));
            $lampiranExt = pathinfo($lampiranName, PATHINFO_EXTENSION);
            $isImageAttachment = in_array($lampiranExt, ['jpg', 'jpeg', 'png', 'gif', 'webp'], true);
            $isPdfAttachment = $lampiranExt === 'pdf';
        @endphp

        <div class="mt-4 grid gap-3 text-sm text-slate-600 sm:grid-cols-2">
            <div><span class="font-semibold text-slate-800">Nomor:</span> {{ $surat->nomor_surat ?? '-' }}</div>
            <div><span class="font-semibold text-slate-800">Tanggal:</span> {{ optional($surat->sent_at)?->timezone(config('app.timezone'))->format('d M Y H:i') ?? '-' }}</div>
            <div><span class="font-semibold text-slate-800">Dari:</span> {{ $surat->sender_division }}</div>
            <div><span class="font-semibold text-slate-800">Kepada:</span> {{ $surat->recipient_division }}</div>
            <div class="sm:col-span-2"><span class="font-semibold text-slate-800">Jenis Surat:</span> {{ $surat->jenis }}</div>
        </div>

        <h2 class="mt-6 text-xl font-semibold uppercase tracking-wide text-slate-900">{{ $documentTitle }}</h2>
        <h3 class="mt-2 text-lg font-semibold text-slate-900">{{ $surat->judul }}</h3>
        <div class="prose prose-slate mt-3 max-w-none text-slate-700">{!! $surat->isi !!}</div>

        @if ($surat->lampiran_path)
            <div class="mt-6 rounded-2xl border border-pink-100 bg-pink-50/60 p-4 text-sm text-slate-700">
                <div class="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
                    <div class="min-w-0 flex-1">
                        <div class="font-semibold text-slate-800">Lampiran</div>
                        <div class="mt-1 break-words text-sm text-slate-600">{{ $surat->lampiran_name }}</div>

                        <div class="mt-4">
                            @if ($isImageAttachment)
                                <button type="button" class="inline-block cursor-zoom-in" data-preview-open data-preview-type="image" data-preview-src="{{ $lampiranUrl }}" data-preview-title="{{ $surat->lampiran_name }}">
                                    <img src="{{ $lampiranUrl }}" alt="{{ $surat->lampiran_name }}" class="attachment-thumb">
                                </button>
                            @elseif ($isPdfAttachment)
                                <button type="button" class="block w-full max-w-[220px] cursor-zoom-in rounded-2xl border border-pink-200 bg-white p-3 text-left shadow-sm" data-preview-open data-preview-type="pdf" data-preview-src="{{ $lampiranUrl }}" data-preview-title="{{ $surat->lampiran_name }}">
                                    <div class="text-xs font-semibold uppercase tracking-wider text-pink-700">Preview PDF</div>
                                    <div class="mt-2 line-clamp-2 text-sm font-medium text-slate-800">{{ $surat->lampiran_name }}</div>
                                    <div class="mt-2 text-xs text-slate-500">Klik untuk lihat ukuran penuh</div>
                                </button>
                            @else
                                <div class="max-w-[240px] rounded-2xl border border-pink-200 bg-white p-3 shadow-sm">
                                    <div class="text-xs font-semibold uppercase tracking-wider text-pink-700">File Lampiran</div>
                                    <div class="mt-2 break-words text-sm font-medium text-slate-800">{{ $surat->lampiran_name }}</div>
                                    <div class="mt-2 text-xs text-slate-500">Preview tidak tersedia untuk format ini.</div>
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="flex flex-wrap items-center gap-2">
                        <a class="inline-flex items-center gap-1.5 rounded-full border border-pink-200 bg-white px-3 py-1 text-xs font-semibold text-pink-700 shadow-sm transition hover:bg-pink-50" href="{{ route('surat.attachment', $surat) }}" target="_blank" rel="noopener">
                            Buka
                        </a>
                        <a class="inline-flex items-center gap-1.5 rounded-full border border-pink-200 bg-white px-3 py-1 text-xs font-semibold text-pink-700 shadow-sm transition hover:bg-pink-50" href="{{ route('surat.attachment', ['surat' => $surat, 'download' => 1]) }}">
                            <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                                <path d="M12 3v12"></path>
                                <path d="m7 10 5 5 5-5"></path>
                                <path d="M5 21h14"></path>
                            </svg>
                            Download
                        </a>
                    </div>
                </div>
            </div>
        @endif
    </article>
</div>

@if ($surat->lampiran_path)
    <div id="attachmentPreviewModal" class="fixed inset-0 z-[70] hidden items-center justify-center bg-slate-950/70 p-4">
        <div class="relative w-full max-w-5xl rounded-3xl bg-white p-4 shadow-2xl">
            <div class="mb-3 flex items-center justify-between gap-3">
                <div id="attachmentPreviewTitle" class="min-w-0 truncate text-sm font-semibold text-slate-900"></div>
                <button type="button" id="attachmentPreviewClose" class="inline-flex h-10 w-10 items-center justify-center rounded-full border border-pink-200 bg-white text-pink-700 transition hover:bg-pink-50" aria-label="Tutup preview">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                        <path d="M18 6 6 18"></path>
                        <path d="m6 6 12 12"></path>
                    </svg>
                </button>
            </div>
            <div id="attachmentPreviewBody" class="max-h-[78vh] overflow-auto rounded-2xl bg-slate-100 p-3"></div>
        </div>
    </div>
@endif
@endsection

@push('scripts')
    @if ($surat->lampiran_path)
        <script>
            (function () {
                const modal = document.getElementById('attachmentPreviewModal');
                const body = document.getElementById('attachmentPreviewBody');
                const title = document.getElementById('attachmentPreviewTitle');
                const closeButton = document.getElementById('attachmentPreviewClose');
                const openButtons = document.querySelectorAll('[data-preview-open]');

                if (!modal || !body || !title || !closeButton || openButtons.length === 0) {
                    return;
                }

                function closeModal() {
                    modal.classList.add('hidden');
                    modal.classList.remove('flex');
                    body.innerHTML = '';
                    title.textContent = '';
                }

                function openModal(type, src, fileTitle) {
                    title.textContent = fileTitle || 'Preview Lampiran';

                    if (type === 'image') {
                        body.innerHTML = `<img src="${src}" alt="${fileTitle}" class="mx-auto max-h-[72vh] w-auto max-w-full rounded-2xl">`;
                    } else if (type === 'pdf') {
                        body.innerHTML = `<iframe src="${src}" class="h-[72vh] w-full rounded-2xl bg-white" title="${fileTitle}"></iframe>`;
                    }

                    modal.classList.remove('hidden');
                    modal.classList.add('flex');
                }

                openButtons.forEach(function (button) {
                    button.addEventListener('click', function () {
                        openModal(
                            button.getAttribute('data-preview-type'),
                            button.getAttribute('data-preview-src'),
                            button.getAttribute('data-preview-title')
                        );
                    });
                });

                closeButton.addEventListener('click', closeModal);

                modal.addEventListener('click', function (event) {
                    if (event.target === modal) {
                        closeModal();
                    }
                });

                document.addEventListener('keydown', function (event) {
                    if (event.key === 'Escape' && !modal.classList.contains('hidden')) {
                        closeModal();
                    }
                });
            })();
        </script>
    @endif
@endpush

@extends('layouts.app')

@section('title', 'Detail Surat - Surat Menyurat')

@section('content')
<div class="mx-auto max-w-5xl space-y-4">
    <div class="rounded-2xl border border-slate-200 bg-white p-6">
        <div class="flex flex-wrap items-start justify-between gap-3">
            <div>
                <h1 class="inline-flex items-center gap-2 text-3xl font-bold text-slate-900">
                    <svg viewBox="0 0 24 24" width="28" height="28" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><path d="M14 2v6h6"></path></svg>
                    {{ $surat->judul }}
                </h1>
                <p class="mt-1 inline-flex items-center gap-1.5 text-sm text-slate-500"><svg viewBox="0 0 24 24" width="13" height="13" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2"></rect><path d="M16 2v4M8 2v4M3 10h18"></path></svg>{{ optional($surat->sent_at)?->timezone(config('app.timezone'))->format('d/m/Y H:i') ?? '-' }}</p>
            </div>
            <div class="flex flex-wrap items-center gap-2">
                @if ($isRecipient)

                    <form method="POST" action="{{ route('surat.done', $surat) }}">
                        @csrf
                        <button type="submit" class="inline-flex items-center gap-1.5 rounded-full bg-blue-600 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-700"><svg viewBox="0 0 24 24" width="13" height="13" fill="none" stroke="currentColor" stroke-width="2"><path d="m20 6-11 11-5-5"></path></svg>Selesai</button>
                    </form>
                @endif
                @if ($surat->archived_at)
                    <form method="POST" action="{{ route('surat.archive.remove', $surat) }}">
                        @csrf
                        <button type="submit" class="inline-flex items-center gap-1.5 rounded-full border border-slate-300 px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-100"><svg viewBox="0 0 24 24" width="13" height="13" fill="none" stroke="currentColor" stroke-width="2"><path d="m3 12 4-4v3h7a4 4 0 1 1 0 8h-1"></path></svg>Buka Arsip</button>
                    </form>
                @else
                    <form method="POST" action="{{ route('surat.archive.store', $surat) }}">
                        @csrf
                        <button type="submit" class="inline-flex items-center gap-1.5 rounded-full border border-slate-300 px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-100"><svg viewBox="0 0 24 24" width="13" height="13" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 8v13H3V8"></path><path d="M1 3h22v5H1z"></path><path d="M10 12h4"></path></svg>Arsipkan</button>
                    </form>
                @endif
            </div>
        </div>

        <div class="mt-5 grid gap-3 text-sm text-slate-600 sm:grid-cols-2">
            <div class="inline-flex items-center gap-1.5"><svg viewBox="0 0 24 24" width="13" height="13" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 3h6"></path><path d="M10 7h4"></path><rect x="4" y="3" width="16" height="18" rx="2"></rect></svg><span class="font-semibold text-slate-800">Nomor:</span> {{ $surat->nomor_surat ?? '-' }}</div>
            <div class="inline-flex items-center gap-1.5"><svg viewBox="0 0 24 24" width="13" height="13" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle></svg><span class="font-semibold text-slate-800">Status:</span> {{ $surat->status }}</div>
            <div class="inline-flex items-center gap-1.5"><svg viewBox="0 0 24 24" width="13" height="13" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 21h18"></path><path d="M5 21V7l7-4 7 4v14"></path></svg><span class="font-semibold text-slate-800">Dari:</span> {{ $surat->sender_division }}</div>
            <div class="inline-flex items-center gap-1.5"><svg viewBox="0 0 24 24" width="13" height="13" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg><span class="font-semibold text-slate-800">Kepada:</span> {{ $surat->recipient_division }}</div>
            <div class="sm:col-span-2 inline-flex items-center gap-1.5"><svg viewBox="0 0 24 24" width="13" height="13" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 13V7a2 2 0 0 0-2-2h-6"></path><path d="M14 3v4h4"></path><path d="M14 17H6a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h6"></path></svg><span class="font-semibold text-slate-800">Jenis:</span> {{ $surat->jenis }}</div>
        </div>
    </div>

    <article class="rounded-2xl border border-slate-200 bg-white p-6">
        <div class="prose max-w-none text-slate-700">{!! $surat->isi !!}</div>

        @if ($surat->lampiran_path)
            @php
                $lampiranName = strtolower((string) ($surat->lampiran_name ?? ''));
                $isPdfLampiran = str_ends_with($lampiranName, '.pdf');
            @endphp
            <div class="mt-6 rounded-xl border border-slate-200 bg-slate-50 p-4">
                <div class="inline-flex items-center gap-1.5 text-sm font-semibold text-slate-800"><svg viewBox="0 0 24 24" width="13" height="13" fill="none" stroke="currentColor" stroke-width="2"><path d="m21.44 11.05-9.19 9.19a6 6 0 0 1-8.49-8.49l9.2-9.19a4 4 0 1 1 5.65 5.66l-9.2 9.19a2 2 0 0 1-2.82-2.83l8.48-8.48"></path></svg>Surat</div>
                <div class="mt-1 text-sm text-slate-600">{{ $surat->lampiran_name }}</div>

                @if ($isPdfLampiran)
                    <div class="mt-3 overflow-hidden rounded-lg border border-slate-200 bg-white">
                        <iframe
                            src="{{ route('surat.attachment', $surat) }}#view=FitH"
                            class="h-[520px] w-full"
                            title="Preview Lampiran Surat">
                        </iframe>
                    </div>
                @else
                    <div class="mt-3 rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs text-slate-500">
                        Preview langsung saat ini hanya tersedia untuk file PDF.
                    </div>
                @endif

                <div class="mt-3 flex flex-wrap items-center gap-2">
                    <a href="{{ route('surat.attachment', ['surat' => $surat, 'download' => 1]) }}" class="inline-flex items-center gap-1 rounded-full border border-slate-300 px-3 py-1.5 text-xs font-semibold text-slate-700 hover:bg-white"><svg viewBox="0 0 24 24" width="12" height="12" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 3v12"></path><path d="m7 10 5 5 5-5"></path><path d="M5 21h14"></path></svg>Download</a>
                </div>
            </div>
        @endif
    </article>
</div>
@endsection

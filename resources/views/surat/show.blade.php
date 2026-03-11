@extends('layouts.app')

@section('title', 'Detail Surat - SURATIN')

@section('content')
<div class="flex min-h-[calc(100vh-200px)] flex-col gap-4">
    <div class="rounded-3xl border border-pink-100 bg-white/90 p-6 shadow-[0_30px_60px_-40px_rgba(236,72,153,0.45)]">
        <div class="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
            <div>
                <h1 class="text-xl font-semibold text-slate-900">Detail Surat</h1>
                <p class="mt-1 text-sm text-slate-500">Informasi lengkap surat internal.</p>
            </div>

            <div class="flex flex-wrap items-center gap-2">
                <a class="inline-flex items-center gap-2 rounded-full border border-pink-200 bg-white px-4 py-2 text-sm font-semibold text-pink-700 shadow-sm transition hover:bg-pink-50" href="{{ route('surat.pdf', $surat) }}" target="_blank" rel="noopener">Lihat PDF</a>
                @if ($isRecipient)
                    <a class="inline-flex items-center gap-2 rounded-full bg-pink-600 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-pink-700" href="{{ route('surat.reply', $surat) }}">Balas</a>
                @endif
                @if ($isRecipient)
                    <form method="POST" action="{{ route('surat.done', $surat) }}">
                        @csrf
                        <button class="inline-flex items-center gap-2 rounded-full border border-pink-200 bg-white px-4 py-2 text-sm font-semibold text-pink-700 shadow-sm transition hover:bg-pink-50" type="submit">Selesai</button>
                    </form>
                @endif
                <form method="POST" action="{{ route('surat.archive.store', $surat) }}">
                    @csrf
                    <button class="inline-flex items-center gap-2 rounded-full border border-pink-200 bg-white px-4 py-2 text-sm font-semibold text-pink-700 shadow-sm transition hover:bg-pink-50" type="submit">Arsipkan</button>
                </form>
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
        <div class="border-b border-pink-100 pb-3">
            <div class="text-sm font-semibold uppercase tracking-[0.2em] text-pink-600">PT Internal Maju</div>
            <div class="mt-1 text-xl font-semibold text-slate-900">Surat Internal</div>
        </div>

        <div class="mt-4 grid gap-3 text-sm text-slate-600 sm:grid-cols-2">
            <div><span class="font-semibold text-slate-800">Nomor:</span> {{ $surat->nomor_surat ?? '-' }}</div>
            <div><span class="font-semibold text-slate-800">Tanggal:</span> {{ optional($surat->sent_at)?->timezone(config('app.timezone'))->format('d M Y H:i') ?? '-' }}</div>
            <div><span class="font-semibold text-slate-800">Dari:</span> {{ $surat->sender_division }}</div>
            <div><span class="font-semibold text-slate-800">Kepada:</span> {{ $surat->recipient_division }}</div>
            <div class="sm:col-span-2"><span class="font-semibold text-slate-800">Jenis Surat:</span> {{ $surat->jenis }}</div>
        </div>

        <h2 class="mt-6 text-xl font-semibold text-slate-900">{{ $surat->judul }}</h2>
        <div class="prose prose-slate mt-3 max-w-none text-slate-700">{!! $surat->isi !!}</div>

        @if ($surat->lampiran_path)
            <div class="mt-6 rounded-2xl border border-pink-100 bg-pink-50/60 p-4 text-sm text-slate-700">
                <span class="font-semibold text-slate-800">Lampiran:</span>
                <a class="ml-1 font-semibold text-pink-700 hover:text-pink-800" href="{{ route('surat.attachment', $surat) }}" target="_blank" rel="noopener">{{ $surat->lampiran_name }}</a>
                <a class="ml-4 inline-flex items-center gap-1.5 rounded-full border border-pink-200 bg-white px-3 py-1 text-xs font-semibold text-pink-700 shadow-sm transition hover:bg-pink-50" href="{{ route('surat.attachment', ['surat' => $surat, 'download' => 1]) }}">
                    <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                        <path d="M12 3v12"></path>
                        <path d="m7 10 5 5 5-5"></path>
                        <path d="M5 21h14"></path>
                    </svg>
                    Download
                </a>
            </div>
        @endif
    </article>
</div>
@endsection

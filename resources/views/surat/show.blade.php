@extends('layouts.app')

@section('title', 'Detail Surat')

@section('content')
<div class="card">
    <div class="card-header">
        <h1>Detail Surat</h1>
        <div class="actions">
            <a class="btn btn-ghost" href="{{ route('surat.pdf', $surat) }}">Download PDF</a>
            @if ($isRecipient)
                <a class="btn" href="{{ route('surat.reply', $surat) }}">Balas</a>
            @endif
            @if ($isRecipient)
                <form method="POST" action="{{ route('surat.done', $surat) }}">
                    @csrf
                    <button class="btn btn-ghost" type="submit">Selesai</button>
                </form>
            @endif
            <form method="POST" action="{{ route('surat.archive.store', $surat) }}">
                @csrf
                <button class="btn btn-ghost" type="submit">Arsipkan</button>
            </form>
        </div>
    </div>

    <div class="status-line">
        <span class="badge badge-{{ strtolower($surat->status) }}">{{ $surat->status }}</span>
        <div class="muted">{{ optional($surat->sent_at)->format('d F Y') ?? '-' }}</div>
    </div>

    <div class="document">
        <div class="doc-header">
            <div class="doc-brand">PT INTERNAL MAJU</div>
            <div class="doc-title">SURAT INTERNAL</div>
        </div>
        <div class="doc-meta">
            <div><strong>Nomor</strong>: {{ $surat->nomor_surat ?? '-' }}</div>
            <div><strong>Tanggal</strong>: {{ optional($surat->sent_at)->format('d F Y') ?? '-' }}</div>
            <div><strong>Dari</strong>: {{ $surat->sender_division }}</div>
            <div><strong>Kepada</strong>: {{ $surat->recipient_division }}</div>
            <div><strong>Jenis Surat</strong>: {{ $surat->jenis }}</div>
        </div>
        <h2 class="doc-subject">{{ $surat->judul }}</h2>
        <div class="doc-body">{!! $surat->isi !!}</div>

        @if ($surat->lampiran_path)
            <div class="doc-attachment">
                <strong>Lampiran</strong>:
                <a class="link" href="{{ asset('storage/' . $surat->lampiran_path) }}" target="_blank">{{ $surat->lampiran_name }}</a>
            </div>
        @endif
    </div>
</div>
@endsection

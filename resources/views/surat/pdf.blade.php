<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <style>
        body {
            font-family: "DejaVu Sans", sans-serif;
            font-size: 12px;
            color: #111827;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #111827;
            padding-bottom: 10px;
            margin-bottom: 16px;
        }
        .header-title {
            font-size: 16px;
            font-weight: bold;
            letter-spacing: 1px;
        }
        .meta {
            margin-bottom: 16px;
        }
        .meta div {
            margin-bottom: 4px;
        }
        .subject {
            font-weight: bold;
            font-size: 14px;
            margin-bottom: 12px;
        }
        .body {
            line-height: 1.6;
        }
        .attachment {
            margin-top: 16px;
            border-top: 1px dashed #94a3b8;
            padding-top: 10px;
        }
    </style>
</head>
<body>
    <div class="header">
        <div>PT INTERNAL MAJU</div>
        <div class="header-title">SURAT INTERNAL</div>
    </div>

    <div class="meta">
        <div><strong>Nomor</strong>: {{ $surat->nomor_surat ?? '-' }}</div>
        <div><strong>Tanggal</strong>: {{ optional($surat->sent_at)->format('d F Y') ?? '-' }}</div>
        <div><strong>Dari</strong>: {{ $surat->sender_division }}</div>
        <div><strong>Kepada</strong>: {{ $surat->recipient_division }}</div>
        <div><strong>Jenis Surat</strong>: {{ $surat->jenis }}</div>
    </div>

    <div class="subject">{{ $surat->judul }}</div>
    <div class="body">{!! $surat->isi !!}</div>

    @if ($surat->lampiran_name)
        <div class="attachment">
            <strong>Lampiran</strong>: {{ $surat->lampiran_name }}
        </div>
    @endif
</body>
</html>

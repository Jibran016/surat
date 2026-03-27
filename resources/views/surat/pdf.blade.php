<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <style>
        @page {
            margin: 28mm 20mm 24mm 20mm;
        }

        body {
            font-family: "Times New Roman", Times, serif;
            font-size: 12pt;
            color: #111;
            line-height: 1.45;
        }

        .kop {
            margin-bottom: 18px;
            border-bottom: 1.5px solid #000;
            padding-bottom: 8px;
            text-align: center;
            font-size: 16pt;
            font-weight: bold;
            letter-spacing: 0.3px;
        }

        .doc-title {
            text-align: center;
            font-size: 13pt;
            font-weight: bold;
            margin: 0 0 14px 0;
            letter-spacing: 0.3px;
        }

        .identity-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 14px;
        }

        .identity-table td {
            vertical-align: top;
            padding: 2px 0;
            font-size: 12pt;
        }

        .label {
            width: 90px;
            font-weight: bold;
        }

        .sep {
            width: 10px;
        }

        .target-block {
            margin: 12px 0 14px 0;
            line-height: 1.5;
        }

        .salam {
            margin: 10px 0;
        }

        .subject {
            font-weight: bold;
            font-size: 12pt;
            margin: 8px 0 10px 0;
        }

        .body {
            text-align: justify;
        }

        .body p {
            margin: 0 0 10px 0;
        }

        .body ul,
        .body ol {
            margin: 0 0 10px 22px;
        }

        .closing {
            margin-top: 14px;
        }

        .footer-block {
            width: 100%;
            margin-top: 18px;
            border-collapse: collapse;
        }

        .footer-block td {
            vertical-align: top;
        }

        .tembusan-cell {
            width: 52%;
            padding-right: 18px;
        }

        .signature-cell {
            width: 48%;
            padding-left: 18px;
        }

        .signature-space {
            height: 58px;
        }
    </style>
</head>
<body>
    @php
        $jenisRaw = strtolower(trim((string) $surat->jenis));
        $jenisSafe = trim((string) $surat->jenis);
        $docTitle = 'SURAT INTERNAL ANTAR DIVISI';
        $jenisLabel = 'Permintaan';

        if (in_array($jenisRaw, ['memorandum', 'memo', 'momendum'], true)) {
            $docTitle = 'MEMORANDUM INTERNAL';
            $jenisLabel = 'Memorandum';
        } elseif ($jenisRaw === 'laporan') {
            $docTitle = 'LAPORAN INTERNAL';
            $jenisLabel = 'Laporan';
        } else {
            $docTitle = $jenisSafe === '' ? 'SURAT INTERNAL' : ('SURAT ' . strtoupper($jenisSafe));
            $jenisLabel = $jenisSafe === '' ? 'Internal' : $jenisSafe;
        }
    @endphp

    @php
        $tembusanItems = collect($surat->tembusan_list ?? $surat->cc_divisions ?? [])
            ->map(fn ($item) => trim((string) $item))
            ->filter()
            ->values()
            ->all();
        $hasTembusan = count($tembusanItems) > 0;
        if ($hasTembusan && !collect($tembusanItems)->contains(fn ($item) => strtolower($item) === 'arsip')) {
            $tembusanItems[] = 'Arsip';
        }
    @endphp

    <div class="kop">
        PT PERTA ARUN GAS
    </div>

    <div class="doc-title">{{ $docTitle }}</div>

    <table class="identity-table">
        <tr>
            <td class="label">Nomor</td>
            <td class="sep">:</td>
            <td>{{ $surat->nomor_surat ?? 'No. XXX/PAGKODEUNIT/TAHUN' }}</td>
        </tr>
        <tr>
            <td class="label">Lampiran</td>
            <td class="sep">:</td>
            <td>{{ $surat->lampiran_name ? '1 berkas' : '-' }}</td>
        </tr>
        <tr>
            <td class="label">Tanggal</td>
            <td class="sep">:</td>
            <td>{{ optional($surat->sent_at)?->timezone(config('app.timezone'))->translatedFormat('d F Y') ?? '-' }}</td>
        </tr>
        <tr>
            <td class="label">Perihal</td>
            <td class="sep">:</td>
            <td>{{ $surat->judul }}</td>
        </tr>
    </table>

    <div class="target-block">
        <div>Kepada Yth.</div>
        <div><strong>Manager {{ $surat->recipient_division }}</strong></div>
        <div>Di Tempat</div>
    </div>

    <div class="salam">Dengan hormat,</div>
    <div class="body">{!! $surat->isi !!}</div>

    <div class="closing">
        Demikian surat ini kami sampaikan. Atas perhatian dan kerja samanya kami ucapkan terima kasih.
    </div>

    <table class="footer-block">
        <tr>
            <td class="tembusan-cell">
                <div style="font-weight: bold;">Tembusan:</div>
                <ol style="margin: 6px 0 0 18px; padding: 0;">
                    @forelse ($tembusanItems as $item)
                        <li>{{ $item }}</li>
                    @empty
                        <li>-</li>
                    @endforelse
                </ol>
            </td>
            <td class="signature-cell">
                <div>Hormat kami,</div>
                <div class="signature-space"></div>
                <div>(....................................)</div>
                <div>{{ $surat->sender_division }}</div>
            </td>
        </tr>
    </table>
</body>
</html>

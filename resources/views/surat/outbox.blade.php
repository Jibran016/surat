@extends('layouts.app')

@section('title', 'Surat Keluar')

@section('content')
<div class="card">
    <div class="card-header">
        <h1>Surat Keluar</h1>
    </div>

    @if ($surats->isEmpty())
        <p class="muted">Belum ada surat keluar.</p>
    @else
        <table class="table">
            <thead>
                <tr>
                    <th>Tanggal</th>
                    <th>Nomor</th>
                    <th>Tujuan</th>
                    <th>Judul</th>
                    <th>Status</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($surats as $surat)
                    <tr>
                        <td>{{ optional($surat->sent_at)->format('d M Y') ?? '-' }}</td>
                        <td>{{ $surat->nomor_surat ?? '-' }}</td>
                        <td>{{ $surat->recipient_division }}</td>
                        <td>{{ $surat->judul }}</td>
                        <td><span class="badge badge-{{ strtolower($surat->status) }}">{{ $surat->status }}</span></td>
                        <td class="row-actions">
                            <a class="link" href="{{ route('surat.show', $surat) }}">Detail</a>
                            <a class="link" href="{{ route('surat.pdf', $surat) }}">PDF</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection

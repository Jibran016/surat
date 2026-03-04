@extends('layouts.app')

@section('title', 'Arsip Surat')

@section('content')
<div class="card">
    <div class="card-header">
        <h1>Arsip</h1>
        <a class="btn btn-ghost" href="{{ route('dashboard') }}">Dashboard</a>
    </div>

    @if ($surats->isEmpty())
        <p class="muted">Belum ada surat diarsipkan.</p>
    @else
        <table class="table">
            <thead>
                <tr>
                    <th>Tanggal Arsip</th>
                    <th>Nomor</th>
                    <th>Judul</th>
                    <th>Pengirim</th>
                    <th>Penerima</th>
                    <th>Status</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($surats as $surat)
                    <tr>
                        <td>{{ optional($surat->archived_at)->format('d M Y') ?? '-' }}</td>
                        <td>{{ $surat->nomor_surat ?? '-' }}</td>
                        <td>{{ $surat->judul }}</td>
                        <td>{{ $surat->sender_division }}</td>
                        <td>{{ $surat->recipient_division }}</td>
                        <td><span class="badge badge-{{ strtolower($surat->status) }}">{{ $surat->status }}</span></td>
                        <td><a class="link" href="{{ route('surat.show', $surat) }}">Detail</a></td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection

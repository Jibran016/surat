@extends('layouts.app')

@section('title', 'Surat Masuk')

@section('content')
<div class="card">
    <div class="card-header">
        <h1>Surat Masuk</h1>
    </div>

    @if ($surats->isEmpty())
        <p class="muted">Belum ada surat masuk.</p>
    @else
        <table class="table">
            <thead>
                <tr>
                    <th>Tanggal</th>
                    <th>Nomor</th>
                    <th>Dari</th>
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
                        <td>{{ $surat->sender_division }}</td>
                        <td>{{ $surat->judul }}</td>
                        <td><span class="badge badge-{{ strtolower($surat->status) }}">{{ $surat->status }}</span></td>
                        <td><a class="link" href="{{ route('surat.show', $surat) }}">Detail</a></td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection

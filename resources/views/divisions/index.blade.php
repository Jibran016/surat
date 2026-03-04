@extends('layouts.app')

@section('title', 'Manajemen Divisi')

@section('content')
<div class="card">
    <div class="card-header">
        <h1>Manajemen Divisi</h1>
        <a class="btn" href="{{ route('divisions.create') }}">Tambah Divisi</a>
    </div>

    @if ($divisions->isEmpty())
        <p class="muted">Belum ada data divisi.</p>
    @else
        <table class="table">
            <thead>
                <tr>
                    <th>Divisi</th>
                    <th style="width: 160px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($divisions as $division)
                    <tr>
                        <td>{{ $division->name }}</td>
                        <td class="row-actions">
                            <a class="link" href="{{ route('divisions.edit', $division) }}">Edit</a>
                            <form method="POST" action="{{ route('divisions.destroy', $division) }}" onsubmit="return confirm('Hapus divisi ini?')">
                                @csrf
                                @method('DELETE')
                                <button class="link danger" type="submit">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        @if (method_exists($divisions, 'links'))
            <div class="pagination">
                <div class="page-info">Halaman {{ $divisions->currentPage() }} dari {{ $divisions->lastPage() }}</div>
                <div class="page-actions">
                    @if ($divisions->onFirstPage())
                        <span class="page-link disabled">Sebelumnya</span>
                    @else
                        <a class="page-link" href="{{ $divisions->previousPageUrl() }}">Sebelumnya</a>
                    @endif

                    @if ($divisions->hasMorePages())
                        <a class="page-link" href="{{ $divisions->nextPageUrl() }}">Berikutnya</a>
                    @else
                        <span class="page-link disabled">Berikutnya</span>
                    @endif
                </div>
            </div>
        @endif
    @endif
</div>
@endsection

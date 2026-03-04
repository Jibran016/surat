@extends('layouts.app')

@section('title', 'Notifikasi')

@section('content')
<div class="card">
    <div class="card-header">
        <div>
            <h1>Notifikasi</h1>
            <div class="filter-tabs">
                <a class="btn btn-ghost {{ $filter === 'all' ? 'active' : '' }}" href="{{ route('notifications.index') }}">Semua</a>
                <a class="btn btn-ghost {{ $filter === 'unread' ? 'active' : '' }}" href="{{ route('notifications.index', ['status' => 'unread']) }}">Baru</a>
                <a class="btn btn-ghost {{ $filter === 'read' ? 'active' : '' }}" href="{{ route('notifications.index', ['status' => 'read']) }}">Dibaca</a>
            </div>
        </div>
        <a class="btn btn-ghost" href="{{ route('dashboard') }}">Dashboard</a>
    </div>

    @if ($notifications->isEmpty())
        <p class="muted">Belum ada notifikasi.</p>
    @else
        <table class="table">
            <thead>
                <tr>
                    <th>Waktu</th>
                    <th>Pesan</th>
                    <th>Status</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($notifications as $notification)
                    <tr>
                        <td>{{ $notification->created_at->format('d M Y H:i') }}</td>
                        <td>{{ $notification->message }}</td>
                        <td>
                            @if ($notification->read_at)
                                <span class="badge badge-read">Dibaca</span>
                            @else
                                <span class="badge badge-unread">Baru</span>
                            @endif
                        </td>
                        <td>
                            <form method="POST" action="{{ route('notifications.open', $notification) }}">
                                @csrf
                                <button class="btn btn-ghost" type="submit">Buka Surat</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        @if ($notifications->hasPages())
            <div class="pagination">
                @if ($notifications->onFirstPage())
                    <span class="page-link disabled">Sebelumnya</span>
                @else
                    <a class="page-link" href="{{ $notifications->previousPageUrl() }}">Sebelumnya</a>
                @endif

                <span class="page-info">Halaman {{ $notifications->currentPage() }} dari {{ $notifications->lastPage() }}</span>

                @if ($notifications->hasMorePages())
                    <a class="page-link" href="{{ $notifications->nextPageUrl() }}">Berikutnya</a>
                @else
                    <span class="page-link disabled">Berikutnya</span>
                @endif
            </div>
        @endif
    @endif
</div>
@endsection

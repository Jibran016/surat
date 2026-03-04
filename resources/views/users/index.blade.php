@extends('layouts.app')

@section('title', 'Manajemen Akun')

@section('content')
<div class="card">
    <div class="card-header">
        <h1>Manajemen Akun</h1>
        <a class="btn" href="{{ route('users.create') }}">Tambah Akun</a>
    </div>

    @if ($users->isEmpty())
        <p class="muted">Belum ada akun.</p>
    @else
        <table class="table">
            <thead>
                <tr>
                    <th>Username</th>
                    <th>Divisi</th>
                    <th>Role</th>
                    <th>Email</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($users as $user)
                    <tr>
                        <td>{{ $user->username }}</td>
                        <td>{{ $user->division ?? '-' }}</td>
                        <td>{{ $user->role }}</td>
                        <td>{{ $user->email ?? '-' }}</td>
                        <td>
                            <div class="row-actions">
                                <a class="link" href="{{ route('users.edit', $user) }}">Edit</a>
                                <form method="POST" action="{{ route('users.destroy', $user) }}" onsubmit="return confirm('Hapus akun ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button class="link danger" type="submit">Hapus</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        @if ($users->hasPages())
            <div class="pagination">
                @if ($users->onFirstPage())
                    <span class="page-link disabled">Sebelumnya</span>
                @else
                    <a class="page-link" href="{{ $users->previousPageUrl() }}">Sebelumnya</a>
                @endif

                <span class="page-info">Halaman {{ $users->currentPage() }} dari {{ $users->lastPage() }}</span>

                @if ($users->hasMorePages())
                    <a class="page-link" href="{{ $users->nextPageUrl() }}">Berikutnya</a>
                @else
                    <span class="page-link disabled">Berikutnya</span>
                @endif
            </div>
        @endif
    @endif
</div>
@endsection

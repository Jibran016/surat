@extends('layouts.app')

@section('title', 'Manajemen Akun - Surat Menyurat')

@section('content')
<div class="rounded-2xl border border-slate-200 bg-white p-6">
    <div class="flex flex-wrap items-center justify-between gap-3">
        <div>
            <h1 class="inline-flex items-center gap-2 text-2xl font-bold text-slate-900">
                <svg viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M22 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                Manajemen Akun
            </h1>
            <p class="mt-1 text-sm text-slate-500">Kelola akun pengguna dan peran.</p>
        </div>
        <a href="{{ route('users.create') }}" class="inline-flex items-center gap-1.5 rounded-lg bg-blue-600 px-3 py-2 text-sm font-semibold text-white hover:bg-blue-700">
            <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 5v14"></path><path d="M5 12h14"></path></svg>
            Tambah Akun
        </a>
    </div>

    <div class="mt-5">
        @if ($users->isEmpty())
            <p class="text-sm text-slate-500">Belum ada akun.</p>
        @else
            <div class="overflow-hidden rounded-xl border border-slate-200">
                <table class="w-full text-sm">
                    <thead class="bg-slate-50 text-left text-xs font-semibold uppercase tracking-wider text-slate-600">
                        <tr>
                            <th class="px-4 py-3">Username</th>
                            <th class="px-4 py-3">Divisi</th>
                            <th class="px-4 py-3">Role</th>
                            <th class="px-4 py-3">Email</th>
                            <th class="px-4 py-3 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200 bg-white">
                        @foreach ($users as $user)
                            <tr>
                                <td class="px-4 py-3 font-medium text-slate-900">{{ $user->username }}</td>
                                <td class="px-4 py-3 text-slate-600">{{ $user->division ?? '-' }}</td>
                                <td class="px-4 py-3 text-slate-600">{{ $user->role }}</td>
                                <td class="px-4 py-3 text-slate-600">{{ $user->email ?? '-' }}</td>
                                <td class="px-4 py-3">
                                    <div class="flex items-center justify-end gap-2">
                                        <a class="inline-flex rounded-md border border-slate-300 px-2.5 py-1 text-xs font-semibold text-slate-700 hover:bg-slate-100" href="{{ route('users.edit', $user) }}">Edit</a>
                                        <form method="POST" action="{{ route('users.destroy', $user) }}" onsubmit="return confirm('Hapus akun ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button class="inline-flex rounded-md border border-rose-200 bg-rose-50 px-2.5 py-1 text-xs font-semibold text-rose-700 hover:bg-rose-100" type="submit">Hapus</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>
@endsection

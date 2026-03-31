@extends('layouts.app')

@section('title', 'Manajemen Divisi - Surat Menyurat')

@section('content')
<div class="rounded-2xl border border-slate-200 bg-white p-6">
    <div class="flex flex-wrap items-center justify-between gap-3">
        <div>
            <h1 class="inline-flex items-center gap-2 text-2xl font-bold text-slate-900">
                <svg viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 21h18"></path><path d="M5 21V7l7-4 7 4v14"></path></svg>
                Manajemen Divisi
            </h1>
            <p class="mt-1 text-sm text-slate-500">Kelola data divisi untuk kebutuhan surat internal.</p>
        </div>
        <a class="inline-flex items-center gap-1.5 rounded-lg bg-blue-600 px-3 py-2 text-sm font-semibold text-white hover:bg-blue-700" href="{{ route('divisions.create') }}">
            <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 5v14"></path><path d="M5 12h14"></path></svg>
            Tambah Divisi
        </a>
    </div>

    <div class="mt-5">
        @if ($divisions->isEmpty())
            <p class="text-sm text-slate-500">Belum ada data divisi.</p>
        @else
            <div class="overflow-hidden rounded-xl border border-slate-200">
                <table class="w-full text-sm">
                    <thead class="bg-slate-50 text-left text-xs font-semibold uppercase tracking-wider text-slate-600">
                        <tr>
                            <th class="px-4 py-3">Divisi</th>
                            <th class="px-4 py-3 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200 bg-white">
                        @foreach ($divisions as $division)
                            <tr>
                                <td class="px-4 py-3 font-medium text-slate-900">{{ $division->name }}</td>
                                <td class="px-4 py-3">
                                    <div class="flex items-center justify-end gap-2">
                                        <a class="inline-flex rounded-md border border-slate-300 px-2.5 py-1 text-xs font-semibold text-slate-700 hover:bg-slate-100" href="{{ route('divisions.edit', $division) }}">Edit</a>
                                        <form method="POST" action="{{ route('divisions.destroy', $division) }}" onsubmit="return confirm('Hapus divisi ini?')">
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

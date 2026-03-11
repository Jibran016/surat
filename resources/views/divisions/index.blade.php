@extends('layouts.app')

@section('title', 'Manajemen Divisi - SURATIN')

@section('content')
<div class="rounded-3xl border border-pink-100 bg-white/90 p-8 shadow-[0_30px_60px_-40px_rgba(236,72,153,0.45)]">
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-semibold text-slate-900">Manajemen Divisi</h1>
            <p class="mt-2 text-sm text-slate-500">Kelola data divisi untuk kebutuhan surat internal.</p>
        </div>
        <a class="inline-flex items-center gap-2 rounded-full bg-pink-600 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-pink-700" href="{{ route('divisions.create') }}">
            Tambah Divisi
        </a>
    </div>

    <div class="mt-6">
        @if ($divisions->isEmpty())
            <p class="text-sm text-slate-500">Belum ada data divisi.</p>
        @else
            <div class="overflow-hidden rounded-2xl border border-pink-100">
                <table class="w-full text-sm">
                    <thead class="bg-pink-50/60 text-left text-xs font-semibold uppercase tracking-wider text-slate-600">
                        <tr>
                            <th class="px-4 py-3">Divisi</th>
                            <th class="px-4 py-3 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-pink-100/70 bg-white">
                        @foreach ($divisions as $division)
                            <tr>
                                <td class="px-4 py-3 font-medium text-slate-800">{{ $division->name }}</td>
                                <td class="px-4 py-3">
                                    <div class="flex items-center justify-end gap-3">
                                        <a class="text-sm font-semibold text-pink-700 hover:text-pink-800" href="{{ route('divisions.edit', $division) }}">Edit</a>
                                        <form method="POST" action="{{ route('divisions.destroy', $division) }}" onsubmit="return confirm('Hapus divisi ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button class="text-sm font-semibold text-rose-600 hover:text-rose-700" type="submit">Hapus</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @if (method_exists($divisions, 'links'))
                <div class="mt-6 flex flex-wrap items-center justify-between gap-3 text-sm text-slate-600">
                    <div>Halaman {{ $divisions->currentPage() }} dari {{ $divisions->lastPage() }}</div>
                    <div class="flex items-center gap-2">
                        @if ($divisions->onFirstPage())
                            <span class="rounded-full border border-pink-100 px-3 py-1 text-slate-400">Sebelumnya</span>
                        @else
                            <a class="rounded-full border border-pink-200 px-3 py-1 font-semibold text-pink-700 hover:bg-pink-50" href="{{ $divisions->previousPageUrl() }}">Sebelumnya</a>
                        @endif

                        @if ($divisions->hasMorePages())
                            <a class="rounded-full border border-pink-200 px-3 py-1 font-semibold text-pink-700 hover:bg-pink-50" href="{{ $divisions->nextPageUrl() }}">Berikutnya</a>
                        @else
                            <span class="rounded-full border border-pink-100 px-3 py-1 text-slate-400">Berikutnya</span>
                        @endif
                    </div>
                </div>
            @endif
        @endif
    </div>
</div>
@endsection

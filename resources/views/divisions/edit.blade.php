@extends('layouts.app')

@section('title', 'Edit Divisi - Surat Menyurat')

@section('content')
<div class="mx-auto max-w-xl rounded-2xl border border-slate-200 bg-white p-6">
    <h1 class="inline-flex items-center gap-2 text-2xl font-bold text-slate-900">
        <svg viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 20h9"></path><path d="M16.5 3.5a2.1 2.1 0 0 1 3 3L7 19l-4 1 1-4Z"></path></svg>
        Edit Divisi
    </h1>
    <p class="mt-1 text-sm text-slate-500">Perbarui data divisi yang dipilih.</p>

    <form method="POST" action="{{ route('divisions.update', $division) }}" class="mt-5 space-y-4">
        @csrf
        @method('PUT')

        <div>
            <label for="name" class="text-sm font-semibold text-slate-700">Nama Divisi</label>
            <input id="name" name="name" type="text" value="{{ old('name', $division->name) }}" required class="mt-2 w-full rounded-xl border border-slate-300 px-3 py-2.5 text-sm focus:border-blue-500 focus:ring-blue-500">
            @error('name')<div class="mt-1 text-sm text-rose-600">{{ $message }}</div>@enderror
        </div>

        <button type="submit" class="inline-flex items-center gap-1.5 rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-700">
            <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path><path d="M17 21v-8H7v8"></path></svg>
            Simpan
        </button>
    </form>
</div>
@endsection

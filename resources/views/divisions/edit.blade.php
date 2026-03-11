@extends('layouts.app')

@section('title', 'Edit Divisi - SURATIN')

@section('content')
<div class="mx-auto max-w-xl rounded-3xl border border-pink-100 bg-white/90 p-8 shadow-[0_30px_60px_-40px_rgba(236,72,153,0.45)]">
    <div class="mb-6">
        <h1 class="text-2xl font-semibold text-slate-900">Edit Divisi</h1>
        <p class="mt-2 text-sm text-slate-500">Perbarui data divisi yang dipilih.</p>
    </div>

    <form method="POST" action="{{ route('divisions.update', $division) }}" class="space-y-5">
        @csrf
        @method('PUT')
        <div>
            <label for="name" class="text-sm font-semibold text-slate-700">Nama Divisi</label>
            <input id="name" name="name" type="text" value="{{ old('name', $division->name) }}" required
                class="mt-2 w-full rounded-2xl border border-pink-100 bg-white px-4 py-3 text-sm text-slate-700 shadow-sm outline-none transition focus:border-pink-300 focus:ring-2 focus:ring-pink-200">
            @error('name')
                <div class="mt-2 text-sm text-rose-600">{{ $message }}</div>
            @enderror
        </div>

        <div>
            <label for="unit_code" class="text-sm font-semibold text-slate-700">Nomor Divisi/Unit</label>
            <input id="unit_code" name="unit_code" type="text" value="{{ old('unit_code', $division->unit_code) }}" required
                class="mt-2 w-full rounded-2xl border border-pink-100 bg-white px-4 py-3 text-sm text-slate-700 shadow-sm outline-none transition focus:border-pink-300 focus:ring-2 focus:ring-pink-200"
                placeholder="Contoh: 1000">
            @error('unit_code')
                <div class="mt-2 text-sm text-rose-600">{{ $message }}</div>
            @enderror
        </div>

        <button class="inline-flex items-center gap-2 rounded-full bg-pink-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-pink-700" type="submit">
            Simpan
        </button>
    </form>
</div>
@endsection

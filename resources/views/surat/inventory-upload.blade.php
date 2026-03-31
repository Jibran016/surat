@extends('layouts.app')

@section('title', 'Upload Surat Lama - Surat Menyurat')

@section('content')
<div class="mx-auto max-w-3xl rounded-2xl border border-slate-200 bg-white p-6">
    <div class="mb-5 flex items-start justify-between gap-3">
        <div>
            <h1 class="inline-flex items-center gap-2 text-2xl font-bold text-slate-900">
                <svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M12 3v12"></path>
                    <path d="m7 10 5-5 5 5"></path>
                    <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                </svg>
                Upload Surat Lama
            </h1>
            <p class="mt-1 text-sm text-slate-500">Tambahkan data surat lama divisi ke dalam inventori file.</p>
        </div>
        <a href="{{ route('surat.inventory') }}" class="inline-flex items-center gap-1 rounded-lg border border-slate-300 px-3 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-100">
            Kembali
        </a>
    </div>

    <form method="POST" action="{{ route('surat.inventory.store') }}" enctype="multipart/form-data" class="grid gap-4">
        @csrf

        <div>
            <label class="mb-1 block text-xs font-semibold uppercase tracking-wide text-slate-600">Jenis Surat</label>
            <select name="jenis" required
                class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm text-slate-700 outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100">
                <option value="">Pilih jenis surat</option>
                @foreach ($jenisList as $jenis)
                    <option value="{{ $jenis }}" @selected(old('jenis') === $jenis)>{{ $jenis }}</option>
                @endforeach
            </select>
            @error('jenis')
                <div class="mt-1 text-xs text-rose-600">{{ $message }}</div>
            @enderror
        </div>

        <div>
            <label class="mb-1 block text-xs font-semibold uppercase tracking-wide text-slate-600">Judul Surat</label>
            <input type="text" name="judul" value="{{ old('judul') }}" required
                class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm text-slate-700 outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100">
            @error('judul')
                <div class="mt-1 text-xs text-rose-600">{{ $message }}</div>
            @enderror
        </div>

        <div>
            <label class="mb-1 block text-xs font-semibold uppercase tracking-wide text-slate-600">Tgl Surat / Waktu</label>
            <input type="datetime-local" name="sent_at" value="{{ old('sent_at') }}" required
                class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm text-slate-700 outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100">
            @error('sent_at')
                <div class="mt-1 text-xs text-rose-600">{{ $message }}</div>
            @enderror
        </div>

        <div>
            <label class="mb-1 block text-xs font-semibold uppercase tracking-wide text-slate-600">File Surat</label>
            <input type="file" name="lampiran" accept=".pdf,.doc,.docx,application/pdf,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document" required
                class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm text-slate-700 file:mr-3 file:rounded-md file:border-0 file:bg-slate-200 file:px-2 file:py-1 file:text-xs file:font-semibold file:text-slate-700 hover:file:bg-slate-300">
            @error('lampiran')
                <div class="mt-1 text-xs text-rose-600">{{ $message }}</div>
            @enderror
        </div>

        <div>
            <button type="submit" class="inline-flex items-center gap-1 rounded-lg bg-slate-900 px-3 py-2 text-sm font-semibold text-white hover:bg-slate-800">
                Upload Surat
            </button>
        </div>
    </form>
</div>
@endsection

@extends('layouts.app')

@section('title', 'Balas Surat - SURATIN')

@push('styles')
    <link rel="stylesheet" href="https://unpkg.com/trix@2.1.2/dist/trix.css">
    <style>
        trix-toolbar,
        trix-editor {
            border-color: rgb(252 231 243) !important;
            border-radius: 1rem;
            box-shadow: 0 1px 2px 0 rgb(15 23 42 / 0.05);
            background: #fff;
        }

        trix-editor {
            min-height: 12rem;
            padding: 1rem;
            color: rgb(51 65 85);
        }
    </style>
@endpush

@section('content')
<div class="mx-auto max-w-4xl rounded-3xl border border-pink-100 bg-white/90 p-8 shadow-[0_30px_60px_-40px_rgba(236,72,153,0.45)]">
    <div class="mb-6">
        <h1 class="text-2xl font-semibold text-slate-900">Balas Surat</h1>
        <p class="mt-2 text-sm text-slate-500">Balasan untuk surat: {{ $surat->judul }}</p>
    </div>

    <form method="POST" action="{{ route('surat.reply.store', $surat) }}" enctype="multipart/form-data" class="grid gap-5 sm:grid-cols-2">
        @csrf

        <div class="sm:col-span-2">
            <label class="text-sm font-semibold text-slate-700">Tujuan</label>
            <div class="mt-2 rounded-2xl border border-pink-100 bg-pink-50/60 px-4 py-3 text-sm font-medium text-slate-700">{{ $surat->sender_division }}</div>
        </div>

        <div class="sm:col-span-2">
            <label for="judul" class="text-sm font-semibold text-slate-700">Judul Balasan</label>
            <input id="judul" name="judul" type="text" value="{{ old('judul', 'Balasan: ' . $surat->judul) }}" required
                class="mt-2 w-full rounded-2xl border border-pink-100 bg-white px-4 py-3 text-sm text-slate-700 shadow-sm outline-none transition focus:border-pink-300 focus:ring-2 focus:ring-pink-200">
            @error('judul')
                <div class="mt-2 text-sm text-rose-600">{{ $message }}</div>
            @enderror
        </div>

        <div class="sm:col-span-2">
            <label for="isi" class="text-sm font-semibold text-slate-700">Isi Balasan</label>
            <input id="isi" type="hidden" name="isi" value="{{ old('isi') }}">
            <trix-editor input="isi" class="mt-2"></trix-editor>
            @error('isi')
                <div class="mt-2 text-sm text-rose-600">{{ $message }}</div>
            @enderror
        </div>

        <div class="sm:col-span-2">
            <label for="lampiran" class="text-sm font-semibold text-slate-700">Lampiran (opsional)</label>
            <input id="lampiran" name="lampiran" type="file"
                class="mt-2 w-full rounded-2xl border border-pink-100 bg-white px-4 py-3 text-sm text-slate-700 file:mr-4 file:rounded-full file:border-0 file:bg-pink-100 file:px-3 file:py-1.5 file:text-sm file:font-semibold file:text-pink-700 hover:file:bg-pink-200">
            <div class="mt-2 text-xs text-slate-500">Lampiran mendukung JPG/JPEG/PDF. Preview isi lampiran di halaman berikut PDF surat hanya untuk JPG/JPEG.</div>
            @error('lampiran')
                <div class="mt-2 text-sm text-rose-600">{{ $message }}</div>
            @enderror
        </div>

        <div class="sm:col-span-2">
            <button class="inline-flex items-center gap-2 rounded-full bg-pink-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-pink-700" type="submit">
                Kirim Balasan
            </button>
        </div>
    </form>
</div>
@endsection

@push('scripts')
    <script src="https://unpkg.com/trix@2.1.2/dist/trix.umd.min.js"></script>
    <script>
        const trixUploadEndpoint = @json(route('trix.attachments.store'));
        const trixCsrfToken = @json(csrf_token());

        document.addEventListener('trix-attachment-add', function (event) {
            const attachment = event.attachment;
            if (attachment.file) {
                uploadTrixAttachment(attachment);
            }
        });

        function uploadTrixAttachment(attachment) {
            const file = attachment.file;
            const form = new FormData();
            form.append('attachment', file);
            form.append('_token', trixCsrfToken);

            const xhr = new XMLHttpRequest();
            xhr.open('POST', trixUploadEndpoint, true);

            xhr.upload.addEventListener('progress', function (event) {
                if (event.lengthComputable) {
                    const progress = (event.loaded / event.total) * 100;
                    attachment.setUploadProgress(progress);
                }
            });

            xhr.addEventListener('load', function () {
                if (xhr.status >= 200 && xhr.status < 300) {
                    const response = JSON.parse(xhr.responseText);
                    attachment.setAttributes({
                        url: response.url,
                        href: response.url,
                    });
                } else {
                    attachment.remove();
                }
            });

            xhr.addEventListener('error', function () {
                attachment.remove();
            });

            xhr.send(form);
        }
    </script>
@endpush

@extends('layouts.app')

@section('title', 'Buat Surat - SURATIN')

@push('styles')
    <link rel="stylesheet" href="https://unpkg.com/trix@2.1.2/dist/trix.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/choices.js/public/assets/styles/choices.min.css">
    <style>
        trix-toolbar,
        trix-editor,
        .choices__inner,
        .choices__list--dropdown,
        .choices__list[aria-expanded] {
            border-color: rgba(255, 43, 214, 0.38) !important;
            border-radius: 1rem;
            box-shadow: 0 12px 26px -18px rgba(255, 43, 214, 0.45);
            background: linear-gradient(165deg, rgba(26, 21, 36, 0.96), rgba(17, 13, 26, 0.94)) !important;
            color: #f5f2ff !important;
        }

        trix-editor {
            min-height: 12rem;
            padding: 1rem;
            color: #f5f2ff !important;
            caret-color: #ff7ae8;
        }

        .choices__inner {
            padding: 0.55rem 0.75rem;
        }

        trix-toolbar .trix-button {
            background: rgba(255, 43, 214, 0.1) !important;
            border-color: rgba(255, 43, 214, 0.28) !important;
            color: #ffb4f2 !important;
        }

        trix-toolbar .trix-button.trix-active {
            background: rgba(255, 43, 214, 0.24) !important;
            color: #fff !important;
        }

        trix-toolbar .trix-button::before {
            filter: brightness(0) invert(1);
        }

        trix-toolbar .trix-button-group {
            border-color: rgba(255, 43, 214, 0.3) !important;
        }

        trix-toolbar .trix-dialog {
            background: linear-gradient(165deg, rgba(26, 21, 36, 0.98), rgba(17, 13, 26, 0.96)) !important;
            border-color: rgba(255, 43, 214, 0.38) !important;
            color: #f5f2ff !important;
        }

        trix-toolbar .trix-dialog label,
        trix-toolbar .trix-dialog input,
        trix-toolbar .trix-dialog select,
        trix-toolbar .trix-dialog a {
            color: #f5f2ff !important;
        }

        trix-toolbar .trix-dialog input,
        trix-toolbar .trix-dialog select {
            background: rgba(12, 10, 18, 0.9) !important;
            border-color: rgba(255, 43, 214, 0.35) !important;
        }

        .choices__input,
        .choices__list--single .choices__item,
        .choices__list--dropdown .choices__item {
            background: transparent !important;
            color: #f5f2ff !important;
        }

        .choices__list--single .choices__item,
        .choices__list--multiple .choices__item,
        .choices__list--dropdown .choices__item,
        .choices__input {
            text-shadow: none !important;
        }

        .choices__list--dropdown .choices__item--selectable.is-highlighted {
            background: rgba(255, 43, 214, 0.18) !important;
        }

        .choices__list--multiple .choices__item {
            background: rgba(255, 43, 214, 0.24) !important;
            border: 1px solid rgba(255, 43, 214, 0.45) !important;
            color: #ffd4f7 !important;
        }

        .choices__placeholder {
            color: #c8bdd9 !important;
            opacity: 1 !important;
        }

        input.bg-black\/40,
        select.bg-black\/40,
        textarea.bg-black\/40 {
            color: #f5f2ff !important;
        }

        #jenis option,
        #recipient_divisions option {
            background: #13101b;
            color: #f5f2ff;
        }

        .choices[data-type*="select-multiple"] .choices__button {
            border-left-color: rgba(255, 122, 232, 0.7);
        }
    </style>
@endpush

@section('content')
<div class="mx-auto max-w-4xl rounded-3xl border border-pink-100 bg-white/90 p-8 shadow-[0_30px_60px_-40px_rgba(236,72,153,0.45)]">
    <div class="mb-5">
        <a href="{{ route('dashboard') }}" aria-label="Kembali ke Dashboard" title="Kembali ke Dashboard" class="inline-flex items-center justify-center rounded-full border border-pink-200 bg-black/40 p-2.5 text-pink-100 shadow-sm transition hover:bg-pink-500/20">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                <path fill-rule="evenodd" d="M17 10a.75.75 0 0 1-.75.75H5.56l3.22 3.22a.75.75 0 1 1-1.06 1.06l-4.5-4.5a.75.75 0 0 1 0-1.06l4.5-4.5a.75.75 0 0 1 1.06 1.06L5.56 9.25h10.69A.75.75 0 0 1 17 10Z" clip-rule="evenodd" />
            </svg>
        </a>
    </div>

    <div class="mb-6">
        <h1 class="text-2xl font-semibold text-slate-900">Buat Surat</h1>
        <p class="mt-2 text-sm text-slate-500">Siapkan surat internal dan kirim ke satu atau beberapa divisi.</p>
    </div>

    <form method="POST" action="{{ route('surat.store') }}" enctype="multipart/form-data" class="grid gap-5 sm:grid-cols-2">
        @csrf
        <div class="sm:col-span-1">
            <label class="text-sm font-semibold text-slate-700">Pengirim</label>
            <div class="mt-2 rounded-2xl border border-pink-200/60 bg-black/40 px-4 py-3 text-sm font-medium text-pink-100">{{ $user->division }}</div>
        </div>

        <div class="sm:col-span-1">
            <label for="jenis" class="text-sm font-semibold text-slate-700">Jenis Surat</label>
            <select id="jenis" name="jenis" required
                class="mt-2 w-full rounded-2xl border border-pink-200/60 bg-black/40 px-4 py-3 text-sm text-pink-100 shadow-sm outline-none transition focus:border-pink-300 focus:ring-2 focus:ring-pink-300/40">
                @foreach ($jenisList as $jenis)
                    <option value="{{ $jenis }}" @selected(old('jenis') === $jenis)>{{ $jenis }}</option>
                @endforeach
            </select>
            @error('jenis')
                <div class="mt-2 text-sm text-rose-600">{{ $message }}</div>
            @enderror
        </div>

        <div class="sm:col-span-2">
            <label for="judul" class="text-sm font-semibold text-slate-700">Judul</label>
            <input id="judul" name="judul" type="text" value="{{ old('judul') }}" required
                class="mt-2 w-full rounded-2xl border border-pink-200/60 bg-black/40 px-4 py-3 text-sm text-pink-100 shadow-sm outline-none transition placeholder:text-pink-200/45 focus:border-pink-300 focus:ring-2 focus:ring-pink-300/40">
            @error('judul')
                <div class="mt-2 text-sm text-rose-600">{{ $message }}</div>
            @enderror
        </div>

        <div class="sm:col-span-2">
            <label for="isi" class="text-sm font-semibold text-slate-700">Isi Surat</label>
            <input id="isi" type="hidden" name="isi" value="{{ old('isi') }}">
            <trix-editor input="isi" class="mt-2"></trix-editor>
            @error('isi')
                <div class="mt-2 text-sm text-rose-600">{{ $message }}</div>
            @enderror
        </div>

        <div class="sm:col-span-1">
            <label for="lampiran" class="text-sm font-semibold text-slate-700">Lampiran (opsional)</label>
            <input id="lampiran" name="lampiran" type="file"
                class="mt-2 w-full rounded-2xl border border-pink-200/60 bg-black/40 px-4 py-3 text-sm text-pink-100 file:mr-4 file:rounded-full file:border-0 file:bg-pink-500/30 file:px-3 file:py-1.5 file:text-sm file:font-semibold file:text-pink-100 hover:file:bg-pink-500/45">
            <div class="mt-2 text-xs text-pink-100/70">Lampiran mendukung JPG/JPEG/PDF. Preview isi lampiran di halaman berikut PDF surat hanya untuk JPG/JPEG.</div>
            @error('lampiran')
                <div class="mt-2 text-sm text-rose-600">{{ $message }}</div>
            @enderror
        </div>

        <div class="sm:col-span-1">
            <label for="recipient_divisions" class="text-sm font-semibold text-slate-700">Divisi Tujuan</label>
            <select id="recipient_divisions" name="recipient_divisions[]" multiple required
                class="mt-2 w-full rounded-2xl border border-pink-200/60 bg-black/40 px-4 py-3 text-sm text-pink-100 shadow-sm outline-none transition focus:border-pink-300 focus:ring-2 focus:ring-pink-300/40">
                @foreach ($divisions as $division)
                    <option value="{{ $division }}" @selected(collect(old('recipient_divisions', []))->contains($division))>{{ $division }}</option>
                @endforeach
            </select>
            <div class="mt-2 text-xs text-pink-100/70">Anda bisa memilih lebih dari satu divisi.</div>
            @error('recipient_divisions')
                <div class="mt-2 text-sm text-rose-600">{{ $message }}</div>
            @enderror
            @error('recipient_divisions.*')
                <div class="mt-2 text-sm text-rose-600">{{ $message }}</div>
            @enderror
        </div>

        <div class="sm:col-span-2">
            <button class="inline-flex items-center gap-2 rounded-full bg-pink-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-pink-700" type="submit">
                Kirim
            </button>
        </div>
    </form>
</div>
@endsection

@push('scripts')
    <script src="https://unpkg.com/trix@2.1.2/dist/trix.umd.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/choices.js/public/assets/scripts/choices.min.js"></script>
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

        const recipientSelect = document.getElementById('recipient_divisions');
        if (recipientSelect) {
            new Choices(recipientSelect, {
                removeItemButton: true,
                placeholder: true,
                placeholderValue: 'Pilih divisi tujuan',
                searchPlaceholderValue: 'Cari divisi...',
                shouldSort: false,
            });
        }

        const jenisSelect = document.getElementById('jenis');
        if (jenisSelect) {
            new Choices(jenisSelect, {
                searchEnabled: false,
                itemSelectText: '',
                shouldSort: false,
                allowHTML: false,
            });
        }
    </script>
@endpush

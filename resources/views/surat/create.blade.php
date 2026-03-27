@extends('layouts.app')

@section('title', 'Kirim Surat - SURATIN')

@push('styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/choices.js/public/assets/styles/choices.min.css">
    <style>
        .choices__inner,
        .choices__list--dropdown,
        .choices__list[aria-expanded] {
            border-color: rgba(0, 115, 178, 0.25) !important;
            border-radius: 1rem;
            box-shadow: 0 12px 26px -18px rgba(0, 115, 178, 0.25);
            background: #ffffff !important;
            color: #0f172a !important;
        }

        .choices__inner {
            padding: 0.55rem 0.75rem;
        }

        .choices__input,
        .choices__list--single .choices__item,
        .choices__list--dropdown .choices__item {
            background: transparent !important;
            color: #0f172a !important;
        }

        .choices__list--multiple .choices__item {
            background: rgba(0, 115, 178, 0.12) !important;
            border: 1px solid rgba(0, 115, 178, 0.25) !important;
            color: #0073B2 !important;
        }

        .choices__placeholder {
            color: #6b7b8c !important;
            opacity: 1 !important;
        }

        input.bg-black\/40,
        select.bg-black\/40,
        textarea.bg-black\/40 {
            color: #0f172a !important;
        }

        #jenis option,
        #recipient_divisions option {
            background: #ffffff;
            color: #0f172a;
        }

        .upload-tile {
            border: 1px dashed rgba(0, 115, 178, 0.3);
            background: linear-gradient(135deg, rgba(0, 115, 178, 0.06), rgba(0, 115, 178, 0.02));
        }

        .attachment-preview-image {
            max-height: 180px;
            width: auto;
            max-width: 100%;
            object-fit: contain;
            border-radius: 1rem;
            border: 1px solid rgba(0, 115, 178, 0.18);
        }
    </style>
@endpush

@section('content')
<div class="mx-auto max-w-4xl rounded-3xl border border-pink-100 bg-white/90 p-8 shadow-[0_30px_60px_-40px_rgba(236,72,153,0.45)]">
    <div class="mb-6">
        <div>
            <h1 class="text-2xl font-semibold text-slate-900">Kirim Surat</h1>
            <p class="mt-2 text-sm text-slate-500">Kirim pesan dan lampiran ke divisi tujuan. Tanggal serta jam pengiriman dicatat otomatis oleh sistem.</p>
        </div>
    </div>

    <form method="POST" action="{{ route('surat.store') }}" enctype="multipart/form-data" class="space-y-6">
        @csrf
        <div class="grid gap-5 sm:grid-cols-2">
            <div>
                <label class="text-sm font-semibold text-slate-700">Pengirim</label>
                <div class="mt-2 rounded-2xl border border-pink-200/60 bg-black/40 px-4 py-3 text-sm font-medium text-pink-100">{{ $user->division }}</div>
            </div>

            <div>
                <label class="text-sm font-semibold text-slate-700">Waktu Kirim</label>
                <div class="mt-2 rounded-2xl border border-pink-200/60 bg-black/40 px-4 py-3 text-sm font-medium text-pink-100">
                    {{ now(config('app.timezone'))->format('d M Y H:i') }}
                </div>
            </div>
        </div>

        <div class="grid gap-5 lg:grid-cols-[1.15fr_0.85fr]">
            <div class="space-y-5">
                <div>
                    <label for="recipient_divisions" class="text-sm font-semibold text-slate-700">Divisi Tujuan</label>
                    <select id="recipient_divisions" name="recipient_divisions[]" multiple required
                        class="mt-2 w-full rounded-2xl border border-pink-200/60 bg-black/40 px-4 py-3 text-sm text-pink-100 shadow-sm outline-none transition focus:border-pink-300 focus:ring-2 focus:ring-pink-300/40">
                        @foreach ($divisions as $division)
                            <option value="{{ $division }}" @selected(collect(old('recipient_divisions', []))->contains($division))>{{ $division }}</option>
                        @endforeach
                    </select>
                    <div class="mt-2 text-xs text-pink-100/70">Pilih satu atau beberapa divisi yang akan menerima surat.</div>
                    @error('recipient_divisions')
                        <div class="mt-2 text-sm text-rose-600">{{ $message }}</div>
                    @enderror
                    @error('recipient_divisions.*')
                        <div class="mt-2 text-sm text-rose-600">{{ $message }}</div>
                    @enderror
                </div>

                <div>
                    <label for="jenis" class="text-sm font-semibold text-slate-700">Jenis Pesan</label>
                    <select id="jenis" name="jenis"
                        class="mt-2 w-full rounded-2xl border border-pink-200/60 bg-black/40 px-4 py-3 text-sm text-pink-100 shadow-sm outline-none transition focus:border-pink-300 focus:ring-2 focus:ring-pink-300/40">
                        @foreach ($jenisList as $jenis)
                            <option value="{{ $jenis }}" @selected(old('jenis', 'Surat Pengantar') === $jenis)>{{ $jenis }}</option>
                        @endforeach
                    </select>
                    @error('jenis')
                        <div class="mt-2 text-sm text-rose-600">{{ $message }}</div>
                    @enderror
                </div>

                <div>
                    <label for="judul" class="text-sm font-semibold text-slate-700">Judul Pesan</label>
                    <input id="judul" name="judul" type="text" value="{{ old('judul') }}" required
                        placeholder="Contoh: Pengiriman dokumen evaluasi bulanan"
                        class="mt-2 w-full rounded-2xl border border-pink-200/60 bg-black/40 px-4 py-3 text-sm text-pink-100 shadow-sm outline-none transition placeholder:text-pink-200/45 focus:border-pink-300 focus:ring-2 focus:ring-pink-300/40">
                    @error('judul')
                        <div class="mt-2 text-sm text-rose-600">{{ $message }}</div>
                    @enderror
                </div>

                <div>
                    <label for="isi" class="text-sm font-semibold text-slate-700">Pesan Tambahan</label>
                    <textarea id="isi" name="isi" rows="8"
                        placeholder="Tulis pengantar singkat, catatan distribusi, atau instruksi untuk divisi tujuan."
                        class="mt-2 w-full rounded-2xl border border-pink-200/60 bg-black/40 px-4 py-3 text-sm text-pink-100 shadow-sm outline-none transition placeholder:text-pink-200/45 focus:border-pink-300 focus:ring-2 focus:ring-pink-300/40">{{ old('isi') }}</textarea>
                    <div class="mt-2 text-xs text-pink-100/70">Boleh dikosongkan. Sistem tetap mengirim surat dan mencatat lampiran.</div>
                    @error('isi')
                        <div class="mt-2 text-sm text-rose-600">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="space-y-5">
                <div class="upload-tile rounded-3xl p-5">
                    <div class="flex items-start gap-4">
                        <div class="inline-flex h-12 w-12 items-center justify-center rounded-2xl bg-pink-600 text-white shadow-sm">
                            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                                <path d="M21.44 11.05 12.25 20.24a6 6 0 1 1-8.49-8.49l9.2-9.19a4 4 0 1 1 5.65 5.66l-9.2 9.19a2 2 0 0 1-2.82-2.83l8.48-8.48"></path>
                            </svg>
                        </div>
                        <div class="min-w-0 flex-1">
                            <div class="text-base font-semibold text-slate-900">Lampiran Surat</div>
                            <div class="mt-1 text-sm text-slate-500">Upload file lampiran surat.</div>
                        </div>
                    </div>

                    <div class="mt-4">
                        <label for="lampiran" class="sr-only">Lampiran</label>
                        <div class="flex items-center gap-3">
                            <input id="lampiran" name="lampiran" type="file"
                                class="min-w-0 flex-1 rounded-2xl border border-pink-200/60 bg-white px-4 py-3 text-sm text-slate-700 file:mr-4 file:rounded-full file:border-0 file:bg-pink-100 file:px-3 file:py-1.5 file:text-sm file:font-semibold file:text-pink-700 hover:file:bg-pink-200">
                            <button id="clearLampiranButton" type="button" hidden class="inline-flex h-11 w-11 items-center justify-center rounded-2xl border border-rose-200 bg-rose-50 text-rose-600 shadow-sm transition hover:bg-rose-100" aria-label="Batalkan lampiran" title="Batalkan lampiran">
                                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                                    <path d="M18 6 6 18"></path>
                                    <path d="m6 6 12 12"></path>
                                </svg>
                            </button>
                        </div>
                        <div class="mt-3 text-xs text-slate-500">PDF, foto, dokumen, dan file lain. Maks. 20 MB.</div>
                        @error('lampiran')
                            <div class="mt-2 text-sm text-rose-600">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div id="attachmentPreviewBox" class="hidden rounded-3xl border border-pink-100 bg-white p-5 shadow-sm">
                    <div class="text-sm font-semibold text-slate-900">Preview Lampiran</div>
                    <div id="attachmentPreviewContent" class="mt-3 text-sm text-slate-600"></div>
                </div>

                <div>
                    <button class="inline-flex w-full items-center justify-center gap-2 rounded-full bg-pink-600 px-5 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-pink-700" type="submit">
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                            <path d="M22 2 11 13"></path>
                            <path d="m22 2-7 20-4-9-9-4Z"></path>
                        </svg>
                        Kirim Surat
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/choices.js/public/assets/scripts/choices.min.js"></script>
    <script>
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

        const attachmentInput = document.getElementById('lampiran');
        const clearLampiranButton = document.getElementById('clearLampiranButton');
        const attachmentPreviewBox = document.getElementById('attachmentPreviewBox');
        const attachmentPreviewContent = document.getElementById('attachmentPreviewContent');

        function renderAttachmentPreview() {
            if (!attachmentInput || !attachmentPreviewBox || !attachmentPreviewContent || !clearLampiranButton) {
                return;
            }

            const file = attachmentInput.files && attachmentInput.files[0];
            if (!file) {
                attachmentPreviewBox.classList.add('hidden');
                attachmentPreviewContent.innerHTML = '';
                clearLampiranButton.hidden = true;
                return;
            }

            const safeName = file.name
                .replace(/&/g, '&amp;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;')
                .replace(/"/g, '&quot;')
                .replace(/'/g, '&#039;');

            const sizeMb = (file.size / (1024 * 1024)).toFixed(2);
            const isImage = /^image\//.test(file.type);

            if (isImage) {
                const objectUrl = URL.createObjectURL(file);
                attachmentPreviewContent.innerHTML = `
                    <img src="${objectUrl}" alt="${safeName}" class="attachment-preview-image">
                    <div class="mt-3">
                        <div class="font-medium text-slate-800">${safeName}</div>
                        <div class="mt-1 text-xs text-slate-500">${sizeMb} MB</div>
                    </div>
                `;
            } else {
                attachmentPreviewContent.innerHTML = `
                    <div class="rounded-2xl border border-pink-100 bg-pink-50/40 px-4 py-4">
                        <div class="font-medium text-slate-800">${safeName}</div>
                        <div class="mt-1 text-xs text-slate-500">${file.type || 'Format file umum'} • ${sizeMb} MB</div>
                    </div>
                `;
            }

            attachmentPreviewBox.classList.remove('hidden');
            clearLampiranButton.hidden = false;
        }

        if (attachmentInput) {
            attachmentInput.addEventListener('change', renderAttachmentPreview);
        }

        if (clearLampiranButton && attachmentInput) {
            clearLampiranButton.addEventListener('click', function () {
                attachmentInput.value = '';
                renderAttachmentPreview();
            });
        }
    </script>
@endpush

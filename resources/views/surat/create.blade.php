@extends('layouts.app')

@section('title', 'Kirim Surat - Surat Menyurat')

@push('styles')
<style>
    .compose-wrap {
        max-width: 900px;
        margin: 0 auto;
    }

    .compose-title {
        margin: 0;
        font-size: 3rem;
        line-height: 1.02;
        font-weight: 700;
        color: #0f172a;
        letter-spacing: -0.02em;
    }

    .compose-subtitle {
        margin-top: 0.45rem;
        color: #64748b;
        font-size: 1.02rem;
    }

    .notice {
        margin-top: 1.15rem;
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        background: #ffffff;
        padding: 0.85rem 0.95rem;
        color: #6b7280;
        font-size: 0.95rem;
        display: flex;
        gap: 0.65rem;
        align-items: flex-start;
    }

    .card {
        margin-top: 1.25rem;
        border: 1px solid #dfe3e8;
        border-radius: 16px;
        background: #fff;
        padding: 1.45rem;
    }

    .sec-title {
        margin: 0;
        font-size: 2rem;
        font-weight: 700;
        color: #0f172a;
        letter-spacing: -0.01em;
    }

    .line {
        border-top: 1px solid #e5e7eb;
        margin: 1.2rem 0;
    }

    .field-label {
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        margin-bottom: 0.42rem;
        font-size: 0.95rem;
        font-weight: 600;
        color: #0f172a;
    }

    .field-label .req {
        color: #ef4444;
        margin-left: 0.2rem;
    }

    .input,
    .select,
    .textarea {
        width: 100%;
        border: 1px solid #e5e7eb;
        background: #f8fafc;
        border-radius: 10px;
        color: #475569;
        font-size: 0.95rem;
        outline: none;
    }

    .input,
    .select {
        height: 44px;
        padding: 0 0.75rem;
    }

    .textarea {
        min-height: 110px;
        padding: 0.75rem;
        resize: vertical;
    }

    .select[multiple] {
        height: 112px;
        padding: 0.45rem;
    }

    .select[multiple] option {
        padding: 0.35rem 0.45rem;
        border-radius: 8px;
        margin-bottom: 0.15rem;
    }

    .input:focus,
    .select:focus,
    .textarea:focus {
        border-color: #93c5fd;
        box-shadow: 0 0 0 2px rgba(37, 99, 235, 0.12);
        background: #fff;
    }

    .grid-2 {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 1rem;
    }

    .hint {
        margin-top: 0.35rem;
        font-size: 0.82rem;
        color: #6b7280;
    }

    .multi-select {
        position: relative;
    }

    .multi-select-toggle {
        width: 100%;
        min-height: 44px;
        border: 1px solid #e5e7eb;
        background: #f8fafc;
        border-radius: 10px;
        padding: 0.35rem 0.65rem;
        display: flex;
        flex-wrap: wrap;
        gap: 0.35rem;
        align-items: center;
        cursor: pointer;
        color: #475569;
        font-size: 0.92rem;
    }

    .multi-select-toggle:focus {
        outline: none;
        border-color: #93c5fd;
        box-shadow: 0 0 0 2px rgba(37, 99, 235, 0.12);
        background: #fff;
    }

    .multi-placeholder {
        color: #94a3b8;
    }

    .chip {
        display: inline-flex;
        align-items: center;
        border-radius: 999px;
        background: #dbeafe;
        color: #1d4ed8;
        padding: 0.12rem 0.55rem;
        font-size: 0.78rem;
        font-weight: 600;
    }

    .multi-panel {
        position: absolute;
        z-index: 20;
        top: calc(100% + 0.35rem);
        left: 0;
        right: 0;
        border: 1px solid #e5e7eb;
        background: #fff;
        border-radius: 10px;
        box-shadow: 0 18px 34px -20px rgba(15, 23, 42, 0.3);
        max-height: 220px;
        overflow-y: auto;
        padding: 0.35rem;
        display: none;
    }

    .multi-panel.open {
        display: block;
    }

    .multi-option {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.45rem 0.5rem;
        border-radius: 8px;
        font-size: 0.9rem;
        color: #334155;
    }

    .multi-option:hover {
        background: #f1f5f9;
    }

    .lampiran-row {
        margin-top: 0.35rem;
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 0.8rem;
        align-items: start;
    }

    .lampiran-left {
        display: flex;
        flex-direction: column;
        gap: 0.45rem;
    }

    .lampiran-picker {
        width: fit-content;
        min-height: 38px;
        border: 1px solid #cbd5e1;
        border-radius: 10px;
        background: #fff;
        padding: 0.4rem 0.7rem;
        color: #334155;
        font-size: 0.82rem;
        font-weight: 700;
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        cursor: pointer;
    }

    .lampiran-picker:hover {
        background: #f8fafc;
    }

    .lampiran-note {
        font-size: 0.8rem;
        color: #64748b;
        line-height: 1.45;
    }

    .lampiran-filename {
        font-size: 0.8rem;
        color: #475569;
        font-weight: 600;
    }

    .file-preview {
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        background: #f8fafc;
        padding: 0.65rem;
        display: none;
        width: 100%;
        min-height: 84px;
    }

    .file-preview.show {
        display: block;
    }

    .file-preview-meta {
        display: flex;
        align-items: flex-start;
        gap: 0.6rem;
        color: #334155;
        font-size: 0.8rem;
        font-weight: 600;
        line-height: 1.45;
    }

    .file-preview-icon {
        width: 34px;
        height: 34px;
        border-radius: 8px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background: #e2e8f0;
        color: #334155;
        flex: 0 0 auto;
    }

    .file-preview-card {
        margin-top: 0.55rem;
        border: 1px solid #cbd5e1;
        border-radius: 8px;
        overflow: hidden;
        background: #fff;
    }

    .file-preview-frame {
        width: 100%;
        height: 320px;
        border: 0;
        display: block;
    }

    .file-preview-note {
        padding: 0.55rem 0.65rem;
        color: #475569;
        font-size: 0.78rem;
        border-top: 1px solid #e2e8f0;
        background: #f8fafc;
    }

    .actions {
        margin-top: 1.25rem;
        display: flex;
        gap: 0.7rem;
    }

    .btn {
        height: 42px;
        border-radius: 10px;
        border: 1px solid transparent;
        padding: 0 1.1rem;
        font-size: 0.95rem;
        font-weight: 700;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        cursor: pointer;
        text-decoration: none;
    }

    .btn-primary {
        background: #6b7280;
        color: #fff;
    }

    .btn-primary:hover { background: #4b5563; }

    .btn-soft {
        background: #fff;
        color: #111827;
        border-color: #d1d5db;
    }

    .error {
        margin-top: 0.35rem;
        color: #dc2626;
        font-size: 0.86rem;
    }

    .hidden-file { display: none; }

    .sec-head {
        display: inline-flex;
        align-items: center;
        gap: 0.55rem;
    }

    .sec-head svg,
    .field-label svg {
        color: #64748b;
        flex: 0 0 auto;
    }

    @media (max-width: 900px) {
        .compose-title { font-size: 2.3rem; }
        .sec-title { font-size: 1.5rem; }
        .grid-2 { grid-template-columns: 1fr; }
        .lampiran-row { grid-template-columns: 1fr; }
    }
</style>
@endpush

@section('content')
<div class="compose-wrap">
    <h1 class="compose-title">Kirim Surat</h1>
    <p class="compose-subtitle">Kirim surat digital ke divisi lain</p>

    <div class="notice">
        <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
            <circle cx="12" cy="12" r="10"></circle>
            <path d="M12 16v-4M12 8h.01"></path>
        </svg>
        <span>Pastikan semua informasi yang Anda masukkan sudah benar. Surat yang telah terkirim akan langsung masuk ke menu Surat Masuk divisi tujuan.</span>
    </div>

    <form method="POST" action="{{ route('surat.store') }}" enctype="multipart/form-data" class="card">
        @csrf

        <h2 class="sec-title sec-head">
            <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                <circle cx="12" cy="7" r="4"></circle>
            </svg>
            Informasi Pengirim
        </h2>
        <div class="mt-3">
            <label class="field-label">
                <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                    <rect x="3" y="4" width="18" height="16" rx="2"></rect>
                    <path d="M16 2v4M8 2v4M3 10h18"></path>
                </svg>
                Divisi Pengirim
            </label>
            <input type="text" class="input" value="{{ $user->division }}" readonly>
        </div>

        <div class="line"></div>

        <h2 class="sec-title sec-head">
            <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                <path d="M14 2v6h6"></path>
                <path d="M16 13H8M16 17H8M10 9H8"></path>
            </svg>
            Detail Surat
        </h2>

        <div class="grid-2 mt-3">
            <div>
                <label for="jenis" class="field-label">
                    <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                        <path d="M20 13V7a2 2 0 0 0-2-2h-6"></path>
                        <path d="M14 17H6a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h6"></path>
                        <path d="M14 3v4h4"></path>
                    </svg>
                    Jenis Surat <span class="req">*</span>
                </label>
                <select id="jenis" name="jenis" class="select" required>
                    <option value="">Pilih jenis surat</option>
                    @foreach ($jenisList as $jenis)
                        <option value="{{ $jenis }}" @selected(old('jenis') === $jenis)>{{ $jenis }}</option>
                    @endforeach
                </select>
                @error('jenis')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>

            <div>
                <label for="recipient_divisions" class="field-label">
                    <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                        <path d="M17 6.1H3"></path>
                        <path d="M21 12.1H3"></path>
                        <path d="M15.1 18H3"></path>
                    </svg>
                    Divisi Tujuan <span class="req">*</span>
                </label>
                <div class="multi-select" data-multi-select>
                    <button type="button" class="multi-select-toggle" data-multi-toggle aria-haspopup="listbox" aria-expanded="false">
                        <span class="multi-placeholder">Pilih divisi tujuan</span>
                    </button>
                    <div class="multi-panel" data-multi-panel role="listbox" aria-multiselectable="true">
                        @foreach ($divisions as $division)
                            @php $isSelected = collect(old('recipient_divisions', []))->contains($division); @endphp
                            <label class="multi-option">
                                <input type="checkbox" value="{{ $division }}" @checked($isSelected) data-multi-option>
                                <span>{{ $division }}</span>
                            </label>
                        @endforeach
                    </div>
                    <select id="recipient_divisions" name="recipient_divisions[]" class="select" multiple required hidden>
                        @foreach ($divisions as $division)
                            <option value="{{ $division }}" @selected(collect(old('recipient_divisions', []))->contains($division))>{{ $division }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="hint">Bisa pilih lebih dari satu divisi.</div>
                @error('recipient_divisions')
                    <div class="error">{{ $message }}</div>
                @enderror
                @error('recipient_divisions.*')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="mt-3">
            <label for="judul" class="field-label">
                <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                    <path d="M4 21h16"></path>
                    <path d="M7 17h10"></path>
                    <path d="M8 3h8l3 8H5z"></path>
                </svg>
                Perihal / Judul Surat <span class="req">*</span>
            </label>
            <input id="judul" name="judul" type="text" value="{{ old('judul') }}" class="input" placeholder="Contoh: Permohonan Anggaran Kegiatan..." required>
            @error('judul')
                <div class="error">{{ $message }}</div>
            @enderror
        </div>

        <div class="mt-3">
            <label for="isi" class="field-label">
                <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                    <path d="M14 2v6h6"></path>
                    <path d="M16 13H8M16 17H8M10 9H8"></path>
                </svg>
                Pesan Tambahan (Opsional)
            </label>
            <textarea id="isi" name="isi" class="textarea" placeholder="Tulis pesan tambahan untuk penerima surat...">{{ old('isi') }}</textarea>
            @error('isi')
                <div class="error">{{ $message }}</div>
            @enderror
        </div>

        <div class="mt-3">
            <label class="field-label">
                <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                    <path d="m21.44 11.05-9.19 9.19a6 6 0 0 1-8.49-8.49l9.2-9.19a4 4 0 1 1 5.65 5.66l-9.2 9.19a2 2 0 0 1-2.82-2.83l8.48-8.48"></path>
                </svg>
                Upload Surat <span class="req">*</span>
            </label>
            <div class="lampiran-row">
                <div class="lampiran-left">
                    <label class="lampiran-picker" for="lampiran">
                        <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M12 3v12"></path>
                            <path d="m7 10 5-5 5 5"></path>
                            <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                        </svg>
                        Pilih Lampiran
                    </label>
                    <div class="lampiran-note">Format: PDF, DOC, DOCX (maks 20MB)</div>
                    <div id="fileName" class="lampiran-filename"></div>
                </div>
                <div id="filePreview" class="file-preview"></div>
            </div>
            <input id="lampiran" class="hidden-file" name="lampiran" type="file" accept=".pdf,.doc,.docx,application/pdf,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document" required>
            @error('lampiran')
                <div class="error">{{ $message }}</div>
            @enderror
        </div>

        <div class="line"></div>

        <div class="actions">
            <button type="submit" class="btn btn-primary">
                <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M22 2 11 13"></path>
                    <path d="m22 2-7 20-4-9-9-4Z"></path>
                </svg>
                Kirim Surat
            </button>
            <a href="{{ route('surat.outbox') }}" class="btn btn-soft">Batal</a>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
    (function () {
        const fileInput = document.getElementById('lampiran');
        const fileName = document.getElementById('fileName');
        const filePreview = document.getElementById('filePreview');
        const multiSelect = document.querySelector('[data-multi-select]');
        let previewUrl = null;

        if (!fileInput || !fileName || !filePreview) {
            return;
        }

        function formatSize(bytes) {
            if (!bytes || bytes <= 0) {
                return '-';
            }
            const units = ['B', 'KB', 'MB', 'GB'];
            let size = bytes;
            let unitIndex = 0;
            while (size >= 1024 && unitIndex < units.length - 1) {
                size /= 1024;
                unitIndex++;
            }
            return size.toFixed(unitIndex === 0 ? 0 : 1) + ' ' + units[unitIndex];
        }

        function resetPreview() {
            if (previewUrl) {
                URL.revokeObjectURL(previewUrl);
                previewUrl = null;
            }
            filePreview.classList.remove('show');
            filePreview.innerHTML = '';
        }

        function escapeHtml(value) {
            return (value || '')
                .replace(/&/g, '&amp;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;')
                .replace(/"/g, '&quot;')
                .replace(/'/g, '&#39;');
        }

        function renderPreview(file) {
            resetPreview();
            if (!file) {
                return;
            }

            const lowerName = (file.name || '').toLowerCase();
            const isPdf = file.type === 'application/pdf' || lowerName.endsWith('.pdf');
            const isDoc = lowerName.endsWith('.doc') || lowerName.endsWith('.docx');
            const typeLabel = isPdf ? 'PDF' : (isDoc ? 'DOC/DOCX' : 'FILE');

            let previewContent = '';
            if (isPdf) {
                previewUrl = URL.createObjectURL(file);
                previewContent = ''
                    + '<div class="file-preview-card">'
                    + '  <iframe class="file-preview-frame" src="' + previewUrl + '#view=FitH"></iframe>'
                    + '  <div class="file-preview-note">Preview PDF sebelum surat dikirim.</div>'
                    + '</div>';
            } else {
                previewContent = ''
                    + '<div class="file-preview-card">'
                    + '  <div class="file-preview-note">Preview isi tidak tersedia untuk format DOC/DOCX di browser. File tetap akan terunggah.</div>'
                    + '</div>';
            }

            filePreview.innerHTML = ''
                + '<div class=\"file-preview-meta\">'
                + '  <span class=\"file-preview-icon\">'
                + '    <svg viewBox=\"0 0 24 24\" width=\"18\" height=\"18\" fill=\"none\" stroke=\"currentColor\" stroke-width=\"2\">'
                + '      <path d=\"M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z\"></path>'
                + '      <path d=\"M14 2v6h6\"></path>'
                + '      <path d=\"M16 13H8M16 17H8M10 9H8\"></path>'
                + '    </svg>'
                + '  </span>'
                + '  <span>'
                + '    <div style=\"font-size:11px; color:#64748b; font-weight:700; margin-bottom:2px;\">' + typeLabel + '</div>'
                + '    <div style=\"font-weight:700; color:#0f172a; word-break: break-word;\">' + escapeHtml(file.name) + '</div>'
                + '    <div style=\"color:#64748b; font-size:12px;\">' + formatSize(file.size) + '</div>'
                + '  </span>'
                + '</div>'
                + previewContent;
            filePreview.classList.add('show');
        }

        function setFile(file) {
            if (!file) {
                fileName.textContent = '';
                resetPreview();
                return;
            }
            fileName.textContent = 'Terpilih: ' + file.name + ' (' + formatSize(file.size) + ')';
            renderPreview(file);
        }

        fileInput.addEventListener('change', function () {
            setFile(fileInput.files && fileInput.files[0]);
        });

        window.addEventListener('beforeunload', function () {
            if (previewUrl) {
                URL.revokeObjectURL(previewUrl);
            }
        });

        if (!multiSelect) {
            return;
        }

        const toggle = multiSelect.querySelector('[data-multi-toggle]');
        const panel = multiSelect.querySelector('[data-multi-panel]');
        const checkboxes = Array.from(multiSelect.querySelectorAll('[data-multi-option]'));
        const nativeSelect = document.getElementById('recipient_divisions');

        if (!toggle || !panel || !nativeSelect || checkboxes.length === 0) {
            return;
        }

        function syncNativeSelect(values) {
            Array.from(nativeSelect.options).forEach(function (option) {
                option.selected = values.includes(option.value);
            });
        }

        function renderChips() {
            const selected = checkboxes.filter(function (item) { return item.checked; }).map(function (item) { return item.value; });
            syncNativeSelect(selected);

            if (selected.length === 0) {
                toggle.innerHTML = '<span class="multi-placeholder">Pilih divisi tujuan</span>';
                return;
            }

            toggle.innerHTML = selected.map(function (value) {
                return '<span class="chip">' + value.replace(/</g, '&lt;').replace(/>/g, '&gt;') + '</span>';
            }).join('');
        }

        checkboxes.forEach(function (checkbox) {
            checkbox.addEventListener('change', renderChips);
        });

        toggle.addEventListener('click', function () {
            const isOpen = panel.classList.toggle('open');
            toggle.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
        });

        document.addEventListener('click', function (event) {
            if (!multiSelect.contains(event.target)) {
                panel.classList.remove('open');
                toggle.setAttribute('aria-expanded', 'false');
            }
        });

        renderChips();
    })();
</script>
@endpush


@extends('layouts.app')

@section('title', 'Buat Surat')

@push('styles')
    <link rel="stylesheet" href="https://unpkg.com/trix@2.1.2/dist/trix.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/choices.js/public/assets/styles/choices.min.css">
@endpush

@section('content')
<div class="card">
    <h1>Buat Surat</h1>

    <form method="POST" action="{{ route('surat.store') }}" enctype="multipart/form-data">
        @csrf
        <div class="form-group">
            <label>Pengirim</label>
            <div class="readonly">{{ $user->division }}</div>
        </div>

        <div class="form-group">
            <label for="jenis">Jenis Surat</label>
            <select id="jenis" name="jenis" required>
                @foreach ($jenisList as $jenis)
                    <option value="{{ $jenis }}" @selected(old('jenis') === $jenis)>{{ $jenis }}</option>
                @endforeach
            </select>
            @error('jenis')
                <div class="error">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="judul">Judul</label>
            <input id="judul" name="judul" type="text" value="{{ old('judul') }}" required>
            @error('judul')
                <div class="error">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="isi">Isi Surat</label>
            <input id="isi" type="hidden" name="isi" value="{{ old('isi') }}">
            <trix-editor input="isi"></trix-editor>
            @error('isi')
                <div class="error">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="lampiran">Lampiran (opsional)</label>
            <input id="lampiran" name="lampiran" type="file">
            @error('lampiran')
                <div class="error">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="recipient_divisions">Divisi Tujuan</label>
            <select id="recipient_divisions" name="recipient_divisions[]" multiple required>
                @foreach ($divisions as $division)
                    <option value="{{ $division }}" @selected(collect(old('recipient_divisions', []))->contains($division))>{{ $division }}</option>
                @endforeach
            </select>
            <div class="muted">Bisa pilih lebih dari satu divisi.</div>
            @error('recipient_divisions')
                <div class="error">{{ $message }}</div>
            @enderror
            @error('recipient_divisions.*')
                <div class="error">{{ $message }}</div>
            @enderror
        </div>

        <button class="btn" type="submit">Kirim</button>
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
    </script>
@endpush

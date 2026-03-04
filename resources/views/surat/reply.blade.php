@extends('layouts.app')

@section('title', 'Balas Surat')

@push('styles')
    <link rel="stylesheet" href="https://unpkg.com/trix@2.1.2/dist/trix.css">
@endpush

@section('content')
<div class="card">
    <h1>Balas Surat</h1>
    <p class="muted">Balasan untuk: {{ $surat->judul }}</p>

    <form method="POST" action="{{ route('surat.reply.store', $surat) }}" enctype="multipart/form-data">
        @csrf
        <div class="form-group">
            <label>Tujuan</label>
            <div class="readonly">{{ $surat->sender_division }}</div>
        </div>

        <div class="form-group">
            <label for="judul">Judul Balasan</label>
            <input id="judul" name="judul" type="text" value="{{ old('judul', 'Balasan: ' . $surat->judul) }}" required>
            @error('judul')
                <div class="error">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="isi">Isi Balasan</label>
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

        <button class="btn" type="submit">Kirim Balasan</button>
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

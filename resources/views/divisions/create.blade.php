@extends('layouts.app')

@section('title', 'Tambah Divisi')

@section('content')
<div class="row-actions" style="margin-bottom: 16px;">
</div>
<div class="card">
    <h1>Tambah Divisi</h1>

    <form method="POST" action="{{ route('divisions.store') }}">
        @csrf
        <div class="form-group">
            <label for="name">Nama Divisi</label>
            <input id="name" name="name" type="text" value="{{ old('name') }}" required>
            @error('name')
                <div class="error">{{ $message }}</div>
            @enderror
        </div>

        <button class="btn" type="submit">Simpan</button>
    </form>
</div>
@endsection

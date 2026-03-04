@extends('layouts.app')

@section('title', 'Edit Divisi')

@section('content')
<div class="card">
    <h1>Edit Divisi</h1>

    <form method="POST" action="{{ route('divisions.update', $division) }}">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label for="name">Nama Divisi</label>
            <input id="name" name="name" type="text" value="{{ old('name', $division->name) }}" required>
            @error('name')
                <div class="error">{{ $message }}</div>
            @enderror
        </div>

        <button class="btn" type="submit">Simpan</button>
    </form>
</div>
@endsection

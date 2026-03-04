@extends('layouts.app')

@section('title', 'Tambah Akun')

@section('content')
<div class="card">
    <h1>Tambah Akun</h1>

    <form method="POST" action="{{ route('users.store') }}">
        @csrf
        <div class="form-group">
            <label for="username">Username</label>
            <input id="username" name="username" type="text" value="{{ old('username') }}" required>
            @error('username')
                <div class="error">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="division">Divisi</label>
            <select id="division" name="division">
                <option value="">- Tanpa divisi (Admin) -</option>
                @foreach ($divisions as $division)
                    <option value="{{ $division }}" @selected(old('division') === $division)>{{ $division }}</option>
                @endforeach
            </select>
            @error('division')
                <div class="error">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="role">Role</label>
            <select id="role" name="role" required>
                @foreach ($roles as $role)
                    <option value="{{ $role }}" @selected(old('role') === $role)>{{ $role }}</option>
                @endforeach
            </select>
            @error('role')
                <div class="error">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="email">Email (opsional)</label>
            <input id="email" name="email" type="email" value="{{ old('email') }}">
            @error('email')
                <div class="error">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="password">Password</label>
            <input id="password" name="password" type="password" required>
            @error('password')
                <div class="error">{{ $message }}</div>
            @enderror
        </div>

        <button class="btn" type="submit">Simpan</button>
    </form>
</div>
@endsection

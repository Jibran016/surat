@extends('layouts.app')

@section('title', 'Edit Akun')

@section('content')
<div class="card">
    <h1>Edit Akun</h1>

    <form method="POST" action="{{ route('users.update', $user) }}">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label for="username">Username</label>
            <input id="username" name="username" type="text" value="{{ old('username', $user->username) }}" required>
            @error('username')
                <div class="error">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="division">Divisi</label>
            <select id="division" name="division">
                <option value="">- Tanpa divisi (Admin) -</option>
                @foreach ($divisions as $division)
                    <option value="{{ $division }}" @selected(old('division', $user->division) === $division)>{{ $division }}</option>
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
                    <option value="{{ $role }}" @selected(old('role', $user->role) === $role)>{{ $role }}</option>
                @endforeach
            </select>
            @error('role')
                <div class="error">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="email">Email (opsional)</label>
            <input id="email" name="email" type="email" value="{{ old('email', $user->email) }}">
            @error('email')
                <div class="error">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="password">Password Baru (opsional)</label>
            <input id="password" name="password" type="password">
            @error('password')
                <div class="error">{{ $message }}</div>
            @enderror
        </div>

        <button class="btn" type="submit">Perbarui</button>
    </form>
</div>
@endsection

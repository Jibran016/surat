@extends('layouts.app')

@section('title', 'Login - Surat Menyurat Internal')

@section('content')
<div class="card login-card">
    <h1>Login</h1>
    <p class="muted">Masukkan username dan password untuk masuk dashboard.</p>

    <form method="POST" action="{{ route('login.submit') }}">
        @csrf
        <div class="form-group">
            <label for="username">Username</label>
            <input id="username" name="username" type="text" value="{{ old('username') }}" required autofocus>
            @error('username')
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

        <button class="btn" type="submit">Masuk Dashboard</button>
    </form>
</div>
@endsection

@extends('layouts.app')

@section('title', 'Login - SURATIN')

@section('content')
<div class="mx-auto max-w-lg">
    <div class="rounded-3xl border border-pink-100 bg-white/90 p-8 shadow-[0_30px_60px_-40px_rgba(236,72,153,0.45)]">
        <div class="mb-6">
            <h1 class="mt-2 text-3xl font-semibold text-slate-900">Selamat datang di SURATIN</h1>
            <p class="mt-2 text-sm text-slate-500">Masukkan email dan password untuk masuk.</p>
        </div>

        <form method="POST" action="{{ route('login.submit') }}" class="space-y-5">
            @csrf
            <div>
                <label for="email" class="text-sm font-semibold text-slate-700">Email</label>
                <input id="email" name="email" type="email" value="{{ old('email') }}" required autofocus
                    class="mt-2 w-full rounded-2xl border border-pink-100 bg-white px-4 py-3 text-sm text-slate-700 shadow-sm outline-none transition focus:border-pink-300 focus:ring-2 focus:ring-pink-200">
                @error('email')
                    <div class="mt-2 text-sm text-rose-600">{{ $message }}</div>
                @enderror
            </div>

            <div>
                <label for="password" class="text-sm font-semibold text-slate-700">Password</label>
                <input id="password" name="password" type="password" required
                    class="mt-2 w-full rounded-2xl border border-pink-100 bg-white px-4 py-3 text-sm text-slate-700 shadow-sm outline-none transition focus:border-pink-300 focus:ring-2 focus:ring-pink-200">
                @error('password')
                    <div class="mt-2 text-sm text-rose-600">{{ $message }}</div>
                @enderror
            </div>

            <button class="inline-flex w-full items-center justify-center gap-2 rounded-full bg-pink-600 px-5 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-pink-700" type="submit">
                Masuk
            </button>
        </form>
    </div>
</div>
@endsection

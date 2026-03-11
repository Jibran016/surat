@extends('layouts.app')

@section('title', 'Tambah Akun - SURATIN')

@section('content')
<div class="mx-auto max-w-2xl rounded-3xl border border-pink-100 bg-white/90 p-8 shadow-[0_30px_60px_-40px_rgba(236,72,153,0.45)]">
    <div class="mb-6">
        <h1 class="text-2xl font-semibold text-slate-900">Tambah Akun</h1>
        <p class="mt-2 text-sm text-slate-500">Buat akun baru untuk pengguna atau admin.</p>
    </div>

    <form method="POST" action="{{ route('users.store') }}" class="grid gap-5 sm:grid-cols-2">
        @csrf
        <div class="sm:col-span-1">
            <label for="username" class="text-sm font-semibold text-slate-700">Username</label>
            <input id="username" name="username" type="text" value="{{ old('username') }}" required
                class="mt-2 w-full rounded-2xl border border-pink-100 bg-white px-4 py-3 text-sm text-slate-700 shadow-sm outline-none transition focus:border-pink-300 focus:ring-2 focus:ring-pink-200">
            @error('username')
                <div class="mt-2 text-sm text-rose-600">{{ $message }}</div>
            @enderror
        </div>

        <div class="sm:col-span-1">
            <label for="division" class="text-sm font-semibold text-slate-700">Divisi</label>
            <select id="division" name="division"
                class="mt-2 w-full rounded-2xl border border-pink-200/60 bg-black/40 px-4 py-3 text-sm text-pink-100 shadow-sm outline-none transition focus:border-pink-300 focus:ring-2 focus:ring-pink-300/40">
                <option value="">- Tanpa divisi (Admin) -</option>
                @foreach ($divisions as $division)
                    <option value="{{ $division }}" @selected(old('division') === $division)>{{ $division }}</option>
                @endforeach
            </select>
            @error('division')
                <div class="mt-2 text-sm text-rose-600">{{ $message }}</div>
            @enderror
        </div>

        <div class="sm:col-span-1">
            <label for="role" class="text-sm font-semibold text-slate-700">Role</label>
            <select id="role" name="role" required
                class="mt-2 w-full rounded-2xl border border-pink-200/60 bg-black/40 px-4 py-3 text-sm text-pink-100 shadow-sm outline-none transition focus:border-pink-300 focus:ring-2 focus:ring-pink-300/40">
                @foreach ($roles as $role)
                    <option value="{{ $role }}" @selected(old('role') === $role)>{{ $role }}</option>
                @endforeach
            </select>
            @error('role')
                <div class="mt-2 text-sm text-rose-600">{{ $message }}</div>
            @enderror
        </div>

        <div class="sm:col-span-1">
            <label for="email" class="text-sm font-semibold text-slate-700">Email</label>
            <input id="email" name="email" type="email" value="{{ old('email') }}" required
                class="mt-2 w-full rounded-2xl border border-pink-100 bg-white px-4 py-3 text-sm text-slate-700 shadow-sm outline-none transition focus:border-pink-300 focus:ring-2 focus:ring-pink-200">
            @error('email')
                <div class="mt-2 text-sm text-rose-600">{{ $message }}</div>
            @enderror
        </div>

        <div class="sm:col-span-2">
            <label for="password" class="text-sm font-semibold text-slate-700">Password</label>
            <input id="password" name="password" type="password" required
                class="mt-2 w-full rounded-2xl border border-pink-100 bg-white px-4 py-3 text-sm text-slate-700 shadow-sm outline-none transition focus:border-pink-300 focus:ring-2 focus:ring-pink-200">
            @error('password')
                <div class="mt-2 text-sm text-rose-600">{{ $message }}</div>
            @enderror
        </div>

        <div class="sm:col-span-2">
            <button class="inline-flex items-center gap-2 rounded-full bg-pink-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-pink-700" type="submit">
                Simpan
            </button>
        </div>
    </form>
</div>
@endsection

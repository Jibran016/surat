@extends('layouts.app')

@section('title', 'Tambah Akun - Surat Menyurat')

@section('content')
<div class="mx-auto max-w-3xl rounded-2xl border border-slate-200 bg-white p-6">
    <h1 class="inline-flex items-center gap-2 text-2xl font-bold text-slate-900">
        <svg viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M20 8v6"></path><path d="M23 11h-6"></path></svg>
        Tambah Akun
    </h1>
    <p class="mt-1 text-sm text-slate-500">Buat akun baru untuk pengguna atau admin.</p>

    <form method="POST" action="{{ route('users.store') }}" class="mt-5 grid gap-4 sm:grid-cols-2">
        @csrf
        <div>
            <label for="username" class="text-sm font-semibold text-slate-700">Username</label>
            <input id="username" name="username" type="text" value="{{ old('username') }}" required class="mt-2 w-full rounded-xl border border-slate-300 px-3 py-2.5 text-sm focus:border-blue-500 focus:ring-blue-500">
            @error('username')<div class="mt-1 text-sm text-rose-600">{{ $message }}</div>@enderror
        </div>

        <div>
            <label for="division" class="text-sm font-semibold text-slate-700">Divisi</label>
            @if (!empty($isLimited))
                <div class="mt-2 rounded-xl border border-slate-200 bg-slate-50 px-3 py-2.5 text-sm text-slate-700">{{ auth()->user()->division }}</div>
                <input type="hidden" name="division" value="{{ auth()->user()->division }}">
            @else
                <select id="division" name="division" class="mt-2 w-full rounded-xl border border-slate-300 px-3 py-2.5 text-sm focus:border-blue-500 focus:ring-blue-500">
                    <option value="">- Tanpa divisi (Admin) -</option>
                    @foreach ($divisions as $division)
                        <option value="{{ $division }}" @selected(old('division') === $division)>{{ $division }}</option>
                    @endforeach
                </select>
            @endif
            @error('division')<div class="mt-1 text-sm text-rose-600">{{ $message }}</div>@enderror
        </div>

        <div>
            <label for="role" class="text-sm font-semibold text-slate-700">Role</label>
            @if (!empty($isLimited))
                <div class="mt-2 rounded-xl border border-slate-200 bg-slate-50 px-3 py-2.5 text-sm text-slate-700">User</div>
                <input type="hidden" name="role" value="User">
            @else
                <select id="role" name="role" required class="mt-2 w-full rounded-xl border border-slate-300 px-3 py-2.5 text-sm focus:border-blue-500 focus:ring-blue-500">
                    @foreach ($roles as $role)
                        <option value="{{ $role }}" @selected(old('role') === $role)>{{ $role }}</option>
                    @endforeach
                </select>
            @endif
            @error('role')<div class="mt-1 text-sm text-rose-600">{{ $message }}</div>@enderror
        </div>

        <div>
            <label for="email" class="text-sm font-semibold text-slate-700">Email</label>
            <input id="email" name="email" type="email" value="{{ old('email') }}" required class="mt-2 w-full rounded-xl border border-slate-300 px-3 py-2.5 text-sm focus:border-blue-500 focus:ring-blue-500">
            @error('email')<div class="mt-1 text-sm text-rose-600">{{ $message }}</div>@enderror
        </div>

        <div class="sm:col-span-2">
            <label for="password" class="text-sm font-semibold text-slate-700">Password</label>
            <input id="password" name="password" type="password" required class="mt-2 w-full rounded-xl border border-slate-300 px-3 py-2.5 text-sm focus:border-blue-500 focus:ring-blue-500">
            @error('password')<div class="mt-1 text-sm text-rose-600">{{ $message }}</div>@enderror
        </div>

        <div class="sm:col-span-2">
            <button type="submit" class="inline-flex items-center gap-1.5 rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-700">
                <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 5v14"></path><path d="M5 12h14"></path></svg>
                Simpan
            </button>
        </div>
    </form>
</div>
@endsection

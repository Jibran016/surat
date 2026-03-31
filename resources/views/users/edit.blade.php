@extends('layouts.app')

@section('title', 'Edit Akun - Surat Menyurat')

@section('content')
<div class="mx-auto max-w-3xl rounded-2xl border border-slate-200 bg-white p-6">
    <h1 class="inline-flex items-center gap-2 text-2xl font-bold text-slate-900">
        <svg viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 20h9"></path><path d="M16.5 3.5a2.1 2.1 0 0 1 3 3L7 19l-4 1 1-4Z"></path></svg>
        Edit Akun
    </h1>
    <p class="mt-1 text-sm text-slate-500">Perbarui data akun yang dipilih.</p>

    <form method="POST" action="{{ route('users.update', $user) }}" class="mt-5 grid gap-4 sm:grid-cols-2">
        @csrf
        @method('PUT')

        <div>
            <label for="username" class="text-sm font-semibold text-slate-700">Username</label>
            <input id="username" name="username" type="text" value="{{ old('username', $user->username) }}" required class="mt-2 w-full rounded-xl border border-slate-300 px-3 py-2.5 text-sm focus:border-blue-500 focus:ring-blue-500">
            @error('username')<div class="mt-1 text-sm text-rose-600">{{ $message }}</div>@enderror
        </div>

        <div>
            <label for="division" class="text-sm font-semibold text-slate-700">Divisi</label>
            <select id="division" name="division" class="mt-2 w-full rounded-xl border border-slate-300 px-3 py-2.5 text-sm focus:border-blue-500 focus:ring-blue-500">
                <option value="">- Tanpa divisi (Admin) -</option>
                @foreach ($divisions as $division)
                    <option value="{{ $division }}" @selected(old('division', $user->division) === $division)>{{ $division }}</option>
                @endforeach
            </select>
            @error('division')<div class="mt-1 text-sm text-rose-600">{{ $message }}</div>@enderror
        </div>

        <div>
            <label for="role" class="text-sm font-semibold text-slate-700">Role</label>
            <select id="role" name="role" required class="mt-2 w-full rounded-xl border border-slate-300 px-3 py-2.5 text-sm focus:border-blue-500 focus:ring-blue-500">
                @foreach ($roles as $role)
                    <option value="{{ $role }}" @selected(old('role', $user->role) === $role)>{{ $role }}</option>
                @endforeach
            </select>
            @error('role')<div class="mt-1 text-sm text-rose-600">{{ $message }}</div>@enderror
        </div>

        <div>
            <label for="email" class="text-sm font-semibold text-slate-700">Email</label>
            <input id="email" name="email" type="email" value="{{ old('email', $user->email) }}" required class="mt-2 w-full rounded-xl border border-slate-300 px-3 py-2.5 text-sm focus:border-blue-500 focus:ring-blue-500">
            @error('email')<div class="mt-1 text-sm text-rose-600">{{ $message }}</div>@enderror
        </div>

        <div class="sm:col-span-2">
            <label for="password" class="text-sm font-semibold text-slate-700">Password Baru (opsional)</label>
            <input id="password" name="password" type="password" class="mt-2 w-full rounded-xl border border-slate-300 px-3 py-2.5 text-sm focus:border-blue-500 focus:ring-blue-500">
            @error('password')<div class="mt-1 text-sm text-rose-600">{{ $message }}</div>@enderror
        </div>

        <div class="sm:col-span-2">
            <button type="submit" class="inline-flex items-center gap-1.5 rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-700">
                <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path><path d="M17 21v-8H7v8"></path></svg>
                Perbarui
            </button>
        </div>
    </form>
</div>
@endsection

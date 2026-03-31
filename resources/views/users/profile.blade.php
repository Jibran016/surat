@extends('layouts.app')

@section('title', 'Profile - Surat Menyurat')

@section('content')
<div class="mx-auto max-w-3xl rounded-2xl border border-slate-200 bg-white p-6">
    <h1 class="inline-flex items-center gap-2 text-3xl font-bold text-slate-900">
        <svg viewBox="0 0 24 24" width="28" height="28" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
            <circle cx="12" cy="7" r="4"></circle>
        </svg>
        Profile
    </h1>
    <p class="mt-1 text-sm text-slate-500">Informasi akun pengguna.</p>

    <div class="mt-6 space-y-3">
        <div class="rounded-xl border border-slate-200 bg-slate-50 p-4">
            <div class="text-xs font-semibold uppercase tracking-wide text-slate-500">Username</div>
            <div class="mt-1 text-lg font-semibold text-slate-900">{{ $user->username }}</div>
        </div>

        <div class="rounded-xl border border-slate-200 bg-slate-50 p-4">
            <div class="text-xs font-semibold uppercase tracking-wide text-slate-500">Email</div>
            <div class="mt-1 text-lg font-semibold text-slate-900">{{ $user->email }}</div>
        </div>

        <div class="rounded-xl border border-slate-200 bg-slate-50 p-4">
            <div class="text-xs font-semibold uppercase tracking-wide text-slate-500">Divisi</div>
            <div class="mt-1 text-lg font-semibold text-slate-900">{{ $user->division ?? '-' }}</div>
        </div>
    </div>
</div>
@endsection

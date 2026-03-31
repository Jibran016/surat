@extends('layouts.app')

@section('title', 'Dashboard Admin - Surat Menyurat')

@section('content')
<div class="rounded-2xl border border-slate-200 bg-white p-6">
    <div class="flex flex-wrap items-start justify-between gap-3">
        <div>
            <h1 class="inline-flex items-center gap-2 text-3xl font-bold text-slate-900">
                <svg viewBox="0 0 24 24" width="28" height="28" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 3h7v7H3z"></path><path d="M14 3h7v7h-7z"></path><path d="M14 14h7v7h-7z"></path><path d="M3 14h7v7H3z"></path></svg>
                Dashboard Admin
            </h1>
            <p class="mt-1 text-sm text-slate-500">Kelola akun, divisi, dan surat seluruh divisi.</p>
        </div>
        <div class="flex flex-wrap gap-2">
            <a href="{{ route('users.create') }}" class="inline-flex items-center gap-1.5 rounded-lg bg-blue-600 px-3 py-2 text-sm font-semibold text-white hover:bg-blue-700">
                <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M20 8v6"></path><path d="M23 11h-6"></path></svg>
                Tambah Akun
            </a>
            <a href="{{ route('divisions.create') }}" class="inline-flex items-center gap-1.5 rounded-lg border border-slate-300 px-3 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-100">
                <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 21h18"></path><path d="M5 21V7l7-4 7 4v14"></path></svg>
                Tambah Divisi
            </a>
            <a href="{{ route('dashboard.monitoring') }}" class="inline-flex items-center gap-1.5 rounded-lg border border-slate-300 px-3 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-100">
                <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 3v18h18"></path><path d="m19 9-5 5-4-4-3 3"></path></svg>
                Monitoring
            </a>
            <a href="{{ route('admin.surat.index') }}" class="inline-flex items-center gap-1.5 rounded-lg border border-slate-300 px-3 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-100">
                <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="5" width="18" height="14" rx="2"></rect><path d="m3 7 9 6 9-6"></path></svg>
                Surat Divisi
            </a>
        </div>
    </div>



    <div class="mt-5 overflow-hidden rounded-xl border border-slate-200">
        <div class="overflow-auto">
            <table class="w-full text-sm">
                <thead class="bg-slate-50 text-left text-xs font-semibold uppercase tracking-wider text-slate-600">
                    <tr>
                        <th class="px-4 py-3">Username</th>
                        <th class="px-4 py-3">Email</th>
                        <th class="px-4 py-3">Role</th>
                        <th class="px-4 py-3">Divisi</th>
                        <th class="px-4 py-3">Terdaftar</th>
                        <th class="px-4 py-3 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 bg-white">
                    @forelse ($accountDivisionRows ?? [] as $row)
                        <tr>
                            <td class="px-4 py-3 font-medium text-slate-900">{{ $row->username }}</td>
                            <td class="px-4 py-3 text-slate-600">{{ $row->email ?? '-' }}</td>
                            <td class="px-4 py-3 text-slate-600">{{ $row->role }}</td>
                            <td class="px-4 py-3 text-slate-600">{{ $row->division ?? '-' }}</td>
                            <td class="px-4 py-3 text-slate-600">{{ optional($row->created_at)?->timezone(config('app.timezone'))->format('d M Y H:i') ?? '-' }}</td>
                            <td class="px-4 py-3">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('users.edit', $row->id) }}" class="inline-flex rounded-md border border-slate-300 px-2.5 py-1 text-xs font-semibold text-slate-700 hover:bg-slate-100">Edit</a>
                                    @if (auth()->id() !== $row->id)
                                        <form method="POST" action="{{ route('users.destroy', $row->id) }}" onsubmit="return confirm('Hapus akun ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="inline-flex rounded-md border border-rose-200 bg-rose-50 px-2.5 py-1 text-xs font-semibold text-rose-700 hover:bg-rose-100">Hapus</button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-4 text-sm text-slate-500">Belum ada data akun/divisi.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

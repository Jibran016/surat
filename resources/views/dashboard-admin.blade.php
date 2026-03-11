@extends('layouts.app')

@section('title', 'Dashboard Admin - SURATIN')

@section('content')
<script src="https://unpkg.com/lucide@latest"></script>

<div class="rounded-3xl border border-pink-100 bg-white/90 p-8 shadow-[0_30px_60px_-40px_rgba(236,72,153,0.45)]">
    <div class="flex flex-col gap-2">
        <div class="text-sm font-semibold uppercase tracking-widest text-pink-600">Admin</div>
        <h1 class="text-3xl font-semibold text-slate-900">Dashboard</h1>
        <p class="text-sm text-slate-500">Kelola Akun dan Divisi.</p>
    </div>

    <div class="mt-6 flex flex-wrap gap-2">
        <a class="inline-flex items-center gap-2 rounded-full border border-pink-200 bg-white px-4 py-2 text-sm font-semibold text-pink-700 shadow-sm transition hover:bg-pink-50" href="{{ route('users.create') }}">
            <i data-lucide="users-round" class="h-4 w-4"></i>
            Tambah Akun
        </a>
        <a class="inline-flex items-center gap-2 rounded-full border border-pink-200 bg-white px-4 py-2 text-sm font-semibold text-pink-700 shadow-sm transition hover:bg-pink-50" href="{{ route('divisions.create') }}">
            <i data-lucide="building-2" class="h-4 w-4"></i>
            Tambah Divisi
        </a>
        <a class="inline-flex items-center gap-2 rounded-full border border-pink-200 bg-white px-4 py-2 text-sm font-semibold text-pink-700 shadow-sm transition hover:bg-pink-50" href="{{ route('dashboard.monitoring') }}">
            <i data-lucide="activity" class="h-4 w-4"></i>
            Monitoring
        </a>
    </div>

    <div class="mt-6 overflow-hidden rounded-2xl border border-pink-100">
        <div class="overflow-auto">
            <table class="w-full text-sm">
                <thead class="bg-pink-50/60 text-left text-xs font-semibold uppercase tracking-wider text-slate-600">
                    <tr>
                        <th class="px-4 py-3">Username</th>
                        <th class="px-4 py-3">Email</th>
                        <th class="px-4 py-3">Role</th>
                        <th class="px-4 py-3">Divisi</th>
                        <th class="px-4 py-3">No. Divisi</th>
                        <th class="px-4 py-3">Terdaftar</th>
                        <th class="px-4 py-3 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-pink-100/70 bg-white">
                    @forelse ($accountDivisionRows ?? [] as $row)
                        <tr>
                            <td class="px-4 py-3 font-medium text-slate-800">{{ $row->username }}</td>
                            <td class="px-4 py-3 text-slate-600">{{ $row->email ?? '-' }}</td>
                            <td class="px-4 py-3 text-slate-600">{{ $row->role }}</td>
                            <td class="px-4 py-3 text-slate-600">{{ $row->division ?? '-' }}</td>
                            <td class="px-4 py-3 text-slate-600">{{ $row->division_code }}</td>
                            <td class="px-4 py-3 text-slate-600">{{ optional($row->created_at)?->timezone(config('app.timezone'))->format('d M Y H:i') ?? '-' }}</td>
                            <td class="px-4 py-3">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('users.edit', $row->id) }}" class="inline-flex items-center rounded-full border border-pink-200 bg-white px-3 py-1.5 text-xs font-semibold text-pink-700 transition hover:bg-pink-50">
                                        Edit
                                    </a>
                                    @if (auth()->id() !== $row->id)
                                        <form method="POST" action="{{ route('users.destroy', $row->id) }}" onsubmit="return confirm('Hapus akun ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="inline-flex items-center rounded-full border border-rose-200 bg-rose-50 px-3 py-1.5 text-xs font-semibold text-rose-700 transition hover:bg-rose-100">
                                                Delete
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-4 py-4 text-sm text-slate-500">Belum ada data akun/divisi.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    lucide.createIcons();
</script>
@endsection

@extends('layouts.app')

@section('title', 'Surat - Surat Menyurat')

@section('content')
<div class="rounded-2xl border border-slate-200 bg-white p-6">
    <div class="mb-4 flex flex-wrap items-start justify-between gap-3">
        <div>
            <h1 class="inline-flex items-center gap-2 text-2xl font-bold text-slate-900">
                <svg viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2">
                    <rect x="3" y="5" width="18" height="14" rx="2"></rect>
                    <path d="m3 7 9 6 9-6"></path>
                </svg>
                Surat
            </h1>
            <p class="mt-1 text-sm text-slate-500">Surat masuk dan surat keluar dalam satu halaman.</p>
        </div>
        <div class="grid grid-cols-3 gap-2 text-center text-xs">
            <div class="rounded-lg border border-slate-200 bg-slate-50 px-3 py-2">
                <div class="font-semibold text-slate-500">Total</div>
                <div class="mt-0.5 text-lg font-bold text-slate-900">{{ $totalCount }}</div>
            </div>
            <div class="rounded-lg border border-slate-200 bg-slate-50 px-3 py-2">
                <div class="font-semibold text-slate-500">Masuk</div>
                <div class="mt-0.5 text-lg font-bold text-slate-900">{{ $incomingCount }}</div>
            </div>
            <div class="rounded-lg border border-slate-200 bg-slate-50 px-3 py-2">
                <div class="font-semibold text-slate-500">Keluar</div>
                <div class="mt-0.5 text-lg font-bold text-slate-900">{{ $outgoingCount }}</div>
            </div>
        </div>
    </div>

    <div class="mb-3 inline-flex rounded-full border border-slate-200 bg-slate-50 p-1 text-sm font-semibold">
        <a class="rounded-full px-3 py-1.5 {{ ($tipe ?? 'all') === 'all' ? 'bg-white text-slate-900 shadow-sm' : 'text-slate-600' }}" href="{{ route('surat.index', ['tipe' => 'all', 'q' => $search]) }}">Semua</a>
        <a class="rounded-full px-3 py-1.5 {{ ($tipe ?? 'all') === 'masuk' ? 'bg-white text-slate-900 shadow-sm' : 'text-slate-600' }}" href="{{ route('surat.index', ['tipe' => 'masuk', 'q' => $search]) }}">Masuk</a>
        <a class="rounded-full px-3 py-1.5 {{ ($tipe ?? 'all') === 'keluar' ? 'bg-white text-slate-900 shadow-sm' : 'text-slate-600' }}" href="{{ route('surat.index', ['tipe' => 'keluar', 'q' => $search]) }}">Keluar</a>
    </div>

    <form method="GET" action="{{ route('surat.index') }}" class="mb-4 flex gap-2">
        <input type="hidden" name="tipe" value="{{ $tipe }}">
        <input
            type="text"
            name="q"
            value="{{ $search }}"
            placeholder="Cari judul, nomor, pengirim, penerima..."
            class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm text-slate-700 outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-100"
        >
        <button type="submit" class="inline-flex shrink-0 items-center rounded-lg bg-slate-900 px-3 py-2 text-sm font-semibold text-white hover:bg-slate-800">
            Cari
        </button>
    </form>

    <div class="overflow-hidden rounded-xl border border-slate-200">
        <div class="overflow-auto">
            <table class="w-full text-sm">
                <thead class="bg-slate-50 text-left text-xs font-semibold uppercase tracking-wider text-slate-600">
                    <tr>
                        <th class="px-4 py-3">Tanggal</th>
                        <th class="px-4 py-3">Tipe</th>
                        <th class="px-4 py-3">Nomor</th>
                        <th class="px-4 py-3">Pengirim</th>
                        <th class="px-4 py-3">Penerima</th>
                        <th class="px-4 py-3">Jenis</th>
                        <th class="px-4 py-3">Judul</th>
                        <th class="px-4 py-3 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 bg-white">
                    @forelse ($surats as $surat)
                        @php $isOutgoing = $surat->sender_user_id === auth()->id(); @endphp
                        <tr>
                            <td class="px-4 py-3 text-slate-600">{{ optional($surat->sent_at)?->timezone(config('app.timezone'))->format('d M Y H:i') ?? '-' }}</td>
                            <td class="px-4 py-3">
                                <span class="inline-flex rounded-full px-2.5 py-1 text-xs font-semibold {{ $isOutgoing ? 'bg-emerald-100 text-emerald-700' : 'bg-blue-100 text-blue-700' }}">
                                    {{ $isOutgoing ? 'Keluar' : 'Masuk' }}
                                </span>
                            </td>
                            <td class="px-4 py-3 font-medium text-slate-900">{{ $surat->nomor_surat ?? '-' }}</td>
                            <td class="px-4 py-3 text-slate-700">{{ $surat->sender_division }}</td>
                            <td class="px-4 py-3 text-slate-700">{{ $surat->recipient_division }}</td>
                            <td class="px-4 py-3 text-slate-700">{{ $surat->jenis }}</td>
                            <td class="px-4 py-3 font-medium text-slate-900">{{ $surat->judul }}</td>
                            <td class="px-4 py-3 text-right">
                                <a class="inline-flex items-center gap-1 text-sm font-semibold text-slate-900 hover:text-blue-700" href="{{ route('surat.show', $surat) }}">
                                    Detail
                                    <svg viewBox="0 0 24 24" width="12" height="12" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="m9 18 6-6-6-6"></path>
                                    </svg>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-4 py-5 text-sm text-slate-500">Belum ada data surat.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if ($surats->hasPages())
        <div class="mt-4">{{ $surats->links() }}</div>
    @endif
</div>
@endsection

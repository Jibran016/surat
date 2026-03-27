@extends('layouts.app')

@section('title', 'Surat Masuk - SURATIN')

@section('content')
<div class="rounded-3xl border border-pink-100 bg-white/90 p-8 shadow-[0_30px_60px_-40px_rgba(236,72,153,0.45)]">
    <div class="mb-6">
        <h1 class="text-2xl font-semibold text-slate-900">Surat Masuk</h1>
        <p class="mt-2 text-sm text-slate-500">Daftar surat yang diterima oleh divisi Anda.</p>
    </div>

    @if ($unreadSurats->isEmpty())
        <p class="text-sm text-slate-500">Belum ada surat masuk.</p>
    @else
        <div class="space-y-6">
            <div>
                <h2 class="mb-3 text-sm font-semibold uppercase tracking-wider text-pink-600">Surat Masuk</h2>
                <div class="overflow-hidden rounded-2xl border border-pink-100">
                    <table class="w-full text-sm">
                        <thead class="bg-pink-50/60 text-left text-xs font-semibold uppercase tracking-wider text-slate-600">
                            <tr>
                                <th class="px-4 py-3">Tanggal</th>
                                <th class="px-4 py-3">Nomor</th>
                                <th class="px-4 py-3">Dari</th>
                                <th class="px-4 py-3">Judul</th>
                                <th class="px-4 py-3 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-pink-100/70 bg-white">
                            @forelse ($unreadSurats as $surat)
                                <tr>
                                    <td class="px-4 py-3 text-slate-600">{{ optional($surat->sent_at)?->timezone(config('app.timezone'))->format('d M Y H:i') ?? '-' }}</td>
                                    <td class="px-4 py-3 font-medium text-slate-800">{{ $surat->nomor_surat ?? '-' }}</td>
                                    <td class="px-4 py-3 text-slate-600">{{ $surat->sender_division }}</td>
                                    <td class="px-4 py-3 font-medium text-slate-800">{{ $surat->judul }}</td>
                                    <td class="px-4 py-3 text-right">
                                        <a class="text-sm font-semibold text-pink-700 hover:text-pink-800" href="{{ route('surat.show', $surat) }}">Detail</a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-4 py-4 text-sm text-slate-500">Tidak ada surat belum dibaca.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    @endif
</div>
@endsection

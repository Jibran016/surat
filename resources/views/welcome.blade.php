<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SURATIN</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=plus-jakarta-sans:400,500,600,700" rel="stylesheet" />

    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    @endif
</head>
<body class="min-h-screen bg-gradient-to-b from-rose-50 via-pink-50 to-white text-slate-800" style="font-family: 'Plus Jakarta Sans', ui-sans-serif, system-ui, sans-serif;">
    <header class="border-b border-pink-100/70 bg-white/80 backdrop-blur">
        <div class="mx-auto flex w-full max-w-6xl items-center justify-between px-4 py-5 sm:px-6 lg:px-8">
            <div class="flex items-center gap-3">
                <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-gradient-to-br from-pink-500 to-rose-500 text-xl font-bold text-white shadow-lg shadow-pink-500/30">
                    S
                </div>
                <div>
                    <div class="text-xl font-semibold tracking-wide">SURATIN</div>
                    <div class="text-xs font-medium text-pink-600">Sistem Surat Internal</div>
                </div>
            </div>
            @if (Route::has('login'))
                <a class="inline-flex items-center gap-2 rounded-full bg-pink-600 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-pink-700" href="{{ route('login') }}">
                    Masuk Dashboard
                </a>
            @endif
        </div>
    </header>

    <main class="mx-auto w-full max-w-6xl px-4 py-12 sm:px-6 lg:px-8">
        <div class="grid items-center gap-10 lg:grid-cols-[1.1fr_0.9fr]">
            <div>
                <div class="inline-flex items-center gap-2 rounded-full border border-pink-200 bg-white px-3 py-1 text-xs font-semibold text-pink-700 shadow-sm">
                    Platform Surat Internal
                </div>
                <h1 class="mt-5 text-4xl font-semibold leading-tight text-slate-900 sm:text-5xl">
                    Kelola surat internal dengan cepat, rapi, dan aman.
                </h1>
                <p class="mt-4 text-base text-slate-600">
                    SURATIN membantu tim Anda mengirim, melacak, dan mengarsipkan surat antar divisi dalam satu
                    dashboard yang modern dan mudah digunakan.
                </p>
                <div class="mt-6 flex flex-wrap items-center gap-3">
                    @if (Route::has('login'))
                        <a class="inline-flex items-center gap-2 rounded-full bg-pink-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-pink-700" href="{{ route('login') }}">
                            Mulai Sekarang
                        </a>
                    @endif
                    <div class="text-sm font-medium text-slate-500">Tema profesional dengan nuansa pink modern.</div>
                </div>
            </div>
            <div class="rounded-3xl border border-pink-100 bg-white/90 p-6 shadow-[0_30px_60px_-40px_rgba(236,72,153,0.45)]">
                <div class="rounded-2xl bg-gradient-to-br from-pink-500 via-rose-500 to-rose-600 p-6 text-white">
                    <div class="text-sm font-semibold uppercase tracking-widest text-pink-100">Dashboard Preview</div>
                    <div class="mt-3 text-2xl font-semibold">SURATIN</div>
                    <p class="mt-2 text-sm text-pink-100">
                        Pantau surat masuk, keluar, dan arsip dalam satu tempat.
                    </p>
                    <div class="mt-6 grid gap-3 sm:grid-cols-3">
                        <div class="rounded-2xl bg-white/15 p-3">
                            <div class="text-xl font-semibold">24</div>
                            <div class="text-xs text-pink-100">Masuk</div>
                        </div>
                        <div class="rounded-2xl bg-white/15 p-3">
                            <div class="text-xl font-semibold">18</div>
                            <div class="text-xs text-pink-100">Keluar</div>
                        </div>
                        <div class="rounded-2xl bg-white/15 p-3">
                            <div class="text-xl font-semibold">56</div>
                            <div class="text-xs text-pink-100">Arsip</div>
                        </div>
                    </div>
                </div>
                <div class="mt-6 grid gap-4">
                    <div class="flex items-center gap-3 rounded-2xl border border-pink-100 bg-white p-4 shadow-sm">
                        <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-pink-100 text-pink-700">
                            <span class="text-sm font-semibold">SM</span>
                        </div>
                        <div>
                            <div class="text-sm font-semibold text-slate-900">Surat masuk terbaru</div>
                            <div class="text-xs text-slate-500">Notifikasi real-time untuk setiap divisi.</div>
                        </div>
                    </div>
                    <div class="flex items-center gap-3 rounded-2xl border border-pink-100 bg-white p-4 shadow-sm">
                        <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-rose-100 text-rose-600">
                            <span class="text-sm font-semibold">PDF</span>
                        </div>
                        <div>
                            <div class="text-sm font-semibold text-slate-900">Export dokumen</div>
                            <div class="text-xs text-slate-500">Unduh surat dalam format PDF resmi.</div>
                        </div>
                    </div>
                    <div class="flex items-center gap-3 rounded-2xl border border-pink-100 bg-white p-4 shadow-sm">
                        <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-fuchsia-100 text-fuchsia-600">
                            <span class="text-sm font-semibold">AR</span>
                        </div>
                        <div>
                            <div class="text-sm font-semibold text-slate-900">Arsip terstruktur</div>
                            <div class="text-xs text-slate-500">Cari surat yang sudah selesai diproses.</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</body>
</html>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'SURATIN')</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=plus-jakarta-sans:400,500,600,700" rel="stylesheet" />

    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    @endif
    <style>
        :root {
            --neon-pink: #ff2bd6;
            --neon-pink-soft: #ff7ae8;
            --ink-black: #09070f;
            --panel-black: #13101b;
            --panel-black-2: #191523;
            --text-main: #f5f2ff;
            --text-muted: #c8bdd9;
        }

        body {
            color: var(--text-main);
            background: linear-gradient(145deg, #07060d 0%, #0c0914 45%, #120a16 100%);
        }

        .bg-white,
        .bg-white\/90,
        .bg-white\/80,
        .bg-white\/78 {
            background: linear-gradient(165deg, rgba(26, 21, 36, 0.96), rgba(17, 13, 26, 0.94)) !important;
        }

        .text-slate-900,
        .text-slate-800,
        .text-slate-700,
        .text-slate-600 {
            color: var(--text-main) !important;
        }

        .text-slate-500,
        .text-slate-400 {
            color: var(--text-muted) !important;
        }

        .text-pink-600,
        .text-pink-700,
        .text-pink-800 {
            color: var(--neon-pink-soft) !important;
        }

        .border-pink-100,
        .border-pink-100\/70,
        .border-pink-200 {
            border-color: rgba(255, 43, 214, 0.36) !important;
        }

        .bg-pink-50,
        .bg-pink-50\/60,
        .bg-pink-100,
        .bg-pink-100\/35,
        .bg-pink-50\/60 {
            background-color: rgba(255, 43, 214, 0.14) !important;
        }

        .bg-rose-100,
        .bg-rose-200,
        .bg-rose-100\/35,
        .bg-rose-100\/45 {
            background-color: rgba(255, 43, 214, 0.16) !important;
        }

        .bg-pink-600 {
            background: linear-gradient(120deg, #ff2bd6, #ff5be2 58%, #ff87ea) !important;
            color: #19051a !important;
            box-shadow: 0 10px 30px -12px rgba(255, 43, 214, 0.75) !important;
        }

        .hover\:bg-pink-700:hover {
            background: linear-gradient(120deg, #ff52e1, #ff84eb) !important;
        }

        .from-pink-500.to-rose-500,
        .from-pink-600.to-rose-600 {
            background-image: linear-gradient(120deg, #ff2bd6, #ff73ea) !important;
        }

        .shadow-sm,
        .shadow-lg {
            box-shadow: 0 16px 34px -24px rgba(255, 43, 214, 0.58) !important;
        }

        select {
            background: linear-gradient(165deg, rgba(26, 21, 36, 0.96), rgba(17, 13, 26, 0.94)) !important;
            border-color: rgba(255, 43, 214, 0.36) !important;
            color: var(--text-main) !important;
        }

        select:focus {
            border-color: rgba(255, 122, 232, 0.72) !important;
            box-shadow: 0 0 0 2px rgba(255, 43, 214, 0.2) !important;
        }

        select option {
            background: #13101b;
            color: #f5f2ff;
        }

        .prose {
            color: var(--text-main);
        }

        @media (min-width: 1024px) {
            main[data-app-main] {
                transition: padding-left 220ms ease;
            }

            body.sidebar-open main[data-app-main] {
                padding-left: 19rem;
            }
        }

        @media (prefers-reduced-motion: no-preference) {
            body.page-enter {
                opacity: 0;
                transform: translateY(6px);
            }

            body.page-enter-active {
                opacity: 1;
                transform: translateY(0);
                transition: opacity 220ms ease, transform 220ms ease;
            }

            body.page-leave {
                opacity: 0;
                transform: translateY(4px);
                transition: opacity 170ms ease, transform 170ms ease;
            }
        }

        body.dashboard-full main[data-app-main] {
            padding-top: 1.25rem;
            padding-bottom: 1.25rem;
            max-width: 86rem;
        }

        @media (min-width: 1024px) {
            body.dashboard-full main[data-app-main] {
                padding-top: 1rem;
                padding-bottom: 1rem;
            }
        }
    </style>

    @stack('styles')
</head>
<body class="relative min-h-screen overflow-x-hidden bg-rose-50 text-slate-800 page-enter {{ request()->routeIs('dashboard') ? 'dashboard-full' : '' }}" style="font-family: 'Plus Jakarta Sans', ui-sans-serif, system-ui, sans-serif;">
    <div class="pointer-events-none fixed inset-0 -z-10 overflow-hidden" aria-hidden="true">
        <div class="absolute inset-0" style="background:
            radial-gradient(circle at 12% 24%, rgba(255, 43, 214, 0.24) 0%, transparent 28%),
            radial-gradient(circle at 88% 14%, rgba(255, 43, 214, 0.22) 0%, transparent 26%),
            radial-gradient(circle at 78% 78%, rgba(255, 43, 214, 0.16) 0%, transparent 30%);">
        </div>
        <div class="absolute -left-10 top-12 hidden h-32 w-32 rounded-full border border-pink-200/70 bg-pink-100/20 lg:block"></div>
        <div class="absolute left-[9%] top-[18%] hidden h-14 w-14 rounded-full border border-pink-200/60 bg-pink-100/20 lg:block"></div>
        <div class="absolute left-[3%] top-[42%] hidden h-24 w-24 rounded-full border border-pink-200/60 bg-pink-100/15 lg:block"></div>
        <div class="absolute left-[11%] top-[57%] hidden h-10 w-10 rounded-full border border-pink-200/60 bg-pink-100/15 lg:block"></div>
        <div class="absolute left-[4%] bottom-[14%] hidden h-20 w-20 rounded-full border border-pink-200/70 bg-pink-100/20 lg:block"></div>
        <div class="absolute left-[14%] bottom-[7%] hidden h-12 w-12 rounded-full border border-pink-200/60 bg-pink-100/15 lg:block"></div>

        <div class="absolute -right-10 top-[10%] hidden h-32 w-32 rounded-full border border-pink-200/70 bg-pink-100/20 lg:block"></div>
        <div class="absolute right-[8%] top-[24%] hidden h-12 w-12 rounded-full border border-pink-200/60 bg-pink-100/18 lg:block"></div>
        <div class="absolute right-[2%] top-[38%] hidden h-24 w-24 rounded-full border border-pink-200/60 bg-pink-100/16 lg:block"></div>
        <div class="absolute right-[12%] top-[54%] hidden h-16 w-16 rounded-full border border-pink-200/60 bg-pink-100/18 lg:block"></div>
        <div class="absolute right-[3%] bottom-[16%] hidden h-20 w-20 rounded-full border border-pink-200/70 bg-pink-100/20 lg:block"></div>
        <div class="absolute right-[13%] bottom-[9%] hidden h-11 w-11 rounded-full border border-pink-200/60 bg-pink-100/15 lg:block"></div>
    </div>

    <header class="sticky top-0 z-40 border-b border-pink-100/70 bg-white/78 backdrop-blur">
        <div class="mx-auto flex w-full max-w-6xl items-center justify-between px-4 py-4 sm:px-6 lg:px-8">
            <a class="flex items-center gap-3" href="{{ route('dashboard') }}">
                <div class="flex h-11 w-11 items-center justify-center rounded-2xl bg-gradient-to-br from-pink-500 to-rose-500 text-lg font-bold text-white shadow-lg shadow-pink-500/30">
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" aria-hidden="true">
                        <path d="M4 7.5A2.5 2.5 0 0 1 6.5 5h11A2.5 2.5 0 0 1 20 7.5v9A2.5 2.5 0 0 1 17.5 19h-11A2.5 2.5 0 0 1 4 16.5v-9z"></path>
                        <path d="m5 7 7 5 7-5"></path>
                    </svg>
                </div>
                <div>
                    <div class="text-lg font-semibold tracking-wide">SURATIN</div>
                    <div class="text-xs font-medium text-pink-600">Sistem Surat Internal</div>
                </div>
            </a>
            <div class="flex items-center gap-3">
                @guest
                    @if (Route::has('login') && !request()->routeIs('login'))
                        <a class="inline-flex items-center gap-2 rounded-full border border-pink-200 bg-white px-4 py-2 text-sm font-semibold text-pink-700 shadow-sm transition hover:bg-pink-50" href="{{ route('login') }}">
                            Masuk
                        </a>
                    @endif
                @endguest
                @auth
                    @if (auth()->user()->role !== 'Admin')
                        <div class="relative" data-notif-dropdown>
                            <button type="button" class="relative inline-flex items-center justify-center rounded-full border border-pink-200 bg-white p-2 text-pink-700 shadow-sm transition hover:bg-pink-50" aria-label="Notifikasi" data-notif-toggle aria-expanded="false">
                                <svg class="h-5 w-5" viewBox="0 0 24 24" aria-hidden="true" fill="none" stroke="currentColor" stroke-width="1.8">
                                    <path d="M12 3c-3.3 0-6 2.7-6 6v3.2l-1.6 2.7c-.4.7.1 1.6.9 1.6h13.4c.8 0 1.3-.9.9-1.6L18 12.2V9c0-3.3-2.7-6-6-6z"></path>
                                    <path d="M9.6 19a2.5 2.5 0 0 0 4.8 0H9.6z"></path>
                                </svg>
                                @if (!empty($globalNotifCount) && $globalNotifCount > 0)
                                    <span class="absolute -right-1 -top-1 flex h-5 min-w-[20px] items-center justify-center rounded-full bg-pink-600 px-1 text-[11px] font-semibold text-white">
                                        {{ $globalNotifCount }}
                                    </span>
                                @endif
                            </button>

                            <div class="absolute right-0 z-50 mt-2 hidden w-[min(92vw,26rem)] rounded-2xl border border-pink-200/60 bg-gradient-to-b from-[#1a1524]/95 to-[#110d1a]/95 p-2 shadow-lg backdrop-blur" data-notif-menu>
                                <div class="mb-1 px-3 py-2 text-xs font-semibold uppercase tracking-wider text-pink-200">Notifikasi</div>
                                @if (!empty($globalNotifItems) && $globalNotifItems->count() > 0)
                                    <div class="max-h-96 space-y-2 overflow-y-auto px-1 pb-1">
                                        @foreach ($globalNotifItems as $notif)
                                            <div class="rounded-xl px-3 py-2 {{ empty($notif->read_at) ? 'border border-pink-200/50 bg-black/35' : 'border border-pink-200/25 bg-black/20 opacity-70' }}">
                                                <div class="flex items-start justify-between gap-3">
                                                    <div class="min-w-0">
                                                        <div class="truncate text-sm font-semibold {{ empty($notif->read_at) ? 'text-pink-100' : 'text-pink-100/70' }}">{{ $notif->message }}</div>
                                                        <div class="mt-1 text-xs {{ empty($notif->read_at) ? 'text-pink-100/65' : 'text-pink-100/45' }}">{{ optional($notif->created_at)?->timezone(config('app.timezone'))->format('d M Y H:i') }}</div>
                                                    </div>
                                                    @if (empty($notif->read_at))
                                                        <span class="mt-0.5 inline-block h-2.5 w-2.5 rounded-full bg-pink-500"></span>
                                                    @endif
                                                </div>
                                                <form method="POST" action="{{ route('notifications.open', $notif) }}" class="mt-2">
                                                    @csrf
                                                    <button type="submit" class="text-xs font-semibold {{ empty($notif->read_at) ? 'text-pink-200 hover:text-pink-100' : 'text-pink-100/60 hover:text-pink-100' }}">Buka Surat</button>
                                                </form>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <div class="px-3 py-3 text-sm text-pink-100/70">Belum ada notifikasi.</div>
                                @endif
                            </div>
                        </div>
                    @endif
                    <div class="hidden text-right sm:block">
                        <div class="text-sm font-semibold">{{ auth()->user()->username }}</div>
                        <div class="text-xs text-slate-500">{{ auth()->user()->division ?? 'Tanpa Divisi' }} &middot; {{ auth()->user()->role }}</div>
                    </div>
                    <a href="{{ route('logout') }}" class="inline-flex items-center gap-2 rounded-full border border-pink-200 bg-white px-4 py-2 text-sm font-semibold text-pink-700 shadow-sm transition hover:bg-pink-50">Logout</a>
                @endauth
            </div>
        </div>
    </header>

    @auth
        @if (auth()->user()->role !== 'Admin')
            <div class="fixed left-3 top-24 z-50" data-sidebar-trigger-wrap>
                <button type="button" class="inline-flex h-12 w-12 items-center justify-center rounded-2xl border border-pink-200/70 bg-black/45 text-pink-100 shadow-sm backdrop-blur transition hover:bg-pink-500/20" data-sidebar-trigger aria-label="Menu Sidebar">
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                        <path d="M4 7h16"></path>
                        <path d="M4 12h16"></path>
                        <path d="M4 17h16"></path>
                    </svg>
                </button>
            </div>

            <aside class="fixed left-0 top-0 z-50 h-screen w-[290px] -translate-x-full border-r border-pink-200/40 bg-gradient-to-b from-[#1a1524]/95 to-[#110d1a]/95 p-5 shadow-[0_24px_50px_-36px_rgba(236,72,153,0.65)] backdrop-blur transition-transform duration-300" data-sidebar-panel>
                <div class="mb-4 flex items-center justify-between">
                    <div class="text-xs font-semibold uppercase tracking-[0.2em] text-pink-200">Menu</div>
                    <button type="button" class="inline-flex h-8 w-8 items-center justify-center rounded-xl border border-pink-200/50 bg-black/30 text-pink-100 hover:bg-pink-500/20" data-sidebar-close aria-label="Tutup Sidebar">
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                            <path d="M18 6 6 18"></path>
                            <path d="m6 6 12 12"></path>
                        </svg>
                    </button>
                </div>

                <div class="rounded-2xl border border-pink-200/60 bg-black/35 p-4">
                    <div class="text-xs font-semibold uppercase tracking-[0.2em] text-pink-200">User Panel</div>
                    <div class="mt-2 text-lg font-semibold text-pink-100">{{ auth()->user()->username }}</div>
                    <div class="mt-1 text-xs text-pink-100/70">{{ auth()->user()->division ?? 'Tanpa Divisi' }}</div>
                </div>

                <nav class="mt-5 space-y-2">
                    <a href="{{ route('dashboard') }}" class="flex items-center gap-3 rounded-2xl border border-pink-200/60 bg-pink-500/20 px-4 py-3 text-sm font-semibold text-pink-100">
                        <span class="inline-flex h-8 w-8 items-center justify-center rounded-xl bg-pink-500/20 text-pink-100">
                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                                <path d="M3 11l9-7 9 7"></path>
                                <path d="M5 10v9h14v-9"></path>
                            </svg>
                        </span>
                        Dashboard
                    </a>
                    <a href="{{ route('surat.inbox') }}" class="flex items-center gap-3 rounded-2xl border border-pink-200/40 bg-black/25 px-4 py-3 text-sm font-semibold text-pink-100/90 transition hover:bg-pink-500/15">
                        <span class="inline-flex h-8 w-8 items-center justify-center rounded-xl bg-pink-500/20 text-pink-100">
                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                                <path d="M4 4h16v10H8l-4 4z"></path>
                                <path d="M8 9h8"></path>
                            </svg>
                        </span>
                        Surat Masuk
                    </a>
                    <a href="{{ route('surat.outbox') }}" class="flex items-center gap-3 rounded-2xl border border-pink-200/40 bg-black/25 px-4 py-3 text-sm font-semibold text-pink-100/90 transition hover:bg-pink-500/15">
                        <span class="inline-flex h-8 w-8 items-center justify-center rounded-xl bg-pink-500/20 text-pink-100">
                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                                <path d="M4 12l16-8-6 16-3-6-7-2z"></path>
                            </svg>
                        </span>
                        Surat Keluar
                    </a>
                    <a href="{{ route('surat.create') }}" class="flex items-center gap-3 rounded-2xl border border-pink-200/40 bg-black/25 px-4 py-3 text-sm font-semibold text-pink-100/90 transition hover:bg-pink-500/15">
                        <span class="inline-flex h-8 w-8 items-center justify-center rounded-xl bg-pink-500/20 text-pink-100">
                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                                <path d="M12 5v14"></path>
                                <path d="M5 12h14"></path>
                            </svg>
                        </span>
                        Buat Surat
                    </a>
                </nav>

                <div class="mt-5 rounded-2xl border border-pink-200/40 bg-black/20 p-4">
                    <div class="text-xs font-semibold uppercase tracking-wider text-pink-200">Ringkas</div>
                    <div class="mt-3 space-y-2 text-xs text-pink-100/80">
                        <div class="flex items-center justify-between">
                            <span>Surat Masuk</span>
                            <span class="font-semibold text-pink-100">{{ $sidebarMasukCount ?? 0 }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span>Surat Keluar</span>
                            <span class="font-semibold text-pink-100">{{ $sidebarKeluarCount ?? 0 }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span>Arsip</span>
                            <span class="font-semibold text-pink-100">{{ $sidebarArsipCount ?? 0 }}</span>
                        </div>
                    </div>
                </div>
            </aside>
        @endif
    @endauth

    <main data-app-main class="mx-auto w-full max-w-6xl px-4 py-8 sm:px-6 lg:px-8">
        @auth
            <div class="mb-6 flex flex-wrap items-center gap-3">
                @if (!request()->routeIs('dashboard') && url()->previous() !== url()->current())
                    <a aria-label="Kembali" title="Kembali" class="inline-flex items-center justify-center rounded-full border border-pink-200 bg-white p-2.5 text-pink-700 shadow-sm transition hover:bg-pink-50" href="{{ url()->previous() }}">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd" d="M17 10a.75.75 0 0 1-.75.75H5.56l3.22 3.22a.75.75 0 1 1-1.06 1.06l-4.5-4.5a.75.75 0 0 1 0-1.06l4.5-4.5a.75.75 0 0 1 1.06 1.06L5.56 9.25h10.69A.75.75 0 0 1 17 10Z" clip-rule="evenodd" />
                        </svg>
                    </a>
                    <a class="inline-flex items-center gap-2 rounded-full bg-pink-600 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-pink-700" href="{{ route('dashboard') }}">Dashboard</a>
                @endif
            </div>
        @endauth
        @if (session('status'))
            <div class="mb-6 rounded-2xl border border-pink-100 bg-white/90 p-4 text-sm font-medium text-pink-700 shadow-sm">
                {{ session('status') }}
            </div>
        @endif
        @yield('content')
    </main>

    @stack('scripts')
    <script>
        (function () {
            requestAnimationFrame(function () {
                document.body.classList.add('page-enter-active');
            });

            window.addEventListener('pageshow', function () {
                document.body.classList.remove('page-leave');
                document.body.classList.add('page-enter-active');
            });

            document.addEventListener('click', function (event) {
                const link = event.target.closest('a[href]');
                if (!link) {
                    return;
                }

                const href = link.getAttribute('href') || '';
                const target = link.getAttribute('target');
                const isModified = event.metaKey || event.ctrlKey || event.shiftKey || event.altKey;
                const isInternal = href.startsWith('/') || href.startsWith('{{ url('/') }}');
                const isHash = href.startsWith('#');
                const isDownload = link.hasAttribute('download');

                if (event.defaultPrevented || isModified || target === '_blank' || isHash || isDownload || !isInternal) {
                    return;
                }

                event.preventDefault();
                document.body.classList.add('page-leave');

                setTimeout(function () {
                    window.location.href = link.href;
                }, 170);
            });
        })();
    </script>
    @auth
        <script>
            (function () {
                const dropdown = document.querySelector('[data-notif-dropdown]');
                if (!dropdown) {
                    return;
                }

                const toggle = dropdown.querySelector('[data-notif-toggle]');
                const menu = dropdown.querySelector('[data-notif-menu]');
                if (!toggle || !menu) {
                    return;
                }

                function closeMenu() {
                    menu.classList.add('hidden');
                    toggle.setAttribute('aria-expanded', 'false');
                }

                function openMenu() {
                    menu.classList.remove('hidden');
                    toggle.setAttribute('aria-expanded', 'true');
                }

                toggle.addEventListener('click', function () {
                    const isHidden = menu.classList.contains('hidden');
                    if (isHidden) {
                        openMenu();
                    } else {
                        closeMenu();
                    }
                });

                document.addEventListener('click', function (event) {
                    if (!dropdown.contains(event.target)) {
                        closeMenu();
                    }
                });

                const sidebarPanel = document.querySelector('[data-sidebar-panel]');
                const sidebarTriggerWrap = document.querySelector('[data-sidebar-trigger-wrap]');
                const sidebarTrigger = document.querySelector('[data-sidebar-trigger]');
                const sidebarClose = document.querySelector('[data-sidebar-close]');

                if (sidebarPanel && sidebarTrigger) {
                    let hideTimer = null;

                    const openSidebar = () => {
                        if (hideTimer) {
                            clearTimeout(hideTimer);
                            hideTimer = null;
                        }
                        sidebarPanel.classList.remove('-translate-x-full');
                        document.body.classList.add('sidebar-open');
                    };

                    const closeSidebar = () => {
                        sidebarPanel.classList.add('-translate-x-full');
                        document.body.classList.remove('sidebar-open');
                    };

                    const delayCloseSidebar = () => {
                        hideTimer = setTimeout(closeSidebar, 180);
                    };

                    sidebarTrigger.addEventListener('mouseenter', openSidebar);
                    sidebarPanel.addEventListener('mouseenter', openSidebar);
                    sidebarPanel.addEventListener('mouseleave', delayCloseSidebar);

                    if (sidebarTriggerWrap) {
                        sidebarTriggerWrap.addEventListener('mouseleave', delayCloseSidebar);
                    }

                    sidebarTrigger.addEventListener('click', function () {
                        if (sidebarPanel.classList.contains('-translate-x-full')) {
                            openSidebar();
                        } else {
                            closeSidebar();
                        }
                    });

                    if (sidebarClose) {
                        sidebarClose.addEventListener('click', closeSidebar);
                    }

                    document.addEventListener('click', function (event) {
                        const inPanel = sidebarPanel.contains(event.target);
                        const inTrigger = sidebarTriggerWrap && sidebarTriggerWrap.contains(event.target);
                        if (!inPanel && !inTrigger) {
                            closeSidebar();
                        }
                    });
                }
            })();
        </script>
    @endauth
</body>
</html>


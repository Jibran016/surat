<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'SURATIN')</title>
    <link rel="icon" type="image/svg+xml" href="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='64' height='64' viewBox='0 0 64 64'%3E%3Crect width='64' height='64' rx='14' fill='white'/%3E%3Crect x='6' y='6' width='52' height='52' rx='12' fill='%2316a34a'/%3E%3Cpath d='M16 22h32v20H16z' fill='white'/%3E%3Cpath d='M16 22l16 12 16-12' fill='none' stroke='%23ef4444' stroke-width='3'/%3E%3C/svg%3E">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=plus-jakarta-sans:400,500,600,700" rel="stylesheet" />

    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    @endif
    <style>
        :root {
            --accent-primary: #16a34a;
            --accent-primary-dark: #15803d;
            --accent-soft: rgba(22, 163, 74, 0.12);
            --accent-soft-strong: rgba(22, 163, 74, 0.2);
            --accent-success: #16a34a;
            --accent-success-soft: rgba(22, 163, 74, 0.12);
            --accent-danger: #ef4444;
            --accent-danger-soft: rgba(239, 68, 68, 0.12);
            --tri-red-soft: rgba(239, 68, 68, 0.08);
            --tri-green-soft: rgba(22, 163, 74, 0.08);
            --surface: #ffffff;
            --surface-soft: #f8fafc;
            --surface-tint: #f8fafc;
            --text-main: #0f172a;
            --text-muted: #5b6b7c;
            --border-soft: rgba(15, 23, 42, 0.12);
        }

        body {
            color: var(--text-main);
            background:
                radial-gradient(circle at 10% 16%, rgba(22, 163, 74, 0.16) 0 60px, transparent 88px),
                radial-gradient(circle at 22% 40%, rgba(239, 68, 68, 0.12) 0 90px, transparent 126px),
                radial-gradient(circle at 38% 18%, rgba(22, 163, 74, 0.1) 0 120px, transparent 160px),
                radial-gradient(circle at 62% 12%, rgba(239, 68, 68, 0.14) 0 70px, transparent 108px),
                radial-gradient(circle at 80% 26%, rgba(22, 163, 74, 0.12) 0 110px, transparent 150px),
                radial-gradient(circle at 16% 72%, rgba(239, 68, 68, 0.1) 0 140px, transparent 185px),
                radial-gradient(circle at 44% 78%, rgba(22, 163, 74, 0.14) 0 80px, transparent 120px),
                radial-gradient(circle at 70% 70%, rgba(239, 68, 68, 0.12) 0 130px, transparent 178px),
                radial-gradient(circle at 86% 88%, rgba(22, 163, 74, 0.1) 0 95px, transparent 140px),
                radial-gradient(circle at 6% 88%, rgba(239, 68, 68, 0.08) 0 110px, transparent 160px),
                #ffffff;
        }

        .bg-white,
        .bg-white\/90,
        .bg-white\/80,
        .bg-white\/78 {
            background-color: var(--surface) !important;
            background-image: none !important;
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
            color: var(--accent-primary) !important;
        }

        .border-pink-100,
        .border-pink-100\/70,
        .border-pink-200 {
            border-color: var(--border-soft) !important;
        }

        .bg-pink-50,
        .bg-pink-50\/60,
        .bg-pink-100,
        .bg-pink-100\/35,
        .bg-pink-50\/60 {
            background-color: var(--accent-soft) !important;
        }

        .bg-rose-100,
        .bg-rose-200,
        .bg-rose-100\/35,
        .bg-rose-100\/45 {
            background-color: var(--accent-danger-soft) !important;
        }

        .bg-rose-50,
        .bg-rose-50\/60 {
            background-color: var(--tri-red-soft) !important;
        }

        .bg-black\/20,
        .bg-black\/25,
        .bg-black\/30,
        .bg-black\/35,
        .bg-black\/40,
        .bg-black\/45 {
            background-color: rgba(15, 23, 42, 0.04) !important;
        }

        .text-pink-100,
        .text-pink-200,
        .text-pink-100\/80,
        .text-pink-100\/70,
        .text-pink-100\/60,
        .text-pink-100\/45 {
            color: var(--accent-primary) !important;
        }

        .text-rose-600,
        .text-rose-700,
        .text-rose-800 {
            color: var(--accent-danger) !important;
        }

        .border-rose-200 {
            border-color: rgba(239, 68, 68, 0.28) !important;
        }

        .bg-rose-500 {
            background-color: var(--accent-danger) !important;
        }

        .bg-fuchsia-100,
        .bg-fuchsia-500\/20 {
            background-color: var(--accent-danger-soft) !important;
        }

        .text-fuchsia-600,
        .text-fuchsia-700 {
            color: var(--accent-danger) !important;
        }

        .shadow-pink-500\/30 {
            --tw-shadow-color: rgba(22, 163, 74, 0.35) !important;
        }

        .shadow-\[0_30px_60px_-40px_rgba\(236\,72\,153\,0\.45\)\] {
            box-shadow: 0 30px 60px -40px rgba(22, 163, 74, 0.28) !important;
        }

        .shadow-\[0_26px_50px_-40px_rgba\(236\,72\,153\,0\.45\)\] {
            box-shadow: 0 26px 50px -40px rgba(22, 163, 74, 0.26) !important;
        }

        .text-emerald-600,
        .text-emerald-700,
        .text-emerald-800 {
            color: var(--accent-success) !important;
        }

        .bg-emerald-50,
        .bg-emerald-100 {
            background-color: var(--accent-success-soft) !important;
        }

        .border-emerald-200 {
            border-color: rgba(22, 163, 74, 0.28) !important;
        }

        .bg-pink-500\/20,
        .bg-pink-500\/15,
        .bg-pink-500\/30,
        .bg-pink-500\/45 {
            background-color: var(--accent-soft) !important;
        }

        .bg-pink-500 {
            background-color: var(--accent-primary) !important;
        }

        .bg-pink-600 {
            background-color: var(--accent-primary) !important;
            color: #ffffff !important;
            box-shadow: 0 10px 30px -12px rgba(37, 99, 235, 0.35) !important;
        }

        .hover\:bg-pink-700:hover {
            background-color: var(--accent-primary-dark) !important;
        }

        .from-pink-500,
        .from-pink-600 {
            --tw-gradient-from: var(--accent-primary) !important;
        }

        .via-rose-500 {
            --tw-gradient-stops: var(--accent-primary), var(--accent-success), var(--accent-danger) !important;
        }

        .to-rose-500,
        .to-rose-600 {
            --tw-gradient-to: var(--accent-danger) !important;
        }

        .from-rose-50 {
            --tw-gradient-from: var(--tri-red-soft) !important;
        }

        .via-pink-50 {
            --tw-gradient-stops: var(--tri-red-soft), var(--tri-blue-soft), var(--tri-green-soft) !important;
        }

        .to-white {
            --tw-gradient-to: #ffffff !important;
        }

        .brand-chip {
            border-radius: 999px;
            padding: 0.125rem 0.5rem;
            font-size: 0.7rem;
            font-weight: 600;
            color: #0f172a;
            background: linear-gradient(90deg, var(--tri-green-soft), var(--tri-red-soft), var(--tri-green-soft));
        }

        .from-pink-500.to-rose-500,
        .from-pink-600.to-rose-600 {
            background-image: none !important;
            background-color: var(--accent-primary) !important;
        }

        .shadow-sm,
        .shadow-lg {
            box-shadow: 0 16px 34px -24px rgba(37, 99, 235, 0.2) !important;
        }

        select {
            background: var(--surface) !important;
            border-color: rgba(37, 99, 235, 0.22) !important;
            color: var(--text-main) !important;
        }

        select:focus {
            border-color: rgba(37, 99, 235, 0.6) !important;
            box-shadow: 0 0 0 2px rgba(37, 99, 235, 0.18) !important;
        }

        select option {
            background: #0f172a;
            color: #f8fafc;
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

            body.sidebar-static main[data-app-main] {
                padding-left: 19rem;
            }
        }

        @media (prefers-reduced-motion: no-preference) {
            body.page-enter main[data-app-main] {
                opacity: 0;
                transform: translateY(6px);
            }

            body.page-enter-active main[data-app-main] {
                opacity: 1;
                transform: translateY(0);
                transition: opacity 220ms ease, transform 220ms ease;
            }

            body.page-leave main[data-app-main] {
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
<body class="relative min-h-screen overflow-x-hidden {{ request()->routeIs('login') ? 'overflow-hidden' : '' }} {{ auth()->check() && auth()->user()->role !== 'Admin' ? 'sidebar-static' : '' }} bg-rose-50 text-slate-800 page-enter {{ request()->routeIs('dashboard') ? 'dashboard-full' : '' }}" style="font-family: 'Plus Jakarta Sans', ui-sans-serif, system-ui, sans-serif;">
    <div class="pointer-events-none fixed inset-0 -z-10 overflow-hidden" aria-hidden="true">
        <div class="absolute -left-8 top-10 hidden h-36 w-36 rounded-full border border-pink-200/70 lg:block" style="background-color: rgba(22, 163, 74, 0.08);"></div>
        <div class="absolute left-[14%] top-[22%] hidden h-20 w-20 rounded-full border border-pink-200/60 lg:block" style="background-color: rgba(239, 68, 68, 0.08);"></div>
        <div class="absolute left-[34%] top-[10%] hidden h-14 w-14 rounded-full border border-pink-200/60 lg:block" style="background-color: rgba(22, 163, 74, 0.06);"></div>
        <div class="absolute right-[10%] top-[16%] hidden h-32 w-32 rounded-full border border-pink-200/70 lg:block" style="background-color: rgba(22, 163, 74, 0.08);"></div>
        <div class="absolute right-[22%] top-[36%] hidden h-16 w-16 rounded-full border border-pink-200/60 lg:block" style="background-color: rgba(239, 68, 68, 0.07);"></div>
        <div class="absolute right-[18%] bottom-[12%] hidden h-24 w-24 rounded-full border border-pink-200/60 lg:block" style="background-color: rgba(239, 68, 68, 0.08);"></div>
        <div class="absolute left-[8%] bottom-[16%] hidden h-28 w-28 rounded-full border border-pink-200/60 lg:block" style="background-color: rgba(22, 163, 74, 0.07);"></div>
        <div class="absolute left-[46%] bottom-[10%] hidden h-18 w-18 rounded-full border border-pink-200/60 lg:block" style="background-color: rgba(239, 68, 68, 0.06);"></div>
    </div>

    @auth
        @if (!request()->routeIs('login'))
        @if (auth()->user()->role !== 'Admin')
            <aside class="fixed left-0 top-0 z-50 flex h-screen w-[290px] flex-col overflow-hidden border-r border-pink-200/40 bg-white/95 p-3 shadow-[0_24px_50px_-36px_rgba(11,94,215,0.35)] backdrop-blur" data-sidebar-panel>
                <div class="mb-3 flex items-center justify-between">
                    <div class="text-[0.6rem] font-semibold uppercase tracking-[0.2em] text-emerald-700">Menu</div>
                    <div class="h-8 w-8"></div>
                </div>

                <div class="rounded-2xl border border-emerald-200/70 bg-emerald-50/70 p-3">
                    <a href="{{ route('dashboard') }}" class="flex items-center gap-2.5">
                        <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-pink-600 text-white shadow-sm">
                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                                <path d="M4 7.5A2.5 2.5 0 0 1 6.5 5h11A2.5 2.5 0 0 1 20 7.5v9A2.5 2.5 0 0 1 17.5 19h-11A2.5 2.5 0 0 1 4 16.5v-9z"></path>
                                <path d="m5 7 7 5 7-5"></path>
                            </svg>
                        </div>
                        <div>
                            <div class="text-base font-semibold text-emerald-950">SURATIN</div>
                            <div class="text-[0.65rem] font-medium text-emerald-800">Sistem Surat Internal</div>
                        </div>
                    </a>
                </div>

                <nav class="mt-4 flex-1 space-y-1.5">
                    <a href="{{ route('users.profile') }}" class="flex items-center gap-2 rounded-2xl border border-emerald-200/70 bg-emerald-100/70 px-3 py-2 text-xs font-semibold text-emerald-900">
                        <span class="inline-flex h-7 w-7 items-center justify-center rounded-lg bg-emerald-200/80 text-emerald-800">
                            <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                <circle cx="12" cy="7" r="4"></circle>
                            </svg>
                        </span>
                        Profile
                    </a>
                    <a href="{{ route('dashboard') }}" class="flex items-center gap-2 rounded-2xl border border-emerald-200/60 bg-emerald-50 px-3 py-2 text-xs font-semibold text-emerald-900/90 transition hover:bg-emerald-100/80">
                        <span class="inline-flex h-7 w-7 items-center justify-center rounded-lg bg-emerald-200/70 text-emerald-800">
                            <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                                <path d="M3 11l9-7 9 7"></path>
                                <path d="M5 10v9h14v-9"></path>
                            </svg>
                        </span>
                        Dashboard
                    </a>
                    <div class="my-1 border-t border-emerald-200/70"></div>
                    <a href="{{ route('surat.create') }}" class="flex items-center gap-2 rounded-2xl border border-emerald-200/60 bg-emerald-50 px-3 py-2 text-xs font-semibold text-emerald-900/90 transition hover:bg-emerald-100/80">
                        <span class="inline-flex h-7 w-7 items-center justify-center rounded-lg bg-emerald-200/70 text-emerald-800">
                            <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                                <path d="M12 5v14"></path>
                                <path d="M5 12h14"></path>
                            </svg>
                        </span>
                        Kirim Surat
                    </a>
                    <a href="{{ route('surat.inbox') }}" class="flex items-center gap-2 rounded-2xl border border-emerald-200/60 bg-emerald-50 px-3 py-2 text-xs font-semibold text-emerald-900/90 transition hover:bg-emerald-100/80">
                        <span class="inline-flex h-7 w-7 items-center justify-center rounded-lg bg-emerald-200/70 text-emerald-800">
                            <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                                <path d="M4 4h16v10H8l-4 4z"></path>
                                <path d="M8 9h8"></path>
                            </svg>
                        </span>
                        Surat Masuk
                    </a>
                    <a href="{{ route('surat.outbox') }}" class="flex items-center gap-2 rounded-2xl border border-emerald-200/60 bg-emerald-50 px-3 py-2 text-xs font-semibold text-emerald-900/90 transition hover:bg-emerald-100/80">
                        <span class="inline-flex h-7 w-7 items-center justify-center rounded-lg bg-emerald-200/70 text-emerald-800">
                            <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                                <path d="M4 12l16-8-6 16-3-6-7-2z"></path>
                            </svg>
                        </span>
                        Surat Keluar
                    </a>
                </nav>

                <div class="mt-4 rounded-2xl border border-emerald-200/60 bg-emerald-50/60 p-3">
                    <div class="text-[0.65rem] font-semibold uppercase tracking-wider text-emerald-700">Ringkas</div>
                    <div class="mt-2 space-y-1.5 text-[0.65rem] text-emerald-800/80">
                        <div class="flex items-center justify-between">
                            <span>Surat Masuk</span>
                            <span class="font-semibold text-emerald-900">{{ $sidebarMasukCount ?? 0 }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span>Surat Keluar</span>
                            <span class="font-semibold text-emerald-900">{{ $sidebarKeluarCount ?? 0 }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span>Arsip</span>
                            <span class="font-semibold text-emerald-900">{{ $sidebarArsipCount ?? 0 }}</span>
                        </div>
                    </div>
                </div>

                <div class="mt-3">
                    <a href="{{ route('logout') }}" class="inline-flex w-full items-center justify-center gap-2 rounded-full bg-rose-500 px-4 py-2 text-xs font-semibold text-white shadow-sm transition hover:bg-rose-600">
                        Logout
                    </a>
                </div>
            </aside>
        @endif
        @endif
    @endauth

    <main data-app-main class="mx-auto w-full {{ request()->routeIs('login') ? 'max-w-none px-0 py-0 h-screen overflow-hidden' : (request()->routeIs('surat.archive') ? 'max-w-[92rem] px-4 py-8 sm:px-6 lg:px-8' : 'max-w-6xl px-4 py-8 sm:px-6 lg:px-8') }}">
        @auth
            <div class="mb-6 flex flex-wrap items-center gap-3">
                @if (!request()->routeIs('dashboard'))
                    <a aria-label="Kembali" title="Kembali" class="inline-flex items-center justify-center rounded-full border border-pink-200 bg-white p-2.5 text-pink-700 shadow-sm transition hover:bg-pink-50" href="{{ route('dashboard') }}">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd" d="M17 10a.75.75 0 0 1-.75.75H5.56l3.22 3.22a.75.75 0 1 1-1.06 1.06l-4.5-4.5a.75.75 0 0 1 0-1.06l4.5-4.5a.75.75 0 0 1 1.06 1.06L5.56 9.25h10.69A.75.75 0 0 1 17 10Z" clip-rule="evenodd" />
                        </svg>
                    </a>
                @endif
            </div>
        @endauth
        @if (session('status'))
            <div class="mb-6 rounded-2xl border border-emerald-200 bg-emerald-50 p-4 text-sm font-medium text-emerald-700 shadow-sm">
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
            })();
        </script>
    @endauth
</body>
</html>


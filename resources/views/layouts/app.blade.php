<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Surat Menyurat')</title>
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    @endif
    <style>
        :root {
            --bg-app: #eef3f8;
            --bg-surface: #ffffff;
            --bg-soft: #f4f8fc;
            --line: #d6e0eb;
            --line-strong: #c1d1e3;
            --text-main: #0f2137;
            --text-muted: #61758c;
            --brand: #0f5fae;
            --brand-strong: #0b4f92;
            --brand-soft: #e1edf8;
            --success: #15803d;
        }

        body {
            margin: 0;
            font-family: "Plus Jakarta Sans", ui-sans-serif, system-ui, sans-serif;
            color: var(--text-main);
            line-height: 1.5;
            text-rendering: optimizeLegibility;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
            background:
                radial-gradient(circle at 3% 6%, rgba(15, 95, 174, 0.18) 0, rgba(15, 95, 174, 0) 34%),
                radial-gradient(circle at 97% 100%, rgba(21, 128, 61, 0.14) 0, rgba(21, 128, 61, 0) 30%),
                linear-gradient(165deg, #edf3f9 0%, #f6f9fd 100%);
        }

        h1, h2, h3, h4 {
            letter-spacing: -0.01em;
            line-height: 1.25;
        }

        p {
            line-height: 1.65;
        }

        .app-shell {
            min-height: calc(100vh - 70px);
            display: grid;
            grid-template-columns: 240px 1fr;
        }

        .app-shell.admin-layout {
            grid-template-columns: 1fr;
        }

        .app-sidebar {
            border-right: 1px solid rgba(255, 255, 255, 0.2);
            background: linear-gradient(172deg, #0a2d53 0%, #0f5fae 66%, #0f4c88 100%);
            padding: 1rem;
            display: flex;
            flex-direction: column;
            gap: 1rem;
            box-shadow: inset -1px 0 0 rgba(255, 255, 255, 0.08);
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .brand-mark {
            width: 36px;
            height: 36px;
            border-radius: 12px;
            background: linear-gradient(140deg, #f97316, #ef4444 45%, #f59e0b);
            color: #fff;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 14px 22px -16px rgba(239, 68, 68, 0.9);
        }

        .brand-title {
            font-size: 1.42rem;
            line-height: 1.2;
            font-weight: 700;
            letter-spacing: 0.01em;
            color: #ffffff;
        }

        .brand-subtitle {
            margin-top: 2px;
            font-size: 0.72rem;
            letter-spacing: 0.02em;
            color: rgba(255, 255, 255, 0.78);
        }

        .menu {
            display: flex;
            flex-direction: column;
            gap: 0.25rem;
        }

        .menu-item {
            display: flex;
            align-items: center;
            gap: 0.65rem;
            padding: 0.6rem 0.75rem;
            border-radius: 10px;
            color: rgba(255, 255, 255, 0.86);
            text-decoration: none;
            font-weight: 600;
            font-size: 0.91rem;
            line-height: 1.35;
            border: 1px solid transparent;
        }

        .menu-item:hover {
            background: rgba(255, 255, 255, 0.14);
            color: #ffffff;
            border-color: rgba(255, 255, 255, 0.22);
        }

        .menu-item.active {
            background: #ffffff;
            color: var(--brand-strong);
            box-shadow: 0 14px 24px -16px rgba(3, 26, 49, 0.95);
        }

        .menu-item svg {
            flex: 0 0 auto;
        }

        .app-main {
            min-width: 0;
            display: flex;
            flex-direction: column;
        }

        .topbar {
            height: 70px;
            border-bottom: 1px solid var(--line);
            background: linear-gradient(95deg, #ffffff 0%, #f4f8fc 86%);
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1.25rem;
            padding: 0 1.5rem;
            position: relative;
            backdrop-filter: blur(6px);
        }

        .topbar::after {
            content: "";
            position: absolute;
            left: 0;
            right: 0;
            bottom: 0;
            height: 3px;
            background: linear-gradient(90deg, #ef4444 0%, #0f5fae 56%, #15803d 100%);
        }

        .topbar-left {
            display: inline-flex;
            align-items: center;
            gap: 0.75rem;
        }

        .topbar .brand-title {
            color: #0f172a;
            font-size: 1.08rem;
        }

        .topbar .brand-subtitle {
            color: #64748b;
        }

        .topbar-right {
            display: inline-flex;
            align-items: center;
            gap: 1.1rem;
        }

        .topbar-division {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            font-size: 0.84rem;
            color: #334155;
            font-weight: 600;
        }

        .topbar-user {
            display: flex;
            align-items: center;
            gap: 0.6rem;
            font-size: 0.9rem;
            font-weight: 600;
        }

        .avatar {
            width: 34px;
            height: 34px;
            border-radius: 999px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(140deg, #0f5fae, #1c76cb 52%, #159a62);
            color: #fff;
            font-size: 0.8rem;
            font-weight: 700;
        }

        .page {
            padding: 1.75rem;
        }

        .page > *:first-child {
            animation: page-enter 220ms ease-out;
        }

        @keyframes page-enter {
            from {
                opacity: 0;
                transform: translateY(8px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .alert-ok {
            border: 1px solid #bde3cb;
            background: #edf8f1;
            color: #145a38;
            padding: 0.75rem 1rem;
            border-radius: 10px;
            margin-bottom: 1rem;
            font-size: 0.88rem;
            line-height: 1.55;
        }

        .account-menu {
            position: relative;
        }

        .account-trigger {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            border: 1px solid transparent;
            background: transparent;
            padding: 0.2rem 0.3rem;
            border-radius: 999px;
            cursor: pointer;
            color: #0f172a;
        }

        .account-trigger:hover {
            background: #f1f6fb;
            border-color: #d3deea;
        }

        .account-dropdown {
            position: absolute;
            right: 0;
            top: calc(100% + 0.5rem);
            width: 190px;
            border: 1px solid #d4e0ed;
            border-radius: 12px;
            background: #fff;
            box-shadow: 0 26px 42px -28px rgba(15, 33, 55, 0.45);
            padding: 0.35rem;
            display: none;
            z-index: 50;
        }

        .account-dropdown.open {
            display: block;
        }

        .account-item {
            width: 100%;
            display: inline-flex;
            align-items: center;
            gap: 0.45rem;
            padding: 0.5rem 0.65rem;
            border-radius: 8px;
            color: #334155;
            text-decoration: none;
            font-size: 0.88rem;
            font-weight: 600;
            border: 0;
            background: transparent;
            cursor: pointer;
            text-align: left;
        }

        .account-item:hover {
            background: #edf3fa;
        }

        .account-item.danger {
            color: #b91c1c;
        }

        .account-item.danger:hover {
            background: #fef2f2;
        }

        .page .rounded-xl.border,
        .page .rounded-2xl.border,
        .page .rounded-3xl.border,
        .page .panel,
        .page .outbox-wrap,
        .page .archive-wrap,
        .page .compose-wrap .card {
            border-color: var(--line) !important;
            box-shadow: 0 22px 42px -34px rgba(15, 33, 55, 0.42);
        }

        .page table thead {
            background: #f2f7fc !important;
        }

        .page table th {
            font-size: 0.74rem;
            letter-spacing: 0.03em;
            line-height: 1.35;
        }

        .page table td {
            line-height: 1.5;
            font-size: 0.9rem;
        }

        .page table tbody tr:hover {
            background: #f8fbff;
        }

        .page input,
        .page select,
        .page textarea {
            border-color: var(--line-strong) !important;
        }

        .page input:focus,
        .page select:focus,
        .page textarea:focus {
            border-color: #8fb3d8 !important;
            box-shadow: 0 0 0 2px rgba(15, 95, 174, 0.12) !important;
        }

        .page .bg-blue-600,
        .page .bg-blue-700,
        .page .bg-slate-900 {
            background-color: var(--brand) !important;
        }

        .page .hover\:bg-blue-700:hover,
        .page .hover\:bg-blue-800:hover,
        .page .hover\:bg-slate-800:hover {
            background-color: var(--brand-strong) !important;
        }

        @media (max-width: 980px) {
            .app-shell {
                grid-template-columns: 1fr;
            }

            .app-sidebar {
                border-right: 0;
                border-bottom: 1px solid rgba(255, 255, 255, 0.2);
            }

            .page {
                padding: 1rem;
            }
        }
    </style>
    @stack('styles')
</head>
<body>
    @auth
        @php
            $user = auth()->user();
            $isAdmin = $user->role === 'Admin';
            $initial = strtoupper(substr($user->username ?? 'U', 0, 1));
        @endphp

        <header class="topbar">
            <div class="topbar-left">
                <div class="brand-mark">
                    <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2">
                        <rect x="3" y="5" width="18" height="14" rx="2"></rect>
                        <path d="m3 7 9 6 9-6"></path>
                    </svg>
                </div>
                <div>
                    <div class="brand-title">SURATIN</div>
                    <div class="brand-subtitle">Sistem Surat Internal</div>
                </div>
            </div>
            <div class="topbar-right">
                <div class="topbar-division">
                    <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                        <path d="M3 21h18"></path>
                        <path d="M5 21V7l7-4 7 4v14"></path>
                        <path d="M9 10h.01M15 10h.01M9 14h.01M15 14h.01"></path>
                    </svg>
                    {{ $user->division ?? 'Tanpa Divisi' }}
                </div>
                <div class="topbar-user">
                    <div class="account-menu" data-account-menu>
                        <button type="button" class="account-trigger" data-account-trigger aria-expanded="false" aria-haspopup="true">
                            <span class="avatar">{{ $initial }}</span>
                            <span class="inline-flex items-center gap-1.5">
                                {{ $user->username }}
                                <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                                    <path d="m6 9 6 6 6-6"></path>
                                </svg>
                            </span>
                        </button>
                        <div class="account-dropdown" data-account-dropdown>
                            <a href="{{ route('users.profile') }}" class="account-item">
                                <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                    <circle cx="12" cy="7" r="4"></circle>
                                </svg>
                                Profile
                            </a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="account-item danger">
                                    <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                                        <path d="m9 21-7-9 7-9"></path>
                                        <path d="M22 12H3"></path>
                                    </svg>
                                    Logout
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <div class="app-shell {{ $isAdmin ? 'admin-layout' : '' }}">
            @if (!$isAdmin)
                <aside class="app-sidebar">
                    <nav class="menu">
                        <a href="{{ route('dashboard') }}" class="menu-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                            <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                                <path d="M3 3h7v7H3z"></path>
                                <path d="M14 3h7v7h-7z"></path>
                                <path d="M14 14h7v7h-7z"></path>
                                <path d="M3 14h7v7H3z"></path>
                            </svg>
                            <span>Dashboard</span>
                        </a>
                        <a href="{{ route('surat.index') }}" class="menu-item {{ request()->routeIs('surat.index', 'surat.inbox', 'surat.outbox') ? 'active' : '' }}">
                            <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                                <path d="M22 12h-6l-2 3h-4l-2-3H2"></path>
                                <path d="M5.5 5h13a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2h-13a2 2 0 0 1-2-2V7a2 2 0 0 1 2-2Z"></path>
                            </svg>
                            <span>Surat</span>
                        </a>
                        <a href="{{ route('surat.create') }}" class="menu-item {{ request()->routeIs('surat.create') ? 'active' : '' }}">
                            <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                                <path d="M12 5v14"></path>
                                <path d="M5 12h14"></path>
                            </svg>
                            <span>Kirim Surat</span>
                        </a>
                        
                        <a href="{{ route('surat.inventory') }}" class="menu-item {{ request()->routeIs('surat.inventory') ? 'active' : '' }}">
                            <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                                <path d="M7 10l5 5 5-5"></path>
                                <path d="M12 15V3"></path>
                            </svg>
                            <span>Inventori File</span>
                        </a>
                    </nav>
                </aside>
            @endif

            <div class="app-main">
                <main class="page">
                    @if (session('status'))
                        <div class="alert-ok">{{ session('status') }}</div>
                    @endif
                    @yield('content')
                </main>
            </div>
        </div>
    @else
        @yield('content')
    @endauth

    @auth
    <script>
        (function () {
            const menuRoot = document.querySelector('[data-account-menu]');
            if (!menuRoot) {
                return;
            }

            const trigger = menuRoot.querySelector('[data-account-trigger]');
            const dropdown = menuRoot.querySelector('[data-account-dropdown]');
            if (!trigger || !dropdown) {
                return;
            }

            function closeMenu() {
                dropdown.classList.remove('open');
                trigger.setAttribute('aria-expanded', 'false');
            }

            function toggleMenu() {
                const shouldOpen = !dropdown.classList.contains('open');
                if (shouldOpen) {
                    dropdown.classList.add('open');
                    trigger.setAttribute('aria-expanded', 'true');
                } else {
                    closeMenu();
                }
            }

            trigger.addEventListener('click', toggleMenu);

            document.addEventListener('click', function (event) {
                if (!menuRoot.contains(event.target)) {
                    closeMenu();
                }
            });
        })();
    </script>
    @endauth

    @stack('scripts')
</body>
</html>

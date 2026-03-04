<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'SURAT MENYURAT INTERNAL')</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    @stack('styles')
</head>
<body>
    <header class="topbar">
        <div class="brand">
            <div class="brand-mark">SMI</div>
            <div>
                <div class="brand-title">SURAT MENYURAT INTERNAL</div>
                <div class="brand-subtitle">Sistem koordinasi antar divisi</div>
            </div>
        </div>
        @auth
        <div class="user-panel">
            <div class="user-identity">
                <div class="user-name">{{ auth()->user()->username }}</div>
                <div class="user-meta">{{ auth()->user()->division ?? 'Tanpa Divisi' }} · {{ auth()->user()->role }}</div>
            </div>
            <div class="user-actions">
                <a class="icon-btn" href="{{ route('notifications.index') }}" aria-label="Notifikasi">
                    <svg viewBox="0 0 24 24" aria-hidden="true">
                        <path d="M12 3c-3.3 0-6 2.7-6 6v3.2l-1.6 2.7c-.4.7.1 1.6.9 1.6h13.4c.8 0 1.3-.9.9-1.6L18 12.2V9c0-3.3-2.7-6-6-6zm0 18a2.5 2.5 0 0 0 2.4-2H9.6A2.5 2.5 0 0 0 12 21z"></path>
                    </svg>
                    @if (!empty($globalNotifCount) && $globalNotifCount > 0)
                        <span class="notif-badge">{{ $globalNotifCount }}</span>
                    @endif
                </a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="btn btn-ghost btn-logout" type="submit">Logout</button>
                </form>
            </div>
        </div>
        @endauth
    </header>

    <main class="container">
        @auth
            @if (!request()->routeIs('dashboard') && url()->previous() !== url()->current())
                <a class="btn btn-ghost btn-back" href="{{ url()->previous() }}">Kembali</a>
                <a href="{{ route('dashboard') }}" class="btn btn-secondary">Dashboard</a>
            @endif
        @endauth
        @if (session('status'))
            <div class="alert">{{ session('status') }}</div>
        @endif
        @yield('content')
    </main>

    @stack('scripts')
</body>
</html>


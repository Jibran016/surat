@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')

<!-- Lucide Icons -->
<script src="https://unpkg.com/lucide@latest"></script>

<style>
body {
    background: #f4f6f9;
}

.dashboard-wrapper {
    max-width: 1300px;
    margin: 40px auto;
    padding: 0 20px;
    font-family: 'Inter', 'Segoe UI', sans-serif;
}

/* Header */
.dashboard-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 40px;
}

.dashboard-title {
    font-size: 30px;
    font-weight: 700;
    color: #111827;
}

.dashboard-subtitle {
    color: #6b7280;
    margin-top: 6px;
}

.user-badge {
    background: #ffffff;
    padding: 10px 18px;
    border-radius: 12px;
    box-shadow: 0 4px 18px rgba(0,0,0,0.05);
    font-size: 14px;
    font-weight: 500;
}

/* Stats */
.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
    gap: 20px;
    margin-bottom: 40px;
}

.stat-card {
    background: #ffffff;
    padding: 25px;
    border-radius: 18px;
    box-shadow: 0 8px 28px rgba(0,0,0,0.06);
    display: flex;
    justify-content: space-between;
    align-items: center;
    transition: 0.25s ease;
}

.stat-card:hover {
    transform: translateY(-6px);
    box-shadow: 0 14px 34px rgba(0,0,0,0.08);
}

.stat-info h2 {
    font-size: 28px;
    font-weight: 700;
    margin: 0;
}

.stat-info span {
    color: #6b7280;
    font-size: 14px;
}

.stat-icon {
    width: 45px;
    height: 45px;
    background: #eef2ff;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 12px;
}

/* Menu Cards */
.menu-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
    gap: 20px;
}

.menu-card {
    background: #ffffff;
    padding: 30px;
    border-radius: 20px;
    text-decoration: none;
    color: inherit;
    box-shadow: 0 8px 28px rgba(0,0,0,0.06);
    transition: 0.3s ease;
    position: relative;
    overflow: hidden;
}

.menu-card:hover {
    transform: translateY(-6px);
    box-shadow: 0 18px 40px rgba(0,0,0,0.1);
}

.menu-card h3 {
    margin-top: 15px;
    font-size: 18px;
    font-weight: 600;
}

.menu-card p {
    margin-top: 8px;
    font-size: 14px;
    color: #6b7280;
}

.menu-icon {
    width: 50px;
    height: 50px;
    background: linear-gradient(135deg, #2563eb, #1e40af);
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 14px;
    color: white;
}

/* Special Create Button */
.menu-card.primary {
    background: linear-gradient(135deg, #2563eb, #1e3a8a);
    color: white;
}

.menu-card.primary p {
    color: rgba(255,255,255,0.85);
}

.menu-card.primary .menu-icon {
    background: rgba(255,255,255,0.2);
}
</style>

<div class="dashboard-wrapper">

    <!-- Header -->
    <div class="dashboard-header">
        <div>
            <div class="dashboard-title">Dashboard</div>
            <div class="dashboard-subtitle">
                Sistem surat internal antar divisi.
            </div>
        </div>
        <div class="user-badge">
            {{ auth()->user()->name }}
        </div>
    </div>

    <!-- Stats -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-info">
                <h2>{{ $masukCount }}</h2>
                <span>Surat Masuk</span>
            </div>
            <div class="stat-icon">
                <i data-lucide="inbox"></i>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-info">
                <h2>{{ $keluarCount }}</h2>
                <span>Surat Keluar</span>
            </div>
            <div class="stat-icon">
                <i data-lucide="send"></i>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-info">
                <h2>{{ $arsipCount }}</h2>
                <span>Arsip</span>
            </div>
            <div class="stat-icon">
                <i data-lucide="archive"></i>
            </div>
        </div>
    </div>

    <!-- Menu -->
    <div class="menu-grid">

        <a href="{{ route('surat.create') }}" class="menu-card primary">
            <div class="menu-icon">
                <i data-lucide="plus"></i>
            </div>
            <h3>Buat Surat</h3>
            <p>Kirim surat baru ke divisi lain dengan cepat.</p>
        </a>

        <a href="{{ route('surat.inbox') }}" class="menu-card">
            <div class="menu-icon">
                <i data-lucide="mail"></i>
            </div>
            <h3>Surat Masuk</h3>
            <p>Lihat dan tindak lanjuti surat yang diterima.</p>
        </a>

        <a href="{{ route('surat.outbox') }}" class="menu-card">
            <div class="menu-icon">
                <i data-lucide="send-horizontal"></i>
            </div>
            <h3>Surat Keluar</h3>
            <p>Pantau status surat yang telah dikirim.</p>
        </a>

        <a href="{{ route('surat.archive') }}" class="menu-card">
            <div class="menu-icon">
                <i data-lucide="folder"></i>
            </div>
            <h3>Arsip</h3>
            <p>Kelola surat yang telah selesai diproses.</p>
        </a>

    </div>

</div>

<script>
    lucide.createIcons();
</script>

@endsection
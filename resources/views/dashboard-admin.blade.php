@extends('layouts.app')

@section('title', 'Dashboard Admin')

@section('content')
<div class="dashboard">
    <h1>Dashboard Admin</h1>
    <p class="muted">Akses khusus untuk manajemen akun.</p>

    <div class="grid">
        <a class="card menu-card" href="{{ route('users.index') }}">
            <div class="menu-title">Manajemen Akun</div>
            <div class="menu-desc">Kelola akun tiap divisi.</div>
        </a>
        <a class="card menu-card" href="{{ route('divisions.index') }}">
            <div class="menu-title">Manajemen Divisi</div>
            <div class="menu-desc">Tambah, ubah, atau hapus divisi.</div>
        </a>
    </div>
</div>
@endsection

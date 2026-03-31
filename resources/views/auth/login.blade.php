@extends('layouts.app')

@section('title', 'Login - Surat Menyurat')

@push('styles')
<style>
    .login-page {
        min-height: 100vh;
        display: grid;
        place-items: center;
        background:
            radial-gradient(circle at 12% 14%, rgba(15, 95, 174, 0.2) 0, rgba(15, 95, 174, 0) 34%),
            radial-gradient(circle at 86% 86%, rgba(21, 128, 61, 0.16) 0, rgba(21, 128, 61, 0) 34%),
            linear-gradient(150deg, #edf3f9 0%, #f7fafd 100%);
        padding: 1.5rem;
    }

    .login-shell {
        width: min(980px, 100%);
        display: grid;
        grid-template-columns: 1.1fr 0.9fr;
        border: 1px solid #d5e0ec;
        border-radius: 20px;
        overflow: hidden;
        background: #fff;
        box-shadow: 0 36px 70px -42px rgba(15, 33, 55, 0.42);
    }

    .login-info {
        padding: 2rem;
        background: linear-gradient(171deg, #0a2d53 0%, #0f5fae 62%, #0e4f8e 100%);
        border-right: 1px solid rgba(255, 255, 255, 0.2);
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }

    .brand-row {
        display: inline-flex;
        align-items: center;
        gap: 0.75rem;
    }

    .brand-icon {
        width: 44px;
        height: 44px;
        border-radius: 12px;
        background: linear-gradient(140deg, #f97316, #ef4444 44%, #f59e0b);
        color: #fff;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 16px 24px -18px rgba(239, 68, 68, 0.9);
    }

    .brand-name {
        font-size: 1.8rem;
        line-height: 1.15;
        font-weight: 700;
        letter-spacing: 0.01em;
        color: #ffffff;
    }

    .brand-sub {
        margin-top: 0.15rem;
        font-size: 0.78rem;
        letter-spacing: 0.03em;
        color: rgba(255, 255, 255, 0.76);
    }

    .login-copy {
        margin-top: 2.2rem;
    }

    .login-copy h2 {
        margin: 0;
        font-size: 1.85rem;
        line-height: 1.24;
        color: #ffffff;
        font-weight: 700;
        letter-spacing: -0.01em;
    }

    .login-copy p {
        margin-top: 0.7rem;
        color: rgba(255, 255, 255, 0.85);
        font-size: 0.92rem;
        line-height: 1.65;
        max-width: 44ch;
    }

    .chip-row {
        margin-top: 1.1rem;
        display: flex;
        gap: 0.45rem;
        flex-wrap: wrap;
    }

    .chip {
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        border-radius: 999px;
        border: 1px solid rgba(255, 255, 255, 0.28);
        background: rgba(255, 255, 255, 0.14);
        color: #ffffff;
        padding: 0.22rem 0.6rem;
        font-size: 0.75rem;
        font-weight: 700;
    }

    .login-form-wrap {
        padding: 2rem;
        display: flex;
        align-items: center;
    }

    .login-form {
        width: 100%;
    }

    .form-title {
        margin: 0;
        font-size: 1.45rem;
        line-height: 1.3;
        letter-spacing: -0.01em;
        color: #0f2137;
        font-weight: 700;
    }

    .form-sub {
        margin-top: 0.25rem;
        color: #60758d;
        font-size: 0.88rem;
        line-height: 1.55;
    }

    .field {
        margin-top: 1rem;
    }

    .label {
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        margin-bottom: 0.45rem;
        font-size: 0.82rem;
        letter-spacing: 0.01em;
        color: #0f172a;
        font-weight: 600;
    }

    .input {
        width: 100%;
        height: 46px;
        border: 1px solid #cbd5e1;
        border-radius: 10px;
        padding: 0 0.8rem;
        font-size: 0.9rem;
        color: #334155;
        background: #fff;
        outline: none;
        line-height: 1.45;
    }

    .input:focus {
        border-color: #8fb3d8;
        box-shadow: 0 0 0 2px rgba(15, 95, 174, 0.14);
    }

    .error {
        margin-top: 0.35rem;
        color: #dc2626;
        font-size: 0.82rem;
    }

    .submit-btn {
        margin-top: 1.2rem;
        width: 100%;
        height: 46px;
        border: 1px solid #0f5fae;
        background: #0f5fae;
        color: #fff;
        border-radius: 10px;
        font-size: 0.9rem;
        letter-spacing: 0.01em;
        font-weight: 700;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.35rem;
        cursor: pointer;
    }

    .submit-btn:hover {
        background: #0b4f92;
    }

    @media (max-width: 920px) {
        .login-shell {
            grid-template-columns: 1fr;
        }

        .login-info {
            border-right: 0;
            border-bottom: 1px solid #d5e0ec;
        }
    }
</style>
@endpush

@section('content')
<div class="login-page">
    <div class="login-shell">
        <section class="login-info">
            <div>
                <div class="brand-row">
                    <span class="brand-icon">
                        <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2">
                            <rect x="3" y="5" width="18" height="14" rx="2"></rect>
                            <path d="m3 7 9 6 9-6"></path>
                        </svg>
                    </span>
                    <div>
                        <div class="brand-name">SURATIN</div>
                        <div class="brand-sub">Sistem Internal</div>
                    </div>
                </div>

                <div class="login-copy">
                    <h2>Kelola surat divisi lebih rapi</h2>
                    <p>Masuk ke sistem untuk memproses surat masuk, surat keluar, dan arsip dengan alur yang terpusat.</p>
                </div>
            </div>
        </section>

        <section class="login-form-wrap">
            <form method="POST" action="{{ route('login.submit') }}" class="login-form">
                @csrf

                <h1 class="form-title">Masuk Akun</h1>
                <p class="form-sub">Gunakan email dan password akun Anda.</p>

                <div class="field">
                    <label class="label" for="email">
                        <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16v16H4z"></path><path d="m4 7 8 6 8-6"></path></svg>
                        Email
                    </label>
                    <input id="email" name="email" type="email" class="input" value="{{ old('email') }}" required autofocus>
                    @error('email')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="field">
                    <label class="label" for="password">
                        <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="10" rx="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>
                        Password
                    </label>
                    <input id="password" name="password" type="password" class="input" required>
                    @error('password')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit" class="submit-btn">
                    <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14"></path><path d="m12 5 7 7-7 7"></path></svg>
                    Masuk
                </button>
            </form>
        </section>
    </div>
</div>
@endsection

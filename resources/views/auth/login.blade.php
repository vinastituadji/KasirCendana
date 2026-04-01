<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk — KasirCendana</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
<div class="auth-page">
    <div class="auth-card">
        <div class="auth-logo">
            <div class="icon-wrap">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
            </div>
            <h1>KasirCendana</h1>
            <p>Masuk ke akun Anda</p>
        </div>

        @if(session('info'))
            <div class="alert alert-info">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                {{ session('info') }}
            </div>
        @endif

        <form action="{{ route('login.post') }}" method="POST">
            @csrf
            <div class="form-group">
                <label class="form-label">Alamat Email</label>
                <input type="email" name="Email" class="form-control {{ $errors->has('Email') ? 'is-invalid' : '' }}"
                    placeholder="email@contoh.com" value="{{ old('Email') }}" required autofocus>
                @error('Email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label class="form-label">Password</label>
                <input type="password" name="Password" class="form-control" placeholder="Password Anda" required>
            </div>

            <div class="form-group" style="display:flex;align-items:center;gap:8px">
                <input type="checkbox" name="remember" id="remember" style="width:16px;height:16px;cursor:pointer">
                <label for="remember" style="font-size:13px;color:var(--gray-600);cursor:pointer">Ingat saya</label>
            </div>

            <button type="submit" class="btn btn-primary w-full" style="justify-content:center;padding:11px 18px;font-size:14px">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M15 3h4a2 2 0 012 2v14a2 2 0 01-2 2h-4"/><polyline points="10 17 15 12 10 7"/><line x1="15" y1="12" x2="3" y2="12"/></svg>
                Masuk
            </button>
        </form>

        <div class="divider"></div>

        <p style="text-align:center;font-size:13px;color:var(--gray-500)">
            Belum punya akun?
            <a href="{{ route('register') }}" style="color:var(--teal-600);font-weight:600;text-decoration:none">Daftar sekarang</a>
        </p>

        <p style="text-align:center;margin-top:12px">
            <a href="{{ route('katalog') }}" style="font-size:12.5px;color:var(--gray-400);text-decoration:none">
                &larr; Kembali ke katalog
            </a>
        </p>
    </div>
</div>
<script src="{{ asset('js/app.js') }}"></script>
</body>
</html>

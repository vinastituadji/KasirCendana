<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar — KasirCendana</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
<div class="auth-page">
    <div class="auth-card" style="max-width:480px">
        <div class="auth-logo">
            <div class="icon-wrap">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
            </div>
            <h1>Buat Akun</h1>
            <p>Daftarkan diri Anda ke KasirCendana</p>
        </div>

        <form action="{{ route('register.post') }}" method="POST">
            @csrf
            <div class="grid-2" style="gap:14px">
                <div class="form-group mb-0">
                    <label class="form-label">Nama Lengkap <span style="color:var(--red-500)">*</span></label>
                    <input type="text" name="NamaPelanggan" class="form-control {{ $errors->has('NamaPelanggan') ? 'is-invalid' : '' }}"
                        placeholder="Nama Anda" value="{{ old('NamaPelanggan') }}" required>
                    @error('NamaPelanggan')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="form-group mb-0">
                    <label class="form-label">Nomor Telepon</label>
                    <input type="text" name="NomorTelepon" class="form-control"
                        placeholder="08xxxxxxxxxx" value="{{ old('NomorTelepon') }}">
                </div>
            </div>

            <div class="form-group" style="margin-top:14px">
                <label class="form-label">Alamat Email <span style="color:var(--red-500)">*</span></label>
                <input type="email" name="Email" class="form-control {{ $errors->has('Email') ? 'is-invalid' : '' }}"
                    placeholder="email@contoh.com" value="{{ old('Email') }}" required>
                @error('Email')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="form-group">
                <label class="form-label">Alamat</label>
                <textarea name="Alamat" class="form-control" placeholder="Alamat lengkap Anda" rows="2">{{ old('Alamat') }}</textarea>
            </div>

            <div class="grid-2" style="gap:14px">
                <div class="form-group mb-0">
                    <label class="form-label">Password <span style="color:var(--red-500)">*</span></label>
                    <input type="password" name="Password" class="form-control {{ $errors->has('Password') ? 'is-invalid' : '' }}"
                        placeholder="Min. 6 karakter" required>
                    @error('Password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="form-group mb-0">
                    <label class="form-label">Konfirmasi Password <span style="color:var(--red-500)">*</span></label>
                    <input type="password" name="Password_confirmation" class="form-control" placeholder="Ulangi password" required>
                </div>
            </div>

            <button type="submit" class="btn btn-primary w-full mt-3" style="justify-content:center;padding:11px 18px;font-size:14px">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M16 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="8.5" cy="7" r="4"/><line x1="20" y1="8" x2="20" y2="14"/><line x1="23" y1="11" x2="17" y2="11"/></svg>
                Daftar Sekarang
            </button>
        </form>

        <div class="divider"></div>
        <p style="text-align:center;font-size:13px;color:var(--gray-500)">
            Sudah punya akun?
            <a href="{{ route('login') }}" style="color:var(--teal-600);font-weight:600;text-decoration:none">Masuk di sini</a>
        </p>
        <p style="text-align:center;margin-top:12px">
            <a href="{{ route('katalog') }}" style="font-size:12.5px;color:var(--gray-400);text-decoration:none">&larr; Kembali ke katalog</a>
        </p>
    </div>
</div>
<script src="{{ asset('js/app.js') }}"></script>
</body>
</html>

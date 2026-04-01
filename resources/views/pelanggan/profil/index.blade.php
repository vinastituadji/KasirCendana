@extends('layouts.public')
@section('title', 'Profil Saya')

@section('content')
<div style="max-width:600px;margin:0 auto">
    <div style="margin-bottom:24px">
        <h1 style="font-size:24px;font-weight:800;color:var(--gray-900);letter-spacing:-.3px">Profil Saya</h1>
        <p style="color:var(--gray-500);margin-top:4px">Kelola informasi akun Anda</p>
    </div>

    <div class="card">
        <div style="padding:28px 28px 20px;text-align:center;border-bottom:1px solid var(--sand-100)">
            <div style="width:68px;height:68px;background:var(--teal-500);border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 12px;font-size:28px;font-weight:800;color:white">
                {{ strtoupper(substr($user->NamaPelanggan, 0, 1)) }}
            </div>
            <div style="font-size:18px;font-weight:700;color:var(--gray-800)">{{ $user->NamaPelanggan }}</div>
            <div style="font-size:13px;color:var(--gray-500);margin-top:3px">{{ $user->Email }}</div>
            <span class="badge badge-info mt-1">Pelanggan</span>
        </div>

        <div class="card-body">
            <form action="{{ route('pelanggan.profil.update') }}" method="POST">
                @csrf @method('PUT')

                <div class="form-group">
                    <label class="form-label">Nama Lengkap <span style="color:var(--red-500)">*</span></label>
                    <input type="text" name="NamaPelanggan" class="form-control {{ $errors->has('NamaPelanggan') ? 'is-invalid' : '' }}"
                        value="{{ old('NamaPelanggan', $user->NamaPelanggan) }}" required>
                    @error('NamaPelanggan')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Nomor Telepon</label>
                    <input type="text" name="NomorTelepon" class="form-control"
                        value="{{ old('NomorTelepon', $user->NomorTelepon) }}" placeholder="08xxxxxxxxxx">
                </div>

                <div class="form-group">
                    <label class="form-label">Alamat</label>
                    <textarea name="Alamat" class="form-control" rows="3" placeholder="Alamat lengkap Anda">{{ old('Alamat', $user->Alamat) }}</textarea>
                </div>

                <div class="divider"></div>

                <div style="margin-bottom:14px">
                    <div style="font-size:14px;font-weight:700;color:var(--gray-700)">Ubah Password</div>
                    <div class="text-sm text-muted mt-1">Kosongkan jika tidak ingin mengubah password</div>
                </div>

                <div class="grid-2">
                    <div class="form-group">
                        <label class="form-label">Password Baru</label>
                        <input type="password" name="Password" class="form-control {{ $errors->has('Password') ? 'is-invalid' : '' }}"
                            placeholder="Min. 6 karakter">
                        @error('Password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label">Konfirmasi Password</label>
                        <input type="password" name="Password_confirmation" class="form-control" placeholder="Ulangi password baru">
                    </div>
                </div>

                <button type="submit" class="btn btn-primary">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg>
                    Simpan Perubahan
                </button>
            </form>
        </div>
    </div>
</div>
@endsection

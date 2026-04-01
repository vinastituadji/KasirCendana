@extends('layouts.kasir')
@section('title', 'Tambah Produk')
@section('page-title', 'Tambah Produk Baru')

@section('content')
<div style="max-width:640px">
    <div class="card">
        <div class="card-header">
            <span class="card-title">Form Produk Baru</span>
            <a href="{{ route('kasir.produk.index') }}" class="btn btn-secondary btn-sm">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="15 18 9 12 15 6"/></svg>
                Kembali
            </a>
        </div>
        <div class="card-body">
            <form action="{{ route('kasir.produk.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <label class="form-label">Nama Produk <span style="color:var(--red-500)">*</span></label>
                    <input type="text" name="NamaProduk" class="form-control {{ $errors->has('NamaProduk') ? 'is-invalid' : '' }}"
                        value="{{ old('NamaProduk') }}" placeholder="Nama produk" required>
                    @error('NamaProduk')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="grid-2">
                    <div class="form-group">
                        <label class="form-label">Kategori <span style="color:var(--red-500)">*</span></label>
                        <select name="KategoriID" class="form-control {{ $errors->has('KategoriID') ? 'is-invalid' : '' }}" required>
                            <option value="">-- Pilih Kategori --</option>
                            @foreach($kategoriList as $k)
                                <option value="{{ $k->KategoriID }}" {{ old('KategoriID') == $k->KategoriID ? 'selected' : '' }}>{{ $k->NamaKategori }}</option>
                            @endforeach
                        </select>
                        @error('KategoriID')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label">Stok <span style="color:var(--red-500)">*</span></label>
                        <input type="number" name="Stok" class="form-control {{ $errors->has('Stok') ? 'is-invalid' : '' }}"
                            value="{{ old('Stok', 0) }}" min="0" required>
                        @error('Stok')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Harga (Rp) <span style="color:var(--red-500)">*</span></label>
                    <input type="number" name="Harga" class="form-control {{ $errors->has('Harga') ? 'is-invalid' : '' }}"
                        value="{{ old('Harga') }}" min="0" step="0.01" placeholder="0" required>
                    @error('Harga')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Gambar Produk</label>
                    <input type="file" name="Gambar" class="form-control {{ $errors->has('Gambar') ? 'is-invalid' : '' }}"
                        accept="image/*" onchange="previewImg(this)">
                    @error('Gambar')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    <div id="imgPreview" style="margin-top:10px;display:none">
                        <img id="previewEl" style="width:120px;height:90px;object-fit:cover;border-radius:var(--radius-sm);border:1px solid var(--sand-200)">
                    </div>
                </div>

                <div class="d-flex gap-2 mt-3">
                    <button type="submit" class="btn btn-primary">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg>
                        Simpan Produk
                    </button>
                    <a href="{{ route('kasir.produk.index') }}" class="btn btn-secondary">Batal</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
@push('scripts')
<script>
function previewImg(input) {
    const wrap = document.getElementById('imgPreview');
    const el = document.getElementById('previewEl');
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = e => { el.src = e.target.result; wrap.style.display = 'block'; };
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
@endpush

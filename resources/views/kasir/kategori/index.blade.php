@extends('layouts.kasir')
@section('title', 'Kategori')
@section('page-title', 'Manajemen Kategori')

@section('content')
<div class="grid-2" style="align-items:start">
    {{-- Form Tambah --}}
    <div class="card">
        <div class="card-header"><span class="card-title">Tambah Kategori</span></div>
        <div class="card-body">
            <form action="{{ route('kasir.kategori.store') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label class="form-label">Nama Kategori <span style="color:var(--red-500)">*</span></label>
                    <input type="text" name="NamaKategori" class="form-control {{ $errors->has('NamaKategori') ? 'is-invalid' : '' }}"
                        placeholder="Contoh: Kursi, Sofa..." value="{{ old('NamaKategori') }}" required>
                    @error('NamaKategori')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <button type="submit" class="btn btn-primary">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                    Tambah Kategori
                </button>
            </form>
        </div>
    </div>

    {{-- Daftar Kategori --}}
    <div class="card">
        <div class="card-header">
            <span class="card-title">Daftar Kategori</span>
            <form method="GET" action="{{ route('kasir.kategori.index') }}" class="search-wrap">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                <input type="text" name="search" class="form-control" placeholder="Cari kategori..." value="{{ request('search') }}" onchange="this.form.submit()">
            </form>
        </div>
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Nama Kategori</th>
                        <th>Jumlah Produk</th>
                        <th style="text-align:center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($kategori as $k)
                    <tr>
                        <td><span style="font-weight:600">{{ $k->NamaKategori }}</span></td>
                        <td><span class="badge badge-info">{{ $k->produk_count }} produk</span></td>
                        <td style="text-align:center">
                            <div class="d-flex gap-2" style="justify-content:center">
                                <button class="btn btn-secondary btn-sm" onclick="openEditModal({{ $k->KategoriID }}, '{{ addslashes($k->NamaKategori) }}')">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                                    Edit
                                </button>
                                @if($k->produk_count == 0)
                                <form action="{{ route('kasir.kategori.destroy', $k) }}" method="POST" onsubmit="return confirm('Hapus kategori ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14H6L5 6"/></svg>
                                        Hapus
                                    </button>
                                </form>
                                @else
                                <button class="btn btn-secondary btn-sm" disabled title="Ada produk terkait" style="opacity:.4;cursor:not-allowed">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14H6L5 6"/></svg>
                                    Hapus
                                </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="3" style="text-align:center;padding:24px;color:var(--gray-400)">Tidak ada kategori</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($kategori->hasPages())
        <div class="pagination-wrap">{{ $kategori->links() }}</div>
        @endif
    </div>
</div>

{{-- Modal Edit --}}
<div class="modal-backdrop" id="modalEdit">
    <div class="modal-box" style="max-width:400px">
        <div class="modal-header">
            <span class="modal-title">Edit Kategori</span>
            <button class="modal-close" data-modal-close="modalEdit">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
            </button>
        </div>
        <form id="editForm" method="POST">
            @csrf @method('PUT')
            <div class="modal-body">
                <div class="form-group mb-0">
                    <label class="form-label">Nama Kategori</label>
                    <input type="text" name="NamaKategori" id="editNama" class="form-control" required>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-modal-close="modalEdit">Batal</button>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
function openEditModal(id, nama) {
    document.getElementById('editForm').action = '/kasir/kategori/' + id;
    document.getElementById('editNama').value = nama;
    openModal('modalEdit');
}
</script>
@endpush

@extends('layouts.kasir')
@section('title', 'Pelanggan')
@section('page-title', 'Manajemen Pelanggan')

@section('content')
<div class="d-flex justify-between align-center mb-3 flex-wrap gap-2">
    <form method="GET" action="{{ route('kasir.pelanggan.index') }}" class="search-wrap">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
        <input type="text" name="search" class="form-control" placeholder="Cari nama pelanggan..." value="{{ request('search') }}" onchange="this.form.submit()">
    </form>
    <button class="btn btn-primary" onclick="openModal('modalTambah')">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
        Tambah Pelanggan
    </button>
</div>

<div class="card">
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Nama</th>
                    <th>Email</th>
                    <th>Telepon</th>
                    <th>Total Pesanan</th>
                    <th style="text-align:center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pelanggan as $p)
                <tr>
                    <td>
                        <div class="d-flex align-center gap-2">
                            <div class="avatar" style="width:34px;height:34px;background:var(--teal-500);border-radius:50%;display:flex;align-items:center;justify-content:center;color:white;font-weight:700;font-size:13px;flex-shrink:0">
                                {{ strtoupper(substr($p->NamaPelanggan, 0, 1)) }}
                            </div>
                            <div>
                                <div style="font-weight:600">{{ $p->NamaPelanggan }}</div>
                                <div class="text-sm text-muted">{{ Str::limit($p->Alamat, 30) }}</div>
                            </div>
                        </div>
                    </td>
                    <td>{{ $p->Email }}</td>
                    <td>{{ $p->NomorTelepon ?: '-' }}</td>
                    <td><span class="badge badge-neutral">{{ $p->penjualan_count }} pesanan</span></td>
                    <td style="text-align:center">
                        <div class="d-flex gap-2" style="justify-content:center">
                            <a href="{{ route('kasir.pelanggan.show', $p) }}" class="btn btn-secondary btn-sm">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                                Detail
                            </a>
                            <button class="btn btn-secondary btn-sm" onclick="openEditPelanggan({{ $p->PelangganID }}, '{{ addslashes($p->NamaPelanggan) }}', '{{ $p->Email }}', '{{ $p->NomorTelepon }}', '{{ addslashes($p->Alamat) }}')">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                                Edit
                            </button>
                            <form action="{{ route('kasir.pelanggan.destroy', $p) }}" method="POST" onsubmit="return confirm('Hapus pelanggan ini?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14H6L5 6"/></svg>
                                    Hapus
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" style="text-align:center;padding:32px;color:var(--gray-400)">Belum ada pelanggan</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($pelanggan->hasPages())
    <div class="pagination-wrap">{{ $pelanggan->links() }}</div>
    @endif
</div>

{{-- Modal Tambah --}}
<div class="modal-backdrop" id="modalTambah">
    <div class="modal-box">
        <div class="modal-header">
            <span class="modal-title">Tambah Pelanggan</span>
            <button class="modal-close" data-modal-close="modalTambah"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg></button>
        </div>
        <form action="{{ route('kasir.pelanggan.store') }}" method="POST">
            @csrf
            <div class="modal-body">
                <div class="form-group">
                    <label class="form-label">Nama Lengkap *</label>
                    <input type="text" name="NamaPelanggan" class="form-control" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Email *</label>
                    <input type="email" name="Email" class="form-control" required>
                </div>
                <div class="grid-2">
                    <div class="form-group">
                        <label class="form-label">No. Telepon</label>
                        <input type="text" name="NomorTelepon" class="form-control">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Password *</label>
                        <input type="password" name="Password" class="form-control" required>
                    </div>
                </div>
                <div class="form-group mb-0">
                    <label class="form-label">Alamat</label>
                    <textarea name="Alamat" class="form-control" rows="2"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-modal-close="modalTambah">Batal</button>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </form>
    </div>
</div>

{{-- Modal Edit --}}
<div class="modal-backdrop" id="modalEdit">
    <div class="modal-box">
        <div class="modal-header">
            <span class="modal-title">Edit Pelanggan</span>
            <button class="modal-close" data-modal-close="modalEdit"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg></button>
        </div>
        <form id="formEdit" method="POST">
            @csrf @method('PUT')
            <div class="modal-body">
                <div class="form-group">
                    <label class="form-label">Nama Lengkap *</label>
                    <input type="text" name="NamaPelanggan" id="editNama" class="form-control" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Email *</label>
                    <input type="email" name="Email" id="editEmail" class="form-control" required>
                </div>
                <div class="grid-2">
                    <div class="form-group">
                        <label class="form-label">No. Telepon</label>
                        <input type="text" name="NomorTelepon" id="editTelp" class="form-control">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Password Baru (kosongkan jika tidak diubah)</label>
                        <input type="password" name="Password" class="form-control">
                    </div>
                </div>
                <div class="form-group mb-0">
                    <label class="form-label">Alamat</label>
                    <textarea name="Alamat" id="editAlamat" class="form-control" rows="2"></textarea>
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
function openEditPelanggan(id, nama, email, telp, alamat) {
    document.getElementById('formEdit').action = '/kasir/pelanggan/' + id;
    document.getElementById('editNama').value = nama;
    document.getElementById('editEmail').value = email;
    document.getElementById('editTelp').value = telp;
    document.getElementById('editAlamat').value = alamat;
    openModal('modalEdit');
}
</script>
@endpush

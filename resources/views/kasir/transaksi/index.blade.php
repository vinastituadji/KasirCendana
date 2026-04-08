@extends('layouts.kasir')
@section('title', 'Transaksi')
@section('page-title', 'Manajemen Transaksi')

@section('content')
<div class="d-flex justify-between align-center mb-3 flex-wrap gap-2">
    <form method="GET" action="{{ route('kasir.transaksi.index') }}" class="d-flex gap-2 flex-wrap align-center">
        <select name="status_pembayaran" class="form-control" style="width:160px" onchange="this.form.submit()">
            <option value="">Semua Pembayaran</option>
            <option value="lunas" {{ request('status_pembayaran')=='lunas' ? 'selected' : '' }}>Lunas</option>
            <option value="belum_lunas" {{ request('status_pembayaran')=='belum_lunas' ? 'selected' : '' }}>Belum Lunas</option>
        </select>
        <select name="status_pesanan" class="form-control" style="width:160px" onchange="this.form.submit()">
            <option value="">Semua Status</option>
            <option value="aktif" {{ request('status_pesanan')=='aktif' ? 'selected' : '' }}>Aktif</option>
            <option value="selesai" {{ request('status_pesanan')=='selesai' ? 'selected' : '' }}>Selesai</option>
            <option value="dibatalkan" {{ request('status_pesanan')=='dibatalkan' ? 'selected' : '' }}>Dibatalkan</option>
        </select>
        @if(request('status_pembayaran') || request('status_pesanan'))
            <a href="{{ route('kasir.transaksi.index') }}" class="btn btn-outline btn-sm">Reset</a>
        @endif
    </form>
    <button class="btn btn-primary" onclick="openModal('modalTambahTransaksi')">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
        Buat Transaksi
    </button>
</div>

<div class="card">
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>#ID</th>
                    <th>Pelanggan</th>
                    <th>Produk</th>
                    <th>Total</th>
                    <th>Tgl Pesanan</th>
                    <th>Status Bayar</th>
                    <th>Status Pesanan</th>
                    <th style="text-align:center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($transaksi as $t)
                <tr>
                    <td><span class="text-muted text-sm">#{{ $t->PenjualanID }}</span></td>
                    <td><span style="font-weight:600">{{ $t->pelanggan?->NamaPelanggan }}</span></td>
                    <td>
                        <span class="text-sm text-muted">
                            {{ $t->detailPenjualan->count() }} item
                            @if($t->detailPenjualan->first()?->produk)
                                — {{ Str::limit($t->detailPenjualan->first()->produk->NamaProduk, 20) }}
                            @endif
                        </span>
                    </td>
                    <td><span style="font-weight:700">Rp {{ number_format($t->TotalHarga, 0, ',', '.') }}</span></td>
                    <td>{{ $t->TanggalPenjualan?->format('d M Y') }}</td>
                    <td>
                        @if($t->StatusPembayaran === 'lunas')
                            <span class="badge badge-success">Lunas</span>
                        @else
                            <span class="badge badge-warning">Belum Lunas</span>
                        @endif
                    </td>
                    <td>
                        @if($t->StatusPesanan === 'selesai')
                            <span class="badge badge-success">Selesai</span>
                        @elseif($t->StatusPesanan === 'aktif')
                            <span class="badge badge-info">Aktif</span>
                        @else
                            <span class="badge badge-danger">Dibatalkan</span>
                        @endif
                    </td>
                    <td>
                        <div class="d-flex gap-2" style="justify-content:center">
                            @if($t->StatusPembayaran === 'belum_lunas' && $t->StatusPesanan === 'aktif')
                                <form action="{{ route('kasir.transaksi.lunas', $t) }}" method="POST" onsubmit="return confirm('Tandai pesanan ini sebagai lunas?')">
                                    @csrf @method('PATCH')
                                    <button type="submit" class="btn btn-success btn-sm">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg>
                                        Lunas
                                    </button>
                                </form>
                                <form action="{{ route('kasir.transaksi.batalkan', $t) }}" method="POST" onsubmit="return confirm('Batalkan pesanan ini?')">
                                    @csrf @method('PATCH')
                                    <button type="submit" class="btn btn-danger btn-sm">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                                        Batal
                                    </button>
                                </form>
                            @endif
                            <a href="{{ route('kasir.transaksi.show', $t) }}" class="btn btn-secondary btn-sm">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                            </a>
                            @if($t->StatusPembayaran !== 'lunas')
                                <form action="{{ route('kasir.transaksi.destroy', $t->PenjualanID) }}" method="POST" onsubmit="return confirm('Yakin mau hapus transaksi ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">
                                        🗑 Hapus
                                    </button>
                                </form>
                                @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="9" style="text-align:center;padding:32px;color:var(--gray-400)">Belum ada transaksi</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($transaksi->hasPages())
    <div class="pagination-wrap">{{ $transaksi->links() }}</div>
    @endif
</div>

{{-- Modal Buat Transaksi --}}
<div class="modal-backdrop" id="modalTambahTransaksi">
    <div class="modal-box modal-xl">
        <div class="modal-header">
            <span class="modal-title">Buat Transaksi Baru</span>
            <button class="modal-close" data-modal-close="modalTambahTransaksi"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg></button>
        </div>
        <form action="{{ route('kasir.transaksi.store') }}" method="POST" id="formTransaksi">
            @csrf
            <div class="modal-body">
                <div class="form-group">
                    <label class="form-label">Pelanggan <span style="color:var(--red-500)">*</span></label>
                    <select name="PelangganID" class="form-control" required>
                        <option value="">-- Pilih Pelanggan --</option>
                        @foreach($pelangganList as $pl)
                            <option value="{{ $pl->PelangganID }}">{{ $pl->NamaPelanggan }} ( {{ $pl->Email }})</option>
                        @endforeach
                    </select>
                </div>

                <div class="divider"></div>

                <div class="d-flex justify-between align-center mb-2">
                    <label class="form-label mb-0">Produk yang Dipesan</label>
                    <button type="button" class="btn btn-secondary btn-sm" id="btnTambahProduk">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                        Tambah Produk
                    </button>
                </div>

                <div id="produk-container"></div>

                <div class="divider"></div>

                <div style="background:var(--sand-50);border-radius:var(--radius-md);padding:16px 20px;border:1px solid var(--sand-200)">
                    <div class="d-flex justify-between align-center">
                        <span style="font-size:14px;font-weight:600;color:var(--gray-600)">Total Pembayaran</span>
                        <span id="total-display" style="font-size:22px;font-weight:800;color:var(--sand-700)">Rp 0</span>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-modal-close="modalTambahTransaksi">Batal</button>
                <button type="submit" class="btn btn-primary">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 2L3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 01-8 0"/></svg>
                    Buat Transaksi
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
@php
    $produkData = $produkList->map(function($p) {
        return [
            'id' => $p->ProdukID,
            'nama' => $p->NamaProduk,
            'harga' => $p->Harga,
            'stok' => $p->Stok
        ];
    })->values();
@endphp

const produkData = @json($produkData);

document.getElementById('btnTambahProduk').addEventListener('click', () => {
    tambahProdukRow(produkData);
});

// Auto-add one row on modal open
document.getElementById('modalTambahTransaksi').addEventListener('click', function(e) {
    if(e.target === this && document.querySelectorAll('.produk-row').length === 0) {
        tambahProdukRow(produkData);
    }
});

document.querySelector('[onclick="openModal(\'modalTambahTransaksi\')"]')?.addEventListener('click', () => {
    if(document.querySelectorAll('.produk-row').length === 0) {
        setTimeout(() => tambahProdukRow(produkData), 50);
    }
});
</script>
@endpush
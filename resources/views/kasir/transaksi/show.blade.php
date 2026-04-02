@extends('layouts.kasir')
@section('title', 'Detail Transaksi')
@section('page-title', 'Detail Transaksi')

@section('content')
<div style="max-width:680px">
    <div class="d-flex justify-between align-center mb-4">
        <div>
            <div style="font-size:20px;font-weight:800;color:var(--gray-900)">Transaksi #{{ $penjualan->PenjualanID }}</div>
            <div class="text-sm text-muted mt-1">{{ $penjualan->TanggalPenjualan?->format('d F Y') }}</div>
        </div>
        <a href="{{ route('kasir.transaksi.index') }}" class="btn btn-secondary">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="15 18 9 12 15 6"/></svg>
            Kembali
        </a>
    </div>

    <div class="card mb-3">
        <div class="card-header"><span class="card-title">Informasi Pelanggan</span></div>
        <div class="card-body">
            <div class="grid-2">
                <div>
                    <div class="text-sm text-muted">Nama</div>
                    <div style="font-weight:600;margin-top:2px">{{ $penjualan->pelanggan?->NamaPelanggan }}</div>
                </div>
                <div>
                    <div class="text-sm text-muted">Email</div>
                    <div style="font-weight:600;margin-top:2px">{{ $penjualan->pelanggan?->Email }}</div>
                </div>
                <div>
                    <div class="text-sm text-muted">Status Pembayaran</div>
                    <div style="margin-top:4px">
                        @if($penjualan->StatusPembayaran==='lunas')
                            <span class="badge badge-success">Lunas</span>
                        @else
                            <span class="badge badge-warning">Belum Lunas</span>
                        @endif
                    </div>
                </div>
                <div>
                    <div class="text-sm text-muted">Status Pesanan</div>
                    <div style="margin-top:4px">
                        @if($penjualan->StatusPesanan==='selesai') <span class="badge badge-success">Selesai</span>
                        @elseif($penjualan->StatusPesanan==='aktif') <span class="badge badge-info">Aktif</span>
                        @else <span class="badge badge-danger">Dibatalkan</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card mb-3">
        <div class="card-header"><span class="card-title">Detail Produk</span></div>
        <div class="table-wrap">
            <table>
                <thead><tr><th>Produk</th><th>Kategori</th><th>Harga</th><th>Qty</th><th>Subtotal</th></tr></thead>
                <tbody>
                    @foreach($penjualan->detailPenjualan as $d)
                    <tr>
                        <td style="font-weight:600">{{ $d->produk?->NamaProduk ?? 'Produk dihapus' }}</td>
                        <td>{{ $d->produk?->kategori?->NamaKategori ?? '-' }}</td>
                        <td>Rp {{ number_format($d->produk?->Harga ?? 0, 0, ',', '.') }}</td>
                        <td>{{ $d->JumlahProduk }}</td>
                        <td style="font-weight:700">Rp {{ number_format($d->Subtotal, 0, ',', '.') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div style="padding:16px 20px;border-top:1px solid var(--sand-100)">
            <div class="d-flex justify-between" style="padding-top:10px;border-top:1px solid var(--sand-100)">
                <span style="font-weight:700;font-size:15px">Total</span>
                <span style="font-weight:800;font-size:20px;color:var(--sand-700)">Rp {{ number_format($penjualan->TotalHarga, 0, ',', '.') }}</span>
            </div>
        </div>
    </div>

    @if($penjualan->StatusPembayaran === 'belum_lunas' && $penjualan->StatusPesanan === 'aktif')
    <div class="d-flex gap-2">
        <form action="{{ route('kasir.transaksi.lunas', $penjualan) }}" method="POST" onsubmit="return confirm('Tandai lunas?')">
            @csrf @method('PATCH')
            <button type="submit" class="btn btn-success">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg>
                Tandai Lunas
            </button>
        </form>
        <form action="{{ route('kasir.transaksi.batalkan', $penjualan) }}" method="POST" onsubmit="return confirm('Batalkan pesanan ini?')">
            @csrf @method('PATCH')
            <button type="submit" class="btn btn-danger">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                Batalkan Pesanan
            </button>
        </form>
    </div>
    @endif
</div>
@endsection

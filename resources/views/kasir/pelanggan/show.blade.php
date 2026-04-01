@extends('layouts.kasir')
@section('title', 'Detail Pelanggan')
@section('page-title', 'Detail Pelanggan')

@section('content')
<div style="max-width:900px">
    <div class="d-flex justify-between align-center mb-4">
        <div class="d-flex align-center gap-3">
            <div style="width:52px;height:52px;background:var(--teal-500);border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:22px;font-weight:800;color:white">
                {{ strtoupper(substr($pelanggan->NamaPelanggan, 0, 1)) }}
            </div>
            <div>
                <div style="font-size:18px;font-weight:800;color:var(--gray-900)">{{ $pelanggan->NamaPelanggan }}</div>
                <div class="text-sm text-muted">{{ $pelanggan->Email }}</div>
            </div>
        </div>
        <a href="{{ route('kasir.pelanggan.index') }}" class="btn btn-secondary">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="15 18 9 12 15 6"/></svg>
            Kembali
        </a>
    </div>

    <div class="grid-3 mb-4">
        <div class="card" style="padding:18px 20px">
            <div class="text-sm text-muted mb-1">Telepon</div>
            <div style="font-weight:600">{{ $pelanggan->NomorTelepon ?: '-' }}</div>
        </div>
        <div class="card" style="padding:18px 20px">
            <div class="text-sm text-muted mb-1">Total Pesanan</div>
            <div style="font-weight:700;font-size:18px;color:var(--teal-600)">{{ $riwayat->count() }}</div>
        </div>
        <div class="card" style="padding:18px 20px">
            <div class="text-sm text-muted mb-1">Total Belanja</div>
            <div style="font-weight:700;font-size:16px;color:var(--sand-700)">
                Rp {{ number_format($riwayat->where('StatusPembayaran','lunas')->sum('TotalHarga'), 0, ',', '.') }}
            </div>
        </div>
    </div>

    <div class="card mb-3">
        <div class="card-header"><span class="card-title">Alamat</span></div>
        <div class="card-body" style="padding:16px 22px">
            {{ $pelanggan->Alamat ?: 'Alamat belum diisi' }}
        </div>
    </div>

    <div class="card">
        <div class="card-header"><span class="card-title">Riwayat Pesanan</span></div>
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>#</th><th>Tanggal</th><th>Produk</th><th>Total</th><th>Status Bayar</th><th>Status Pesanan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($riwayat as $r)
                    <tr>
                        <td class="text-muted text-sm">#{{ $r->PenjualanID }}</td>
                        <td>{{ $r->TanggalPenjualan?->format('d M Y') }}</td>
                        <td>
                            @foreach($r->detailPenjualan as $d)
                                <div class="text-sm">{{ $d->produk?->NamaProduk }} ({{ $d->JumlahProduk }}x)</div>
                            @endforeach
                        </td>
                        <td style="font-weight:700">Rp {{ number_format($r->TotalHarga, 0, ',', '.') }}</td>
                        <td>
                            @if($r->StatusPembayaran==='lunas') <span class="badge badge-success">Lunas</span>
                            @else <span class="badge badge-warning">Belum Lunas</span>
                            @endif
                        </td>
                        <td>
                            @if($r->StatusPesanan==='selesai') <span class="badge badge-success">Selesai</span>
                            @elseif($r->StatusPesanan==='aktif') <span class="badge badge-info">Aktif</span>
                            @else <span class="badge badge-danger">Dibatalkan</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="6" style="text-align:center;padding:24px;color:var(--gray-400)">Belum ada riwayat pesanan</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

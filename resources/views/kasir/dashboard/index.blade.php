@extends('layouts.kasir')
@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')
<div class="grid-4 mb-4">
    <div class="stat-card">
        <div class="stat-icon teal">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 2L3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 01-8 0"/></svg>
        </div>
        <div>
            <div class="stat-value">{{ number_format($totalProdukTerjual) }}</div>
            <div class="stat-label">Produk Terjual</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon amber">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 000 7h5a3.5 3.5 0 010 7H6"/></svg>
        </div>
        <div>
            <div class="stat-value" style="font-size:18px">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</div>
            <div class="stat-label">Total Pendapatan</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon blue">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 00-3-3.87"/><path d="M16 3.13a4 4 0 010 7.75"/></svg>
        </div>
        <div>
            <div class="stat-value">{{ number_format($totalPelanggan) }}</div>
            <div class="stat-label">Total Pelanggan</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon purple">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg>
        </div>
        <div>
            <div class="stat-value">{{ number_format($totalTransaksi) }}</div>
            <div class="stat-label">Transaksi Selesai</div>
        </div>
    </div>
</div>

{{-- Produk Terlaris --}}
<div class="card mb-4">
    <div class="card-header">
        <span class="card-title">Produk Terlaris</span>
        <a href="{{ route('kasir.produk.index') }}" class="btn btn-secondary btn-sm">Lihat Semua</a>
    </div>
    <div class="card-body" style="padding:0">
        @forelse($produkTerlaris as $item)
        <div style="display:flex;align-items:center;gap:14px;padding:12px 20px;border-bottom:1px solid var(--sand-100)">
            <div class="product-thumb">
                @if($item->produk?->Gambar)
                    <img src="{{ asset('images/products/' . $item->produk->Gambar) }}" alt="">
                @else
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M21 16V8a2 2 0 00-1-1.73l-7-4a2 2 0 00-2 0l-7 4A2 2 0 003 8v8a2 2 0 001 1.73l7 4a2 2 0 002 0l7-4A2 2 0 0021 16z"/></svg>
                @endif
            </div>
            <div style="flex:1;min-width:0">
                <div style="font-weight:600;font-size:13px;color:var(--gray-800);white-space:nowrap;overflow:hidden;text-overflow:ellipsis">
                    {{ $item->produk?->NamaProduk ?? 'Produk dihapus' }}
                </div>
                <div style="font-size:12px;color:var(--gray-500)">{{ $item->produk?->kategori?->NamaKategori }}</div>
            </div>
            <div style="text-align:right;flex-shrink:0">
                <div style="font-weight:700;font-size:14px;color:var(--teal-600)">{{ number_format($item->total_terjual) }}</div>
                <div style="font-size:11px;color:var(--gray-400)">terjual</div>
            </div>
        </div>
        @empty
        <div style="padding:24px;text-align:center;color:var(--gray-400)">Belum ada data</div>
        @endforelse
    </div>
</div>

{{-- Transaksi Terbaru --}}
<div class="card">
    <div class="card-header">
        <span class="card-title">Transaksi Terbaru</span>
        <a href="{{ route('kasir.transaksi.index') }}" class="btn btn-secondary btn-sm">Lihat Semua</a>
    </div>
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>#ID</th>
                    <th>Pelanggan</th>
                    <th>Tanggal</th>
                    <th>Total</th>
                    <th>Status Bayar</th>
                    <th>Status Pesanan</th>
                </tr>
            </thead>
            <tbody>
                @forelse($transaksiTerbaru as $t)
                <tr>
                    <td><span class="text-muted text-sm">#{{ $t->PenjualanID }}</span></td>
                    <td><span style="font-weight:600">{{ $t->pelanggan?->NamaPelanggan }}</span></td>
                    <td>{{ $t->TanggalPenjualan?->format('d M Y') }}</td>
                    <td><span style="font-weight:700">Rp {{ number_format($t->TotalHarga, 0, ',', '.') }}</span></td>
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
                </tr>
                @empty
                <tr><td colspan="6" style="text-align:center;padding:28px;color:var(--gray-400)">Belum ada transaksi</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection



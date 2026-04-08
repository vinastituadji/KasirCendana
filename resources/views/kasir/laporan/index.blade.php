@extends('layouts.kasir')
@section('title', 'Laporan')
@section('page-title', 'Laporan Penjualan')

@section('content')
{{-- Stat Cards --}}
<div class="grid-2 mb-4">
    <div class="stat-card">
        <div class="stat-icon teal">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 000 7h5a3.5 3.5 0 010 7H6"/></svg>
        </div>
        <div>
            <div class="stat-value" style="font-size:18px">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</div>
            <div class="stat-label">Total Pendapatan (Lunas)</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon amber">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg>
        </div>
        <div>
            <div class="stat-value">{{ number_format($totalTransaksiLunas) }}</div>
            <div class="stat-label">Transaksi Berhasil</div>
        </div>
    </div>
</div>

{{-- Filter --}}
<div class="card mb-4">
    <div class="card-body" style="padding:16px 24px">
        <form method="GET" action="{{ route('kasir.laporan.index') }}" class="d-flex gap-2 flex-wrap align-center">
            <!-- <div>
                <label class="form-label mb-1">Dari Tanggal</label>
                <input type="date" name="dari" class="form-control" value="{{ request('dari') }}">
            </div>
            <div>
                <label class="form-label mb-1">Sampai Tanggal</label>
                <input type="date" name="sampai" class="form-control" value="{{ request('sampai') }}">
            </div> -->
            <div>
                <label class="form-label mb-1">Status Pembayaran</label>
                <select name="status_pembayaran" class="form-control">
                    <option value="">Semua</option>
                    <option value="lunas" {{ request('status_pembayaran')=='lunas'?'selected':'' }}>Lunas</option>
                    <option value="belum_lunas" {{ request('status_pembayaran')=='belum_lunas'?'selected':'' }}>Belum Lunas</option>
                </select>
            </div>
            <div style="align-self:flex-end;display:flex;gap:8px">
                <button type="submit" class="btn btn-primary">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                    Filter
                </button>
                @if(request('dari') || request('sampai') || request('status_pembayaran'))
                    <a href="{{ route('kasir.laporan.index') }}" class="btn btn-outline">Reset</a>
                @endif
            </div>
        </form>
    </div>
</div>

<div class="grid-2 mb-4" style="align-items:start">
    {{-- Produk Terlaris --}}
    <div class="card">
        <div class="card-header"><span class="card-title">Produk Terlaris</span></div>
        <div class="table-wrap">
            <table>
                <thead><tr><th>Produk</th><th>Terjual</th><th>Pendapatan</th></tr></thead>
                <tbody>
                    @forelse($produkTerlaris as $item)
                    <tr>
                        <td style="font-weight:600;font-size:13px">{{ $item->produk?->NamaProduk ?? 'Produk dihapus' }}</td>
                        <td><span class="badge badge-info">{{ number_format($item->total_terjual) }}</span></td>
                        <td style="font-weight:700;font-size:12.5px">Rp {{ number_format($item->total_pendapatan, 0, ',', '.') }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="3" style="text-align:center;padding:20px;color:var(--gray-400)">Belum ada data</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Pesanan Masuk --}}
    <div class="card">
        <div class="card-header">
            <span class="card-title">Pesanan Masuk (Aktif)</span>
            <span class="badge badge-info">{{ $pesananMasuk->count() }}</span>
        </div>
        <div class="table-wrap">
            <table>
                <thead><tr><th>#</th><th>Pelanggan</th><th>Total</th><th>Aksi</th></tr></thead>
                <tbody>
                    @forelse($pesananMasuk as $p)
                    <tr>
                        <td class="text-muted text-sm">#{{ $p->PenjualanID }}</td>
                        <td style="font-weight:600">{{ $p->pelanggan?->NamaPelanggan }}</td>
                        <td style="font-weight:700">Rp {{ number_format($p->TotalHarga, 0, ',', '.') }}</td>
                        <td>
                            <form action="{{ route('kasir.transaksi.lunas', $p) }}" method="POST" onsubmit="return confirm('Tandai lunas?')">
                                @csrf @method('PATCH')
                                <button type="submit" class="btn btn-success btn-sm">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg>
                                    Lunas
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="4" style="text-align:center;padding:20px;color:var(--gray-400)">Tidak ada pesanan masuk</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- Riwayat Transaksi --}}
<div class="card mb-4">
    <div class="card-header"><span class="card-title">Riwayat Transaksi</span></div>
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
                    <th>Detail</th>
                </tr>
            </thead>
            <tbody>
                @forelse($penjualan as $t)
                <tr>
                    <td class="text-muted text-sm">#{{ $t->PenjualanID }}</td>
                    <td style="font-weight:600">{{ $t->pelanggan?->NamaPelanggan }}</td>
                    <td>{{ $t->TanggalPenjualan?->format('d M Y') }}</td>
                    <td style="font-weight:700">Rp {{ number_format($t->TotalHarga, 0, ',', '.') }}</td>
                    <td>
                        @if($t->StatusPembayaran==='lunas')
                            <span class="badge badge-success">Lunas</span>
                        @else
                            <span class="badge badge-warning">Belum Lunas</span>
                        @endif
                    </td>
                    <td>
                        @if($t->StatusPesanan==='selesai') <span class="badge badge-success">Selesai</span>
                        @elseif($t->StatusPesanan==='aktif') <span class="badge badge-info">Aktif</span>
                        @else <span class="badge badge-danger">Dibatalkan</span>
                        @endif
                    </td>
                    <td>
                        <button class="btn btn-secondary btn-sm" onclick="openDetailModal({{ $t->PenjualanID }})">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                        </button>
                    </td>
                </tr>
                @empty
                <tr><td colspan="8" style="text-align:center;padding:28px;color:var(--gray-400)">Belum ada data transaksi</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($penjualan->hasPages())
    <div class="pagination-wrap">{{ $penjualan->links() }}</div>
    @endif
</div>

{{-- Pesanan Dibatalkan --}}
<div class="card">
    <div class="card-header">
        <span class="card-title">Pesanan Dibatalkan</span>
        <span class="badge badge-danger">{{ $pesananDibatalkan->count() }}</span>
    </div>
    <div class="table-wrap">
        <table>
            <thead><tr><th>#</th><th>Pelanggan</th><th>Total</th><th>Tanggal</th></tr></thead>
            <tbody>
                @forelse($pesananDibatalkan as $p)
                <tr>
                    <td class="text-muted text-sm">#{{ $p->PenjualanID }}</td>
                    <td style="font-weight:600">{{ $p->pelanggan?->NamaPelanggan }}</td>
                    <td>Rp {{ number_format($p->TotalHarga, 0, ',', '.') }}</td>
                    <td>{{ $p->TanggalPenjualan?->format('d M Y') }}</td>
                </tr>
                @empty
                <tr><td colspan="4" style="text-align:center;padding:20px;color:var(--gray-400)">Tidak ada pesanan dibatalkan</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- Detail Modal --}}
<div class="modal-backdrop" id="modalDetail">
    <div class="modal-box modal-lg">
        <div class="modal-header">
            <span class="modal-title">Detail Transaksi</span>
            <button class="modal-close" data-modal-close="modalDetail"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg></button>
        </div>
        <div class="modal-body" id="detailContent" style="min-height:100px">
            <div style="text-align:center;padding:20px;color:var(--gray-400)">Memuat...</div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
@push('scripts')
<script>
@php
    $transaksiData = $penjualan->map(function($t) {
        return [
            'id' => $t->PenjualanID,
            'pelanggan' => $t->pelanggan?->NamaPelanggan,
            'tanggal' => $t->TanggalPenjualan?->format('d M Y'),
            'total' => $t->TotalHarga,
            'status_bayar' => $t->StatusPembayaran,
            'status_pesanan' => $t->StatusPesanan,
            'items' => $t->detailPenjualan->map(function($d) {
                return [
                    'nama' => $d->produk?->NamaProduk ?? 'Produk dihapus',
                    'jumlah' => $d->JumlahProduk,
                    'subtotal' => $d->Subtotal
                ];
            })->values()
        ];
    })->values();
@endphp

const transaksiData = @json($transaksiData);
</script>
@endpush

function openDetailModal(id) {
    const t = transaksiData.find(x => x.id === id);
    if (!t) return;
    let items = t.items.map(i => `
        <tr>
            <td style="font-weight:600">${i.nama}</td>
            <td>${i.jumlah}</td>
            <td style="font-weight:700">Rp ${Number(i.subtotal).toLocaleString('id-ID')}</td>
        </tr>`).join('');
    document.getElementById('detailContent').innerHTML = `
        <div style="margin-bottom:16px">
            <div class="grid-2" style="gap:12px">
                <div><div class="text-sm text-muted">Pelanggan</div><div style="font-weight:600">${t.pelanggan}</div></div>
                <div><div class="text-sm text-muted">Tanggal</div><div style="font-weight:600">${t.tanggal}</div></div>
                <div><div class="text-sm text-muted">Status Bayar</div><div><span class="badge ${t.status_bayar==='lunas'?'badge-success':'badge-warning'}">${t.status_bayar==='lunas'?'Lunas':'Belum Lunas'}</span></div></div>
                <div><div class="text-sm text-muted">Status Pesanan</div><div><span class="badge ${t.status_pesanan==='selesai'?'badge-success':t.status_pesanan==='aktif'?'badge-info':'badge-danger'}">${t.status_pesanan}</span></div></div>
            </div>
        </div>
        <table style="width:100%;border-collapse:collapse">
            <thead><tr style="background:var(--sand-50)">
                <th style="padding:8px 12px;text-align:left;font-size:11px;text-transform:uppercase;color:var(--gray-500)">Produk</th>
                <th style="padding:8px 12px;text-align:left;font-size:11px;text-transform:uppercase;color:var(--gray-500)">Qty</th>
                <th style="padding:8px 12px;text-align:left;font-size:11px;text-transform:uppercase;color:var(--gray-500)">Subtotal</th>
            </tr></thead>
            <tbody>${items}</tbody>
        </table>
        <div style="margin-top:8px;padding:12px 16px;background:var(--sand-100);border-radius:var(--radius-sm);display:flex;justify-content:space-between;align-items:center">
            <span style="font-weight:700;font-size:15px">Total</span>
            <span style="font-weight:800;font-size:18px;color:var(--sand-700)">Rp ${Number(t.total).toLocaleString('id-ID')}</span>
        </div>`;
    openModal('modalDetail');
}
</script>
@endpush
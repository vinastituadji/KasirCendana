@extends('layouts.public')
@section('title', 'Riwayat Pesanan')

@section('content')
<div style="max-width:900px;margin:0 auto">
    <div style="margin-bottom:24px">
        <h1 style="font-size:24px;font-weight:800;color:var(--gray-900);letter-spacing:-.3px">Riwayat Pesanan</h1>
        <p style="color:var(--gray-500);margin-top:4px">Semua pesanan Anda tersedia di sini</p>
    </div>

    @forelse($pesanan as $p)
    <div class="card mb-3">
        <div style="padding:16px 22px;border-bottom:1px solid var(--sand-100);display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:10px">
            <div class="d-flex align-center gap-2">
                <span class="text-muted text-sm">Pesanan #{{ $p->PenjualanID }}</span>
                <span class="text-muted text-sm">—</span>
                <span class="text-sm">{{ $p->TanggalPenjualan?->format('d M Y') }}</span>
            </div>
            <div class="d-flex gap-2 align-center">
                @if($p->StatusPembayaran==='lunas')
                    <span class="badge badge-success">Lunas</span>
                @else
                    <span class="badge badge-warning">Belum Lunas</span>
                @endif
                @if($p->StatusPesanan==='selesai')
                    <span class="badge badge-success">Selesai</span>
                @elseif($p->StatusPesanan==='aktif')
                    <span class="badge badge-info">Aktif</span>
                @else
                    <span class="badge badge-danger">Dibatalkan</span>
                @endif
            </div>
        </div>

        <div style="padding:16px 22px">
            @foreach($p->detailPenjualan as $d)
            <div style="display:flex;align-items:center;gap:14px;padding:8px 0;border-bottom:1px solid var(--sand-50)">
                <div class="product-thumb">
                    @if($d->produk?->Gambar)
                        <img src="{{ asset('images/products/' . $d->produk->Gambar) }}" alt="">
                    @else
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M21 16V8a2 2 0 00-1-1.73l-7-4a2 2 0 00-2 0l-7 4A2 2 0 003 8v8a2 2 0 001 1.73l7 4a2 2 0 002 0l7-4A2 2 0 0021 16z"/></svg>
                    @endif
                </div>
                <div style="flex:1;min-width:0">
                    <div style="font-weight:600;font-size:14px">{{ $d->produk?->NamaProduk ?? 'Produk dihapus' }}</div>
                    <div class="text-sm text-muted">{{ $d->produk?->kategori?->NamaKategori }}</div>
                </div>
                <div style="text-align:right;flex-shrink:0">
                    <div class="text-sm text-muted">{{ $d->JumlahProduk }} x Rp {{ number_format($d->produk?->Harga ?? 0, 0, ',', '.') }}</div>
                    <div style="font-weight:700">Rp {{ number_format($d->Subtotal, 0, ',', '.') }}</div>
                </div>
            </div>
            @endforeach
        </div>

        <div style="padding:14px 22px;background:var(--sand-50);border-top:1px solid var(--sand-100);display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:10px">
            <div>
                @if($p->Diskon > 0)
                <div class="text-sm text-muted">Diskon: Rp {{ number_format($p->Diskon, 0, ',', '.') }}</div>
                @endif
                <div style="font-weight:800;font-size:16px;color:var(--sand-700)">Total: Rp {{ number_format($p->TotalHarga, 0, ',', '.') }}</div>
            </div>
            <div>
                @if($p->StatusPesanan === 'aktif' && $p->StatusPembayaran === 'belum_lunas')
                <form action="{{ route('pelanggan.pesanan.batalkan', $p) }}" method="POST"
                    onsubmit="return confirm('Batalkan pesanan ini? Stok akan dikembalikan.')">
                    @csrf @method('PATCH')
                    <button type="submit" class="btn btn-danger btn-sm">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                        Batalkan Pesanan
                    </button>
                </form>
                @elseif($p->StatusPesanan === 'aktif' && $p->StatusPembayaran === 'lunas')
                <span class="text-sm text-muted" style="font-style:italic">Pesanan sudah lunas, tidak dapat dibatalkan</span>
                @endif
            </div>
        </div>
    </div>
    @empty
    <div class="card" style="text-align:center;padding:60px 20px;color:var(--gray-400)">
        <svg width="60" height="60" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.2" style="display:block;margin:0 auto 16px;opacity:.3"><path d="M6 2L3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 01-8 0"/></svg>
        <div style="font-size:16px;font-weight:600;margin-bottom:8px;color:var(--gray-600)">Belum ada pesanan</div>
        <p style="font-size:13.5px;margin-bottom:20px">Mulai pesan produk dari katalog kami</p>
        <a href="{{ route('katalog') }}" class="btn btn-primary" style="display:inline-flex;margin:0 auto">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 16V8a2 2 0 00-1-1.73l-7-4a2 2 0 00-2 0l-7 4A2 2 0 003 8v8a2 2 0 001 1.73l7 4a2 2 0 002 0l7-4A2 2 0 0021 16z"/></svg>
            Lihat Katalog
        </a>
    </div>
    @endforelse

    @if($pesanan->hasPages())
    <div class="d-flex" style="justify-content:center;margin-top:20px">{{ $pesanan->links() }}</div>
    @endif
</div>
@endsection

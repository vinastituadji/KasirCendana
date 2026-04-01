@extends('layouts.public')
@section('title', 'Katalog Produk')

@section('content')
<div style="max-width:1200px;margin:0 auto">
    {{-- Header --}}
    <div style="margin-bottom:28px">
        <h1 style="font-size:26px;font-weight:800;color:var(--gray-900);letter-spacing:-.4px">Katalog Produk</h1>
        <p style="color:var(--gray-500);margin-top:4px">Temukan furniture terbaik untuk hunian Anda</p>
    </div>

    {{-- Filter --}}
    <div class="card mb-4">
        <div class="card-body" style="padding:16px 20px">
            <form method="GET" action="{{ route('katalog') }}" class="d-flex gap-2 flex-wrap align-center">
                <div class="search-wrap" style="flex:1;max-width:360px">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                    <input type="text" name="search" class="form-control" placeholder="Cari produk furniture..." value="{{ request('search') }}">
                </div>
                <select name="kategori" class="form-control" style="width:160px" onchange="this.form.submit()">
                    <option value="">Semua Kategori</option>
                    @foreach($kategoriList as $k)
                        <option value="{{ $k->KategoriID }}" {{ request('kategori') == $k->KategoriID ? 'selected' : '' }}>{{ $k->NamaKategori }}</option>
                    @endforeach
                </select>
                <button type="submit" class="btn btn-primary">Cari</button>
                @if(request('search') || request('kategori'))
                    <a href="{{ route('katalog') }}" class="btn btn-outline">Reset</a>
                @endif
            </form>
        </div>
    </div>

    {{-- Produk Grid --}}
    @if($produk->count() > 0)
    <div class="product-grid mb-4">
        @foreach($produk as $p)
        <div class="product-card">
            <div class="product-img">
                @if($p->Gambar)
                    <img src="{{ asset('images/products/' . $p->Gambar) }}" alt="{{ $p->NamaProduk }}">
                @else
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M21 16V8a2 2 0 00-1-1.73l-7-4a2 2 0 00-2 0l-7 4A2 2 0 003 8v8a2 2 0 001 1.73l7 4a2 2 0 002 0l7-4A2 2 0 0021 16z"/><polyline points="3.27 6.96 12 12.01 20.73 6.96"/><line x1="12" y1="22.08" x2="12" y2="12"/></svg>
                @endif
            </div>
            <div class="product-info">
                <div class="product-cat">{{ $p->kategori?->NamaKategori }}</div>
                <div class="product-name">{{ $p->NamaProduk }}</div>
                <div class="product-price">Rp {{ number_format($p->Harga, 0, ',', '.') }}</div>
                <div class="product-stok">
                    @if($p->Stok > 0)
                        <span style="color:var(--green-500)">
                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="display:inline;vertical-align:middle"><polyline points="20 6 9 17 4 12"/></svg>
                            Stok tersedia ({{ $p->Stok }})
                        </span>
                    @else
                        <span style="color:var(--red-500)">Stok habis</span>
                    @endif
                </div>
            </div>
            <div class="product-actions">
                @if($p->Stok > 0)
                    @auth('pelanggan')
                        @if(auth('pelanggan')->user()->isPelanggan())
                        <button class="btn btn-primary w-full" style="justify-content:center"
                            onclick="openPesanModal({{ $p->ProdukID }}, '{{ addslashes($p->NamaProduk) }}', {{ $p->Harga }}, {{ $p->Stok }})">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 2L3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/></svg>
                            Pesan Sekarang
                        </button>
                        @else
                        <a href="{{ route('kasir.dashboard') }}" class="btn btn-secondary w-full" style="justify-content:center">Panel Kasir</a>
                        @endif
                    @else
                    <a href="{{ route('login') }}" class="btn btn-primary w-full" style="justify-content:center">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M15 3h4a2 2 0 012 2v14a2 2 0 01-2 2h-4"/><polyline points="10 17 15 12 10 7"/><line x1="15" y1="12" x2="3" y2="12"/></svg>
                        Login untuk Pesan
                    </a>
                    @endauth
                @else
                <button class="btn btn-secondary w-full" style="justify-content:center;opacity:.6;cursor:not-allowed" disabled>Stok Habis</button>
                @endif
            </div>
        </div>
        @endforeach
    </div>
    <div class="d-flex" style="justify-content:center">{{ $produk->links() }}</div>
    @else
    <div style="text-align:center;padding:60px 20px;color:var(--gray-400)">
        <svg width="60" height="60" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.2" style="display:block;margin:0 auto 16px;opacity:.3"><path d="M21 16V8a2 2 0 00-1-1.73l-7-4a2 2 0 00-2 0l-7 4A2 2 0 003 8v8a2 2 0 001 1.73l7 4a2 2 0 002 0l7-4A2 2 0 0021 16z"/></svg>
        <div style="font-size:16px;font-weight:600;margin-bottom:8px">Produk tidak ditemukan</div>
        <div style="font-size:13px">Coba kata kunci atau kategori yang berbeda</div>
    </div>
    @endif
</div>

{{-- Modal Pesan --}}
@auth('pelanggan')
@if(auth('pelanggan')->user()->isPelanggan())
<div class="modal-backdrop" id="modalPesan">
    <div class="modal-box" style="max-width:420px">
        <div class="modal-header">
            <span class="modal-title">Konfirmasi Pesanan</span>
            <button class="modal-close" data-modal-close="modalPesan"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg></button>
        </div>
        <form id="formPesan" method="POST">
            @csrf
            <div class="modal-body">
                <div style="background:var(--sand-50);border-radius:var(--radius-md);padding:14px 16px;margin-bottom:18px;border:1px solid var(--sand-200)">
                    <div style="font-weight:700;font-size:15px;color:var(--gray-800)" id="pesanNama"></div>
                    <div style="font-size:13px;color:var(--teal-600);font-weight:600;margin-top:4px" id="pesanHarga"></div>
                </div>
                <div class="form-group mb-0">
                    <label class="form-label">Jumlah Pesanan</label>
                    <input type="number" name="jumlah" id="pesanJumlah" class="form-control" value="1" min="1" oninput="updatePesanTotal()">
                    <div class="text-sm text-muted mt-1" id="pesanStokInfo"></div>
                </div>
                <div style="margin-top:14px;padding:12px 16px;background:var(--sand-100);border-radius:var(--radius-sm);display:flex;justify-content:space-between">
                    <span style="font-weight:600">Total</span>
                    <span style="font-weight:800;color:var(--sand-700)" id="pesanTotal"></span>
                </div>
                <div class="alert alert-info mt-2" style="font-size:12.5px">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="16" height="16"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                    Setelah memesan, silakan lakukan pembayaran di kasir.
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-modal-close="modalPesan">Batal</button>
                <button type="submit" class="btn btn-primary">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 2L3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/></svg>
                    Pesan Sekarang
                </button>
            </div>
        </form>
    </div>
</div>
@endif
@endauth
@endsection

@push('scripts')
<script>
let currentHarga = 0;
let currentStok = 0;

function openPesanModal(id, nama, harga, stok) {
    currentHarga = harga;
    currentStok = stok;
    document.getElementById('formPesan').action = '/akun/pesan/' + id;
    document.getElementById('pesanNama').textContent = nama;
    document.getElementById('pesanHarga').textContent = 'Rp ' + harga.toLocaleString('id-ID') + ' / item';
    document.getElementById('pesanStokInfo').textContent = 'Stok tersedia: ' + stok;
    document.getElementById('pesanJumlah').max = stok;
    document.getElementById('pesanJumlah').value = 1;
    updatePesanTotal();
    openModal('modalPesan');
}

function updatePesanTotal() {
    const jumlah = parseInt(document.getElementById('pesanJumlah').value) || 1;
    const total = currentHarga * Math.min(jumlah, currentStok);
    document.getElementById('pesanTotal').textContent = 'Rp ' + total.toLocaleString('id-ID');
}
</script>
@endpush

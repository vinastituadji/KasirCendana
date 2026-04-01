@extends('layouts.kasir')
@section('title', 'Produk')
@section('page-title', 'Manajemen Produk')

@section('content')
<div class="d-flex justify-between align-center mb-3 flex-wrap gap-2">
    <form method="GET" action="{{ route('kasir.produk.index') }}" class="d-flex gap-2 flex-wrap align-center">
        <div class="search-wrap">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
            <input type="text" name="search" class="form-control" placeholder="Cari produk..." value="{{ request('search') }}">
        </div>
        <select name="kategori" class="form-control" style="width:160px" onchange="this.form.submit()">
            <option value="">Semua Kategori</option>
            @foreach($kategoriList as $k)
                <option value="{{ $k->KategoriID }}" {{ request('kategori') == $k->KategoriID ? 'selected' : '' }}>{{ $k->NamaKategori }}</option>
            @endforeach
        </select>
        <button type="submit" class="btn btn-secondary btn-sm">Filter</button>
        @if(request('search') || request('kategori'))
            <a href="{{ route('kasir.produk.index') }}" class="btn btn-outline btn-sm">Reset</a>
        @endif
    </form>
    <a href="{{ route('kasir.produk.create') }}" class="btn btn-primary">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
        Tambah Produk
    </a>
</div>

<div class="card">
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Produk</th>
                    <th>ID</th>
                    <th>Kategori</th>
                    <th>Harga</th>
                    <th>Stok</th>
                    <th style="text-align:center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($produk as $p)
                <tr>
                    <td>
                        <div class="d-flex align-center gap-2">
                            <div class="product-thumb">
                                @if($p->Gambar)
                                    <img src="{{ asset('images/products/' . $p->Gambar) }}" alt="{{ $p->NamaProduk }}">
                                @else
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M21 16V8a2 2 0 00-1-1.73l-7-4a2 2 0 00-2 0l-7 4A2 2 0 003 8v8a2 2 0 001 1.73l7 4a2 2 0 002 0l7-4A2 2 0 0021 16z"/></svg>
                                @endif
                            </div>
                            <span style="font-weight:600">{{ $p->NamaProduk }}</span>
                        </div>
                    </td>
                    <td><span class="text-muted text-sm">#{{ $p->ProdukID }}</span></td>
                    <td><span class="badge badge-info">{{ $p->kategori?->NamaKategori }}</span></td>
                    <td><span style="font-weight:700">Rp {{ number_format($p->Harga, 0, ',', '.') }}</span></td>
                    <td>
                        @if($p->Stok <= 5)
                            <span class="badge badge-danger">{{ $p->Stok }}</span>
                        @elseif($p->Stok <= 15)
                            <span class="badge badge-warning">{{ $p->Stok }}</span>
                        @else
                            <span class="badge badge-success">{{ $p->Stok }}</span>
                        @endif
                    </td>
                    <td style="text-align:center">
                        <div class="d-flex gap-2" style="justify-content:center">
                            <a href="{{ route('kasir.produk.edit', $p) }}" class="btn btn-secondary btn-sm">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                                Edit
                            </a>
                            <form action="{{ route('kasir.produk.destroy', $p) }}" method="POST" onsubmit="return confirm('Hapus produk ini?')">
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
                <tr><td colspan="6" style="text-align:center;padding:32px;color:var(--gray-400)">
                    <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" style="display:block;margin:0 auto 10px;opacity:.3"><path d="M21 16V8a2 2 0 00-1-1.73l-7-4a2 2 0 00-2 0l-7 4A2 2 0 003 8v8a2 2 0 001 1.73l7 4a2 2 0 002 0l7-4A2 2 0 0021 16z"/></svg>
                    Tidak ada produk ditemukan
                </td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($produk->hasPages())
    <div class="pagination-wrap">
        {{ $produk->links() }}
    </div>
    @endif
</div>
@endsection

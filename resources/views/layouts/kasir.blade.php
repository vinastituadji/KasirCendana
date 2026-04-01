<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard') — KasirCendana</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    @stack('styles')
</head>
<body>

{{-- Sidebar overlay for mobile --}}
<div id="sidebarOverlay" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.5);z-index:99;"></div>

{{-- SIDEBAR --}}
<aside class="sidebar" id="sidebar">
    <div class="sidebar-logo">
        <a href="{{ route('kasir.dashboard') }}" class="brand">
            <div class="brand-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
            </div>
            <div>
                <div class="brand-name">KasirCendana</div>
                <div class="brand-sub">Panel Kasir</div>
            </div>
        </a>
    </div>

    <nav class="sidebar-nav">
        <div class="nav-section-label">Menu Utama</div>

        <a href="{{ route('kasir.dashboard') }}" class="nav-item {{ request()->routeIs('kasir.dashboard') ? 'active' : '' }}">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg>
            Dashboard
        </a>

        <a href="{{ route('kasir.transaksi.index') }}" class="nav-item {{ request()->routeIs('kasir.transaksi.*') ? 'active' : '' }}">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 2L3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 01-8 0"/></svg>
            Transaksi
        </a>

        <a href="{{ route('kasir.laporan.index') }}" class="nav-item {{ request()->routeIs('kasir.laporan.*') ? 'active' : '' }}">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/></svg>
            Laporan
        </a>

        <div class="nav-section-label">Katalog</div>

        <a href="{{ route('kasir.produk.index') }}" class="nav-item {{ request()->routeIs('kasir.produk.*') ? 'active' : '' }}">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 16V8a2 2 0 00-1-1.73l-7-4a2 2 0 00-2 0l-7 4A2 2 0 003 8v8a2 2 0 001 1.73l7 4a2 2 0 002 0l7-4A2 2 0 0021 16z"/></svg>
            Produk
        </a>

        <a href="{{ route('kasir.kategori.index') }}" class="nav-item {{ request()->routeIs('kasir.kategori.*') ? 'active' : '' }}">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="8" y1="6" x2="21" y2="6"/><line x1="8" y1="12" x2="21" y2="12"/><line x1="8" y1="18" x2="21" y2="18"/><line x1="3" y1="6" x2="3.01" y2="6"/><line x1="3" y1="12" x2="3.01" y2="12"/><line x1="3" y1="18" x2="3.01" y2="18"/></svg>
            Kategori
        </a>

        <div class="nav-section-label">Pengguna</div>

        <a href="{{ route('kasir.pelanggan.index') }}" class="nav-item {{ request()->routeIs('kasir.pelanggan.*') ? 'active' : '' }}">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 00-3-3.87"/><path d="M16 3.13a4 4 0 010 7.75"/></svg>
            Pelanggan
        </a>
    </nav>

    <div class="sidebar-footer">
        <div class="sidebar-user">
            <div class="avatar">{{ strtoupper(substr(auth('pelanggan')->user()->NamaPelanggan, 0, 1)) }}</div>
            <div>
                <div class="user-name">{{ auth('pelanggan')->user()->NamaPelanggan }}</div>
                <div class="user-role">Kasir</div>
            </div>
        </div>
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-secondary w-full" style="justify-content:center">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 21H5a2 2 0 01-2-2V5a2 2 0 012-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
                Keluar
            </button>
        </form>
    </div>
</aside>

{{-- MAIN CONTENT --}}
<div class="main-wrap">
    <header class="topbar">
        <div class="d-flex align-center gap-2">
            <button class="menu-toggle" id="menuToggle">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="12" x2="21" y2="12"/><line x1="3" y1="18" x2="21" y2="18"/></svg>
            </button>
            <span class="topbar-title">@yield('page-title', 'Dashboard')</span>
        </div>
        <div class="topbar-actions">
            <a href="{{ route('katalog') }}" class="btn btn-outline btn-sm" target="_blank">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                Lihat Toko
            </a>
        </div>
    </header>

    <main class="page-content">
        @if(session('success'))
            <div class="alert alert-success alert-auto">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg>
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-error alert-auto">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
                {{ session('error') }}
            </div>
        @endif

        @yield('content')
    </main>
</div>

{{-- FAQ BUTTON & MODAL --}}
<button class="faq-fab" id="faqBtn" title="Bantuan / FAQ">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M9.09 9a3 3 0 015.83 1c0 2-3 3-3 3"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
</button>

<div class="modal-backdrop" id="modalFAQ">
    <div class="modal-box">
        <div class="modal-header">
            <span class="modal-title">Bantuan — FAQ Kasir</span>
            <button class="modal-close" data-modal-close="modalFAQ">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
            </button>
        </div>
        <div class="modal-body" style="padding:0">
            @php
            $faqs = [
                ['q'=>'Bagaimana cara menambah produk?','a'=>'Buka menu Produk, klik tombol "Tambah Produk", isi data lengkap kemudian simpan.'],
                ['q'=>'Bagaimana cara mengubah stok produk?','a'=>'Buka menu Produk, klik tombol Edit pada produk yang ingin diubah, perbarui nilai Stok.'],
                ['q'=>'Bagaimana cara memproses pesanan pelanggan?','a'=>'Buka menu Transaksi, pesanan dari pelanggan akan muncul otomatis. Pilih pesanan dan ubah statusnya.'],
                ['q'=>'Bagaimana cara mengubah status menjadi lunas?','a'=>'Di halaman Transaksi atau Laporan, klik tombol "Tandai Lunas" pada baris pesanan yang ingin diperbarui.'],
                ['q'=>'Bagaimana cara membatalkan transaksi?','a'=>'Klik tombol "Batalkan" pada transaksi yang belum lunas. Stok akan dikembalikan otomatis.'],
                ['q'=>'Bagaimana cara melihat laporan penjualan?','a'=>'Buka menu Laporan. Gunakan filter tanggal untuk mempersempit rentang laporan.'],
                ['q'=>'Bagaimana cara melihat riwayat transaksi?','a'=>'Semua riwayat transaksi tersedia di halaman Transaksi. Gunakan filter status untuk menyaring data.'],
                ['q'=>'Bagaimana cara maintenance database?','a'=>'Gunakan phpMyAdmin di Laragon. Buka http://localhost/phpmyadmin untuk mengelola database.'],
            ];
            @endphp
            @foreach($faqs as $faq)
            <div class="faq-item">
                <div class="faq-question">
                    {{ $faq['q'] }}
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"/></svg>
                </div>
                <div class="faq-answer">{{ $faq['a'] }}</div>
            </div>
            @endforeach
        </div>
    </div>
</div>

<script src="{{ asset('js/app.js') }}"></script>
@stack('scripts')
</body>
</html>

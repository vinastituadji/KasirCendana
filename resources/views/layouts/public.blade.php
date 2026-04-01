<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Katalog') — KasirCendana</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    @stack('styles')
</head>
<body>

<nav class="public-nav">
    <a href="{{ route('katalog') }}" class="brand">
        <div class="brand-icon">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
        </div>
        <span class="brand-name">KasirCendana</span>
    </a>

    <div class="nav-links">
        <a href="{{ route('katalog') }}" class="{{ request()->routeIs('katalog*') ? 'active' : '' }}">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="display:inline;vertical-align:middle;margin-right:4px"><path d="M21 16V8a2 2 0 00-1-1.73l-7-4a2 2 0 00-2 0l-7 4A2 2 0 003 8v8a2 2 0 001 1.73l7 4a2 2 0 002 0l7-4A2 2 0 0021 16z"/></svg>
            <span>Katalog</span>
        </a>

        @auth('pelanggan')
            @if(auth('pelanggan')->user()->isPelanggan())
                <a href="{{ route('pelanggan.pesanan') }}" class="{{ request()->routeIs('pelanggan.pesanan*') ? 'active' : '' }}">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="display:inline;vertical-align:middle;margin-right:4px"><path d="M6 2L3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 01-8 0"/></svg>
                    <span>Pesanan</span>
                </a>
                <a href="{{ route('pelanggan.profil') }}" class="{{ request()->routeIs('pelanggan.profil*') ? 'active' : '' }}">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="display:inline;vertical-align:middle;margin-right:4px"><path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                    <span>{{ auth('pelanggan')->user()->NamaPelanggan }}</span>
                </a>
                <form action="{{ route('logout') }}" method="POST" style="display:inline">
                    @csrf
                    <button type="submit" class="btn btn-secondary btn-sm">Keluar</button>
                </form>
            @else
                <a href="{{ route('kasir.dashboard') }}" class="btn btn-primary btn-sm">Dashboard Kasir</a>
            @endif
        @else
            <a href="{{ route('login') }}" class="{{ request()->routeIs('login') ? 'active' : '' }}">Masuk</a>
            <a href="{{ route('register') }}" class="btn btn-primary btn-sm">Daftar</a>
        @endauth
    </div>
</nav>

<main style="min-height:calc(100vh - 64px); padding: 28px 32px;">
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
    @if(session('info'))
        <div class="alert alert-info alert-auto">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
            {{ session('info') }}
        </div>
    @endif

    @yield('content')
</main>

<footer style="background:var(--sand-800);color:var(--sand-400);text-align:center;padding:20px;font-size:12.5px;">
    &copy; {{ date('Y') }} KasirCendana — Toko Furniture
</footer>

{{-- FAQ Button --}}
@auth('pelanggan')
@if(auth('pelanggan')->user()->isPelanggan())
<button class="faq-fab" id="faqBtn" title="Bantuan / FAQ">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M9.09 9a3 3 0 015.83 1c0 2-3 3-3 3"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
</button>

<div class="modal-backdrop" id="modalFAQ">
    <div class="modal-box">
        <div class="modal-header">
            <span class="modal-title">Bantuan — FAQ Pelanggan</span>
            <button class="modal-close" data-modal-close="modalFAQ">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
            </button>
        </div>
        <div class="modal-body" style="padding:0">
            @php
            $faqs = [
                ['q'=>'Bagaimana cara memesan produk?','a'=>'Buka halaman Katalog, temukan produk yang diinginkan, klik "Pesan Sekarang" dan isi jumlah pesanan.'],
                ['q'=>'Apakah harus login untuk memesan?','a'=>'Ya, Anda perlu login atau mendaftar terlebih dahulu sebelum melakukan pemesanan.'],
                ['q'=>'Bagaimana cara melihat riwayat pesanan?','a'=>'Setelah login, klik menu "Pesanan" di navigasi atas untuk melihat semua riwayat pesanan.'],
                ['q'=>'Bagaimana cara membatalkan pesanan?','a'=>'Di halaman Pesanan, klik tombol "Batalkan Pesanan" pada pesanan yang ingin dibatalkan (hanya untuk pesanan belum lunas).'],
                ['q'=>'Mengapa pesanan tidak bisa dibatalkan?','a'=>'Pesanan yang sudah berstatus "Lunas" tidak dapat dibatalkan. Hubungi kasir untuk informasi lebih lanjut.'],
                ['q'=>'Bagaimana cara melakukan pembayaran?','a'=>'Setelah memesan, datang langsung ke kasir dan sebutkan nama atau nomor pesanan Anda untuk melakukan pembayaran.'],
                ['q'=>'Bagaimana cara mengubah data akun?','a'=>'Klik nama Anda di navigasi atas, kemudian pilih menu Profil untuk mengubah data akun.'],
                ['q'=>'Bagaimana cara melihat status pembayaran?','a'=>'Buka halaman Pesanan, status pembayaran ditampilkan di setiap baris pesanan.'],
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
@endif
@endauth

<script src="{{ asset('js/app.js') }}"></script>
@stack('scripts')
</body>
</html>

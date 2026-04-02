<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Kasir\DashboardController;
use App\Http\Controllers\Kasir\ProdukController;
use App\Http\Controllers\Kasir\KategoriController;
use App\Http\Controllers\Kasir\PelangganController;
use App\Http\Controllers\Kasir\TransaksiController;
use App\Http\Controllers\Kasir\LaporanController;
use App\Http\Controllers\Pelanggan\PelangganControllers;

// ─── PUBLIC ROUTES ─────────────────────────────────────────────────────────
Route::get('/', function () {
    return redirect()->route('katalog');
});

Route::get('/katalog', [App\Http\Controllers\Pelanggan\KatalogController::class, 'index'])->name('katalog');
Route::get('/katalog/{produk}', [App\Http\Controllers\Pelanggan\KatalogController::class, 'show'])->name('katalog.show');

// ─── AUTH ROUTES ────────────────────────────────────────────────────────────
Route::middleware('guest.pelanggan')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.post');
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// ─── KASIR ROUTES ───────────────────────────────────────────────────────────
Route::prefix('kasir')->name('kasir.')->middleware('kasir')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::resource('produk', ProdukController::class)->except(['show']);

    Route::resource('kategori', KategoriController::class)->except(['show', 'create', 'edit']);

    Route::get('/pelanggan', [PelangganController::class, 'index'])->name('pelanggan.index');
    Route::get('/pelanggan/{pelanggan}', [PelangganController::class, 'show'])->name('pelanggan.show');
    Route::post('/pelanggan', [PelangganController::class, 'store'])->name('pelanggan.store');
    Route::put('/pelanggan/{pelanggan}', [PelangganController::class, 'update'])->name('pelanggan.update');
    Route::delete('/pelanggan/{pelanggan}', [PelangganController::class, 'destroy'])->name('pelanggan.destroy');

    Route::get('/transaksi', [TransaksiController::class, 'index'])->name('transaksi.index');
    Route::post('/transaksi', [TransaksiController::class, 'store'])->name('transaksi.store');
    Route::get('/transaksi/{penjualan}', [TransaksiController::class, 'show'])->name('transaksi.show');
    Route::patch('/transaksi/{penjualan}/lunas', [TransaksiController::class, 'tandaiLunas'])->name('transaksi.lunas');
    Route::patch('/transaksi/{penjualan}/batalkan', [TransaksiController::class, 'batalkan'])->name('transaksi.batalkan');

    Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');

    Route::delete('/transaksi/{penjualan}', [TransaksiController::class, 'destroy'])->name('transaksi.destroy');
});

// ─── PELANGGAN ROUTES ───────────────────────────────────────────────────────
Route::prefix('akun')->name('pelanggan.')->middleware('pelanggan.auth')->group(function () {
    Route::post('/pesan/{produk}', [App\Http\Controllers\Pelanggan\KatalogController::class, 'pesan'])->name('pesan');
    Route::get('/pesanan', [App\Http\Controllers\Pelanggan\PesananController::class, 'index'])->name('pesanan');
    Route::patch('/pesanan/{penjualan}/batalkan', [App\Http\Controllers\Pelanggan\PesananController::class, 'batalkan'])->name('pesanan.batalkan');
    Route::get('/profil', [App\Http\Controllers\Pelanggan\ProfilController::class, 'index'])->name('profil');
    Route::put('/profil', [App\Http\Controllers\Pelanggan\ProfilController::class, 'update'])->name('profil.update');
});

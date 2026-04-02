<?php

namespace App\Http\Controllers\Pelanggan;

use App\Http\Controllers\Controller;
use App\Models\Produk;
use App\Models\Kategori;
use App\Models\Penjualan;
use App\Models\DetailPenjualan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class KatalogController extends Controller
{
    public function index(Request $request)
    {
        $query = Produk::with('kategori');
        if ($request->filled('search')) {
            $query->where('NamaProduk', 'like', '%' . $request->search . '%');
        }
        if ($request->filled('kategori')) {
            $query->where('KategoriID', $request->kategori);
        }
        $produk = $query->orderBy('NamaProduk')->paginate(12)->withQueryString();
        $kategoriList = Kategori::all();
        return view('pelanggan.katalog.index', compact('produk', 'kategoriList'));
    }

    public function show(Produk $produk)
    {
        $produk->load('kategori');
        return view('pelanggan.katalog.show', compact('produk'));
    }

    public function pesan(Request $request, Produk $produk)
    {
        if (!Auth::guard('pelanggan')->check()) {
            return redirect()->route('login')->with('info', 'Silakan login terlebih dahulu untuk memesan.');
        }
        $request->validate(['jumlah' => 'required|integer|min:1|max:' . $produk->Stok]);
        $user = Auth::guard('pelanggan')->user();
        $jumlah = $request->jumlah;
        $subtotal = $produk->Harga * $jumlah;
        DB::transaction(function () use ($user, $produk, $jumlah, $subtotal) {
            $penjualan = Penjualan::create([
                'TanggalPenjualan' => now()->toDateString(),
                'TotalHarga' => $subtotal,
                'StatusPembayaran' => 'belum_lunas',
                'StatusPesanan' => 'aktif',
                'PelangganID' => $user->PelangganID,
            ]);
            DetailPenjualan::create([
                'PenjualanID' => $penjualan->PenjualanID,
                'ProdukID' => $produk->ProdukID,
                'JumlahProduk' => $jumlah,
                'Subtotal' => $subtotal,
            ]);
            $produk->decrement('Stok', $jumlah);
        });
        return redirect()->route('pelanggan.pesanan')->with('success', 'Pesanan berhasil dibuat. Silakan lakukan pembayaran di kasir.');
    }
}

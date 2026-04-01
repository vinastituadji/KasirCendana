<?php

namespace App\Http\Controllers\Kasir;

use App\Http\Controllers\Controller;
use App\Models\Pelanggan;
use App\Models\Produk;
use App\Models\Penjualan;
use App\Models\DetailPenjualan;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $totalProdukTerjual = DetailPenjualan::sum('JumlahProduk');
        $totalPendapatan = Penjualan::where('StatusPembayaran', 'lunas')->sum('TotalHarga');
        $totalPelanggan = Pelanggan::where('Role', 'pelanggan')->count();
        $totalTransaksi = Penjualan::where('StatusPesanan', 'selesai')->count();

        // Produk terlaris
        $produkTerlaris = DetailPenjualan::select('ProdukID', DB::raw('SUM(JumlahProduk) as total_terjual'))
            ->with('produk.kategori')
            ->groupBy('ProdukID')
            ->orderByDesc('total_terjual')
            ->limit(5)
            ->get();

        // Transaksi terbaru
        $transaksiTerbaru = Penjualan::with('pelanggan')
            ->orderByDesc('created_at')
            ->limit(8)
            ->get();

        return view('kasir.dashboard.index', compact(
            'totalProdukTerjual',
            'totalPendapatan',
            'totalPelanggan',
            'totalTransaksi',
            'produkTerlaris',
            'transaksiTerbaru'
        ));
    }
}

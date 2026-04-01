<?php

namespace App\Http\Controllers\Kasir;

use App\Http\Controllers\Controller;
use App\Models\Penjualan;
use App\Models\DetailPenjualan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        $query = Penjualan::with('pelanggan', 'detailPenjualan.produk');

        if ($request->filled('dari')) {
            $query->where('TanggalPenjualan', '>=', $request->dari);
        }
        if ($request->filled('sampai')) {
            $query->where('TanggalPenjualan', '<=', $request->sampai);
        }
        if ($request->filled('status_pembayaran')) {
            $query->where('StatusPembayaran', $request->status_pembayaran);
        }

        $penjualan = $query->orderByDesc('TanggalPenjualan')->paginate(15)->withQueryString();

        $totalPendapatan = Penjualan::where('StatusPembayaran', 'lunas')
            ->when($request->dari, fn($q) => $q->where('TanggalPenjualan', '>=', $request->dari))
            ->when($request->sampai, fn($q) => $q->where('TanggalPenjualan', '<=', $request->sampai))
            ->sum('TotalHarga');

        $totalTransaksiLunas = Penjualan::where('StatusPembayaran', 'lunas')
            ->when($request->dari, fn($q) => $q->where('TanggalPenjualan', '>=', $request->dari))
            ->when($request->sampai, fn($q) => $q->where('TanggalPenjualan', '<=', $request->sampai))
            ->count();

        $produkTerlaris = DetailPenjualan::select('ProdukID', DB::raw('SUM(JumlahProduk) as total_terjual'), DB::raw('SUM(Subtotal) as total_pendapatan'))
            ->with('produk')
            ->groupBy('ProdukID')
            ->orderByDesc('total_terjual')
            ->limit(10)
            ->get();

        $pesananMasuk = Penjualan::with('pelanggan', 'detailPenjualan.produk')
            ->where('StatusPesanan', 'aktif')
            ->orderByDesc('created_at')
            ->get();

        $pesananDibatalkan = Penjualan::with('pelanggan')
            ->where('StatusPesanan', 'dibatalkan')
            ->orderByDesc('updated_at')
            ->get();

        return view('kasir.laporan.index', compact(
            'penjualan',
            'totalPendapatan',
            'totalTransaksiLunas',
            'produkTerlaris',
            'pesananMasuk',
            'pesananDibatalkan'
        ));
    }
}

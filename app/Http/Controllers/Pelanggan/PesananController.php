<?php

namespace App\Http\Controllers\Pelanggan;

use App\Http\Controllers\Controller;
use App\Models\Penjualan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PesananController extends Controller
{
    public function index()
    {
        $user = Auth::guard('pelanggan')->user();
        $pesanan = Penjualan::with('detailPenjualan.produk')
            ->where('PelangganID', $user->PelangganID)
            ->orderByDesc('TanggalPenjualan')
            ->paginate(10);
        return view('pelanggan.pesanan.index', compact('pesanan'));
    }

    public function batalkan(Penjualan $penjualan)
    {
        $user = Auth::guard('pelanggan')->user();
        if ($penjualan->PelangganID !== $user->PelangganID) abort(403);
        if ($penjualan->StatusPembayaran === 'lunas') {
            return redirect()->back()->with('error', 'Pesanan yang sudah lunas tidak dapat dibatalkan.');
        }
        DB::transaction(function () use ($penjualan) {
            foreach ($penjualan->detailPenjualan as $detail) {
                $detail->produk->increment('Stok', $detail->JumlahProduk);
            }
            $penjualan->update(['StatusPesanan' => 'dibatalkan']);
        });
        return redirect()->back()->with('success', 'Pesanan berhasil dibatalkan.');
    }
}

<?php

namespace App\Http\Controllers\Kasir;

use App\Http\Controllers\Controller;
use App\Models\Pelanggan;
use App\Models\Produk;
use App\Models\Penjualan;
use App\Models\DetailPenjualan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TransaksiController extends Controller
{
    public function index(Request $request)
    {
        $query = Penjualan::with('pelanggan', 'detailPenjualan.produk');

        if ($request->filled('status_pembayaran')) {
            $query->where('StatusPembayaran', $request->status_pembayaran);
        }
        if ($request->filled('status_pesanan')) {
            $query->where('StatusPesanan', $request->status_pesanan);
        }

        $transaksi = $query->orderByDesc('created_at')->paginate(10)->withQueryString();
        $pelangganList = Pelanggan::where('Role', 'pelanggan')->orderBy('NamaPelanggan')->get();
        $produkList = Produk::where('Stok', '>', 0)->with('kategori')->orderBy('NamaProduk')->get();

        return view('kasir.transaksi.index', compact('transaksi', 'pelangganList', 'produkList'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'PelangganID' => 'required|exists:pelanggan,PelangganID',
            'produk' => 'required|array|min:1',
            'produk.*.ProdukID' => 'required|exists:produk,ProdukID',
            'produk.*.jumlah' => 'required|integer|min:1',
            'diskon_nominal' => 'nullable|numeric|min:0',
        ]);

        DB::transaction(function () use ($request) {
            $totalHarga = 0;
            $items = [];

            foreach ($request->produk as $item) {
                $produk = Produk::findOrFail($item['ProdukID']);
                if ($produk->Stok < $item['jumlah']) {
                    throw new \Exception('Stok produk ' . $produk->NamaProduk . ' tidak mencukupi.');
                }
                $subtotal = $produk->Harga * $item['jumlah'];
                $totalHarga += $subtotal;
                $items[] = [
                    'produk' => $produk,
                    'jumlah' => $item['jumlah'],
                    'subtotal' => $subtotal,
                ];
            }

            $diskon = floatval($request->diskon_nominal ?? 0);
            $totalAkhir = max(0, $totalHarga - $diskon);

            $penjualan = Penjualan::create([
                'TanggalPenjualan' => now()->toDateString(),
                'TotalHarga' => $totalAkhir,
                'Diskon' => $diskon,
                'StatusPembayaran' => 'belum_lunas',
                'StatusPesanan' => 'aktif',
                'PelangganID' => $request->PelangganID,
            ]);

            foreach ($items as $item) {
                DetailPenjualan::create([
                    'PenjualanID' => $penjualan->PenjualanID,
                    'ProdukID' => $item['produk']->ProdukID,
                    'JumlahProduk' => $item['jumlah'],
                    'Subtotal' => $item['subtotal'],
                ]);
                $item['produk']->decrement('Stok', $item['jumlah']);
            }
        });

        return redirect()->route('kasir.transaksi.index')->with('success', 'Transaksi berhasil dibuat.');
    }

    public function tandaiLunas(Penjualan $penjualan)
    {
        $penjualan->update([
            'StatusPembayaran' => 'lunas',
            'StatusPesanan' => 'selesai',
        ]);
        return redirect()->back()->with('success', 'Pesanan #' . $penjualan->PenjualanID . ' berhasil ditandai lunas.');
    }

    public function batalkan(Penjualan $penjualan)
    {
        if ($penjualan->StatusPembayaran === 'lunas') {
            return redirect()->back()->with('error', 'Pesanan yang sudah lunas tidak dapat dibatalkan.');
        }

        DB::transaction(function () use ($penjualan) {
            foreach ($penjualan->detailPenjualan as $detail) {
                $detail->produk->increment('Stok', $detail->JumlahProduk);
            }
            $penjualan->update(['StatusPesanan' => 'dibatalkan']);
        });

        return redirect()->back()->with('success', 'Pesanan #' . $penjualan->PenjualanID . ' berhasil dibatalkan.');
    }

    public function show(Penjualan $penjualan)
    {
        $penjualan->load('pelanggan', 'detailPenjualan.produk');
        return view('kasir.transaksi.show', compact('penjualan'));
    }
}

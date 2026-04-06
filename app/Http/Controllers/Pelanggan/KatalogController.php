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
        $produk      = $query->orderBy('NamaProduk')->paginate(12)->withQueryString();
        $kategoriList = Kategori::all();

        // Ambil keranjang dari session
        $keranjang = session('keranjang', []);
        $keranjangItems = [];
        $keranjangTotal = 0;

        foreach ($keranjang as $produkId => $item) {
            $p = Produk::find($produkId);
            if ($p) {
                $subtotal = $p->Harga * $item['jumlah'];
                $keranjangItems[] = [
                    'produk'   => $p,
                    'jumlah'   => $item['jumlah'],
                    'subtotal' => $subtotal,
                ];
                $keranjangTotal += $subtotal;
            }
        }

        return view('pelanggan.katalog.index', compact(
            'produk', 'kategoriList', 'keranjangItems', 'keranjangTotal'
        ));
    }

    public function tambahKeranjang(Request $request, Produk $produk)
    {
        if (!Auth::guard('pelanggan')->check()) {
            return redirect()->route('login')->with('info', 'Silakan login terlebih dahulu untuk memesan.');
        }

        $request->validate([
            'jumlah' => 'required|integer|min:1|max:' . $produk->Stok,
        ]);

        $keranjang = session('keranjang', []);
        $id = $produk->ProdukID;

        if (isset($keranjang[$id])) {
            // Tambah jumlah jika sudah ada, tapi tidak melebihi stok
            $jumlahBaru = $keranjang[$id]['jumlah'] + $request->jumlah;
            $keranjang[$id]['jumlah'] = min($jumlahBaru, $produk->Stok);
        } else {
            $keranjang[$id] = ['jumlah' => $request->jumlah];
        }

        session(['keranjang' => $keranjang]);

        return redirect()->back()->with('success', '"' . $produk->NamaProduk . '" ditambahkan ke keranjang.');
    }

    public function updateKeranjang(Request $request, $produkId)
    {
        $request->validate(['jumlah' => 'required|integer|min:1']);

        $keranjang = session('keranjang', []);
        if (isset($keranjang[$produkId])) {
            $produk = Produk::find($produkId);
            $keranjang[$produkId]['jumlah'] = min($request->jumlah, $produk->Stok ?? $request->jumlah);
            session(['keranjang' => $keranjang]);
        }

        return redirect()->back()->with('success', 'Keranjang diperbarui.');
    }

    public function hapusKeranjang($produkId)
    {
        $keranjang = session('keranjang', []);
        unset($keranjang[$produkId]);
        session(['keranjang' => $keranjang]);

        return redirect()->back()->with('success', 'Produk dihapus dari keranjang.');
    }

    public function checkout(Request $request)
    {
        $user      = Auth::guard('pelanggan')->user();
        $keranjang = session('keranjang', []);

        if (empty($keranjang)) {
            return redirect()->back()->with('error', 'Keranjang belanja kosong.');
        }

        DB::transaction(function () use ($user, $keranjang) {
            $totalHarga = 0;
            $items = [];

            foreach ($keranjang as $produkId => $item) {
                $produk = Produk::findOrFail($produkId);
                if ($produk->Stok < $item['jumlah']) {
                    throw new \Exception('Stok produk "' . $produk->NamaProduk . '" tidak mencukupi.');
                }
                $subtotal    = $produk->Harga * $item['jumlah'];
                $totalHarga += $subtotal;
                $items[]     = ['produk' => $produk, 'jumlah' => $item['jumlah'], 'subtotal' => $subtotal];
            }

            $penjualan = Penjualan::create([
                'TanggalPenjualan' => now()->toDateString(),
                'TotalHarga'       => $totalHarga,
                'Diskon'           => 0,
                'StatusPembayaran' => 'belum_lunas',
                'StatusPesanan'    => 'aktif',
                'PelangganID'      => $user->PelangganID,
            ]);

            foreach ($items as $item) {
                DetailPenjualan::create([
                    'PenjualanID'  => $penjualan->PenjualanID,
                    'ProdukID'     => $item['produk']->ProdukID,
                    'JumlahProduk' => $item['jumlah'],
                    'Subtotal'     => $item['subtotal'],
                ]);
                $item['produk']->decrement('Stok', $item['jumlah']);
            }
        });

        // Kosongkan keranjang setelah checkout
        session()->forget('keranjang');

        return redirect()->route('pelanggan.pesanan')
            ->with('success', 'Pesanan berhasil dibuat. Silakan lakukan pembayaran di kasir.');
    }
}

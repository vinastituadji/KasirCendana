<?php

namespace App\Http\Controllers\Pelanggan;

use App\Http\Controllers\Controller;
use App\Models\Produk;
use App\Models\Kategori;
use App\Models\Penjualan;
use App\Models\DetailPenjualan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
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

        $request->validate([
            'jumlah' => 'required|integer|min:1|max:' . $produk->Stok,
        ]);

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

        if ($penjualan->PelangganID !== $user->PelangganID) {
            abort(403);
        }

        if ($penjualan->StatusPembayaran === 'lunas') {
            return redirect()->back()->with('error', 'Pesanan yang sudah lunas tidak dapat dibatalkan.');
        }

        if ($penjualan->StatusPesanan === 'dibatalkan') {
            return redirect()->back()->with('error', 'Pesanan sudah dibatalkan sebelumnya.');
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

class ProfilController extends Controller
{
    public function index()
    {
        $user = Auth::guard('pelanggan')->user();
        return view('pelanggan.profil.index', compact('user'));
    }

    public function update(Request $request)
    {
        $user = Auth::guard('pelanggan')->user();

        $request->validate([
            'NamaPelanggan' => 'required|string|max:255',
            'NomorTelepon' => 'nullable|string|max:15',
            'Alamat' => 'nullable|string',
            'Password' => 'nullable|min:6|confirmed',
        ]);

        $data = $request->only(['NamaPelanggan', 'NomorTelepon', 'Alamat']);
        if ($request->filled('Password')) {
            $data['Password'] = Hash::make($request->Password);
        }

        $user->update($data);
        return redirect()->route('pelanggan.profil')->with('success', 'Profil berhasil diperbarui.');
    }
}

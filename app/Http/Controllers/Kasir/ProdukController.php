<?php

namespace App\Http\Controllers\Kasir;

use App\Http\Controllers\Controller;
use App\Models\Produk;
use App\Models\Kategori;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProdukController extends Controller
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

        $produk = $query->orderBy('NamaProduk')->paginate(10)->withQueryString();
        $kategoriList = Kategori::all();

        return view('kasir.produk.index', compact('produk', 'kategoriList'));
    }

    public function create()
    {
        $kategoriList = Kategori::all();
        return view('kasir.produk.create', compact('kategoriList'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'NamaProduk' => 'required|string|max:255',
            'KategoriID' => 'required|exists:kategori,KategoriID',
            'Harga' => 'required|numeric|min:0',
            'Stok' => 'required|integer|min:0',
            'Gambar' => 'nullable|image|max:2048',
        ]);

        $data = $request->only(['NamaProduk', 'KategoriID', 'Harga', 'Stok']);

        if ($request->hasFile('Gambar')) {
            $gambar = $request->file('Gambar');
            $namaFile = time() . '_' . $gambar->getClientOriginalName();
            $gambar->move(public_path('images/products'), $namaFile);
            $data['Gambar'] = $namaFile;
        }

        Produk::create($data);
        return redirect()->route('kasir.produk.index')->with('success', 'Produk berhasil ditambahkan.');
    }

    public function edit(Produk $produk)
    {
        $kategoriList = Kategori::all();
        return view('kasir.produk.edit', compact('produk', 'kategoriList'));
    }

    public function update(Request $request, Produk $produk)
    {
        $request->validate([
            'NamaProduk' => 'required|string|max:255',
            'KategoriID' => 'required|exists:kategori,KategoriID',
            'Harga' => 'required|numeric|min:0',
            'Stok' => 'required|integer|min:0',
            'Gambar' => 'nullable|image|max:2048',
        ]);

        $data = $request->only(['NamaProduk', 'KategoriID', 'Harga', 'Stok']);

        if ($request->hasFile('Gambar')) {
            if ($produk->Gambar && file_exists(public_path('images/products/' . $produk->Gambar))) {
                unlink(public_path('images/products/' . $produk->Gambar));
            }
            $gambar = $request->file('Gambar');
            $namaFile = time() . '_' . $gambar->getClientOriginalName();
            $gambar->move(public_path('images/products'), $namaFile);
            $data['Gambar'] = $namaFile;
        }

        $produk->update($data);
        return redirect()->route('kasir.produk.index')->with('success', 'Produk berhasil diperbarui.');
    }

    public function destroy(Produk $produk)
    {
        // cek apakah produk pernah dipakai di detailpenjualan
    $cek = DB::table('detailpenjualan')
        ->where('ProdukID', $produk->ProdukID)
        ->exists();

    if ($cek) {
        return redirect()->route('kasir.produk.index')
            ->with('error', 'Produk sudah pernah dipesan, tidak bisa dihapus!');
    }

    // hapus gambar jika ada
    if ($produk->Gambar && file_exists(public_path('images/products/' . $produk->Gambar))) {
        unlink(public_path('images/products/' . $produk->Gambar));
    }

    // hapus produk
    $produk->delete();

    return redirect()->route('kasir.produk.index')
        ->with('success', 'Produk berhasil dihapus.');
    }
}

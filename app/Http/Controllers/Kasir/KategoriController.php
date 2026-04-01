<?php

namespace App\Http\Controllers\Kasir;

use App\Http\Controllers\Controller;
use App\Models\Kategori;
use Illuminate\Http\Request;

class KategoriController extends Controller
{
    public function index(Request $request)
    {
        $query = Kategori::withCount('produk');
        if ($request->filled('search')) {
            $query->where('NamaKategori', 'like', '%' . $request->search . '%');
        }
        $kategori = $query->orderBy('NamaKategori')->paginate(10)->withQueryString();
        return view('kasir.kategori.index', compact('kategori'));
    }

    public function store(Request $request)
    {
        $request->validate(['NamaKategori' => 'required|string|max:255|unique:kategori,NamaKategori']);
        Kategori::create(['NamaKategori' => $request->NamaKategori]);
        return redirect()->route('kasir.kategori.index')->with('success', 'Kategori berhasil ditambahkan.');
    }

    public function update(Request $request, Kategori $kategori)
    {
        $request->validate(['NamaKategori' => 'required|string|max:255|unique:kategori,NamaKategori,' . $kategori->KategoriID . ',KategoriID']);
        $kategori->update(['NamaKategori' => $request->NamaKategori]);
        return redirect()->route('kasir.kategori.index')->with('success', 'Kategori berhasil diperbarui.');
    }

    public function destroy(Kategori $kategori)
    {
        if ($kategori->produk()->count() > 0) {
            return redirect()->route('kasir.kategori.index')->with('error', 'Kategori tidak dapat dihapus karena masih memiliki produk.');
        }
        $kategori->delete();
        return redirect()->route('kasir.kategori.index')->with('success', 'Kategori berhasil dihapus.');
    }
}

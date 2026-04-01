<?php

namespace App\Http\Controllers\Kasir;

use App\Http\Controllers\Controller;
use App\Models\Pelanggan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class PelangganController extends Controller
{
    public function index(Request $request)
    {
        $query = Pelanggan::where('Role', 'pelanggan');
        if ($request->filled('search')) {
            $query->where('NamaPelanggan', 'like', '%' . $request->search . '%');
        }
        $pelanggan = $query->withCount('penjualan')->orderBy('NamaPelanggan')->paginate(10)->withQueryString();
        return view('kasir.pelanggan.index', compact('pelanggan'));
    }

    public function show(Pelanggan $pelanggan)
    {
        $riwayat = $pelanggan->penjualan()->with('detailPenjualan.produk')->orderByDesc('TanggalPenjualan')->get();
        return view('kasir.pelanggan.show', compact('pelanggan', 'riwayat'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'NamaPelanggan' => 'required|string|max:255',
            'Email' => 'required|email|unique:pelanggan,Email',
            'Password' => 'required|min:6',
            'NomorTelepon' => 'nullable|string|max:15',
            'Alamat' => 'nullable|string',
        ]);

        Pelanggan::create([
            'NamaPelanggan' => $request->NamaPelanggan,
            'Email' => $request->Email,
            'Password' => Hash::make($request->Password),
            'NomorTelepon' => $request->NomorTelepon,
            'Alamat' => $request->Alamat,
            'Role' => 'pelanggan',
        ]);

        return redirect()->route('kasir.pelanggan.index')->with('success', 'Pelanggan berhasil ditambahkan.');
    }

    public function update(Request $request, Pelanggan $pelanggan)
    {
        $request->validate([
            'NamaPelanggan' => 'required|string|max:255',
            'Email' => 'required|email|unique:pelanggan,Email,' . $pelanggan->PelangganID . ',PelangganID',
            'NomorTelepon' => 'nullable|string|max:15',
            'Alamat' => 'nullable|string',
        ]);

        $data = $request->only(['NamaPelanggan', 'Email', 'NomorTelepon', 'Alamat']);
        if ($request->filled('Password')) {
            $data['Password'] = Hash::make($request->Password);
        }
        $pelanggan->update($data);

        return redirect()->route('kasir.pelanggan.index')->with('success', 'Data pelanggan berhasil diperbarui.');
    }

    public function destroy(Pelanggan $pelanggan)
    {
        $pelanggan->delete();
        return redirect()->route('kasir.pelanggan.index')->with('success', 'Pelanggan berhasil dihapus.');
    }
}

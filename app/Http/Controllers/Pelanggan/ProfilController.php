<?php

namespace App\Http\Controllers\Pelanggan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

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

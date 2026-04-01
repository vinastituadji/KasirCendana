<?php

namespace App\Http\Controllers;

use App\Models\Pelanggan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::guard('pelanggan')->check()) {
            return $this->redirectByRole(Auth::guard('pelanggan')->user());
        }
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'Email' => 'required|email',
            'Password' => 'required',
        ], [
            'Email.required' => 'Email wajib diisi.',
            'Email.email' => 'Format email tidak valid.',
            'Password.required' => 'Password wajib diisi.',
        ]);

        $pelanggan = Pelanggan::where('Email', $request->Email)->first();

        if (!$pelanggan || !Hash::check($request->Password, $pelanggan->Password)) {
            return back()->withErrors(['Email' => 'Email atau password salah.'])->withInput();
        }

        Auth::guard('pelanggan')->login($pelanggan, $request->boolean('remember'));

        return $this->redirectByRole($pelanggan);
    }

    public function showRegister()
    {
        if (Auth::guard('pelanggan')->check()) {
            return $this->redirectByRole(Auth::guard('pelanggan')->user());
        }
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'NamaPelanggan' => 'required|string|max:255',
            'Email' => 'required|email|unique:pelanggan,Email',
            'Password' => 'required|min:6|confirmed',
            'NomorTelepon' => 'nullable|string|max:15',
            'Alamat' => 'nullable|string',
        ], [
            'NamaPelanggan.required' => 'Nama wajib diisi.',
            'Email.required' => 'Email wajib diisi.',
            'Email.unique' => 'Email sudah terdaftar.',
            'Password.required' => 'Password wajib diisi.',
            'Password.min' => 'Password minimal 6 karakter.',
            'Password.confirmed' => 'Konfirmasi password tidak cocok.',
        ]);

        $pelanggan = Pelanggan::create([
            'NamaPelanggan' => $request->NamaPelanggan,
            'Email' => $request->Email,
            'Password' => Hash::make($request->Password),
            'NomorTelepon' => $request->NomorTelepon,
            'Alamat' => $request->Alamat,
            'Role' => 'pelanggan',
        ]);

        Auth::guard('pelanggan')->login($pelanggan);

        return redirect()->route('katalog')->with('success', 'Registrasi berhasil! Selamat datang, ' . $pelanggan->NamaPelanggan . '.');
    }

    public function logout(Request $request)
    {
        Auth::guard('pelanggan')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('katalog')->with('success', 'Berhasil keluar.');
    }

    private function redirectByRole(Pelanggan $pelanggan)
    {
        if ($pelanggan->isKasir()) {
            return redirect()->route('kasir.dashboard');
        }
        return redirect()->route('katalog');
    }
}

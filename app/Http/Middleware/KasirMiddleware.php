<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KasirMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::guard('pelanggan')->check()) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
        }

        if (!Auth::guard('pelanggan')->user()->isKasir()) {
            return redirect()->route('katalog')->with('error', 'Akses tidak diizinkan.');
        }

        return $next($request);
    }
}

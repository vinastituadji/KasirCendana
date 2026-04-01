<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PelangganMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::guard('pelanggan')->check()) {
            return redirect()->route('login')->with('info', 'Silakan login terlebih dahulu.');
        }

        if (!Auth::guard('pelanggan')->user()->isPelanggan()) {
            return redirect()->route('kasir.dashboard');
        }

        return $next($request);
    }
}

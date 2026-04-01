<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GuestPelangganMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::guard('pelanggan')->check()) {
            $user = Auth::guard('pelanggan')->user();
            if ($user->isKasir()) return redirect()->route('kasir.dashboard');
            return redirect()->route('katalog');
        }
        return $next($request);
    }
}

<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, $role)
    {
        // cek apakah user sudah login
        if (!Auth::check()) {
            return redirect('/login')->with('error', 'Silakan login dulu');
        }

        // cek apakah role user sesuai dengan yang diizinkan
        if (Auth::user()->role != $role) {
            return redirect('/login')->with('error', 'Akses ditolak');
        }

        // lanjut ke request berikutnya jika lolos pengecekan
        return $next($request);
    }
}

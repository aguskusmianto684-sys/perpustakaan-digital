<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, $role)
    {
        // 🔥 cek login
        if (!Auth::check()) {
            return redirect('/login')->with('error', 'Silakan login dulu');
        }

        // 🔥 cek role
        if (Auth::user()->role != $role) {
            return redirect('/login')->with('error', 'Akses ditolak');
        }

        return $next($request);
    }
}

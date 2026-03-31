<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    // =============================
    // TAMPIL LOGIN
    // =============================
    public function showLogin()
    {
        return view('auth.login');
    }

    // =============================
    // PROSES LOGIN
    // =============================
    public function login(Request $request)
    {
        $credentials = [
            'username' => $request->username,
            'password' => $request->password
        ];

        if (Auth::attempt($credentials)) {

            $request->session()->regenerate(); // 🔥 WAJIB (Laravel 12)

            $user = Auth::user();

            // 🔥 REDIRECT SESUAI ROLE
            if ($user->role == 'kepala') {
                return redirect('/kepala/dashboard')
                    ->with('success', 'Login berhasil sebagai Kepala');
            }

            if ($user->role == 'petugas') {
                return redirect('/petugas/dashboard')
                    ->with('success', 'Login berhasil sebagai Petugas');
            }

            return redirect('/anggota/dashboard')
                ->with('success', 'Login berhasil sebagai Anggota');
        }

        return back()->with('error', 'Username atau password salah');
    }

    // =============================
    // LOGOUT (FIX TOTAL 🔥)
    // =============================
    public function logout(Request $request)
    {
        Auth::logout();

        // 🔥 HAPUS SESSION TOTAL
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login')
            ->with('success', 'Anda telah logout');
    }

    // =============================
    // TAMPIL REGISTER
    // =============================
    public function showRegister()
    {
        return view('auth.register');
    }

    // =============================
    // PROSES REGISTER
    // =============================
    public function register(Request $request)
    {
        $id_user = DB::table('users')->insertGetId([
            'username' => $request->username,
            'password' => Hash::make($request->password),
            'role' => 'anggota',
            'created_at' => now()
        ]);

        DB::table('anggota')->insert([
            'id_user' => $id_user,
            'nama' => $request->nama,
            'alamat' => $request->alamat,
            'email' => $request->email,
            'no_hp' => $request->no_hp,
            'jenis_kel' => $request->jenis_kel,
            'tgl_lahir' => $request->tgl_lahir
        ]);

        return redirect('/login')
            ->with('success', 'Registrasi berhasil');
    }
}

<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    // Tampilkan halaman login
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        // Validasi input login sederhana
        $request->validate([
            'username' => 'required',
            'password' => 'required'
        ]);

        $credentials = [
            'username' => $request->username,
            'password' => $request->password
        ];

        // Cek login user ke database
        if (Auth::attempt($credentials)) {

            $request->session()->regenerate();

            $user = Auth::user();

            // 🔥 CEK STATUS PETUGAS
            if ($user->role == 'petugas') {

                $petugas = \App\Models\Petugas::where('id_user', $user->id_user)->first();

                if ($petugas && $petugas->status == 'nonaktif') {

                    Auth::logout();

                    return back()->with('error', 'Akun petugas sudah dinonaktifkan');
                }
            }

            // Redirect sesuai role user
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

        // Jika gagal login tampilkan error
        return back()->with('error', 'Username atau password salah');
    }

    // Proses logout user dari sistem
    public function logout(Request $request)
    {
        Auth::logout();

        // Hapus semua session user
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login')
            ->with('success', 'Anda telah logout');
    }

    // Tampilkan halaman register user
    public function showRegister()
    {
        return view('auth.register');
    }

    // Proses registrasi user baru
    public function register(Request $request)
    {
        // Validasi username dan email unik
        $request->validate([
            'username' => 'required|unique:users,username',
            'password' => 'required|min:4',
            'nama' => 'required',
            'email' => 'required|email|unique:anggota,email'
        ], [
            'username.unique' => 'Username sudah digunakan',
            'email.unique' => 'Email sudah digunakan'
        ]);

        // Simpan data user baru
        $id_user = DB::table('users')->insertGetId([
            'username' => $request->username,
            'password' => Hash::make($request->password),
            'role' => 'anggota',
            'created_at' => now()
        ]);

        // Simpan data anggota baru
        DB::table('anggota')->insert([
            'id_user' => $id_user,
            'nama' => $request->nama,
            'alamat' => $request->alamat,
            'email' => $request->email,
            'no_hp' => $request->no_hp,
            'jenis_kel' => $request->jenis_kel,
            'tgl_lahir' => $request->tgl_lahir
        ]);

        // Redirect ke login setelah berhasil
        return redirect('/login')
            ->with('success', 'Registrasi berhasil');
    }
}

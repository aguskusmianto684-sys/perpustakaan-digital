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
        return view('auth.login'); // menampilkan view halaman login
    }

    public function login(Request $request)
    {
        // validasi input login (username dan password wajib diisi)
        $request->validate([
            'username' => 'required',
            'password' => 'required'
        ]);

        // mengambil data credentials dari input
        $credentials = [
            'username' => $request->username,
            'password' => $request->password
        ];

        // proses pengecekan login ke database
        if (Auth::attempt($credentials)) {

            // regenerate session untuk keamanan
            $request->session()->regenerate();

            // ambil data user yang berhasil login
            $user = Auth::user();

            // cek jika user adalah petugas
            if ($user->role == 'petugas') {

                // ambil data petugas berdasarkan user
                $petugas = \App\Models\Petugas::where('id_user', $user->id_user)->first();

                // jika status petugas nonaktif
                if ($petugas && $petugas->status == 'nonaktif') {

                    Auth::logout(); // logout paksa

                    return back()->with('error', 'Akun petugas sudah dinonaktifkan');
                }
            }

            // redirect berdasarkan role user
            if ($user->role == 'kepala') {
                return redirect('/kepala/dashboard')
                    ->with('success', 'Login berhasil sebagai Kepala');
            }

            if ($user->role == 'petugas') {
                return redirect('/petugas/dashboard')
                    ->with('success', 'Login berhasil sebagai Petugas');
            }

            // default jika role anggota
            return redirect('/anggota/dashboard')
                ->with('success', 'Login berhasil sebagai Anggota');
        }

        // jika login gagal
        return back()->with('error', 'Username atau password salah');
    }

    // proses logout user
    public function logout(Request $request)
    {
        Auth::logout(); // keluar dari sistem

        // hapus semua session
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // redirect ke halaman login
        return redirect('/login')
            ->with('success', 'Anda telah logout');
    }

    // menampilkan halaman register
    public function showRegister()
    {
        return view('auth.register'); // tampilkan view register
    }

    // proses registrasi user baru
    public function register(Request $request)
    {
        // validasi data input
        $request->validate([
            'username' => 'required|unique:users,username', // username harus unik
            'password' => 'required|min:4', // minimal 4 karakter
            'nama' => 'required',
            'email' => 'required|email|unique:anggota,email' // email unik
        ], [
            'username.unique' => 'Username sudah digunakan',
            'email.unique' => 'Email sudah digunakan'
        ]);

        // simpan data ke tabel users
        $id_user = DB::table('users')->insertGetId([
            'username' => $request->username,
            'password' => Hash::make($request->password), // enkripsi password
            'role' => 'anggota', // default role anggota
            'created_at' => now()
        ]);

        // simpan data ke tabel anggota
        DB::table('anggota')->insert([
            'id_user' => $id_user,
            'nama' => $request->nama,
            'alamat' => $request->alamat,
            'email' => $request->email,
            'no_hp' => $request->no_hp,
            'jenis_kel' => $request->jenis_kel,
            'tgl_lahir' => $request->tgl_lahir
        ]);

        // redirect ke login setelah registrasi berhasil
        return redirect('/login')
            ->with('success', 'Registrasi berhasil');
    }
}

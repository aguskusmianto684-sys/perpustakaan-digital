<?php

namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

// Model yang digunakan
use App\Models\Anggota;
use App\Models\Peminjaman;
use App\Models\User;

class AnggotaController extends Controller
{
    // Cek login dan role petugas
    public function __construct()
    {
        if (!Auth::check()) {
            redirect('/login')->with('error', 'Silakan login terlebih dahulu')->send();
        }

        if (Auth::user()->role != 'petugas') {
            redirect('/login')->with('error', 'Silakan login terlebih dahulu')->send();
        }
    }

    // Menampilkan semua data anggota
    public function index()
    {
        // Urutkan terbaru di atas
        $anggota = Anggota::orderBy('id_anggota', 'desc')->get();

        return view('petugas.anggota.index', compact('anggota'));
    }

    // Tampilkan form tambah anggota
    public function create()
    {
        return view('petugas.anggota.create');
    }



    public function store(Request $request)
    {
        // Validasi data tidak boleh duplikat
        $request->validate([
            'username' => 'required|unique:users,username',
            'password' => 'required|min:4',
            'nama' => 'required',
            'email' => 'required|email|unique:anggota,email'
        ], [
            'username.unique' => 'Username sudah digunakan',
            'email.unique' => 'Email sudah digunakan'
        ]);

        // 🔥 simpan ke tabel users
        $user = User::create([
            'username' => $request->username,
            'password' => bcrypt($request->password),
            'role' => 'anggota'
        ]);

        // 🔥 simpan ke tabel anggota
        Anggota::create([
            'id_user' => $user->id_user,
            'nama' => $request->nama,
            'alamat' => $request->alamat,
            'email' => $request->email,
            'no_hp' => $request->no_hp,
            'jenis_kel' => $request->jenis_kel,
            'tgl_lahir' => $request->tgl_lahir
        ]);

        return redirect('/petugas/anggota')
            ->with('success', 'Anggota berhasil ditambahkan');
    }

    // Detail anggota + peminjaman aktif
    public function detail($id)
    {
        $anggota = Anggota::find($id);

        $peminjaman = Peminjaman::with('buku')
            ->where('id_anggota', $id)
            ->whereIn('status', ['dipinjam', 'menunggu pengembalian']) // 🔥 FIX
            ->orderBy('tgl_pinjam', 'desc')
            ->get();

        return view('petugas.anggota.detail', compact('anggota', 'peminjaman'));
    }

    public function edit($id)
    {
        $anggota = Anggota::leftJoin('users', 'anggota.id_user', '=', 'users.id_user')
            ->where('anggota.id_anggota', $id)
            ->select('anggota.*', 'users.username')
            ->first();

        if (!$anggota) {
            return redirect('/petugas/anggota')
                ->with('error', 'Data anggota tidak ditemukan');
        }

        return view('petugas.anggota.edit', compact('anggota'));
    }

    // Update data anggota
    public function update(Request $request, $id)
    {
        $anggota = Anggota::find($id);

        if (!$anggota) {
            return redirect('/petugas/anggota')
                ->with('error', 'Data anggota tidak ditemukan');
        }

        // validasi username + email tidak duplikat
        $request->validate([
            'username' => 'required|unique:users,username,' . $anggota->id_user . ',id_user',
            'email' => 'required|email|unique:anggota,email,' . $id . ',id_anggota'
        ], [
            'username.unique' => 'Username sudah digunakan',
            'email.unique' => 'Email sudah digunakan'
        ]);

        // update username
        User::where('id_user', $anggota->id_user)
            ->update([
                'username' => $request->username
            ]);

        // update password jika diisi
        if ($request->password) {
            User::where('id_user', $anggota->id_user)
                ->update([
                    'password' => bcrypt($request->password)
                ]);
        }

        // update anggota
        $anggota->update([
            'nama' => $request->nama,
            'alamat' => $request->alamat,
            'email' => $request->email,
            'no_hp' => $request->no_hp,
            'jenis_kel' => $request->jenis_kel,
            'tgl_lahir' => $request->tgl_lahir
        ]);

        return redirect('/petugas/anggota')
            ->with('success', 'Data anggota berhasil diupdate');
    }

    // Hapus anggota (sudah aman FK)
    public function delete($id)
    {
        $anggota = Anggota::find($id);

        if (!$anggota) {
            return redirect('/petugas/anggota')
                ->with('error', 'Data anggota tidak ditemukan');
        }

        // cek peminjaman aktif
        $cek = Peminjaman::where('id_anggota', $id)
            ->where('status', 'dipinjam')
            ->count();

        if ($cek > 0) {
            return redirect('/petugas/anggota')
                ->with('error', 'Anggota masih memiliki buku yang dipinjam');
        }

        // hapus riwayat peminjaman
        Peminjaman::where('id_anggota', $id)->delete();

        // 🔥 simpan id_user dulu
        $idUser = $anggota->id_user;

        // hapus anggota
        $anggota->delete();

        // 🔥 hapus user terkait
        if ($idUser) {
            User::where('id_user', $idUser)->delete();
        }

        return redirect('/petugas/anggota')
            ->with('success', 'Anggota dan akun berhasil dihapus');
    }
}

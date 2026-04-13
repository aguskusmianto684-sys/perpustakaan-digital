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
        // cek apakah user sudah login
        if (!Auth::check()) {
            redirect('/login')->with('error', 'Silakan login terlebih dahulu')->send();
        }

        // cek apakah user memiliki role petugas
        if (Auth::user()->role != 'petugas') {
            redirect('/login')->with('error', 'Silakan login terlebih dahulu')->send();
        }
    }

    // Menampilkan semua data anggota
    public function index()
    {
        // mengambil semua data anggota dan diurutkan terbaru
        $anggota = Anggota::orderBy('id_anggota', 'desc')->get();

        // kirim data ke view
        return view('petugas.anggota.index', compact('anggota'));
    }

    // Tampilkan form tambah anggota
    public function create()
    {
        // menampilkan halaman form tambah anggota
        return view('petugas.anggota.create');
    }

    public function store(Request $request)
    {
        // validasi input agar tidak duplikat
        $request->validate([
            'username' => 'required|unique:users,username',
            'password' => 'required|min:4',
            'nama' => 'required',
            'email' => 'required|email|unique:anggota,email'
        ], [
            'username.unique' => 'Username sudah digunakan',
            'email.unique' => 'Email sudah digunakan'
        ]);

        // simpan data ke tabel users
        $user = User::create([
            'username' => $request->username,
            'password' => bcrypt($request->password), // enkripsi password
            'role' => 'anggota'
        ]);

        // simpan data ke tabel anggota
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
        // ambil data anggota berdasarkan id
        $anggota = Anggota::find($id);

        // ambil data peminjaman aktif milik anggota
        $peminjaman = Peminjaman::with('buku')
            ->where('id_anggota', $id)
            ->whereIn('status', ['dipinjam', 'menunggu pengembalian']) // hanya status aktif
            ->orderBy('tgl_pinjam', 'desc')
            ->get();

        // kirim data ke view
        return view('petugas.anggota.detail', compact('anggota', 'peminjaman'));
    }

    public function edit($id)
    {
        // ambil data anggota + username dari tabel users
        $anggota = Anggota::leftJoin('users', 'anggota.id_user', '=', 'users.id_user')
            ->where('anggota.id_anggota', $id)
            ->select('anggota.*', 'users.username')
            ->first();

        // jika data tidak ditemukan
        if (!$anggota) {
            return redirect('/petugas/anggota')
                ->with('error', 'Data anggota tidak ditemukan');
        }

        // tampilkan form edit
        return view('petugas.anggota.edit', compact('anggota'));
    }

    // Update data anggota
    public function update(Request $request, $id)
    {
        // ambil data anggota
        $anggota = Anggota::find($id);

        // jika tidak ditemukan
        if (!$anggota) {
            return redirect('/petugas/anggota')
                ->with('error', 'Data anggota tidak ditemukan');
        }

        // validasi agar username dan email tetap unik
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

        // update data anggota
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

    // Hapus anggota
    public function delete($id)
    {
        // ambil data anggota
        $anggota = Anggota::find($id);

        // jika tidak ditemukan
        if (!$anggota) {
            return redirect('/petugas/anggota')
                ->with('error', 'Data anggota tidak ditemukan');
        }

        // cek apakah masih ada buku yang dipinjam
        $cek = Peminjaman::where('id_anggota', $id)
            ->where('status', 'dipinjam')
            ->count();

        // jika masih ada pinjaman aktif
        if ($cek > 0) {
            return redirect('/petugas/anggota')
                ->with('error', 'Anggota masih memiliki buku yang dipinjam');
        }

        // hapus semua riwayat peminjaman
        Peminjaman::where('id_anggota', $id)->delete();

        // simpan id_user sebelum dihapus
        $idUser = $anggota->id_user;

        // hapus data anggota
        $anggota->delete();

        // hapus user terkait jika ada
        if ($idUser) {
            User::where('id_user', $idUser)->delete();
        }

        return redirect('/petugas/anggota')
            ->with('success', 'Anggota dan akun berhasil dihapus');
    }
}

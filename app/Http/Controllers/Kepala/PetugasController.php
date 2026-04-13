<?php

namespace App\Http\Controllers\Kepala;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

// Model yang digunakan
use App\Models\Petugas;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class PetugasController extends Controller
{
    // Cek login dan role kepala
    public function __construct()
    {
        // cek apakah user sudah login
        if (!Auth::check()) {
            redirect('/login')->with('error', 'Silakan login terlebih dahulu')->send();
        }

        // cek apakah user memiliki role kepala
        if (Auth::user()->role != 'kepala') {
            redirect('/login')->with('error', 'Silakan login terlebih dahulu')->send();
        }
    }

    // Tampilkan semua data petugas terbaru
    public function index()
    {
        // mengambil data petugas dan join ke tabel users untuk ambil username
        $petugas = Petugas::join('users', 'petugas.id_user', '=', 'users.id_user')
            ->select('petugas.*', 'users.username') // pilih field yang ditampilkan
            ->orderBy('petugas.id_petugas', 'desc') // urutkan terbaru
            ->get();

        // kirim data ke view
        return view('kepala.petugas.index', compact('petugas'));
    }

    // Tampilkan form tambah petugas
    public function create()
    {
        // menampilkan halaman form tambah petugas
        return view('kepala.petugas.create');
    }

    // Simpan data petugas baru
    public function store(Request $request)
    {
        // validasi input
        $request->validate([
            'username' => 'required|unique:users,username',
            'password' => 'required|min:4',
            'nama' => 'required',
            'email' => 'required|email|unique:petugas,email',
            'no_hp' => 'required',
            'jenis_kel' => 'required',
            'tgl_lahir' => 'required',
            'alamat' => 'required'
        ], [
            'username.unique' => 'Username sudah digunakan',
            'email.unique' => 'Email sudah digunakan'
        ]);

        try {

            // simpan data ke tabel users
            $user = User::create([
                'username' => $request->username,
                'password' => bcrypt($request->password), // enkripsi password
                'role' => 'petugas'
            ]);

            // simpan data ke tabel petugas
            Petugas::create([
                'id_user' => $user->id_user,
                'nama' => $request->nama,
                'alamat' => $request->alamat,
                'email' => $request->email,
                'no_hp' => $request->no_hp,
                'jenis_kel' => $request->jenis_kel,
                'tgl_lahir' => $request->tgl_lahir,
                'status' => 'aktif' // status default aktif
            ]);

            return redirect('/kepala/petugas')
                ->with('success', 'Petugas berhasil ditambahkan');
        } catch (\Exception $e) {
            // jika terjadi error
            return back()->with('error', $e->getMessage());
        }
    }

    // Tampilkan detail petugas
    public function detail($id)
    {
        // ambil data petugas + username berdasarkan id
        $petugas = Petugas::join('users', 'petugas.id_user', '=', 'users.id_user')
            ->where('petugas.id_petugas', $id)
            ->select('petugas.*', 'users.username')
            ->first();

        // tampilkan ke view
        return view('kepala.petugas.detail', compact('petugas'));
    }

    // Tampilkan form edit petugas
    public function edit($id)
    {
        // ambil data petugas untuk diedit
        $petugas = Petugas::join('users', 'petugas.id_user', '=', 'users.id_user')
            ->where('petugas.id_petugas', $id)
            ->select('petugas.*', 'users.username')
            ->first();

        // tampilkan ke form edit
        return view('kepala.petugas.edit', compact('petugas'));
    }

    // Update data petugas
    public function update(Request $request, $id)
    {
        // ambil data petugas berdasarkan id
        $petugas = Petugas::find($id);

        // validasi username dan email (unik kecuali data sendiri)
        $request->validate([
            'username' => 'required|unique:users,username,' . $petugas->id_user . ',id_user',
            'email' => 'required|email|unique:petugas,email,' . $id . ',id_petugas'
        ], [
            'username.unique' => 'Username sudah digunakan',
            'email.unique' => 'Email sudah digunakan'
        ]);

        // ambil data user terkait
        $user = User::where('id_user', $petugas->id_user)->first();

        if ($user) {
            $user->username = $request->username;

            // jika password diisi maka update password
            if (!empty($request->password)) {
                $user->password = Hash::make($request->password);
            }

            $user->save(); // simpan perubahan user
        }

        // update data petugas
        $petugas->update([
            'nama' => $request->nama,
            'alamat' => $request->alamat,
            'email' => $request->email,
            'no_hp' => $request->no_hp,
            'jenis_kel' => $request->jenis_kel,
            'tgl_lahir' => $request->tgl_lahir
        ]);

        return redirect('/kepala/petugas')
            ->with('success', 'Petugas berhasil diupdate');
    }

    // Hapus data petugas dan user
    public function delete($id)
    {
        // ambil data petugas
        $petugas = Petugas::find($id);

        // hapus data petugas
        Petugas::where('id_petugas', $id)->delete();

        // hapus data user yang terkait
        User::where('id_user', $petugas->id_user)->delete();

        return redirect('/kepala/petugas')
            ->with('success', 'Petugas berhasil dihapus');
    }

    // Nonaktifkan petugas
    public function nonaktif($id)
    {
        // ubah status menjadi nonaktif
        Petugas::where('id_petugas', $id)
            ->update(['status' => 'nonaktif']);

        return redirect('/kepala/petugas')
            ->with('success', 'Petugas berhasil dinonaktifkan');
    }

    // Aktifkan petugas
    public function aktif($id)
    {
        // ubah status menjadi aktif
        Petugas::where('id_petugas', $id)
            ->update(['status' => 'aktif']);

        return redirect('/kepala/petugas')
            ->with('success', 'Petugas berhasil diaktifkan');
    }
}

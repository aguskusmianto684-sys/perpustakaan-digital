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
        if (!Auth::check()) {
            redirect('/login')->with('error', 'Silakan login terlebih dahulu')->send();
        }

        if (Auth::user()->role != 'kepala') {
            redirect('/login')->with('error', 'Silakan login terlebih dahulu')->send();
        }
    }

    // Tampilkan semua data petugas terbaru
    public function index()
    {
        $petugas = Petugas::join('users', 'petugas.id_user', '=', 'users.id_user')
            ->select('petugas.*', 'users.username')
            ->orderBy('petugas.id_petugas', 'desc')
            ->get();

        return view('kepala.petugas.index', compact('petugas'));
    }

    // Tampilkan form tambah petugas
    public function create()
    {
        return view('kepala.petugas.create');
    }

    // Simpan data petugas baru
    public function store(Request $request)
    {
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

            // simpan user
            $user = User::create([
                'username' => $request->username,
                'password' => bcrypt($request->password),
                'role' => 'petugas'
            ]);

            // simpan petugas
            Petugas::create([
                'id_user' => $user->id_user,
                'nama' => $request->nama,
                'alamat' => $request->alamat,
                'email' => $request->email,
                'no_hp' => $request->no_hp,
                'jenis_kel' => $request->jenis_kel,
                'tgl_lahir' => $request->tgl_lahir,
                'status' => 'aktif'
            ]);

            return redirect('/kepala/petugas')
                ->with('success', 'Petugas berhasil ditambahkan');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    // Tampilkan detail petugas
    public function detail($id)
    {
        $petugas = Petugas::join('users', 'petugas.id_user', '=', 'users.id_user')
            ->where('petugas.id_petugas', $id)
            ->select('petugas.*', 'users.username')
            ->first();

        return view('kepala.petugas.detail', compact('petugas'));
    }

    // Tampilkan form edit petugas
    public function edit($id)
    {
        $petugas = Petugas::join('users', 'petugas.id_user', '=', 'users.id_user')
            ->where('petugas.id_petugas', $id)
            ->select('petugas.*', 'users.username')
            ->first();

        return view('kepala.petugas.edit', compact('petugas'));
    }

    // Update data petugas

    public function update(Request $request, $id)
    {
        $petugas = Petugas::find($id);

        $request->validate([
            'username' => 'required|unique:users,username,' . $petugas->id_user . ',id_user',
            'email' => 'required|email|unique:petugas,email,' . $id . ',id_petugas'
        ], [
            'username.unique' => 'Username sudah digunakan',
            'email.unique' => 'Email sudah digunakan'
        ]);

        // 🔥 UPDATE USER + PASSWORD OPSIONAL
        $user = User::where('id_user', $petugas->id_user)->first();

        if ($user) {
            $user->username = $request->username;

            if (!empty($request->password)) {
                $user->password = Hash::make($request->password);
            }

            $user->save();
        }

        // Update data petugas
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
        $petugas = Petugas::find($id);

        Petugas::where('id_petugas', $id)->delete();

        User::where('id_user', $petugas->id_user)->delete();

        return redirect('/kepala/petugas')
            ->with('success', 'Petugas berhasil dihapus');
    }

    // Nonaktifkan petugas
    public function nonaktif($id)
    {
        Petugas::where('id_petugas', $id)
            ->update(['status' => 'nonaktif']);

        return redirect('/kepala/petugas')
            ->with('success', 'Petugas berhasil dinonaktifkan');
    }

    // Aktifkan petugas
    public function aktif($id)
    {
        Petugas::where('id_petugas', $id)
            ->update(['status' => 'aktif']);

        return redirect('/kepala/petugas')
            ->with('success', 'Petugas berhasil diaktifkan');
    }
}

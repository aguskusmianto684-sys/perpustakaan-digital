<?php

namespace App\Http\Controllers\Kepala;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class PetugasController extends Controller
{

    public function __construct()
    {
        if (!Auth::check()) {
            redirect('/login')->with('error', 'Silakan login terlebih dahulu')->send();
        }

        if (Auth::user()->role != 'kepala') {
            redirect('/login')->with('error', 'Silakan login terlebih dahulu')->send();
        }
    }


    public function index()
    {

        $petugas = DB::table('petugas')
            ->join('users', 'petugas.id_user', '=', 'users.id_user')
            ->select('petugas.*', 'users.username')
            ->get();

        return view('kepala.petugas.index', compact('petugas'));
    }


    public function create()
    {
        return view('kepala.petugas.create');
    }

    public function store(Request $request)
    {

        // cek username sudah ada atau belum
        $cek = DB::table('users')
            ->where('username', $request->username)
            ->first();

        if ($cek) {
            return redirect()->back()
                ->with('error', 'Username sudah digunakan');
        }

        // insert ke users
        $id_user = DB::table('users')->insertGetId([
            'username' => $request->username,
            'password' => bcrypt($request->password),
            'role' => 'petugas',
            'created_at' => now()
        ]);

        // insert ke petugas
        DB::table('petugas')->insert([
            'id_user' => $id_user,
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
    }

    public function detail($id)
    {

        $petugas = DB::table('petugas')
            ->join('users', 'petugas.id_user', '=', 'users.id_user')
            ->where('petugas.id_petugas', $id)
            ->select('petugas.*', 'users.username')
            ->first();

        return view('kepala.petugas.detail', compact('petugas'));
    }


    public function edit($id)
    {

        $petugas = DB::table('petugas')
            ->join('users', 'petugas.id_user', '=', 'users.id_user')
            ->where('petugas.id_petugas', $id)
            ->select('petugas.*', 'users.username')
            ->first();

        return view('kepala.petugas.edit', compact('petugas'));
    }


    public function update(Request $request, $id)
    {

        $petugas = DB::table('petugas')
            ->where('id_petugas', $id)
            ->first();

        DB::table('users')
            ->where('id_user', $petugas->id_user)
            ->update([
                'username' => $request->username
            ]);

        DB::table('petugas')
            ->where('id_petugas', $id)
            ->update([
                'nama' => $request->nama,
                'alamat' => $request->alamat,
                'email' => $request->email,
                'no_hp' => $request->no_hp,
                'jenis_kel' => $request->jenis_kel,
                'tgl_lahir' => $request->tgl_lahir
            ]);

        return redirect('/kepala/petugas')->with('success', 'Petugas berhasil diupdate');
    }

    public function delete($id)
    {

        $petugas = DB::table('petugas')
            ->where('id_petugas', $id)
            ->first();

        DB::table('petugas')
            ->where('id_petugas', $id)
            ->delete();

        DB::table('users')
            ->where('id_user', $petugas->id_user)
            ->delete();

        return redirect('/kepala/petugas')
            ->with('success', 'Petugas berhasil dihapus');
    }
    public function nonaktif($id)
    {

        DB::table('petugas')
            ->where('id_petugas', $id)
            ->update([
                'status' => 'nonaktif'
            ]);

        return redirect('/kepala/petugas')
            ->with('success', 'Petugas berhasil dinonaktifkan');
    }


    public function aktif($id)
    {

        DB::table('petugas')
            ->where('id_petugas', $id)
            ->update([
                'status' => 'aktif'
            ]);

        return redirect('/kepala/petugas')
            ->with('success', 'Petugas berhasil diaktifkan');
    }
}

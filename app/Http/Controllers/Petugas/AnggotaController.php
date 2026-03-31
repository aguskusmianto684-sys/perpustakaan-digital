<?php

namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class AnggotaController extends Controller
{

    public function __construct()
    {
        if (!Auth::check()) {
            redirect('/login')->with('error', 'Silakan login terlebih dahulu')->send();
        }

        if (Auth::user()->role != 'petugas') {
            redirect('/login')->with('error', 'Silakan login terlebih dahulu')->send();
        }
    }
    public function index()
    {
        $anggota = DB::table('anggota')->get();
        return view('petugas.anggota.index', compact('anggota'));
    }

    public function create()
    {
        return view('petugas.anggota.create');
    }

    public function store(Request $request)
    {

        DB::table('anggota')->insert([
            'nama' => $request->nama,
            'alamat' => $request->alamat,
            'email' => $request->email,
            'no_hp' => $request->no_hp,
            'jenis_kel' => $request->jenis_kel,
            'tgl_lahir' => $request->tgl_lahir
        ]);

        return redirect('/petugas/anggota')->with('success', 'Anggota berhasil ditambahkan');
    }

    public function detail($id)
    {
        $anggota = DB::table('anggota')
            ->where('id_anggota', $id)
            ->first();

        return view('petugas.anggota.detail', compact('anggota'));
    }

    public function edit($id)
    {
        $anggota = DB::table('anggota')
            ->where('id_anggota', $id)
            ->first();

        return view('petugas.anggota.edit', compact('anggota'));
    }

    public function update(Request $request, $id)
    {
        DB::table('anggota')
            ->where('id_anggota', $id)
            ->update([
                'nama' => $request->nama,
                'alamat' => $request->alamat,
                'email' => $request->email,
                'no_hp' => $request->no_hp,
                'jenis_kel' => $request->jenis_kel,
                'tgl_lahir' => $request->tgl_lahir
            ]);

        return redirect('/petugas/anggota')->with('success', 'Data anggota berhasil diupdate');
    }

    public function delete($id)
    {
        DB::table('anggota')
            ->where('id_anggota', $id)
            ->delete();

        return redirect('/petugas/anggota')->with('success', 'Anggota berhasil dihapus');
    }
}

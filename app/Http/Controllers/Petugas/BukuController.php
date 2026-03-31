<?php

namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class BukuController extends Controller
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
        $buku = DB::table('buku')->get();
        return view('petugas.buku.index', compact('buku'));
    }

    public function create()
    {
        return view('petugas.buku.create');
    }

    public function store(Request $request)
    {
        // VALIDASI DATA saya
        $request->validate([
            'judul' => 'required',
            'penulis' => 'required',
            'penerbit' => 'required',
            'tahun_terbit' => 'required',
            'kategori' => 'required',
            'stok' => 'required|numeric',
            'gambar' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048'
        ]);

        $gambar = null;

        // CEK JIKA ADA GAMBARrr
        if ($request->hasFile('gambar')) {

            $file = $request->file('gambar');

            // BUAT NAMA FILE BARUuuu
            $gambar = time() . '.' . $file->getClientOriginalExtension();

            // SIMPAN FILEee
            $file->move(public_path('uploads/buku'), $gambar);
        }

        // SIMPAN KE DATABASEsss
        DB::table('buku')->insert([
            'judul' => $request->judul,
            'penulis' => $request->penulis,
            'penerbit' => $request->penerbit,
            'tahun_terbit' => $request->tahun_terbit,
            'kategori' => $request->kategori,
            'stok' => $request->stok,
            'deskripsi' => $request->deskripsi,
            'gambar' => $gambar
        ]);

        return redirect('/petugas/buku')->with('success', 'Buku berhasil ditambahkan');
    }
    public function edit($id)
    {
        $buku = DB::table('buku')->where('id_buku', $id)->first();

        return view('petugas.buku.edit', compact('buku'));
    }
    public function update(Request $request, $id)
    {
        $buku = DB::table('buku')->where('id_buku', $id)->first();

        $gambar = $buku->gambar;

        if ($request->hasFile('gambar')) {

            $file = $request->file('gambar');

            $gambar = time() . '.' . $file->getClientOriginalExtension();

            $file->move(public_path('uploads/buku'), $gambar);
        }

        DB::table('buku')->where('id_buku', $id)->update([
            'judul' => $request->judul,
            'penulis' => $request->penulis,
            'penerbit' => $request->penerbit,
            'tahun_terbit' => $request->tahun_terbit,
            'kategori' => $request->kategori,
            'stok' => $request->stok,
            'deskripsi' => $request->deskripsi,
            'gambar' => $gambar
        ]);

        return redirect('/petugas/buku')->with('success', 'Buku berhasil diupdate');
    }
    public function delete($id)
    {
        DB::table('buku')->where('id_buku', $id)->delete();

        return redirect('/petugas/buku')->with('success', 'Buku berhasil dihapus');
    }
    public function detail($id)
    {
        $buku = DB::table('buku')->where('id_buku', $id)->first();

        return view('petugas.buku.detail', compact('buku'));
    }
}

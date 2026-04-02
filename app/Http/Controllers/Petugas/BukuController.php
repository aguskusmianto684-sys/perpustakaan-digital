<?php

namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

// Import model buku
use App\Models\Buku;

class BukuController extends Controller
{
    /**
     * Constructor untuk cek login dan role
     */
    public function __construct()
    {
        // Cek apakah user sudah login
        if (!Auth::check()) {
            redirect('/login')->with('error', 'Silakan login terlebih dahulu')->send();
        }

        // Cek apakah user adalah petugas
        if (Auth::user()->role != 'petugas') {
            redirect('/login')->with('error', 'Silakan login terlebih dahulu')->send();
        }
    }

    /**
     * Menampilkan semua data buku tersedia
     */
    public function index()
    {
        // Ambil data buku urut terbaru
        $buku = Buku::orderBy('id_buku', 'desc')->get();

        return view('petugas.buku.index', compact('buku'));
    }

    /**
     * Menampilkan halaman form tambah buku
     */
    public function create()
    {
        // Tampilkan halaman form tambah buku
        return view('petugas.buku.create');
    }

    /**
     * Menyimpan data buku baru ke database
     */
    public function store(Request $request)
    {
        // Validasi input data buku dari form
        $request->validate([
            'judul' => 'required',
            'penulis' => 'required',
            'penerbit' => 'required',
            'tahun_terbit' => 'required',
            'kategori' => 'required',
            'stok' => 'required|numeric',
            'gambar' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048'
        ]);

        // Inisialisasi variabel gambar kosong
        $gambar = null;

        // Cek apakah file gambar diupload
        if ($request->hasFile('gambar')) {

            $file = $request->file('gambar');

            // Buat nama file gambar baru
            $gambar = time() . '.' . $file->getClientOriginalExtension();

            // Simpan file gambar ke folder
            $file->move(public_path('uploads/buku'), $gambar);
        }

        // Simpan data buku ke database
        Buku::create([
            'judul' => $request->judul,
            'penulis' => $request->penulis,
            'penerbit' => $request->penerbit,
            'tahun_terbit' => $request->tahun_terbit,
            'kategori' => $request->kategori,
            'stok' => $request->stok,
            'deskripsi' => $request->deskripsi,
            'gambar' => $gambar
        ]);

        // Redirect ke halaman buku dengan sukses
        return redirect('/petugas/buku')->with('success', 'Buku berhasil ditambahkan');
    }

    /**
     * Menampilkan halaman edit data buku
     */
    public function edit($id)
    {
        // Ambil data buku
        $buku = Buku::find($id);

        // 🔥 daftar kategori
        $kategori = ['Novel', 'Pendidikan', 'Komik', 'Sejarah', 'Teknologi'];

        // kirim ke view
        return view('petugas.buku.edit', compact('buku', 'kategori'));
    }

    /**
     * Mengupdate data buku yang dipilih
     */
    public function update(Request $request, $id)
    {
        // Ambil data buku berdasarkan id
        $buku = Buku::find($id);

        // Ambil gambar lama dari database
        $gambar = $buku->gambar;

        // Cek apakah ada gambar baru diupload
        if ($request->hasFile('gambar')) {

            $file = $request->file('gambar');

            // Buat nama file gambar baru
            $gambar = time() . '.' . $file->getClientOriginalExtension();

            // Simpan file gambar ke folder
            $file->move(public_path('uploads/buku'), $gambar);
        }

        // Update data buku di database
        $buku->update([
            'judul' => $request->judul,
            'penulis' => $request->penulis,
            'penerbit' => $request->penerbit,
            'tahun_terbit' => $request->tahun_terbit,
            'kategori' => $request->kategori,
            'stok' => $request->stok,
            'deskripsi' => $request->deskripsi,
            'gambar' => $gambar
        ]);

        // Redirect ke halaman buku setelah update
        return redirect('/petugas/buku')->with('success', 'Buku berhasil diupdate');
    }

    /**
     * Menghapus data buku dari database
     */
    public function delete($id)
    {
        // Hapus data buku berdasarkan id
        Buku::where('id_buku', $id)->delete();

        // Redirect ke halaman buku setelah hapus
        return redirect('/petugas/buku')->with('success', 'Buku berhasil dihapus');
    }

    /**
     * Menampilkan detail data buku lengkap
     */
    public function detail($id)
    {
        // Ambil data buku berdasarkan id
        $buku = Buku::find($id);

        // Tampilkan halaman detail buku
        return view('petugas.buku.detail', compact('buku'));
    }
}

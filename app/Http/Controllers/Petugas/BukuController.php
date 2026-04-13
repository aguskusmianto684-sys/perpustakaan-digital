<?php

namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use App\Models\Buku;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BukuController extends Controller
{
    /**
     * Constructor untuk cek login dan role
     */
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

    /**
     * Menampilkan semua data buku tersedia
     */
    public function index()
    {
        // mengambil semua data buku dan diurutkan dari terbaru
        $buku = Buku::orderBy('id_buku', 'desc')->get();

        // kirim data ke view
        return view('petugas.buku.index', compact('buku'));
    }

    /**
     * Menampilkan halaman form tambah buku
     */
    public function create()
    {
        // menampilkan halaman form tambah buku
        return view('petugas.buku.create');
    }

    /**
     * Menyimpan data buku baru ke database
     */
    public function store(Request $request)
    {
        // validasi input dari form
        $request->validate([
            'judul' => 'required',
            'penulis' => 'required',
            'penerbit' => 'required',
            'tahun_terbit' => 'required',
            'kategori' => 'required',
            'stok' => 'required|numeric',
            'gambar' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048'
        ]);

        // inisialisasi variabel gambar
        $gambar = null;

        // cek apakah ada file gambar yang diupload
        if ($request->hasFile('gambar')) {

            $file = $request->file('gambar');

            // buat nama file gambar baru
            $gambar = time() . '.' . $file->getClientOriginalExtension();

            // simpan file ke folder uploads/buku
            $file->move(public_path('uploads/buku'), $gambar);
        }

        // simpan data buku ke database
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

        // redirect ke halaman buku
        return redirect('/petugas/buku')->with('success', 'Buku berhasil ditambahkan');
    }

    /**
     * Menampilkan halaman edit data buku
     */
    public function edit($id)
    {
        // ambil data buku berdasarkan id
        $buku = Buku::find($id);

        // daftar kategori buku
        $kategori = ['Novel', 'Pendidikan', 'Komik', 'Sejarah', 'Teknologi'];

        // kirim data ke view
        return view('petugas.buku.edit', compact('buku', 'kategori'));
    }

    /**
     * Mengupdate data buku yang dipilih
     */
    public function update(Request $request, $id)
    {
        // ambil data buku
        $buku = Buku::find($id);

        // ambil gambar lama
        $gambar = $buku->gambar;

        // cek jika ada gambar baru diupload
        if ($request->hasFile('gambar')) {

            $file = $request->file('gambar');

            // buat nama file baru
            $gambar = time() . '.' . $file->getClientOriginalExtension();

            // simpan file ke folder
            $file->move(public_path('uploads/buku'), $gambar);
        }

        // update data buku
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

        // redirect setelah update
        return redirect('/petugas/buku')->with('success', 'Buku berhasil diupdate');
    }

    /**
     * Menghapus data buku dari database
     */
    public function delete($id)
    {
        // cek apakah buku masih digunakan di peminjaman
        $dipakai = \App\Models\Peminjaman::where('id_buku', $id)->exists();

        // jika masih dipakai
        if ($dipakai) {
            return redirect('/petugas/buku')
                ->with('error', 'Buku tidak bisa dihapus karena masih dipinjam');
        }

        // jika tidak dipakai maka hapus
        Buku::where('id_buku', $id)->delete();

        return redirect('/petugas/buku')
            ->with('success', 'Buku berhasil dihapus');
    }

    /**
     * Menampilkan detail data buku lengkap
     */
    public function detail($id)
    {
        // ambil data buku berdasarkan id
        $buku = Buku::find($id);

        // tampilkan ke view detail
        return view('petugas.buku.detail', compact('buku'));
    }
}

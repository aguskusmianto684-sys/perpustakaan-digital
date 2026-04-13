<?php

namespace App\Http\Controllers\Anggota;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

// Import model yang digunakan
use App\Models\Buku;
use App\Models\Anggota;
use App\Models\Peminjaman;

class BukuAnggotaController extends Controller
{
    /**
     * Constructor untuk cek login dan role
     */
    public function __construct()
    {
        // cek apakah user sudah login
        if (!Auth::check()) {
            redirect('/login')->send(); // jika belum login diarahkan ke halaman login
        }

        // cek apakah user memiliki role anggota
        if (Auth::user()->role != 'anggota') {
            redirect('/login')
                ->with('error', 'Silakan login terlebih dahulu') // kirim pesan error
                ->send();
        }
    }

    /**
     * Menampilkan daftar semua buku tersedia
     */
    public function index()
    {
        // mengambil semua data buku dari database
        $buku = Buku::all();

        // mengambil data anggota berdasarkan user yang sedang login
        $anggota = Anggota::where('id_user', Auth::user()->id_user)->first();

        // menghitung jumlah buku yang sedang dipinjam atau menunggu
        $jumlahPinjam = Peminjaman::where('id_anggota', $anggota->id_anggota)
            ->whereIn('status', ['dipinjam', 'menunggu'])
            ->count();

        // mengirim data ke halaman view
        return view('anggota.buku.index', compact('buku', 'jumlahPinjam'));
    }

    /**
     * Menampilkan detail satu data buku
     */
    public function detail($id)
    {
        // mengambil satu data buku berdasarkan id
        $buku = Buku::find($id);

        // menampilkan ke halaman detail
        return view('anggota.buku.detail', compact('buku'));
    }

    /**
     * Proses peminjaman buku secara langsung
     */
    public function pinjam($id)
    {
        // ambil data anggota dari user login
        $anggota = Anggota::where('id_user', Auth::user()->id_user)->first();

        // hitung jumlah pinjaman aktif
        $jumlahPinjam = Peminjaman::where('id_anggota', $anggota->id_anggota)
            ->whereIn('status', ['dipinjam', 'menunggu'])
            ->count();

        // validasi maksimal pinjam 3 buku
        if ($jumlahPinjam >= 3) {
            return redirect('/anggota/buku')
                ->with('error', 'Maksimal 3 buku');
        }

        // ambil data buku
        $buku = Buku::find($id);

        // cek stok buku
        if ($buku->stok <= 0) {
            return redirect('/anggota/buku')
                ->with('error', 'Stok buku habis');
        }

        // cek apakah buku sudah dipinjam oleh user
        $cek = Peminjaman::where('id_anggota', $anggota->id_anggota)
            ->where('id_buku', $id)
            ->whereIn('status', ['dipinjam', 'menunggu'])
            ->exists();

        // jika sudah dipinjam
        if ($cek) {
            return redirect('/anggota/buku')
                ->with('error', 'Buku sudah dipinjam');
        }

        // mengurangi stok buku secara langsung
        $buku->decrement('stok');

        // menyimpan data peminjaman ke database
        Peminjaman::create([
            'id_buku' => $id,
            'id_anggota' => $anggota->id_anggota,
            'tgl_pinjam' => now(), // tanggal pinjam sekarang
            'tgl_kembali' => now()->addDays(7), // batas pengembalian 7 hari
            'status' => 'menunggu' // status awal
        ]);

        return redirect('/anggota/buku')
            ->with('success', 'Peminjaman berhasil diajukan');
    }

    /**
     * Menampilkan form untuk pinjam buku
     */
    public function formPinjam($id)
    {
        // ambil data buku
        $buku = Buku::find($id);

        // ambil data user login
        $user = Auth::user();

        // tampilkan form peminjaman
        return view('anggota.peminjaman.create', compact('buku', 'user'));
    }

    /**
     * Menyimpan data peminjaman dari form
     */
    public function storePinjam(Request $request)
    {
        // ambil data anggota
        $anggota = Anggota::where('id_user', Auth::user()->id_user)->first();

        // hitung jumlah pinjaman aktif
        $jumlahPinjam = Peminjaman::where('id_anggota', $anggota->id_anggota)
            ->whereIn('status', ['dipinjam', 'menunggu'])
            ->count();

        // validasi maksimal 3 buku
        if ($jumlahPinjam >= 3) {
            return redirect('/anggota/buku')
                ->with('error', 'Maksimal pinjam 3 buku');
        }

        // ambil data buku dari request
        $buku = Buku::find($request->id_buku);

        // cek stok buku
        if ($buku->stok <= 0) {
            return back()->with('error', 'Stok buku habis');
        }

        // cek apakah sudah pernah pinjam buku ini
        $cek = Peminjaman::where('id_anggota', $anggota->id_anggota)
            ->where('id_buku', $request->id_buku)
            ->whereIn('status', ['dipinjam', 'menunggu'])
            ->exists();

        // jika sudah
        if ($cek) {
            return back()->with('error', 'Buku sudah dipinjam');
        }

        // mengurangi stok buku
        $buku->decrement('stok');

        // simpan data peminjaman
        Peminjaman::create([
            'id_anggota' => $anggota->id_anggota,
            'id_petugas' => null, // belum diproses petugas
            'id_buku' => $request->id_buku,
            'tgl_pinjam' => now(),
            'tgl_kembali' => now()->addDays(7),
            'status' => 'menunggu'
        ]);

        return redirect('/anggota/buku')
            ->with('success', 'Peminjaman berhasil');
    }

    /**
     * Menampilkan data peminjaman yang masih aktif
     */
    public function peminjamanSaya()
    {
        // ambil data anggota login
        $anggota = Anggota::where('id_user', Auth::user()->id_user)->first();

        // ambil data peminjaman + join ke tabel buku
        $data = Peminjaman::join('buku', 'peminjaman.id_buku', '=', 'buku.id_buku')
            ->where('peminjaman.id_anggota', $anggota->id_anggota)

            // 🔥 INI YANG DIPERBAIKI
            ->whereIn('peminjaman.status', ['menunggu', 'dipinjam', 'menunggu pengembalian'])

            ->select('peminjaman.*', 'buku.judul', 'buku.gambar')
            ->orderBy('peminjaman.tgl_pinjam', 'desc')
            ->get();

        return view('anggota.peminjaman.index', compact('data'));
    }

    public function ajukanPengembalian($id)
    {
        // ambil data peminjaman
        $pinjam = \App\Models\Peminjaman::find($id);

        // jika data tidak ditemukan
        if (!$pinjam) {
            return back()->with('error', 'Data tidak ditemukan');
        }

        // hanya bisa ajukan jika status dipinjam
        if ($pinjam->status != 'dipinjam') {
            return back()->with('error', 'Tidak bisa ajukan pengembalian');
        }

        // update status menjadi menunggu pengembalian
        $pinjam->update([
            'status' => 'menunggu pengembalian'
        ]);

        return back()->with('success', 'Pengembalian berhasil diajukan, tunggu konfirmasi petugas');
    }

    public function detailPeminjaman($id)
    {
        // ambil detail peminjaman beserta relasi buku
        $data = \App\Models\Peminjaman::with('buku')
            ->where('id_peminjaman', $id)
            ->first();

        // tampilkan ke view
        return view('anggota.riwayat.detail', compact('data'));
    }

    /**
     * Menampilkan semua riwayat peminjaman buku
     */
    public function riwayat()
    {
        // ambil anggota dari user login menggunakan relasi
        $anggota = Auth::user()->anggota;

        // ambil data riwayat peminjaman
        $data = Peminjaman::with('buku')
            ->where('id_anggota', $anggota->id_anggota)
            ->whereIn('status', ['dikembalikan', 'ditolak'])
            ->orderBy('tgl_pinjam', 'desc')
            ->get();

        // tampilkan ke view
        return view('anggota.riwayat.index', compact('data'));
    }
}

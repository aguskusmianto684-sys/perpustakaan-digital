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
        // Cek apakah user sudah login
        if (!Auth::check()) {
            redirect('/login')->send();
        }

        // Cek apakah user adalah anggota
        if (Auth::user()->role != 'anggota') {
            redirect('/login')
                ->with('error', 'Silakan login terlebih dahulu')
                ->send();
        }
    }

    /**
     * Menampilkan daftar semua buku tersedia
     */
    public function index()
    {
        // Ambil semua data buku
        $buku = Buku::all();

        // Ambil data anggota dari user login
        $anggota = Anggota::where('id_user', Auth::user()->id_user)->first();

        // Hitung jumlah pinjaman yang masih aktif
        $jumlahPinjam = Peminjaman::where('id_anggota', $anggota->id_anggota)
            ->whereIn('status', ['dipinjam', 'menunggu'])
            ->count();

        // Kirim data buku ke halaman view
        return view('anggota.buku.index', compact('buku', 'jumlahPinjam'));
    }

    /**
     * Menampilkan detail satu data buku
     */
    public function detail($id)
    {
        // Ambil satu buku berdasarkan id
        $buku = Buku::find($id);

        // Tampilkan data buku ke halaman detail
        return view('anggota.buku.detail', compact('buku'));
    }

    /**
     * Proses peminjaman buku secara langsung
     */
    public function pinjam($id)
    {
        // Ambil data anggota berdasarkan user login
        $anggota = Anggota::where('id_user', Auth::user()->id_user)->first();

        // Hitung jumlah pinjaman yang masih aktif
        $jumlahPinjam = Peminjaman::where('id_anggota', $anggota->id_anggota)
            ->whereIn('status', ['dipinjam', 'menunggu'])
            ->count();

        // Cek batas maksimal peminjaman buku
        if ($jumlahPinjam >= 3) {
            return redirect('/anggota/buku')
                ->with('error', 'Maksimal 3 buku');
        }

        // Cek apakah buku sudah dipinjam
        $cek = Peminjaman::where('id_anggota', $anggota->id_anggota)
            ->where('id_buku', $id)
            ->whereIn('status', ['dipinjam', 'menunggu'])
            ->exists();

        // Jika sudah dipinjam tampilkan pesan
        if ($cek) {
            return redirect('/anggota/buku')
                ->with('error', 'Buku sudah dipinjam');
        }

        // Simpan data peminjaman baru ke database
        Peminjaman::create([
            'id_buku' => $id,
            'id_anggota' => $anggota->id_anggota,
            'tgl_pinjam' => now(),
            'tgl_kembali' => now()->addDays(7),
            'status' => 'menunggu'
        ]);

        // Kembali ke halaman buku dengan sukses
        return redirect('/anggota/buku')
            ->with('success', 'Request peminjaman berhasil');
    }

    /**
     * Menampilkan form untuk pinjam buku
     */
    public function formPinjam($id)
    {
        // Ambil data buku berdasarkan id
        $buku = Buku::find($id);

        // Ambil data user yang sedang login
        $user = Auth::user();

        // Tampilkan halaman form peminjaman buku
        return view('anggota.peminjaman.create', compact('buku', 'user'));
    }

    /**
     * Menyimpan data peminjaman dari form
     */
    public function storePinjam(Request $request)
    {
        // Ambil data anggota dari user login
        $anggota = Anggota::where('id_user', Auth::user()->id_user)->first();

        // Hitung jumlah pinjaman yang masih aktif
        $jumlahPinjam = Peminjaman::where('id_anggota', $anggota->id_anggota)
            ->whereIn('status', ['dipinjam', 'menunggu'])
            ->count();

        // Cek batas maksimal peminjaman buku
        if ($jumlahPinjam >= 3) {
            return redirect('/anggota/buku')
                ->with('error', 'Maksimal pinjam 3 buku');
        }

        // Cek apakah buku sudah dipinjam
        $cek = Peminjaman::where('id_anggota', $anggota->id_anggota)
            ->where('id_buku', $request->id_buku)
            ->whereIn('status', ['dipinjam', 'menunggu'])
            ->exists();

        // Jika sudah dipinjam tampilkan pesan
        if ($cek) {
            return back()->with('error', 'Buku sudah dipinjam');
        }

        // Simpan data peminjaman ke database
        Peminjaman::create([
            'id_anggota' => $anggota->id_anggota,
            'id_petugas' => null,
            'id_buku' => $request->id_buku,
            'tgl_pinjam' => now(),
            'tgl_kembali' => now()->addDays(7),
            'status' => 'menunggu'
        ]);

        // Kembali ke halaman buku dengan sukses
        return redirect('/anggota/buku')
            ->with('success', 'Peminjaman berhasil');
    }

    /**
     * Menampilkan data peminjaman yang masih aktif
     */
    public function peminjamanSaya()
    {
        // Ambil data anggota dari user login
        $anggota = Anggota::where('id_user', Auth::user()->id_user)->first();

        // Ambil data peminjaman dengan join buku
        $data = Peminjaman::join('buku', 'peminjaman.id_buku', '=', 'buku.id_buku')
            ->where('peminjaman.id_anggota', $anggota->id_anggota)
            ->whereIn('peminjaman.status', ['menunggu', 'dipinjam'])
            ->select('peminjaman.*', 'buku.judul', 'buku.gambar')
            ->orderBy('peminjaman.tgl_pinjam', 'desc')
            ->get();

        // Tampilkan data peminjaman ke halaman
        return view('anggota.peminjaman.index', compact('data'));
    }

    /**
     * Menampilkan semua riwayat peminjaman buku
     */
    public function riwayat()
    {
        // Ambil data anggota dari user login
        $anggota = Anggota::where('id_user', Auth::user()->id_user)->first();

        // Ambil data riwayat peminjaman dengan join
        $data = Peminjaman::join('buku', 'peminjaman.id_buku', '=', 'buku.id_buku')
        ->where('peminjaman.id_anggota', $anggota->id_anggota)
        ->whereIn('peminjaman.status', ['dikembalikan', 'ditolak'])
        ->select('peminjaman.*', 'buku.judul', 'buku.gambar')
        ->orderBy('peminjaman.tgl_pinjam', 'desc')
        ->get();

        // Tampilkan data riwayat ke halaman view
        return view('anggota.riwayat.index', compact('data'));
    }
}

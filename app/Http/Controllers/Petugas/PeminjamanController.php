<?php

namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

// Import model yang digunakan
use App\Models\Peminjaman;
use App\Models\Buku;
use App\Models\Anggota;
use App\Models\Petugas;
use App\Models\Pengembalian;
use Carbon\Carbon;

class PeminjamanController extends Controller
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
     * Menampilkan semua data peminjaman terbaru
     */
    public function index()
    {
        // Ambil data peminjaman pakai relasi
        $peminjaman = Peminjaman::with(['anggota', 'buku'])
            ->latest('tgl_pinjam')
            ->get();

        // Tampilkan ke view
        return view('petugas.peminjaman.index', compact('peminjaman'));
    }

    /**
     * Menampilkan halaman form tambah peminjaman
     */
    public function create()
    {
        // Ambil semua data anggota tersedia
        $anggota = Anggota::all();

        // Ambil buku yang stok masih tersedia
        $buku = Buku::where('stok', '>', 0)->get();

        // Tampilkan halaman form peminjaman baru
        return view('petugas.peminjaman.create', compact('anggota', 'buku'));
    }

    /**
     * Menyimpan data peminjaman baru ke database
     */
    public function store(Request $request)
    {
        // Ambil petugas dari relasi user login
        $petugas = Auth::user()->petugas;

        if (!$petugas) {
            return back()->with('error', 'Petugas tidak ditemukan');
        }

        // Simpan peminjaman
        Peminjaman::create([
            'id_anggota' => $request->id_anggota,
            'id_buku' => $request->id_buku,
            'id_petugas' => $petugas->id_petugas,
            'tgl_pinjam' => now(),
            'tgl_kembali' => now()->addDays(7),
            'status' => 'dipinjam'
        ]);

        // Kurangi stok buku
        Buku::where('id_buku', $request->id_buku)->decrement('stok');

        return redirect('/petugas/peminjaman')
            ->with('success', 'Peminjaman berhasil ditambahkan');
    }

    /**
     * Konfirmasi peminjaman yang sebelumnya menunggu
     */
    public function konfirmasi($id)
    {
        // Ambil data peminjaman berdasarkan id
        $pinjam = Peminjaman::find($id);

        // Cek apakah data peminjaman tersedia
        if (!$pinjam) {
            return back()->with('error', 'Data tidak ditemukan');
        }

        // Ambil data buku berdasarkan id buku
        $buku = Buku::find($pinjam->id_buku);

        // Cek apakah stok buku masih tersedia
        if ($buku->stok <= 0) {
            return back()->with('error', 'Stok habis');
        }

        // Ambil data petugas dari user login
        $petugas = Petugas::where('id_user', Auth::user()->id_user)->first();

        // Cek apakah petugas ditemukan di database
        if (!$petugas) {
            return back()->with('error', 'Petugas tidak ditemukan');
        }

        // Kurangi stok buku setelah dikonfirmasi
        Buku::where('id_buku', $pinjam->id_buku)->decrement('stok');

        // Update status peminjaman menjadi dipinjam
        $pinjam->update([
            'status' => 'dipinjam',
            'id_petugas' => $petugas->id_petugas
        ]);

        // Kembali dengan pesan sukses konfirmasi
        return back()->with('success', 'Peminjaman dikonfirmasi');
    }
    // proses tolak peminjaman buku
    public function tolak($id)
    {
        $peminjaman = \App\Models\Peminjaman::find($id);

        if (!$peminjaman) {
            return back()->with('error', 'Data tidak ditemukan');
        }

        $peminjaman->update([
            'status' => 'ditolak'
        ]);

        return back()->with('success', 'Peminjaman berhasil ditolak');
    }

    /**
     * Proses pengembalian buku oleh anggota
     */

    public function kembalikan($id)
    {
        $pinjam = Peminjaman::find($id);

        if (!$pinjam) {
            return back()->with('error', 'Data tidak ditemukan');
        }

        // ❌ cegah double return
        if ($pinjam->status == 'dikembalikan') {
            return back()->with('error', 'Buku sudah dikembalikan');
        }

        // tambah stok buku
        Buku::where('id_buku', $pinjam->id_buku)->increment('stok');

        $today = Carbon::now();
        $denda = 0;
        $statusPengembalian = 'tepat waktu';

        // 🔥 hitung denda
        if ($today->gt($pinjam->tgl_kembali)) {
            $hariTerlambat = Carbon::parse($pinjam->tgl_kembali)->diffInDays($today);
            $denda = $hariTerlambat * 1000;
            $statusPengembalian = 'terlambat';
        }

        // update peminjaman
        $pinjam->update([
            'status' => 'dikembalikan',
            'tgl_dikembalikan' => $today,
            'denda' => $denda
        ]);

        // 🔥 WAJIB: insert ke tabel pengembalian
        Pengembalian::create([
            'id_peminjaman' => $pinjam->id_peminjaman,
            'tgl_pengembalian' => $today,
            'denda' => $denda,
            'status' => $statusPengembalian
        ]);

        return back()->with('success', 'Buku berhasil dikembalikan');
    }

    public function tolakPengembalian($id)
    {
        $pinjam = Peminjaman::find($id);

        if (!$pinjam) {
            return back()->with('error', 'Data tidak ditemukan');
        }

        // hanya bisa ditolak kalau statusnya menunggu pengembalian
        if ($pinjam->status != 'menunggu pengembalian') {
            return back()->with('error', 'Tidak bisa menolak');
        }

        // kembalikan status ke dipinjam
        $pinjam->update([
            'status' => 'dipinjam'
        ]);

        return back()->with('success', 'Pengembalian ditolak');
    }

    /**
     * Menampilkan riwayat peminjaman sudah dikembalikan
     */
    public function riwayat()
    {
        // Ambil data riwayat pakai relasi
        $data = Peminjaman::with(['anggota', 'buku'])
            ->whereIn('status', ['dikembalikan', 'ditolak'])
            ->orderBy('tgl_pinjam', 'desc')
            ->get();

        return view('petugas.peminjaman.riwayat', compact('data'));
    }

    /**
     * Menampilkan data peminjaman untuk kepala
     */
    public function kepala()
    {
        // Ambil data peminjaman lengkap untuk kepala
        $peminjaman = Peminjaman::join('anggota', 'peminjaman.id_anggota', '=', 'anggota.id_anggota')
            ->join('buku', 'peminjaman.id_buku', '=', 'buku.id_buku')
            ->leftJoin('petugas', 'peminjaman.id_petugas', '=', 'petugas.id_petugas')
            ->select(
                'peminjaman.*',
                'anggota.nama as anggota',
                'buku.judul as buku',
                'petugas.nama as petugas'
            )
            ->orderBy('peminjaman.tgl_pinjam', 'desc')
            ->get();

        // Tampilkan data peminjaman ke kepala
        return view('kepala.peminjaman.index', compact('peminjaman'));
    }

    /**
     * Menampilkan detail data peminjaman untuk kepala
     */
    public function detailKepala($id)
    {
        // Ambil satu data peminjaman lengkap
        $data = Peminjaman::join('anggota', 'peminjaman.id_anggota', '=', 'anggota.id_anggota')
            ->join('buku', 'peminjaman.id_buku', '=', 'buku.id_buku')
            ->leftJoin('petugas', 'peminjaman.id_petugas', '=', 'petugas.id_petugas')
            ->select(
                'peminjaman.*',
                'anggota.nama as anggota',
                'buku.judul as buku',
                'petugas.nama as petugas'
            )
            ->where('peminjaman.id_peminjaman', $id)
            ->first();

        // Tampilkan detail peminjaman ke halaman
        return view('kepala.peminjaman.detail', compact('data'));
    }

    /**
     * Menampilkan laporan peminjaman untuk kepala
     */
    public function laporanKepala()
    {
        // Ambil data laporan peminjaman sudah selesai
        $data = Peminjaman::join('anggota', 'peminjaman.id_anggota', '=', 'anggota.id_anggota')
            ->join('buku', 'peminjaman.id_buku', '=', 'buku.id_buku')
            ->leftJoin('petugas', 'peminjaman.id_petugas', '=', 'petugas.id_petugas')
            ->select(
                'peminjaman.*',
                'anggota.nama as anggota',
                'buku.judul as buku',
                'petugas.nama as petugas'
            )
            ->where('peminjaman.status', 'dikembalikan')
            ->orderBy('peminjaman.tgl_pinjam', 'desc')
            ->get();

        // Tampilkan laporan peminjaman ke halaman
        return view('kepala.laporan.index', compact('data'));
    }
}

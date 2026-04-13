<?php

namespace App\Http\Controllers\Kepala;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Peminjaman;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class PeminjamanController extends Controller
{
    /**
     * Constructor cek login & role
     */
    public function __construct()
    {
        // cek apakah user sudah login
        if (!Auth::check()) {
            redirect('/login')->with('error', 'Silakan login terlebih dahulu')->send();
        }

        // cek apakah user memiliki role kepala
        if (Auth::user()->role != 'kepala') {
            redirect('/login')->with('error', 'Akses ditolak')->send();
        }
    }

    /**
     * Menampilkan data peminjaman
     */
    public function index()
    {
        // mengambil semua data peminjaman beserta relasi anggota, buku, dan petugas
        $peminjaman = Peminjaman::with(['anggota', 'buku', 'petugas'])
            ->orderBy('id_peminjaman', 'desc') // urutkan dari data terbaru
            ->get();

        // kirim data ke view
        return view('kepala.peminjaman.index', compact('peminjaman'));
    }

    /**
     * Detail peminjaman
     */
    public function detail($id)
    {
        // mengambil satu data peminjaman berdasarkan id + relasi
        $data = Peminjaman::with(['anggota', 'buku', 'petugas'])
            ->where('id_peminjaman', $id)
            ->first();

        // tampilkan ke view detail
        return view('kepala.peminjaman.detail', compact('data'));
    }

    /**
     * Laporan peminjaman
     */
    public function laporan(Request $request)
    {
        // ambil input filter bulan dan tahun dari request
        $bulan = $request->bulan;
        $tahun = $request->tahun;

        // query dasar dengan relasi
        $query = Peminjaman::with([
            'anggota',
            'buku',
            'petugas',
            'pengembalian'
        ]);

        // filter berdasarkan bulan jika dipilih
        if ($bulan) {
            $query->whereMonth('tgl_pinjam', $bulan);
        }

        // filter berdasarkan tahun jika dipilih
        if ($tahun) {
            $query->whereYear('tgl_pinjam', $tahun);
        }

        // ambil data dan urutkan dari terbaru
        $data = $query->orderBy('id_peminjaman', 'desc')->get();

        // kirim data ke view laporan
        return view('kepala.laporan.index', compact('data', 'bulan', 'tahun'));
    }

    public function exportPdf(Request $request)
    {
        // ambil input filter bulan dan tahun
        $bulan = $request->bulan;
        $tahun = $request->tahun;

        // query data dengan relasi
        $query = Peminjaman::with([
            'anggota',
            'buku',
            'petugas',
            'pengembalian'
        ]);

        // filter bulan
        if ($bulan) {
            $query->whereMonth('tgl_pinjam', $bulan);
        }

        // filter tahun
        if ($tahun) {
            $query->whereYear('tgl_pinjam', $tahun);
        }

        // ambil data hasil query
        $data = $query->get();

        // generate file PDF dari view
        $pdf = Pdf::loadView('kepala.laporan.pdf', compact('data', 'bulan', 'tahun'));

        // download file PDF
        return $pdf->download('laporan-perpustakaan.pdf');
    }
}

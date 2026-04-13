<?php

namespace App\Http\Controllers\Kepala;

use App\Http\Controllers\Controller;
use App\Models\Peminjaman;
use App\Models\Buku;
use App\Models\Anggota;
use Illuminate\Support\Facades\Auth;
use App\Models\KepalaPerpustakaan;

class DashboardController extends Controller
{
    public function index()
    {
        // menghitung total semua buku
        $totalBuku = Buku::count();

        // menghitung total semua anggota
        $totalAnggota = Anggota::count();

        // menghitung total semua peminjaman
        $totalPeminjaman = Peminjaman::count();

        // menghitung jumlah peminjaman pada bulan ini
        $bulanIni = Peminjaman::whereMonth('tgl_pinjam', now()->month)->count();

        // mengambil 5 buku paling populer (paling sering dipinjam)
        $populer = Peminjaman::select('id_buku')
            ->selectRaw('count(*) as total') // menghitung jumlah peminjaman per buku
            ->groupBy('id_buku') // mengelompokkan berdasarkan buku
            ->orderByDesc('total') // urutkan dari yang paling banyak
            ->with('buku') // relasi ke tabel buku
            ->take(5) // ambil 5 teratas
            ->get();

        // mengambil 5 data peminjaman terbaru
        $latest = Peminjaman::with(['anggota', 'buku', 'petugas']) // relasi ke anggota, buku, dan petugas
            ->latest('tgl_pinjam') // urutkan berdasarkan tanggal pinjam terbaru
            ->take(5) // ambil 5 data
            ->get();

        // kirim semua data ke view dashboard kepala
        return view('kepala.dashboard', compact(
            'totalBuku',
            'totalAnggota',
            'totalPeminjaman',
            'bulanIni',
            'populer',
            'latest'
        ));
    }

    // method untuk menampilkan profil kepala perpustakaan
    public function profile()
    {
        // mengambil data kepala berdasarkan user login
        $kepala = KepalaPerpustakaan::where('id_user', Auth::user()->id_user)->first();

        // tampilkan ke halaman profile
        return view('kepala.profile', compact('kepala'));
    }
}

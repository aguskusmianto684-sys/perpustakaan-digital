<?php

namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Peminjaman;
use App\Models\Buku;
use App\Models\Anggota;

class DashboardController extends Controller
{
    public function index()
    {
        // menghitung total semua buku
        $totalBuku = Buku::count();

        // menghitung total semua anggota
        $totalAnggota = Anggota::count();

        // menghitung jumlah peminjaman yang sedang aktif (status dipinjam)
        $peminjamanAktif = Peminjaman::where('status', 'dipinjam')->count();

        // menghitung jumlah peminjaman yang masih menunggu
        $menunggu = Peminjaman::where('status', 'menunggu')->count();

        // menghitung jumlah peminjaman yang terlambat (sudah lewat tanggal kembali)
        $terlambat = Peminjaman::where('status', 'dipinjam')
            ->where('tgl_kembali', '<', now())
            ->count();

        // menghitung jumlah peminjaman hari ini
        $hariIni = Peminjaman::whereDate('tgl_pinjam', today())->count();

        // mengambil 5 data peminjaman terbaru
        $latest = Peminjaman::with(['anggota', 'buku'])
            ->latest('tgl_pinjam')
            ->take(5)
            ->get();

        // menghitung jumlah pengajuan pengembalian yang menunggu
        $pengajuanPengembalian = Peminjaman::where('status', 'menunggu pengembalian')->count();

        // kirim semua data ke view dashboard petugas
        return view('petugas.dashboard', compact(
            'totalBuku',
            'totalAnggota',
            'peminjamanAktif',
            'menunggu',
            'terlambat',
            'hariIni',
            'latest',
            'pengajuanPengembalian'
        ));
    }

    public function profile()
    {
        // mengambil data petugas dari user login
        $petugas = Auth::user()->petugas;

        // menampilkan halaman profile petugas
        return view('petugas.profile', compact('petugas'));
    }
}

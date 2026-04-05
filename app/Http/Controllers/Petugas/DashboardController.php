<?php

namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use App\Models\Peminjaman;
use App\Models\Buku;
use App\Models\Anggota;

class DashboardController extends Controller
{
    public function index()
    {
        $totalBuku = Buku::count();
        $totalAnggota = Anggota::count();

        $peminjamanAktif = Peminjaman::where('status','dipinjam')->count();

        $menunggu = Peminjaman::where('status','menunggu')->count();

        $terlambat = Peminjaman::where('status','dipinjam')
            ->where('tgl_kembali','<',now())
            ->count();

        $hariIni = Peminjaman::whereDate('tgl_pinjam', today())->count();

        $latest = Peminjaman::with(['anggota','buku'])
            ->latest('tgl_pinjam')
            ->take(5)
            ->get();

        return view('petugas.dashboard', compact(
            'totalBuku',
            'totalAnggota',
            'peminjamanAktif',
            'menunggu',
            'terlambat',
            'hariIni',
            'latest'
        ));
    }
}

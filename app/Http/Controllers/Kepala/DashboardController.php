<?php

namespace App\Http\Controllers\Kepala;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Peminjaman;
use App\Models\Buku;
use App\Models\Anggota;

class DashboardController extends Controller
{
    public function index()
    {
        $totalBuku = Buku::count();
        $totalAnggota = Anggota::count();
        $totalPeminjaman = Peminjaman::count();

        $bulanIni = Peminjaman::whereMonth('tgl_pinjam', now()->month)->count();

        // buku populer
        $populer = Peminjaman::select('id_buku')
            ->selectRaw('count(*) as total')
            ->groupBy('id_buku')
            ->orderByDesc('total')
            ->with('buku')
            ->take(5)
            ->get();

        // aktivitas terbaru
        $latest = Peminjaman::with(['anggota', 'buku', 'petugas'])
            ->latest('tgl_pinjam')
            ->take(5)
            ->get();

        return view('kepala.dashboard', compact(
            'totalBuku',
            'totalAnggota',
            'totalPeminjaman',
            'bulanIni',
            'populer',
            'latest'
        ));
    }
    public function profile()
    {
        $kepala = Auth::user()->kepala;

        return view('kepala.profile', compact('kepala'));
    }
}

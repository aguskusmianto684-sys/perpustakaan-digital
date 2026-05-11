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
        $totalBuku = Buku::count();
        $totalAnggota = Anggota::count();
        $totalPeminjaman = Peminjaman::count();

        $bulanIni = Peminjaman::whereMonth('tgl_pinjam', now()->month)->count();

        $populer = Peminjaman::select('id_buku')
            ->selectRaw('count(*) as total')
            ->groupBy('id_buku')
            ->orderByDesc('total')
            ->with('buku')
            ->take(5)
            ->get();

        $latest = Peminjaman::with(['anggota','buku','petugas'])
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

    // 🔥 TAMBAHAN INI
    public function profile()
    {
        $kepala = KepalaPerpustakaan::where('id_user', Auth::user()->id_user)->first();

        return view('kepala.profile', compact('kepala'));
    }
}

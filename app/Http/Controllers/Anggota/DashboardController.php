<?php

namespace App\Http\Controllers\Anggota;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Peminjaman;

class DashboardController extends Controller
{
    public function index()
    {
        $anggota = Auth::user()->anggota;

        $data = Peminjaman::with('buku')
            ->where('id_anggota', $anggota->id_anggota)
            ->get();

        $total = $data->count();

        $dipinjam = $data->where('status', 'dipinjam')->count();

        $kembali = $data->where('status', 'dikembalikan')->count();

        $terlambat = $data->filter(function ($d) {
            return $d->status == 'dipinjam' && now()->gt($d->tgl_kembali);
        })->count();

        $last = $data->sortByDesc('tgl_pinjam')->first();

        return view('anggota.dashboard', compact(
            'total',
            'dipinjam',
            'kembali',
            'terlambat',
            'last'
        ));
    }

    public function profile()
    {
        $anggota = Auth::user()->anggota;

        return view('anggota.profile', compact('anggota'));
    }
}

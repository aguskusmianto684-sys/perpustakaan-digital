<?php

namespace App\Http\Controllers\Anggota;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Peminjaman;

class DashboardController extends Controller
{
    public function index()
    {
        // mengambil data anggota dari user yang sedang login (relasi user -> anggota)
        $anggota = Auth::user()->anggota;

        // mengambil semua data peminjaman milik anggota + relasi ke buku
        $data = Peminjaman::with('buku')
            ->where('id_anggota', $anggota->id_anggota)
            ->get();

        // menghitung total semua peminjaman
        $total = $data->count();

        // menghitung jumlah buku yang sedang dipinjam
        $dipinjam = $data->where('status', 'dipinjam')->count();

        // menghitung jumlah buku yang sudah dikembalikan
        $kembali = $data->where('status', 'dikembalikan')->count();

        // menghitung jumlah buku yang terlambat (status dipinjam dan melewati tanggal kembali)
        $terlambat = $data->filter(function ($d) {
            return $d->status == 'dipinjam' && now()->gt($d->tgl_kembali);
        })->count();

        // mengambil data peminjaman terakhir berdasarkan tanggal pinjam terbaru
        $last = $data->sortByDesc('tgl_pinjam')->first();

        // mengirim data ke halaman dashboard
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
        // mengambil data anggota dari user login
        $anggota = Auth::user()->anggota;

        // menampilkan halaman profile anggota
        return view('anggota.profile', compact('anggota'));
    }
}

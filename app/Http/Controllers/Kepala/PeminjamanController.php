<?php

namespace App\Http\Controllers\Kepala;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class PeminjamanController extends Controller
{

    public function __construct()
    {
        if (!Auth::check()) {
            redirect('/login')->with('error', 'Silakan login terlebih dahulu')->send();
        }

        if (Auth::user()->role != 'kepala') {
            redirect('/login')->with('error', 'Silakan login terlebih dahulu')->send();
        }
    }

    // 📌 DATA PEMINJAMAN (UNTUK TABLE)
    public function index()
    {
        $peminjaman = DB::table('peminjaman')
            ->join('anggota', 'peminjaman.id_anggota', '=', 'anggota.id_anggota')
            ->join('buku', 'peminjaman.id_buku', '=', 'buku.id_buku')
            ->leftJoin('petugas', 'peminjaman.id_petugas', '=', 'petugas.id_petugas')
            ->select(
                'peminjaman.*',
                'anggota.nama as anggota',
                'buku.judul as buku',
                'petugas.nama as petugas'
            )
            ->get();

        return view('kepala.peminjaman.index', compact('peminjaman'));
    }

    // 📌 DETAIL PEMINJAMAN
    public function detail($id)
    {
        $data = DB::table('peminjaman')
            ->join('anggota', 'peminjaman.id_anggota', '=', 'anggota.id_anggota')
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

        return view('kepala.peminjaman.detail', compact('data'));
    }

    // 📌 LAPORAN
    public function laporan()
    {
        $data = DB::table('peminjaman')
            ->join('anggota', 'peminjaman.id_anggota', '=', 'anggota.id_anggota')
            ->join('buku', 'peminjaman.id_buku', '=', 'buku.id_buku')
            ->leftJoin('petugas', 'peminjaman.id_petugas', '=', 'petugas.id_petugas')
            ->select(
                'peminjaman.*',
                'anggota.nama as anggota',
                'buku.judul as buku',
                'petugas.nama as petugas'
            )
            ->get();

        return view('kepala.laporan.index', compact('data'));
    }
}

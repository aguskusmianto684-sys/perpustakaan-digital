<?php

namespace App\Http\Controllers\Kepala;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Peminjaman;

class PeminjamanController extends Controller
{
    /**
     * Constructor cek login & role
     */
    public function __construct()
    {
        if (!Auth::check()) {
            redirect('/login')->with('error', 'Silakan login terlebih dahulu')->send();
        }

        if (Auth::user()->role != 'kepala') {
            redirect('/login')->with('error', 'Akses ditolak')->send();
        }
    }

    /**
     * Menampilkan data peminjaman
     */
    public function index()
    {
        $peminjaman = Peminjaman::with(['anggota', 'buku', 'petugas'])
            ->orderBy('id_peminjaman', 'desc')
            ->get();

        return view('kepala.peminjaman.index', compact('peminjaman'));
    }

    /**
     * Detail peminjaman
     */
    public function detail($id)
    {
        $data = Peminjaman::with(['anggota', 'buku', 'petugas'])
            ->where('id_peminjaman', $id)
            ->first();

        return view('kepala.peminjaman.detail', compact('data'));
    }

    /**
     * Laporan peminjaman (🔥 SUDAH RELASI)
     */
    public function laporan()
    {
        $data = Peminjaman::with(['anggota', 'buku', 'petugas'])
            ->orderBy('id_peminjaman', 'desc')
            ->get();

        return view('kepala.laporan.index', compact('data'));
    }
}

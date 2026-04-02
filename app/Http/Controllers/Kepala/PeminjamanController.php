<?php

namespace App\Http\Controllers\Kepala;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

// Import model yang digunakan
use App\Models\Peminjaman;

class PeminjamanController extends Controller
{
    /**
     * Constructor untuk cek login dan role
     */
    public function __construct()
    {
        // Cek apakah user sudah login
        if (!Auth::check()) {
            redirect('/login')->with('error', 'Silakan login terlebih dahulu')->send();
        }

        // Cek apakah user adalah kepala
        if (Auth::user()->role != 'kepala') {
            redirect('/login')->with('error', 'Silakan login terlebih dahulu')->send();
        }
    }

    /**
     * Menampilkan semua data peminjaman terbaru
     */
    public function index()
    {
        // Ambil data peminjaman dengan relasi lengkap
        $peminjaman = Peminjaman::join('anggota', 'peminjaman.id_anggota', '=', 'anggota.id_anggota')
            ->join('buku', 'peminjaman.id_buku', '=', 'buku.id_buku')
            ->leftJoin('petugas', 'peminjaman.id_petugas', '=', 'petugas.id_petugas')
            ->select(
                'peminjaman.*',
                'anggota.nama as anggota',
                'buku.judul as buku',
                'petugas.nama as petugas'
            )
            ->orderBy('peminjaman.id_peminjaman', 'desc')
            ->get();

        // Tampilkan data peminjaman ke halaman
        return view('kepala.peminjaman.index', compact('peminjaman'));
    }

    /**
     * Menampilkan detail data peminjaman tertentu
     */
    public function detail($id)
    {
        // Ambil satu data peminjaman berdasarkan id
        $data = Peminjaman::join('anggota', 'peminjaman.id_anggota', '=', 'anggota.id_anggota')
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

        // Tampilkan detail peminjaman ke halaman
        return view('kepala.peminjaman.detail', compact('data'));
    }

    /**
     * Menampilkan laporan data peminjaman lengkap
     */
    public function laporan()
    {
        // Ambil data laporan peminjaman lengkap
        $data = Peminjaman::join('anggota', 'peminjaman.id_anggota', '=', 'anggota.id_anggota')
            ->join('buku', 'peminjaman.id_buku', '=', 'buku.id_buku')
            ->leftJoin('petugas', 'peminjaman.id_petugas', '=', 'petugas.id_petugas')
            ->select(
                'peminjaman.*',
                'anggota.nama as anggota',
                'buku.judul as buku',
                'petugas.nama as petugas'
            )
            ->orderBy('peminjaman.id_peminjaman', 'desc')
            ->get();

        // Tampilkan data laporan ke halaman
        return view('kepala.laporan.index', compact('data'));
    }
}

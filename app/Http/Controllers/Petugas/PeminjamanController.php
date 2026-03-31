<?php

namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class PeminjamanController extends Controller
{
    public function __construct()
    {
        if (!Auth::check()) {
            redirect('/login')->with('error', 'Silakan login terlebih dahulu')->send();
        }

        if (Auth::user()->role != 'petugas') {
            redirect('/login')->with('error', 'Silakan login terlebih dahulu')->send();
        }
    }

    public function index()
    {

        $peminjaman = DB::table('peminjaman')
            ->join('anggota', 'peminjaman.id_anggota', '=', 'anggota.id_anggota')
            ->join('buku', 'peminjaman.id_buku', '=', 'buku.id_buku')
            ->select('peminjaman.*', 'anggota.nama', 'buku.judul')
            ->get();

        return view('petugas.peminjaman.index', compact('peminjaman'));
    }

    public function create()
    {
        $anggota = DB::table('anggota')->get();
        $buku = DB::table('buku')->where('stok', '>', 0)->get();

        return view('petugas.peminjaman.create', compact('anggota', 'buku'));
    }

    public function store(Request $request)
    {
        $petugas = DB::table('petugas')
            ->where('id_user', Auth::user()->id_user)
            ->first();

        if (!$petugas) {
            return back()->with('error', 'Petugas tidak ditemukan');
        }

        DB::table('peminjaman')->insert([
            'id_anggota' => $request->id_anggota,
            'id_buku' => $request->id_buku,
            'id_petugas' => $petugas->id_petugas,
            'tgl_pinjam' => now(), // 🔥 otomatis
            'tgl_kembali' => now()->addDays(7), // 🔥 otomatis
            'status' => 'dipinjam'
        ]);

        DB::table('buku')
            ->where('id_buku', $request->id_buku)
            ->decrement('stok');

        return redirect('/petugas/peminjaman')
            ->with('success', 'Peminjaman berhasil ditambahkan');
    }


    public function konfirmasi($id)
    {

        $peminjaman = DB::table('peminjaman')
            ->where('id_peminjaman', $id)
            ->first();

        $petugas = DB::table('petugas')
            ->where('id_user', Auth::user()->id_user)
            ->first();

        if (!$petugas) {
            return redirect('/login')
                ->with('error', 'Akun ini bukan petugas');
        }

        DB::table('peminjaman')
            ->where('id_peminjaman', $id)
            ->update([
                'status' => 'dipinjam',
                'id_petugas' => $petugas->id_petugas
            ]);

        DB::table('buku')
            ->where('id_buku', $peminjaman->id_buku)
            ->decrement('stok');

        return redirect('/petugas/peminjaman')
            ->with('success', 'Peminjaman dikonfirmasi');
    }

    public function kembalikan($id)
    {

        $peminjaman = DB::table('peminjaman')
            ->where('id_peminjaman', $id)
            ->first();

        $denda = 0;

        if (now()->greaterThan($peminjaman->tgl_kembali)) {

            $terlambat = now()->diffInDays($peminjaman->tgl_kembali);

            $denda = $terlambat * 2000;
        }

        DB::table('peminjaman')
            ->where('id_peminjaman', $id)
            ->update([
                'status' => 'dikembalikan',
                'denda' => $denda
            ]);

        DB::table('buku')
            ->where('id_buku', $peminjaman->id_buku)
            ->increment('stok');

        return redirect('/petugas/peminjaman')
            ->with('success', 'Buku dikembalikan. Denda : Rp ' . $denda);
    }

    public function riwayat()
    {

        $data = DB::table('peminjaman')
            ->join('anggota', 'peminjaman.id_anggota', '=', 'anggota.id_anggota')
            ->join('buku', 'peminjaman.id_buku', '=', 'buku.id_buku')
            ->where('peminjaman.status', 'dikembalikan')
            ->select(
                'peminjaman.*',
                'anggota.nama',
                'buku.judul'
            )
            ->get();

        return view('petugas.peminjaman.riwayat', compact('data'));
    }
    public function kepala()
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
    public function detailKepala($id)
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
    public function laporanKepala()
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

            ->where('peminjaman.status', 'dikembalikan')

            ->get();

        return view('kepala.laporan.index', compact('data'));
    }
}

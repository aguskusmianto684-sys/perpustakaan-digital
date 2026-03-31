<?php

namespace App\Http\Controllers\Anggota;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class BukuAnggotaController extends Controller
{
    public function __construct()
    {
        if (!Auth::check()) {
            redirect('/login')->send();
        }

        if (Auth::user()->role != 'anggota') {
            redirect('/login')->with('error', 'Silakan login terlebih dahulu')->send();
        }
    }

    public function index()
    {
        $buku = DB::table('buku')->get();

        return view('anggota.buku.index', compact('buku'));
    }


    public function detail($id)
    {

        $buku = DB::table('buku')
            ->where('id_buku', $id)
            ->first();

        return view('anggota.buku.detail', compact('buku'));
    }


    public function pinjam($id)
    {
        $id_anggota = Auth::user()->id_user;

        DB::table('peminjaman')->insert([
            'id_buku' => $id,
            'id_anggota' => $id_anggota,
            'tgl_pinjam' => now(),
            'tgl_kembali' => now()->addDays(7),
            'status' => 'menunggu'
        ]);

        return redirect('/anggota/buku')->with('success', 'Request peminjaman berhasil');
    }
    public function formPinjam($id)
    {
        $buku = DB::table('buku')
            ->where('id_buku', $id)
            ->first();

        $user = Auth::user();

        return view('anggota.peminjaman.create', compact('buku', 'user'));
    }

    public function storePinjam(Request $request)
    {

        $anggota = DB::table('anggota')
            ->where('id_user', Auth::user()->id_user)
            ->first();


        // hitung buku yang sedang dipinjam
        $jumlahPinjam = DB::table('peminjaman')
            ->where('id_anggota', $anggota->id_anggota)
            ->where('status', 'dipinjam')
            ->count();


        if ($jumlahPinjam >= 3) {

            return redirect('/anggota/buku')
                ->with('error', 'Anda sudah meminjam 3 buku, kembalikan buku terlebih dahulu');
        }


        DB::table('peminjaman')->insert([

            'id_anggota' => $anggota->id_anggota,
            'id_petugas' => null,
            'id_buku' => $request->id_buku,
            'tgl_pinjam' => now(),
            'tgl_kembali' => now()->addDays(7),
            'status' => 'menunggu'

        ]);


        return redirect('/anggota/buku')
            ->with('success', 'Permintaan peminjaman berhasil dikirim');
    }
    public function peminjamanSaya()
    {

        $anggota = DB::table('anggota')
            ->where('id_user', Auth::user()->id_user)
            ->first();

        $data = DB::table('peminjaman')
            ->join('buku', 'peminjaman.id_buku', '=', 'buku.id_buku')
            ->where('peminjaman.id_anggota', $anggota->id_anggota)

            // FILTER STATUS
            ->whereIn('peminjaman.status', ['menunggu', 'dipinjam'])

            ->select(
                'peminjaman.*',
                'buku.judul',
                'buku.gambar'
            )
            ->get();

        return view('anggota.peminjaman.index', compact('data'));
    }
    public function riwayat()
    {

        $anggota = DB::table('anggota')
            ->where('id_user', Auth::user()->id_user)
            ->first();

        $data = DB::table('peminjaman')
            ->join('buku', 'peminjaman.id_buku', '=', 'buku.id_buku')
            ->where('peminjaman.id_anggota', $anggota->id_anggota)
            ->select(
                'peminjaman.*',
                'buku.judul',
                'buku.gambar'
            )
            ->orderBy('tgl_pinjam', 'desc')
            ->get();

        return view('anggota.riwayat.index', compact('data'));
    }
}

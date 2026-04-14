<?php

namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

// Import model yang digunakan
use App\Models\Peminjaman;
use App\Models\Buku;
use App\Models\Anggota;
use App\Models\Petugas;
use App\Models\Pengembalian;
use Carbon\Carbon;

class PeminjamanController extends Controller
{
    /**
     * Constructor untuk cek login dan role
     */
    public function __construct()
    {
        // cek apakah user sudah login
        if (!Auth::check()) {
            redirect('/login')->with('error', 'Silakan login terlebih dahulu')->send();
        }

        // cek apakah user memiliki role petugas
        if (Auth::user()->role != 'petugas') {
            redirect('/login')->with('error', 'Silakan login terlebih dahulu')->send();
        }
    }

    /**
     * Menampilkan semua data peminjaman terbaru
     */
    public function index()
    {
        // ambil data peminjaman dengan relasi anggota dan buku
        $peminjaman = Peminjaman::with(['anggota', 'buku'])
            ->latest('tgl_pinjam') // urutkan dari terbaru
            ->get();

        // tampilkan ke view
        return view('petugas.peminjaman.index', compact('peminjaman'));
    }

    /**
     * Menampilkan halaman form tambah peminjaman
     */
    public function create()
    {
        // ambil semua anggota
        $anggota = Anggota::all();

        // ambil buku yang stoknya masih tersedia
        $buku = Buku::where('stok', '>', 0)->get();

        // tampilkan form
        return view('petugas.peminjaman.create', compact('anggota', 'buku'));
    }

    public function detail($id)
    {
        // ambil detail peminjaman berdasarkan id
        $data = \App\Models\Peminjaman::with(['anggota', 'buku'])
            ->where('id_peminjaman', $id)
            ->first();

        // tampilkan ke view
        return view('petugas.peminjaman.detail', compact('data'));
    }

    /**
     * Menyimpan data peminjaman baru ke database
     */
    public function store(Request $request)
    {
        // ambil data petugas dari user login
        $petugas = Auth::user()->petugas;

        // jika petugas tidak ditemukan
        if (!$petugas) {
            return back()->with('error', 'Petugas tidak ditemukan');
        }

        // simpan data peminjaman
        Peminjaman::create([
            'id_anggota' => $request->id_anggota,
            'id_buku' => $request->id_buku,
            'id_petugas' => $petugas->id_petugas,
            'tgl_pinjam' => now(),
            'tgl_kembali' => now()->addDays(7), // batas 7 hari
            'status' => 'dipinjam'
        ]);

        // kurangi stok buku
        Buku::where('id_buku', $request->id_buku)->decrement('stok');

        return redirect('/petugas/peminjaman')
            ->with('success', 'Peminjaman berhasil ditambahkan');
    }

    /**
     * Konfirmasi peminjaman yang sebelumnya menunggu
     */
    public function konfirmasi($id)
    {
        // ambil data peminjaman
        $pinjam = Peminjaman::find($id);

        // jika tidak ditemukan
        if (!$pinjam) {
            return back()->with('error', 'Data tidak ditemukan');
        }

        // ambil petugas login
        $petugas = Petugas::where('id_user', Auth::user()->id_user)->first();

        // jika petugas tidak ditemukan
        if (!$petugas) {
            return back()->with('error', 'Petugas tidak ditemukan');
        }

        // update status menjadi dipinjam dan set petugas
        $pinjam->update([
            'status' => 'dipinjam',
            'id_petugas' => $petugas->id_petugas
        ]);

        return back()->with('success', 'Peminjaman dikonfirmasi');
    }

    // proses tolak peminjaman buku
    public function tolak($id, $alasan)
    {
        $peminjaman = Peminjaman::find($id);

        if (!$peminjaman) {
            return back()->with('error', 'Data tidak ditemukan');
        }


        $petugas = Auth::user()->petugas;

        if ($alasan == "1") $text = "Buku sedang dipinjam oleh anggota lain";
        elseif ($alasan == "2") $text = "Stok buku tidak tersedia";
        elseif ($alasan == "3") $text = "Melebihi batas maksimal peminjaman";
        elseif ($alasan == "4") $text = "Data anggota tidak valid";
        else $text = $alasan;

        Buku::where('id_buku', $peminjaman->id_buku)->increment('stok');

        $peminjaman->update([
            'status' => 'ditolak',
            'alasan' => $text,
            'id_petugas' => $petugas->id_petugas
        ]);

        return back()->with('success', 'Peminjaman berhasil ditolak');
    }

    /**
     * Proses pengembalian buku oleh anggota
     */

    public function kembalikan($id)
    {
        $pinjam = Peminjaman::find($id);

        if (!$pinjam) {
            return back()->with('error', 'Data tidak ditemukan');
        }

        if ($pinjam->status == 'dikembalikan') {
            return back()->with('error', 'Buku sudah dikembalikan');
        }

        // tambah stok
        Buku::where('id_buku', $pinjam->id_buku)->increment('stok');

        $today = Carbon::now()->startOfDay();
        $batas = Carbon::parse($pinjam->tgl_kembali)->startOfDay();

        $denda = 0;
        $hariTerlambat = 0;
        $statusPengembalian = 'tepat waktu';

        if ($today->gt($batas)) {
            $hariTerlambat = $batas->diffInDays($today);
            $denda = $hariTerlambat * 1000;
            $statusPengembalian = 'terlambat';
        }

        // update peminjaman
        $pinjam->status = 'dikembalikan';
        $pinjam->tgl_dikembalikan = $today;
        $pinjam->denda = $denda;
        $pinjam->save();

        // simpan ke pengembalian
        Pengembalian::create([
            'id_peminjaman' => $pinjam->id_peminjaman,
            'tgl_pengembalian' => $today,
            'denda' => $denda,
            'status' => $statusPengembalian
        ]);

        return back()->with('success', 'Buku berhasil dikembalikan');
    }

    public function tolakPengembalian($id, $alasan)
    {
        $pinjam = Peminjaman::find($id);

        if (!$pinjam) {
            return back()->with('error', 'Data tidak ditemukan');
        }

        // 🔥 PERBAIKAN VALIDASI
        if (!in_array($pinjam->status, ['menunggu pengembalian', 'dipinjam'])) {
            return back()->with('error', 'Tidak bisa menolak');
        }

        $petugas = Auth::user()->petugas;

        // mapping alasan
        if ($alasan == "1") $text = "Buku belum benar-benar dikembalikan";
        elseif ($alasan == "2") $text = "Kondisi buku rusak atau tidak sesuai";
        elseif ($alasan == "3") $text = "Data pengembalian tidak sesuai";
        elseif ($alasan == "4") $text = "Perlu pengecekan ulang oleh petugas";
        else $text = $alasan;

        $pinjam->update([
            'status' => 'dipinjam',
            'alasan' => $text,
            'id_petugas' => $petugas->id_petugas
        ]);

        return back()->with('success', 'Pengembalian ditolak');
    }

    /**
     * Menampilkan riwayat peminjaman
     */
    public function riwayat()
    {
        // ambil data peminjaman yang sudah selesai
        $data = Peminjaman::with(['anggota', 'buku'])
            ->whereIn('status', ['dikembalikan', 'ditolak'])
            ->orderBy('tgl_pinjam', 'desc')
            ->get();

        return view('petugas.riwayat.index', compact('data'));
    }

    // detail riwayat peminjaman
    public function detailRiwayat($id)
    {
        // ambil detail riwayat
        $data = Peminjaman::with(['anggota', 'buku'])
            ->where('id_peminjaman', $id)
            ->first();

        return view('petugas.riwayat.detail', compact('data'));
    }

    /**
     * Menampilkan data peminjaman untuk kepala
     */
    public function kepala()
    {
        // ambil data peminjaman lengkap dengan join
        $peminjaman = Peminjaman::join('anggota', 'peminjaman.id_anggota', '=', 'anggota.id_anggota')
            ->join('buku', 'peminjaman.id_buku', '=', 'buku.id_buku')
            ->leftJoin('petugas', 'peminjaman.id_petugas', '=', 'petugas.id_petugas')
            ->select(
                'peminjaman.*',
                'anggota.nama as anggota',
                'buku.judul as buku',
                'petugas.nama as petugas'
            )
            ->orderBy('peminjaman.tgl_pinjam', 'desc')
            ->get();

        return view('kepala.peminjaman.index', compact('peminjaman'));
    }

    /**
     * Detail peminjaman untuk kepala
     */
    public function detailKepala($id)
    {
        // ambil detail peminjaman lengkap
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

        return view('kepala.peminjaman.detail', compact('data'));
    }

    /**
     * Menampilkan laporan peminjaman untuk kepala
     */
    public function laporanKepala()
    {
        // ambil data peminjaman yang sudah dikembalikan
        $data = Peminjaman::join('anggota', 'peminjaman.id_anggota', '=', 'anggota.id_anggota')
            ->join('buku', 'peminjaman.id_buku', '=', 'buku.id_buku')
            ->leftJoin('petugas', 'peminjaman.id_petugas', '=', 'petugas.id_petugas')
            ->select(
                'peminjaman.*',
                'anggota.nama as anggota',
                'buku.judul as buku',
                'petugas.nama as petugas'
            )
            ->where('peminjaman.status', 'dikembalikan')
            ->orderBy('peminjaman.tgl_pinjam', 'desc')
            ->get();

        return view('kepala.laporan.index', compact('data'));
    }

    // bayar denda
    public function bayar($id)
    {
        $peminjaman = Peminjaman::findOrFail($id);

        $peminjaman->denda = 0;
        $peminjaman->save();

        return back()->with('success', 'Pembayaran berhasil');
    }

    // untuk struk pembayarann
    public function struk($id)
    {
        $peminjaman = \App\Models\Peminjaman::with(['anggota', 'buku'])->findOrFail($id);

        return view('petugas.peminjaman.struk', compact('peminjaman'));
    }
}

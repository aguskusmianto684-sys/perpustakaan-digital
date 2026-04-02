<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Peminjaman extends Model
{
    protected $table = 'peminjaman';

    protected $primaryKey = 'id_peminjaman';

    public $timestamps = false;

    // Kolom yang boleh diisi dan diupdate
    protected $fillable = [
        'id_anggota',
        'id_buku',
        'id_petugas',
        'tgl_pinjam',
        'tgl_kembali',
        'tgl_dikembalikan',
        'status'
    ];
}

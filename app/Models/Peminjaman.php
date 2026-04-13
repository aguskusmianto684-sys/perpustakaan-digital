<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Peminjaman extends Model
{
    // menentukan nama tabel di database
    protected $table = 'peminjaman';

    // menentukan primary key
    protected $primaryKey = 'id_peminjaman';

    // menonaktifkan timestamps
    public $timestamps = false;

    // field yang boleh diisi (mass assignment)
    protected $fillable = [
        'id_anggota',
        'id_buku',
        'id_petugas',
        'tgl_pinjam',
        'tgl_kembali',
        'tgl_dikembalikan',
        'status',
        'denda',
        'alasan' // menyimpan denda keterlambatan
    ];

    // relasi ke anggota (satu peminjaman dimiliki satu anggota)
    public function anggota()
    {
        return $this->belongsTo(Anggota::class, 'id_anggota', 'id_anggota');
    }

    // relasi ke buku (satu peminjaman untuk satu buku)
    public function buku()
    {
        return $this->belongsTo(Buku::class, 'id_buku', 'id_buku');
    }

    // relasi ke petugas (peminjaman diproses oleh petugas)
    public function petugas()
    {
        return $this->belongsTo(Petugas::class, 'id_petugas', 'id_petugas');
    }

    // relasi ke pengembalian (satu peminjaman memiliki satu data pengembalian)
    public function pengembalian()
    {
        return $this->hasOne(Pengembalian::class, 'id_peminjaman');
    }
}

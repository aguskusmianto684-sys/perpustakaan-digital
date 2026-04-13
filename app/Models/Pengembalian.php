<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pengembalian extends Model
{
    // menentukan nama tabel di database
    protected $table = 'pengembalian';

    // menentukan primary key
    protected $primaryKey = 'id_pengembalian';

    // menonaktifkan timestamps
    public $timestamps = false;

    // field yang boleh diisi (mass assignment)
    protected $fillable = [
        'id_peminjaman',
        'tgl_pengembalian',
        'denda',
        'status'
    ];

    // relasi ke peminjaman (satu pengembalian milik satu peminjaman)
    public function peminjaman()
    {
        return $this->belongsTo(Peminjaman::class, 'id_peminjaman', 'id_peminjaman');
    }
}

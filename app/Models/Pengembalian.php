<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pengembalian extends Model
{
    protected $table = 'pengembalian';

    protected $primaryKey = 'id_pengembalian';

    public $timestamps = false;

    protected $fillable = [
        'id_peminjaman',
        'tgl_pengembalian',
        'denda',
        'status'
    ];

    // 🔥 RELASI KE PEMINJAMAN (WAJIB)
    public function peminjaman()
    {
        return $this->belongsTo(Peminjaman::class, 'id_peminjaman', 'id_peminjaman');
    }
}

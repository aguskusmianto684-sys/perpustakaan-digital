<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Anggota extends Model
{
    protected $table = 'anggota';

    protected $primaryKey = 'id_anggota';

    public $timestamps = false;

    // Kolom yang boleh diisi
    protected $fillable = [
        'id_user', // WAJIB TAMBAH INI
        'nama',
        'alamat',
        'email',
        'no_hp',
        'jenis_kel',
        'tgl_lahir'
    ];
}

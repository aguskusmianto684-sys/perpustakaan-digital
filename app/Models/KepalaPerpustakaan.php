<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KepalaPerpustakaan extends Model
{
    protected $table = 'kepala_perpustakaan';

    protected $primaryKey = 'id_kepala_perpustakaan';

    public $timestamps = false;

    protected $fillable = [
        'id_user',
        'nama',
        'alamat',
        'email',
        'no_hp',
        'jenis_kel',
        'tgl_lahir'
    ];
}

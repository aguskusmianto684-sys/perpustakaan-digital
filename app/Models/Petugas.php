<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Petugas extends Model
{
    protected $table = 'petugas';

    protected $primaryKey = 'id_petugas';

    public $timestamps = false;

    // kolom yang boleh diisi
    protected $fillable = [
        'id_user',
        'nama',
        'alamat',
        'email',
        'no_hp',
        'jenis_kel',
        'tgl_lahir',
        'status'
    ];
}

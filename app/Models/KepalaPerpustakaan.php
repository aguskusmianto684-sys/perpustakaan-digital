<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KepalaPerpustakaan extends Model
{
    // menentukan nama tabel di database
    protected $table = 'kepala_perpustakaan';

    // menentukan primary key tabel
    protected $primaryKey = 'id_kepala_perpustakaan';

    // menonaktifkan timestamps
    public $timestamps = false;

    // field yang boleh diisi (mass assignment)
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

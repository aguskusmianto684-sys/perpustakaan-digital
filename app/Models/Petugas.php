<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Petugas extends Model
{
    // menentukan nama tabel di database
    protected $table = 'petugas';

    // menentukan primary key
    protected $primaryKey = 'id_petugas';

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
        'tgl_lahir',
        'status'
    ];

    // relasi ke user (petugas milik satu user)
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }

}

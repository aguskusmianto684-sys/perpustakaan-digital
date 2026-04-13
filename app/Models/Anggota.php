<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Anggota extends Model
{
    // menentukan nama tabel di database
    protected $table = 'anggota';

    // menentukan primary key tabel
    protected $primaryKey = 'id_anggota';

    // menonaktifkan timestamps (created_at dan updated_at)
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

    // relasi ke tabel user (anggota milik satu user)
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }
    public function peminjaman()
    {
        return $this->hasMany(Peminjaman::class, 'id_anggota');
    }
}

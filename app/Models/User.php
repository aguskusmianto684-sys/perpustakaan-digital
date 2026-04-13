<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    protected $table = 'users';
    protected $primaryKey = 'id_user';
    public $timestamps = false;

    protected $fillable = [
        'username',
        'password',
        'role'
    ];

    protected $hidden = [
        'password'
    ];

    // Relasi ke petugas
    public function petugas()
    {
        return $this->hasOne(Petugas::class, 'id_user', 'id_user');
    }

    // Relasi ke anggota
    public function anggota()
    {
        return $this->hasOne(Anggota::class, 'id_user', 'id_user');
    }

    // Relasi ke kepala
    public function kepala()
    {
        return $this->hasOne(KepalaPerpustakaan::class, 'id_user');
    }
}

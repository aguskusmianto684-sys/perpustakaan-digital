<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    protected $table = 'users';

    protected $primaryKey = 'id_user';

    public $timestamps = false;

    // Kolom yang boleh diisi
    protected $fillable = [
        'username',
        'password',
        'role'
    ];

    // Kolom yang disembunyikan (keamanan)
    protected $hidden = [
        'password'
    ];
}

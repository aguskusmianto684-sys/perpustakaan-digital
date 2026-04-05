<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Petugas extends Model
{
    protected $table = 'petugas';

    protected $primaryKey = 'id_petugas';

    public $timestamps = false;

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

    // Relasi ke user
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }
    
}

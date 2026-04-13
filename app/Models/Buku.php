<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Buku extends Model
{
    // menentukan nama tabel di database
    protected $table = 'buku';

    // menentukan primary key
    protected $primaryKey = 'id_buku';

    // menonaktifkan timestamps (created_at dan updated_at)
    public $timestamps = false;

    // field yang boleh diisi (mass assignment)
    protected $fillable = [
        'judul',
        'penulis',
        'penerbit',
        'tahun_terbit',
        'kategori',
        'stok',
        'gambar',
        'deskripsi'
    ];
}

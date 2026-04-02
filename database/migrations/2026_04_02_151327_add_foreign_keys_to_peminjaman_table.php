<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('peminjaman', function (Blueprint $table) {
            $table->foreign(['id_buku'], 'fk_peminjaman_buku')->references(['id_buku'])->on('buku')->onUpdate('cascade')->onDelete('no action');
            $table->foreign(['id_anggota'], 'peminjaman_ibfk_1')->references(['id_anggota'])->on('anggota')->onUpdate('no action')->onDelete('no action');
            $table->foreign(['id_petugas'], 'peminjaman_ibfk_2')->references(['id_petugas'])->on('petugas')->onUpdate('no action')->onDelete('no action');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('peminjaman', function (Blueprint $table) {
            $table->dropForeign('fk_peminjaman_buku');
            $table->dropForeign('peminjaman_ibfk_1');
            $table->dropForeign('peminjaman_ibfk_2');
        });
    }
};

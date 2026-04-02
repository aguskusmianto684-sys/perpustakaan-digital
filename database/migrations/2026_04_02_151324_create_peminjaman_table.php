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
        Schema::create('peminjaman', function (Blueprint $table) {
            $table->integer('id_peminjaman', true);
            $table->integer('id_anggota')->nullable()->index('id_anggota');
            $table->integer('id_petugas')->nullable()->index('id_petugas');
            $table->integer('id_buku')->nullable()->index('fk_peminjaman_buku');
            $table->date('tgl_pinjam')->nullable();
            $table->date('tgl_kembali')->nullable();
            $table->enum('status', ['menunggu', 'dipinjam', 'dikembalikan', 'ditolak'])->nullable();
            $table->integer('denda')->nullable()->default(0);
            $table->date('tgl_dikembalikan')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('peminjaman');
    }
};

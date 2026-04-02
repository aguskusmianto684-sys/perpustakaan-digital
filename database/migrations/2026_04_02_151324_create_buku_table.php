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
        Schema::create('buku', function (Blueprint $table) {
            $table->integer('id_buku', true);
            $table->string('judul', 150)->nullable();
            $table->string('penulis', 100)->nullable();
            $table->string('penerbit', 100)->nullable();
            $table->integer('tahun_terbit')->nullable();
            $table->string('kategori', 50)->nullable();
            $table->integer('stok')->nullable();
            $table->string('gambar')->nullable();
            $table->text('deskripsi')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('buku');
    }
};

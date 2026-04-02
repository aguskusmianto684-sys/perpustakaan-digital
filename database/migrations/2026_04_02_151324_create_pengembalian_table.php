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
        Schema::create('pengembalian', function (Blueprint $table) {
            $table->integer('id_pengembalian', true);
            $table->integer('id_peminjaman')->nullable()->unique('id_peminjaman');
            $table->date('tgl_pengembalian')->nullable();
            $table->decimal('denda', 10)->nullable();
            $table->enum('status', ['tepat waktu', 'terlambat', 'hilang', 'rusak'])->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengembalian');
    }
};

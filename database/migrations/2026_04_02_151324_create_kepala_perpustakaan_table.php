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
        Schema::create('kepala_perpustakaan', function (Blueprint $table) {
            $table->integer('id_kepala_perpustakaan', true);
            $table->integer('id_user')->nullable()->unique('id_user');
            $table->string('nama', 100)->nullable();
            $table->text('alamat')->nullable();
            $table->string('email', 100)->nullable();
            $table->string('no_hp', 20)->nullable();
            $table->enum('jenis_kel', ['L', 'P'])->nullable();
            $table->date('tgl_lahir')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kepala_perpustakaan');
    }
};

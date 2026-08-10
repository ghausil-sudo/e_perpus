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
        Schema::create('shelves', function (Blueprint $table) {
            $table->id();
            $table->string('kode_rak')->unique();      // Contoh: RAK-A01
            $table->string('nama_rak');                // Contoh: Rak Ilmu Pengetahuan
            $table->string('lokasi')->nullable();      // Contoh: Lantai 1 / Etalase Depan
            $table->enum('tipe', ['rak', 'etalase'])->default('rak'); // Pembeda rak simpan & etalase
            $table->integer('kapasitas')->default(0);  // Kapasitas maksimal buku di rak
            $table->text('deskripsi')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shelves');
    }
};

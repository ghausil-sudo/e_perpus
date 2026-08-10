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
        Schema::create('books', function (Blueprint $table) {
            $table->id();
            $table->string('kode_buku')->unique();
            $table->string('judul');
            $table->string('isbn')->nullable();
            $table->foreignId('category_id')->constrained()->onDelete('cascade');
            $table->foreignId('publisher_id')->constrained()->onDelete('cascade');
            $table->foreignId('author_id')->constrained()->onDelete('cascade');
            $table->foreignId('shelf_id')->nullable()->constrained()->onDelete('set null'); // Relasi ke Rak/Etalase
            $table->year('tahun_terbit');
            $table->integer('jumlah')->default(0);          // total stok
            $table->integer('tersedia')->default(0);        // stok tersedia
            $table->string('cover')->nullable();
            $table->text('deskripsi')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('books');
    }
};

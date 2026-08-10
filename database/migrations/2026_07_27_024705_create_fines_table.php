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
        Schema::create('fines', function (Blueprint $table) {
            $table->id();
            $table->string('kode_denda')->unique();
            $table->foreignId('member_id')->constrained()->onDelete('cascade');
            $table->foreignId('loan_id')->nullable()->constrained()->onDelete('cascade');
            $table->decimal('jumlah', 10, 2);
            $table->text('keterangan');
            $table->enum('status', ['belum_bayar', 'lunas'])->default('belum_bayar');
            $table->date('tanggal_bayar')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fines');
    }
};

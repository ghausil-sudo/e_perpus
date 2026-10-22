<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('member_nis_nip')->nullable()->after('member_code');
            $table->string('member_jenis_kelamin', 1)->nullable()->after('member_nis_nip');
            $table->string('member_alamat')->nullable()->after('member_jenis_kelamin');
            $table->string('member_telepon')->nullable()->after('member_alamat');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'member_nis_nip',
                'member_jenis_kelamin',
                'member_alamat',
                'member_telepon',
            ]);
        });
    }
};

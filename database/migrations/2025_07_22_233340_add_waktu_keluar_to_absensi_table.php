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
        Schema::table('absensi', function (Blueprint $table) {
            $table->datetime('waktu_keluar')->nullable()->after('waktu_masuk');
            $table->string('foto_keluar')->nullable()->after('foto_kegiatan');
            $table->decimal('latitude_keluar', 10, 8)->nullable()->after('longitude');
            $table->decimal('longitude_keluar', 11, 8)->nullable()->after('latitude_keluar');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('absensi', function (Blueprint $table) {
            $table->dropColumn(['waktu_keluar', 'foto_keluar', 'latitude_keluar', 'longitude_keluar']);
        });
    }
};

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
        Schema::table('logbook', function (Blueprint $table) {
            $table->string('judul')->after('waktu_selesai');
            $table->enum('jenis', ['individu', 'desa', 'kecamatan'])->after('judul');
            $table->text('keterangan')->after('jenis');
            $table->dropColumn(['jenis_kegiatan', 'deskripsi_kegiatan', 'lokasi_kegiatan']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('logbook', function (Blueprint $table) {
            $table->dropColumn(['judul', 'jenis', 'keterangan']);
            $table->string('jenis_kegiatan');
            $table->text('deskripsi_kegiatan');
            $table->string('lokasi_kegiatan');
        });
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('nilai', function (Blueprint $table) {
            // Drop kolom lama
            $table->dropColumn([
                'nilai_kehadiran',
                'nilai_aktivitas', 
                'nilai_laporan',
                'nilai_presentasi'
            ]);

            // Tambah kolom baru sesuai bobot KKN Tematik
            // Tahap Pembekalan (10%)
            $table->decimal('nilai_kehadiran_pembekalan', 5, 2)->default(0); // 5%
            $table->decimal('nilai_sikap_pembekalan', 5, 2)->default(0); // 5%
            
            // Pelaksanaan (60%)
            $table->decimal('nilai_kehadiran_lokasi', 5, 2)->default(0); // 5%
            $table->decimal('nilai_sikap_pelaksanaan', 5, 2)->default(0); // 5%
            $table->decimal('nilai_keterlibatan_kegiatan', 5, 2)->default(0); // 15%
            $table->decimal('nilai_relevansi_program', 5, 2)->default(0); // 15%
            $table->decimal('nilai_keberhasilan_program', 5, 2)->default(0); // 20%
            
            // Laporan KKN Tematik (30%)
            $table->decimal('nilai_sistematika_laporan', 5, 2)->default(0); // 3%
            $table->decimal('nilai_konten_medsos', 5, 2)->default(0); // 7%
            $table->decimal('nilai_bahasa', 5, 2)->default(0); // 2%
            $table->decimal('nilai_analisis', 5, 2)->default(0); // 3%
            $table->decimal('nilai_ketepatan_waktu', 5, 2)->default(0); // 5%
            $table->decimal('nilai_produk_teknologi', 5, 2)->default(0); // 10%
        });
    }

    public function down(): void
    {
        Schema::table('nilai', function (Blueprint $table) {
            // Drop kolom baru
            $table->dropColumn([
                'nilai_kehadiran_pembekalan',
                'nilai_sikap_pembekalan',
                'nilai_kehadiran_lokasi',
                'nilai_sikap_pelaksanaan',
                'nilai_keterlibatan_kegiatan',
                'nilai_relevansi_program',
                'nilai_keberhasilan_program',
                'nilai_sistematika_laporan',
                'nilai_konten_medsos',
                'nilai_bahasa',
                'nilai_analisis',
                'nilai_ketepatan_waktu',
                'nilai_produk_teknologi'
            ]);

            // Kembalikan kolom lama
            $table->decimal('nilai_kehadiran', 5, 2);
            $table->decimal('nilai_aktivitas', 5, 2);
            $table->decimal('nilai_laporan', 5, 2);
            $table->decimal('nilai_presentasi', 5, 2);
        });
    }
};

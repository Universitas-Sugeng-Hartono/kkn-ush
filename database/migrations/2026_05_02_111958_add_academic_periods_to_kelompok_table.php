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
        Schema::table('kelompok', function (Blueprint $table) {
            // Kita buat angkatan_id menjadi nullable agar tidak error jika tidak diisi di sistem baru
            if (Schema::hasColumn('kelompok', 'angkatan_id')) {
                $table->unsignedBigInteger('angkatan_id')->nullable()->change();
            }

            if (!Schema::hasColumn('kelompok', 'tahun_akademik_id')) {
                $table->foreignId('tahun_akademik_id')
                    ->nullable()
                    ->after('nama_kelompok')
                    ->constrained('tahun_akademik')
                    ->nullOnDelete();
            }

            if (!Schema::hasColumn('kelompok', 'semester_id')) {
                $table->foreignId('semester_id')
                    ->nullable()
                    ->after('tahun_akademik_id')
                    ->constrained('semester')
                    ->nullOnDelete();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('kelompok', function (Blueprint $table) {
            if (Schema::hasColumn('kelompok', 'semester_id')) {
                $table->dropForeign(['semester_id']);
                $table->dropColumn('semester_id');
            }

            if (Schema::hasColumn('kelompok', 'tahun_akademik_id')) {
                $table->dropForeign(['tahun_akademik_id']);
                $table->dropColumn('tahun_akademik_id');
            }

            // Kembalikan angkatan_id ke original state jika diperlukan
            // Note: ini bisa error jika ada data null, jadi hati-hati.
        });
    }
};

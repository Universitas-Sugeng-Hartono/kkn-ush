<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('angkatan', function (Blueprint $table) {
            if (!Schema::hasColumn('angkatan', 'tahun_akademik_id')) {
                $table->foreignId('tahun_akademik_id')
                    ->nullable()
                    ->after('id')
                    ->constrained('tahun_akademik')
                    ->nullOnDelete();
            }

            if (!Schema::hasColumn('angkatan', 'semester_id')) {
                $table->foreignId('semester_id')
                    ->nullable()
                    ->after('tahun_akademik_id')
                    ->constrained('semester')
                    ->nullOnDelete();
            }
        });
    }

    public function down(): void
    {
        Schema::table('angkatan', function (Blueprint $table) {
            $table->dropForeign(['semester_id']);
            $table->dropColumn('semester_id');

            $table->dropForeign(['tahun_akademik_id']);
            $table->dropColumn('tahun_akademik_id');
        });
    }
};

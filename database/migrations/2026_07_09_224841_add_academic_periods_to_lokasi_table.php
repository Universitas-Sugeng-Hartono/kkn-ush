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
        Schema::table('lokasi', function (Blueprint $table) {
            $table->unsignedBigInteger('tahun_akademik_id')->nullable()->after('id');
            $table->unsignedBigInteger('semester_id')->nullable()->after('tahun_akademik_id');

            $table->foreign('tahun_akademik_id')->references('id')->on('tahun_akademik')->onDelete('set null');
            $table->foreign('semester_id')->references('id')->on('semester')->onDelete('set null');
        });

        // Set all existing locations to 2024/2025 Genap (ID 1)
        \DB::table('lokasi')->update([
            'tahun_akademik_id' => 1,
            'semester_id' => 1
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('lokasi', function (Blueprint $table) {
            $table->dropForeign(['tahun_akademik_id']);
            $table->dropForeign(['semester_id']);
            $table->dropColumn(['tahun_akademik_id', 'semester_id']);
        });
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('tahun_akademik')) {
            Schema::create('tahun_akademik', function (Blueprint $table) {
                $table->id();
                $table->string('nama')->unique();
                $table->boolean('is_aktif')->default(false);
                $table->timestamps();
            });
            return;
        }

        Schema::table('tahun_akademik', function (Blueprint $table) {
            if (!Schema::hasColumn('tahun_akademik', 'nama')) {
                $table->string('nama')->unique()->after('id');
            }
            if (!Schema::hasColumn('tahun_akademik', 'is_aktif')) {
                $table->boolean('is_aktif')->default(false)->after('nama');
            }
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tahun_akademik');
    }
};

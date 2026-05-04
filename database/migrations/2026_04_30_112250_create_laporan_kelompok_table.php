<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('laporan_kelompok')) {
            Schema::create('laporan_kelompok', function (Blueprint $table) {
                $table->id();
                $table->foreignId('kelompok_id')->constrained('kelompok')->cascadeOnDelete();
                $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
                $table->string('judul')->nullable();
                $table->text('keterangan')->nullable();
                $table->string('file_path');
                $table->string('file_original_name');
                $table->unsignedBigInteger('file_size')->default(0);
                $table->string('file_mime')->nullable();
                $table->timestamps();
            });
            return;
        }

        Schema::table('laporan_kelompok', function (Blueprint $table) {
            if (!Schema::hasColumn('laporan_kelompok', 'kelompok_id')) {
                $table->foreignId('kelompok_id')->constrained('kelompok')->cascadeOnDelete()->after('id');
            }
            if (!Schema::hasColumn('laporan_kelompok', 'user_id')) {
                $table->foreignId('user_id')->constrained('users')->cascadeOnDelete()->after('kelompok_id');
            }
            if (!Schema::hasColumn('laporan_kelompok', 'judul')) {
                $table->string('judul')->nullable()->after('user_id');
            }
            if (!Schema::hasColumn('laporan_kelompok', 'keterangan')) {
                $table->text('keterangan')->nullable()->after('judul');
            }
            if (!Schema::hasColumn('laporan_kelompok', 'file_path')) {
                $table->string('file_path')->after('keterangan');
            }
            if (!Schema::hasColumn('laporan_kelompok', 'file_original_name')) {
                $table->string('file_original_name')->after('file_path');
            }
            if (!Schema::hasColumn('laporan_kelompok', 'file_size')) {
                $table->unsignedBigInteger('file_size')->default(0)->after('file_original_name');
            }
            if (!Schema::hasColumn('laporan_kelompok', 'file_mime')) {
                $table->string('file_mime')->nullable()->after('file_size');
            }
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('laporan_kelompok');
    }
};

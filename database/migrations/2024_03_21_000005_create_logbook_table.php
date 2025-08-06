<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('logbook', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('kelompok_id')->constrained('kelompok')->onDelete('cascade');
            $table->date('tanggal');
            $table->time('waktu_mulai');
            $table->time('waktu_selesai');
            $table->string('jenis_kegiatan');
            $table->text('deskripsi_kegiatan');
            $table->string('lokasi_kegiatan');
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->string('foto_kegiatan')->nullable();
            $table->enum('status', ['draft', 'submitted', 'approved', 'rejected'])->default('draft');
            $table->text('komentar_dpl')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('logbook');
    }
}; 
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('absensi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('kelompok_id')->constrained('kelompok')->onDelete('cascade');
            $table->date('tanggal');
            $table->time('waktu_masuk');
            $table->string('foto_kegiatan');
            $table->decimal('latitude', 10, 8);
            $table->decimal('longitude', 11, 8);
            $table->enum('status', ['pending', 'validated', 'rejected'])->default('pending');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('absensi');
    }
}; 
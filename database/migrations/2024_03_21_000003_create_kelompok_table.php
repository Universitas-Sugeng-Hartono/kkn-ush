<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kelompok', function (Blueprint $table) {
            $table->id();
            $table->string('nama_kelompok');
            $table->foreignId('angkatan_id')->constrained('angkatan')->onDelete('cascade');
            $table->foreignId('lokasi_id')->constrained('lokasi')->onDelete('cascade');
            $table->unsignedBigInteger('dpl_id')->nullable();
            $table->text('deskripsi')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kelompok');
    }
}; 
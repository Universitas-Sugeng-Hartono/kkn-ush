<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('dokumen', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('file_path');
            $table->string('jenis'); // panduan, template, laporan, lainnya
            $table->text('keterangan')->nullable();
            $table->unsignedBigInteger('ukuran'); // ukuran file dalam bytes
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dokumen');
    }
}; 
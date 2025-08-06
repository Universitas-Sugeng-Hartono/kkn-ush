<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lokasi', function (Blueprint $table) {
            $table->id();
            $table->string('nama_desa');
            $table->string('nama_kecamatan');
            $table->string('nama_kabupaten');
            $table->string('nama_provinsi');
            $table->decimal('latitude', 10, 8);
            $table->decimal('longitude', 11, 8);
            $table->text('deskripsi')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lokasi');
    }
}; 
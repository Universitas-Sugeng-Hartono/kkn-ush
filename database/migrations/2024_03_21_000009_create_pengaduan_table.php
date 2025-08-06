<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pengaduan', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_pengaduan')->unique();
            $table->string('nama_pelapor');
            $table->string('email_pelapor');
            $table->string('no_hp_pelapor');
            $table->string('subjek');
            $table->text('isi_pengaduan');
            $table->string('bukti_pendukung')->nullable();
            $table->foreignId('lokasi_id')->constrained('lokasi')->onDelete('cascade');
            $table->enum('status', ['pending', 'process', 'resolved', 'rejected'])->default('pending');
            $table->text('tanggapan')->nullable();
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null'); // admin yang menangani
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pengaduan');
    }
}; 
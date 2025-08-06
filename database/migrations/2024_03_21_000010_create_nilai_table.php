<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('nilai', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('kelompok_id')->constrained('kelompok')->onDelete('cascade');
            $table->decimal('nilai_kehadiran', 5, 2);
            $table->decimal('nilai_aktivitas', 5, 2);
            $table->decimal('nilai_laporan', 5, 2);
            $table->decimal('nilai_presentasi', 5, 2);
            $table->decimal('nilai_akhir', 5, 2);
            $table->char('grade', 2);
            $table->text('catatan')->nullable();
            $table->foreignId('dpl_id')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('nilai');
    }
}; 
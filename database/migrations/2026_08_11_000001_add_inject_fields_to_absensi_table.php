<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('absensi', function (Blueprint $table) {
            $table->foreignId('injected_by')->nullable()->after('status')->constrained('users')->nullOnDelete();
            $table->text('catatan_inject')->nullable()->after('injected_by');
        });

        // Buat foto_kegiatan nullable (agar admin bisa inject tanpa foto)
        Schema::table('absensi', function (Blueprint $table) {
            $table->string('foto_kegiatan')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('absensi', function (Blueprint $table) {
            $table->dropForeign(['injected_by']);
            $table->dropColumn(['injected_by', 'catatan_inject']);
        });

        Schema::table('absensi', function (Blueprint $table) {
            $table->string('foto_kegiatan')->nullable(false)->change();
        });
    }
};

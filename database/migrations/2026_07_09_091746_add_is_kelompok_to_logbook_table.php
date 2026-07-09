<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('logbook', function (Blueprint $table) {
            if (!Schema::hasColumn('logbook', 'is_kelompok')) {
                $table->boolean('is_kelompok')->default(false)->after('jenis');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('logbook', function (Blueprint $table) {
            if (Schema::hasColumn('logbook', 'is_kelompok')) {
                $table->dropColumn('is_kelompok');
            }
        });
    }
};

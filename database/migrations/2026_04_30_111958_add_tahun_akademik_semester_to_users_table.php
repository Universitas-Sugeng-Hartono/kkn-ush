<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'tahun_akademik_id')) {
                $table->foreignId('tahun_akademik_id')
                    ->nullable()
                    ->after('angkatan_id')
                    ->constrained('tahun_akademik')
                    ->nullOnDelete();
            }

            if (!Schema::hasColumn('users', 'semester_id')) {
                $table->foreignId('semester_id')
                    ->nullable()
                    ->after('tahun_akademik_id')
                    ->constrained('semester')
                    ->nullOnDelete();
            }
        });

        // Backfill dari relasi angkatan (jika ada data lama).
        if (!Schema::hasColumn('users', 'angkatan_id')) {
            return;
        }

        $users = DB::table('users')
            ->join('angkatan', 'users.angkatan_id', '=', 'angkatan.id')
            ->whereNotNull('users.angkatan_id')
            ->whereNull('users.tahun_akademik_id')
            ->whereNull('users.semester_id')
            ->whereNotNull('angkatan.tahun_akademik_id')
            ->whereNotNull('angkatan.semester_id')
            ->select('users.id', 'angkatan.tahun_akademik_id', 'angkatan.semester_id')
            ->get();

        foreach ($users as $user) {
            DB::table('users')
                ->where('id', $user->id)
                ->update([
                    'tahun_akademik_id' => $user->tahun_akademik_id,
                    'semester_id' => $user->semester_id,
                ]);
        }
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['semester_id']);
            $table->dropColumn('semester_id');

            $table->dropForeign(['tahun_akademik_id']);
            $table->dropColumn('tahun_akademik_id');
        });
    }
};

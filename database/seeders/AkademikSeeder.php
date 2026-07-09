<?php

namespace Database\Seeders;

use App\Models\Kelompok;
use App\Models\Semester;
use App\Models\TahunAkademik;
use App\Models\User;
use Illuminate\Database\Seeder;

class AkademikSeeder extends Seeder
{
    /**
     * Membuat data awal Tahun Akademik & Semester, lalu
     * menghubungkan data KKN yang sudah ada (NULL) ke periode tersebut.
     */
    public function run(): void
    {
        // 1. Buat Tahun Akademik "2024/2025"
        $tahunAkademik = TahunAkademik::firstOrCreate(
            ['nama' => '2024/2025'],
            ['is_aktif' => true]
        );

        // Pastikan hanya satu yang aktif
        TahunAkademik::where('id', '!=', $tahunAkademik->id)->update(['is_aktif' => false]);
        $tahunAkademik->update(['is_aktif' => true]);

        // 2. Buat Semester "Genap"
        $semester = Semester::firstOrCreate(
            ['nama' => 'Genap'],
            ['is_aktif' => true]
        );

        // Pastikan hanya satu yang aktif
        Semester::where('id', '!=', $semester->id)->update(['is_aktif' => false]);
        $semester->update(['is_aktif' => true]);

        $this->command->info("Tahun Akademik '{$tahunAkademik->nama}' dan Semester '{$semester->nama}' siap.");

        // 3. Update semua Kelompok yang tahun_akademik_id-nya masih NULL
        $kelompokCount = Kelompok::whereNull('tahun_akademik_id')->count();
        if ($kelompokCount > 0) {
            Kelompok::whereNull('tahun_akademik_id')->update([
                'tahun_akademik_id' => $tahunAkademik->id,
                'semester_id'       => $semester->id,
            ]);
            $this->command->info("{$kelompokCount} kelompok berhasil dihubungkan ke periode 2024/2025 - Genap.");
        } else {
            $this->command->info("Semua kelompok sudah memiliki tahun akademik.");
        }

        // 4. Update semua User mahasiswa yang tahun_akademik_id-nya masih NULL
        $mahasiswaCount = User::role('mahasiswa')->whereNull('tahun_akademik_id')->count();
        if ($mahasiswaCount > 0) {
            User::role('mahasiswa')->whereNull('tahun_akademik_id')->update([
                'tahun_akademik_id' => $tahunAkademik->id,
                'semester_id'       => $semester->id,
            ]);
            $this->command->info("{$mahasiswaCount} mahasiswa berhasil dihubungkan ke periode 2024/2025 - Genap.");
        } else {
            $this->command->info("Semua mahasiswa sudah memiliki tahun akademik.");
        }

        $this->command->info('AkademikSeeder selesai!');
    }
}

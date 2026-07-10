<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class SyncAcademicPeriod extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:sync-academic-period';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sinkronisasi data lama yang belum memiliki Tahun Akademik & Semester ke periode yang sedang aktif';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $ta = \App\Models\TahunAkademik::getAktif();
        $sem = \App\Models\Semester::getAktif();

        if (!$ta || !$sem) {
            $this->error('Tahun Akademik atau Semester aktif tidak ditemukan! Pastikan sudah ada yang diaktifkan.');
            return;
        }

        $this->info("Menyinkronkan data lama ke: {$ta->nama} - {$sem->nama}");

        // 1. Sinkronisasi User (Mahasiswa & DPL)
        $usersUpdated = \App\Models\User::whereNull('tahun_akademik_id')
            ->orWhere('tahun_akademik_id', '!=', $ta->id)
            ->update([
                'tahun_akademik_id' => $ta->id,
                'semester_id' => $sem->id
            ]);
        $this->info("- User (Mahasiswa/DPL) diperbarui: {$usersUpdated} data");

        // 2. Sinkronisasi Kelompok
        $kelompokUpdated = \App\Models\Kelompok::whereNull('tahun_akademik_id')
            ->orWhere('tahun_akademik_id', '!=', $ta->id)
            ->update([
                'tahun_akademik_id' => $ta->id,
                'semester_id' => $sem->id
            ]);
        $this->info("- Kelompok diperbarui: {$kelompokUpdated} data");

        // 3. Sinkronisasi Lokasi
        $lokasiUpdated = \App\Models\Lokasi::whereNull('tahun_akademik_id')
            ->orWhere('tahun_akademik_id', '!=', $ta->id)
            ->update([
                'tahun_akademik_id' => $ta->id,
                'semester_id' => $sem->id
            ]);
        $this->info("- Lokasi diperbarui: {$lokasiUpdated} data");

        $this->info('Sinkronisasi selesai!');
    }
}

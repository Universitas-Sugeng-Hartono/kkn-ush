<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Absensi;
use App\Models\User;
use App\Models\Kelompok;
use Carbon\Carbon;

class AttendancePendingSeeder extends Seeder
{
    public function run()
    {
        // Ambil DPL yang sudah ada
        $dpl = User::whereHas('roles', function($query) {
            $query->where('name', 'dpl');
        })->first();

        if (!$dpl) {
            $this->command->info('DPL tidak ditemukan. Silakan jalankan UserSeeder terlebih dahulu.');
            return;
        }

        // Ambil atau buat kelompok yang memiliki DPL
        $kelompok = Kelompok::where('dpl_id', $dpl->id)->first();
        
        if (!$kelompok) {
            $kelompok = Kelompok::create([
                'nama_kelompok' => 'Kelompok Test DPL',
                'dpl_id' => $dpl->id,
                'lokasi_id' => 1, // Pastikan lokasi dengan ID 1 ada
                'angkatan_id' => 1 // Pastikan angkatan dengan ID 1 ada
            ]);
        }

        // Ambil mahasiswa yang sudah ada dan assign ke kelompok
        $mahasiswa = User::whereHas('roles', function($query) {
            $query->where('name', 'mahasiswa');
        })->take(3)->get();

        foreach ($mahasiswa as $student) {
            $student->update(['kelompok_id' => $kelompok->id]);
        }

        // Buat absensi pending untuk setiap mahasiswa
        $attendanceData = [
            [
                'tanggal' => Carbon::now()->subDays(2),
                'waktu_masuk' => '08:00:00',
                'status' => 'pending',
                'latitude' => -6.9175,
                'longitude' => 106.9277
            ],
            [
                'tanggal' => Carbon::now()->subDays(1),
                'waktu_masuk' => '07:30:00',
                'status' => 'pending',
                'latitude' => -6.9175,
                'longitude' => 106.9277
            ],
            [
                'tanggal' => Carbon::now(),
                'waktu_masuk' => '08:15:00',
                'status' => 'pending',
                'latitude' => -6.9175,
                'longitude' => 106.9277
            ]
        ];

        foreach ($mahasiswa as $index => $student) {
            if (isset($attendanceData[$index])) {
                $data = $attendanceData[$index];
                
                Absensi::create([
                    'user_id' => $student->id,
                    'kelompok_id' => $kelompok->id,
                    'tanggal' => $data['tanggal'],
                    'waktu_masuk' => $data['waktu_masuk'],
                    'status' => $data['status'],
                    'latitude' => $data['latitude'],
                    'longitude' => $data['longitude'],
                    'foto_kegiatan' => 'absensi/default-selfie.jpg', // Foto default
                    'created_at' => Carbon::now()->subHours(rand(1, 24)),
                    'updated_at' => Carbon::now()->subHours(rand(1, 24))
                ]);
            }
        }

        $this->command->info('Data absensi pending berhasil dibuat!');
    }
} 
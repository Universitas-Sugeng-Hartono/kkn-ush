<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Logbook;
use App\Models\User;
use App\Models\Kelompok;
use Carbon\Carbon;

class LogbookPendingSeeder extends Seeder
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

        // Buat logbook pending untuk setiap mahasiswa
        $logbookData = [
            [
                'judul' => 'Kunjungan ke Desa Sukamaju',
                'keterangan' => 'Melakukan observasi awal di Desa Sukamaju untuk memahami kondisi sosial ekonomi masyarakat setempat. Bertemu dengan kepala desa dan beberapa tokoh masyarakat untuk membahas program KKN yang akan dilaksanakan.',
                'lokasi' => 'Desa Sukamaju, Kecamatan Sukabumi',
                'tanggal' => Carbon::now()->subDays(2),
                'waktu_mulai' => '08:00:00',
                'waktu_selesai' => '12:00:00',
                'jenis' => 'desa'
            ],
            [
                'judul' => 'Pembuatan Program Kerja',
                'keterangan' => 'Menyusun program kerja KKN berdasarkan hasil observasi dan kebutuhan masyarakat. Program meliputi pemberdayaan ekonomi, pendidikan, dan kesehatan masyarakat.',
                'lokasi' => 'Kantor Desa Sukamaju',
                'tanggal' => Carbon::now()->subDays(1),
                'waktu_mulai' => '08:00:00',
                'waktu_selesai' => '14:00:00',
                'jenis' => 'desa'
            ],
            [
                'judul' => 'Pelatihan Komputer untuk Pemuda',
                'keterangan' => 'Mengadakan pelatihan dasar komputer untuk pemuda desa. Materi meliputi pengenalan Microsoft Office, internet, dan media sosial untuk mendukung UMKM.',
                'lokasi' => 'Balai Desa Sukamaju',
                'tanggal' => Carbon::now(),
                'waktu_mulai' => '09:00:00',
                'waktu_selesai' => '12:00:00',
                'jenis' => 'desa'
            ]
        ];

        foreach ($mahasiswa as $index => $student) {
            if (isset($logbookData[$index])) {
                $data = $logbookData[$index];
                
                Logbook::create([
                    'user_id' => $student->id,
                    'kelompok_id' => $kelompok->id,
                    'judul' => $data['judul'],
                    'keterangan' => $data['keterangan'],
                    'lokasi' => $data['lokasi'],
                    'tanggal' => $data['tanggal'],
                    'waktu_mulai' => $data['waktu_mulai'],
                    'waktu_selesai' => $data['waktu_selesai'],
                    'jenis' => $data['jenis'],
                    'status' => 'submitted', // Status pending untuk validasi DPL
                    'created_at' => Carbon::now()->subHours(rand(1, 24)),
                    'updated_at' => Carbon::now()->subHours(rand(1, 24))
                ]);
            }
        }

        $this->command->info('Data logbook pending berhasil dibuat!');
    }
} 
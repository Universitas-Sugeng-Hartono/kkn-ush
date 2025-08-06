<?php

namespace Database\Seeders;

use App\Models\Angkatan;
use Illuminate\Database\Seeder;

class AngkatanSeeder extends Seeder
{
    public function run(): void
    {
        Angkatan::create([
            'nama_angkatan' => 'Angkatan 1 - 2024',
            'tanggal_mulai' => '2024-07-01',
            'tanggal_selesai' => '2024-08-31',
            'status' => 'persiapan',
            'deskripsi' => 'Angkatan Pertama KKN USH Tahun 2024'
        ]);
    }
} 
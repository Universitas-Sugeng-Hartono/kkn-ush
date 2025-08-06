<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Lokasi;
use App\Models\Kelompok;
use App\Models\Angkatan;
use Illuminate\Database\Seeder;

class KelompokSeeder extends Seeder
{
    public function run(): void
    {
        $dpl = User::role('dpl')->first();
        $angkatan = Angkatan::first();
        
        $lokasi = Lokasi::all();
        
        foreach ($lokasi as $index => $lok) {
            Kelompok::create([
                'nama_kelompok' => 'Kelompok ' . ($index + 1),
                'lokasi_id' => $lok->id,
                'dpl_id' => $dpl->id,
                'angkatan_id' => $angkatan->id,
                'deskripsi' => 'Kelompok KKN di ' . $lok->nama_desa
            ]);
        }
    }
} 
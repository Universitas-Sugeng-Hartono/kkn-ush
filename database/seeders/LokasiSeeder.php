<?php

namespace Database\Seeders;

use App\Models\Lokasi;
use Illuminate\Database\Seeder;

class LokasiSeeder extends Seeder
{
    public function run(): void
    {
        $lokasi = [
            [
                'nama_desa' => 'Caturtunggal',
                'nama_kecamatan' => 'Depok',
                'nama_kabupaten' => 'Sleman',
                'nama_provinsi' => 'DI Yogyakarta',
                'latitude' => -7.7713,
                'longitude' => 110.3845,
                'deskripsi' => 'Lokasi KKN di wilayah perkotaan'
            ],
            [
                'nama_desa' => 'Condongcatur',
                'nama_kecamatan' => 'Depok',
                'nama_kabupaten' => 'Sleman',
                'nama_provinsi' => 'DI Yogyakarta',
                'latitude' => -7.7560,
                'longitude' => 110.4023,
                'deskripsi' => 'Lokasi KKN di area pendidikan'
            ],
            [
                'nama_desa' => 'Maguwoharjo',
                'nama_kecamatan' => 'Depok',
                'nama_kabupaten' => 'Sleman',
                'nama_provinsi' => 'DI Yogyakarta',
                'latitude' => -7.7651,
                'longitude' => 110.4321,
                'deskripsi' => 'Lokasi KKN di kawasan industri'
            ]
        ];

        foreach ($lokasi as $data) {
            Lokasi::create($data);
        }
    }
} 
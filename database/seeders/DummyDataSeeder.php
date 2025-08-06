<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Galeri;
use App\Models\Berita;
use App\Models\Dokumen;
use App\Models\User;
use Illuminate\Support\Str;

class DummyDataSeeder extends Seeder
{
    public function run(): void
    {
        // Create Admin user
        $admin = User::updateOrCreate(
            ['email' => 'admin@ush.ac.id'],
            [
                'name' => 'Administrator USH',
                'password' => bcrypt('ushjaya'),
                'email_verified_at' => now(),
                'nip' => '198501012010011001',
                'no_hp' => '081234567890',
                'alamat' => 'Jl. Admin No. 1, Sukoharjo'
            ]
        );
        $admin->assignRole('admin');

        // Get first DPL user for creating content
        $dpl = User::role('dpl')->first();
        
        if (!$dpl) {
            // If no DPL exists, create one
            $dpl = User::create([
                'name' => 'Dosen Pembimbing Dummy',
                'email' => 'dpl.dummy@ush.ac.id',
                'password' => bcrypt('100000'),
                'nip' => '100000',
                'no_hp' => '081234567890',
                'alamat' => 'Jl. Dosen Dummy No. 1, Sukoharjo'
            ]);
            $dpl->assignRole('dpl');
        }

        // Create dummy Berita
        Berita::firstOrCreate(
            ['judul' => 'Pembukaan KKN Tematik 2024'],
            [
                'slug' => 'pembukaan-kkn-tematik-2024',
                'konten' => 'Kuliah Kerja Nyata (KKN) Tematik Universitas Sugeng Hartono tahun 2024 telah resmi dibuka. Program ini akan melibatkan mahasiswa dari berbagai jurusan untuk berkontribusi dalam pengembangan masyarakat.',
                'gambar' => 'berita/kkn-pembukaan.jpg',
                'is_published' => true,
                'published_at' => now(),
                'user_id' => $dpl->id
            ]
        );

        // Create dummy Dokumen
        Dokumen::firstOrCreate(
            ['nama' => 'Panduan KKN Tematik 2024'],
            [
                'keterangan' => 'Panduan lengkap pelaksanaan KKN Tematik tahun 2024 untuk mahasiswa dan dosen pembimbing lapangan.',
                'file_path' => 'dokumen/panduan-kkn-2024.pdf',
                'jenis' => 'panduan',
                'ukuran' => 2048576, // 2MB in bytes
                'user_id' => $dpl->id
            ]
        );

        // Create dummy Galeri
        Galeri::firstOrCreate(
            ['judul' => 'Kegiatan KKN di Desa Grogol'],
            [
                'deskripsi' => 'Dokumentasi kegiatan KKN mahasiswa di Desa Grogol, Kecamatan Grogol, Sukoharjo.',
                'gambar' => 'galeri/kkn-grogol.jpg',
                'aktif' => true,
                'urutan' => 1,
                'user_id' => $dpl->id
            ]
        );
    }
} 
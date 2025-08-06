<?php

namespace Database\Seeders;

use App\Models\Galeri;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class GaleriSeeder extends Seeder
{
    public function run(): void
    {
        // Pastikan folder storage/app/public/galeri ada
        if (!File::exists(storage_path('app/public/galeri'))) {
            File::makeDirectory(storage_path('app/public/galeri'), 0777, true);
        }

        $admin = User::role('admin')->first();
        
        // Jika tidak ada admin, gunakan user pertama
        if (!$admin) {
            $admin = User::first();
        }

        if (!$admin) {
            $this->command->warn('Tidak ada user admin ditemukan. Skipping GaleriSeeder.');
            return;
        }

        $galeriData = [
            [
                'judul' => 'Pembukaan KKN 2024',
                'deskripsi' => 'Acara pembukaan KKN USH tahun 2024 yang dihadiri oleh Rektor dan jajaran pimpinan universitas. Kegiatan ini menandai dimulainya program KKN Tematik yang akan dilaksanakan oleh mahasiswa.',
                'gambar' => 'galeri/pembukaan-kkn.jpg',
                'aktif' => true,
                'urutan' => 1,
            ],
            [
                'judul' => 'Kegiatan Sosial Masyarakat',
                'deskripsi' => 'Mahasiswa KKN melakukan kegiatan sosial bersama masyarakat setempat. Kegiatan ini meliputi gotong royong, bantuan sosial, dan pemberdayaan masyarakat.',
                'gambar' => 'galeri/kegiatan-sosial.jpg',
                'aktif' => true,
                'urutan' => 2,
            ],
            [
                'judul' => 'Program Pendidikan',
                'deskripsi' => 'Kegiatan bimbingan belajar untuk anak-anak di lokasi KKN. Program ini bertujuan untuk meningkatkan kualitas pendidikan anak-anak di desa.',
                'gambar' => 'galeri/program-pendidikan.jpg',
                'aktif' => true,
                'urutan' => 3,
            ],
            [
                'judul' => 'Gotong Royong',
                'deskripsi' => 'Kegiatan gotong royong bersama warga dalam pembangunan fasilitas umum. Mahasiswa KKN turut berpartisipasi dalam pembangunan infrastruktur desa.',
                'gambar' => 'galeri/gotong-royong.jpg',
                'aktif' => true,
                'urutan' => 4,
            ],
            [
                'judul' => 'Pelatihan Kewirausahaan',
                'deskripsi' => 'Program pelatihan kewirausahaan untuk masyarakat desa. Mahasiswa KKN memberikan pelatihan dan pendampingan dalam pengembangan usaha.',
                'gambar' => 'galeri/pelatihan-kewirausahaan.jpg',
                'aktif' => true,
                'urutan' => 5,
            ],
            [
                'judul' => 'Kegiatan Kesehatan',
                'deskripsi' => 'Program kesehatan masyarakat yang meliputi pemeriksaan kesehatan, penyuluhan gizi, dan pelatihan hidup sehat.',
                'gambar' => 'galeri/kegiatan-kesehatan.jpg',
                'aktif' => false,
                'urutan' => 6,
            ],
        ];

        foreach ($galeriData as $data) {
            // Cek apakah file gambar sample ada
            $sourcePath = public_path('images/sample/' . basename($data['gambar']));
            $destinationPath = storage_path('app/public/' . $data['gambar']);
            
            // Jika file sample tidak ada, buat file dummy
            if (!File::exists($sourcePath)) {
                // Buat file dummy dengan ukuran minimal
                $dummyImage = imagecreate(300, 200);
                $bgColor = imagecolorallocate($dummyImage, 240, 240, 240);
                $textColor = imagecolorallocate($dummyImage, 100, 100, 100);
                
                // Tambah teks ke gambar
                imagestring($dummyImage, 5, 50, 80, 'Sample Image', $textColor);
                imagestring($dummyImage, 3, 30, 100, basename($data['gambar']), $textColor);
                
                // Simpan gambar
                imagejpeg($dummyImage, $destinationPath, 80);
                imagedestroy($dummyImage);
                
                $this->command->info('Created dummy image: ' . $data['gambar']);
            } else {
                // Copy file sample jika ada
                File::copy($sourcePath, $destinationPath);
                $this->command->info('Copied sample image: ' . $data['gambar']);
            }

            Galeri::create([
                'judul' => $data['judul'],
                'deskripsi' => $data['deskripsi'],
                'gambar' => $data['gambar'],
                'aktif' => $data['aktif'],
                'urutan' => $data['urutan'],
                'user_id' => $admin->id
            ]);
        }

        $this->command->info('Galeri seeder completed successfully!');
    }
} 
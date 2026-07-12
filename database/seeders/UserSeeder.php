<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Create or update admin user
        $admin = User::updateOrCreate(
            ['email' => 'admin@ush.ac.id'],
            [
                'name' => 'Administrator',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
                'nip' => '198501012010011001',
                'no_hp' => '081234567890',
                'alamat' => 'Jl. Admin No. 1, Surakarta'
            ]
        );
        $admin->assignRole('admin');

        // Create or update DPL user
        $dpl = User::updateOrCreate(
            ['email' => 'dpl@ush.ac.id'],
            [
                'name' => 'Dr. Dosen Pembimbing',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
                'nip' => '198203152010011002',
                'no_hp' => '089876543210',
                'alamat' => 'Jl. Dosen No. 1, Surakarta'
            ]
        );
        $dpl->assignRole('dpl');

        // Create or update mahasiswa user
        $mahasiswa = User::updateOrCreate(
            ['email' => 'mahasiswa@ush.ac.id'],
            [
                'name' => 'Mahasiswa KKN',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
                'nim' => '20240001',
                'jurusan' => 'ilmu komputer',
                'no_hp' => '087654321098',
                'alamat' => 'Jl. Mahasiswa No. 1, Surakarta'
            ]
        );
        $mahasiswa->assignRole('mahasiswa');

        // Create or update additional mahasiswa for testing
        $mahasiswa2 = User::updateOrCreate(
            ['email' => 'ahmad.fauzi@student.ush.ac.id'],
            [
                'name' => 'Ahmad Fauzi',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
                'nim' => '20240002',
                'jurusan' => 'bisnis digital',
                'no_hp' => '081234567891',
                'alamat' => 'Jl. Sudirman No. 15, Surakarta'
            ]
        );
        $mahasiswa2->assignRole('mahasiswa');

        $mahasiswa3 = User::updateOrCreate(
            ['email' => 'siti.nurhaliza@student.ush.ac.id'],
            [
                'name' => 'Siti Nurhaliza',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
                'nim' => '20240003',
                'jurusan' => 'gizi',
                'no_hp' => '081234567892',
                'alamat' => 'Jl. Gatot Subroto No. 22, Surakarta'
            ]
        );
        $mahasiswa3->assignRole('mahasiswa');

        // Create or update additional DPL for testing
        $dpl2 = User::updateOrCreate(
            ['email' => 'siti.aminah@ush.ac.id'],
            [
                'name' => 'Dr. Siti Aminah',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
                'nip' => '198504010012003123',
                'no_hp' => '089876543211',
                'alamat' => 'Jl. Profesor No. 5, Surakarta'
            ]
        );
        $dpl2->assignRole('dpl');
    }
} 
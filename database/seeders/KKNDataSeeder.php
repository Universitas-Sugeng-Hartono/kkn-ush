<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Kelompok;
use App\Models\Lokasi;
use Illuminate\Support\Facades\Hash;

class KKNDataSeeder extends Seeder
{
    public function run(): void
    {
        // Create Administrator FIRST (to ensure ID = 1)
        $admin = User::create([
            'id' => 1,
            'name' => 'Administrator',
            'email' => 'admin@ush.ac.id',
            'password' => Hash::make('ushjaya'),
            'email_verified_at' => now(),
            'no_hp' => '081234567890',
            'alamat' => 'Jl. Administrator No. 1, Sukoharjo'
        ]);
        $admin->assignRole('admin');

        // Create Dosen Pembimbing (Supervising Lecturers) with correct NIP
        $dosenPembimbing = [
            [
                'nip' => '111202212',
                'name' => 'Yulaikha Mar\'atullatifah, M.Kom',
                'email' => 'yulaikha.maratullatifah@ush.ac.id',
                'password' => Hash::make('111202212'),
                'no_hp' => '081234567890',
                'alamat' => 'Jl. Dosen No. 1, Sukoharjo'
            ],
            [
                'nip' => '102202545',
                'name' => 'Muhammad Anwar Fauzi, M.Kom',
                'email' => 'muhammad.anwar.fauzi@ush.ac.id',
                'password' => Hash::make('102202545'),
                'no_hp' => '081234567891',
                'alamat' => 'Jl. Dosen No. 2, Sukoharjo'
            ],
            [
                'nip' => '110202442',
                'name' => 'Agatha Pricillia Sekar Tamtomo, M.M',
                'email' => 'agatha.pricillia@ush.ac.id',
                'password' => Hash::make('110202442'),
                'no_hp' => '081234567892',
                'alamat' => 'Jl. Dosen No. 3, Sukoharjo'
            ],
            [
                'nip' => '103202546',
                'name' => 'Ardy Wicaksono, M.Kom',
                'email' => 'ardy.wicaksono@ush.ac.id',
                'password' => Hash::make('103202546'),
                'no_hp' => '081234567893',
                'alamat' => 'Jl. Dosen No. 4, Sukoharjo'
            ],
            [
                'nip' => '106202547',
                'name' => 'Muhammad Adi Pratama, S.S, M.A',
                'email' => 'muhammad.adi.pratama@ush.ac.id',
                'password' => Hash::make('106202547'),
                'no_hp' => '081234567894',
                'alamat' => 'Jl. Dosen No. 5, Sukoharjo'
            ],
            [
                'nip' => '102202544',
                'name' => 'Suyahman, M.Kom',
                'email' => 'suyahman@ush.ac.id',
                'password' => Hash::make('102202544'),
                'no_hp' => '081234567895',
                'alamat' => 'Jl. Dosen No. 6, Sukoharjo'
            ],
            [
                'nip' => '111202209',
                'name' => 'Himmatunnisak Mahmudah, M.Gz',
                'email' => 'himmatunnisak.mahmudah@ush.ac.id',
                'password' => Hash::make('111202209'),
                'no_hp' => '081234567896',
                'alamat' => 'Jl. Dosen No. 7, Sukoharjo'
            ]
        ];

        foreach ($dosenPembimbing as $dosen) {
            $user = User::create([
                'name' => $dosen['name'],
                'password' => $dosen['password'],
                'email_verified_at' => now(),
                'nip' => $dosen['nip'],
                'no_hp' => $dosen['no_hp'],
                'alamat' => $dosen['alamat'],
                'email' => $dosen['email']
            ]);
            $user->assignRole('dpl');
        }

        // Create Lokasi (Locations) with latitude/longitude for Sukoharjo area
        $lokasi = [
            [
                'nama_desa' => 'Grogol',
                'nama_kecamatan' => 'Grogol',
                'nama_kabupaten' => 'Sukoharjo',
                'nama_provinsi' => 'Jawa Tengah',
                'latitude' => -7.6833,
                'longitude' => 110.8167,
                'deskripsi' => 'Desa Grogol, Kecamatan Grogol, Sukoharjo'
            ],
            [
                'nama_desa' => 'Madegondo',
                'nama_kecamatan' => 'Grogol',
                'nama_kabupaten' => 'Sukoharjo',
                'nama_provinsi' => 'Jawa Tengah',
                'latitude' => -7.6917,
                'longitude' => 110.8083,
                'deskripsi' => 'Desa Madegondo, Kecamatan Grogol, Sukoharjo'
            ],
            [
                'nama_desa' => 'Kwarasan',
                'nama_kecamatan' => 'Grogol',
                'nama_kabupaten' => 'Sukoharjo',
                'nama_provinsi' => 'Jawa Tengah',
                'latitude' => -7.7000,
                'longitude' => 110.8200,
                'deskripsi' => 'Desa Kwarasan, Kecamatan Grogol, Sukoharjo'
            ],
            [
                'nama_desa' => 'Gedangan',
                'nama_kecamatan' => 'Grogol',
                'nama_kabupaten' => 'Sukoharjo',
                'nama_provinsi' => 'Jawa Tengah',
                'latitude' => -7.6750,
                'longitude' => 110.8250,
                'deskripsi' => 'Desa Gedangan, Kecamatan Grogol, Sukoharjo'
            ],
            [
                'nama_desa' => 'Langenharjo',
                'nama_kecamatan' => 'Grogol',
                'nama_kabupaten' => 'Sukoharjo',
                'nama_provinsi' => 'Jawa Tengah',
                'latitude' => -7.6850,
                'longitude' => 110.8350,
                'deskripsi' => 'Desa Langenharjo, Kecamatan Grogol, Sukoharjo'
            ],
            [
                'nama_desa' => 'Pondok',
                'nama_kecamatan' => 'Grogol',
                'nama_kabupaten' => 'Sukoharjo',
                'nama_provinsi' => 'Jawa Tengah',
                'latitude' => -7.6950,
                'longitude' => 110.8150,
                'deskripsi' => 'Desa Pondok, Kecamatan Grogol, Sukoharjo'
            ],
            [
                'nama_desa' => 'Telukan',
                'nama_kecamatan' => 'Grogol',
                'nama_kabupaten' => 'Sukoharjo',
                'nama_provinsi' => 'Jawa Tengah',
                'latitude' => -7.7050,
                'longitude' => 110.8300,
                'deskripsi' => 'Desa Telukan, Kecamatan Grogol, Sukoharjo'
            ]
        ];

        foreach ($lokasi as $loc) {
            Lokasi::updateOrCreate(
                ['nama_desa' => $loc['nama_desa']],
                [
                    'nama_kecamatan' => $loc['nama_kecamatan'],
                    'nama_kabupaten' => $loc['nama_kabupaten'],
                    'nama_provinsi' => $loc['nama_provinsi'],
                    'latitude' => $loc['latitude'],
                    'longitude' => $loc['longitude'],
                    'deskripsi' => $loc['deskripsi']
                ]
            );
        }

        // Create Angkatan (Year/Batch) for different semesters
        $angkatan2021 = \App\Models\Angkatan::firstOrCreate(
            ['nama_angkatan' => '2021'],
            [
                'tanggal_mulai' => '2021-08-01',
                'tanggal_selesai' => '2025-07-31',
                'status' => 'aktif',
                'deskripsi' => 'Angkatan 2021 (Semester 7)'
            ]
        );
        
        $angkatan2022 = \App\Models\Angkatan::firstOrCreate(
            ['nama_angkatan' => '2022'],
            [
                'tanggal_mulai' => '2022-08-01',
                'tanggal_selesai' => '2026-07-31',
                'status' => 'aktif',
                'deskripsi' => 'Angkatan 2022 (Semester 6)'
            ]
        );

        // Create Kelompok (Groups) with mapping
        $kelompok = [
            [
                'nama_kelompok' => 'Kelompok 1',
                'lokasi_id' => Lokasi::where('nama_desa', 'Grogol')->first()->id,
                'dpl_id' => User::where('nip', '111202212')->first()->id,
                'angkatan_id' => $angkatan2021->id
            ],
            [
                'nama_kelompok' => 'Kelompok 2',
                'lokasi_id' => Lokasi::where('nama_desa', 'Madegondo')->first()->id,
                'dpl_id' => User::where('nip', '102202545')->first()->id,
                'angkatan_id' => $angkatan2021->id
            ],
            [
                'nama_kelompok' => 'Kelompok 3',
                'lokasi_id' => Lokasi::where('nama_desa', 'Kwarasan')->first()->id,
                'dpl_id' => User::where('nip', '110202442')->first()->id,
                'angkatan_id' => $angkatan2021->id
            ],
            [
                'nama_kelompok' => 'Kelompok 4',
                'lokasi_id' => Lokasi::where('nama_desa', 'Gedangan')->first()->id,
                'dpl_id' => User::where('nip', '103202546')->first()->id,
                'angkatan_id' => $angkatan2021->id
            ],
            [
                'nama_kelompok' => 'Kelompok 5',
                'lokasi_id' => Lokasi::where('nama_desa', 'Langenharjo')->first()->id,
                'dpl_id' => User::where('nip', '106202547')->first()->id,
                'angkatan_id' => $angkatan2021->id
            ],
            [
                'nama_kelompok' => 'Kelompok 6',
                'lokasi_id' => Lokasi::where('nama_desa', 'Pondok')->first()->id,
                'dpl_id' => User::where('nip', '102202544')->first()->id,
                'angkatan_id' => $angkatan2021->id
            ],
            [
                'nama_kelompok' => 'Kelompok 7',
                'lokasi_id' => Lokasi::where('nama_desa', 'Telukan')->first()->id,
                'dpl_id' => User::where('nip', '111202209')->first()->id,
                'angkatan_id' => $angkatan2021->id
            ]
        ];

        foreach ($kelompok as $kel) {
            Kelompok::updateOrCreate(
                ['nama_kelompok' => $kel['nama_kelompok']],
                [
                    'lokasi_id' => $kel['lokasi_id'],
                    'dpl_id' => $kel['dpl_id'],
                    'angkatan_id' => $kel['angkatan_id']
                ]
            );
        }

        // Create Students with NIM and program mapping
        $students = [
            // BISNIS DIGITAL Students
            ['nim' => '062202009', 'name' => 'ARIELA PUTRI ANDINI', 'jurusan' => 'bisnis digital'],
            ['nim' => '062202008', 'name' => 'ALBERTA SILVIA PAMAELA', 'jurusan' => 'bisnis digital'],
            ['nim' => '062202010', 'name' => 'PRESCILA BELLA GUNAWAN', 'jurusan' => 'bisnis digital'],
            ['nim' => '062202012', 'name' => 'RAFLI YUNAN SURYA TAMA', 'jurusan' => 'bisnis digital'],
            ['nim' => '062202020', 'name' => 'QO\'QO\'', 'jurusan' => 'bisnis digital'],
            ['nim' => '062202018', 'name' => 'JANSEN WANGTANADI', 'jurusan' => 'bisnis digital'],
            ['nim' => '062202011', 'name' => 'FIRDA INTAN SARTIKA', 'jurusan' => 'bisnis digital'],
            ['nim' => '062202005', 'name' => 'ELISABETH YOHANITA DILLA PUSPITA', 'jurusan' => 'bisnis digital'],
            ['nim' => '062202014', 'name' => 'DHAVINA YOGA PUTRI', 'jurusan' => 'bisnis digital'],
            ['nim' => '062202045', 'name' => 'DANIEL DIDIEK SUTIKNO', 'jurusan' => 'bisnis digital'],
            ['nim' => '062202016', 'name' => 'MATTHEW ADRIAN HAMANTO', 'jurusan' => 'bisnis digital'],
            ['nim' => '062202047', 'name' => 'YULI SETYONINGSIH', 'jurusan' => 'bisnis digital'],
            ['nim' => '062202063', 'name' => 'ENI LIDIAWATI', 'jurusan' => 'bisnis digital'],
            ['nim' => '062202013', 'name' => 'GHINA SALSABILA SANTOSO', 'jurusan' => 'bisnis digital'],
            ['nim' => '062202015', 'name' => 'MOCH. NAZILA RIFAN FALAHI', 'jurusan' => 'bisnis digital'],
            ['nim' => '062202002', 'name' => 'CRISTIN ERVIA YUNAERY', 'jurusan' => 'bisnis digital'],
            ['nim' => '062202038', 'name' => 'MEGA CRISTY CAHYANI', 'jurusan' => 'bisnis digital'],
            ['nim' => '062202004', 'name' => 'FELITA MAHARANI HARTONO', 'jurusan' => 'bisnis digital'],
            ['nim' => '062202003', 'name' => 'SAID ROHMADHONI', 'jurusan' => 'bisnis digital'],
            ['nim' => '062202034', 'name' => 'PRIYANTO', 'jurusan' => 'bisnis digital'],
            ['nim' => '062202028', 'name' => 'SARIYANTO', 'jurusan' => 'bisnis digital'],
            ['nim' => '062202049', 'name' => 'BUDI UTOMO', 'jurusan' => 'bisnis digital'],
            ['nim' => '062202073', 'name' => 'ANUGRA ZIDAN ALFARITSQI', 'jurusan' => 'bisnis digital'],
            ['nim' => '062202006', 'name' => 'FATHAN BAGAS WICAKSONO', 'jurusan' => 'bisnis digital'],
            ['nim' => '062202035', 'name' => 'JENDRA MATAHARI', 'jurusan' => 'bisnis digital'],
            ['nim' => '062202022', 'name' => 'GRADIAN ARI ATOYA', 'jurusan' => 'bisnis digital'],
            ['nim' => '062102006', 'name' => 'RIZKI FAJAR KURNIAWAN', 'jurusan' => 'bisnis digital'],
            ['nim' => '062102008', 'name' => 'RESTY YOLANDA EKA PRASETIANI', 'jurusan' => 'bisnis digital'],
            ['nim' => '062102001', 'name' => 'LARASWATI ANGGRAINI', 'jurusan' => 'bisnis digital'],
            ['nim' => '062102007', 'name' => 'MUHAMMAD ALDO BRYAN RAMADAN', 'jurusan' => 'bisnis digital'],
            ['nim' => '062102004', 'name' => 'GLADIOLA BUDI ASMARA', 'jurusan' => 'bisnis digital'],
            // Tambahan mahasiswa yang belum ada
            ['nim' => '062202007', 'name' => 'LUSINTA EKA SAPUTRI', 'jurusan' => 'bisnis digital'],
            ['nim' => '062202021', 'name' => 'APRILDO TRI ANGGA PUTRA', 'jurusan' => 'bisnis digital'],
            ['nim' => '062202023', 'name' => 'NANDA KIRANA LESTARI', 'jurusan' => 'bisnis digital'],

            // GIZI Students
            ['nim' => '062203031', 'name' => 'ADRIEL ELSAFAN KOTTA', 'jurusan' => 'gizi'],
            ['nim' => '062203008', 'name' => 'VIORESTA STEFFIANDRY DUSI', 'jurusan' => 'gizi'],
            ['nim' => '062203007', 'name' => 'APRILIA NUR WIJAYANTI', 'jurusan' => 'gizi'],
            ['nim' => '062203020', 'name' => 'TIARA SYIFA LAKSMI', 'jurusan' => 'gizi'],
            ['nim' => '062203010', 'name' => 'ISTIQOMAH KHAIRUNNISA', 'jurusan' => 'gizi'],
            ['nim' => '062203012', 'name' => 'GRACE OLIVIA EVANGELINA WATTIMURY', 'jurusan' => 'gizi'],
            ['nim' => '062203011', 'name' => 'ZHAIROH HILWA AZIZAH', 'jurusan' => 'gizi'],
            ['nim' => '062203013', 'name' => 'NUGRAHANING WIDI RAHMANDA PANGESTI', 'jurusan' => 'gizi'],
            ['nim' => '062203014', 'name' => 'ISYFA\'SYIFA UZZAHRO', 'jurusan' => 'gizi'],
            ['nim' => '062203002', 'name' => 'LEFIYANA', 'jurusan' => 'gizi'],
            ['nim' => '062203004', 'name' => 'MUHAMMAD ROSYID RIDHO', 'jurusan' => 'gizi'],
            ['nim' => '062203029', 'name' => 'NANDINE HANIFA FATIHAH', 'jurusan' => 'gizi'],
            ['nim' => '062203016', 'name' => 'MAESTI PUTRI KRESTEVI RIANI', 'jurusan' => 'gizi'],
            ['nim' => '062203021', 'name' => 'DHEA KRISTINA NINGRUM', 'jurusan' => 'gizi'],
            ['nim' => '062203017', 'name' => 'BERLIANA NUR HAFIDHOH', 'jurusan' => 'gizi'],
            ['nim' => '062203041', 'name' => 'ENGGITA DESTA MAHARANI', 'jurusan' => 'gizi'],
            ['nim' => '062203018', 'name' => 'BERLIAN FEBY AMTARI', 'jurusan' => 'gizi'],
            ['nim' => '062203005', 'name' => 'NADIA CANTIKA ANGGRIADIPTA', 'jurusan' => 'gizi'],
            ['nim' => '062203030', 'name' => 'CINDIA ISKAR AYU PRAMESTI', 'jurusan' => 'gizi'],
            ['nim' => '062203028', 'name' => 'NABILAH ASY SYIFA', 'jurusan' => 'gizi'],
            ['nim' => '062203027', 'name' => 'INESA JENITA PUTRI', 'jurusan' => 'gizi'],
            ['nim' => '062203019', 'name' => 'JESSICA SIH DEBORAH REY', 'jurusan' => 'gizi'],
            ['nim' => '062203022', 'name' => 'FAIZAH REZA HAPSARI', 'jurusan' => 'gizi'],
            ['nim' => '062103005', 'name' => 'AGUS BAGASKORO', 'jurusan' => 'gizi'],
            ['nim' => '062103010', 'name' => 'AUSTRIN CANDRAWATY', 'jurusan' => 'gizi'],
            ['nim' => '062103006', 'name' => 'SALMA FAHIRA FATHIN', 'jurusan' => 'gizi'],
            ['nim' => '062103007', 'name' => 'FRISKA OKTAVIANI', 'jurusan' => 'gizi'],
            ['nim' => '062103001', 'name' => 'ANISA TRI ASTUTI', 'jurusan' => 'gizi'],
            ['nim' => '062103008', 'name' => 'ANTONIUS KRISTIAN', 'jurusan' => 'gizi'],
            ['nim' => '062103003', 'name' => 'BELLA PUTRI NURADILLA', 'jurusan' => 'gizi'],

            // INFORMATIKA Students
            ['nim' => '062201010', 'name' => 'NADIRA PUTRI SALSABILA', 'jurusan' => 'ilmu komputer'],
            ['nim' => '062201006', 'name' => 'ADILIYA NANDA', 'jurusan' => 'ilmu komputer'],
            ['nim' => '062201011', 'name' => 'YEHEZKIEL DEVARA AGWILANATA', 'jurusan' => 'ilmu komputer'],
            ['nim' => '062201012', 'name' => 'TALITHA ANITA ZAHRA', 'jurusan' => 'ilmu komputer'],
            ['nim' => '062201008', 'name' => 'HESTI NUR FAJAR HAYATI', 'jurusan' => 'ilmu komputer'],
            ['nim' => '062201005', 'name' => 'YOEL EKO CHRISTIAN', 'jurusan' => 'ilmu komputer'],
            ['nim' => '062201019', 'name' => 'JOYA NATHANAEL', 'jurusan' => 'ilmu komputer'],
            ['nim' => '062201003', 'name' => 'MIRANDA IKA VANIA', 'jurusan' => 'ilmu komputer'],
            ['nim' => '062201017', 'name' => 'ARIS KURNIAWAN', 'jurusan' => 'ilmu komputer'],
            ['nim' => '062201027', 'name' => 'NAFISAH RIZQI KHAIRRIYAH', 'jurusan' => 'ilmu komputer'],
            ['nim' => '062201028', 'name' => 'UMI LATHIFAH', 'jurusan' => 'ilmu komputer'],
            ['nim' => '062201009', 'name' => 'AL FAJRI RIDHO FARHIDAYAT', 'jurusan' => 'ilmu komputer'],
            ['nim' => '062201015', 'name' => 'MAULANA ILHAM ALISYAHBANA', 'jurusan' => 'ilmu komputer'],
            ['nim' => '062201024', 'name' => 'FURQON GEORGE BRILLIANT CHOES HERMAWAN', 'jurusan' => 'ilmu komputer'],
            ['nim' => '062201020', 'name' => 'YONATHAN TANJUNG', 'jurusan' => 'ilmu komputer'],
            ['nim' => '062201031', 'name' => 'AISYAH NURIKA PRATIWI', 'jurusan' => 'ilmu komputer'],
            ['nim' => '062201002', 'name' => 'WIBOWO JAMAL NUGROHO', 'jurusan' => 'ilmu komputer'],
            ['nim' => '062201014', 'name' => 'ADITYA BAGUS PERDANA PUTRA', 'jurusan' => 'ilmu komputer'],
            ['nim' => '062201034', 'name' => 'YOSEP PAUZI', 'jurusan' => 'ilmu komputer'],
            ['nim' => '062201007', 'name' => 'MUHAMMAD AFIIF\'UDDIN', 'jurusan' => 'ilmu komputer'],
            ['nim' => '062201021', 'name' => 'JONATHAN HERJUNA PUTRA RIHAN', 'jurusan' => 'ilmu komputer'],
            ['nim' => '062201004', 'name' => 'TEGAR ARYA RAMADANI', 'jurusan' => 'ilmu komputer'],
            ['nim' => '062101001', 'name' => 'OSVALDO FERNANDY WIJAYA', 'jurusan' => 'ilmu komputer'],
            ['nim' => '062101005', 'name' => 'BILAL SETYA PAMBUDI', 'jurusan' => 'ilmu komputer'],
            ['nim' => '062101002', 'name' => 'AHMAD DAVID WAHYUDI', 'jurusan' => 'ilmu komputer'],
            ['nim' => '062101004', 'name' => 'WIDYA KARISMA ARI RAHMAWATI', 'jurusan' => 'ilmu komputer'],
            ['nim' => '062101008', 'name' => 'MUKRAMIN ADIMAS SAHID', 'jurusan' => 'ilmu komputer'],
            ['nim' => '062101012', 'name' => 'FIRMAN WIDODO', 'jurusan' => 'ilmu komputer'],
            // Tambahan mahasiswa yang belum ada
            ['nim' => '062201016', 'name' => 'DHANI HERMANSYAH', 'jurusan' => 'ilmu komputer'],
        ];

        // Create students and assign them to groups based on the mapping from images
        foreach ($students as $student) {
            // Determine angkatan based on NIM pattern
            // Students in the image (semester 7) have specific NIMs
            $semester7NIMs = [
                '062101001', '062101005', '062101002', '062101004', '062101008', '062101012', // Ilmu Komputer semester 7
                '062103005', '062103010', '062103006', '062103007', '062103001', '062103008', '062103003', // Gizi semester 7
                '062102006', '062102008', '062102001', '062102007', '062102004' // Bisnis Digital semester 7
            ];
            
            $angkatanId = in_array($student['nim'], $semester7NIMs) ? $angkatan2021->id : $angkatan2022->id;
            
            $user = User::create([
                'name' => $student['name'],
                'email' => strtolower(str_replace(' ', '.', $student['name'])) . '@student.ush.ac.id',
                'password' => Hash::make($student['nim']), // Password same as NIM
                'email_verified_at' => now(),
                'nim' => $student['nim'],
                'jurusan' => $student['jurusan'],
                'no_hp' => '08' . substr($student['nim'], -9), // Generate phone number from NIM
                'alamat' => 'Jl. Mahasiswa No. ' . substr($student['nim'], -3) . ', Sukoharjo',
                'angkatan_id' => $angkatanId
            ]);
            $user->assignRole('mahasiswa');
        }

        // Assign students to groups based on the correct mapping provided by user
        $groupAssignments = [
            // ===== KELOMPOK 1 - DESA GROGOL (12 mahasiswa) =====
            '062103008' => 1, // ANTONIUS KRISTIAN (S1-Gizi)
            '062203010' => 1, // ISTIQOMAH KHAIRUNNISA (S1-Gizi)
            '062203029' => 1, // NANDINE HANIFA FATIHAH (S1-Gizi)
            '062201019' => 1, // JOYA NATHANAEL (S1-Ilmu Komputer)
            '062202011' => 1, // FIRDA INTAN SARTIKA (S1-Bisnis Digital)
            '062202073' => 1, // ANUGRA ZIDAN ALFARITSQI (S1-Bisnis Digital)
            '062103010' => 1, // AUSTRIN CANDRAWATY (S1-Gizi)
            '062203021' => 1, // DHEA KRISTINA NINGRUM (S1-Gizi)
            '062101001' => 1, // OSVALDO FERNANDI WIJAYA (S1-Ilmu Komputer)
            '062201003' => 1, // MIRANDA IKA VANIA (S1-Ilmu Komputer)
            '062202010' => 1, // PRESCILA BELLA GUNAWAN (S1-Bisnis Digital)
            '062102001' => 1, // LARASWATI ANGGRAINI (S1-Bisnis Digital)
            
            // ===== KELOMPOK 2 - DESA MADEGONDO (14 mahasiswa) =====
            '062103003' => 2, // BELLA PUTRI NURADILLA (S1-Gizi)
            '062203019' => 2, // JESSICA SIH DEBORAH REY (S1-Gizi)
            '062202018' => 2, // JANSEN WANGTANADI (S1-Bisnis Digital)
            '062201007' => 2, // MUHAMMAD AFIIF'UDDIN (S1-Ilmu Komputer)
            '062201031' => 2, // AISYAH NURIKA PRATIWI (S1-Ilmu Komputer)
            '062202035' => 2, // JENDRA MATAHARI (S1-Bisnis Digital)
            '062202022' => 2, // GRADIAN ARI ATOYA (S1-Bisnis Digital)
            '062202045' => 2, // DANIEL DIDIEK SUTIKNO (S1-Bisnis Digital)
            '062103001' => 2, // ANISA TRI ASTUTI (S1-Gizi)
            '062203018' => 2, // BERLIAN FEBY AMTARI (S1-Gizi)
            '062203017' => 2, // BERLIANA NUR HAFIDHOH (S1-Gizi)
            '062201014' => 2, // ADITYA BAGUS PERDANA PUTRA (S1-Ilmu Komputer)
            '062201002' => 2, // WIBOWO JAMAL NUGROHO (S1-Ilmu Komputer)
            '062202008' => 2, // ALBERTA SILVIA PAMAELA (S1-Bisnis Digital)
            
            // ===== KELOMPOK 3 - DESA KWARASAN (12 mahasiswa) =====
            '062103005' => 3, // AGUS BAGASKORO (S1-Gizi)
            '062203008' => 3, // VIORESTA STEFFIANDRY DUSI (S1-Gizi)
            '062201010' => 3, // NADIRA PUTRI SALSABILA (S1-Ilmu Komputer)
            '062201028' => 3, // UMI LATHIFAH (S1-Ilmu Komputer)
            '062202020' => 3, // QO'QO' (S1-Bisnis Digital)
            '062103006' => 3, // SALMA FAHIRA FATHIN (S1-Gizi)
            '062203020' => 3, // TIARA SYIFA LAKSMI (S1-Gizi)
            '062202049' => 3, // BUDI UTOMO (S1-Bisnis Digital)
            '062201016' => 3, // DHANI HERMANSYAH (S1-Ilmu Komputer)
            '062201008' => 3, // HESTI NUR FAJAR HAYATI (S1-Ilmu Komputer)
            '062202002' => 3, // CRISTIN ERVIA YUNAERY (S1-Bisnis Digital)
            '062202006' => 3, // FATHAN BAGAS WICAKSONO (S1-Bisnis Digital)
            
            // ===== KELOMPOK 4 - DESA GEDANGAN (13 mahasiswa) =====
            '062103007' => 4, // FRISKA OKTAVIANI (S1-Gizi)
            '062203022' => 4, // FAIZAH REZA HAPSARI (S1-Gizi)
            '062201011' => 4, // YEHEZKIEL DEVARA AGWILANATA (S1-Ilmu Komputer)
            '062201004' => 4, // TEGAR ARYA RAMADANI (S1-Ilmu Komputer)
            '062202009' => 4, // ARIELA PUTRI ANDINI (S1-Bisnis Digital)
            '062203013' => 4, // NUGRAHANING WIDI RAHMANDA PANGESTI (S1-Gizi)
            '062203012' => 4, // GRACE OLIVIA EVANGELINA WATTIMURY (S1-Gizi)
            '062201012' => 4, // TALITHA ANITA ZAHRA (S1-Ilmu Komputer)
            '062201005' => 4, // YOEL EKO CHRISTIAN (S1-Ilmu Komputer)
            '062202012' => 4, // RAFLI YUNAN SURYA TAMA (S1-Bisnis Digital)
            '062202063' => 4, // ENI LIDIAWATI (S1-Bisnis Digital)
            '062202015' => 4, // MOCH NAZILA RIFAN FALAHI (S1-Bisnis Digital)
            '062202047' => 4, // YULI SETYONINGSIH (S1-Bisnis Digital)
            
            // ===== KELOMPOK 5 - DESA LANGENHARJO (13 mahasiswa) =====
            '062203031' => 5, // ADRIEL ELSAFAN KOTTA (S1-Gizi)
            '062201017' => 5, // ARIS KURNIAWAN (S1-Ilmu Komputer)
            '062201020' => 5, // YONATHAN TANJUNG (S1-Ilmu Komputer)
            '062201024' => 5, // FURQON GEORGE BRILLIANT CHOES HERMAWAN (S1-Ilmu Komputer)
            '062202005' => 5, // ELISABETH YOHANITA DILLA PUSPITA (S1-Bisnis Digital)
            '062202007' => 5, // LUSINTA EKA SAPUTRI (S1-Bisnis Digital)
            '062203007' => 5, // APRILIA NUR WIJAYANTI (S1-Gizi)
            '062203030' => 5, // CINDIA ISKAR AYU PRAMESTI (S1-Gizi)
            '062203011' => 5, // ZHAIROH HILWA AZIZAH (S1-Gizi)
            '062101005' => 5, // BILAL SETYA PAMBUDI (S1-Ilmu Komputer)
            '062201021' => 5, // JONATHAN HERJUNA PUTRA RIHAN (S1-Ilmu Komputer)
            '062101012' => 5, // FIRMAN WIDODO (S1-Ilmu Komputer)
            '062202016' => 5, // MATTHEW ADRIAN HAMANTO (S1-Bisnis Digital)
            '062202023' => 5, // NANDA KIRANA LESTARI (S1-Bisnis Digital)
            
            // ===== KELOMPOK 6 - DESA PONDOK (13 mahasiswa) =====
            '062203005' => 6, // NADIA CANTIKA ANGGRIADIPTA (S1-Gizi)
            '062203027' => 6, // INESA JENITA PUTRI (S1-Gizi)
            '062201015' => 6, // MAULANA ILHAM ALISYAHBANA (S1-Ilmu Komputer)
            '062101008' => 6, // MUKRAMIN ADIMAS SAHID (S1-Ilmu Komputer)
            '062202038' => 6, // MEGA CRISTY CAHYANI (S1-Bisnis Digital)
            '062202004' => 6, // FELITA MAHARANI HARTONO (S1-Bisnis Digital)
            '062102004' => 6, // GLADIOLA BUDI ASMARA (S1-Bisnis Digital)
            '062203002' => 6, // LEFIYANA (S1-Gizi)
            '062203004' => 6, // MUHAMMAD ROSYID RIDHO (S1-Gizi)
            '062201027' => 6, // NAFISAH RIZQI KHAIRRIYAH (S1-Ilmu Komputer)
            '062201034' => 6, // YOSEP PAUZI (S1-Ilmu Komputer)
            '062102006' => 6, // RIZKI FAJAR KURNIAWAN (S1-Bisnis Digital)
            '062102007' => 6, // MUHAMMAD ALDO BRYAN RAMADAN (S1-Bisnis Digital)
            
            // ===== KELOMPOK 7 - DESA TELUKAN (12 mahasiswa) =====
            '062102008' => 7, // RESTY YOLANDA EKA PRASETIANI (S1-Bisnis Digital)
            '062203014' => 7, // ISYFA'SYIFA UZZAHRO (S1-Gizi)
            '062203041' => 7, // ENGGITA DESTA MAHARANI (S1-Gizi)
            '062201009' => 7, // AL FAJRI RIDHO FARHIDAYAT (S1-Ilmu Komputer)
            '062101004' => 7, // WIDYA KARISMA ARI RAHMAWATI (S1-Ilmu Komputer)
            '062202014' => 7, // DHAVINA YOGA PUTRI (S1-Bisnis Digital)
            '062203016' => 7, // MAESTI PUTRI KRESTEVI RIANI (S1-Gizi)
            '062203028' => 7, // NABILAH ASY SYIFA (S1-Gizi)
            '062101002' => 7, // AHMAD DAVID WAHYUDI (S1-Ilmu Komputer)
            '062201006' => 7, // ADILIYA NANDA (S1-Ilmu Komputer)
            '062202021' => 7, // APRILDO TRI ANGGA PUTRA (S1-Bisnis Digital)
        ];

        foreach ($groupAssignments as $nim => $groupNumber) {
            $user = User::where('nim', $nim)->first();
            $kelompok = Kelompok::where('nama_kelompok', 'Kelompok ' . $groupNumber)->first();
            if ($user && $kelompok) {
                $user->update(['kelompok_id' => $kelompok->id]);
            }
        }
    }
} 
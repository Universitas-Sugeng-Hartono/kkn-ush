# USH Logbook - Sistem Logbook KKN Tematik

Sistem logbook untuk Kuliah Kerja Nyata (KKN) Tematik yang memungkinkan mahasiswa mencatat aktivitas harian dan dosen pembimbing lapangan (DPL) memberikan penilaian.

## Fitur Utama

### Untuk Mahasiswa
- Mencatat logbook harian dengan foto
- Melihat absensi dan lokasi
- Mengunggah dokumen dan galeri
- Melihat penilaian dari DPL
- Login menggunakan email atau NIM

### Untuk Dosen Pembimbing Lapangan (DPL)
- Memantau aktivitas mahasiswa bimbingan
- Memberikan penilaian sesuai bobot KKN Tematik
- Melihat peta lokasi mahasiswa
- Mengelola kelompok mahasiswa
- Login menggunakan email atau NIP

### Untuk Admin
- Mengelola user dan kelompok
- Mengatur lokasi KKN
- Mengelola berita dan dokumen
- Monitoring keseluruhan sistem

## Fitur Nomor Induk (NIM/NIP) dan Jurusan

Sistem telah diintegrasikan dengan Nomor Induk dan informasi jurusan untuk identifikasi yang lebih baik:

### Login Multi-Identifier
- **Mahasiswa**: Bisa login menggunakan email atau NIM
- **Dosen**: Bisa login menggunakan email atau NIP
- **Admin**: Login menggunakan email

### Jurusan Mahasiswa
Sistem mendukung 3 jurusan untuk mahasiswa:
- **Informatika**: Program studi teknologi informasi
- **Bisnis Digital**: Program studi bisnis digital
- **Gizi**: Program studi gizi

### Tampilan NIM/NIP dan Jurusan di Semua Fitur
- **Dashboard**: Menampilkan NIM/NIP user yang sedang login
- **Profil**: Menampilkan dan mengelola informasi NIM/NIP dan jurusan
- **Navigation**: Dropdown user menampilkan NIM/NIP
- **Daftar Mahasiswa**: Tabel menampilkan kolom NIM dan jurusan
- **Penilaian**: Semua view penilaian menampilkan NIM mahasiswa dan jurusan
- **Berita & Dokumen**: Menampilkan NIM/NIP pembuat konten
- **Logbook**: Menampilkan NIM mahasiswa di dashboard DPL
- **Manajemen User**: Form create/edit dengan field jurusan untuk mahasiswa

### Validasi NIM/NIP dan Jurusan
- NIM wajib untuk mahasiswa (format: 8-10 digit)
- NIP wajib untuk dosen (format: 18 digit sesuai standar PNS)
- Jurusan wajib dipilih untuk mahasiswa (informatika, bisnis digital, gizi)
- Validasi unik untuk mencegah duplikasi NIM/NIP
- NIM/NIP tidak dapat diubah setelah dibuat (readonly di profil)
- Jurusan dapat diubah oleh mahasiswa di halaman profil

## Sistem Penilaian KKN Tematik

Sistem penilaian telah disesuaikan dengan bobot penilaian KKN Tematik yang terdiri dari:

### 1. Tahap Pembekalan (10%)
- **Kehadiran Selama Pembekalan (5%)**: Evaluasi kehadiran mahasiswa selama tahap pembekalan
- **Sikap (5%)**: Penilaian etika, kesopanan, kesantunan, kerapian, dan kedisiplinan

### 2. Pelaksanaan (60%)
- **Kehadiran di Lokasi KKN (5%)**: Evaluasi kehadiran di lokasi KKN
- **Sikap (5%)**: Penilaian etika, kesopanan, kesantunan, kerapian, dan kedisiplinan selama pelaksanaan
- **Keterlibatan dalam Kegiatan Kemasyarakatan (15%)**: Evaluasi partisipasi dalam kegiatan masyarakat
- **Relevansi Program Kerja (15%)**: Penilaian kesesuaian program dengan kondisi masyarakat dan tema KKN
- **Keberhasilan Program Kerja (20%)**: Evaluasi keberhasilan program dan produk teknologi tepat guna

### 3. Laporan KKN Tematik (30%)
- **Sistematika Penyusunan Laporan (3%)**: Penilaian struktur dan sistematika laporan
- **Konten Media Sosial (7%)**: Evaluasi konten yang dipublikasikan di media sosial
- **Penggunaan Bahasa (2%)**: Penilaian penggunaan bahasa yang baik dan benar
- **Ketepatan Analisis (3%)**: Evaluasi ketepatan analisis dan pembahasan
- **Ketepatan Waktu Pengumpulan (5%)**: Penilaian ketepatan waktu pengumpulan laporan
- **Produk Teknologi Tepat Guna (10%)**: Evaluasi produk teknologi atau kewirausahaan yang dihasilkan

## Konversi Nilai

Sistem menggunakan konversi nilai sebagai berikut:
- **A (85-100)**: Sangat Baik Sekali
- **A- (80-84.99)**: Sangat Baik
- **B+ (75-79.99)**: Cukup Baik
- **B (65-74.99)**: Baik
- **C (60-64.99)**: Cukup
- **D (45-59.99)**: Kurang
- **E (0-44.99)**: Sangat Kurang

## Akun Default

### Admin
- Email: `admin@ush.ac.id`
- Password: `password`
- NIP: `198501012010011001`

### Dosen Pembimbing
- Email: `dpl@ush.ac.id` atau NIP: `198203152010011002`
- Password: `password`

### Mahasiswa
- Email: `mahasiswa@ush.ac.id` atau NIM: `20240001` (Jurusan: Informatika)
- Email: `ahmad.fauzi@student.ush.ac.id` atau NIM: `20240002` (Jurusan: Bisnis Digital)
- Email: `siti.nurhaliza@student.ush.ac.id` atau NIM: `20240003` (Jurusan: Gizi)
- Password: `password`

## Instalasi

1. Clone repository ini
2. Install dependencies: `composer install`
3. Copy file `.env.example` ke `.env` dan sesuaikan konfigurasi database
4. Generate key aplikasi: `php artisan key:generate`
5. Jalankan migration: `php artisan migrate`
6. Jalankan seeder: `php artisan db:seed`
7. Jalankan aplikasi: `php artisan serve`

## Teknologi yang Digunakan

- **Backend**: Laravel 10
- **Frontend**: Blade Templates, Bootstrap 5, Tailwind CSS
- **Database**: MySQL/PostgreSQL
- **Authentication**: Laravel Breeze
- **Authorization**: Spatie Laravel Permission

## Struktur Database

### Tabel Utama
- `users`: Data pengguna (admin, DPL, mahasiswa) dengan NIM/NIP dan jurusan
- `kelompok`: Data kelompok KKN
- `logbook`: Data logbook harian mahasiswa
- `nilai`: Data penilaian mahasiswa oleh DPL
- `absensi`: Data absensi mahasiswa
- `lokasi`: Data lokasi KKN

### Field Jurusan
Field `jurusan` ditambahkan ke tabel `users` dengan tipe ENUM:
- `informatika`: Program studi teknologi informasi
- `bisnis digital`: Program studi bisnis digital  
- `gizi`: Program studi gizi

## Kontribusi

Silakan berkontribusi dengan membuat pull request atau melaporkan issue.

## Lisensi

Proyek ini dilisensikan di bawah MIT License.

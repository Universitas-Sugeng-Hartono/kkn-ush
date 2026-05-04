<?php

namespace App\Imports;

use App\Models\Semester;
use App\Models\TahunAkademik;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class MahasiswaImport implements ToCollection, WithHeadingRow
{
    public int $successCount = 0;
    public int $skipCount = 0;
    public array $errors = [];

    public function collection(Collection $rows)
    {
        $tahunAktif    = TahunAkademik::getAktif();
        $semesterAktif = Semester::getAktif();

        foreach ($rows as $index => $row) {
            $rowNumber = $index + 2; // +2 karena baris 1 = heading

            // Skip baris kosong
            $nimRaw  = trim((string) ($row['nim'] ?? ''));
            $namaRaw = trim((string) ($row['nama'] ?? ''));
            if (empty($nimRaw) && empty($namaRaw)) {
                continue;
            }

            $role = strtolower(trim((string) ($row['role'] ?? 'mahasiswa')));

            $data = [
                'nim'     => $nimRaw,
                'nama'    => $namaRaw,
                'email'   => trim((string) ($row['email'] ?? '')),
                'role'    => $role,
                'jurusan' => strtolower(trim((string) ($row['jurusan'] ?? ''))),
                'no_hp'   => trim((string) ($row['no_hp'] ?? '')),
                'alamat'  => trim((string) ($row['alamat'] ?? '')),
            ];

            // Validasi
            $validator = Validator::make($data, [
                'nim'     => 'required|string|max:20',
                'nama'    => 'required|string|max:255',
                'email'   => 'required|email|max:255',
                'role'    => 'required|in:mahasiswa,dpl,admin',
                'jurusan' => 'nullable|in:informatika,bisnis digital,gizi',
                'no_hp'   => 'nullable|string|max:20',
                'alamat'  => 'nullable|string',
            ], [
                'role.required' => 'Kolom role wajib diisi.',
                'role.in'       => 'Role tidak valid. Nilai yang diizinkan: mahasiswa, dpl, admin.',
                'jurusan.in'    => 'Jurusan tidak valid. Nilai yang diizinkan: informatika, bisnis digital, gizi.',
            ]);

            if ($validator->fails()) {
                $this->errors[] = "Baris {$rowNumber} (NIM: {$data['nim']}): " . implode(', ', $validator->errors()->all());
                continue;
            }

            // Cek duplikat NIM
            if (User::where('nim', $data['nim'])->exists()) {
                $this->errors[] = "Baris {$rowNumber}: NIM {$data['nim']} sudah terdaftar, dilewati.";
                $this->skipCount++;
                continue;
            }

            // Cek duplikat email
            if (User::where('email', $data['email'])->exists()) {
                $this->errors[] = "Baris {$rowNumber}: Email {$data['email']} sudah terdaftar, dilewati.";
                $this->skipCount++;
                continue;
            }

            // Periode hanya berlaku untuk mahasiswa
            $tahunAkademikId = $data['role'] === 'mahasiswa' ? $tahunAktif?->id : null;
            $semesterId      = $data['role'] === 'mahasiswa' ? $semesterAktif?->id : null;

            // Buat user baru
            $user = User::create([
                'name'              => $data['nama'],
                'email'             => $data['email'],
                'password'          => Hash::make($data['nim']), // password default = NIM
                'nim'               => $data['nim'],
                'jurusan'           => $data['jurusan'] ?: null,
                'no_hp'             => $data['no_hp'] ?: null,
                'alamat'            => $data['alamat'] ?: null,
                'tahun_akademik_id' => $tahunAkademikId,
                'semester_id'       => $semesterId,
            ]);

            $user->assignRole($data['role']);
            $this->successCount++;
        }
    }
}

<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class MahasiswaTemplateExport implements FromArray, WithHeadings, WithColumnWidths, WithStyles
{
    public function array(): array
    {
        // Contoh data
        return [
            ['220101001', 'Budi Santoso',   'budi.santoso@email.com',   'mahasiswa', 'ilmu komputer',   '08123456789', 'Jl. Merdeka No. 1, Semarang'],
            ['220101002', 'Siti Rahayu',    'siti.rahayu@email.com',    'mahasiswa', 'bisnis digital', '08234567890', 'Jl. Sudirman No. 5, Jakarta'],
            ['220101003', 'Ahmad Fauzi',    'ahmad.fauzi@email.com',    'mahasiswa', 'gizi',           '08345678901', 'Jl. Diponegoro No. 10, Bandung'],
            ['198501012', 'Dr. Budi Utomo', 'budi.utomo@ush.ac.id',     'dpl',       '',               '08456789012', 'Jl. Kampus No. 1, Semarang'],
        ];
    }

    public function headings(): array
    {
        return [
            'nim',
            'nama',
            'email',
            'role',
            'jurusan',
            'no_hp',
            'alamat',
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 18,  // nim
            'B' => 30,  // nama
            'C' => 35,  // email
            'D' => 14,  // role
            'E' => 18,  // jurusan
            'F' => 18,  // no_hp
            'G' => 40,  // alamat
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            // Style header baris pertama
            1 => [
                'font' => [
                    'bold'  => true,
                    'color' => ['argb' => 'FFFFFFFF'],
                ],
                'fill' => [
                    'fillType'   => Fill::FILL_SOLID,
                    'startColor' => ['argb' => 'FF0D6EFD'],
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                ],
            ],
        ];
    }
}

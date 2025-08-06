<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Nilai extends Model
{
    use HasFactory;

    protected $table = 'nilai';

    protected $fillable = [
        'user_id',
        'kelompok_id',
        // Tahap Pembekalan (10%)
        'nilai_kehadiran_pembekalan',
        'nilai_sikap_pembekalan',
        // Pelaksanaan (60%)
        'nilai_kehadiran_lokasi',
        'nilai_sikap_pelaksanaan',
        'nilai_keterlibatan_kegiatan',
        'nilai_relevansi_program',
        'nilai_keberhasilan_program',
        // Laporan KKN Tematik (30%)
        'nilai_sistematika_laporan',
        'nilai_konten_medsos',
        'nilai_bahasa',
        'nilai_analisis',
        'nilai_ketepatan_waktu',
        'nilai_produk_teknologi',
        'nilai_akhir',
        'grade',
        'catatan',
        'dpl_id'
    ];

    protected $casts = [
        'nilai_kehadiran_pembekalan' => 'decimal:2',
        'nilai_sikap_pembekalan' => 'decimal:2',
        'nilai_kehadiran_lokasi' => 'decimal:2',
        'nilai_sikap_pelaksanaan' => 'decimal:2',
        'nilai_keterlibatan_kegiatan' => 'decimal:2',
        'nilai_relevansi_program' => 'decimal:2',
        'nilai_keberhasilan_program' => 'decimal:2',
        'nilai_sistematika_laporan' => 'decimal:2',
        'nilai_konten_medsos' => 'decimal:2',
        'nilai_bahasa' => 'decimal:2',
        'nilai_analisis' => 'decimal:2',
        'nilai_ketepatan_waktu' => 'decimal:2',
        'nilai_produk_teknologi' => 'decimal:2',
        'nilai_akhir' => 'decimal:2'
    ];

    public function mahasiswa(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function kelompok(): BelongsTo
    {
        return $this->belongsTo(Kelompok::class);
    }

    public function dpl(): BelongsTo
    {
        return $this->belongsTo(User::class, 'dpl_id');
    }

    public function calculateGrade(): string
    {
        return match(true) {
            $this->nilai_akhir >= 85 => 'A',
            $this->nilai_akhir >= 80 => 'A-',
            $this->nilai_akhir >= 75 => 'B+',
            $this->nilai_akhir >= 65 => 'B',
            $this->nilai_akhir >= 60 => 'C',
            $this->nilai_akhir >= 45 => 'D',
            default => 'E'
        };
    }

    public function calculateNilaiAkhir(): float
    {
        // Tahap Pembekalan (10%)
        $nilai_pembekalan = (
            ($this->nilai_kehadiran_pembekalan * 0.05) +
            ($this->nilai_sikap_pembekalan * 0.05)
        );

        // Pelaksanaan (60%)
        $nilai_pelaksanaan = (
            ($this->nilai_kehadiran_lokasi * 0.05) +
            ($this->nilai_sikap_pelaksanaan * 0.05) +
            ($this->nilai_keterlibatan_kegiatan * 0.15) +
            ($this->nilai_relevansi_program * 0.15) +
            ($this->nilai_keberhasilan_program * 0.20)
        );

        // Laporan KKN Tematik (30%)
        $nilai_laporan = (
            ($this->nilai_sistematika_laporan * 0.03) +
            ($this->nilai_konten_medsos * 0.07) +
            ($this->nilai_bahasa * 0.02) +
            ($this->nilai_analisis * 0.03) +
            ($this->nilai_ketepatan_waktu * 0.05) +
            ($this->nilai_produk_teknologi * 0.10)
        );

        return $nilai_pembekalan + $nilai_pelaksanaan + $nilai_laporan;
    }

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($nilai) {
            $nilai->nilai_akhir = $nilai->calculateNilaiAkhir();
            $nilai->grade = $nilai->calculateGrade();
        });
    }
} 
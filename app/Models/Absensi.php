<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Absensi extends Model
{
    use HasFactory;

    protected $table = 'absensi';

    protected $fillable = [
        'user_id',
        'kelompok_id',
        'tanggal',
        'waktu_masuk',
        'waktu_keluar',
        'foto_kegiatan',
        'foto_keluar',
        'latitude',
        'longitude',
        'latitude_keluar',
        'longitude_keluar',
        'status'
    ];

    protected $casts = [
        'tanggal' => 'date',
        'waktu_masuk' => 'datetime',
        'waktu_keluar' => 'datetime',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
        'latitude_keluar' => 'decimal:8',
        'longitude_keluar' => 'decimal:8'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function kelompok(): BelongsTo
    {
        return $this->belongsTo(Kelompok::class);
    }
} 
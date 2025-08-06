<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Kelompok extends Model
{
    use HasFactory;

    protected $table = 'kelompok';

    protected $fillable = [
        'nama_kelompok',
        'lokasi_id',
        'dpl_id',
        'angkatan_id',
        'deskripsi'
    ];

    public function lokasi(): BelongsTo
    {
        return $this->belongsTo(Lokasi::class);
    }

    public function dpl(): BelongsTo
    {
        return $this->belongsTo(User::class, 'dpl_id');
    }

    public function angkatan(): BelongsTo
    {
        return $this->belongsTo(Angkatan::class);
    }

    public function mahasiswa(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function logbooks(): HasManyThrough
    {
        return $this->hasManyThrough(Logbook::class, User::class, 'kelompok_id', 'user_id');
    }

    public function absensi(): HasManyThrough
    {
        return $this->hasManyThrough(Absensi::class, User::class, 'kelompok_id', 'user_id');
    }

    public function nilai(): HasManyThrough
    {
        return $this->hasManyThrough(Nilai::class, User::class);
    }

    public function getNamaAttribute()
    {
        return $this->nama_kelompok;
    }
} 
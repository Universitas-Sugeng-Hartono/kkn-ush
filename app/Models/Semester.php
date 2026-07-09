<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Semester extends Model
{
    use HasFactory;

    protected $table = 'semester';

    protected $fillable = [
        'nama',
        'is_aktif',
    ];

    protected $casts = [
        'is_aktif' => 'boolean',
    ];


    /**
     * Ambil satu record aktif pertama (backward compat).
     */
    public static function getAktif(): ?self
    {
        return self::query()->where('is_aktif', true)->first();
    }

    /**
     * Ambil semua record yang aktif (multi-aktif).
     */
    public static function getAktifList(): \Illuminate\Database\Eloquent\Collection
    {
        return self::query()->where('is_aktif', true)->get();
    }

    /**
     * Aktifkan record ini TANPA menonaktifkan yang lain.
     */
    public function setAsAktif(): void
    {
        $this->forceFill(['is_aktif' => true])->save();
    }

    public function setAsNonaktif(): void
    {
        $this->forceFill(['is_aktif' => false])->save();
    }

    public function kelompok(): HasMany
    {
        return $this->hasMany(Kelompok::class, 'semester_id');
    }

    public function mahasiswa(): HasMany
    {
        return $this->hasMany(User::class, 'semester_id');
    }
}


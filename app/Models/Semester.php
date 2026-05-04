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


    public static function getAktif(): ?self
    {
        return self::query()->where('is_aktif', true)->first();
    }

    public function setAsAktif(): void
    {
        self::query()->update(['is_aktif' => false]);
        $this->forceFill(['is_aktif' => true])->save();
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


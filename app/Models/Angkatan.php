<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Angkatan extends Model
{
    use HasFactory;

    protected $table = 'angkatan';

    protected $fillable = [
        'nama_angkatan',
        'tanggal_mulai',
        'tanggal_selesai',
        'status',
        'deskripsi'
    ];

    protected $casts = [
        'tanggal_mulai' => 'date',
        'tanggal_selesai' => 'date'
    ];

    public function kelompok(): HasMany
    {
        return $this->hasMany(Kelompok::class);
    }

    public function getTahunAttribute()
    {
        return $this->tanggal_mulai->format('Y');
    }
} 
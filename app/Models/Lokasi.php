<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lokasi extends Model
{
    use HasFactory;

    protected $table = 'lokasi';
    
    protected $fillable = [
        'nama_desa',
        'nama_kecamatan',
        'nama_kabupaten',
        'nama_provinsi',
        'latitude',
        'longitude',
        'deskripsi'
    ];

    public function kelompok()
    {
        return $this->hasMany(Kelompok::class);
    }

    public function getNamaAttribute()
    {
        return "Desa {$this->nama_desa}, Kec. {$this->nama_kecamatan}, Kab. {$this->nama_kabupaten}";
    }

    public function getAlamatAttribute()
    {
        return "{$this->nama_desa}, {$this->nama_kecamatan}, {$this->nama_kabupaten}, {$this->nama_provinsi}";
    }
} 
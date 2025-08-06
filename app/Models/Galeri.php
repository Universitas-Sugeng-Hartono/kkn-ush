<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class Galeri extends Model
{
    use HasFactory;

    protected $table = 'galeri';

    protected $fillable = [
        'judul',
        'gambar',
        'deskripsi',
        'aktif',
        'urutan',
        'user_id'
    ];

    protected $casts = [
        'aktif' => 'boolean',
        'urutan' => 'integer'
    ];

    protected $appends = ['gambar_url'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getGambarUrlAttribute(): string
    {
        if ($this->gambar && Storage::disk('public')->exists($this->gambar)) {
            return asset('storage/' . $this->gambar);
        }
        return asset('images/no-image.jpg');
    }
} 
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LaporanKelompok extends Model
{
    use HasFactory;

    protected $table = 'laporan_kelompok';

    protected $fillable = [
        'kelompok_id',
        'user_id',
        'judul',
        'keterangan',
        'file_path',
        'file_original_name',
        'file_size',
        'file_mime',
    ];

    public function kelompok(): BelongsTo
    {
        return $this->belongsTo(Kelompok::class, 'kelompok_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}


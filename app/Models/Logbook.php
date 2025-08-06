<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Logbook extends Model
{
    use HasFactory;

    protected $table = 'logbook';

    protected $fillable = [
        'user_id',
        'kelompok_id',
        'tanggal',
        'waktu_mulai',
        'waktu_selesai',
        'judul',
        'jenis',
        'keterangan',
        'lokasi',
        'status',
        'attachments'
    ];

    protected $casts = [
        'tanggal' => 'date',
        'attachments' => 'array'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function kelompok(): BelongsTo
    {
        return $this->belongsTo(Kelompok::class);
    }

    public function photos(): HasMany
    {
        return $this->hasMany(LogbookPhoto::class);
    }

    public function getStatusBadgeClassAttribute(): string
    {
        return match($this->status) {
            'draft' => 'bg-warning',
            'submitted' => 'bg-info',
            'approved' => 'bg-success',
            'rejected' => 'bg-danger',
            default => 'bg-secondary'
        };
    }
} 
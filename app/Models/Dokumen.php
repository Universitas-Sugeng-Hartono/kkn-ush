<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class Dokumen extends Model
{
    use HasFactory;

    protected $table = 'dokumen';

    protected $fillable = [
        'nama',
        'file_path',
        'jenis',
        'keterangan',
        'ukuran',
        'user_id'
    ];

    protected $appends = ['file_url', 'icon_class'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getFileUrlAttribute(): string
    {
        if ($this->file_path && Storage::disk('public')->exists($this->file_path)) {
            return asset('storage/' . $this->file_path);
        }
        return '#';
    }

    public function getIconClassAttribute(): string
    {
        $extension = pathinfo($this->file_path, PATHINFO_EXTENSION);
        
        return match(strtolower($extension)) {
            'pdf' => 'fa-file-pdf text-danger',
            'doc', 'docx' => 'fa-file-word text-primary',
            'xls', 'xlsx' => 'fa-file-excel text-success',
            default => 'fa-file text-secondary'
        };
    }

    public function getFormattedSizeAttribute(): string
    {
        $bytes = $this->ukuran;
        
        if ($bytes >= 1048576) {
            return number_format($bytes / 1048576, 2) . ' MB';
        }
        
        if ($bytes >= 1024) {
            return number_format($bytes / 1024, 2) . ' KB';
        }
        
        return $bytes . ' bytes';
    }

    public function getJenisLabelAttribute(): string
    {
        return match($this->jenis) {
            'panduan' => 'Panduan/Pedoman',
            'template' => 'Template/Form',
            'laporan' => 'Laporan',
            'lainnya' => 'Lainnya',
            default => ucfirst($this->jenis)
        };
    }

    public function getJenisBadgeClassAttribute(): string
    {
        return match($this->jenis) {
            'panduan' => 'bg-info',
            'template' => 'bg-primary',
            'laporan' => 'bg-danger',
            'lainnya' => 'bg-secondary',
            default => 'bg-secondary'
        };
    }

    public function getJenisOptions(): array
    {
        return [
            'peraturan' => 'Peraturan KKN',
            'panduan' => 'Panduan KKN',
            'template' => 'Template Dokumen',
            'logo' => 'Logo Universitas'
        ];
    }
} 
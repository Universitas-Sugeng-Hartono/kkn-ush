<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class Pengaduan extends Model
{
    use HasFactory;

    protected $table = 'pengaduan';

    protected $fillable = [
        'nomor_pengaduan',
        'nama_pelapor',
        'email_pelapor',
        'no_hp_pelapor',
        'subjek',
        'isi_pengaduan',
        'bukti_pendukung',
        'lokasi_id',
        'status',
        'tanggapan',
        'user_id'
    ];

    public static function boot()
    {
        parent::boot();

        static::creating(function ($pengaduan) {
            $pengaduan->nomor_pengaduan = static::generateNomorPengaduan();
        });
    }

    public static function generateNomorPengaduan()
    {
        $today = Carbon::now();
        $prefix = $today->format('ymd');
        
        // Ambil nomor terakhir untuk hari ini
        $lastNumber = static::where('nomor_pengaduan', 'like', $prefix . '%')
            ->orderBy('nomor_pengaduan', 'desc')
            ->first();
        
        if ($lastNumber) {
            $counter = intval(substr($lastNumber->nomor_pengaduan, -3)) + 1;
        } else {
            $counter = 1;
        }
        
        // Format counter jadi 3 digit
        $counter = str_pad($counter, 3, '0', STR_PAD_LEFT);
        
        return $prefix . $counter;
    }

    public function lokasi(): BelongsTo
    {
        return $this->belongsTo(Lokasi::class);
    }

    public function admin(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function getStatusOptions(): array
    {
        return [
            'pending' => 'Menunggu',
            'process' => 'Diproses',
            'resolved' => 'Selesai',
            'rejected' => 'Ditolak'
        ];
    }

    public function getStatusBadgeClass(): string
    {
        return match($this->status) {
            'pending' => 'bg-warning',
            'process' => 'bg-info',
            'resolved' => 'bg-success',
            'rejected' => 'bg-danger',
            default => 'bg-secondary'
        };
    }
} 
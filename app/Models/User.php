<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'nim',
        'nip',
        'no_hp',
        'alamat',
        'foto_profil',
        'kelompok_id',
        'photo',
        'jurusan',
        'tahun_akademik_id',
        'semester_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function kelompok()
    {
        return $this->belongsTo(Kelompok::class);
    }

    public function logbooks()
    {
        return $this->hasMany(Logbook::class);
    }

    public function absensi()
    {
        return $this->hasMany(Absensi::class);
    }

    public function nilai()
    {
        return $this->hasMany(Nilai::class);
    }

    public function berita()
    {
        return $this->hasMany(Berita::class);
    }

    public function dokumen()
    {
        return $this->hasMany(Dokumen::class);
    }

    public function kelompokDpl()
    {
        return $this->hasMany(Kelompok::class, 'dpl_id');
    }


    public function tahunAkademik()
    {
        return $this->belongsTo(TahunAkademik::class);
    }

    public function semester()
    {
        return $this->belongsTo(Semester::class);
    }

    public function hasKelompok(): bool
    {
        return !is_null($this->kelompok_id);
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

    public function unreadNotifications()
    {
        return $this->notifications()->where('is_read', false);
    }

    public function fcmTokens()
    {
        return $this->hasMany(FcmToken::class);
    }
}

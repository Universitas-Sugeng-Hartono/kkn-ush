<?php

namespace App\Policies;

use App\Models\Pengaduan;
use App\Models\User;

class PengaduanPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasRole('admin');
    }

    public function view(User $user, Pengaduan $pengaduan): bool
    {
        return $user->hasRole('admin');
    }

    public function process(User $user, Pengaduan $pengaduan): bool
    {
        return $user->hasRole('admin') && $pengaduan->status === 'pending';
    }

    public function delete(User $user, Pengaduan $pengaduan): bool
    {
        return $user->hasRole('admin');
    }
} 
<?php

namespace App\Policies;

use App\Models\Dokumen;
use App\Models\User;

class DokumenPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasRole('admin');
    }

    public function view(?User $user, Dokumen $dokumen): bool
    {
        if ($dokumen->is_public) {
            return true;
        }
        return $user && $user->hasRole('admin');
    }

    public function create(User $user): bool
    {
        return $user->hasRole('admin');
    }

    public function update(User $user, Dokumen $dokumen): bool
    {
        return $user->hasRole('admin');
    }

    public function delete(User $user, Dokumen $dokumen): bool
    {
        return $user->hasRole('admin');
    }

    public function download(?User $user, Dokumen $dokumen): bool
    {
        if ($dokumen->is_public) {
            return true;
        }
        return $user && $user->hasRole('admin');
    }
} 
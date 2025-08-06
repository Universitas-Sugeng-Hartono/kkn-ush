<?php

namespace App\Policies;

use App\Models\Galeri;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class GaleriPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasRole(['admin', 'dpl']);
    }

    public function view(User $user, Galeri $galeri): bool
    {
        return $user->hasRole(['admin', 'dpl']);
    }

    public function create(User $user): bool
    {
        return $user->hasRole(['admin']);
    }

    public function update(User $user, Galeri $galeri): bool
    {
        return $user->hasRole(['admin']);
    }

    public function delete(User $user, Galeri $galeri): bool
    {
        return $user->hasRole(['admin']);
    }
} 
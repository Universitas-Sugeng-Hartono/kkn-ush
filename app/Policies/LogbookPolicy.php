<?php

namespace App\Policies;

use App\Models\Logbook;
use App\Models\User;

class LogbookPolicy
{
    public function view(User $user, Logbook $logbook): bool
    {
        return $user->id === $logbook->user_id || 
               ($logbook->is_kelompok && $user->kelompok_id && $user->kelompok_id === $logbook->kelompok_id) ||
               $user->hasRole('dpl') || 
               $user->hasRole('admin');
    }

    public function create(User $user): bool
    {
        return $user->hasRole('mahasiswa');
    }

    public function update(User $user, Logbook $logbook): bool
    {
        $isAuthorized = $user->id === $logbook->user_id || 
                       ($logbook->is_kelompok && $user->kelompok_id && $user->kelompok_id === $logbook->kelompok_id);
        return $isAuthorized && $logbook->status === 'draft';
    }

    public function delete(User $user, Logbook $logbook): bool
    {
        $isAuthorized = $user->id === $logbook->user_id || 
                       ($logbook->is_kelompok && $user->kelompok_id && $user->kelompok_id === $logbook->kelompok_id);
        return $isAuthorized && $logbook->status === 'draft';
    }

    public function submit(User $user, Logbook $logbook): bool
    {
        $isAuthorized = $user->id === $logbook->user_id || 
                       ($logbook->is_kelompok && $user->kelompok_id && $user->kelompok_id === $logbook->kelompok_id);
        return $isAuthorized && $logbook->status === 'draft';
    }

    public function review(User $user, Logbook $logbook): bool
    {
        return $user->hasRole('dpl') && $logbook->status === 'submitted';
    }

    public function approve(User $user, Logbook $logbook): bool
    {
        return $user->hasRole('dpl') && $logbook->status === 'submitted';
    }

    public function reject(User $user, Logbook $logbook): bool
    {
        return $user->hasRole('dpl') && $logbook->status === 'submitted';
    }
} 
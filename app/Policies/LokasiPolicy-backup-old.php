<?php

namespace App\Policies;

use App\Models\Lokasi;
use App\Models\User;

class LokasiPolicy
{
    public function view(User $user, Lokasi $lokasi)
    {
        if ($user->isAdmin()) {
            return true;
        }

        return $lokasi->petugas()->where('user_id', $user->id)->exists();
    }

    public function update(User $user, Lokasi $lokasi)
    {
        if ($user->isAdmin()) {
            return true;
        }

        return $lokasi->petugas()
            ->where('user_id', $user->id)
            ->where('access_type', 'creator')
            ->exists();
    }

    public function delete(User $user, Lokasi $lokasi)
    {
        if ($user->isAdmin()) {
            return true;
        }

        return $lokasi->petugas()
            ->where('user_id', $user->id)
            ->where('access_type', 'creator')
            ->exists();
    }
}

<?php

namespace App\Policies;

use App\Models\Refugee;
use App\Models\User;

class RefugeePolicy
{
    public function viewAny(User $user): bool
    {
        return in_array($user->role ?? null, ['admin', 'petugas', 'supervisor'], true);
    }

    public function view(User $user, Refugee $refugee): bool
    {
        return $this->viewAny($user);
    }

    public function create(User $user): bool
    {
        return in_array($user->role ?? null, ['admin', 'petugas'], true);
    }

    public function update(User $user, Refugee $refugee): bool
    {
        return in_array($user->role ?? null, ['admin', 'petugas', 'supervisor'], true);
    }

    public function delete(User $user, Refugee $refugee): bool
    {
        return ($user->role ?? null) === 'admin';
    }
}

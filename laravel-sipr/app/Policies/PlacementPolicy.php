<?php

namespace App\Policies;

use App\Models\Placement;
use App\Models\User;

class PlacementPolicy
{
    public function viewAny(User $user): bool
    {
        return in_array($user->role ?? null, ['admin', 'petugas', 'supervisor'], true);
    }

    public function view(User $user, Placement $placement): bool
    {
        return $this->viewAny($user);
    }

    public function create(User $user): bool
    {
        return in_array($user->role ?? null, ['admin', 'petugas'], true);
    }

    public function update(User $user, Placement $placement): bool
    {
        return in_array($user->role ?? null, ['admin', 'petugas', 'supervisor'], true);
    }

    public function delete(User $user, Placement $placement): bool
    {
        return ($user->role ?? null) === 'admin';
    }
}

<?php

namespace App\Policies;

use App\Models\RefugeeDocument;
use App\Models\User;

class RefugeeDocumentPolicy
{
    public function viewAny(User $user): bool
    {
        return in_array($user->role ?? null, ['admin', 'petugas', 'supervisor'], true);
    }

    public function view(User $user, RefugeeDocument $document): bool
    {
        return $this->viewAny($user);
    }

    public function create(User $user): bool
    {
        return in_array($user->role ?? null, ['admin', 'petugas'], true);
    }

    public function update(User $user, RefugeeDocument $document): bool
    {
        return in_array($user->role ?? null, ['admin', 'petugas', 'supervisor'], true);
    }

    public function delete(User $user, RefugeeDocument $document): bool
    {
        return ($user->role ?? null) === 'admin';
    }
}

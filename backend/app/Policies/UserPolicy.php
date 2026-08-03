<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\User;

final class UserPolicy
{
    public function viewAny(User $actor): bool
    {
        return $this->isAdmin($actor);
    }

    public function view(User $actor, User $user): bool
    {
        return $actor->id === $user->id || $this->isAdmin($actor);
    }

    public function create(User $actor): bool
    {
        return $this->isAdmin($actor);
    }

    public function update(User $actor, User $user): bool
    {
        return $actor->id === $user->id || $this->isAdmin($actor);
    }

    public function delete(User $actor, User $user): bool
    {
        return $actor->id === $user->id || $actor->hasRole('super-admin');
    }

    private function isAdmin(User $actor): bool
    {
        return $actor->hasRole(['admin', 'super-admin']);
    }
}

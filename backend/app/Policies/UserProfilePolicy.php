<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\User;
use App\Models\UserProfile;

final class UserProfilePolicy
{
    public function viewAny(User $actor): bool
    {
        return $this->isAdmin($actor);
    }

    public function view(User $actor, UserProfile $profile): bool
    {
        return $actor->id === $profile->user_id || $this->isAdmin($actor);
    }

    public function create(User $actor): bool
    {
        return $this->isAdmin($actor);
    }

    public function update(User $actor, UserProfile $profile): bool
    {
        return $actor->id === $profile->user_id || $this->isAdmin($actor);
    }

    public function delete(User $actor, UserProfile $profile): bool
    {
        return $actor->id === $profile->user_id || $actor->hasRole('super-admin');
    }

    private function isAdmin(User $actor): bool
    {
        return $actor->hasRole(['admin', 'super-admin']);
    }
}

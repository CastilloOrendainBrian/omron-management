<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Goal;
use App\Models\User;

final class GoalPolicy
{
    public function viewAny(User $actor): bool
    {
        return $actor->hasRole(['admin', 'super-admin']);
    }

    public function view(User $actor, Goal $goal): bool
    {
        return $this->isOwner($actor, $goal) || $this->isAdmin($actor);
    }

    public function create(User $actor): bool
    {
        return true;
    }

    public function update(User $actor, Goal $goal): bool
    {
        return $this->isOwner($actor, $goal) || $this->isAdmin($actor);
    }

    public function delete(User $actor, Goal $goal): bool
    {
        return $this->isOwner($actor, $goal) || $actor->hasRole('super-admin');
    }

    private function isOwner(User $actor, Goal $goal): bool
    {
        return $goal->user_id === $actor->id;
    }

    private function isAdmin(User $actor): bool
    {
        return $actor->hasRole(['admin', 'super-admin']);
    }
}

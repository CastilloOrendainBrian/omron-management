<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\SkinfoldSite;
use App\Models\User;

final class SkinfoldSitePolicy
{
    public function viewAny(User $actor): bool
    {
        return true;
    }

    public function view(User $actor, SkinfoldSite $site): bool
    {
        return true;
    }

    public function create(User $actor): bool
    {
        return $actor->hasRole(['admin', 'super-admin']);
    }

    public function update(User $actor, SkinfoldSite $site): bool
    {
        return $actor->hasRole(['admin', 'super-admin']);
    }

    public function delete(User $actor, SkinfoldSite $site): bool
    {
        return $actor->hasRole('super-admin');
    }
}

<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\SkinfoldProtocol;
use App\Models\User;

final class SkinfoldProtocolPolicy
{
    public function viewAny(User $actor): bool
    {
        return true;
    }

    public function view(User $actor, SkinfoldProtocol $protocol): bool
    {
        return true;
    }

    public function create(User $actor): bool
    {
        return $actor->hasRole(['admin', 'super-admin']);
    }

    public function update(User $actor, SkinfoldProtocol $protocol): bool
    {
        return $actor->hasRole(['admin', 'super-admin']);
    }

    public function delete(User $actor, SkinfoldProtocol $protocol): bool
    {
        return $actor->hasRole('super-admin');
    }
}

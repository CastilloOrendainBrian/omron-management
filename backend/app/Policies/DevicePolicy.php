<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Device;
use App\Models\User;

final class DevicePolicy
{
    public function viewAny(User $actor): bool
    {
        return $actor->hasRole(['admin', 'super-admin']);
    }

    public function view(User $actor, Device $device): bool
    {
        return $this->isOwner($actor, $device) || $this->isAdmin($actor);
    }

    public function create(User $actor): bool
    {
        return true;
    }

    public function update(User $actor, Device $device): bool
    {
        return $this->isOwner($actor, $device) || $this->isAdmin($actor);
    }

    public function delete(User $actor, Device $device): bool
    {
        return $this->isOwner($actor, $device) || $actor->hasRole('super-admin');
    }

    private function isOwner(User $actor, Device $device): bool
    {
        return $device->user_id !== null && $device->user_id === $actor->id;
    }

    private function isAdmin(User $actor): bool
    {
        return $actor->hasRole(['admin', 'super-admin']);
    }
}

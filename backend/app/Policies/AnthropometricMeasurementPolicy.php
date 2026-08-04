<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\AnthropometricMeasurement;
use App\Models\User;

final class AnthropometricMeasurementPolicy
{
    public function viewAny(User $actor): bool
    {
        return $actor->hasRole(['admin', 'super-admin']);
    }

    public function view(User $actor, AnthropometricMeasurement $measurement): bool
    {
        return $this->isOwner($actor, $measurement) || $this->isAdmin($actor);
    }

    public function create(User $actor): bool
    {
        return true;
    }

    public function update(User $actor, AnthropometricMeasurement $measurement): bool
    {
        return $this->isOwner($actor, $measurement) || $this->isAdmin($actor);
    }

    public function delete(User $actor, AnthropometricMeasurement $measurement): bool
    {
        return $this->isOwner($actor, $measurement) || $actor->hasRole('super-admin');
    }

    private function isOwner(User $actor, AnthropometricMeasurement $measurement): bool
    {
        return $actor->id === $measurement->measurementSession->user_id;
    }

    private function isAdmin(User $actor): bool
    {
        return $actor->hasRole(['admin', 'super-admin']);
    }
}

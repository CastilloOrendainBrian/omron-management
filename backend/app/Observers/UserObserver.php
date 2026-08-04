<?php

declare(strict_types=1);

namespace App\Observers;

use App\Models\User;

final class UserObserver
{
    public function trashed(User $user): void
    {
        $profile = $user->profile;
        if ($profile === null) {
            return;
        }

        if ($profile->trashed()) {
            return;
        }

        $profile->delete();
    }

    public function forceDeleted(User $user): void
    {
        $profile = $user->profile()->withTrashed()->first();
        if ($profile === null) {
            return;
        }

        $profile->forceDelete();
    }
}

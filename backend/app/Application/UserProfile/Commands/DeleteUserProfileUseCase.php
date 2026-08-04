<?php

declare(strict_types=1);

namespace App\Application\UserProfile\Commands;

use App\Domain\UserProfile\Contracts\UserProfileRepositoryInterface;
use App\Models\UserProfile;

final readonly class DeleteUserProfileUseCase
{
    public function __construct(
        private UserProfileRepositoryInterface $profiles,
    ) {
    }

    public function execute(UserProfile $profile): void
    {
        $this->profiles->delete($profile);
    }
}

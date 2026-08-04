<?php

declare(strict_types=1);

namespace App\Application\UserProfile\Queries;

use App\Domain\UserProfile\Contracts\UserProfileRepositoryInterface;
use App\Models\UserProfile;

final readonly class ShowUserProfileUseCase
{
    public function __construct(
        private UserProfileRepositoryInterface $profiles,
    ) {
    }

    public function execute(UserProfile $profile): UserProfile
    {
        return $profile->load('user');
    }
}

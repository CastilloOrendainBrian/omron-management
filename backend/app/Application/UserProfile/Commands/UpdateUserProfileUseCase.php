<?php

declare(strict_types=1);

namespace App\Application\UserProfile\Commands;

use App\Domain\UserProfile\Contracts\UserProfileRepositoryInterface;
use App\Domain\UserProfile\DTOs\UpdateUserProfileDTO;
use App\Models\UserProfile;

final readonly class UpdateUserProfileUseCase
{
    public function __construct(
        private UserProfileRepositoryInterface $profiles,
    ) {
    }

    public function execute(UserProfile $profile, UpdateUserProfileDTO $dto): UserProfile
    {
        return $this->profiles->update($profile, $dto);
    }
}

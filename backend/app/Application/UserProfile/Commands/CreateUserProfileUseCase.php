<?php

declare(strict_types=1);

namespace App\Application\UserProfile\Commands;

use App\Domain\UserProfile\Contracts\UserProfileRepositoryInterface;
use App\Domain\UserProfile\DTOs\CreateUserProfileDTO;
use App\Models\UserProfile;

final readonly class CreateUserProfileUseCase
{
    public function __construct(
        private UserProfileRepositoryInterface $profiles,
    ) {
    }

    public function execute(CreateUserProfileDTO $dto): UserProfile
    {
        return $this->profiles->create($dto);
    }
}

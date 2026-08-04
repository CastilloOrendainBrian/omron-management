<?php

declare(strict_types=1);

namespace App\Application\UserProfile\Queries;

use App\Domain\UserProfile\Contracts\UserProfileRepositoryInterface;
use App\Domain\UserProfile\DTOs\ListUserProfilesDTO;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

final readonly class ListUserProfilesUseCase
{
    public function __construct(
        private UserProfileRepositoryInterface $profiles,
    ) {
    }

    public function execute(ListUserProfilesDTO $dto): LengthAwarePaginator
    {
        return $this->profiles->paginate($dto);
    }
}

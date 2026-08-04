<?php

declare(strict_types=1);

namespace App\Application\User\Commands;

use App\Domain\User\DTOs\CreateUserDTO;
use App\Domain\User\DTOs\CreateUserWithProfileDTO;
use App\Domain\UserProfile\DTOs\CreateUserProfileDTO;
use App\Domain\UserProfile\Contracts\UserProfileRepositoryInterface;
use App\Domain\User\Contracts\UserRepositoryInterface;
use App\Models\User;
use App\Models\UserProfile;
use Illuminate\Support\Facades\DB;

final readonly class CreateUserWithProfileUseCase
{
    public function __construct(
        private UserRepositoryInterface $users,
        private UserProfileRepositoryInterface $profiles,
    ) {
    }

    /**
     * @return array{user: User, profile: UserProfile}
     */
    public function execute(CreateUserWithProfileDTO $dto): array
    {
        return DB::transaction(function () use ($dto) {
            $user = $this->users->create(new CreateUserDTO(
                name: $dto->name,
                email: $dto->email,
                password: $dto->password,
            ));

            $profile = $this->profiles->create(new CreateUserProfileDTO(
                userId: $user->id,
                sex: $dto->sex,
                birthDate: $dto->birthDate,
                heightReferenceCm: $dto->heightReferenceCm,
                activityLevel: $dto->activityLevel,
            ));

            return ['user' => $user, 'profile' => $profile];
        });
    }
}

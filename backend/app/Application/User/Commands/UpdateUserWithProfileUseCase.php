<?php

declare(strict_types=1);

namespace App\Application\User\Commands;

use App\Domain\User\DTOs\UpdateUserDTO;
use App\Domain\User\DTOs\UpdateUserWithProfileDTO;
use App\Domain\User\Contracts\UserRepositoryInterface;
use App\Domain\UserProfile\DTOs\CreateUserProfileDTO;
use App\Domain\UserProfile\DTOs\UpdateUserProfileDTO;
use App\Domain\UserProfile\Contracts\UserProfileRepositoryInterface;
use App\Models\User;
use App\Models\UserProfile;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

final readonly class UpdateUserWithProfileUseCase
{
    public function __construct(
        private UserRepositoryInterface $users,
        private UserProfileRepositoryInterface $profiles,
    ) {
    }

    /**
     * @return array{user: User, profile: UserProfile|null}
     */
    public function execute(User $user, UpdateUserWithProfileDTO $dto): array
    {
        return DB::transaction(function () use ($user, $dto) {
            $updatedUser = $this->users->update($user, new UpdateUserDTO(
                name: $dto->name,
                email: $dto->email,
                password: $dto->password,
            ));

            $hasProfileFields = $dto->sex !== null
                || $dto->birthDate !== null
                || $dto->heightReferenceCm !== null
                || $dto->activityLevel !== null;

            $updatedProfile = $user->profile;

            if ($hasProfileFields) {
                if ($updatedProfile === null) {
                    if ($dto->sex === null || $dto->birthDate === null) {
                        throw new InvalidArgumentException(
                            'Cannot create profile without sex and birth_date.'
                        );
                    }
                    $updatedProfile = $this->profiles->create(new CreateUserProfileDTO(
                        userId: $user->id,
                        sex: $dto->sex,
                        birthDate: $dto->birthDate,
                        heightReferenceCm: $dto->heightReferenceCm,
                        activityLevel: $dto->activityLevel,
                    ));
                } else {
                    $updatedProfile = $this->profiles->update($updatedProfile, new UpdateUserProfileDTO(
                        sex: $dto->sex,
                        birthDate: $dto->birthDate,
                        heightReferenceCm: $dto->heightReferenceCm,
                        activityLevel: $dto->activityLevel,
                    ));
                }
            }

            return ['user' => $updatedUser, 'profile' => $updatedProfile];
        });
    }
}

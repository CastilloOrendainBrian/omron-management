<?php

declare(strict_types=1);

namespace App\Application\Auth\Commands;

use App\Domain\Auth\Contracts\AuthTokenServiceInterface;
use App\Domain\Auth\Contracts\PasswordVerifierInterface;
use App\Domain\Auth\DTOs\AuthTokenDTO;
use App\Domain\Auth\DTOs\LoginCredentialsDTO;
use App\Domain\Auth\Exceptions\InvalidCredentialsException;
use App\Domain\User\Contracts\UserRepositoryInterface;

final readonly class LoginUseCase
{
    public function __construct(
        private UserRepositoryInterface $users,
        private PasswordVerifierInterface $verifier,
        private AuthTokenServiceInterface $tokens,
    ) {
    }

    public function execute(LoginCredentialsDTO $credentials): AuthTokenDTO
    {
        $user = $this->users->findByEmail($credentials->email);

        if ($user === null) {
            throw new InvalidCredentialsException();
        }

        if (! $this->verifier->verify($credentials->password, $user->password)) {
            throw new InvalidCredentialsException();
        }

        return $this->tokens->issue(
            userId: $user->id,
            name: $credentials->deviceName,
            abilities: ['*'],
        );
    }
}

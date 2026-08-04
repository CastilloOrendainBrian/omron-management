<?php

declare(strict_types=1);

namespace App\Application\Auth\Commands;

use App\Domain\Auth\Contracts\PasswordResetServiceInterface;
use App\Domain\Auth\Exceptions\InvalidPasswordResetTokenException;

final readonly class ResetPasswordUseCase
{
    public function __construct(
        private PasswordResetServiceInterface $passwords,
    ) {
    }

    public function execute(string $email, string $token, string $newPassword): void
    {
        $reset = $this->passwords->reset($email, $token, $newPassword);

        if (! $reset) {
            throw new InvalidPasswordResetTokenException();
        }
    }
}

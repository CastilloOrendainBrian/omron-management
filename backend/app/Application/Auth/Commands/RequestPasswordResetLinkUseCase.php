<?php

declare(strict_types=1);

namespace App\Application\Auth\Commands;

use App\Domain\Auth\Contracts\PasswordResetServiceInterface;

final readonly class RequestPasswordResetLinkUseCase
{
    public function __construct(
        private PasswordResetServiceInterface $passwords,
    ) {
    }

    public function execute(string $email): void
    {
        $this->passwords->requestLink($email);
    }
}

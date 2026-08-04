<?php

declare(strict_types=1);

namespace App\Application\Auth\Commands;

use App\Domain\Auth\Contracts\AuthTokenServiceInterface;

final readonly class LogoutAllUseCase
{
    public function __construct(
        private AuthTokenServiceInterface $tokens,
    ) {
    }

    public function execute(int $userId): int
    {
        return $this->tokens->revokeAll($userId);
    }
}

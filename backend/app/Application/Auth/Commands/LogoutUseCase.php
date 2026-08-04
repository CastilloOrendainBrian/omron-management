<?php

declare(strict_types=1);

namespace App\Application\Auth\Commands;

use App\Domain\Auth\Contracts\AuthTokenServiceInterface;

final readonly class LogoutUseCase
{
    public function __construct(
        private AuthTokenServiceInterface $tokens,
    ) {
    }

    public function execute(int $userId, int $tokenId): void
    {
        $this->tokens->revokeCurrent($userId, $tokenId);
    }
}

<?php

declare(strict_types=1);

namespace App\Domain\Auth\Contracts;

use App\Domain\Auth\DTOs\AuthTokenDTO;

interface AuthTokenServiceInterface
{
    public function issue(int $userId, string $name, array $abilities = []): AuthTokenDTO;

    public function revokeCurrent(int $userId, int $tokenId): void;

    /**
     * @return int Number of tokens revoked
     */
    public function revokeAll(int $userId): int;
}

<?php

declare(strict_types=1);

namespace App\Domain\Auth\DTOs;

use DateTimeImmutable;

final readonly class AuthTokenDTO
{
    /**
     * @param  array<int, string>  $abilities
     */
    public function __construct(
        public int $userId,
        public int $tokenId,
        public string $plainTextToken,
        public string $name,
        public array $abilities,
        public ?DateTimeImmutable $expiresAt,
    ) {
    }
}

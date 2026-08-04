<?php

declare(strict_types=1);

namespace App\Domain\Goal\DTOs;

final readonly class ListGoalsDTO
{
    public function __construct(
        public int $perPage = 25,
        public ?int $userId = null,
        public ?string $status = null,
    ) {
    }
}

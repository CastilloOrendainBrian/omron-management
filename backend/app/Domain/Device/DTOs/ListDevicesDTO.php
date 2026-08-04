<?php

declare(strict_types=1);

namespace App\Domain\Device\DTOs;

final readonly class ListDevicesDTO
{
    public function __construct(
        public int $perPage = 25,
        public ?int $userId = null,
        public ?string $brand = null,
    ) {
    }
}

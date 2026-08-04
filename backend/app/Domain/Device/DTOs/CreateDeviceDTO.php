<?php

declare(strict_types=1);

namespace App\Domain\Device\DTOs;

final readonly class CreateDeviceDTO
{
    public function __construct(
        public string $brand,
        public string $model,
        public ?int $userId = null,
        public ?string $serialNumber = null,
    ) {
    }
}

<?php

declare(strict_types=1);

namespace App\Domain\Device\DTOs;

final readonly class UpdateDeviceDTO
{
    public function __construct(
        public ?int $userId = null,
        public ?string $brand = null,
        public ?string $model = null,
        public ?string $serialNumber = null,
    ) {
    }
}

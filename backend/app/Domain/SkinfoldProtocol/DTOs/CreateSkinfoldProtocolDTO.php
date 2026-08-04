<?php

declare(strict_types=1);

namespace App\Domain\SkinfoldProtocol\DTOs;

final readonly class CreateSkinfoldProtocolDTO
{
    public function __construct(
        public string $name,
        public int $sitesCount,
        public ?string $description = null,
    ) {
    }
}

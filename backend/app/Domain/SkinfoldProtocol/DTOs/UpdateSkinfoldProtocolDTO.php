<?php

declare(strict_types=1);

namespace App\Domain\SkinfoldProtocol\DTOs;

final readonly class UpdateSkinfoldProtocolDTO
{
    public function __construct(
        public ?string $name = null,
        public ?int $sitesCount = null,
        public ?string $description = null,
    ) {
    }
}

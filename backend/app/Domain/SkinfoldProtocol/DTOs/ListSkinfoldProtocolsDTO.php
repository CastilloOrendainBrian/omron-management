<?php

declare(strict_types=1);

namespace App\Domain\SkinfoldProtocol\DTOs;

final readonly class ListSkinfoldProtocolsDTO
{
    public function __construct(
        public int $perPage = 25,
        public ?string $name = null,
        public ?int $sitesCount = null,
    ) {
    }
}

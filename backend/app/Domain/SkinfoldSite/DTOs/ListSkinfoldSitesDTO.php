<?php

declare(strict_types=1);

namespace App\Domain\SkinfoldSite\DTOs;

final readonly class ListSkinfoldSitesDTO
{
    public function __construct(
        public int $perPage = 25,
        public ?string $code = null,
        public ?string $name = null,
    ) {
    }
}

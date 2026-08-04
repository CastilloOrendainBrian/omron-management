<?php

declare(strict_types=1);

namespace App\Domain\SkinfoldSite\DTOs;

final readonly class CreateSkinfoldSiteDTO
{
    public function __construct(
        public string $code,
        public string $name,
    ) {
    }
}

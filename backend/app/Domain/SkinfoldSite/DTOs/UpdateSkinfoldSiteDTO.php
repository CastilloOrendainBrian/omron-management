<?php

declare(strict_types=1);

namespace App\Domain\SkinfoldSite\DTOs;

final readonly class UpdateSkinfoldSiteDTO
{
    public function __construct(
        public ?string $code = null,
        public ?string $name = null,
    ) {
    }
}

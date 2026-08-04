<?php

declare(strict_types=1);

namespace App\Application\SkinfoldProtocol\Commands;

use App\Domain\SkinfoldProtocol\Contracts\SkinfoldProtocolRepositoryInterface;
use App\Models\SkinfoldProtocol;

final readonly class DeleteSkinfoldProtocolUseCase
{
    public function __construct(
        private SkinfoldProtocolRepositoryInterface $protocols,
    ) {
    }

    public function execute(SkinfoldProtocol $protocol): void
    {
        $this->protocols->delete($protocol);
    }
}

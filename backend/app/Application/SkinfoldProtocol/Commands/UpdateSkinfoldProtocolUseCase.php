<?php

declare(strict_types=1);

namespace App\Application\SkinfoldProtocol\Commands;

use App\Domain\SkinfoldProtocol\Contracts\SkinfoldProtocolRepositoryInterface;
use App\Domain\SkinfoldProtocol\DTOs\UpdateSkinfoldProtocolDTO;
use App\Models\SkinfoldProtocol;

final readonly class UpdateSkinfoldProtocolUseCase
{
    public function __construct(
        private SkinfoldProtocolRepositoryInterface $protocols,
    ) {
    }

    public function execute(SkinfoldProtocol $protocol, UpdateSkinfoldProtocolDTO $dto): SkinfoldProtocol
    {
        return $this->protocols->update($protocol, $dto);
    }
}

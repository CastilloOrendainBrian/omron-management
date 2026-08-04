<?php

declare(strict_types=1);

namespace App\Application\SkinfoldProtocol\Commands;

use App\Domain\SkinfoldProtocol\Contracts\SkinfoldProtocolRepositoryInterface;
use App\Domain\SkinfoldProtocol\DTOs\CreateSkinfoldProtocolDTO;
use App\Models\SkinfoldProtocol;

final readonly class CreateSkinfoldProtocolUseCase
{
    public function __construct(
        private SkinfoldProtocolRepositoryInterface $protocols,
    ) {
    }

    public function execute(CreateSkinfoldProtocolDTO $dto): SkinfoldProtocol
    {
        return $this->protocols->create($dto);
    }
}

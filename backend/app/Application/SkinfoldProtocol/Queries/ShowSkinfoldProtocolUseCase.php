<?php

declare(strict_types=1);

namespace App\Application\SkinfoldProtocol\Queries;

use App\Models\SkinfoldProtocol;

final readonly class ShowSkinfoldProtocolUseCase
{
    public function execute(SkinfoldProtocol $protocol): SkinfoldProtocol
    {
        return $protocol;
    }
}

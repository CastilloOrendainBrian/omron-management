<?php

declare(strict_types=1);

namespace App\Application\Device\Commands;

use App\Domain\Device\Contracts\DeviceRepositoryInterface;
use App\Domain\Device\DTOs\CreateDeviceDTO;
use App\Models\Device;

final readonly class CreateDeviceUseCase
{
    public function __construct(
        private DeviceRepositoryInterface $devices,
    ) {
    }

    public function execute(CreateDeviceDTO $dto): Device
    {
        return $this->devices->create($dto);
    }
}

<?php

declare(strict_types=1);

namespace App\Application\Device\Queries;

use App\Models\Device;

final readonly class ShowDeviceUseCase
{
    public function execute(Device $device): Device
    {
        return $device->load('user');
    }
}

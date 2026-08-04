<?php

declare(strict_types=1);

namespace App\Http\Requests\Devices;

use App\Domain\Device\DTOs\UpdateDeviceDTO;
use App\Models\Device;
use Illuminate\Foundation\Http\FormRequest;

final class UpdateDeviceRequest extends FormRequest
{
    public function authorize(): bool
    {
        $device = $this->route('device');

        return $device instanceof Device
            ? ($this->user()?->can('update', $device) ?? false)
            : false;
    }

    public function rules(): array
    {
        return [
            'user_id' => ['sometimes', 'nullable', 'integer', 'exists:users,id'],
            'brand' => ['sometimes', 'string', 'max:255'],
            'model' => ['sometimes', 'string', 'max:255'],
            'serial_number' => ['sometimes', 'nullable', 'string', 'max:255'],
        ];
    }

    public function toDto(): UpdateDeviceDTO
    {
        return new UpdateDeviceDTO(
            userId: $this->validated('user_id') !== null ? (int) $this->validated('user_id') : null,
            brand: $this->validated('brand'),
            model: $this->validated('model'),
            serialNumber: $this->validated('serial_number'),
        );
    }
}

<?php

declare(strict_types=1);

namespace App\Http\Requests\Devices;

use App\Domain\Device\DTOs\ListDevicesDTO;
use App\Models\Device;
use Illuminate\Foundation\Http\FormRequest;

final class ListDevicesRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('viewAny', Device::class) ?? false;
    }

    public function rules(): array
    {
        return [
            'per_page' => ['sometimes', 'integer', 'min:1', 'max:100'],
            'page' => ['sometimes', 'integer', 'min:1'],
            'user_id' => ['sometimes', 'integer', 'exists:users,id'],
            'brand' => ['sometimes', 'string', 'max:255'],
        ];
    }

    public function toDto(): ListDevicesDTO
    {
        return new ListDevicesDTO(
            perPage: (int) ($this->validated('per_page') ?? 25),
            userId: $this->validated('user_id') !== null ? (int) $this->validated('user_id') : null,
            brand: $this->validated('brand'),
        );
    }
}

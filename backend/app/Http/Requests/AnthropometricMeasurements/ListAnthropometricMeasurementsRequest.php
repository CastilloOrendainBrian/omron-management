<?php

declare(strict_types=1);

namespace App\Http\Requests\AnthropometricMeasurements;

use App\Domain\AnthropometricMeasurement\DTOs\ListAnthropometricMeasurementsDTO;
use App\Models\AnthropometricMeasurement;
use Illuminate\Foundation\Http\FormRequest;

final class ListAnthropometricMeasurementsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('viewAny', AnthropometricMeasurement::class) ?? false;
    }

    public function rules(): array
    {
        return [
            'per_page' => ['sometimes', 'integer', 'min:1', 'max:100'],
            'page' => ['sometimes', 'integer', 'min:1'],
            'measurement_session_id' => ['sometimes', 'integer', 'exists:measurement_sessions,id'],
        ];
    }

    public function toDto(): ListAnthropometricMeasurementsDTO
    {
        return new ListAnthropometricMeasurementsDTO(
            perPage: (int) ($this->validated('per_page') ?? 25),
            measurementSessionId: $this->validated('measurement_session_id') !== null ? (int) $this->validated('measurement_session_id') : null,
        );
    }
}

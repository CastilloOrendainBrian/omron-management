<?php

declare(strict_types=1);

namespace App\Http\Requests\SkinfoldMeasurements;

use App\Domain\SkinfoldMeasurement\DTOs\ListSkinfoldMeasurementsDTO;
use App\Models\SkinfoldMeasurement;
use Illuminate\Foundation\Http\FormRequest;

final class ListSkinfoldMeasurementsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('viewAny', SkinfoldMeasurement::class) ?? false;
    }

    public function rules(): array
    {
        return [
            'per_page' => ['sometimes', 'integer', 'min:1', 'max:100'],
            'page' => ['sometimes', 'integer', 'min:1'],
            'measurement_session_id' => ['sometimes', 'integer', 'exists:measurement_sessions,id'],
            'skinfold_protocol_id' => ['sometimes', 'integer', 'exists:skinfold_protocols,id'],
        ];
    }

    public function toDto(): ListSkinfoldMeasurementsDTO
    {
        return new ListSkinfoldMeasurementsDTO(
            perPage: (int) ($this->validated('per_page') ?? 25),
            measurementSessionId: $this->validated('measurement_session_id') !== null ? (int) $this->validated('measurement_session_id') : null,
            skinfoldProtocolId: $this->validated('skinfold_protocol_id') !== null ? (int) $this->validated('skinfold_protocol_id') : null,
        );
    }
}

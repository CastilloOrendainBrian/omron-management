<?php

declare(strict_types=1);

namespace App\Http\Requests\AnthropometricMeasurements;

use App\Domain\AnthropometricMeasurement\DTOs\UpdateAnthropometricMeasurementDTO;
use App\Models\AnthropometricMeasurement;
use Illuminate\Foundation\Http\FormRequest;

final class UpdateAnthropometricMeasurementRequest extends FormRequest
{
    public function authorize(): bool
    {
        $measurement = $this->route('anthropometric_measurement');

        return $measurement instanceof AnthropometricMeasurement
            ? ($this->user()?->can('update', $measurement) ?? false)
            : false;
    }

    public function rules(): array
    {
        return [
            'height_cm' => ['sometimes', 'numeric', 'min:0', 'max:300'],
            'weight_kg' => ['sometimes', 'numeric', 'min:0', 'max:700'],
            'bmi' => ['sometimes', 'numeric', 'min:0', 'max:99.9'],
        ];
    }

    public function toDto(): UpdateAnthropometricMeasurementDTO
    {
        return new UpdateAnthropometricMeasurementDTO(
            heightCm: $this->validated('height_cm') !== null ? (float) $this->validated('height_cm') : null,
            weightKg: $this->validated('weight_kg') !== null ? (float) $this->validated('weight_kg') : null,
            bmi: $this->validated('bmi') !== null ? (float) $this->validated('bmi') : null,
        );
    }
}

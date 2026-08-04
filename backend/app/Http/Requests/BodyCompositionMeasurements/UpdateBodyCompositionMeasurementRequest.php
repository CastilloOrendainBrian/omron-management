<?php

declare(strict_types=1);

namespace App\Http\Requests\BodyCompositionMeasurements;

use App\Domain\BodyCompositionMeasurement\DTOs\UpdateBodyCompositionMeasurementDTO;
use App\Models\BodyCompositionMeasurement;
use Illuminate\Foundation\Http\FormRequest;

final class UpdateBodyCompositionMeasurementRequest extends FormRequest
{
    public function authorize(): bool
    {
        $measurement = $this->route('body_composition_measurement');

        return $measurement instanceof BodyCompositionMeasurement
            ? ($this->user()?->can('update', $measurement) ?? false)
            : false;
    }

    public function rules(): array
    {
        return [
            'body_fat_percentage' => ['sometimes', 'nullable', 'numeric', 'min:0', 'max:99.9'],
            'muscle_percentage' => ['sometimes', 'nullable', 'numeric', 'min:0', 'max:99.9'],
            'visceral_fat_level' => ['sometimes', 'nullable', 'integer', 'min:0', 'max:100'],
            'metabolic_age' => ['sometimes', 'nullable', 'integer', 'min:0', 'max:150'],
            'bmr_kcal' => ['sometimes', 'nullable', 'integer', 'min:0', 'max:10000'],
        ];
    }

    public function toDto(): UpdateBodyCompositionMeasurementDTO
    {
        return new UpdateBodyCompositionMeasurementDTO(
            bodyFatPercentage: $this->validated('body_fat_percentage') !== null ? (float) $this->validated('body_fat_percentage') : null,
            musclePercentage: $this->validated('muscle_percentage') !== null ? (float) $this->validated('muscle_percentage') : null,
            visceralFatLevel: $this->validated('visceral_fat_level') !== null ? (int) $this->validated('visceral_fat_level') : null,
            metabolicAge: $this->validated('metabolic_age') !== null ? (int) $this->validated('metabolic_age') : null,
            bmrKcal: $this->validated('bmr_kcal') !== null ? (int) $this->validated('bmr_kcal') : null,
        );
    }
}

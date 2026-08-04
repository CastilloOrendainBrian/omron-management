<?php

declare(strict_types=1);

namespace App\Http\Requests\BodyCompositionMeasurements;

use App\Domain\BodyCompositionMeasurement\DTOs\CreateBodyCompositionMeasurementDTO;
use App\Models\MeasurementSession;
use Illuminate\Foundation\Http\FormRequest;

final class StoreBodyCompositionMeasurementRequest extends FormRequest
{
    public function authorize(): bool
    {
        $actor = $this->user();
        if ($actor === null) {
            return false;
        }

        if ($actor->hasRole(['admin', 'super-admin'])) {
            return true;
        }

        $measurementSessionId = $this->input('measurement_session_id');
        if ($measurementSessionId === null) {
            return false;
        }

        $measurementSession = MeasurementSession::find($measurementSessionId);

        return $measurementSession !== null && $measurementSession->user_id === $actor->id;
    }

    public function rules(): array
    {
        return [
            'measurement_session_id' => ['required', 'integer', 'exists:measurement_sessions,id', 'unique:body_composition_measurements,measurement_session_id'],
            'body_fat_percentage' => ['nullable', 'numeric', 'min:0', 'max:99.9'],
            'muscle_percentage' => ['nullable', 'numeric', 'min:0', 'max:99.9'],
            'visceral_fat_level' => ['nullable', 'integer', 'min:0', 'max:100'],
            'metabolic_age' => ['nullable', 'integer', 'min:0', 'max:150'],
            'bmr_kcal' => ['nullable', 'integer', 'min:0', 'max:10000'],
        ];
    }

    public function toDto(): CreateBodyCompositionMeasurementDTO
    {
        return new CreateBodyCompositionMeasurementDTO(
            measurementSessionId: (int) $this->validated('measurement_session_id'),
            bodyFatPercentage: $this->validated('body_fat_percentage') !== null ? (float) $this->validated('body_fat_percentage') : null,
            musclePercentage: $this->validated('muscle_percentage') !== null ? (float) $this->validated('muscle_percentage') : null,
            visceralFatLevel: $this->validated('visceral_fat_level') !== null ? (int) $this->validated('visceral_fat_level') : null,
            metabolicAge: $this->validated('metabolic_age') !== null ? (int) $this->validated('metabolic_age') : null,
            bmrKcal: $this->validated('bmr_kcal') !== null ? (int) $this->validated('bmr_kcal') : null,
        );
    }
}

<?php

declare(strict_types=1);

namespace App\Http\Requests\AnthropometricMeasurements;

use App\Domain\AnthropometricMeasurement\DTOs\CreateAnthropometricMeasurementDTO;
use App\Models\MeasurementSession;
use Illuminate\Foundation\Http\FormRequest;

final class StoreAnthropometricMeasurementRequest extends FormRequest
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
            'measurement_session_id' => ['required', 'integer', 'exists:measurement_sessions,id', 'unique:anthropometric_measurements,measurement_session_id'],
            'height_cm' => ['required', 'numeric', 'min:0', 'max:300'],
            'weight_kg' => ['required', 'numeric', 'min:0', 'max:700'],
            'bmi' => ['required', 'numeric', 'min:0', 'max:99.9'],
        ];
    }

    public function toDto(): CreateAnthropometricMeasurementDTO
    {
        return new CreateAnthropometricMeasurementDTO(
            measurementSessionId: (int) $this->validated('measurement_session_id'),
            heightCm: (float) $this->validated('height_cm'),
            weightKg: (float) $this->validated('weight_kg'),
            bmi: (float) $this->validated('bmi'),
        );
    }
}

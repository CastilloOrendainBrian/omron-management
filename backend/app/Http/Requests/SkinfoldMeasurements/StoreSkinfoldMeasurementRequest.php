<?php

declare(strict_types=1);

namespace App\Http\Requests\SkinfoldMeasurements;

use App\Domain\SkinfoldMeasurement\DTOs\CreateSkinfoldMeasurementDTO;
use App\Domain\SkinfoldMeasurement\DTOs\SkinfoldMeasurementDetailDTO;
use App\Models\MeasurementSession;
use App\Models\SkinfoldMeasurement;
use Illuminate\Foundation\Http\FormRequest;

final class StoreSkinfoldMeasurementRequest extends FormRequest
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
            'measurement_session_id' => ['required', 'integer', 'exists:measurement_sessions,id'],
            'skinfold_protocol_id' => ['required', 'integer', 'exists:skinfold_protocols,id'],
            'estimated_body_fat_percentage' => ['sometimes', 'nullable', 'numeric', 'min:0', 'max:99.9'],
            'details' => ['required', 'array', 'min:1'],
            'details.*.skinfold_site_id' => ['required', 'integer', 'exists:skinfold_sites,id'],
            'details.*.value_mm' => ['required', 'numeric', 'min:0', 'max:999.9'],
        ];
    }

    public function toDto(): CreateSkinfoldMeasurementDTO
    {
        $details = [];
        foreach ($this->validated('details') as $detail) {
            $details[] = new SkinfoldMeasurementDetailDTO(
                skinfoldSiteId: (int) $detail['skinfold_site_id'],
                valueMm: (float) $detail['value_mm'],
            );
        }

        return new CreateSkinfoldMeasurementDTO(
            measurementSessionId: (int) $this->validated('measurement_session_id'),
            skinfoldProtocolId: (int) $this->validated('skinfold_protocol_id'),
            estimatedBodyFatPercentage: $this->validated('estimated_body_fat_percentage') !== null ? (float) $this->validated('estimated_body_fat_percentage') : null,
            details: $details,
        );
    }
}

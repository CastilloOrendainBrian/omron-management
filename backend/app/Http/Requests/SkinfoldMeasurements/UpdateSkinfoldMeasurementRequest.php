<?php

declare(strict_types=1);

namespace App\Http\Requests\SkinfoldMeasurements;

use App\Domain\SkinfoldMeasurement\DTOs\SkinfoldMeasurementDetailDTO;
use App\Domain\SkinfoldMeasurement\DTOs\UpdateSkinfoldMeasurementDTO;
use App\Models\SkinfoldMeasurement;
use Illuminate\Foundation\Http\FormRequest;

final class UpdateSkinfoldMeasurementRequest extends FormRequest
{
    public function authorize(): bool
    {
        $measurement = $this->route('skinfold_measurement');

        return $measurement instanceof SkinfoldMeasurement
            ? ($this->user()?->can('update', $measurement) ?? false)
            : false;
    }

    public function rules(): array
    {
        return [
            'estimated_body_fat_percentage' => ['sometimes', 'nullable', 'numeric', 'min:0', 'max:99.9'],
            'details' => ['sometimes', 'array'],
            'details.*.skinfold_site_id' => ['required', 'integer', 'exists:skinfold_sites,id'],
            'details.*.value_mm' => ['required', 'numeric', 'min:0', 'max:999.9'],
        ];
    }

    public function toDto(): UpdateSkinfoldMeasurementDTO
    {
        $details = null;
        if ($this->has('details')) {
            $details = [];
            foreach ($this->validated('details') as $detail) {
                $details[] = new SkinfoldMeasurementDetailDTO(
                    skinfoldSiteId: (int) $detail['skinfold_site_id'],
                    valueMm: (float) $detail['value_mm'],
                );
            }
        }

        return new UpdateSkinfoldMeasurementDTO(
            estimatedBodyFatPercentage: $this->validated('estimated_body_fat_percentage') !== null ? (float) $this->validated('estimated_body_fat_percentage') : null,
            details: $details,
        );
    }
}

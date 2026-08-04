<?php

declare(strict_types=1);

namespace App\Http\Resources\AnthropometricMeasurements;

use App\Models\AnthropometricMeasurement;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin AnthropometricMeasurement */
final class AnthropometricMeasurementResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'measurement_session_id' => $this->measurement_session_id,
            'height_cm' => (float) $this->height_cm,
            'weight_kg' => (float) $this->weight_kg,
            'bmi' => (float) $this->bmi,
            'measurement_session' => $this->whenLoaded('measurementSession'),
            'created_at' => $this->created_at?->toIso8601String(),
            'updated_at' => $this->updated_at?->toIso8601String(),
        ];
    }
}

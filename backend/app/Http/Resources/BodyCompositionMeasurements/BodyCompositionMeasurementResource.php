<?php

declare(strict_types=1);

namespace App\Http\Resources\BodyCompositionMeasurements;

use App\Models\BodyCompositionMeasurement;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin BodyCompositionMeasurement */
final class BodyCompositionMeasurementResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'measurement_session_id' => $this->measurement_session_id,
            'body_fat_percentage' => $this->body_fat_percentage !== null ? (float) $this->body_fat_percentage : null,
            'muscle_percentage' => $this->muscle_percentage !== null ? (float) $this->muscle_percentage : null,
            'visceral_fat_level' => $this->visceral_fat_level,
            'metabolic_age' => $this->metabolic_age,
            'bmr_kcal' => $this->bmr_kcal,
            'measurement_session' => $this->whenLoaded('measurementSession'),
            'created_at' => $this->created_at?->toIso8601String(),
            'updated_at' => $this->updated_at?->toIso8601String(),
        ];
    }
}

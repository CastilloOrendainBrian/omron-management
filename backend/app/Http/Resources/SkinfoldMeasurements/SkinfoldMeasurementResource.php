<?php

declare(strict_types=1);

namespace App\Http\Resources\SkinfoldMeasurements;

use App\Http\Resources\SkinfoldProtocols\SkinfoldProtocolResource;
use App\Models\SkinfoldMeasurement;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin SkinfoldMeasurement */
final class SkinfoldMeasurementResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'measurement_session_id' => $this->measurement_session_id,
            'skinfold_protocol_id' => $this->skinfold_protocol_id,
            'estimated_body_fat_percentage' => $this->estimated_body_fat_percentage !== null ? (float) $this->estimated_body_fat_percentage : null,
            'details' => SkinfoldMeasurementDetailResource::collection($this->whenLoaded('details')),
            'protocol' => SkinfoldProtocolResource::make($this->whenLoaded('protocol')),
            'created_at' => $this->created_at?->toIso8601String(),
            'updated_at' => $this->updated_at?->toIso8601String(),
        ];
    }
}

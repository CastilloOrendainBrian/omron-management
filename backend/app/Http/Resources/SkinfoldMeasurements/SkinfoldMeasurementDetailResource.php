<?php

declare(strict_types=1);

namespace App\Http\Resources\SkinfoldMeasurements;

use App\Http\Resources\SkinfoldSites\SkinfoldSiteResource;
use App\Models\SkinfoldMeasurementDetail;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin SkinfoldMeasurementDetail */
final class SkinfoldMeasurementDetailResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'skinfold_site_id' => $this->skinfold_site_id,
            'value_mm' => (float) $this->value_mm,
            'site' => SkinfoldSiteResource::make($this->whenLoaded('site')),
        ];
    }
}

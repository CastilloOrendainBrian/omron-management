<?php

declare(strict_types=1);

namespace App\Http\Resources\SkinfoldProtocols;

use App\Models\SkinfoldProtocol;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin SkinfoldProtocol */
final class SkinfoldProtocolResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'sites_count' => $this->sites_count,
            'description' => $this->description,
        ];
    }
}

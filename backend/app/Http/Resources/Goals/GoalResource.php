<?php

declare(strict_types=1);

namespace App\Http\Resources\Goals;

use App\Models\Goal;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin Goal */
final class GoalResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'target_weight_kg' => $this->target_weight_kg !== null ? (float) $this->target_weight_kg : null,
            'target_body_fat_percentage' => $this->target_body_fat_percentage !== null ? (float) $this->target_body_fat_percentage : null,
            'start_date' => $this->start_date?->toDateString(),
            'target_date' => $this->target_date?->toDateString(),
            'status' => $this->status,
            'user' => $this->whenLoaded('user'),
            'created_at' => $this->created_at?->toIso8601String(),
            'updated_at' => $this->updated_at?->toIso8601String(),
        ];
    }
}

<?php

declare(strict_types=1);

namespace App\Http\Resources\UserProfiles;

use App\Models\UserProfile;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin UserProfile */
final class UserProfileResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'sex' => $this->sex,
            'birth_date' => $this->birth_date?->toDateString(),
            'height_reference_cm' => $this->height_reference_cm === null
                ? null
                : (float) $this->height_reference_cm,
            'activity_level' => $this->activity_level,
            'user' => $this->whenLoaded('user'),
            'created_at' => $this->created_at?->toIso8601String(),
            'updated_at' => $this->updated_at?->toIso8601String(),
        ];
    }
}

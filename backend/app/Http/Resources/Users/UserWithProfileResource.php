<?php

declare(strict_types=1);

namespace App\Http\Resources\Users;

use App\Http\Resources\UserProfiles\UserProfileResource;
use App\Http\Resources\Users\UserResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

final class UserWithProfileResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'user' => (new UserResource($this->resource['user']))->toArray($request),
            'profile' => $this->resource['profile'] !== null
                ? (new UserProfileResource($this->resource['profile']))->toArray($request)
                : null,
        ];
    }
}

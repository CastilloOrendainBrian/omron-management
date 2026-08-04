<?php

declare(strict_types=1);

namespace App\Http\Resources\Auth;

use App\Http\Resources\Users\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin User */
final class UserMeResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        /** @var User $user */
        $user = $this->resource;

        return (new UserResource($user))->toArray($request);
    }
}

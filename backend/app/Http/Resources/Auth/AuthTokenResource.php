<?php

declare(strict_types=1);

namespace App\Http\Resources\Auth;

use App\Domain\Auth\DTOs\AuthTokenDTO;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin AuthTokenDTO */
final class AuthTokenResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        /** @var AuthTokenDTO $token */
        $token = $this->resource;

        return [
            'access_token' => $token->plainTextToken,
            'token_type' => 'Bearer',
            'token_id' => $token->tokenId,
            'name' => $token->name,
            'abilities' => $token->abilities,
            'expires_at' => $token->expiresAt?->format(\DateTimeInterface::ATOM),
        ];
    }
}

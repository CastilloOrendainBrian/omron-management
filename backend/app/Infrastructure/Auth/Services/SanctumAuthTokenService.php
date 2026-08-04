<?php

declare(strict_types=1);

namespace App\Infrastructure\Auth\Services;

use App\Domain\Auth\Contracts\AuthTokenServiceInterface;
use App\Domain\Auth\DTOs\AuthTokenDTO;
use App\Models\User;
use DateTimeImmutable;
use Laravel\Sanctum\NewAccessToken;

final class SanctumAuthTokenService implements AuthTokenServiceInterface
{
    public function issue(int $userId, string $name, array $abilities = []): AuthTokenDTO
    {
        $user = User::findOrFail($userId);
        $new = $user->createToken($name, $abilities);

        return $this->toDTO($new, $userId);
    }

    public function revokeCurrent(int $userId, int $tokenId): void
    {
        $user = User::findOrFail($userId);
        $user->tokens()->where('id', $tokenId)->delete();
    }

    public function revokeAll(int $userId): int
    {
        $user = User::findOrFail($userId);

        return $user->tokens()->delete();
    }

    private function toDTO(NewAccessToken $token, int $userId): AuthTokenDTO
    {
        return new AuthTokenDTO(
            userId: $userId,
            tokenId: $token->accessToken->id,
            plainTextToken: $token->plainTextToken,
            name: $token->accessToken->name,
            abilities: $token->accessToken->abilities ?? [],
            expiresAt: $token->accessToken->expires_at?->toDateTimeImmutable(),
        );
    }
}

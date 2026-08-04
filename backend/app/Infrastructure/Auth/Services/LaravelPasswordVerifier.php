<?php

declare(strict_types=1);

namespace App\Infrastructure\Auth\Services;

use App\Domain\Auth\Contracts\PasswordVerifierInterface;
use Illuminate\Support\Facades\Hash;

final class LaravelPasswordVerifier implements PasswordVerifierInterface
{
    public function verify(string $plainPassword, string $hashedPassword): bool
    {
        return Hash::check($plainPassword, $hashedPassword);
    }
}

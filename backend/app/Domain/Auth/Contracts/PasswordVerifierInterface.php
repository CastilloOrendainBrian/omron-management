<?php

declare(strict_types=1);

namespace App\Domain\Auth\Contracts;

interface PasswordVerifierInterface
{
    public function verify(string $plainPassword, string $hashedPassword): bool;
}

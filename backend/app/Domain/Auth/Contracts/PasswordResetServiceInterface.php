<?php

declare(strict_types=1);

namespace App\Domain\Auth\Contracts;

interface PasswordResetServiceInterface
{
    /**
     * Send a password reset link to the given email.
     * Always returns silently to avoid leaking which emails are registered.
     */
    public function requestLink(string $email): void;

    /**
     * @return bool true if the password was reset, false if the token is invalid/expired
     */
    public function reset(string $email, string $token, string $newPassword): bool;
}

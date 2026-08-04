<?php

declare(strict_types=1);

namespace App\Infrastructure\Auth\Services;

use App\Domain\Auth\Contracts\PasswordResetServiceInterface;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Support\Facades\Password;

final class LaravelPasswordResetService implements PasswordResetServiceInterface
{
    public function requestLink(string $email): void
    {
        ResetPassword::createUrlUsing(
            static fn ($user, string $token): string => url("/api/auth/reset-password?token={$token}&email=".urlencode($user->getEmailForPasswordReset()))
        );

        $status = Password::sendResetLink(['email' => $email]);

        if ($status !== Password::RESET_LINK_SENT && $status !== Password::INVALID_USER) {
            // Throttled or mail failure: surface as a runtime exception so the
            // controller can return a 500 with a meaningful message.
            throw new \RuntimeException("Password reset link could not be sent: {$status}");
        }
    }

    public function reset(string $email, string $token, string $newPassword): bool
    {
        $status = Password::reset(
            credentials: [
                'email' => $email,
                'password' => $newPassword,
                'password_confirmation' => $newPassword,
                'token' => $token,
            ],
            callback: function ($user, string $password): void {
                $user->forceFill([
                    'password' => \Illuminate\Support\Facades\Hash::make($password),
                ])->save();
            },
        );

        return $status === Password::PASSWORD_RESET;
    }
}

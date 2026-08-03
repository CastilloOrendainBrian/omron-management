<?php

declare(strict_types=1);

namespace App\Providers;

use App\Domain\User\Contracts\UserRepositoryInterface;
use App\Infrastructure\User\Repositories\EloquentUserRepository;
use Illuminate\Support\ServiceProvider;

final class RepositoryServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(UserRepositoryInterface::class, EloquentUserRepository::class);
    }
}

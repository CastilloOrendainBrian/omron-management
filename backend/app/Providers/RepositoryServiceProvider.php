<?php

declare(strict_types=1);

namespace App\Providers;

use App\Domain\AnthropometricMeasurement\Contracts\AnthropometricMeasurementRepositoryInterface;
use App\Domain\BodyCompositionMeasurement\Contracts\BodyCompositionMeasurementRepositoryInterface;
use App\Domain\Device\Contracts\DeviceRepositoryInterface;
use App\Domain\Goal\Contracts\GoalRepositoryInterface;
use App\Domain\SkinfoldMeasurement\Contracts\SkinfoldMeasurementRepositoryInterface;
use App\Domain\SkinfoldProtocol\Contracts\SkinfoldProtocolRepositoryInterface;
use App\Domain\SkinfoldSite\Contracts\SkinfoldSiteRepositoryInterface;
use App\Domain\User\Contracts\UserRepositoryInterface;
use App\Domain\UserProfile\Contracts\UserProfileRepositoryInterface;
use App\Infrastructure\AnthropometricMeasurement\Repositories\EloquentAnthropometricMeasurementRepository;
use App\Infrastructure\BodyCompositionMeasurement\Repositories\EloquentBodyCompositionMeasurementRepository;
use App\Infrastructure\Device\Repositories\EloquentDeviceRepository;
use App\Infrastructure\Goal\Repositories\EloquentGoalRepository;
use App\Infrastructure\SkinfoldMeasurement\Repositories\EloquentSkinfoldMeasurementRepository;
use App\Infrastructure\SkinfoldProtocol\Repositories\EloquentSkinfoldProtocolRepository;
use App\Infrastructure\SkinfoldSite\Repositories\EloquentSkinfoldSiteRepository;
use App\Infrastructure\User\Repositories\EloquentUserRepository;
use App\Infrastructure\UserProfile\Repositories\EloquentUserProfileRepository;
use Illuminate\Support\ServiceProvider;

final class RepositoryServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(UserRepositoryInterface::class, EloquentUserRepository::class);
        $this->app->bind(UserProfileRepositoryInterface::class, EloquentUserProfileRepository::class);
        $this->app->bind(SkinfoldSiteRepositoryInterface::class, EloquentSkinfoldSiteRepository::class);
        $this->app->bind(SkinfoldProtocolRepositoryInterface::class, EloquentSkinfoldProtocolRepository::class);
        $this->app->bind(SkinfoldMeasurementRepositoryInterface::class, EloquentSkinfoldMeasurementRepository::class);
        $this->app->bind(AnthropometricMeasurementRepositoryInterface::class, EloquentAnthropometricMeasurementRepository::class);
        $this->app->bind(BodyCompositionMeasurementRepositoryInterface::class, EloquentBodyCompositionMeasurementRepository::class);
        $this->app->bind(DeviceRepositoryInterface::class, EloquentDeviceRepository::class);
        $this->app->bind(GoalRepositoryInterface::class, EloquentGoalRepository::class);
    }
}

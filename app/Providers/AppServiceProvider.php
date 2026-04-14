<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;


use App\Domain\Permission\Repositories\PermissionRepositoryInterface;
use App\Domain\Role\Repositories\RoleRepositoryInterface;
use App\Domain\User\Repositories\UserRepositoryInterface;

use App\Domain\Auth\Services\TokenManagerInterface;
use App\Shared\Domain\Auth\CurrentTokenProviderInterface; // 👈 NUEVO

use App\Infrastructure\Auth\Repositories\AuthUserRepository;
use App\Infrastructure\Permission\Repositories\PermissionRepository;
use App\Infrastructure\Role\Repositories\RoleRepository;
use App\Infrastructure\User\Repositories\UserRepository;

use App\Infrastructure\Auth\Services\JwtTokenManager;              // 👈 ya lo tenías
use App\Infrastructure\Auth\Services\HttpJwtCurrentTokenProvider; // 👈 NUEVO
use App\Domain\Category\Repositories\CategoryRepositoryInterface;
use App\Infrastructure\Category\Repositories\CategoryRepository;
use App\Domain\Laboratory\Repositories\LaboratoryRepositoryInterface;
use App\Infrastructure\Laboratory\Repositories\LaboratoryRepository;
use App\Domain\Presentation\Repositories\PresentationRepositoryInterface;
use App\Infrastructure\Presentation\Repositories\PresentationRepository;
use App\Domain\PharmaceuticalForm\Repositories\PharmaceuticalFormRepositoryInterface;
use App\Infrastructure\PharmaceuticalForm\Repositories\PharmaceuticalFormRepository;
use App\Domain\UnitOfMeasure\Repositories\UnitOfMeasureRepositoryInterface;
use App\Infrastructure\UnitOfMeasure\Repositories\UnitOfMeasureRepository;



class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Auth repository
        //$this->app->bind(AuthRepositoryInterface::class, AuthUserRepository::class);

        // Permission repository
        $this->app->bind(PermissionRepositoryInterface::class, PermissionRepository::class);

        // Role repository
        $this->app->bind(RoleRepositoryInterface::class, RoleRepository::class);

        // User repository
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);

        // Category repository
        $this->app->bind(CategoryRepositoryInterface::class, CategoryRepository::class);

        // Laboratory repository
        $this->app->bind(LaboratoryRepositoryInterface::class, LaboratoryRepository::class);

        // Presentation repository
        $this->app->bind(PresentationRepositoryInterface::class, PresentationRepository::class);

        // PharmaceuticalForm repository
        $this->app->bind(PharmaceuticalFormRepositoryInterface::class, PharmaceuticalFormRepository::class);

        // UnitOfMeasure repository
        $this->app->bind(UnitOfMeasureRepositoryInterface::class, UnitOfMeasureRepository::class);

        // Token manager (Domain → Infrastructure)
        $this->app->bind(TokenManagerInterface::class, JwtTokenManager::class);

        // Proveedor del token actual (HTTP / JWT)
        $this->app->bind(
            CurrentTokenProviderInterface::class,
            HttpJwtCurrentTokenProvider::class
        );
    }

    public function boot(): void
    {
        //
    }
}

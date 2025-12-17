<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use App\Domain\Auth\Repositories\AuthRepositoryInterface;
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

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Auth repository
        $this->app->bind(AuthRepositoryInterface::class, AuthUserRepository::class);

        // Permission repository
        $this->app->bind(PermissionRepositoryInterface::class, PermissionRepository::class);

        // Role repository
        $this->app->bind(RoleRepositoryInterface::class, RoleRepository::class);

        // User repository
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);

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

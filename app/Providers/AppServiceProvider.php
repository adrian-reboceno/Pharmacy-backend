<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Domain\User\Repositories\UserRepositoryInterface;
use App\Infrastructure\User\Repositories\UserRepository;

use App\Domain\Permission\Repositories\PermissionRepositoryInterface;
use App\Infrastructure\Repositories\PermissionRepository;

use App\Domain\Role\Repositories\RoleRepositoryInterface;
use App\Infrastructure\Repositories\RoleRepository;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
        // Vincula la interfaz con la implementación concreta
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
        $this->app->bind(PermissionRepositoryInterface::class, PermissionRepository::class);
        $this->app->bind(RoleRepositoryInterface::class, RoleRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}

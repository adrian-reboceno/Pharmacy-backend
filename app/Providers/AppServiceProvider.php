<?php

namespace App\Providers;

use App\Domain\Auth\Repositories\AuthRepositoryInterface;
use App\Domain\Permission\Repositories\PermissionRepositoryInterface;
use App\Domain\Role\Repositories\RoleRepositoryInterface;
use App\Domain\User\Repositories\UserRepositoryInterface;
use App\Infrastructure\Auth\Repositories\AuthUserRepository;
use App\Infrastructure\Permission\Repositories\PermissionRepository;
use App\Infrastructure\Role\Repositories\RoleRepository;
use App\Infrastructure\User\Repositories\UserRepository;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services and bindings.
     *
     * This method binds interfaces to their concrete implementations,
     * ensuring that the Dependency Injection Container can resolve
     * the appropriate classes throughout the application.
     */
    public function register(): void
    {
        // Bind Auth repository
        $this->app->bind(AuthRepositoryInterface::class, AuthUserRepository::class);

        // Bind Permission repository
        $this->app->bind(PermissionRepositoryInterface::class, PermissionRepository::class);

        // Bind Role repository
        $this->app->bind(RoleRepositoryInterface::class, RoleRepository::class);

        // Bind User repository
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
    }

    /**
     * Bootstrap any application services.
     *
     * This method is called after all services have been registered.
     * It can be used to configure or initialize services as needed.
     */
    public function boot(): void
    {
        //
    }
}

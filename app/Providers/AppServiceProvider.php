<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Domain\Auth\Repositories\AuthRepositoryInterface;
use App\Infrastructure\Auth\Repositories\AuthUserRepository;

use App\Domain\Permission\Repositories\PermissionRepositoryInterface;
use App\Infrastructure\Repositories\PermissionRepository;

use App\Domain\Role\Repositories\RoleRepositoryInterface;
use App\Infrastructure\Repositories\RoleRepository;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services and bindings.
     *
     * This method binds interfaces to their concrete implementations,
     * ensuring that the Dependency Injection Container can resolve 
     * the appropriate classes throughout the application.
     *
     * @return void
     */
    public function register(): void
    {
        // Bind Auth repository
        $this->app->bind(AuthRepositoryInterface::class, AuthUserRepository::class);

        // Bind Permission repository
        $this->app->bind(PermissionRepositoryInterface::class, PermissionRepository::class);

        // Bind Role repository
        $this->app->bind(RoleRepositoryInterface::class, RoleRepository::class);
    }

    /**
     * Bootstrap any application services.
     *
     * This method is called after all services have been registered.
     * It can be used to configure or initialize services as needed.
     *
     * @return void
     */
    public function boot(): void
    {
        //
    }
}

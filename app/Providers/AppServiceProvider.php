<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Domain\User\Repositories\UserRepositoryInterface;
use App\Infrastructure\User\Repositories\UserRepository;

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
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}

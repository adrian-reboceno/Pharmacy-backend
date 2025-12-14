<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    public const HOME = '/home';

    public function boot(): void
    {
        $this->routes(function () {
            // API versionadas dinámicamente
            foreach ($this->getApiVersions() as $version) {
                $file = base_path("routes/api/{$version}.php");

                if (file_exists($file)) {
                    Route::prefix("api/{$version}")
                        ->middleware('api')
                        ->group($file);
                }
            }

            // Web routes
            Route::middleware('web')
                ->group(base_path('routes/web.php'));
        });
    }

    /**
     * Devuelve automáticamente las versiones de API detectadas
     * (ej: v1.php, v2.php en /routes/api).
     */
    protected function getApiVersions(): array
    {
        return collect(glob(base_path('routes/api/v*.php')))
            ->map(fn ($file) => basename($file, '.php'))
            ->values()
            ->toArray();
    }
}

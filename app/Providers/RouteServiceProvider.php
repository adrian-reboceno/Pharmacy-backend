<?php

namespace App\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * Path to the "home" route for your application.
     *
     * @var string
     */
    public const HOME = '/home';

    /**
     * Define your route model bindings, pattern filters, etc.
     */
    public function boot(): void
    {
        $this->configureRateLimiting();

        $this->routes(function () {
            // API versionadas
            foreach ($this->getApiVersions() as $version) {
                Route::prefix("api/{$version}")
                    ->middleware('api')
                    ->namespace($this->namespaceForVersion($version))
                    ->group(base_path("routes/api/{$version}.php"));
            }

            // Web routes
            Route::middleware('web')
                ->group(base_path('routes/web.php'));
        });
    }

    /**
     * Devuelve un array con las versiones de la API disponibles.
     */
    protected function getApiVersions(): array
    {
        // Puedes agregar nuevas versiones aquí sin tocar la lógica principal
        return ['v1'];
    }

    /**
     * Devuelve el namespace del controlador para cada versión de API.
     */
    protected function namespaceForVersion(string $version): ?string
    {
        return "App\\Http\\Controllers\\Api\\" . strtoupper($version);
    }

    /**
     * Configuración de rate limiting (opcional)
     */
    protected function configureRateLimiting(): void
    {
        // Por ejemplo, 60 requests por minuto por usuario/IP
        // RateLimiter::for('api', function (Request $request) {
        //     return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        // });
    }
}
